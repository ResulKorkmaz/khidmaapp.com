<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Search keywords as JSON for better search capabilities
            $table->json('keywords')->nullable()->after('description_en');
            
            // Search priority for ranking results (higher = better)
            $table->integer('search_priority')->default(0)->after('keywords');
            
            // Whether this category should appear in search results
            $table->boolean('is_searchable')->default(true)->after('search_priority');
            
            // Search count for analytics
            $table->unsignedBigInteger('search_count')->default(0)->after('is_searchable');
            
            // Add index for better performance
            $table->index(['is_searchable', 'search_priority']);
            $table->index('search_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['is_searchable', 'search_priority']);
            $table->dropIndex(['search_count']);
            $table->dropColumn(['keywords', 'search_priority', 'is_searchable', 'search_count']);
        });
    }
};
