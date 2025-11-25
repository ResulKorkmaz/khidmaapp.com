<?php

/**
 * Lead Model
 * 
 * Handles all database operations related to leads (customer requests)
 */

class Lead
{
    private $db;
    
    public function __construct($db = null)
    {
        $this->db = $db ?? getDatabase();
    }
    
    /**
     * Find lead by ID
     * 
     * @param int $id Lead ID
     * @return array|false Lead data or false if not found
     */
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM leads WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Find lead by reference number
     * 
     * @param string $referenceNumber Reference number (e.g., "000010")
     * @return array|false Lead data or false if not found
     */
    public function findByReference($referenceNumber)
    {
        $stmt = $this->db->prepare("SELECT * FROM leads WHERE reference_number = ?");
        $stmt->execute([$referenceNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get all leads with optional filters
     * 
     * @param array $filters Filters (status, service_type, city, search)
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array Array of leads
     */
    public function getAll($filters = [], $limit = 100, $offset = 0)
    {
        $sql = "SELECT * FROM leads WHERE 1=1";
        $params = [];
        
        // Status filter
        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }
        
        // Service type filter
        if (!empty($filters['service_type'])) {
            $sql .= " AND service_type = ?";
            $params[] = $filters['service_type'];
        }
        
        // City filter
        if (!empty($filters['city'])) {
            $sql .= " AND city = ?";
            $params[] = $filters['city'];
        }
        
        // Search filter
        if (!empty($filters['search'])) {
            $sql .= " AND (phone LIKE ? OR reference_number LIKE ? OR description LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get available leads for distribution
     * Filters by service type, city, and status
     * 
     * @param string $serviceType Service type
     * @param string $city City
     * @return array Available leads
     */
    public function getAvailableForDistribution($serviceType, $city)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM leads 
            WHERE service_type = ? 
            AND city = ? 
            AND status IN ('new', 'verified', 'pending')
            AND id NOT IN (
                SELECT DISTINCT lead_id 
                FROM provider_lead_deliveries 
                WHERE lead_id IS NOT NULL
            )
            ORDER BY created_at ASC
            LIMIT 50
        ");
        $stmt->execute([$serviceType, $city]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create new lead
     * 
     * @param array $data Lead data
     * @return int|false Inserted lead ID or false on failure
     */
    public function create($data)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO leads (
                    reference_number,
                    service_type,
                    city,
                    phone,
                    whatsapp_phone,
                    description,
                    budget_min,
                    budget_max,
                    service_time_type,
                    service_time_details,
                    status,
                    created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $data['reference_number'] ?? $this->generateReferenceNumber(),
                $data['service_type'],
                $data['city'],
                $data['phone'],
                $data['whatsapp_phone'] ?? $data['phone'],
                $data['description'] ?? '',
                $data['budget_min'] ?? null,
                $data['budget_max'] ?? null,
                $data['service_time_type'] ?? 'flexible',
                $data['service_time_details'] ?? null,
                $data['status'] ?? 'new'
            ]);
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            error_log("Lead creation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update lead
     * 
     * @param int $id Lead ID
     * @param array $data Data to update
     * @return bool Success status
     */
    public function update($id, $data)
    {
        try {
            $fields = [];
            $params = [];
            
            // Build dynamic UPDATE query
            $allowedFields = [
                'service_type', 'city', 'phone', 'whatsapp_phone',
                'description', 'budget_min', 'budget_max',
                'service_time_type', 'service_time_details', 'status'
            ];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $fields[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }
            
            if (empty($fields)) {
                return false; // Nothing to update
            }
            
            $params[] = $id;
            $sql = "UPDATE leads SET " . implode(', ', $fields) . " WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            error_log("Lead update error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update lead status
     * 
     * @param int $id Lead ID
     * @param string $status New status
     * @return bool Success status
     */
    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE leads SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
    
    /**
     * Delete lead (soft delete by setting status to 'deleted')
     * 
     * @param int $id Lead ID
     * @return bool Success status
     */
    public function delete($id)
    {
        // Soft delete - just change status
        return $this->updateStatus($id, 'deleted');
    }
    
    /**
     * Increment view count
     * 
     * @param int $id Lead ID
     * @return bool Success status
     */
    public function incrementViewCount($id)
    {
        $stmt = $this->db->prepare("
            UPDATE leads 
            SET view_count = view_count + 1 
            WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }
    
    /**
     * Get lead count by filters
     * 
     * @param array $filters Filters (status, service_type, city)
     * @return int Lead count
     */
    public function count($filters = [])
    {
        $sql = "SELECT COUNT(*) as total FROM leads WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['service_type'])) {
            $sql .= " AND service_type = ?";
            $params[] = $filters['service_type'];
        }
        
        if (!empty($filters['city'])) {
            $sql .= " AND city = ?";
            $params[] = $filters['city'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }
    
    /**
     * Generate unique reference number
     * Format: 000001, 000002, etc.
     * 
     * @return string Reference number
     */
    private function generateReferenceNumber()
    {
        $stmt = $this->db->query("SELECT MAX(CAST(reference_number AS UNSIGNED)) as max_ref FROM leads");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nextNumber = ($result['max_ref'] ?? 0) + 1;
        return str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Check if lead has been delivered to provider
     * 
     * @param int $leadId Lead ID
     * @param int $providerId Provider ID
     * @return bool True if delivered
     */
    public function isDeliveredToProvider($leadId, $providerId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM provider_lead_deliveries 
            WHERE lead_id = ? AND provider_id = ?
        ");
        $stmt->execute([$leadId, $providerId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
    }
    
    /**
     * Get leads delivered to a specific provider
     * 
     * @param int $providerId Provider ID
     * @param bool $includeHidden Include hidden leads
     * @return array Array of leads
     */
    public function getDeliveredToProvider($providerId, $includeHidden = false)
    {
        $hiddenFilter = $includeHidden ? "" : "AND pld.hidden_by_provider = 0";
        
        $stmt = $this->db->prepare("
            SELECT 
                l.*,
                pld.delivered_at,
                pld.viewed_at,
                pld.hidden_by_provider,
                pld.hidden_at
            FROM leads l
            INNER JOIN provider_lead_deliveries pld ON l.id = pld.lead_id
            WHERE pld.provider_id = ? $hiddenFilter
            ORDER BY pld.delivered_at DESC
        ");
        $stmt->execute([$providerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

/**
 * Lead Model
 * 
 * Handles all database operations related to leads (customer requests)
 */

class Lead
{
    private $db;
    
    public function __construct($db = null)
    {
        $this->db = $db ?? getDatabase();
    }
    
    /**
     * Find lead by ID
     * 
     * @param int $id Lead ID
     * @return array|false Lead data or false if not found
     */
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM leads WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Find lead by reference number
     * 
     * @param string $referenceNumber Reference number (e.g., "000010")
     * @return array|false Lead data or false if not found
     */
    public function findByReference($referenceNumber)
    {
        $stmt = $this->db->prepare("SELECT * FROM leads WHERE reference_number = ?");
        $stmt->execute([$referenceNumber]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get all leads with optional filters
     * 
     * @param array $filters Filters (status, service_type, city, search)
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array Array of leads
     */
    public function getAll($filters = [], $limit = 100, $offset = 0)
    {
        $sql = "SELECT * FROM leads WHERE 1=1";
        $params = [];
        
        // Status filter
        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }
        
        // Service type filter
        if (!empty($filters['service_type'])) {
            $sql .= " AND service_type = ?";
            $params[] = $filters['service_type'];
        }
        
        // City filter
        if (!empty($filters['city'])) {
            $sql .= " AND city = ?";
            $params[] = $filters['city'];
        }
        
        // Search filter
        if (!empty($filters['search'])) {
            $sql .= " AND (phone LIKE ? OR reference_number LIKE ? OR description LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get available leads for distribution
     * Filters by service type, city, and status
     * 
     * @param string $serviceType Service type
     * @param string $city City
     * @return array Available leads
     */
    public function getAvailableForDistribution($serviceType, $city)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM leads 
            WHERE service_type = ? 
            AND city = ? 
            AND status IN ('new', 'verified', 'pending')
            AND id NOT IN (
                SELECT DISTINCT lead_id 
                FROM provider_lead_deliveries 
                WHERE lead_id IS NOT NULL
            )
            ORDER BY created_at ASC
            LIMIT 50
        ");
        $stmt->execute([$serviceType, $city]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create new lead
     * 
     * @param array $data Lead data
     * @return int|false Inserted lead ID or false on failure
     */
    public function create($data)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO leads (
                    reference_number,
                    service_type,
                    city,
                    phone,
                    whatsapp_phone,
                    description,
                    budget_min,
                    budget_max,
                    service_time_type,
                    service_time_details,
                    status,
                    created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $data['reference_number'] ?? $this->generateReferenceNumber(),
                $data['service_type'],
                $data['city'],
                $data['phone'],
                $data['whatsapp_phone'] ?? $data['phone'],
                $data['description'] ?? '',
                $data['budget_min'] ?? null,
                $data['budget_max'] ?? null,
                $data['service_time_type'] ?? 'flexible',
                $data['service_time_details'] ?? null,
                $data['status'] ?? 'new'
            ]);
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            error_log("Lead creation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update lead
     * 
     * @param int $id Lead ID
     * @param array $data Data to update
     * @return bool Success status
     */
    public function update($id, $data)
    {
        try {
            $fields = [];
            $params = [];
            
            // Build dynamic UPDATE query
            $allowedFields = [
                'service_type', 'city', 'phone', 'whatsapp_phone',
                'description', 'budget_min', 'budget_max',
                'service_time_type', 'service_time_details', 'status'
            ];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $fields[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }
            
            if (empty($fields)) {
                return false; // Nothing to update
            }
            
            $params[] = $id;
            $sql = "UPDATE leads SET " . implode(', ', $fields) . " WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            error_log("Lead update error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update lead status
     * 
     * @param int $id Lead ID
     * @param string $status New status
     * @return bool Success status
     */
    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE leads SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
    
    /**
     * Delete lead (soft delete by setting status to 'deleted')
     * 
     * @param int $id Lead ID
     * @return bool Success status
     */
    public function delete($id)
    {
        // Soft delete - just change status
        return $this->updateStatus($id, 'deleted');
    }
    
    /**
     * Increment view count
     * 
     * @param int $id Lead ID
     * @return bool Success status
     */
    public function incrementViewCount($id)
    {
        $stmt = $this->db->prepare("
            UPDATE leads 
            SET view_count = view_count + 1 
            WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }
    
    /**
     * Get lead count by filters
     * 
     * @param array $filters Filters (status, service_type, city)
     * @return int Lead count
     */
    public function count($filters = [])
    {
        $sql = "SELECT COUNT(*) as total FROM leads WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['service_type'])) {
            $sql .= " AND service_type = ?";
            $params[] = $filters['service_type'];
        }
        
        if (!empty($filters['city'])) {
            $sql .= " AND city = ?";
            $params[] = $filters['city'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }
    
    /**
     * Generate unique reference number
     * Format: 000001, 000002, etc.
     * 
     * @return string Reference number
     */
    private function generateReferenceNumber()
    {
        $stmt = $this->db->query("SELECT MAX(CAST(reference_number AS UNSIGNED)) as max_ref FROM leads");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nextNumber = ($result['max_ref'] ?? 0) + 1;
        return str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Check if lead has been delivered to provider
     * 
     * @param int $leadId Lead ID
     * @param int $providerId Provider ID
     * @return bool True if delivered
     */
    public function isDeliveredToProvider($leadId, $providerId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM provider_lead_deliveries 
            WHERE lead_id = ? AND provider_id = ?
        ");
        $stmt->execute([$leadId, $providerId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
    }
    
    /**
     * Get leads delivered to a specific provider
     * 
     * @param int $providerId Provider ID
     * @param bool $includeHidden Include hidden leads
     * @return array Array of leads
     */
    public function getDeliveredToProvider($providerId, $includeHidden = false)
    {
        $hiddenFilter = $includeHidden ? "" : "AND pld.hidden_by_provider = 0";
        
        $stmt = $this->db->prepare("
            SELECT 
                l.*,
                pld.delivered_at,
                pld.viewed_at,
                pld.hidden_by_provider,
                pld.hidden_at
            FROM leads l
            INNER JOIN provider_lead_deliveries pld ON l.id = pld.lead_id
            WHERE pld.provider_id = ? $hiddenFilter
            ORDER BY pld.delivered_at DESC
        ");
        $stmt->execute([$providerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


