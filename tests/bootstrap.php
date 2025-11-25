<?php
/**
 * PHPUnit Bootstrap File
 * 
 * This file is executed before running tests.
 * It sets up the test environment and loads necessary files.
 */

// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load configuration (test environment)
$_ENV['APP_ENV'] = 'testing';
$_ENV['APP_DEBUG'] = 'true';
$_ENV['DB_HOST'] = '127.0.0.1';
$_ENV['DB_NAME'] = 'khidmaapp_test';
$_ENV['DB_USER'] = 'root';
$_ENV['DB_PASS'] = '';

// Load helper functions
require_once __DIR__ . '/../src/config/helpers.php';

// Disable output buffering for tests
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "PHPUnit Bootstrap loaded successfully!\n";



