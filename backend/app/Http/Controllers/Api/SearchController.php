<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    /**
     * Search categories by query
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function categories(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $limit = min($request->get('limit', 8), 20); // Max 20 results
        
        if (empty(trim($query))) {
            // Return popular categories when no query
            $categories = Category::where('is_searchable', true)
                ->where('is_active', true)
                ->orderBy('search_priority', 'desc')
                ->orderBy('services_count', 'desc')
                ->limit($limit)
                ->get();
        } else {
            // Search categories
            $categories = Category::where('is_searchable', true)
                ->where('is_active', true)
                ->where(function($q) use ($query) {
                    $q->where('name_ar', 'LIKE', "%{$query}%")
                      ->orWhere('name_en', 'LIKE', "%{$query}%")
                      ->orWhere('slug_ar', 'LIKE', "%{$query}%")
                      ->orWhere('slug_en', 'LIKE', "%{$query}%");
                      
                    // Search in keywords if exists (PostgreSQL compatible)
                    if (!empty($query)) {
                        $q->orWhereJsonContains('keywords', $query);
                        
                        // Search for partial keyword matches in PostgreSQL
                        $keywords = is_string($query) ? explode(' ', $query) : [$query];
                        foreach ($keywords as $keyword) {
                            if (strlen($keyword) >= 2) {
                                $q->orWhereRaw("keywords::text LIKE ?", ["%{$keyword}%"]);
                            }
                        }
                    }
                })
                ->orderBy('search_priority', 'desc')
                ->orderBy('services_count', 'desc')
                ->limit($limit)
                ->get();
                
            // Increment search count for found categories
            if ($categories->count() > 0) {
                Category::whereIn('id', $categories->pluck('id'))
                    ->increment('search_count');
            }
        }
        
        // Transform response
        $result = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name_ar' => $category->name_ar,
                'name_en' => $category->name_en,
                'slug' => $category->slug_ar,
                'icon' => $category->icon,
                'color' => $category->color,
                'count' => $category->services_count,
                'keywords' => $category->keywords ?? [],
                'is_popular' => $category->search_priority > 5 || $category->services_count > 1000,
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $result,
            'meta' => [
                'query' => $query,
                'count' => $result->count(),
                'limit' => $limit,
            ]
        ]);
    }
    
    /**
     * Search services by query
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function services(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $categoryId = $request->get('category_id');
        $cityId = $request->get('city_id');
        $limit = min($request->get('limit', 20), 50); // Max 50 results
        
        $services = Service::with(['category', 'city', 'user.profile'])
            ->where('status', 'active')
            ->where('is_active', true);
            
        // Apply search filters
        if (!empty(trim($query))) {
            $services->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhereHas('category', function($cat) use ($query) {
                      $cat->where('name_ar', 'LIKE', "%{$query}%")
                          ->orWhere('name_en', 'LIKE', "%{$query}%");
                  });
            });
        }
        
        if ($categoryId) {
            $services->where('category_id', $categoryId);
        }
        
        if ($cityId) {
            $services->where('city_id', $cityId);
        }
        
        // Order by relevance and recency
        $services = $services->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        
        // Transform response
        $result = $services->map(function ($service) {
            return [
                'id' => $service->id,
                'title' => $service->title,
                'description' => $service->description,
                'category' => [
                    'id' => $service->category->id,
                    'name_ar' => $service->category->name_ar,
                    'name_en' => $service->category->name_en,
                ],
                'city' => [
                    'id' => $service->city->id,
                    'name_ar' => $service->city->name_ar,
                    'name_en' => $service->city->name_en,
                ],
                'user' => [
                    'name' => $service->user->profile->first_name ?? 'مستخدم',
                    'avatar' => $service->user->profile->avatar,
                ],
                'created_at' => $service->created_at->diffForHumans(),
                'url' => url("/ar/service/{$service->id}"),
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $result,
            'meta' => [
                'query' => $query,
                'count' => $result->count(),
                'limit' => $limit,
                'filters' => [
                    'category_id' => $categoryId,
                    'city_id' => $cityId,
                ]
            ]
        ]);
    }
    
    /**
     * Get search suggestions
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function suggestions(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'data' => [],
                'meta' => ['query' => $query, 'count' => 0]
            ]);
        }
        
        // Get category suggestions
        $categories = Category::where('is_searchable', true)
            ->where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name_ar', 'LIKE', "%{$query}%")
                  ->orWhere('name_en', 'LIKE', "%{$query}%");
            })
            ->orderBy('search_priority', 'desc')
            ->limit(5)
            ->get(['name_ar', 'name_en', 'slug_ar']);
            
        $suggestions = $categories->map(function($category) {
            return [
                'text' => $category->name_ar,
                'type' => 'category',
                'url' => "/ar/services/{$category->slug_ar}"
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $suggestions,
            'meta' => [
                'query' => $query,
                'count' => $suggestions->count()
            ]
        ]);
    }
}
