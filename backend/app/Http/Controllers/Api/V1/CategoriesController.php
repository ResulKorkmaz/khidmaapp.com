<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriesController extends BaseController
{
    /**
     * Get all categories
     */
    public function index(Request $request): JsonResponse
    {
        $categories = Category::active()
            ->select(['id', 'name', 'name_en', 'slug', 'icon', 'parent_id', 'sort_order'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return $this->success($categories);
    }

    /**
     * Get root categories (no parent)
     */
    public function roots(Request $request): JsonResponse
    {
        $categories = Category::active()
            ->whereNull('parent_id')
            ->select(['id', 'name', 'name_en', 'slug', 'icon', 'sort_order'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return $this->success($categories);
    }

    /**
     * Get categories as tree structure
     */
    public function tree(Request $request): JsonResponse
    {
        $categories = Category::active()
            ->select(['id', 'name', 'name_en', 'slug', 'icon', 'parent_id', 'sort_order'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('parent_id');

        $tree = $this->buildTree($categories, null);

        return $this->success($tree);
    }

    /**
     * Get popular categories
     */
    public function popular(Request $request): JsonResponse
    {
        $categories = Category::active()
            ->where('is_popular', true)
            ->select(['id', 'name', 'name_en', 'slug', 'icon', 'sort_order'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return $this->success($categories);
    }

    /**
     * Search categories
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');
        
        if (empty($query)) {
            return $this->success([]);
        }

        $categories = Category::active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('name_en', 'like', "%{$query}%")
                  ->orWhere('keywords', 'like', "%{$query}%");
            })
            ->select(['id', 'name', 'name_en', 'slug', 'icon'])
            ->orderBy('name')
            ->limit(20)
            ->get();

        return $this->success($categories);
    }

    /**
     * Get categories by city
     */
    public function byCity(Request $request, string $citySlug): JsonResponse
    {
        // TODO: Implement city-specific categories
        return $this->roots($request);
    }

    /**
     * Get single category
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        $category = Category::active()
            ->where('slug', $slug)
            ->first();

        if (!$category) {
            return $this->notFound('Kategori bulunamadı');
        }

        return $this->success($category);
    }

    /**
     * Build tree structure from flat array
     */
    private function buildTree($categories, $parentId)
    {
        $tree = [];
        $items = $categories->get($parentId, collect());

        foreach ($items as $item) {
            $node = $item->toArray();
            $children = $this->buildTree($categories, $item->id);
            if (!empty($children)) {
                $node['children'] = $children;
            }
            $tree[] = $node;
        }

        return $tree;
    }
}