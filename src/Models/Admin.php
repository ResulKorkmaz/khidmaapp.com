<?php
/**
 * Admin Model
 * 
 * Admin kullanıcı yönetimi için database işlemleri
 * 
 * @package KhidmaApp
 */

class Admin
{
    private $pdo;
    
    public function __construct()
    {
        $this->pdo = getDatabase();
    }
    
    /**
     * ID ile admin bul
     */
    public function findById(int $id): ?array
    {
        if (!$this->pdo) return null;
        
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Admin findById error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Username ile admin bul
     */
    public function findByUsername(string $username): ?array
    {
        if (!$this->pdo) return null;
        
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE username = ?");
            $stmt->execute([$username]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Admin findByUsername error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Email ile admin bul
     */
    public function findByEmail(string $email): ?array
    {
        if (!$this->pdo) return null;
        
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Admin findByEmail error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Tüm adminleri getir (super_admin hariç opsiyonel)
     */
    public function getAll(bool $excludeSuperAdmin = false, ?string $excludeUsername = null): array
    {
        if (!$this->pdo) return [];
        
        try {
            $where = [];
            $params = [];
            
            if ($excludeSuperAdmin) {
                $where[] = "role != 'super_admin'";
            }
            
            if ($excludeUsername) {
                $where[] = "username != ?";
                $params[] = $excludeUsername;
            }
            
            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
            
            $sql = "SELECT id, username, email, role, is_active, created_at, last_login 
                    FROM admins 
                    $whereClause
                    ORDER BY 
                        CASE role 
                            WHEN 'super_admin' THEN 1 
                            WHEN 'admin' THEN 2 
                            WHEN 'user' THEN 3 
                        END,
                        created_at DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Admin getAll error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Yeni admin oluştur
     */
    public function create(array $data): ?int
    {
        if (!$this->pdo) return null;
        
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO admins (username, email, password_hash, role, is_active, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $data['username'],
                $data['email'],
                password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]),
                $data['role'] ?? 'user',
                $data['is_active'] ?? 1
            ]);
            
            return (int) $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Admin create error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Admin güncelle
     */
    public function update(int $id, array $data): bool
    {
        if (!$this->pdo) return false;
        
        try {
            $fields = [];
            $params = [];
            
            if (isset($data['username'])) {
                $fields[] = "username = ?";
                $params[] = $data['username'];
            }
            
            if (isset($data['email'])) {
                $fields[] = "email = ?";
                $params[] = $data['email'];
            }
            
            if (isset($data['password'])) {
                $fields[] = "password_hash = ?";
                $params[] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
            }
            
            if (isset($data['role'])) {
                $fields[] = "role = ?";
                $params[] = $data['role'];
            }
            
            if (isset($data['is_active'])) {
                $fields[] = "is_active = ?";
                $params[] = $data['is_active'];
            }
            
            if (empty($fields)) return false;
            
            $params[] = $id;
            
            $sql = "UPDATE admins SET " . implode(", ", $fields) . " WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Admin update error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Admin sil
     */
    public function delete(int $id): bool
    {
        if (!$this->pdo) return false;
        
        try {
            $stmt = $this->pdo->prepare("DELETE FROM admins WHERE id = ? AND role != 'super_admin'");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Admin delete error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Login doğrulama
     */
    public function verifyLogin(string $username, string $password): ?array
    {
        $admin = $this->findByUsername($username);
        
        if ($admin && password_verify($password, $admin['password_hash'])) {
            // Son giriş zamanını güncelle
            $this->updateLastLogin($admin['id']);
            return $admin;
        }
        
        return null;
    }
    
    /**
     * Son giriş zamanını güncelle
     */
    public function updateLastLogin(int $id): bool
    {
        if (!$this->pdo) return false;
        
        try {
            $stmt = $this->pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Admin updateLastLogin error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Username veya email mevcut mu kontrol et
     */
    public function exists(string $username = null, string $email = null, ?int $excludeId = null): bool
    {
        if (!$this->pdo) return false;
        
        try {
            $where = [];
            $params = [];
            
            if ($username) {
                $where[] = "username = ?";
                $params[] = $username;
            }
            
            if ($email) {
                $where[] = "email = ?";
                $params[] = $email;
            }
            
            if (empty($where)) return false;
            
            $sql = "SELECT id FROM admins WHERE (" . implode(" OR ", $where) . ")";
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            error_log("Admin exists error: " . $e->getMessage());
            return false;
        }
    }
}

