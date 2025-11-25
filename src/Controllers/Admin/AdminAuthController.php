<?php

/**
 * KhidmaApp.com - Admin Auth Controller
 * 
 * Admin giriş/çıkış işlemleri
 */

require_once __DIR__ . '/BaseAdminController.php';

class AdminAuthController extends BaseAdminController 
{
    /**
     * Admin login sayfası
     */
    public function login(): void
    {
        // Zaten giriş yapmışsa dashboard'a yönlendir
        if (isAdminLoggedIn()) {
            $this->redirect('/admin');
        }
        
        $error = '';
        
        // POST isteği (giriş denemesi)
        if ($this->isPost()) {
            $username = $this->sanitizedPost('username');
            $password = $this->postParam('password', '');
            
            if (empty($username) || empty($password)) {
                $error = 'Lütfen kullanıcı adı ve şifre girin';
            } elseif (adminLogin($username, $password)) {
                $this->redirect('/admin');
            } else {
                $error = 'Kullanıcı adı veya şifre hatalı';
            }
        }
        
        $this->render('login', ['error' => $error]);
    }
    
    /**
     * Admin çıkış
     */
    public function logout(): void
    {
        adminLogout();
        $this->redirect('/admin/login');
    }
}

