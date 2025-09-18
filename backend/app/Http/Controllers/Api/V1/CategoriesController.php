<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoriesController extends BaseController
{
    /**
     * Display a listing of categories
     */
    public function index(Request $request): JsonResponse
    {
        $query = Category::query()->active();

        // Filter by parent category
        if ($request->filled('parent')) {
            if ($request->parent === 'root') {
                $query->rootCategories();
            } else {
                $parent = Category::where('slug_ar', $request->parent)
                                ->orWhere('slug_en', $request->parent)
                                ->first();
                if ($parent) {
                    $query->where('parent_id', $parent->id);
                }
            }
        }

        // Search by name
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name_ar', 'LIKE', "%{$search}%")
                  ->orWhere('name_en', 'LIKE', "%{$search}%")
                  ->orWhere('description_ar', 'LIKE', "%{$search}%")
                  ->orWhere('description_en', 'LIKE', "%{$search}%");
            });
        }

        // Include only categories with services
        if ($request->boolean('with_services')) {
            $query->withServices();
        }

        // Include children if requested
        if ($request->boolean('include_children')) {
            $query->with('children');
        }

        // Order by sort order and name
        $categories = $query->ordered()->get();

        $response = $this->success(
            CategoryResource::collection($categories),
            'Categories retrieved successfully'
        );

        // Cache for 2 hours
        return $this->cached($response, 7200);
    }

    /**
     * Display the specified category
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        $category = Category::where('slug_ar', $slug)
                          ->orWhere('slug_en', $slug)
                          ->active()
                          ->with(['parent', 'children'])
                          ->first();

        if (!$category) {
            return $this->notFound('Category not found');
        }

        $response = $this->resource(
            new CategoryResource($category),
            'Category retrieved successfully'
        );

        // Cache for 4 hours
        return $this->cached($response, 14400);
    }

    /**
     * Get root categories (main categories)
     */
    public function roots(Request $request): JsonResponse
    {
        $categories = Category::query()
            ->active()
            ->rootCategories()
            ->ordered();

        // Include children if requested
        if ($request->boolean('include_children')) {
            $categories->with(['children' => function($query) {
                $query->active()->ordered();
            }]);
        }

        $categories = $categories->get();

        $response = $this->success(
            CategoryResource::collection($categories),
            'Root categories retrieved successfully'
        );

        // Cache for 4 hours
        return $this->cached($response, 14400);
    }

    /**
     * Get popular categories (with most services)
     */
    public function popular(Request $request): JsonResponse
    {
        $categories = Category::query()
            ->active()
            ->withServices()
            ->orderBy('services_count', 'desc')
            ->orderBy('sort_order')
            ->limit($request->limit ?? 12)
            ->get();

        $response = $this->success(
            CategoryResource::collection($categories),
            'Popular categories retrieved successfully'
        );

        // Cache for 2 hours
        return $this->cached($response, 7200);
    }

    /**
     * Get category tree (hierarchical structure)
     */
    public function tree(Request $request): JsonResponse
    {
        $categories = Category::query()
            ->active()
            ->with(['children' => function($query) {
                $query->active()->ordered()->with(['children' => function($subQuery) {
                    $subQuery->active()->ordered();
                }]);
            }])
            ->rootCategories()
            ->ordered()
            ->get();

        $response = $this->success(
            CategoryResource::collection($categories),
            'Category tree retrieved successfully'
        );

        // Cache for 6 hours
        return $this->cached($response, 21600);
    }

    /**
     * Get categories for a specific city
     */
    public function byCity(Request $request, string $citySlug): JsonResponse
    {
        $city = \App\Models\City::where('slug_ar', $citySlug)
                               ->orWhere('slug_en', $citySlug)
                               ->active()
                               ->first();

        if (!$city) {
            return $this->notFound('City not found');
        }

        $categories = $city->categories()
            ->wherePivot('is_active', true)
            ->wherePivot('services_count', '>', 0)
            ->orderByPivot('services_count', 'desc')
            ->get();

        $response = $this->success(
            CategoryResource::collection($categories),
            "Categories for {$city->name} retrieved successfully"
        );

        // Cache for 1 hour
        return $this->cached($response, 3600);
    }

    /**
     * Search categories with autocomplete
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->q;
        
        if (!$query || strlen($query) < 2) {
            return $this->error('Search query must be at least 2 characters', 400);
        }

        $categories = Category::query()
            ->active()
            ->where(function($q) use ($query) {
                $q->where('name_ar', 'LIKE', "%{$query}%")
                  ->orWhere('name_en', 'LIKE', "%{$query}%");
            })
            ->orderBy('services_count', 'desc')
            ->limit(10)
            ->get(['id', 'name_ar', 'name_en', 'slug_ar', 'slug_en', 'icon', 'services_count']);

        $results = $categories->map(function ($category) {
            $locale = app()->getLocale();
            return [
                'id' => $category->id,
                'name' => $category->{"name_{$locale}"} ?? $category->name_ar,
                'slug' => $category->{"slug_{$locale}"} ?? $category->slug_ar,
                'icon' => $category->icon,
                'services_count' => $category->services_count,
            ];
        });

        $response = $this->success(
            $results,
            'Categories search results retrieved successfully'
        );

        // Cache for 30 minutes
        return $this->cached($response, 1800);
    }
}
