<?php
/**
 * Security Functions Test
 * 
 * Tests for security-related functionality
 */

use PHPUnit\Framework\TestCase;

class SecurityTest extends TestCase
{
    /**
     * Test XSS prevention
     */
    public function testXSSPrevention()
    {
        $maliciousInputs = [
            '<script>alert("xss")</script>',
            '<img src=x onerror=alert("xss")>',
            '<iframe src="javascript:alert(\'xss\')">',
            '<svg onload=alert("xss")>',
        ];
        
        foreach ($maliciousInputs as $input) {
            $sanitized = sanitizeInput($input);
            
            // Should not contain script tags
            $this->assertStringNotContainsString('<script>', $sanitized);
            $this->assertStringNotContainsString('onerror', $sanitized);
            $this->assertStringNotContainsString('onload', $sanitized);
        }
    }
    
    /**
     * Test SQL injection prevention patterns
     */
    public function testSQLInjectionPreventionPatterns()
    {
        $sqlInjectionAttempts = [
            "1' OR '1'='1",
            "admin'--",
            "1; DROP TABLE users--",
            "' UNION SELECT * FROM users--",
        ];
        
        foreach ($sqlInjectionAttempts as $attempt) {
            $sanitized = sanitizeInput($attempt);
            
            // Should escape dangerous characters
            $this->assertStringNotContainsString("'1'='1", $sanitized);
            $this->assertNotEquals($attempt, $sanitized);
        }
    }
    
    /**
     * Test password hashing
     */
    public function testPasswordHashing()
    {
        $password = 'SecurePassword123!';
        
        // Hash the password
        $hash = password_hash($password, PASSWORD_BCRYPT);
        
        // Verify hash is created
        $this->assertNotEmpty($hash);
        $this->assertNotEquals($password, $hash);
        
        // Verify password
        $this->assertTrue(password_verify($password, $hash));
        
        // Verify wrong password fails
        $this->assertFalse(password_verify('WrongPassword', $hash));
    }
    
    /**
     * Test CSRF token length
     */
    public function testCSRFTokenLength()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = generateCsrfToken();
        
        // Token should be 64 characters (32 bytes hex encoded)
        $this->assertEquals(64, strlen($token));
        
        // Token should only contain hex characters
        $this->assertMatchesRegularExpression('/^[0-9a-f]{64}$/', $token);
    }
    
    /**
     * Test CSRF token expiration
     */
    public function testCSRFTokenExpiration()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Generate token
        $token = generateCsrfToken();
        
        // Should be valid immediately
        $this->assertTrue(verifyCsrfToken($token));
        
        // Simulate expired token by setting old timestamp
        $_SESSION['csrf_token_time'] = time() - 7200; // 2 hours ago
        
        // Should be invalid (assuming 1 hour expiry in CSRF_TOKEN_EXPIRE)
        // Note: This depends on your CSRF_TOKEN_EXPIRE constant
        $this->assertFalse(verifyCsrfToken($token));
    }
    
    /**
     * Test sensitive data masking
     */
    public function testSensitiveDataMasking()
    {
        $phone = '0501234567';
        $masked = $this->maskPhoneNumber($phone);
        
        // Should mask middle digits
        $this->assertStringContainsString('***', $masked);
        $this->assertStringNotContainsString($phone, $masked);
    }
    
    /**
     * Helper: Mask phone number
     */
    private function maskPhoneNumber(string $phone): string
    {
        if (strlen($phone) < 4) {
            return str_repeat('*', strlen($phone));
        }
        
        $start = substr($phone, 0, 2);
        $end = substr($phone, -2);
        $middle = str_repeat('*', strlen($phone) - 4);
        
        return $start . $middle . $end;
    }
}


