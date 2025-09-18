<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'description' => $this->description,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'slug' => $this->slug,
            'slug_ar' => $this->slug_ar,
            'slug_en' => $this->slug_en,
            
            // Media
            'images' => $this->images_urls,
            'featured_image' => $this->featured_image,
            
            // Budget & pricing
            'budget_min' => $this->budget_min,
            'budget_max' => $this->budget_max,
            'budget_currency' => $this->budget_currency,
            'budget_range' => $this->budget_range,
            
            // Location
            'address' => $this->address,
            'coordinates' => $this->getCoordinates(),
            
            // Timing
            'urgency' => $this->urgency,
            'urgency_label' => $this->urgency_label,
            'preferred_date' => $this->preferred_date?->format('Y-m-d'),
            'preferred_time' => $this->preferred_time?->format('H:i'),
            
            // Status
            'status' => $this->status,
            'status_label' => $this->status_label,
            'is_expired' => $this->is_expired,
            'is_completed' => $this->is_completed,
            'days_remaining' => $this->days_remaining,
            
            // Stats
            'views_count' => $this->views_count,
            'bids_count' => $this->bids_count,
            'is_featured' => $this->is_featured,
            
            // Relationships
            'customer' => $this->when(
                $this->relationLoaded('customer'),
                fn() => new UserResource($this->customer)
            ),
            
            'category' => $this->when(
                $this->relationLoaded('category'),
                fn() => new CategoryResource($this->category)
            ),
            
            'city' => $this->when(
                $this->relationLoaded('city'),
                fn() => new CityResource($this->city)
            ),
            
            'accepted_bid' => $this->when(
                $this->relationLoaded('acceptedBid') && $this->acceptedBid,
                fn() => new ServiceBidResource($this->acceptedBid)
            ),
            
            'review' => $this->when(
                $this->relationLoaded('review') && $this->review,
                fn() => new ServiceReviewResource($this->review)
            ),
            
            // Custom fields
            'custom_fields' => $this->when(
                $request->has('include_fields'),
                fn() => $this->custom_fields
            ),
            
            // SEO meta
            'seo_meta' => $this->when(
                $request->has('include_seo'),
                fn() => $this->seo_meta
            ),
            
            // Timestamps
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            'expires_at' => $this->expires_at?->toISOString(),
            'completed_at' => $this->completed_at?->toISOString(),
        ];
    }

    public function with(Request $request): array
    {
        return [
            'links' => [
                'self' => route('api.v1.services.show', $this->slug ?? $this->id),
                'bids' => route('api.v1.services.bids.index', $this->id),
                'category' => route('api.v1.categories.show', $this->category?->slug),
                'city' => route('api.v1.cities.show', $this->city?->slug),
            ]
        ];
    }
}
