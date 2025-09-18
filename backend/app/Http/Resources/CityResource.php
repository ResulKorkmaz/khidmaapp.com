<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'region_code' => $this->region_code,
            'region_name' => $this->region_name,
            'coordinates' => $this->coordinates,
            'services_count' => $this->services_count,
            'is_active' => $this->is_active,
            'priority' => $this->priority,
            'meta' => $this->meta,
            
            // Conditional includes
            'top_categories' => $this->when(
                $request->has('include_categories'), 
                fn() => $this->getTopCategories()
            ),
            
            'providers_count' => $this->when(
                $request->has('include_stats'),
                fn() => $this->providers()->count()
            ),
        ];
    }

    public function with(Request $request): array
    {
        return [
            'links' => [
                'self' => route('api.v1.cities.show', $this->slug),
                'services' => route('api.v1.services.index') . "?city={$this->slug}",
            ]
        ];
    }
}
