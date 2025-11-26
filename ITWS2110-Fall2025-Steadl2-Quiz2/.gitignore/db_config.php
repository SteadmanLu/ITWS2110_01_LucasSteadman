<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'admin');
define('DB_PASS', 'della?20');
define('DB_NAME', 'ITWS2110-Fall2025-yourRCSID-Quiz2');

// Create connection
function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        // Set charset to utf8mb4 for proper character support
        $conn->set_charset("utf8mb4");
        
        return $conn;
    } catch (Exception $e) {
        die("Database connection error: " . $e->getMessage());
    }
}

// Helper function to safely close connection
function closeDBConnection($conn) {
    if ($conn) {
        $conn->close();
    }
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>