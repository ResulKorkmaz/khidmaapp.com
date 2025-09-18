<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CitiesController extends BaseController
{
    /**
     * Display a listing of cities
     */
    public function index(Request $request): JsonResponse
    {
        $query = City::query()->active();

        // Filter by region
        if ($request->filled('region')) {
            $query->byRegion($request->region);
        }

        // Search by name
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name_ar', 'LIKE', "%{$search}%")
                  ->orWhere('name_en', 'LIKE', "%{$search}%");
            });
        }

        // Filter nearby cities
        if ($request->filled('lat') && $request->filled('lng')) {
            $radius = $request->radius ?? 100; // Default 100km radius
            $query->nearby($request->lat, $request->lng, $radius);
        }

        // Include only cities with services
        if ($request->boolean('with_services')) {
            $query->withServices();
        }

        // Order by priority and name
        $cities = $query->byPriority()->get();

        $response = $this->success(
            CityResource::collection($cities),
            'Cities retrieved successfully'
        );

        // Cache for 1 hour
        return $this->cached($response, 3600);
    }

    /**
     * Display the specified city
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        $city = City::where('slug_ar', $slug)
                   ->orWhere('slug_en', $slug)
                   ->active()
                   ->first();

        if (!$city) {
            return $this->notFound('City not found');
        }

        $response = $this->resource(
            new CityResource($city),
            'City retrieved successfully'
        );

        // Cache for 2 hours
        return $this->cached($response, 7200);
    }

    /**
     * Get popular cities (with most services)
     */
    public function popular(Request $request): JsonResponse
    {
        $cities = City::query()
            ->active()
            ->withServices()
            ->orderBy('services_count', 'desc')
            ->orderBy('priority', 'desc')
            ->limit($request->limit ?? 12)
            ->get();

        $response = $this->success(
            CityResource::collection($cities),
            'Popular cities retrieved successfully'
        );

        // Cache for 2 hours
        return $this->cached($response, 7200);
    }

    /**
     * Get cities by region
     */
    public function byRegion(Request $request, string $regionCode): JsonResponse
    {
        $cities = City::query()
            ->active()
            ->byRegion(strtoupper($regionCode))
            ->byPriority()
            ->get();

        if ($cities->isEmpty()) {
            return $this->notFound('No cities found for this region');
        }

        $response = $this->success(
            CityResource::collection($cities),
            "Cities in region {$regionCode} retrieved successfully"
        );

        // Cache for 2 hours
        return $this->cached($response, 7200);
    }

    /**
     * Get top categories for a city
     */
    public function categories(Request $request, string $slug): JsonResponse
    {
        $city = City::where('slug_ar', $slug)
                   ->orWhere('slug_en', $slug)
                   ->active()
                   ->first();

        if (!$city) {
            return $this->notFound('City not found');
        }

        $categories = $city->getTopCategories($request->limit ?? 12);

        $response = $this->success(
            $categories,
            "Top categories for {$city->name} retrieved successfully"
        );

        // Cache for 1 hour
        return $this->cached($response, 3600);
    }
}
