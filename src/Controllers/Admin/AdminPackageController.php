<?php

/**
 * KhidmaApp.com - Admin Package Controller
 * 
 * Lead paketleri yönetimi
 */

require_once __DIR__ . '/BaseAdminController.php';

class AdminPackageController extends BaseAdminController 
{
    /**
     * Paketler listesi (sadece 2 paket: 1 ve 3 lead)
     */
    public function index(): void
    {
        $this->requireAuth();
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM lead_packages 
                ORDER BY display_order ASC, lead_count ASC
            ");
            $stmt->execute();
            $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $activeCount = count(array_filter($packages, fn($p) => $p['is_active'] == 1));
            $inactiveCount = count($packages) - $activeCount;
            
            $this->render('lead_packages', [
                'packages' => $packages,
                'totalPackages' => count($packages),
                'activeCount' => $activeCount,
                'inactiveCount' => $inactiveCount,
                'pageTitle' => 'Lead Paket Yönetimi'
            ]);
            
        } catch (PDOException $e) {
            error_log("Manage lead packages error: " . $e->getMessage());
            $_SESSION['error'] = 'Paketler yüklenirken hata oluştu';
            $this->redirect('/admin');
        }
    }
    
    /**
     * Yeni paket oluştur
     */
    public function create(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Geçersiz istek', 400);
        }
        
        if (!verifyCsrfToken($this->postParam('csrf_token', ''))) {
            $this->errorResponse('CSRF token doğrulaması başarısız', 403);
        }
        
        try {
            $leadCount = $this->intPost('lead_count');
            $priceSar = floatval($this->postParam('price_sar', 0));
            $nameAr = trim($this->postParam('name_ar', ''));
            $nameTr = trim($this->postParam('name_tr', ''));
            $descriptionAr = trim($this->postParam('description_ar', ''));
            $descriptionTr = trim($this->postParam('description_tr', ''));
            $discountPercentage = floatval($this->postParam('discount_percentage', 0));
            $displayOrder = $this->intPost('display_order', 0);
            $isActive = $this->intPost('is_active', 1);
            
            if ($leadCount <= 0 || $priceSar <= 0) {
                $this->errorResponse('Lead sayısı ve fiyat zorunludur', 400);
            }
            
            // Aynı lead sayısına sahip paket var mı kontrol et
            $stmt = $this->pdo->prepare("SELECT id FROM lead_packages WHERE lead_count = ?");
            $stmt->execute([$leadCount]);
            if ($stmt->fetch()) {
                $this->errorResponse('Bu lead sayısına sahip bir paket zaten mevcut', 400);
            }
            
            $pricePerLead = $priceSar / $leadCount;
            
            // Stripe entegrasyonu (opsiyonel)
            $stripeProductId = null;
            $stripePriceId = null;
            
            try {
                require_once __DIR__ . '/../../config/stripe.php';
                if (defined('STRIPE_SECRET_KEY') && STRIPE_SECRET_KEY) {
                    initStripe();
                    
                    $stripeProduct = \Stripe\Product::create([
                        'name' => $nameTr ?: ($leadCount . ' Lead Paketi'),
                        'description' => $descriptionTr,
                        'metadata' => [
                            'lead_count' => $leadCount,
                            'name_ar' => $nameAr,
                        ]
                    ]);
                    
                    $stripePrice = \Stripe\Price::create([
                        'product' => $stripeProduct->id,
                        'unit_amount' => intval($priceSar * 100),
                        'currency' => 'sar',
                    ]);
                    
                    $stripeProductId = $stripeProduct->id;
                    $stripePriceId = $stripePrice->id;
                }
            } catch (\Exception $e) {
                error_log("Stripe integration skipped: " . $e->getMessage());
            }
            
            $stmt = $this->pdo->prepare("
                INSERT INTO lead_packages (
                    lead_count, price_sar, price_per_lead, 
                    name_ar, name_tr, description_ar, description_tr,
                    discount_percentage, display_order,
                    stripe_product_id, stripe_price_id, is_active
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $leadCount, $priceSar, $pricePerLead,
                $nameAr, $nameTr, $descriptionAr, $descriptionTr,
                $discountPercentage, $displayOrder,
                $stripeProductId, $stripePriceId, $isActive
            ]);
            
            $this->successResponse('Paket başarıyla oluşturuldu', [
                'package_id' => $this->pdo->lastInsertId()
            ]);
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log("Stripe API error: " . $e->getMessage());
            $this->errorResponse('Stripe hatası: ' . $e->getMessage(), 500);
        } catch (PDOException $e) {
            error_log("Create lead package error: " . $e->getMessage());
            $this->errorResponse('Veritabanı hatası: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Paket güncelle
     */
    public function update(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Geçersiz istek', 400);
        }
        
        if (!verifyCsrfToken($this->postParam('csrf_token', ''))) {
            $this->errorResponse('CSRF token doğrulaması başarısız', 403);
        }
        
        try {
            $packageId = $this->intPost('package_id');
            $priceSar = floatval($this->postParam('price_sar', 0));
            $nameAr = trim($this->postParam('name_ar', ''));
            $nameTr = trim($this->postParam('name_tr', ''));
            $descriptionAr = trim($this->postParam('description_ar', ''));
            $descriptionTr = trim($this->postParam('description_tr', ''));
            $isActive = $this->intPost('is_active', 1);
            
            if ($packageId <= 0 || $priceSar <= 0) {
                $this->errorResponse('Geçersiz paket ID veya fiyat', 400);
            }
            
            // Mevcut paketi al
            $stmt = $this->pdo->prepare("SELECT * FROM lead_packages WHERE id = ?");
            $stmt->execute([$packageId]);
            $package = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$package) {
                $this->errorResponse('Paket bulunamadı', 404);
            }
            
            $pricePerLead = $priceSar / $package['lead_count'];
            
            // Stripe güncelle
            require_once __DIR__ . '/../../config/stripe.php';
            initStripe();
            
            $newStripePriceId = $package['stripe_price_id'];
            
            if ($package['stripe_product_id']) {
                \Stripe\Product::update($package['stripe_product_id'], [
                    'name' => $nameTr,
                    'description' => $descriptionTr,
                ]);
                
                // Yeni fiyat oluştur (Stripe fiyat güncellemesine izin vermiyor)
                $stripePrice = \Stripe\Price::create([
                    'product' => $package['stripe_product_id'],
                    'unit_amount' => intval($priceSar * 100),
                    'currency' => 'sar',
                ]);
                $newStripePriceId = $stripePrice->id;
            }
            
            // Veritabanı güncelle
            $stmt = $this->pdo->prepare("
                UPDATE lead_packages 
                SET price_sar = ?, price_per_lead = ?, 
                    name_ar = ?, name_tr = ?, description_ar = ?, description_tr = ?,
                    stripe_price_id = ?, is_active = ?
                WHERE id = ?
            ");
            
            $stmt->execute([
                $priceSar, $pricePerLead,
                $nameAr, $nameTr, $descriptionAr, $descriptionTr,
                $newStripePriceId, $isActive, $packageId
            ]);
            
            $this->successResponse('Paket başarıyla güncellendi');
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log("Stripe API error: " . $e->getMessage());
            $this->errorResponse('Stripe hatası: ' . $e->getMessage(), 500);
        } catch (PDOException $e) {
            error_log("Update lead package error: " . $e->getMessage());
            $this->errorResponse('Veritabanı hatası', 500);
        }
    }
    
    /**
     * Paket durumunu değiştir
     */
    public function toggleStatus(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Geçersiz istek', 400);
        }
        
        if (!verifyCsrfToken($this->postParam('csrf_token', ''))) {
            $this->errorResponse('CSRF token doğrulaması başarısız', 403);
        }
        
        try {
            $packageId = $this->intPost('package_id');
            
            if ($packageId <= 0) {
                $this->errorResponse('Geçersiz paket ID', 400);
            }
            
            $stmt = $this->pdo->prepare("SELECT is_active FROM lead_packages WHERE id = ?");
            $stmt->execute([$packageId]);
            $package = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$package) {
                $this->errorResponse('Paket bulunamadı', 404);
            }
            
            $newStatus = $package['is_active'] ? 0 : 1;
            
            $stmt = $this->pdo->prepare("UPDATE lead_packages SET is_active = ? WHERE id = ?");
            $stmt->execute([$newStatus, $packageId]);
            
            $this->successResponse('Paket durumu güncellendi', ['new_status' => $newStatus]);
            
        } catch (PDOException $e) {
            error_log("Toggle package status error: " . $e->getMessage());
            $this->errorResponse('Veritabanı hatası', 500);
        }
    }
    
    /**
     * Paket sil
     */
    public function delete(): void
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $this->errorResponse('Geçersiz istek', 400);
        }
        
        if (!verifyCsrfToken($this->postParam('csrf_token', ''))) {
            $this->errorResponse('CSRF token doğrulaması başarısız', 403);
        }
        
        try {
            $packageId = $this->intPost('package_id');
            
            if ($packageId <= 0) {
                $this->errorResponse('Geçersiz paket ID', 400);
            }
            
            // Satın alma kontrolü
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM provider_purchases WHERE package_id = ?");
            $stmt->execute([$packageId]);
            $purchaseCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            if ($purchaseCount > 0) {
                $this->errorResponse("Bu pakete ait {$purchaseCount} satın alma var. Silinemez.", 400);
            }
            
            $stmt = $this->pdo->prepare("DELETE FROM lead_packages WHERE id = ?");
            $stmt->execute([$packageId]);
            
            $this->successResponse('Paket başarıyla silindi');
            
        } catch (PDOException $e) {
            error_log("Delete package error: " . $e->getMessage());
            $this->errorResponse('Veritabanı hatası', 500);
        }
    }
}

