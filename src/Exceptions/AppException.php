<?php

/**
 * KhidmaApp.com - Base Application Exception
 * 
 * Tüm uygulama exception'larının temel sınıfı
 */

class AppException extends Exception
{
    /**
     * HTTP status kodu
     */
    protected int $httpCode = 500;
    
    /**
     * Kullanıcıya gösterilecek mesaj
     */
    protected string $userMessage = 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
    
    /**
     * Ek veri
     */
    protected array $context = [];
    
    /**
     * Constructor
     * 
     * @param string $message Teknik hata mesajı (log için)
     * @param string $userMessage Kullanıcı mesajı
     * @param int $httpCode HTTP status kodu
     * @param array $context Ek veri
     * @param Throwable|null $previous Önceki exception
     */
    public function __construct(
        string $message = '',
        string $userMessage = '',
        int $httpCode = 500,
        array $context = [],
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        
        $this->httpCode = $httpCode;
        $this->userMessage = $userMessage ?: $this->userMessage;
        $this->context = $context;
    }
    
    /**
     * HTTP status kodunu al
     */
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
    
    /**
     * Kullanıcı mesajını al
     */
    public function getUserMessage(): string
    {
        return $this->userMessage;
    }
    
    /**
     * Context verisini al
     */
    public function getContext(): array
    {
        return $this->context;
    }
    
    /**
     * Log için formatlı mesaj
     */
    public function getLogMessage(): string
    {
        $log = $this->getMessage();
        
        if (!empty($this->context)) {
            $log .= ' | Context: ' . json_encode($this->context, JSON_UNESCAPED_UNICODE);
        }
        
        return $log;
    }
}

/**
 * Validation Exception
 */
class ValidationException extends AppException
{
    protected int $httpCode = 422;
    protected string $userMessage = 'Girilen bilgiler geçersiz.';
    
    /**
     * Validation hataları
     */
    protected array $errors = [];
    
    public function __construct(array $errors, string $message = 'Validation failed')
    {
        $this->errors = $errors;
        parent::__construct($message, 'Girilen bilgiler geçersiz.', 422, ['errors' => $errors]);
    }
    
    public function getErrors(): array
    {
        return $this->errors;
    }
}

/**
 * Not Found Exception
 */
class NotFoundException extends AppException
{
    protected int $httpCode = 404;
    protected string $userMessage = 'Aradığınız kaynak bulunamadı.';
    
    public function __construct(string $resource = 'Kaynak', string $message = '')
    {
        $message = $message ?: "{$resource} bulunamadı";
        parent::__construct($message, "{$resource} bulunamadı.", 404);
    }
}

/**
 * Unauthorized Exception
 */
class UnauthorizedException extends AppException
{
    protected int $httpCode = 401;
    protected string $userMessage = 'Bu işlem için giriş yapmanız gerekiyor.';
    
    public function __construct(string $message = 'Unauthorized')
    {
        parent::__construct($message, 'Bu işlem için giriş yapmanız gerekiyor.', 401);
    }
}

/**
 * Forbidden Exception
 */
class ForbiddenException extends AppException
{
    protected int $httpCode = 403;
    protected string $userMessage = 'Bu işlem için yetkiniz bulunmuyor.';
    
    public function __construct(string $message = 'Forbidden')
    {
        parent::__construct($message, 'Bu işlem için yetkiniz bulunmuyor.', 403);
    }
}

/**
 * Database Exception
 */
class DatabaseException extends AppException
{
    protected int $httpCode = 500;
    protected string $userMessage = 'Veritabanı hatası oluştu. Lütfen daha sonra tekrar deneyin.';
    
    public function __construct(string $message = 'Database error', ?Throwable $previous = null)
    {
        parent::__construct($message, $this->userMessage, 500, [], $previous);
    }
}

/**
 * Rate Limit Exception
 */
class RateLimitException extends AppException
{
    protected int $httpCode = 429;
    protected string $userMessage = 'Çok fazla istek gönderdiniz. Lütfen bekleyin.';
    
    /**
     * Bekleme süresi (saniye)
     */
    protected int $retryAfter;
    
    public function __construct(int $retryAfter = 60)
    {
        $this->retryAfter = $retryAfter;
        $minutes = ceil($retryAfter / 60);
        parent::__construct(
            "Rate limit exceeded. Retry after {$retryAfter} seconds",
            "Çok fazla istek gönderdiniz. Lütfen {$minutes} dakika sonra tekrar deneyin.",
            429,
            ['retry_after' => $retryAfter]
        );
    }
    
    public function getRetryAfter(): int
    {
        return $this->retryAfter;
    }
}

/**
 * Payment Exception
 */
class PaymentException extends AppException
{
    protected int $httpCode = 402;
    protected string $userMessage = 'Ödeme işlemi başarısız oldu.';
    
    public function __construct(string $message = 'Payment failed', array $context = [])
    {
        parent::__construct($message, 'Ödeme işlemi başarısız oldu. Lütfen tekrar deneyin.', 402, $context);
    }
}

/**
 * Service Exception
 */
class ServiceException extends AppException
{
    protected int $httpCode = 503;
    protected string $userMessage = 'Servis geçici olarak kullanılamıyor.';
    
    public function __construct(string $service = 'Servis', string $message = '')
    {
        $message = $message ?: "{$service} kullanılamıyor";
        parent::__construct($message, "{$service} geçici olarak kullanılamıyor. Lütfen daha sonra tekrar deneyin.", 503);
    }
}

