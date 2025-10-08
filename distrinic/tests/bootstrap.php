<?php
// Define minimal constants required by included controllers
if (!defined('BASEPATH')) {
    define('BASEPATH', __DIR__ . '/../');
}

if (!defined('APPPATH')) {
    define('APPPATH', realpath(__DIR__ . '/../application/') . '/');
}

// Minimal stub for CI_Controller to avoid loading the full framework
if (!class_exists('CI_Controller')) {
    class CI_Controller
    {
    }
}

// Provide a log_message stub to capture logs during tests
if (!function_exists('log_message')) {
    function log_message($level, $message)
    {
        $GLOBALS['test_log_messages'][] = array(
            'level'   => $level,
            'message' => $message,
        );
    }
}

require_once APPPATH . 'controllers/Order.php';
