<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoriesController;
use App\Http\Controllers\Api\V1\CitiesController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0',
        'environment' => app()->environment(),
    ]);
})->name('api.health');

// API V1 Routes
Route::group(['prefix' => 'v1', 'as' => 'api.v1.'], function () {
    
    // Authentication Routes (No auth required)
    Route::controller(AuthController::class)->group(function () {
        Route::post('/auth/register', 'register')->name('auth.register');
        Route::post('/auth/login', 'login')->name('auth.login');
        Route::post('/auth/phone/otp/send', 'sendPhoneOTP')->name('auth.phone.otp.send');
        Route::post('/auth/phone/otp/verify', 'verifyPhoneOTP')->name('auth.phone.otp.verify');
    });

    // Protected Authentication Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('/auth/logout', 'logout')->name('auth.logout');
            Route::post('/auth/logout-all', 'logoutAll')->name('auth.logout-all');
            Route::get('/auth/me', 'me')->name('auth.me');
            Route::put('/auth/profile', 'updateProfile')->name('auth.profile.update');
            Route::post('/auth/password/change', 'changePassword')->name('auth.password.change');
            Route::post('/auth/token/refresh', 'refreshToken')->name('auth.token.refresh');
        });
    });
    
    // Cities Routes
    Route::controller(CitiesController::class)->group(function () {
        Route::get('/cities', 'index')->name('cities.index');
        Route::get('/cities/popular', 'popular')->name('cities.popular');
        Route::get('/cities/region/{regionCode}', 'byRegion')->name('cities.by-region');
        Route::get('/cities/{slug}', 'show')->name('cities.show');
        Route::get('/cities/{slug}/categories', 'categories')->name('cities.categories');
    });

    // Categories Routes
    Route::controller(CategoriesController::class)->group(function () {
        Route::get('/categories', 'index')->name('categories.index');
        Route::get('/categories/roots', 'roots')->name('categories.roots');
        Route::get('/categories/tree', 'tree')->name('categories.tree');
        Route::get('/categories/popular', 'popular')->name('categories.popular');
        Route::get('/categories/search', 'search')->name('categories.search');
        Route::get('/categories/city/{citySlug}', 'byCity')->name('categories.by-city');
        Route::get('/categories/{slug}', 'show')->name('categories.show');
    });

    // Services Routes
    Route::controller(ServiceController::class)->group(function () {
        Route::get('/services', 'index')->name('services.index');
        Route::get('/services/featured', 'featured')->name('services.featured');
        Route::get('/services/popular', 'popular')->name('services.popular');
        Route::get('/services/recent', 'recent')->name('services.recent');
        Route::get('/services/city/{citySlug}/category/{categorySlug}', 'byCityAndCategory')->name('services.by-city-category');
        Route::get('/services/{slugOrId}', 'show')->name('services.show');
    });

    // SEO & Sitemap Routes
    Route::get('/sitemap/services', function () {
        $services = \App\Models\Service::active()
            ->select(['slug_ar', 'slug_en', 'updated_at'])
            ->limit(10000)
            ->get();

        $urls = [];
        foreach ($services as $service) {
            if ($service->slug_ar) {
                $urls[] = [
                    'loc' => "https://khidmaapp.com/ar/service/{$service->slug_ar}",
                    'lastmod' => $service->updated_at->toISOString(),
                    'changefreq' => 'daily',
                    'priority' => '0.8'
                ];
            }
            if ($service->slug_en) {
                $urls[] = [
                    'loc' => "https://khidmaapp.com/en/service/{$service->slug_en}",
                    'lastmod' => $service->updated_at->toISOString(),
                    'changefreq' => 'daily',
                    'priority' => '0.8'
                ];
            }
        }

        return response()->json(['urls' => $urls])
            ->header('Cache-Control', 'public, max-age=3600');
    })->name('api.v1.sitemap.services');

    Route::get('/sitemap/cities', function () {
        $cities = \App\Models\City::active()
            ->select(['slug_ar', 'slug_en', 'updated_at'])
            ->get();

        $urls = [];
        foreach ($cities as $city) {
            if ($city->slug_ar) {
                $urls[] = [
                    'loc' => "https://khidmaapp.com/ar/city/{$city->slug_ar}",
                    'lastmod' => $city->updated_at->toISOString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7'
                ];
            }
            if ($city->slug_en) {
                $urls[] = [
                    'loc' => "https://khidmaapp.com/en/city/{$city->slug_en}",
                    'lastmod' => $city->updated_at->toISOString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7'
                ];
            }
        }

        return response()->json(['urls' => $urls])
            ->header('Cache-Control', 'public, max-age=7200');
    })->name('api.v1.sitemap.cities');

    Route::get('/sitemap/categories', function () {
        $categories = \App\Models\Category::active()
            ->select(['slug_ar', 'slug_en', 'updated_at'])
            ->get();

        $urls = [];
        foreach ($categories as $category) {
            if ($category->slug_ar) {
                $urls[] = [
                    'loc' => "https://khidmaapp.com/ar/category/{$category->slug_ar}",
                    'lastmod' => $category->updated_at->toISOString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.6'
                ];
            }
            if ($category->slug_en) {
                $urls[] = [
                    'loc' => "https://khidmaapp.com/en/category/{$category->slug_en}",
                    'lastmod' => $category->updated_at->toISOString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.6'
                ];
            }
        }

        return response()->json(['urls' => $urls])
            ->header('Cache-Control', 'public, max-age=7200');
    })->name('api.v1.sitemap.categories');
});

// Fallback route for API
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found',
        'code' => 404
    ], 404);
});

// Search API Routes
Route::prefix('search')->group(function () {
    Route::get('categories', [App\Http\Controllers\Api\SearchController::class, 'categories']);
    Route::get('services', [App\Http\Controllers\Api\SearchController::class, 'services']);
    Route::get('suggestions', [App\Http\Controllers\Api\SearchController::class, 'suggestions']);
});
