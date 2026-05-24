<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

// Track visitor for Analytics
try {
    $ip = $_SERVER['REMOTE_ADDR'];
    $page = basename($_SERVER['PHP_SELF']);
    if(empty($page) || $page == 'index.php') $page = 'Home';
    $check = $pdo->prepare("SELECT id FROM page_views WHERE ip_address=? AND page=? AND created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)");
    $check->execute([$ip, $page]);
    if ($check->rowCount() == 0) {
        $pdo->prepare("INSERT INTO page_views (ip_address, page) VALUES (?, ?)")->execute([$ip, $page]);
    }
} catch (Exception $e) {}

// Fetch profile data globally since it's used in multiple places
$stmt = $pdo->query("SELECT * FROM profile LIMIT 1");
$profile = $stmt->fetch();
if (!$profile) {
    // Fallback if empty
    $profile = [
        'name' => 'Rovic Andrew',
        'title' => 'Information Systems Student',
        'phone' => '09489632834',
        'email' => 'bungalanandrew707@gmail.com',
        'address' => 'Brgy. Lasang Davao City',
        'linkedin' => 'https://www.linkedin.com/in/rovic-bungalan-4b132a409/',
        'objective' => '',
        'years_experience' => 2,
        'ojt_hours' => 80
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($profile['name']) ?> | Portfolio</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/andrew/assets/images/logo.png?v=<?= time() ?>">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/andrew/assets/css/style.css?v=<?= time() ?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/andrew/">
                <img src="/andrew/assets/images/logo.png?v=<?= time() ?>" alt="Andrew Logo" height="45" style="object-fit: contain; border-radius: 4px; border-radius: 50%;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border-color: rgba(255,255,255,0.1);">
                <span class="navbar-toggler-icon" style="background-image: url('data:image/svg+xml;charset=utf8,%3Csvg viewBox=\'0 0 30 30\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath stroke=\'rgba(255, 255, 255, 1)\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-miterlimit=\'10\' d=\'M4 7h22M4 15h22M4 23h22\'/%3E%3C/svg%3E');"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/andrew/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/andrew/about">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="/andrew/skills">Skills</a></li>
                    <li class="nav-item"><a class="nav-link" href="/andrew/certificates">Certificates</a></li>
                    <li class="nav-item"><a class="nav-link" href="/andrew/services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="/andrew/portfolio">Portfolio</a></li>
                    <li class="nav-item"><a class="nav-link" href="/andrew/contact">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </nav>
