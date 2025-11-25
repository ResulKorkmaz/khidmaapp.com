<?php

/**
 * KhidmaApp.com - Web Routes
 * 
 * Tüm web route'ları burada tanımlanır
 */

// ============================================
// PUBLIC ROUTES (Giriş gerektirmeyen)
// ============================================

$router->get('/', [HomeController::class, 'index']);
$router->get('/home', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/services', [HomeController::class, 'services']);
$router->get('/services/{key}', [ServiceController::class, 'show']);
$router->get('/contact', [HomeController::class, 'contact']);
$router->get('/thanks', [HomeController::class, 'thanks']);
$router->get('/privacy', [HomeController::class, 'privacy']);
$router->get('/terms', [HomeController::class, 'terms']);
$router->get('/cookies', [HomeController::class, 'cookies']);
$router->get('/sitemap.xml', [HomeController::class, 'sitemap']);
$router->get('/robots.txt', [HomeController::class, 'robots']);

// Lead submission
$router->post('/lead/submit', [LeadController::class, 'store']);

// ============================================
// PROVIDER ROUTES
// ============================================

// Auth routes (giriş gerektirmeyen)
$router->post('/provider/login', [ProviderAuthController::class, 'login']);
$router->post('/provider/register', [ProviderAuthController::class, 'register']);
$router->get('/provider/logout', [ProviderAuthController::class, 'logout']);

// Protected routes (giriş gerektiren)
$router->group('/provider', ['provider.auth'], function($router) {
    // Dashboard
    $router->get('/dashboard', [ProviderDashboardController::class, 'index']);
    
    // Leads
    $router->get('/leads', [ProviderLeadController::class, 'index']);
    $router->get('/my-requests', [ProviderLeadController::class, 'myRequests']);
    $router->get('/hidden-leads', [ProviderLeadController::class, 'hidden']);
    $router->post('/request-lead', [ProviderLeadController::class, 'request']);
    $router->post('/mark-lead-viewed', [ProviderLeadController::class, 'markViewed']);
    $router->post('/hide-lead', [ProviderLeadController::class, 'hide']);
    
    // Profile & Settings
    $router->get('/profile', [ProviderProfileController::class, 'index']);
    $router->post('/profile', [ProviderProfileController::class, 'index']);
    $router->get('/settings', [ProviderProfileController::class, 'settings']);
    $router->post('/settings', [ProviderProfileController::class, 'settings']);
    
    // Messages
    $router->get('/messages', [ProviderMessageController::class, 'index']);
    $router->post('/mark-message-read', [ProviderMessageController::class, 'markRead']);
    $router->post('/delete-message', [ProviderMessageController::class, 'delete']);
    
    // Packages & Purchase
    $router->get('/packages', [ProviderPurchaseController::class, 'packages']);
    $router->get('/purchase/{id}', [ProviderPurchaseController::class, 'purchase']);
    $router->post('/create-checkout-session', [ProviderPurchaseController::class, 'createCheckoutSession']);
    $router->get('/purchase/success', [ProviderPurchaseController::class, 'success']);
    $router->get('/purchase/cancel', [ProviderPurchaseController::class, 'cancel']);
});

// ============================================
// ADMIN ROUTES
// ============================================

// Auth routes
$router->any('/admin/login', [AdminAuthController::class, 'login']);
$router->get('/admin/logout', [AdminAuthController::class, 'logout']);

// Protected routes
$router->group('/admin', ['admin.auth'], function($router) {
    // Dashboard
    $router->get('', [AdminDashboardController::class, 'index']);
    $router->get('/', [AdminDashboardController::class, 'index']);
    
    // Leads
    $router->get('/leads', [AdminLeadController::class, 'index']);
    $router->get('/lead/{id}', [AdminLeadController::class, 'detail']);
    $router->post('/leads/update-status', [AdminLeadController::class, 'updateStatus']);
    $router->post('/leads/mark-as-sent', [AdminLeadController::class, 'markAsSent']);
    $router->post('/leads/toggle-sent', [AdminLeadController::class, 'toggleSent']);
    $router->post('/leads/delete', [AdminLeadController::class, 'delete']);
    $router->post('/leads/restore', [AdminLeadController::class, 'restore']);
    $router->post('/leads/permanently-delete', [AdminLeadController::class, 'permanentDelete']);
    $router->post('/leads/send-to-provider', [AdminLeadController::class, 'sendToProvider']);
    $router->post('/leads/withdraw-from-provider', [AdminLeadController::class, 'withdrawFromProvider']);
    $router->get('/leads/export', [AdminLeadController::class, 'export']);
    
    // Providers
    $router->get('/providers', [AdminProviderController::class, 'index']);
    $router->get('/providers/{id}', [AdminProviderController::class, 'detail']);
    $router->post('/providers/approve', [AdminProviderController::class, 'approve']);
    $router->post('/providers/change-status', [AdminProviderController::class, 'changeStatus']);
    $router->post('/providers/delete', [AdminProviderController::class, 'delete']);
    $router->get('/providers/search', [AdminProviderController::class, 'search']);
    
    // Services
    $router->get('/services', [AdminServiceController::class, 'index']);
    $router->post('/services/create', [AdminServiceController::class, 'create']);
    $router->post('/services/update', [AdminServiceController::class, 'update']);
    $router->post('/services/toggle', [AdminServiceController::class, 'toggleStatus']);
    $router->post('/services/delete', [AdminServiceController::class, 'delete']);
    
    // Packages
    $router->get('/lead-packages', [AdminPackageController::class, 'index']);
    $router->post('/lead-packages/create', [AdminPackageController::class, 'create']);
    $router->post('/lead-packages/update', [AdminPackageController::class, 'update']);
    $router->post('/lead-packages/toggle', [AdminPackageController::class, 'toggleStatus']);
    $router->post('/lead-packages/delete', [AdminPackageController::class, 'delete']);
    
    // Purchases & Lead Assignments
    $router->get('/purchases', [AdminPurchaseController::class, 'index']);
    $router->get('/lead-requests', [AdminPurchaseController::class, 'leadRequests']);
    $router->post('/lead-requests/send', [AdminPurchaseController::class, 'sendLeadManually']);
    $router->get('/lead-requests/count', [AdminPurchaseController::class, 'pendingRequestsCount']);
    $router->get('/leads/get-available-providers', [AdminPurchaseController::class, 'getAvailableProviders']);
    $router->get('/leads/available', [AdminPurchaseController::class, 'availableLeads']);
    $router->post('/leads/mark-sent-whatsapp', [AdminPurchaseController::class, 'markAsSentViaWhatsApp']);
    $router->post('/leads/assign-to-provider', [AdminPurchaseController::class, 'assignLeadsToProvider']);
    $router->post('/leads/withdraw', [AdminPurchaseController::class, 'withdrawLead']);
    
    // Messages
    $router->get('/provider-messages', [AdminMessageController::class, 'index']);
    $router->post('/provider-messages/send', [AdminMessageController::class, 'send']);
    $router->get('/provider-messages/history', [AdminMessageController::class, 'history']);
    
    // Users (Super Admin only)
    $router->get('/users', [AdminUserController::class, 'index']);
    $router->get('/users/create', [AdminUserController::class, 'createForm']);
    $router->post('/users/create', [AdminUserController::class, 'create']);
    $router->post('/users/delete', [AdminUserController::class, 'delete']);
    $router->post('/users/toggle-status', [AdminUserController::class, 'toggleStatus']);
});

// ============================================
// WEBHOOK ROUTES
// ============================================

$router->post('/webhook/stripe', [WebhookController::class, 'stripe']);

