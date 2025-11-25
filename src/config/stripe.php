<?php
/**
 * Stripe Configuration
 */

// Stripe API Keys
// 🔒 IMPORTANT: Set these in .env file!
// Dashboard: https://dashboard.stripe.com/apikeys

// Load from environment variables
if (!defined('STRIPE_SECRET_KEY')) {
    $stripeKey = env('STRIPE_SECRET_KEY');
    if (empty($stripeKey)) {
        error_log('⚠️ STRIPE_SECRET_KEY not set in .env file!');
        throw new Exception('Stripe API key not configured. Please check .env file.');
    }
    define('STRIPE_SECRET_KEY', $stripeKey);
}

if (!defined('STRIPE_PUBLISHABLE_KEY')) {
    $stripePubKey = env('STRIPE_PUBLISHABLE_KEY');
    if (empty($stripePubKey)) {
        error_log('⚠️ STRIPE_PUBLISHABLE_KEY not set in .env file!');
        throw new Exception('Stripe publishable key not configured. Please check .env file.');
    }
    define('STRIPE_PUBLISHABLE_KEY', $stripePubKey);
}

// Webhook Secret
// Endpoint URL: https://khidmaapp.com/webhook/stripe
// Events: checkout.session.completed, payment_intent.succeeded, charge.refunded
if (!defined('STRIPE_WEBHOOK_SECRET')) {
    $webhookSecret = env('STRIPE_WEBHOOK_SECRET');
    if (empty($webhookSecret)) {
        error_log('⚠️ STRIPE_WEBHOOK_SECRET not set in .env file!');
        // Webhook secret is optional for local development
        if (defined('APP_ENV') && APP_ENV === 'production') {
            throw new Exception('Stripe webhook secret not configured. Please check .env file.');
        }
    }
    define('STRIPE_WEBHOOK_SECRET', $webhookSecret ?? '');
}

// Uygulama URL'i (production'da environment variable olarak ayarlanmalı)
if (!defined('APP_URL')) {
    define('APP_URL', getenv('APP_URL') ?: 'http://localhost:8000');
}

// Stripe Webhook endpoint
if (!defined('STRIPE_WEBHOOK_URL')) {
    define('STRIPE_WEBHOOK_URL', APP_URL . '/webhook/stripe');
}

// Currency
if (!defined('STRIPE_CURRENCY')) {
    define('STRIPE_CURRENCY', 'SAR');
}

// Success ve Cancel URLs
if (!defined('STRIPE_SUCCESS_URL')) {
    define('STRIPE_SUCCESS_URL', APP_URL . '/provider/purchase/success?session_id={CHECKOUT_SESSION_ID}');
}
if (!defined('STRIPE_CANCEL_URL')) {
    define('STRIPE_CANCEL_URL', APP_URL . '/provider/purchase/cancel');
}

/**
 * Stripe SDK'yı başlat
 */
function initStripe() {
    require_once __DIR__ . '/../../vendor/autoload.php';
    
    // Debug: Check if key is set
    if (!defined('STRIPE_SECRET_KEY')) {
        error_log('STRIPE_SECRET_KEY is not defined!');
        throw new Exception('Stripe API key not configured');
    }
    
    if (empty(STRIPE_SECRET_KEY)) {
        error_log('STRIPE_SECRET_KEY is empty!');
        throw new Exception('Stripe API key is empty');
    }
    
    error_log('Setting Stripe API key: ' . substr(STRIPE_SECRET_KEY, 0, 20) . '...');
    \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
}

/**
 * Lead paketlerini getir
 */
function getLeadPackages($pdo, $serviceType = null, $activeOnly = true) {
    $sql = "SELECT * FROM lead_packages WHERE 1=1";
    $params = [];
    
    if ($serviceType) {
        $sql .= " AND service_type = ?";
        $params[] = $serviceType;
    }
    
    if ($activeOnly) {
        $sql .= " AND is_active = 1";
    }
    
    $sql .= " ORDER BY service_type, lead_count";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Lead paketi detayını getir
 */
function getLeadPackage($pdo, $packageId) {
    $stmt = $pdo->prepare("SELECT * FROM lead_packages WHERE id = ? AND is_active = 1");
    $stmt->execute([$packageId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Stripe Checkout Session oluştur
 */
function createStripeCheckoutSession($packageId, $providerId, $pdo) {
    initStripe();
    
    $package = getLeadPackage($pdo, $packageId);
    if (!$package) {
        throw new Exception('Paket bulunamadı');
    }
    
    // Provider bilgilerini al
    $stmt = $pdo->prepare("SELECT * FROM service_providers WHERE id = ?");
    $stmt->execute([$providerId]);
    $provider = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$provider) {
        throw new Exception('Usta bulunamadı');
    }
    
    // Service type kontrolü
    if ($provider['service_type'] !== $package['service_type']) {
        throw new Exception('Bu paket sizin hizmet türünüz için değil');
    }
    
    // Hizmet adını al
    $serviceTypes = getServiceTypes();
    $serviceName = $serviceTypes[$package['service_type']]['ar'] ?? $package['service_type'];
    
    // Stripe Checkout Session oluştur
    $sessionData = [
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => strtolower(STRIPE_CURRENCY),
                'product_data' => [
                    'name' => sprintf('KhidmaApp - %d Lead (%s)', $package['lead_count'], $serviceName),
                    'description' => sprintf('خدمة - منصة ربط مقدمي الخدمات | Lead başına %s SAR', number_format($package['price_per_lead'], 2)),
                ],
                'unit_amount' => intval($package['price_sar'] * 100), // Stripe kuruş cinsinden çalışır
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => str_replace('{CHECKOUT_SESSION_ID}', '{CHECKOUT_SESSION_ID}', STRIPE_SUCCESS_URL),
        'cancel_url' => STRIPE_CANCEL_URL,
        'client_reference_id' => sprintf('provider_%d_package_%d', $providerId, $packageId),
        'customer_email' => $provider['email'] ?? null,
        'billing_address_collection' => 'auto',
        'metadata' => [
            'provider_id' => $providerId,
            'package_id' => $packageId,
            'lead_count' => $package['lead_count'],
            'service_type' => $package['service_type'],
            'platform' => 'KhidmaApp.com',
            'operator' => 'Aptiro LLC',
        ],
    ];
    
    // Statement descriptor için payment_intent_data kullan (daha güvenli)
    // Not: Sadece onaylı Stripe hesaplarında çalışır
    try {
        $sessionData['payment_intent_data'] = [
            'statement_descriptor' => 'KHIDMAAPP', // Max 22 karakter, sadece harf ve rakam
            'statement_descriptor_suffix' => sprintf('%dL', $package['lead_count']), // Max 22 karakter
        ];
    } catch (\Exception $e) {
        // Hesap desteklemiyorsa sessizce geç
        error_log('Statement descriptor not supported: ' . $e->getMessage());
    }
    
    $session = \Stripe\Checkout\Session::create($sessionData);
    
    // Purchase kaydını oluştur (pending olarak)
    $packageName = sprintf('%d Lead - %s', $package['lead_count'], $serviceName);
    $stmt = $pdo->prepare("
        INSERT INTO provider_purchases 
        (provider_id, package_id, package_name, leads_count, remaining_leads, price_paid, stripe_session_id, payment_status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    $stmt->execute([
        $providerId,
        $packageId,
        $packageName,
        $package['lead_count'],
        $package['lead_count'], // Başlangıçta tüm lead'ler kullanılabilir
        $package['price_sar'],
        $session->id
    ]);
    
    return $session;
}

/**
 * Stripe webhook'u işle
 */
function handleStripeWebhook($payload, $signature, $pdo) {
    initStripe();
    
    try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, 
            $signature, 
            STRIPE_WEBHOOK_SECRET
        );
    } catch (\Exception $e) {
        error_log('Webhook signature verification failed: ' . $e->getMessage());
        return false;
    }
    
    // Event tipine göre işlem yap
    switch ($event->type) {
        case 'checkout.session.completed':
            return handleCheckoutSessionCompleted($event->data->object, $pdo);
            
        case 'payment_intent.payment_failed':
            return handlePaymentFailed($event->data->object, $pdo);
            
        default:
            error_log('Unhandled webhook event type: ' . $event->type);
            return true;
    }
}

/**
 * Checkout session tamamlandı
 */
function handleCheckoutSessionCompleted($session, $pdo) {
    try {
        $pdo->beginTransaction();
        
        // Purchase kaydını bul
        $stmt = $pdo->prepare("
            SELECT * FROM provider_purchases 
            WHERE stripe_session_id = ? AND payment_status = 'pending'
        ");
        $stmt->execute([$session->id]);
        $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$purchase) {
            error_log('Purchase not found for session: ' . $session->id);
            $pdo->rollBack();
            return false;
        }
        
        // Purchase'ı güncelle - ödeme tamamlandı
        $stmt = $pdo->prepare("
            UPDATE provider_purchases 
            SET payment_status = 'completed',
                remaining_leads = total_leads,
                stripe_payment_intent = ?,
                paid_amount = ?,
                currency = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $session->payment_intent,
            $session->amount_total / 100, // Stripe kuruş cinsinden, SAR'a çevir
            strtoupper($session->currency),
            $purchase['id']
        ]);
        
        $pdo->commit();
        
        error_log(sprintf(
            'Purchase completed: ID=%d, Provider=%d, Leads=%d',
            $purchase['id'],
            $purchase['provider_id'],
            $purchase['total_leads']
        ));
        
        return true;
    } catch (\Exception $e) {
        $pdo->rollBack();
        error_log('Error handling checkout completed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Ödeme başarısız oldu
 */
function handlePaymentFailed($paymentIntent, $pdo) {
    try {
        // Purchase kaydını bul ve güncelle
        $stmt = $pdo->prepare("
            UPDATE provider_purchases 
            SET payment_status = 'failed'
            WHERE stripe_payment_intent = ?
        ");
        $stmt->execute([$paymentIntent->id]);
        
        error_log('Payment failed for intent: ' . $paymentIntent->id);
        return true;
    } catch (\Exception $e) {
        error_log('Error handling payment failed: ' . $e->getMessage());
        return false;
    }
}




