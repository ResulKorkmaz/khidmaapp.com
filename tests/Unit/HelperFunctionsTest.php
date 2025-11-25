<?php
/**
 * Helper Functions Test
 * 
 * Tests for global helper functions
 */

use PHPUnit\Framework\TestCase;

class HelperFunctionsTest extends TestCase
{
    /**
     * Test sanitizeInput function
     */
    public function testSanitizeInputRemovesHtmlTags()
    {
        $input = '<script>alert("xss")</script>Hello';
        $result = sanitizeInput($input);
        
        $this->assertStringNotContainsString('<script>', $result);
        $this->assertStringContainsString('Hello', $result);
    }
    
    public function testSanitizeInputTrimsWhitespace()
    {
        $input = '  Hello World  ';
        $result = sanitizeInput($input);
        
        $this->assertEquals('Hello World', $result);
    }
    
    public function testSanitizeInputHandlesEmptyString()
    {
        $result = sanitizeInput('');
        $this->assertEquals('', $result);
    }
    
    /**
     * Test CSRF token generation
     */
    public function testGenerateCsrfTokenReturnsString()
    {
        // Start session for CSRF token
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = generateCsrfToken();
        
        $this->assertIsString($token);
        $this->assertNotEmpty($token);
        $this->assertEquals(64, strlen($token)); // 32 bytes = 64 hex chars
    }
    
    public function testGenerateCsrfTokenSavesToSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = generateCsrfToken();
        
        $this->assertArrayHasKey('csrf_token', $_SESSION);
        $this->assertEquals($token, $_SESSION['csrf_token']);
    }
    
    /**
     * Test CSRF token verification
     */
    public function testVerifyCsrfTokenWithValidToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = generateCsrfToken();
        $result = verifyCsrfToken($token);
        
        $this->assertTrue($result);
    }
    
    public function testVerifyCsrfTokenWithInvalidToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        generateCsrfToken(); // Generate a valid token
        $result = verifyCsrfToken('invalid_token');
        
        $this->assertFalse($result);
    }
    
    /**
     * Test service types function
     */
    public function testGetServiceTypesReturnsArray()
    {
        $services = getServiceTypes();
        
        $this->assertIsArray($services);
        $this->assertNotEmpty($services);
    }
    
    public function testGetServiceTypesHasRequiredFields()
    {
        $services = getServiceTypes();
        
        foreach ($services as $key => $service) {
            $this->assertArrayHasKey('ar', $service);
            $this->assertArrayHasKey('tr', $service);
            $this->assertIsString($service['ar']);
            $this->assertIsString($service['tr']);
        }
    }
    
    /**
     * Test cities function
     */
    public function testGetCitiesReturnsArray()
    {
        $cities = getCities();
        
        $this->assertIsArray($cities);
        $this->assertNotEmpty($cities);
    }
    
    public function testGetCitiesHasRequiredFields()
    {
        $cities = getCities();
        
        foreach ($cities as $key => $city) {
            $this->assertArrayHasKey('ar', $city);
            $this->assertArrayHasKey('tr', $city);
            $this->assertIsString($city['ar']);
            $this->assertIsString($city['tr']);
        }
    }
    
    public function testGetCitiesContainsMajorCities()
    {
        $cities = getCities();
        
        $this->assertArrayHasKey('riyadh', $cities);
        $this->assertArrayHasKey('jeddah', $cities);
        $this->assertArrayHasKey('mecca', $cities);
    }
}



