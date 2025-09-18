<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('slug_ar')->unique();
            $table->string('slug_en')->unique();
            $table->string('region_code', 10);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0);
            $table->integer('services_count')->default(0);
            $table->json('meta')->nullable(); // Additional city data
            $table->timestamps();
            
            $table->index(['is_active', 'priority']);
            $table->index('region_code');
            $table->index(['latitude', 'longitude']);
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('slug_ar')->unique();
            $table->string('slug_en')->unique();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('icon')->nullable();
            $table->string('color', 7)->default('#3B82F6');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('services_count')->default(0);
            $table->json('custom_fields')->nullable(); // Dynamic form fields
            $table->json('seo_meta')->nullable();
            $table->timestamps();
            
            $table->index(['parent_id', 'is_active', 'sort_order']);
            $table->index('slug_ar');
            $table->index('slug_en');
        });

        Schema::create('category_city', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('category_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('city_id')->constrained()->cascadeOnDelete();
            $table->integer('services_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['category_id', 'city_id']);
            $table->index('services_count');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_city');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('cities');
    }
};
