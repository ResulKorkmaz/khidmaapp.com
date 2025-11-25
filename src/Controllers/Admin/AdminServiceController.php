<?php

/**
 * KhidmaApp.com - Admin Service Controller
 * 
 * Hizmet türleri yönetimi
 */

require_once __DIR__ . '/BaseAdminController.php';

class AdminServiceController extends BaseAdminController 
{
    /**
     * Hizmetler listesi
     */
    public function index(): void
    {
        $this->requireAuth();
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM services 
                ORDER BY display_order ASC, id ASC
            ");
            $stmt->execute();
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $activeCount = count(array_filter($services, fn($s) => $s['is_active'] == 1));
            $inactiveCount = count($services) - $activeCount;
            
            $this->render('services', [
                'services' => $services,
                'totalServices' => count($services),
                'activeCount' => $activeCount,
                'inactiveCount' => $inactiveCount,
                'pageTitle' => 'Hizmet Yönetimi'
            ]);
            
        } catch (PDOException $e) {
            error_log("Manage services error: " . $e->getMessage());
            $_SESSION['error'] = 'Hizmetler yüklenirken hata oluştu';
            $this->redirect('/admin');
        }
    }
    
    /**
     * Yeni hizmet oluştur
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
            $serviceKey = trim($this->postParam('service_key', ''));
            $nameAr = trim($this->postParam('name_ar', ''));
            $nameTr = trim($this->postParam('name_tr', ''));
            $icon = trim($this->postParam('icon', ''));
            $isActive = $this->intPost('is_active', 1);
            
            if (empty($serviceKey) || empty($nameAr) || empty($nameTr)) {
                $this->errorResponse('Tüm alanlar zorunludur', 400);
            }
            
            if (!preg_match('/^[a-z0-9_]+$/', $serviceKey)) {
                $this->errorResponse('Service key sadece küçük harf, rakam ve alt çizgi içerebilir', 400);
            }
            
            // Benzersizlik kontrolü
            $stmt = $this->pdo->prepare("SELECT id FROM services WHERE service_key = ?");
            $stmt->execute([$serviceKey]);
            if ($stmt->fetch()) {
                $this->errorResponse('Bu service key zaten kullanılıyor', 400);
            }
            
            // Max display_order
            $stmt = $this->pdo->prepare("SELECT MAX(display_order) as max_order FROM services");
            $stmt->execute();
            $maxOrder = $stmt->fetch(PDO::FETCH_ASSOC)['max_order'] ?? 0;
            $displayOrder = $maxOrder + 1;
            
            // Insert
            $stmt = $this->pdo->prepare("
                INSERT INTO services (service_key, name_ar, name_tr, icon, is_active, display_order)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$serviceKey, $nameAr, $nameTr, $icon, $isActive, $displayOrder]);
            
            $this->clearServiceCache();
            
            $this->successResponse('Hizmet başarıyla eklendi', [
                'service_id' => $this->pdo->lastInsertId()
            ]);
            
        } catch (PDOException $e) {
            error_log("Create service error: " . $e->getMessage());
            $this->errorResponse('Veritabanı hatası', 500);
        }
    }
    
    /**
     * Hizmet güncelle
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
            $serviceId = $this->intPost('service_id');
            $nameAr = trim($this->postParam('name_ar', ''));
            $nameTr = trim($this->postParam('name_tr', ''));
            $icon = trim($this->postParam('icon', ''));
            $isActive = $this->intPost('is_active', 1);
            $displayOrder = $this->intPost('display_order', 0);
            
            if ($serviceId <= 0) {
                $this->errorResponse('Geçersiz hizmet ID', 400);
            }
            
            if (empty($nameAr) || empty($nameTr)) {
                $this->errorResponse('Hizmet adları zorunludur', 400);
            }
            
            // Varlık kontrolü
            $stmt = $this->pdo->prepare("SELECT service_key FROM services WHERE id = ?");
            $stmt->execute([$serviceId]);
            if (!$stmt->fetch()) {
                $this->errorResponse('Hizmet bulunamadı', 404);
            }
            
            // Update
            $stmt = $this->pdo->prepare("
                UPDATE services 
                SET name_ar = ?, name_tr = ?, icon = ?, is_active = ?, display_order = ?
                WHERE id = ?
            ");
            $stmt->execute([$nameAr, $nameTr, $icon, $isActive, $displayOrder, $serviceId]);
            
            $this->clearServiceCache();
            
            $this->successResponse('Hizmet başarıyla güncellendi');
            
        } catch (PDOException $e) {
            error_log("Update service error: " . $e->getMessage());
            $this->errorResponse('Veritabanı hatası', 500);
        }
    }
    
    /**
     * Hizmet durumunu değiştir
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
            $serviceId = $this->intPost('service_id');
            
            if ($serviceId <= 0) {
                $this->errorResponse('Geçersiz hizmet ID', 400);
            }
            
            // Mevcut durumu al
            $stmt = $this->pdo->prepare("SELECT service_key, is_active FROM services WHERE id = ?");
            $stmt->execute([$serviceId]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$service) {
                $this->errorResponse('Hizmet bulunamadı', 404);
            }
            
            // Toggle
            $newStatus = $service['is_active'] ? 0 : 1;
            
            $stmt = $this->pdo->prepare("UPDATE services SET is_active = ? WHERE id = ?");
            $stmt->execute([$newStatus, $serviceId]);
            
            $this->clearServiceCache();
            
            $this->successResponse('Hizmet durumu güncellendi', ['new_status' => $newStatus]);
            
        } catch (PDOException $e) {
            error_log("Toggle service status error: " . $e->getMessage());
            $this->errorResponse('Veritabanı hatası', 500);
        }
    }
    
    /**
     * Hizmet sil
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
            $serviceId = $this->intPost('service_id');
            
            if ($serviceId <= 0) {
                $this->errorResponse('Geçersiz hizmet ID', 400);
            }
            
            // Varlık kontrolü
            $stmt = $this->pdo->prepare("SELECT service_key FROM services WHERE id = ?");
            $stmt->execute([$serviceId]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$service) {
                $this->errorResponse('Hizmet bulunamadı', 404);
            }
            
            // İlişkili lead kontrolü
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM leads WHERE service_type = ?");
            $stmt->execute([$service['service_key']]);
            $leadCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
            
            if ($leadCount > 0) {
                $this->errorResponse("Bu hizmete ait {$leadCount} lead var. Önce pasif yapın.", 400);
            }
            
            // Sil
            $stmt = $this->pdo->prepare("DELETE FROM services WHERE id = ?");
            $stmt->execute([$serviceId]);
            
            $this->clearServiceCache();
            
            $this->successResponse('Hizmet başarıyla silindi');
            
        } catch (PDOException $e) {
            error_log("Delete service error: " . $e->getMessage());
            $this->errorResponse('Veritabanı hatası', 500);
        }
    }
    
    /**
     * Service cache'i temizle
     */
    private function clearServiceCache(): void
    {
        try {
            $cacheDir = __DIR__ . '/../../storage/cache/';
            $cacheFiles = glob($cacheDir . '*services*');
            foreach ($cacheFiles as $file) {
                @unlink($file);
            }
        } catch (Exception $e) {
            error_log("Clear service cache error: " . $e->getMessage());
        }
    }
}

