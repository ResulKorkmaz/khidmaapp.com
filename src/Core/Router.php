<?php

/**
 * KhidmaApp.com - Router
 * 
 * Modern, temiz ve genişletilebilir routing sistemi
 */

class Router
{
    private array $routes = [];
    private array $middlewares = [];
    private string $currentGroup = '';
    private array $currentMiddlewares = [];
    
    /**
     * GET route ekle
     */
    public function get(string $path, $handler): self
    {
        return $this->addRoute('GET', $path, $handler);
    }
    
    /**
     * POST route ekle
     */
    public function post(string $path, $handler): self
    {
        return $this->addRoute('POST', $path, $handler);
    }
    
    /**
     * Hem GET hem POST route ekle
     */
    public function any(string $path, $handler): self
    {
        $this->addRoute('GET', $path, $handler);
        return $this->addRoute('POST', $path, $handler);
    }
    
    /**
     * Route grubu oluştur
     */
    public function group(string $prefix, array $middlewares, callable $callback): self
    {
        $previousGroup = $this->currentGroup;
        $previousMiddlewares = $this->currentMiddlewares;
        
        $this->currentGroup = $previousGroup . $prefix;
        $this->currentMiddlewares = array_merge($previousMiddlewares, $middlewares);
        
        $callback($this);
        
        $this->currentGroup = $previousGroup;
        $this->currentMiddlewares = $previousMiddlewares;
        
        return $this;
    }
    
    /**
     * Route ekle
     */
    private function addRoute(string $method, string $path, $handler): self
    {
        $fullPath = $this->currentGroup . $path;
        
        // Path'i regex pattern'e çevir
        $pattern = $this->pathToPattern($fullPath);
        
        $this->routes[] = [
            'method' => $method,
            'path' => $fullPath,
            'pattern' => $pattern,
            'handler' => $handler,
            'middlewares' => $this->currentMiddlewares
        ];
        
        return $this;
    }
    
    /**
     * Path'i regex pattern'e çevir
     */
    private function pathToPattern(string $path): string
    {
        // {id} gibi parametreleri regex'e çevir
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
    
    /**
     * Request'i işle
     */
    public function dispatch(string $method, string $path): void
    {
        // Route'u bul
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            if (preg_match($route['pattern'], $path, $matches)) {
                // URL parametrelerini ayıkla
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                // Middlewares'leri çalıştır
                foreach ($route['middlewares'] as $middleware) {
                    $this->runMiddleware($middleware);
                }
                
                // Handler'ı çalıştır
                $this->runHandler($route['handler'], $params);
                return;
            }
        }
        
        // Route bulunamadı
        $this->handleNotFound();
    }
    
    /**
     * Middleware çalıştır
     */
    private function runMiddleware(string $middleware): void
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
                
            case 'csrf':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                break;
        }
    }
    
    /**
     * Handler çalıştır
     */
    private function runHandler($handler, array $params): void
    {
        if (is_array($handler)) {
            [$controllerClass, $method] = $handler;
            
            // Controller dosyasını yükle
            $this->loadController($controllerClass);
            
            // Controller instance oluştur
            $controller = new $controllerClass();
            
            // Method'u çağır
            call_user_func_array([$controller, $method], $params);
        } elseif (is_callable($handler)) {
            call_user_func_array($handler, $params);
        }
    }
    
    /**
     * Controller dosyasını yükle
     */
    private function loadController(string $controllerClass): void
    {
        // Admin controller'ları
        if (strpos($controllerClass, 'Admin') === 0 && $controllerClass !== 'AdminController') {
            $file = __DIR__ . '/../Controllers/Admin/' . $controllerClass . '.php';
            if (file_exists($file)) {
                require_once __DIR__ . '/../Controllers/Admin/AdminControllerLoader.php';
                return;
            }
        }
        
        // Provider controller'ları
        if (strpos($controllerClass, 'Provider') === 0 && $controllerClass !== 'ProviderController') {
            $file = __DIR__ . '/../Controllers/Provider/' . $controllerClass . '.php';
            if (file_exists($file)) {
                require_once __DIR__ . '/../Controllers/Provider/ProviderControllerLoader.php';
                return;
            }
        }
        
        // Standart controller'lar
        $file = __DIR__ . '/../Controllers/' . $controllerClass . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
    
    /**
     * 404 handler
     */
    private function handleNotFound(): void
    {
        require_once __DIR__ . '/../Controllers/HomeController.php';
        $controller = new HomeController();
        $controller->notFound();
    }
}

