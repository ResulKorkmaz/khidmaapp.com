<?php

/**
 * KhidmaApp.com - Application Entry Point
 * 
 * Tüm HTTP isteklerini yönlendirir.
 * Temiz, profesyonel ve kolay yönetilebilir yapı.
 */

// ============================================
// BOOTSTRAP
// ============================================

// Hata raporlama
error_reporting(E_ALL);

// Konfigürasyon
require_once __DIR__ . '/../src/config/config.php';

// Display errors sadece development'ta
ini_set('display_errors', APP_DEBUG ? 1 : 0);

// Session başlat
startSession();

// ============================================
// REQUEST PARSING
// ============================================

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Path'i parse et
$path = parse_url($requestUri, PHP_URL_PATH);
$path = str_replace('/public', '', $path);
$path = '/' . trim($path, '/');
if ($path === '/') {
    // Root path olarak bırak
} elseif (substr($path, -1) === '/') {
    $path = rtrim($path, '/');
}

// ============================================
// SECURITY CHECKS
// ============================================

// Directory traversal koruması
if (strpos($path, '..') !== false) {
    http_response_code(403);
    die('Forbidden');
}

// Static dosya kontrolü
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|eot|map)$/i', $path)) {
    http_response_code(404);
    die('File not found');
}

// ============================================
// CONTROLLER AUTOLOADING
// ============================================

// Temel controller'ları yükle
require_once __DIR__ . '/../src/Controllers/HomeController.php';
require_once __DIR__ . '/../src/Controllers/ServiceController.php';

/**
 * Controller'ı lazy load et
 */
function loadController(string $name): void
{
    static $loaded = [];
    
    if (isset($loaded[$name])) {
        return;
    }
    
    // Admin controller'ları
    if (strpos($name, 'Admin') === 0 && $name !== 'AdminController') {
        require_once __DIR__ . '/../src/Controllers/Admin/AdminControllerLoader.php';
        $loaded[$name] = true;
        return;
    }
    
    // Provider controller'ları
    if (strpos($name, 'Provider') === 0 && $name !== 'ProviderController') {
        require_once __DIR__ . '/../src/Controllers/Provider/ProviderControllerLoader.php';
        $loaded[$name] = true;
        return;
    }
    
    // Eski controller'lar (geçiş dönemi için)
    $file = __DIR__ . '/../src/Controllers/' . $name . '.php';
    if (file_exists($file)) {
        require_once $file;
        $loaded[$name] = true;
    }
}

// ============================================
// ROUTE DEFINITIONS
// ============================================

$routes = [
    // ========== PUBLIC ROUTES ==========
    'GET:/' => ['HomeController', 'index'],
    'GET:/home' => ['HomeController', 'index'],
    'GET:/about' => ['HomeController', 'about'],
    'GET:/services' => ['HomeController', 'services'],
    'GET:/contact' => ['HomeController', 'contact'],
    'GET:/thanks' => ['HomeController', 'thanks'],
    'GET:/privacy' => ['HomeController', 'privacy'],
    'GET:/terms' => ['HomeController', 'terms'],
    'GET:/cookies' => ['HomeController', 'cookies'],
    'GET:/sitemap.xml' => ['HomeController', 'sitemap'],
    'GET:/robots.txt' => ['HomeController', 'robots'],
    
    // Lead submission
    'POST:/lead/submit' => ['LeadController', 'store'],
    
    // ========== PROVIDER AUTH ==========
    'POST:/provider/login' => ['ProviderAuthController', 'login'],
    'POST:/provider/register' => ['ProviderAuthController', 'register'],
    'GET:/provider/logout' => ['ProviderAuthController', 'logout'],
    
    // ========== PROVIDER DASHBOARD ==========
    'GET:/provider/dashboard' => ['ProviderDashboardController', 'index', 'provider.auth'],
    
    // ========== PROVIDER LEADS ==========
    'GET:/provider/leads' => ['ProviderLeadController', 'index', 'provider.auth'],
    'GET:/provider/my-requests' => ['ProviderLeadController', 'myRequests', 'provider.auth'],
    'GET:/provider/hidden-leads' => ['ProviderLeadController', 'hidden', 'provider.auth'],
    'POST:/provider/request-lead' => ['ProviderLeadController', 'request', 'provider.auth'],
    'POST:/provider/mark-lead-viewed' => ['ProviderLeadController', 'markViewed', 'provider.auth'],
    'POST:/provider/hide-lead' => ['ProviderLeadController', 'hide', 'provider.auth'],
    'POST:/provider/restore-lead' => ['ProviderLeadController', 'restore', 'provider.auth'],
    
    // ========== PROVIDER PROFILE ==========
    'GET:/provider/profile' => ['ProviderProfileController', 'index', 'provider.auth'],
    'POST:/provider/profile' => ['ProviderProfileController', 'index', 'provider.auth'],
    'GET:/provider/settings' => ['ProviderProfileController', 'settings', 'provider.auth'],
    'POST:/provider/settings' => ['ProviderProfileController', 'settings', 'provider.auth'],
    
    // ========== PROVIDER MESSAGES ==========
    'GET:/provider/messages' => ['ProviderMessageController', 'index', 'provider.auth'],
    'POST:/provider/mark-message-read' => ['ProviderMessageController', 'markRead', 'provider.auth'],
    'POST:/provider/delete-message' => ['ProviderMessageController', 'delete', 'provider.auth'],
    
    // ========== PROVIDER PACKAGES ==========
    'GET:/provider/packages' => ['ProviderPurchaseController', 'packages', 'provider.auth'],
    'GET:/provider/browse-packages' => ['ProviderPurchaseController', 'packages', 'provider.auth'],
    'GET:/provider/lead-policy' => ['ProviderPurchaseController', 'leadPolicy', 'provider.auth'],
    'POST:/provider/create-checkout-session' => ['ProviderPurchaseController', 'createCheckoutSession', 'provider.auth'],
    'GET:/provider/purchase/success' => ['ProviderPurchaseController', 'success', 'provider.auth'],
    'GET:/provider/purchase/cancel' => ['ProviderPurchaseController', 'cancel', 'provider.auth'],
    
    // ========== ADMIN AUTH ==========
    'GET:/admin/login' => ['AdminAuthController', 'login'],
    'POST:/admin/login' => ['AdminAuthController', 'login'],
    'GET:/admin/logout' => ['AdminAuthController', 'logout'],
    
    // ========== ADMIN DASHBOARD ==========
    'GET:/admin' => ['AdminDashboardController', 'index', 'admin.auth'],
    'GET:/admin/' => ['AdminDashboardController', 'index', 'admin.auth'],
    
    // ========== ADMIN LEADS ==========
    'GET:/admin/leads' => ['AdminLeadController', 'index', 'admin.auth'],
    'GET:/admin/lead/detail' => ['AdminLeadController', 'detail', 'admin.auth'],
    'POST:/admin/leads/update-status' => ['AdminLeadController', 'updateStatus', 'admin.auth'],
    'POST:/admin/leads/mark-as-sent' => ['AdminLeadController', 'markAsSent', 'admin.auth'],
    'POST:/admin/leads/toggle-sent' => ['AdminLeadController', 'toggleSent', 'admin.auth'],
    'POST:/admin/leads/delete' => ['AdminLeadController', 'delete', 'admin.auth'],
    'POST:/admin/leads/restore' => ['AdminLeadController', 'restore', 'admin.auth'],
    'POST:/admin/leads/permanently-delete' => ['AdminLeadController', 'permanentDelete', 'admin.auth'],
    'POST:/admin/leads/send-to-provider' => ['AdminLeadController', 'sendToProvider', 'admin.auth'],
    'POST:/admin/leads/withdraw-from-provider' => ['AdminLeadController', 'withdrawFromProvider', 'admin.auth'],
    'POST:/admin/leads/assign-to-provider' => ['AdminController', 'assignLeadsToProvider', 'admin.auth'],
    'GET:/admin/leads/export' => ['AdminController', 'exportLeads', 'admin.auth'],
    'POST:/admin/leads/mark-sent-via-whatsapp' => ['AdminController', 'markLeadAsSentViaWhatsApp', 'admin.auth'],
    'GET:/admin/leads/get-available-providers' => ['AdminController', 'getAvailableProviders', 'admin.auth'],
    
    // ========== ADMIN PROVIDERS ==========
    'GET:/admin/providers' => ['AdminProviderController', 'index', 'admin.auth'],
    'POST:/admin/providers/approve' => ['AdminProviderController', 'approve', 'admin.auth'],
    'POST:/admin/providers/change-status' => ['AdminProviderController', 'changeStatus', 'admin.auth'],
    'POST:/admin/providers/delete' => ['AdminProviderController', 'delete', 'admin.auth'],
    'GET:/admin/providers/search' => ['AdminProviderController', 'search', 'admin.auth'],
    
    // ========== ADMIN SERVICES ==========
    'GET:/admin/services' => ['AdminServiceController', 'index', 'admin.auth'],
    'POST:/admin/services/create' => ['AdminServiceController', 'create', 'admin.auth'],
    'POST:/admin/services/update' => ['AdminServiceController', 'update', 'admin.auth'],
    'POST:/admin/services/toggle' => ['AdminServiceController', 'toggleStatus', 'admin.auth'],
    'POST:/admin/services/delete' => ['AdminServiceController', 'delete', 'admin.auth'],
    
    // ========== ADMIN PACKAGES ==========
    'GET:/admin/lead-packages' => ['AdminPackageController', 'index', 'admin.auth'],
    'POST:/admin/lead-packages/create' => ['AdminPackageController', 'create', 'admin.auth'],
    'POST:/admin/lead-packages/update' => ['AdminPackageController', 'update', 'admin.auth'],
    'POST:/admin/lead-packages/toggle' => ['AdminPackageController', 'toggleStatus', 'admin.auth'],
    'POST:/admin/lead-packages/delete' => ['AdminPackageController', 'delete', 'admin.auth'],
    
    // ========== ADMIN PURCHASES ==========
    'GET:/admin/purchases' => ['AdminPurchaseController', 'index', 'admin.auth'],
    'GET:/admin/purchases/detail' => ['AdminPurchaseController', 'purchaseDetail', 'admin.auth'],
    'GET:/admin/lead-requests' => ['AdminPurchaseController', 'leadRequests', 'admin.auth'],
    'POST:/admin/lead-requests/send' => ['AdminPurchaseController', 'sendLeadManually', 'admin.auth'],
    'GET:/admin/lead-requests/count' => ['AdminPurchaseController', 'pendingRequestsCount', 'admin.auth'],
    'GET:/admin/api/available-leads' => ['AdminPurchaseController', 'availableLeads', 'admin.auth'],
    
    // ========== ADMIN REFUNDS ==========
    'GET:/admin/refunds' => ['AdminPurchaseController', 'refunds', 'admin.auth'],
    'POST:/admin/refunds/create' => ['AdminPurchaseController', 'createRefund', 'admin.auth'],
    
    // ========== ADMIN MESSAGES ==========
    'GET:/admin/provider-messages' => ['AdminMessageController', 'index', 'admin.auth'],
    'POST:/admin/provider-messages/send' => ['AdminMessageController', 'send', 'admin.auth'],
    'POST:/admin/provider-messages/send-bulk' => ['AdminMessageController', 'sendBulk', 'admin.auth'],
    'POST:/admin/provider-messages/send-lead' => ['AdminMessageController', 'sendLead', 'admin.auth'],
    'GET:/admin/provider-messages/history' => ['AdminMessageController', 'history', 'admin.auth'],
    'GET:/admin/provider-messages/filter-providers' => ['AdminMessageController', 'filterProviders', 'admin.auth'],
    
    // ========== ADMIN USERS ==========
    'GET:/admin/users' => ['AdminUserController', 'index', 'admin.auth'],
    'GET:/admin/users/create' => ['AdminUserController', 'createForm', 'admin.auth'],
    'POST:/admin/users/create' => ['AdminUserController', 'create', 'admin.auth'],
    'POST:/admin/users/delete' => ['AdminUserController', 'delete', 'admin.auth'],
    'POST:/admin/users/toggle-status' => ['AdminUserController', 'toggleStatus', 'admin.auth'],
    
    // ========== WEBHOOKS ==========
    'POST:/webhook/stripe' => ['WebhookController', 'stripe'],
];

// ============================================
// DYNAMIC ROUTE PATTERNS
// ============================================

$dynamicRoutes = [
    // Service detail pages
    '#^GET:/services/([a-z]+)$#' => ['ServiceController', 'show'],
    
    // Admin lead detail
    '#^GET:/admin/lead/(\d+)$#' => ['AdminLeadController', 'detail', 'admin.auth'],
    
    // Admin provider detail
    '#^GET:/admin/providers/(\d+)$#' => ['AdminProviderController', 'detail', 'admin.auth'],
    
    // Provider purchase package
    '#^GET:/provider/purchase/(\d+)$#' => ['ProviderPurchaseController', 'purchase', 'provider.auth'],
];

// ============================================
// MIDDLEWARE FUNCTIONS
// ============================================

function runMiddleware(string $middleware): void
{
    switch ($middleware) {
        case 'admin.auth':
            requireAdminLogin();
            break;
            
        case 'provider.auth':
            if (!isset($_SESSION['provider_id'])) {
                $_SESSION['error'] = 'يجب تسجيل الدخول أولاً';
                header('Location: /');
                exit;
            }
            break;
    }
}

function checkCsrf(string $path): void
{
    // Login sayfası hariç tüm POST isteklerinde CSRF kontrolü
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }
    
    // Webhook'lar için CSRF kontrolü yapma
    if (strpos($path, '/webhook/') === 0) {
        return;
    }
    
    // Login ve register sayfaları için CSRF kontrolü yapma
    $excludedPaths = [
        '/admin/login',
        '/provider/login',
        '/provider/register',
        '/lead/submit',
        '/provider/create-checkout-session', // Stripe checkout için geçici muafiyet
    ];
    
    if (in_array($path, $excludedPaths)) {
        return;
    }
    
    $csrfToken = $_POST['csrf_token'] ?? '';
    
    if (empty($csrfToken)) {
        $jsonInput = file_get_contents('php://input');
        $jsonData = json_decode($jsonInput, true);
        $csrfToken = $jsonData['csrf_token'] ?? '';
        $GLOBALS['_JSON_INPUT'] = $jsonData;
    }
    
    if (!verifyCsrfToken($csrfToken)) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
        exit;
    }
}

// ============================================
// ROUTE MATCHING & DISPATCH
// ============================================

try {
    $routeKey = $requestMethod . ':' . $path;
    $params = [];
    $route = null;
    
    // Önce statik route'lara bak
    if (isset($routes[$routeKey])) {
        $route = $routes[$routeKey];
    } else {
        // Dinamik route'lara bak
        foreach ($dynamicRoutes as $pattern => $routeData) {
            if (preg_match($pattern, $routeKey, $matches)) {
                $route = $routeData;
                array_shift($matches); // İlk eleman tam eşleşme
                $params = $matches;
                
                // GET parametresi olarak da ekle (geriye uyumluluk)
                if (!empty($params[0])) {
                    $_GET['id'] = $params[0];
                }
                break;
            }
        }
    }
    
    // Route bulunamadı
    if (!$route) {
        $controller = new HomeController();
        $controller->notFound();
        exit;
    }
    
    // Route bilgilerini ayıkla
    $controllerName = $route[0];
    $methodName = $route[1];
    $middleware = $route[2] ?? null;
    
    // CSRF kontrolü
    checkCsrf($path);
    
    // Middleware çalıştır
    if ($middleware) {
        runMiddleware($middleware);
    }
    
    // Controller'ı yükle
    loadController($controllerName);
    
    // Controller instance oluştur
    if (!class_exists($controllerName)) {
        throw new Exception("Controller not found: {$controllerName}");
    }
    
    $controller = new $controllerName();
    
    // Method kontrolü
    if (!method_exists($controller, $methodName)) {
        throw new Exception("Method not found: {$controllerName}::{$methodName}");
    }
    
    // Method'u çağır
    call_user_func_array([$controller, $methodName], $params);
    
} catch (Exception $e) {
    // Hata yönetimi
    if (APP_DEBUG) {
        echo "<div style='padding: 20px; background: #fff3cd; border: 1px solid #ffeaa7; margin: 20px; border-radius: 8px;'>";
        echo "<h3 style='color: #856404;'>🐛 Debug Error</h3>";
        echo "<p><strong>Path:</strong> " . htmlspecialchars($path) . "</p>";
        echo "<p><strong>Method:</strong> " . htmlspecialchars($requestMethod) . "</p>";
        echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . ":" . $e->getLine() . "</p>";
        echo "<details><summary>Stack Trace</summary>";
        echo "<pre style='background: #f8f9fa; padding: 10px; font-size: 12px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        echo "</details></div>";
    } else {
        error_log("Router Error: " . $e->getMessage() . " | Path: " . $path);
        $controller = new HomeController();
        $controller->notFound();
    }
}

// ============================================
// DEBUG INFO (Development only)
// ============================================

if (APP_DEBUG && isset($_GET['debug'])) {
    $executionTime = (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000;
    $memoryUsage = memory_get_peak_usage(true) / 1024 / 1024;
    
    echo "<div style='position: fixed; bottom: 10px; right: 10px; background: rgba(0,0,0,0.8); color: white; padding: 10px; border-radius: 5px; font-size: 12px; z-index: 9999;'>";
    echo "<strong>Debug:</strong> " . round($executionTime, 2) . "ms | " . round($memoryUsage, 2) . "MB";
    echo "</div>";
}
