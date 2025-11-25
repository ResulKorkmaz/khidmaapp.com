<?php
/**
 * Database Service
 * 
 * Transaction yönetimi ve database helper fonksiyonları.
 * Kritik işlemlerde veri tutarlılığını sağlar.
 * 
 * @package KhidmaApp
 * @since 1.0.0
 */

class DatabaseService
{
    private static $pdo = null;
    private static $transactionLevel = 0;
    
    /**
     * PDO instance'ını al
     */
    public static function getPdo(): ?PDO
    {
        if (self::$pdo === null) {
            self::$pdo = getDatabase();
        }
        return self::$pdo;
    }
    
    /**
     * Transaction başlat (nested transaction desteği)
     */
    public static function beginTransaction(): bool
    {
        $pdo = self::getPdo();
        if (!$pdo) {
            return false;
        }
        
        if (self::$transactionLevel === 0) {
            $result = $pdo->beginTransaction();
        } else {
            // Nested transaction için savepoint
            $pdo->exec("SAVEPOINT trans_" . self::$transactionLevel);
            $result = true;
        }
        
        self::$transactionLevel++;
        return $result;
    }
    
    /**
     * Transaction'ı onayla
     */
    public static function commit(): bool
    {
        $pdo = self::getPdo();
        if (!$pdo || self::$transactionLevel === 0) {
            return false;
        }
        
        self::$transactionLevel--;
        
        if (self::$transactionLevel === 0) {
            return $pdo->commit();
        } else {
            // Nested transaction için savepoint release
            $pdo->exec("RELEASE SAVEPOINT trans_" . self::$transactionLevel);
            return true;
        }
    }
    
    /**
     * Transaction'ı geri al
     */
    public static function rollback(): bool
    {
        $pdo = self::getPdo();
        if (!$pdo || self::$transactionLevel === 0) {
            return false;
        }
        
        self::$transactionLevel--;
        
        if (self::$transactionLevel === 0) {
            return $pdo->rollBack();
        } else {
            // Nested transaction için savepoint rollback
            $pdo->exec("ROLLBACK TO SAVEPOINT trans_" . self::$transactionLevel);
            return true;
        }
    }
    
    /**
     * Transaction içinde mi?
     */
    public static function inTransaction(): bool
    {
        return self::$transactionLevel > 0;
    }
    
    /**
     * Transaction seviyesi
     */
    public static function getTransactionLevel(): int
    {
        return self::$transactionLevel;
    }
    
    /**
     * Callback'i transaction içinde çalıştır
     * 
     * @param callable $callback
     * @return mixed
     * @throws Exception
     */
    public static function transaction(callable $callback)
    {
        self::beginTransaction();
        
        try {
            $result = $callback(self::getPdo());
            self::commit();
            return $result;
        } catch (Exception $e) {
            self::rollback();
            error_log("DatabaseService::transaction error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Güvenli sorgu çalıştır (hata yakalama ile)
     */
    public static function query(string $sql, array $params = []): ?PDOStatement
    {
        $pdo = self::getPdo();
        if (!$pdo) {
            return null;
        }
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("DatabaseService::query error: " . $e->getMessage() . " SQL: " . $sql);
            return null;
        }
    }
    
    /**
     * Tek satır getir
     */
    public static function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = self::query($sql, $params);
        if (!$stmt) {
            return null;
        }
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Tüm satırları getir
     */
    public static function fetchAll(string $sql, array $params = []): array
    {
        $stmt = self::query($sql, $params);
        if (!$stmt) {
            return [];
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Tek değer getir
     */
    public static function fetchColumn(string $sql, array $params = [], int $column = 0)
    {
        $stmt = self::query($sql, $params);
        if (!$stmt) {
            return null;
        }
        
        return $stmt->fetchColumn($column);
    }
    
    /**
     * Insert ve son ID'yi döndür
     */
    public static function insert(string $table, array $data): ?int
    {
        $pdo = self::getPdo();
        if (!$pdo) {
            return null;
        }
        
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_values($data));
            return (int) $pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("DatabaseService::insert error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update ve etkilenen satır sayısını döndür
     */
    public static function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $pdo = self::getPdo();
        if (!$pdo) {
            return 0;
        }
        
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        $sql = "UPDATE {$table} SET {$set} WHERE {$where}";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_merge(array_values($data), $whereParams));
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("DatabaseService::update error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Delete ve etkilenen satır sayısını döndür
     */
    public static function delete(string $table, string $where, array $params = []): int
    {
        $pdo = self::getPdo();
        if (!$pdo) {
            return 0;
        }
        
        $sql = "DELETE FROM {$table} WHERE {$where}";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("DatabaseService::delete error: " . $e->getMessage());
            return 0;
        }
    }
}

// ==========================================
// GLOBAL HELPER FUNCTIONS
// ==========================================

/**
 * Transaction başlat
 */
function db_begin(): bool {
    return DatabaseService::beginTransaction();
}

/**
 * Transaction onayla
 */
function db_commit(): bool {
    return DatabaseService::commit();
}

/**
 * Transaction geri al
 */
function db_rollback(): bool {
    return DatabaseService::rollback();
}

/**
 * Callback'i transaction içinde çalıştır
 */
function db_transaction(callable $callback) {
    return DatabaseService::transaction($callback);
}

