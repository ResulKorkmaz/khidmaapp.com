<?php

/**
 * KhidmaApp.com - Ana Router Dosyası
 * 
 * Tüm HTTP isteklerini yönlendirir ve uygun controller'lara iletir.
 * Bu dosya projenin giriş noktasıdır.
 */

// Hata raporlama ve temel ayarlar
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Konfigürasyon dosyasını yükle
require_once __DIR__ . '/../src/config/config.php';

// Start session
startSession();

// Controller dosyalarını yükle
require_once __DIR__ . '/../src/Controllers/HomeController.php';
require_once __DIR__ . '/../src/Controllers/ServiceController.php';

// Request URI'yi al ve temizle
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Query string'i kaldır (URL'den ? sonrasını)
$path = parse_url($requestUri, PHP_URL_PATH);

// Public klasör prefix'ini kaldır (eğer varsa)
$path = str_replace('/public', '', $path);

// Başındaki ve sonundaki slash'leri temizle
$path = trim($path, '/');

// Boş path'i ana sayfa olarak kabul et
if (empty($path)) {
    $path = '/';
} else {
    $path = '/' . $path;
}

// Güvenlik kontrolleri
// Directory traversal attacks'e karşı koruma
if (strpos($path, '..') !== false || strpos($path, './') !== false) {
    http_response_code(403);
    die('Forbidden');
}

// Controller ve method belirleme
$controller = null;
$method = 'index';
$params = [];

try {
    // Hizmet detay sayfaları kontrolü (switch'ten önce)
    if (preg_match('#^/services/([a-z]+)$#', $path, $matches)) {
        $serviceKey = $matches[1] ?? null;
        if ($serviceKey) {
            $controller = new ServiceController();
            $method = 'show';
            $params = [$serviceKey];
        } else {
            $controller = new HomeController();
            $method = 'notFound';
        }
    } else {
        // Router logic
        switch ($path) {
        // Ana sayfa rotaları
        case '/':
        case '/home':
        case '/index':
            $controller = new HomeController();
            $method = 'index';
            break;
            
        // Hakkımızda sayfası
        case '/about':
        case '/hakkimizda':
            $controller = new HomeController();
            $method = 'about';
            break;
            
        // Hizmetler sayfası
        case '/services':
        case '/hizmetler':
            $controller = new HomeController();
            $method = 'services';
            break;
            
        // İletişim sayfası
        case '/contact':
        case '/iletisim':
            $controller = new HomeController();
            $method = 'contact';
            break;
            
        // Teşekkür sayfası
        case '/thanks':
        case '/thank-you':
        case '/شكراً':
            $controller = new HomeController();
            $method = 'thanks';
            break;
            
        // Gizlilik Politikası
        case '/privacy':
        case '/privacy-policy':
        case '/سياسة-الخصوصية':
            $controller = new HomeController();
            $method = 'privacy';
            break;
            
        // Kullanım Şartları
        case '/terms':
        case '/terms-of-service':
        case '/شروط-الاستخدام':
            $controller = new HomeController();
            $method = 'terms';
            break;
            
        // Çerez Politikası
        case '/cookies':
        case '/cookie-policy':
        case '/ملفات-تعريف-الارتباط':
            $controller = new HomeController();
            $method = 'cookies';
            break;
            
        // Lead işlemleri (dinamik olarak yüklenecek)
        case '/lead/submit':
            if ($requestMethod === 'POST') {
                // LeadController'ı gerektiğinde yükle
                if (file_exists(__DIR__ . '/../src/Controllers/LeadController.php')) {
                    require_once __DIR__ . '/../src/Controllers/LeadController.php';
                    
                    if (class_exists('LeadController')) {
                        $controller = new LeadController();
                        $method = 'store';
                    } else {
                        throw new Exception('LeadController sınıfı bulunamadı');
                    }
                } else {
                    throw new Exception('LeadController dosyası bulunamadı');
                }
            } else {
                // GET istekleri ana sayfaya yönlendir
                header('Location: /#request-service');
                exit;
            }
            break;
            
        // ============================================
        // PROVIDER (USTA) ROUTES
        // ============================================
        
        // Provider login
        case '/provider/login':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'login';
            break;
            
        // Provider register
        case '/provider/register':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'register';
            break;
            
        // Provider dashboard
        case '/provider/dashboard':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'dashboard';
            break;
            
        // Provider leads
        case '/provider/leads':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'leads';
            break;
            
        // Provider request lead (AJAX)
        case '/provider/request-lead':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'requestLead';
            break;
            
        // Provider my requests
        case '/provider/my-requests':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'myRequests';
            break;
            
        // Provider profile
        case '/provider/profile':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'profile';
            break;
            
        // Provider settings
        case '/provider/settings':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'settings';
            break;
            
        // Provider messages
        case '/provider/messages':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'messages';
            break;
            
        // Mark message as read (AJAX)
        case '/provider/mark-message-read':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'markMessageRead';
            break;
            
        // Delete message (AJAX)
        case '/provider/delete-message':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'deleteMessage';
            break;
            
        // Provider purchase package
        case (preg_match('/^\/provider\/purchase\/\d+$/', $path) ? $path : false):
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'purchasePackage';
            break;
            
        // Provider logout
        case '/provider/logout':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'logout';
            break;
            
        // Mark lead as viewed
        case '/provider/mark-lead-viewed':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'markLeadViewed';
            break;
            
        // Hide lead (soft delete for UI)
        case '/provider/hide-lead':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'hideLead';
            break;
            
        // Hidden leads (trash/archive)
        case '/provider/hidden-leads':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'hiddenLeads';
            break;
            
        // Browse lead packages (Stripe)
        case '/provider/packages':
        case '/provider/browse-packages':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'browsePackages';
            break;
            
        // Create Stripe Checkout Session
        case '/provider/create-checkout-session':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'createCheckoutSession';
            break;
            
        // Purchase success
        case '/provider/purchase/success':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'purchaseSuccess';
            break;
            
        // Purchase cancel
        case '/provider/purchase/cancel':
            require_once __DIR__ . '/../src/Controllers/ProviderController.php';
            $controller = new ProviderController();
            $method = 'purchaseCancel';
            break;
            
        // ============================================
        // WEBHOOK ROUTES
        // ============================================
        
        // Stripe Webhook
        case '/webhook/stripe':
            require_once __DIR__ . '/../src/Controllers/WebhookController.php';
            $controller = new WebhookController();
            $method = 'stripe';
            break;
            
        // ============================================
        // ADMIN ROUTES
        // ============================================
        
        // Admin paneli
        case '/admin':
        case '/admin/':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'index';
            break;
            
        // Admin login
        case '/admin/login':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'login';
            break;
            
        // Admin logout
        case '/admin/logout':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'logout';
            break;
            
        // Admin leads
        case '/admin/leads':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'leads';
            break;
            
        // Admin update lead status
        case '/admin/leads/update-status':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'updateLeadStatus';
            break;
            
        // Admin mark lead as sent to provider
        case '/admin/leads/mark-as-sent':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'markAsSent';
            break;
            
        // Admin toggle sent to provider status
        case '/admin/leads/toggle-sent':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'toggleSentToProvider';
            break;
            
        // Admin export leads
        case '/admin/leads/export':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'exportLeads';
            break;
            
        case '/admin/leads/assign-to-provider':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'assignLeadsToProvider';
            break;
            
        case '/admin/leads/withdraw-from-provider':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'withdrawLeadFromProvider';
            break;
            
        case '/admin/leads/delete':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'deleteLead';
            break;
            
        case '/admin/leads/restore':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'restoreLead';
            break;
            
        case '/admin/leads/permanently-delete':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'permanentlyDeleteLead';
            break;
            
        case '/admin/providers/delete':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'deleteProvider';
            break;
            
        case '/admin/providers/search':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'searchProviders';
            break;
            
        // Admin providers (ustalar)
        case '/admin/providers':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'providers';
            break;
            
        // Admin purchases (satın alımlar)
        case '/admin/purchases':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'purchases';
            break;
        
        // Kullanıcı Yönetimi (Super Admin Only)
        case '/admin/users':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'users';
            break;
        case '/admin/users/create':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'createUser';
            break;
        case '/admin/users/delete':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'deleteUser';
            break;
        case '/admin/users/toggle-status':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'toggleUserStatus';
            break;
            
        // Admin services management
        case '/admin/services':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'manageServices';
            break;
            
        case '/admin/services/create':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'createService';
            break;
            
        case '/admin/services/update':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'updateService';
            break;
            
        case '/admin/services/toggle':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'toggleServiceStatus';
            break;
            
        case '/admin/services/delete':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'deleteService';
            break;
            
        // Admin lead packages management
        case '/admin/lead-packages':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'manageLeadPackages';
            break;
            
        case '/admin/lead-packages/create':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'createLeadPackage';
            break;
            
        case '/admin/lead-packages/update':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'updateLeadPackage';
            break;
            
        case '/admin/lead-packages/toggle':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'toggleLeadPackageStatus';
            break;
            
        case '/admin/lead-packages/delete':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'deleteLeadPackage';
            break;
            
        // Admin lead requests management
        case '/admin/lead-requests':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'leadRequests';
            break;
            
        // Admin provider messages
        case '/admin/provider-messages':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'providerMessages';
            break;
            
        case '/admin/provider-messages/send':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'sendMessage';
            break;
            
        case '/admin/provider-messages/history':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'getProviderMessageHistory';
            break;
            
        case '/admin/lead-requests/send':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'sendLeadManually';
            break;
            
        case '/admin/lead-requests/count':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'getPendingRequestsCount';
            break;
            
        // Admin provider detail
        case (preg_match('/^\/admin\/providers\/\d+$/', $path) ? $path : false):
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'providerDetail';
            break;
            
        // Admin change provider status
        case '/admin/providers/change-status':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'changeProviderStatus';
            break;
            
        // Admin send lead to provider
        case '/admin/leads/send-to-provider':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'sendLeadToProvider';
            break;
            
        // Admin mark lead as sent via WhatsApp
        case '/admin/leads/mark-sent-via-whatsapp':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'markLeadAsSentViaWhatsApp';
            break;
            
        // Admin get available providers
        case '/admin/leads/get-available-providers':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'getAvailableProviders';
            break;
            
        // Admin approve provider
        case '/admin/providers/approve':
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'approveProvider';
            break;
            
        // Admin lead detail
        case '/admin/lead/detail':
        case (preg_match('#^/admin/lead/(\d+)$#', $path, $matches) ? true : false):
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
            $method = 'leadDetail';
            if (!empty($matches[1])) {
                $_GET['id'] = $matches[1];
            }
            break;
            
        // SEO dosyaları
        case '/sitemap.xml':
            $controller = new HomeController();
            $method = 'sitemap';
            break;
            
        case '/robots.txt':
            $controller = new HomeController();
            $method = 'robots';
            break;
            
        // API endpoints (gelecekte kullanılabilir)
        case (preg_match('#^/api/(.+)$#', $path, $matches) ? true : false):
            // API endpoint'leri için ayrı bir controller kullanılabilir
            http_response_code(501);
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'API endpoints not implemented yet',
                'message' => 'API özellikleri henüz implementeed edilmemiş'
            ]);
            exit;
            break;
            
        // Statik dosyalar (CSS, JS, Images) - normalde web server tarafından handle edilir
        case (preg_match('#\.(css|js|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|eot)$#i', $path) ? true : false):
            // Bu durumda dosya bulunamadı
            http_response_code(404);
            header('Content-Type: text/plain');
            echo 'File not found';
            exit;
            break;
            
        // Default - 404 sayfası
        default:
            $controller = new HomeController();
            $method = 'notFound';
            break;
        }
    }
    
    // Controller ve method'un varlığını kontrol et
    if ($controller === null) {
        throw new Exception('Controller bulunamadı');
    }
    
    if (!method_exists($controller, $method)) {
        throw new Exception("Method '$method' bulunamadı");
    }
    
    // HTTP method kontrolü (güvenlik için)
    $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
    if (!in_array($requestMethod, $allowedMethods)) {
        http_response_code(405);
        header('Allow: ' . implode(', ', $allowedMethods));
        die('Method Not Allowed');
    }
    
    // CSRF koruması (POST istekleri için)
    if ($requestMethod === 'POST' && !in_array($path, ['/admin/login'])) {
        // CSRF token'ı hem form data hem JSON body'den kontrol et
        $csrfToken = $_POST['csrf_token'] ?? '';
        
        // Eğer form data'da yoksa JSON body'den al
        if (empty($csrfToken)) {
            $jsonInput = file_get_contents('php://input');
            $jsonData = json_decode($jsonInput, true);
            $csrfToken = $jsonData['csrf_token'] ?? '';
            
            // JSON body'yi global değişkene kaydet (controller'da kullanılabilmesi için)
            $GLOBALS['_JSON_INPUT'] = $jsonData;
        }
        
        if (!verifyCsrfToken($csrfToken)) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Invalid CSRF token',
                'error' => 'CSRF token doğrulaması başarısız'
            ]);
            exit;
        }
    }
    
    // Controller method'unu çalıştır
    call_user_func_array([$controller, $method], $params);
    
} catch (Exception $e) {
    // Hata yönetimi
    if (APP_DEBUG) {
        // Debug modunda detaylı hata göster
        echo "<div style='padding: 20px; background: #fff3cd; border: 1px solid #ffeaa7; margin: 20px; border-radius: 8px;'>";
        echo "<h3 style='color: #856404; margin-top: 0;'>🐛 Debug Error</h3>";
        echo "<p><strong>Path:</strong> " . htmlspecialchars($path) . "</p>";
        echo "<p><strong>Method:</strong> " . htmlspecialchars($requestMethod) . "</p>";
        echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
        echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
        echo "<details><summary>Stack Trace</summary>";
        echo "<pre style='background: #f8f9fa; padding: 10px; overflow: auto; font-size: 12px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        echo "</details>";
        echo "</div>";
    } else {
        // Production modunda genel hata sayfası göster
        error_log("Router Error: " . $e->getMessage() . " | Path: " . $path);
        
        $controller = new HomeController();
        $controller->notFound();
    }
}

/**
 * Basit benchmarking (development için)
 */
if (APP_DEBUG && isset($_GET['debug'])) {
    $endTime = microtime(true);
    $executionTime = ($endTime - $_SERVER['REQUEST_TIME_FLOAT']) * 1000;
    $memoryUsage = memory_get_peak_usage(true) / 1024 / 1024;
    
    echo "<div style='position: fixed; bottom: 10px; right: 10px; background: rgba(0,0,0,0.8); color: white; padding: 10px; border-radius: 5px; font-size: 12px; z-index: 9999;'>";
    echo "<strong>Debug Info:</strong><br>";
    echo "Execution Time: " . round($executionTime, 2) . "ms<br>";
    echo "Memory Usage: " . round($memoryUsage, 2) . "MB<br>";
    echo "Path: " . htmlspecialchars($path) . "<br>";
    echo "Method: " . htmlspecialchars($requestMethod);
    echo "</div>";
}


