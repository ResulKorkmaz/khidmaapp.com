<?php

/**
 * KhidmaApp.com - Ana Sayfa Controller
 * 
 * Ana sayfa ve genel site sayfalarını yönetir
 */

require_once __DIR__ . '/../config/config.php';

class HomeController 
{
    /**
     * Ana sayfa görüntüleme
     */
    public function index() 
    {
        try {
            // Analytics ve tracking için sayfa görüntüleme kayıt et (opsiyonel)
            $this->logPageView('home');
            
            // Ana sayfa view'ini yükle
            include __DIR__ . '/../Views/home.php';
            
        } catch (Exception $e) {
            // Hata durumunda log kaydet ve genel hata sayfası göster
            $this->handleError($e, 'Ana sayfa yüklenemedi');
        }
    }
    
    /**
     * Hakkımızda sayfası
     */
    public function about() 
    {
        try {
            $this->logPageView('about');
            
            // Şimdilik ana sayfaya yönlendir, ileride ayrı sayfa olabilir
            header('Location: /#about');
            exit;
            
        } catch (Exception $e) {
            $this->handleError($e, 'Hakkımızda sayfası yüklenemedi');
        }
    }
    
    /**
     * Hizmetler sayfası
     */
    public function services() 
    {
        try {
            $this->logPageView('services');
            
            // Şimdilik ana sayfadaki hizmetler bölümüne yönlendir
            header('Location: /#services');
            exit;
            
        } catch (Exception $e) {
            $this->handleError($e, 'Hizmetler sayfası yüklenemedi');
        }
    }
    
    /**
     * İletişim sayfası
     */
    public function contact() 
    {
        try {
            $this->logPageView('contact');
            
            // Şimdilik ana sayfadaki iletişim bölümüne yönlendir
            header('Location: /#contact');
            exit;
            
        } catch (Exception $e) {
            $this->handleError($e, 'İletişim sayfası yüklenemedi');
        }
    }
    
    /**
     * Gizlilik Politikası sayfası
     */
    public function privacy() 
    {
        try {
            $this->logPageView('privacy');
            
            $pageTitle = 'سياسة الخصوصية - خدمة';
            $pageDescription = 'سياسة الخصوصية لموقع خدمة - منصة الخدمات المنزلية والتجارية في السعودية';
            $pageKeywords = 'سياسة الخصوصية، الخصوصية، حماية البيانات، السعودية';
            
            include __DIR__ . '/../Views/legal/privacy.php';
            
        } catch (Exception $e) {
            $this->handleError($e, 'Gizlilik politikası sayfası yüklenemedi');
        }
    }
    
    /**
     * Kullanım Şartları sayfası
     */
    public function terms() 
    {
        try {
            $this->logPageView('terms');
            
            $pageTitle = 'شروط الاستخدام - خدمة';
            $pageDescription = 'شروط الاستخدام لموقع خدمة - منصة الخدمات المنزلية والتجارية في السعودية';
            $pageKeywords = 'شروط الاستخدام، الشروط والأحكام، السعودية';
            
            include __DIR__ . '/../Views/legal/terms.php';
            
        } catch (Exception $e) {
            $this->handleError($e, 'Kullanım şartları sayfası yüklenemedi');
        }
    }
    
    /**
     * Çerez Politikası sayfası
     */
    public function cookies() 
    {
        try {
            $this->logPageView('cookies');
            
            $pageTitle = 'ملفات تعريف الارتباط - خدمة';
            $pageDescription = 'سياسة ملفات تعريف الارتباط لموقع خدمة - منصة الخدمات المنزلية والتجارية في السعودية';
            $pageKeywords = 'ملفات تعريف الارتباط، الكوكيز، سياسة الكوكيز، السعودية';
            
            include __DIR__ . '/../Views/legal/cookies.php';
            
        } catch (Exception $e) {
            $this->handleError($e, 'Çerez politikası sayfası yüklenemedi');
        }
    }
    
    /**
     * Teşekkür sayfası
     */
    public function thanks() 
    {
        try {
            $this->logPageView('thanks');
            
            $pageTitle = 'شكراً لك - طلبك قيد المعالجة | خدمة';
            $pageDescription = 'تم استلام طلبك بنجاح. سنتواصل معك خلال دقائق لتقديم أفضل الخدمات.';
            
            include __DIR__ . '/../Views/thanks.php';
            
        } catch (Exception $e) {
            $this->handleError($e, 'Teşekkür sayfası yüklenemedi');
        }
    }
    
    /**
     * 404 Sayfa bulunamadı
     */
    public function notFound() 
    {
        http_response_code(404);
        
        $pageTitle = "الصفحة غير موجودة - خدمة";
        $pageDescription = "الصفحة التي تبحث عنها غير موجودة";
        
        ob_start();
        ?>
        <div class="min-h-screen flex items-center justify-center bg-gradient-soft">
            <div class="text-center">
                <div class="w-32 h-32 mx-auto mb-8">
                    <svg class="w-full h-full text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.034 0-3.9.785-5.291 2.063M19.5 12c0 4.142-3.358 7.5-7.5 7.5s-7.5-3.358-7.5-7.5 3.358-7.5 7.5-7.5 7.5 3.358 7.5 7.5z"/>
                    </svg>
                </div>
                
                <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
                <h2 class="text-2xl font-semibold text-gray-700 mb-6">الصفحة غير موجودة</h2>
                <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
                    عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها إلى مكان آخر
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/" class="btn-primary">
                        <svg class="w-5 h-5 me-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        العودة للرئيسية
                    </a>
                    
                    <a href="#request-service" class="btn-secondary">
                        اطلب خدمة
                    </a>
                </div>
            </div>
        </div>
        <?php
        $content = ob_get_clean();
        include __DIR__ . '/../Views/layouts/base.php';
    }
    
    /**
     * Site Haritası (Sitemap)
     */
    public function sitemap() 
    {
        header('Content-Type: application/xml; charset=utf-8');
        
        $baseUrl = SITE_URL;
        $currentDate = date('Y-m-d');
        
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        ?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
            <url>
                <loc><?= $baseUrl ?>/</loc>
                <lastmod><?= $currentDate ?></lastmod>
                <changefreq>daily</changefreq>
                <priority>1.0</priority>
            </url>
            <url>
                <loc><?= $baseUrl ?>/#services</loc>
                <lastmod><?= $currentDate ?></lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.8</priority>
            </url>
            <url>
                <loc><?= $baseUrl ?>/#about</loc>
                <lastmod><?= $currentDate ?></lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.6</priority>
            </url>
            <url>
                <loc><?= $baseUrl ?>/#contact</loc>
                <lastmod><?= $currentDate ?></lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.5</priority>
            </url>
        </urlset>
        <?php
    }
    
    /**
     * Robots.txt dosyası
     */
    public function robots() 
    {
        header('Content-Type: text/plain; charset=utf-8');
        
        $baseUrl = SITE_URL;
        
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /admin/\n";
        echo "Disallow: /src/\n";
        echo "Disallow: /database/\n";
        echo "\n";
        echo "Sitemap: {$baseUrl}/sitemap.xml\n";
    }
    
    /**
     * Sayfa görüntüleme kaydet (analytics için)
     */
    private function logPageView($page) 
    {
        if (!APP_DEBUG) {
            return; // Production'da daha gelişmiş analytics kullanılabilir
        }
        
        try {
            $pdo = getDatabase();
            
            // Veritabanı bağlantısı yoksa analytics atla
            if ($pdo === null) {
                return;
            }
            
            // Basit analytics tablosu (opsiyonel)
            $sql = "INSERT INTO page_views (page, ip_address, user_agent, visited_at) VALUES (?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $page,
                $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);
            
        } catch (Exception $e) {
            // Analytics hatası kritik değil, sessizce geç
            if (APP_DEBUG) {
                error_log("Analytics error: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Hata yönetimi
     */
    private function handleError($exception, $userMessage = 'Bir hata oluştu') 
    {
        // Log kaydet
        if (APP_DEBUG) {
            error_log("HomeController Error: " . $exception->getMessage());
            error_log("Stack trace: " . $exception->getTraceAsString());
        }
        
        // Production'da kullanıcıya genel hata mesajı göster
        if (!APP_DEBUG) {
            http_response_code(500);
            
            $pageTitle = "خطأ في الخادم - خدمة";
            $pageDescription = "حدث خطأ مؤقت، يرجى المحاولة مرة أخرى";
            
            ob_start();
            ?>
            <div class="min-h-screen flex items-center justify-center bg-gradient-soft">
                <div class="text-center max-w-md">
                    <div class="w-20 h-20 mx-auto mb-6">
                        <svg class="w-full h-full text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">حدث خطأ مؤقت</h1>
                    <p class="text-gray-600 mb-6">
                        نعتذر عن هذا الخطأ، فريقنا يعمل على حل المشكلة
                    </p>
                    
                    <a href="/" class="btn-primary">
                        العودة للرئيسية
                    </a>
                </div>
            </div>
            <?php
            $content = ob_get_clean();
            include __DIR__ . '/../Views/layouts/base.php';
        } else {
            // Debug modunda detaylı hata göster
            echo "<div style='padding: 20px; background: #f8f9fa; border: 1px solid #dee2e6; margin: 20px;'>";
            echo "<h3 style='color: #dc3545;'>Debug Error:</h3>";
            echo "<p><strong>Message:</strong> " . htmlspecialchars($exception->getMessage()) . "</p>";
            echo "<p><strong>File:</strong> " . htmlspecialchars($exception->getFile()) . "</p>";
            echo "<p><strong>Line:</strong> " . $exception->getLine() . "</p>";
            echo "<pre style='background: #f1f3f4; padding: 10px; overflow: auto;'>" . htmlspecialchars($exception->getTraceAsString()) . "</pre>";
            echo "</div>";
        }
    }
}


