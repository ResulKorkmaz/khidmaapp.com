<?php

/**
 * KhidmaApp.com - Provider Purchase Controller
 * 
 * Provider için paket satın alma işlemleri
 */

require_once __DIR__ . '/BaseProviderController.php';

class ProviderPurchaseController extends BaseProviderController 
{
    /**
     * Paketleri listele
     */
    public function packages(): void
    {
        $this->requireAuth();
        
        $provider = $this->getProvider();
        
        if (!$provider) {
            $this->redirect('/');
        }
        
        try {
            // Ustanın hizmet türüne göre paketleri getir (sadece 2 paket: 1 ve 3 lead)
            $providerServiceType = $provider['service_type'] ?? '';
            
            $stmt = $this->db->prepare("
                SELECT * FROM lead_packages 
                WHERE is_active = 1 AND service_type = ?
                ORDER BY display_order ASC, lead_count ASC
            ");
            $stmt->execute([$providerServiceType]);
            $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->render('browse_packages', [
                'packages' => $packages,
                'provider' => $provider
            ]);
        } catch (PDOException $e) {
            error_log("Browse packages error: " . $e->getMessage());
            $_SESSION['error'] = 'Paketler yüklenirken hata oluştu';
            $this->redirect('/provider/dashboard');
        }
    }
    
    /**
     * Lead Kalite Politikası sayfası
     */
    public function leadPolicy(): void
    {
        $this->requireAuth();
        $this->render('lead_policy', []);
    }
    
    /**
     * Paket satın alma sayfası
     */
    public function purchase(int $packageId): void
    {
        $this->requireAuth();
        
        $provider = $this->getProvider();
        
        if (!$provider || $provider['status'] !== 'active') {
            $_SESSION['error'] = 'Hesabınız aktif değil';
            $this->redirect('/provider/dashboard');
        }
        
        try {
            // Paketi getir - ustanın hizmet türüne uygun olmalı
            $stmt = $this->db->prepare("SELECT * FROM lead_packages WHERE id = ? AND is_active = 1 AND service_type = ?");
            $stmt->execute([$packageId, $provider['service_type']]);
            $package = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$package) {
                $_SESSION['error'] = 'Bu paket sizin hizmet türünüz için uygun değil';
                $this->redirect('/provider/browse-packages');
            }
            
            $this->render('purchase', [
                'package' => $package,
                'provider' => $provider
            ]);
        } catch (PDOException $e) {
            error_log("Purchase package error: " . $e->getMessage());
            $_SESSION['error'] = 'İşlem sırasında hata oluştu';
            $this->redirect('/provider/packages');
        }
    }
    
    /**
     * Stripe checkout session oluştur
     */
    public function createCheckoutSession(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Method not allowed', 405);
        }
        
        $packageId = $this->intPost('package_id');
        $providerId = $this->getProviderId();
        $provider = $this->getProvider();
        
        if (!$packageId) {
            $this->errorResponse('Geçersiz paket ID', 400);
        }
        
        if (!$provider || $provider['status'] !== 'active') {
            $this->errorResponse('Hesabınız aktif değil', 403);
        }
        
        try {
            // Paketi getir - ustanın hizmet türüne uygun olmalı
            $stmt = $this->db->prepare("SELECT * FROM lead_packages WHERE id = ? AND is_active = 1 AND service_type = ?");
            $stmt->execute([$packageId, $provider['service_type']]);
            $package = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$package) {
                $this->errorResponse('Bu paket sizin hizmet türünüz için uygun değil', 404);
            }
            
            // Stripe SDK yükle
            require_once __DIR__ . '/../../../vendor/autoload.php';
            
            $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
            
            // Checkout session oluştur
            $packageName = $package['name_ar'] ?? ($package['lead_count'] == 1 ? 'حزمة طلب واحد' : 'حزمة ' . $package['lead_count'] . ' طلبات');
            $packageDescription = $package['description_ar'] ?? $package['lead_count'] . ' lead paketi';
            
            $session = $stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'sar', // SAR - Suudi Riyali
                        'product_data' => [
                            'name' => $packageName,
                            'description' => $packageDescription,
                        ],
                        'unit_amount' => intval($package['price_sar'] * 100), // Kuruş cinsinden
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => APP_URL . '/provider/purchase/success?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => APP_URL . '/provider/purchase/cancel',
                'metadata' => [
                    'provider_id' => $providerId,
                    'package_id' => $packageId,
                ],
            ]);
            
            $this->successResponse('Checkout session oluşturuldu', [
                'session_id' => $session->id,
                'url' => $session->url
            ]);
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log("Stripe error: " . $e->getMessage());
            $this->errorResponse('Ödeme sistemi hatası: ' . $e->getMessage(), 500);
        } catch (PDOException $e) {
            error_log("Create checkout session error: " . $e->getMessage());
            $this->errorResponse('İşlem başarısız', 500);
        } catch (\Exception $e) {
            error_log("General error in checkout: " . $e->getMessage());
            $this->errorResponse('Bir hata oluştu: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Satın alma başarılı
     */
    public function success(): void
    {
        $this->requireAuth();
        
        $sessionId = $this->getParam('session_id');
        $provider = $this->getProvider();
        
        if (!$sessionId) {
            $_SESSION['error'] = 'Geçersiz oturum';
            $this->redirect('/provider/packages');
        }
        
        try {
            // Stripe SDK yükle
            require_once __DIR__ . '/../../../vendor/autoload.php';
            
            $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
            $session = $stripe->checkout->sessions->retrieve($sessionId);
            
            if ($session->payment_status !== 'paid') {
                $_SESSION['error'] = 'Ödeme tamamlanmadı';
                $this->redirect('/provider/packages');
            }
            
            $providerId = $session->metadata->provider_id;
            $packageId = $session->metadata->package_id;
            
            // Satın alma kaydı oluştur (eğer yoksa)
            $stmt = $this->db->prepare("SELECT id FROM provider_purchases WHERE stripe_session_id = ?");
            $stmt->execute([$sessionId]);
            
            if (!$stmt->fetch()) {
                // Paketi getir
                $stmt = $this->db->prepare("SELECT * FROM lead_packages WHERE id = ?");
                $stmt->execute([$packageId]);
                $package = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($package) {
                    $stmt = $this->db->prepare("
                        INSERT INTO provider_purchases 
                        (provider_id, package_id, leads_count, remaining_leads, price, payment_status, stripe_session_id, purchased_at)
                        VALUES (?, ?, ?, ?, ?, 'completed', ?, NOW())
                    ");
                    $stmt->execute([
                        $providerId,
                        $packageId,
                        $package['lead_count'],
                        $package['lead_count'],
                        $package['price_sar'],
                        $sessionId
                    ]);
                    
                    error_log("✅ Purchase created for provider #{$providerId}, package #{$packageId}");
                }
            }
            
            $_SESSION['success'] = 'تم الشراء بنجاح! يمكنك الآن طلب العملاء المحتملين';
            $this->redirect('/provider/leads');
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log("Stripe retrieve error: " . $e->getMessage());
            $_SESSION['error'] = 'Ödeme doğrulanamadı';
            $this->redirect('/provider/packages');
        } catch (PDOException $e) {
            error_log("Purchase success error: " . $e->getMessage());
            $_SESSION['error'] = 'İşlem kaydedilemedi';
            $this->redirect('/provider/packages');
        }
    }
    
    /**
     * Satın alma iptal
     */
    public function cancel(): void
    {
        $this->requireAuth();
        
        $_SESSION['info'] = 'تم إلغاء عملية الشراء';
        $this->redirect('/provider/packages');
    }
}

