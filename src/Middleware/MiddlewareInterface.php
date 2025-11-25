<?php

/**
 * KhidmaApp.com - Middleware Interface
 * 
 * Tüm middleware'lerin uygulaması gereken arayüz
 */

namespace App\Middleware;

interface MiddlewareInterface
{
    /**
     * Middleware'i çalıştır
     * 
     * @param array $request Request bilgileri
     * @param callable $next Sonraki middleware veya handler
     * @return mixed
     */
    public function handle(array $request, callable $next);
}

