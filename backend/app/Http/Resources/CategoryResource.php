<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'slug' => $this->slug,
            'slug_ar' => $this->slug_ar,
            'slug_en' => $this->slug_en,
            'description' => $this->description,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'icon' => $this->icon,
            'icon_url' => $this->icon_url,
            'color' => $this->color,
            'sort_order' => $this->sort_order,
            'services_count' => $this->services_count,
            'is_active' => $this->is_active,
            'full_name' => $this->full_name,
            'breadcrumb' => $this->breadcrumb,
            
            // Parent category
            'parent' => $this->when(
                $this->parent_id && $this->relationLoaded('parent'),
                fn() => new CategoryResource($this->parent)
            ),
            
            // Child categories
            'children' => $this->when(
                $request->has('include_children') && $this->relationLoaded('children'),
                fn() => CategoryResource::collection($this->children()->active()->ordered()->get())
            ),
            
            // Custom fields schema
            'custom_fields' => $this->when(
                $request->has('include_fields'),
                fn() => $this->custom_fields
            ),
            
            // SEO meta
            'seo_meta' => $this->when(
                $request->has('include_seo'),
                fn() => $this->seo_meta
            ),
        ];
    }

    public function with(Request $request): array
    {
        return [
            'links' => [
                'self' => route('api.v1.categories.show', $this->slug),
                'services' => route('api.v1.services.index') . "?category={$this->slug}",
                'parent' => $this->when(
                    $this->parent_id,
                    fn() => route('api.v1.categories.show', $this->parent?->slug)
                ),
            ]
        ];
    }
}
