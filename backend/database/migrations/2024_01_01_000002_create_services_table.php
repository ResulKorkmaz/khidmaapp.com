<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('category_id')->constrained();
            $table->foreignUuid('city_id')->constrained();
            
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->text('description_ar');
            $table->text('description_en')->nullable();
            $table->string('slug_ar')->unique();
            $table->string('slug_en')->nullable()->unique();
            
            $table->json('images')->nullable();
            $table->decimal('budget_min', 10, 2)->nullable();
            $table->decimal('budget_max', 10, 2)->nullable();
            $table->string('budget_currency', 3)->default('SAR');
            
            $table->enum('urgency', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->date('preferred_date')->nullable();
            $table->time('preferred_time')->nullable();
            
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('address_ar')->nullable();
            $table->string('address_en')->nullable();
            
            $table->enum('status', ['draft', 'active', 'closed', 'completed', 'cancelled'])->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->integer('views_count')->default(0);
            $table->integer('bids_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->json('custom_fields')->nullable();
            $table->json('seo_meta')->nullable();
            
            $table->timestamps();
            
            $table->index(['status', 'is_featured', 'created_at']);
            $table->index(['city_id', 'category_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['latitude', 'longitude']);
            $table->index('expires_at');
        });

        Schema::create('service_bids', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('service_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('provider_id')->constrained('users')->cascadeOnDelete();
            
            $table->text('message_ar');
            $table->text('message_en')->nullable();
            $table->decimal('price_min', 10, 2);
            $table->decimal('price_max', 10, 2)->nullable();
            $table->string('currency', 3)->default('SAR');
            $table->integer('eta_days')->nullable();
            $table->json('attachments')->nullable();
            
            $table->enum('status', ['pending', 'accepted', 'rejected', 'withdrawn'])->default('pending');
            $table->timestamp('accepted_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            
            $table->unique(['service_id', 'provider_id']);
            $table->index(['provider_id', 'status']);
            $table->index(['service_id', 'status', 'created_at']);
        });

        Schema::create('service_reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('service_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('provider_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('customer_id')->constrained('users')->cascadeOnDelete();
            
            $table->integer('rating')->comment('1-5 stars');
            $table->text('comment_ar')->nullable();
            $table->text('comment_en')->nullable();
            $table->json('images')->nullable();
            
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('verified_at')->nullable();
            
            $table->timestamps();
            
            $table->unique(['service_id', 'customer_id']);
            $table->index(['provider_id', 'rating']);
            $table->index(['is_verified', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_reviews');
        Schema::dropIfExists('service_bids');
        Schema::dropIfExists('services');
    }
};
