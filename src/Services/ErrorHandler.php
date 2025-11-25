<?php

/**
 * KhidmaApp.com - Error Handler
 * 
 * Merkezi hata yönetimi
 */

require_once __DIR__ . '/LoggerService.php';
require_once __DIR__ . '/../Exceptions/AppException.php';

class ErrorHandler
{
    /**
     * Debug modu
     */
    private static bool $debug = false;
    
    /**
     * Error handler'ı kaydet
     * 
     * @param bool $debug Debug modu
     */
    public static function register(bool $debug = false): void
    {
        self::$debug = $debug;
        
        // Logger'ı başlat
        LoggerService::init();
        
        // Error handler
        set_error_handler([self::class, 'handleError']);
        
        // Exception handler
        set_exception_handler([self::class, 'handleException']);
        
        // Shutdown handler (fatal errors için)
        register_shutdown_function([self::class, 'handleShutdown']);
        
        // Error reporting
        if ($debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
            ini_set('display_errors', '0');
        }
    }
    
    /**
     * PHP error handler
     */
    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): bool
    {
        // Error suppression (@) kontrolü
        if (!(error_reporting() & $errno)) {
            return false;
        }
        
        $level = self::errorLevelToLogLevel($errno);
        
        LoggerService::log($level, $errstr, [
            'error_code' => $errno,
            'file' => $errfile,
            'line' => $errline,
        ]);
        
        // E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR için exception fırlat
        if (in_array($errno, [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
        
        return true;
    }
    
    /**
     * Exception handler
     */
    public static function handleException(Throwable $exception): void
    {
        // Log
        LoggerService::exception($exception);
        
        // HTTP status kodu
        $httpCode = 500;
        $userMessage = 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
        $errorCode = 'internal_error';
        $context = [];
        
        // AppException ise özel bilgileri al
        if ($exception instanceof AppException) {
            $httpCode = $exception->getHttpCode();
            $userMessage = $exception->getUserMessage();
            $context = $exception->getContext();
            
            // Error code
            $errorCode = strtolower(str_replace('Exception', '', get_class($exception)));
            $errorCode = preg_replace('/([a-z])([A-Z])/', '$1_$2', $errorCode);
            $errorCode = strtolower($errorCode);
        }
        
        // ValidationException için errors al
        if ($exception instanceof ValidationException) {
            $context['errors'] = $exception->getErrors();
        }
        
        // RateLimitException için retry_after header
        if ($exception instanceof RateLimitException) {
            header('Retry-After: ' . $exception->getRetryAfter());
        }
        
        // Response gönder
        self::sendResponse($httpCode, $userMessage, $errorCode, $context, $exception);
    }
    
    /**
     * Shutdown handler (fatal errors)
     */
    public static function handleShutdown(): void
    {
        $error = error_get_last();
        
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            LoggerService::critical('Fatal Error', [
                'error_type' => $error['type'],
                'message' => $error['message'],
                'file' => $error['file'],
                'line' => $error['line'],
            ]);
            
            // Response henüz gönderilmediyse hata sayfası göster
            if (!headers_sent()) {
                self::sendResponse(500, 'Kritik bir hata oluştu.', 'fatal_error');
            }
        }
    }
    
    /**
     * Response gönder
     */
    private static function sendResponse(
        int $httpCode,
        string $message,
        string $errorCode = 'error',
        array $context = [],
        ?Throwable $exception = null
    ): void {
        // Headers zaten gönderildiyse çık
        if (headers_sent()) {
            return;
        }
        
        http_response_code($httpCode);
        
        // AJAX veya JSON request kontrolü
        $isJson = self::isJsonRequest();
        
        if ($isJson) {
            header('Content-Type: application/json; charset=utf-8');
            
            $response = [
                'success' => false,
                'message' => $message,
                'error' => $errorCode,
            ];
            
            // Context'i ekle (errors gibi)
            if (!empty($context['errors'])) {
                $response['errors'] = $context['errors'];
            }
            
            // Debug modunda ek bilgi
            if (self::$debug && $exception) {
                $response['debug'] = [
                    'exception' => get_class($exception),
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ];
            }
            
            echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            // HTML response
            self::renderErrorPage($httpCode, $message, $exception);
        }
        
        exit;
    }
    
    /**
     * Hata sayfası render et
     */
    private static function renderErrorPage(int $httpCode, string $message, ?Throwable $exception = null): void
    {
        $titles = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            422 => 'Validation Error',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
        ];
        
        $title = $titles[$httpCode] ?? 'Error';
        
        echo "<!DOCTYPE html>
<html lang='tr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>{$httpCode} - {$title}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .error-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .error-code {
            font-size: 80px;
            font-weight: 800;
            color: #667eea;
            line-height: 1;
            margin-bottom: 10px;
        }
        .error-title {
            font-size: 24px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 15px;
        }
        .error-message {
            color: #718096;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .back-button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        .debug-info {
            margin-top: 30px;
            padding: 20px;
            background: #f7fafc;
            border-radius: 10px;
            text-align: left;
            font-size: 12px;
            color: #4a5568;
            overflow-x: auto;
        }
        .debug-info pre {
            margin: 0;
            white-space: pre-wrap;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class='error-container'>
        <div class='error-code'>{$httpCode}</div>
        <h1 class='error-title'>{$title}</h1>
        <p class='error-message'>" . htmlspecialchars($message) . "</p>
        <a href='/' class='back-button'>Ana Sayfaya Dön</a>";
        
        // Debug modunda ek bilgi
        if (self::$debug && $exception) {
            echo "<div class='debug-info'>
                <strong>Debug Info:</strong><br>
                <pre>Exception: " . htmlspecialchars(get_class($exception)) . "\n";
            echo "Message: " . htmlspecialchars($exception->getMessage()) . "\n";
            echo "File: " . htmlspecialchars($exception->getFile()) . ":" . $exception->getLine() . "\n\n";
            echo "Stack Trace:\n" . htmlspecialchars($exception->getTraceAsString()) . "</pre>
            </div>";
        }
        
        echo "</div>
</body>
</html>";
    }
    
    /**
     * JSON request kontrolü
     */
    private static function isJsonRequest(): bool
    {
        // AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            return true;
        }
        
        // Accept header
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        if (strpos($accept, 'application/json') !== false) {
            return true;
        }
        
        // Content-Type header (POST requests)
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            return true;
        }
        
        return false;
    }
    
    /**
     * PHP error seviyesini log seviyesine çevir
     */
    private static function errorLevelToLogLevel(int $errno): string
    {
        return match ($errno) {
            E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR => LoggerService::LEVEL_ERROR,
            E_WARNING, E_CORE_WARNING, E_COMPILE_WARNING, E_USER_WARNING => LoggerService::LEVEL_WARNING,
            E_NOTICE, E_USER_NOTICE => LoggerService::LEVEL_INFO,
            E_DEPRECATED, E_USER_DEPRECATED => LoggerService::LEVEL_DEBUG,
            default => LoggerService::LEVEL_WARNING,
        };
    }
}

