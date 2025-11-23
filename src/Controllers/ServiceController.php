<?php

/**
 * KhidmaApp.com - Service Controller
 * 
 * Hizmet detay sayfalarını yönetir
 */

require_once __DIR__ . '/../config/config.php';

class ServiceController 
{
    /**
     * Hizmet detay sayfası (SEO-Optimized)
     */
    public function show($serviceKey) 
    {
        try {
            // Servis kontrolü
            $services = getServiceTypes();
            if (!isset($services[$serviceKey])) {
                // Geçersiz servis - 404'e yönlendir
                $controller = new HomeController();
                $controller->notFound();
                return;
            }
            
            // Hizmet sayfası dosya yolu
            $servicePage = __DIR__ . '/../Views/services/' . $serviceKey . '.php';
            
            // Sayfa yoksa ana sayfaya yönlendir
            if (!file_exists($servicePage)) {
                header('Location: /#services');
                exit;
            }
            
            // Analytics
            $this->logServiceView($serviceKey);
            
            // SEO-optimized view'i yükle
            include $servicePage;
            
        } catch (Exception $e) {
            error_log("Service detail error: " . $e->getMessage());
            
            // Hata durumunda ana sayfaya yönlendir
            header('Location: /');
            exit;
        }
    }
    
    /**
     * Servis görüntüleme kaydı (analytics)
     */
    private function logServiceView($serviceKey) 
    {
        if (!APP_DEBUG) {
            return; // Production'da daha gelişmiş analytics kullanılabilir
        }
        
        try {
            $pdo = getDatabase();
            
            if ($pdo === null) {
                return;
            }
            
            // Basit analytics (service_views tablosu gerekli)
            $sql = "INSERT INTO service_views (service_type, ip_address, user_agent, viewed_at) 
                    VALUES (?, ?, ?, NOW()) 
                    ON DUPLICATE KEY UPDATE view_count = view_count + 1";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $serviceKey,
                $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);
            
        } catch (Exception $e) {
            // Analytics hatası kritik değil
            if (APP_DEBUG) {
                error_log("Service analytics error: " . $e->getMessage());
            }
        }
    }
}


