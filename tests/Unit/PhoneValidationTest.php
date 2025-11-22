<?php
/**
 * Phone Number Validation Test
 * 
 * Tests for Saudi phone number validation
 */

use PHPUnit\Framework\TestCase;

class PhoneValidationTest extends TestCase
{
    /**
     * Test valid Saudi phone numbers
     */
    public function testValidSaudiPhoneNumber()
    {
        $validNumbers = [
            '0501234567',   // Standard format
            '0561234567',   // Different prefix
            '+966501234567', // International format
            '00966501234567' // Alternative international
        ];
        
        foreach ($validNumbers as $number) {
            $normalized = $this->normalizePhone($number);
            $this->assertTrue($this->isValidSaudiPhone($normalized), 
                "Failed for: {$number}");
        }
    }
    
    public function testInvalidSaudiPhoneNumber()
    {
        $invalidNumbers = [
            '123456',       // Too short
            '12345678901',  // Too long
            'abcd1234567',  // Contains letters
            '0401234567',   // Wrong prefix (should be 05x)
            '',             // Empty
        ];
        
        foreach ($invalidNumbers as $number) {
            $normalized = $this->normalizePhone($number);
            $this->assertFalse($this->isValidSaudiPhone($normalized),
                "Should fail for: {$number}");
        }
    }
    
    /**
     * Test phone number normalization
     */
    public function testPhoneNormalization()
    {
        // Test cases: [input, expected_output]
        $testCases = [
            ['+966501234567', '0501234567'],
            ['00966501234567', '0501234567'],
            ['966501234567', '0501234567'],
            ['501234567', '0501234567'],
            ['0501234567', '0501234567'], // Already normalized
        ];
        
        foreach ($testCases as [$input, $expected]) {
            $result = $this->normalizePhone($input);
            $this->assertEquals($expected, $result, 
                "Failed to normalize: {$input}");
        }
    }
    
    /**
     * Helper: Normalize phone number
     */
    private function normalizePhone(string $phone): string
    {
        // Remove non-numeric characters except +
        $phone = preg_replace('/[^\d+]/', '', $phone);
        
        // Convert to 05xxxxxxxx format
        if (strpos($phone, '+9665') === 0) {
            $phone = '0' . substr($phone, 5);
        } elseif (strpos($phone, '009665') === 0) {
            $phone = '0' . substr($phone, 6);
        } elseif (strpos($phone, '9665') === 0) {
            $phone = '0' . substr($phone, 4);
        } elseif (strpos($phone, '5') === 0 && strlen($phone) === 9) {
            $phone = '0' . $phone;
        }
        
        return $phone;
    }
    
    /**
     * Helper: Validate Saudi phone number
     */
    private function isValidSaudiPhone(string $phone): bool
    {
        $validPatterns = [
            '/^05[0-9]{8}$/',        // 05xxxxxxxx (10 digits)
            '/^5[0-9]{8}$/',         // 5xxxxxxxx (9 digits) 
            '/^\+9665[0-9]{8}$/',    // +9665xxxxxxxx
            '/^009665[0-9]{8}$/'     // 009665xxxxxxxx
        ];
        
        foreach ($validPatterns as $pattern) {
            if (preg_match($pattern, $phone)) {
                return true;
            }
        }
        
        return false;
    }
}


