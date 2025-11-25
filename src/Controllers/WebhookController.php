<?php
/**
 * Webhook Controller
 * 
 * Dış servislerden gelen webhook'ları işler
 * 
 * @package KhidmaApp
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/stripe.php';

class WebhookController
{
    private $pdo;
    
    public function __construct()
    {
        $this->pdo = getDatabase();
    }
    
    /**
     * Stripe Webhook Handler
     * 
     * Stripe'dan gelen ödeme bildirimleri burada işlenir
     * Endpoint: /webhook/stripe
     */
    public function stripe()
    {
        // Sadece POST isteklerini kabul et
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }
        
        // Raw payload'ı al
        $payload = file_get_contents('php://input');
        $signature = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        
        // Log webhook request
        error_log("Stripe webhook received: " . strlen($payload) . " bytes");
        
        // Webhook secret kontrolü (production'da zorunlu)
        if (empty(STRIPE_WEBHOOK_SECRET)) {
            if (APP_ENV === 'production') {
                error_log('Stripe webhook secret not configured!');
                http_response_code(500);
                echo json_encode(['error' => 'Webhook not configured']);
                exit;
            }
            
            // Development modunda signature kontrolü olmadan işle
            error_log('⚠️ Development mode: Skipping webhook signature verification');
            $this->handleWebhookWithoutVerification($payload);
            return;
        }
        
        // Signature doğrulama
        if (empty($signature)) {
            error_log('Missing Stripe signature header');
            http_response_code(400);
            echo json_encode(['error' => 'Missing signature']);
            exit;
        }
        
        try {
            // Stripe SDK'yı başlat
            initStripe();
            
            // Event'i doğrula ve parse et
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                STRIPE_WEBHOOK_SECRET
            );
            
            error_log("Stripe webhook event: " . $event->type);
            
            // Event tipine göre işle
            $this->processEvent($event);
            
            // Başarılı response
            http_response_code(200);
            echo json_encode(['status' => 'success']);
            
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            error_log('Stripe signature verification failed: ' . $e->getMessage());
            http_response_code(400);
            echo json_encode(['error' => 'Invalid signature']);
            
        } catch (\Exception $e) {
            error_log('Stripe webhook error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Internal error']);
        }
    }
    
    /**
     * Development modunda signature olmadan webhook işle
     */
    private function handleWebhookWithoutVerification(string $payload)
    {
        try {
            $data = json_decode($payload, true);
            
            if (!$data || !isset($data['type'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid payload']);
                return;
            }
            
            error_log("Processing webhook (dev mode): " . $data['type']);
            
            // Basit event object oluştur
            $event = (object) [
                'type' => $data['type'],
                'data' => (object) ['object' => (object) ($data['data']['object'] ?? [])]
            ];
            
            $this->processEvent($event);
            
            http_response_code(200);
            echo json_encode(['status' => 'success']);
            
        } catch (\Exception $e) {
            error_log('Dev webhook error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Stripe event'ini işle
     */
    private function processEvent($event)
    {
        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutCompleted($event->data->object);
                break;
                
            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;
                
            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
                
            case 'charge.refunded':
                $this->handleChargeRefunded($event->data->object);
                break;
                
            default:
                error_log("Unhandled Stripe event type: " . $event->type);
        }
    }
    
    /**
     * Checkout session tamamlandı
     */
    private function handleCheckoutCompleted($session)
    {
        if (!$this->pdo) {
            error_log('Database connection failed in webhook');
            return;
        }
        
        try {
            $sessionId = is_object($session) ? $session->id : ($session['id'] ?? '');
            $paymentIntent = is_object($session) ? ($session->payment_intent ?? '') : ($session['payment_intent'] ?? '');
            $amountTotal = is_object($session) ? ($session->amount_total ?? 0) : ($session['amount_total'] ?? 0);
            $currency = is_object($session) ? ($session->currency ?? 'sar') : ($session['currency'] ?? 'sar');
            
            error_log("Processing checkout completed: $sessionId");
            
            $this->pdo->beginTransaction();
            
            // Purchase kaydını bul
            $stmt = $this->pdo->prepare("
                SELECT * FROM provider_purchases 
                WHERE stripe_session_id = ? AND payment_status = 'pending'
            ");
            $stmt->execute([$sessionId]);
            $purchase = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$purchase) {
                error_log("Purchase not found for session: $sessionId");
                $this->pdo->rollBack();
                return;
            }
            
            // Purchase'ı güncelle
            $stmt = $this->pdo->prepare("
                UPDATE provider_purchases 
                SET payment_status = 'completed',
                    stripe_payment_intent = ?,
                    currency = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $paymentIntent,
                strtoupper($currency),
                $purchase['id']
            ]);
            
            $this->pdo->commit();
            
            error_log("✅ Purchase completed: ID={$purchase['id']}, Provider={$purchase['provider_id']}");
            
        } catch (\Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log('Checkout completed error: ' . $e->getMessage());
        }
    }
    
    /**
     * Payment intent başarılı
     */
    private function handlePaymentSucceeded($paymentIntent)
    {
        $intentId = is_object($paymentIntent) ? $paymentIntent->id : ($paymentIntent['id'] ?? '');
        error_log("Payment succeeded: $intentId");
        
        // checkout.session.completed zaten işliyor, burada ek işlem gerekmez
    }
    
    /**
     * Ödeme başarısız
     */
    private function handlePaymentFailed($paymentIntent)
    {
        if (!$this->pdo) return;
        
        try {
            $intentId = is_object($paymentIntent) ? $paymentIntent->id : ($paymentIntent['id'] ?? '');
            
            $stmt = $this->pdo->prepare("
                UPDATE provider_purchases 
                SET payment_status = 'failed'
                WHERE stripe_payment_intent = ?
            ");
            $stmt->execute([$intentId]);
            
            error_log("❌ Payment failed: $intentId");
            
        } catch (\Exception $e) {
            error_log('Payment failed handler error: ' . $e->getMessage());
        }
    }
    
    /**
     * Ödeme iade edildi
     */
    private function handleChargeRefunded($charge)
    {
        if (!$this->pdo) return;
        
        try {
            $chargeId = is_object($charge) ? $charge->id : ($charge['id'] ?? '');
            $paymentIntent = is_object($charge) ? ($charge->payment_intent ?? '') : ($charge['payment_intent'] ?? '');
            
            // İade durumunu kaydet
            $stmt = $this->pdo->prepare("
                UPDATE provider_purchases 
                SET payment_status = 'refunded'
                WHERE stripe_payment_intent = ?
            ");
            $stmt->execute([$paymentIntent]);
            
            error_log("💰 Charge refunded: $chargeId");
            
        } catch (\Exception $e) {
            error_log('Charge refunded handler error: ' . $e->getMessage());
        }
    }
}

