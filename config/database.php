<?php
// Database configuration - Environment Aware
$is_local = in_array($_SERVER['HTTP_HOST'] ?? 'localhost', ['localhost', '127.0.0.1']);

if ($is_local) {
    // Local XAMPP Environment
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'andrew_portfolio_db');
} else {
    // Live InfinityFree Production Environment
    define('DB_HOST', 'sql102.infinityfree.com');
    define('DB_USER', 'if0_42004841');
    define('DB_PASS', '8ZY5rFTO6NH2');
    define('DB_NAME', 'if0_42004841_andrew_portfolio_db');
}

// Define dynamic base URL for assets and routing
define('BASE_URL', $is_local ? '/andrew/' : '/');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    if ($is_local) {
        die("Database Connection Error: " . $e->getMessage());
    }
}
?>
