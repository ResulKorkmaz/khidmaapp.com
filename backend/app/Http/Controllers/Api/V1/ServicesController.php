<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ServicesController extends BaseController
{
    /**
     * Display a listing of services
     */
    public function index(Request $request): JsonResponse
    {
        $query = Service::query()
            ->with(['customer', 'category', 'city'])
            ->active()
            ->latest();

        // Filter by city
        if ($request->filled('city')) {
            $city = City::where('slug_ar', $request->city)
                       ->orWhere('slug_en', $request->city)
                       ->first();
            if ($city) {
                $query->where('city_id', $city->id);
            }
        }

        // Filter by category
        if ($request->filled('category')) {
            $category = Category::where('slug_ar', $request->category)
                               ->orWhere('slug_en', $request->category)
                               ->first();
            if ($category) {
                // Include subcategories
                $categoryIds = [$category->id];
                $categoryIds = array_merge($categoryIds, $category->getAllDescendants());
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Search by keyword
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('title_ar', 'LIKE', "%{$search}%")
                  ->orWhere('title_en', 'LIKE', "%{$search}%")
                  ->orWhere('description_ar', 'LIKE', "%{$search}%")
                  ->orWhere('description_en', 'LIKE', "%{$search}%");
            });
        }

        // Filter by budget range
        if ($request->filled('budget_min') || $request->filled('budget_max')) {
            $query->withBudget(
                $request->budget_min,
                $request->budget_max
            );
        }

        // Filter by urgency
        if ($request->filled('urgency')) {
            $query->byUrgency($request->urgency);
        }

        // Filter by location (nearby services)
        if ($request->filled('lat') && $request->filled('lng')) {
            $radius = $request->radius ?? 25; // Default 25km radius
            $query->nearby($request->lat, $request->lng, $radius);
        }

        // Filter recent services
        if ($request->filled('recent_days')) {
            $query->recent($request->recent_days);
        }

        // Sort options
        $sortBy = $request->sort_by ?? 'created_at';
        $sortDir = $request->sort_dir ?? 'desc';

        switch ($sortBy) {
            case 'budget_min':
            case 'budget_max':
            case 'views_count':
            case 'bids_count':
                $query->orderBy($sortBy, $sortDir);
                break;
            case 'distance':
                // Already ordered by distance if lat/lng provided
                break;
            default:
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = min($request->per_page ?? 20, 100); // Max 100 per page
        $services = $query->paginate($perPage);

        $response = $this->collection(
            ServiceResource::collection($services),
            'Services retrieved successfully'
        );

        // Cache for 5 minutes
        return $this->cached($response, 300);
    }

    /**
     * Display the specified service
     */
    public function show(Request $request, string $slugOrId): JsonResponse
    {
        $service = Service::query()
            ->with(['customer.profile', 'category', 'city', 'acceptedBid.provider', 'review'])
            ->where('slug_ar', $slugOrId)
            ->orWhere('slug_en', $slugOrId)
            ->orWhere('id', $slugOrId)
            ->first();

        if (!$service) {
            return $this->notFound('Service not found');
        }

        // Increment view count (async job would be better)
        $service->incrementViewsCount();

        $response = $this->resource(
            new ServiceResource($service),
            'Service retrieved successfully'
        );

        // Cache for 10 minutes
        return $this->cached($response, 600);
    }

    /**
     * Get featured services
     */
    public function featured(Request $request): JsonResponse
    {
        $services = Service::query()
            ->with(['customer', 'category', 'city'])
            ->active()
            ->featured()
            ->latest()
            ->limit($request->limit ?? 12)
            ->get();

        $response = $this->success(
            ServiceResource::collection($services),
            'Featured services retrieved successfully'
        );

        // Cache for 15 minutes
        return $this->cached($response, 900);
    }

    /**
     * Get popular services (most viewed/bid on)
     */
    public function popular(Request $request): JsonResponse
    {
        $period = $request->period ?? 'week'; // week, month, all_time
        
        $query = Service::query()
            ->with(['customer', 'category', 'city'])
            ->active();

        switch ($period) {
            case 'week':
                $query->where('created_at', '>=', now()->subWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', now()->subMonth());
                break;
            // all_time has no date filter
        }

        $services = $query
            ->orderByRaw('(views_count + bids_count * 2) DESC')
            ->limit($request->limit ?? 12)
            ->get();

        $response = $this->success(
            ServiceResource::collection($services),
            'Popular services retrieved successfully'
        );

        // Cache for 30 minutes
        return $this->cached($response, 1800);
    }

    /**
     * Get recent services
     */
    public function recent(Request $request): JsonResponse
    {
        $days = $request->days ?? 7;
        
        $services = Service::query()
            ->with(['customer', 'category', 'city'])
            ->active()
            ->recent($days)
            ->limit($request->limit ?? 12)
            ->get();

        $response = $this->success(
            ServiceResource::collection($services),
            'Recent services retrieved successfully'
        );

        // Cache for 5 minutes
        return $this->cached($response, 300);
    }

    /**
     * Get services by city and category for SEO pages
     */
    public function byCityAndCategory(Request $request, string $citySlug, string $categorySlug): JsonResponse
    {
        $city = City::where('slug_ar', $citySlug)
                   ->orWhere('slug_en', $citySlug)
                   ->first();

        $category = Category::where('slug_ar', $categorySlug)
                           ->orWhere('slug_en', $categorySlug)
                           ->first();

        if (!$city || !$category) {
            return $this->notFound('City or category not found');
        }

        $services = Service::query()
            ->with(['customer', 'category', 'city'])
            ->active()
            ->where('city_id', $city->id)
            ->where('category_id', $category->id)
            ->latest()
            ->paginate($request->per_page ?? 20);

        $response = $this->collection(
            ServiceResource::collection($services),
            "Services in {$city->name} for {$category->name} retrieved successfully"
        );

        // Add extra meta for SEO
        $response = $response->getData(true);
        $response['seo'] = [
            'city' => new CityResource($city),
            'category' => new CategoryResource($category),
            'total_services' => $services->total(),
        ];

        // Cache for 30 minutes
        return $this->cached(response()->json($response), 1800);
    }
}
