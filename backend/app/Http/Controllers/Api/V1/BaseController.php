<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    /**
     * Success response
     */
    protected function success($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Error response
     */
    protected function error(string $message = 'Error', int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Resource response
     */
    protected function resource(JsonResource $resource, string $message = 'Success', int $code = 200): JsonResponse
    {
        return $this->success($resource, $message, $code);
    }

    /**
     * Collection response with pagination
     */
    protected function collection(ResourceCollection $collection, string $message = 'Success'): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $collection->collection,
        ];

        // Add pagination meta if available
        if (method_exists($collection, 'resource') && $collection->resource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $paginator = $collection->resource;
            $response['meta'] = [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ];

            $response['links'] = [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * Validation error response
     */
    protected function validationError($errors): JsonResponse
    {
        return $this->error('Validation failed', 422, $errors);
    }

    /**
     * Not found response
     */
    protected function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->error($message, 404);
    }

    /**
     * Unauthorized response
     */
    protected function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->error($message, 401);
    }

    /**
     * Forbidden response
     */
    protected function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->error($message, 403);
    }

    /**
     * Server error response
     */
    protected function serverError(string $message = 'Internal server error'): JsonResponse
    {
        return $this->error($message, 500);
    }

    /**
     * Set cache headers for response
     */
    protected function cached(JsonResponse $response, int $maxAge = 300): JsonResponse
    {
        return $response->withHeaders([
            'Cache-Control' => "public, max-age={$maxAge}",
            'Expires' => now()->addSeconds($maxAge)->toRfc7231String(),
            'Last-Modified' => now()->toRfc7231String(),
        ]);
    }

    /**
     * Add CORS headers
     */
    protected function withCors(JsonResponse $response): JsonResponse
    {
        $allowedOrigins = config('cors.allowed_origins', []);
        $origin = request()->header('Origin');

        if (in_array($origin, $allowedOrigins) || in_array('*', $allowedOrigins)) {
            $response->header('Access-Control-Allow-Origin', $origin);
        }

        return $response->withHeaders([
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept, Origin',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age' => '3600',
        ]);
    }
}
