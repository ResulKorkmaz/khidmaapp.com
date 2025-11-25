<?php
/**
 * Provider Model
 * 
 * Hizmet sağlayıcı (usta) yönetimi için database işlemleri
 * 
 * @package KhidmaApp
 */

class Provider
{
    private $pdo;
    
    public function __construct()
    {
        $this->pdo = getDatabase();
    }
    
    /**
     * ID ile provider bul
     */
    public function findById(int $id): ?array
    {
        if (!$this->pdo) return null;
        
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM service_providers WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Provider findById error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Email ile provider bul
     */
    public function findByEmail(string $email): ?array
    {
        if (!$this->pdo) return null;
        
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM service_providers WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Provider findByEmail error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Telefon ile provider bul
     */
    public function findByPhone(string $phone): ?array
    {
        if (!$this->pdo) return null;
        
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM service_providers WHERE phone = ?");
            $stmt->execute([$phone]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Provider findByPhone error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Tüm provider'ları getir (filtreli)
     */
    public function getAll(array $filters = [], int $limit = 100, int $offset = 0): array
    {
        if (!$this->pdo) return [];
        
        try {
            $where = [];
            $params = [];
            
            if (!empty($filters['status'])) {
                $where[] = "status = ?";
                $params[] = $filters['status'];
            }
            
            if (!empty($filters['service_type'])) {
                $where[] = "service_type = ?";
                $params[] = $filters['service_type'];
            }
            
            if (!empty($filters['city'])) {
                $where[] = "city = ?";
                $params[] = $filters['city'];
            }
            
            if (isset($filters['is_active'])) {
                $where[] = "is_active = ?";
                $params[] = $filters['is_active'];
            }
            
            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
            
            $sql = "SELECT * FROM service_providers 
                    $whereClause 
                    ORDER BY created_at DESC 
                    LIMIT ? OFFSET ?";
            
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Provider getAll error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Provider sayısını getir (filtreli)
     */
    public function count(array $filters = []): int
    {
        if (!$this->pdo) return 0;
        
        try {
            $where = [];
            $params = [];
            
            if (!empty($filters['status'])) {
                $where[] = "status = ?";
                $params[] = $filters['status'];
            }
            
            if (!empty($filters['service_type'])) {
                $where[] = "service_type = ?";
                $params[] = $filters['service_type'];
            }
            
            if (!empty($filters['city'])) {
                $where[] = "city = ?";
                $params[] = $filters['city'];
            }
            
            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
            
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM service_providers $whereClause");
            $stmt->execute($params);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Provider count error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Yeni provider oluştur
     */
    public function create(array $data): ?int
    {
        if (!$this->pdo) return null;
        
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO service_providers 
                (name, email, phone, service_type, city, password_hash, status, is_active, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['service_type'],
                $data['city'],
                password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]),
                $data['status'] ?? 'pending',
                $data['is_active'] ?? 0
            ]);
            
            return (int) $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Provider create error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Provider güncelle
     */
    public function update(int $id, array $data): bool
    {
        if (!$this->pdo) return false;
        
        try {
            $fields = [];
            $params = [];
            
            $allowedFields = ['name', 'email', 'phone', 'service_type', 'city', 'status', 'is_active'];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $fields[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }
            
            if (isset($data['password'])) {
                $fields[] = "password_hash = ?";
                $params[] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
            }
            
            if (empty($fields)) return false;
            
            $params[] = $id;
            
            $sql = "UPDATE service_providers SET " . implode(", ", $fields) . " WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Provider update error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Provider durumunu güncelle
     */
    public function updateStatus(int $id, string $status): bool
    {
        return $this->update($id, ['status' => $status]);
    }
    
    /**
     * Provider'ı onayla
     */
    public function approve(int $id): bool
    {
        return $this->update($id, ['status' => 'approved', 'is_active' => 1]);
    }
    
    /**
     * Provider'ı reddet
     */
    public function reject(int $id): bool
    {
        return $this->update($id, ['status' => 'rejected', 'is_active' => 0]);
    }
    
    /**
     * Login doğrulama
     */
    public function verifyLogin(string $identifier, string $password): ?array
    {
        // Email veya telefon ile giriş
        $provider = $this->findByEmail($identifier);
        
        if (!$provider) {
            $provider = $this->findByPhone($identifier);
        }
        
        if ($provider && password_verify($password, $provider['password_hash'])) {
            // Aktif ve onaylı mı kontrol et
            if ($provider['is_active'] && $provider['status'] === 'approved') {
                $this->updateLastLogin($provider['id']);
                return $provider;
            }
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
            $stmt = $this->pdo->prepare("UPDATE service_providers SET last_login = NOW() WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Provider updateLastLogin error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Provider'ın aktif paketlerini getir
     */
    public function getActivePurchases(int $providerId): array
    {
        if (!$this->pdo) return [];
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT pp.*, lp.name_ar, lp.name_tr, lp.lead_count as package_lead_count
                FROM provider_purchases pp
                LEFT JOIN leads_packages lp ON pp.package_id = lp.id
                WHERE pp.provider_id = ? 
                AND pp.remaining_leads > 0 
                AND pp.payment_status = 'completed'
                ORDER BY pp.purchased_at DESC
            ");
            $stmt->execute([$providerId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Provider getActivePurchases error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Provider'a teslim edilen lead'leri getir
     */
    public function getDeliveredLeads(int $providerId, int $limit = 50): array
    {
        if (!$this->pdo) return [];
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT l.*, pld.delivered_at, pld.delivery_method, pld.viewed_at, pld.viewed_count
                FROM leads l
                INNER JOIN provider_lead_deliveries pld ON l.id = pld.lead_id
                WHERE pld.provider_id = ?
                ORDER BY pld.delivered_at DESC
                LIMIT ?
            ");
            $stmt->execute([$providerId, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Provider getDeliveredLeads error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Email veya telefon mevcut mu kontrol et
     */
    public function exists(string $email = null, string $phone = null, ?int $excludeId = null): bool
    {
        if (!$this->pdo) return false;
        
        try {
            $where = [];
            $params = [];
            
            if ($email) {
                $where[] = "email = ?";
                $params[] = $email;
            }
            
            if ($phone) {
                $where[] = "phone = ?";
                $params[] = $phone;
            }
            
            if (empty($where)) return false;
            
            $sql = "SELECT id FROM service_providers WHERE (" . implode(" OR ", $where) . ")";
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            error_log("Provider exists error: " . $e->getMessage());
            return false;
        }
    }
}

