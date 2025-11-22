<?php
/**
 * Admin Kullanıcısı Oluşturma Scripti
 * 
 * Kullanım: php database/create_admin.php
 */

require_once __DIR__ . '/../src/config/config.php';

$username = 'rslkrkmz';
$password = 'Rr123456';
$email = 'admin@khidmaapp.com';

$pdo = getDatabase();

if (!$pdo) {
    die("❌ Veritabanı bağlantısı başarısız!\n");
}

try {
    // Şifreyi hash'le
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Admin kullanıcısını oluştur veya güncelle
    $stmt = $pdo->prepare("
        INSERT INTO admins (username, password_hash, email, is_active) 
        VALUES (?, ?, ?, TRUE)
        ON DUPLICATE KEY UPDATE 
            password_hash = VALUES(password_hash),
            email = VALUES(email),
            is_active = TRUE
    ");
    
    $stmt->execute([$username, $passwordHash, $email]);
    
    echo "✅ Admin kullanıcısı başarıyla oluşturuldu!\n";
    echo "   Kullanıcı adı: {$username}\n";
    echo "   Şifre: {$password}\n";
    echo "   E-posta: {$email}\n";
    echo "\n";
    echo "🌐 Admin paneline giriş: http://localhost:8000/admin/login\n";
    
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Table') !== false && strpos($e->getMessage(), "doesn't exist") !== false) {
        echo "❌ 'admins' tablosu bulunamadı!\n";
        echo "   Önce 'database/schema.sql' dosyasını çalıştırarak tabloyu oluşturun.\n";
    } else {
        echo "❌ Hata: " . $e->getMessage() . "\n";
    }
    exit(1);
}









