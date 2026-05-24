<?php
ob_start();
require_once '../config/database.php';
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login");
    exit;
}

$msg_count = $pdo->query("SELECT count(*) FROM messages")->fetchColumn();
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// Fetch profile for header avatar
$profile_stmt = $pdo->query("SELECT profile_image FROM profile LIMIT 1");
$admin_profile = $profile_stmt->fetch();
$admin_avatar = (!empty($admin_profile['profile_image'])) ? $admin_profile['profile_image'] : 'https://ui-avatars.com/api/?name=Admin&background=1e212b&color=ff6b57';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Portfolio</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/andrew/assets/images/logo.png?v=<?= time() ?>">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --admin-bg: #f4f6f9; --sidebar-bg: #1e212b; --sidebar-hover: #2a2e3d; --accent: #ff6b57; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--admin-bg); overflow-x: hidden; }
        #wrapper { display: flex; width: 100%; align-items: stretch; }
        #sidebar-wrapper { min-width: 260px; max-width: 260px; background-color: var(--sidebar-bg); min-height: 100vh; transition: all 0.3s; }
        .sidebar-heading { padding: 1.5rem 1.25rem; font-size: 1.4rem; color: #fff; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .list-group-item { background: transparent; color: #a0a4b8; border: none; padding: 14px 20px; font-weight: 500; margin: 6px 15px; width: calc(100% - 30px); border-radius: 8px; transition: all 0.3s ease; }
        .list-group-item:hover { background: var(--sidebar-hover); color: #fff; transform: translateX(5px); }
        .list-group-item.active { background: var(--accent); color: #fff; box-shadow: 0 4px 15px rgba(255,107,87,0.3); }
        .list-group-item.active:hover { transform: none; }
        #page-content-wrapper { min-width: 0; width: 100%; display: flex; flex-direction: column; }
        .top-navbar { background: #fff; padding: 15px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; z-index: 10;}
        .content-area { padding: 30px; flex-grow: 1; overflow-y: auto; height: calc(100vh - 75px); }
        .card { border: none; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.03); transition: transform 0.3s; margin-bottom: 24px;}
        .card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.06); }
        .btn-primary { background-color: var(--accent); border-color: var(--accent); font-weight: 500;}
        .btn-primary:hover { background-color: #ff523b; border-color: #ff523b; }
        .swal2-popup { font-family: 'Poppins', sans-serif; border-radius: 15px; }
        .table > :not(caption) > * > * { padding: 1rem 1rem; }
        
        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            #sidebar-wrapper { margin-left: -260px; position: fixed; z-index: 1050; height: 100vh; }
            #wrapper.toggled #sidebar-wrapper { margin-left: 0; box-shadow: 10px 0 30px rgba(0,0,0,0.5); }
            .top-navbar { padding: 15px; }
            .content-area { padding: 15px; height: calc(100vh - 70px); }
            .mobile-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1040; backdrop-filter: blur(2px); }
            #wrapper.toggled .mobile-overlay { display: block; }
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <div class="mobile-overlay" id="mobile-overlay"></div>
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading fw-bold text-center d-flex align-items-center justify-content-center gap-2">
                <img src="/andrew/assets/images/logo.png" alt="Logo" style="width: 28px; height: 28px; object-fit: contain;">
                <span class="text-white">Admin<span style="font-weight:300; color: #a0a4b8;">Panel</span></span>
            </div>
            <div class="list-group list-group-flush mt-4">
                <a href="index" class="list-group-item list-group-item-action <?= $current_page == 'index' ? 'active' : '' ?>"><i class="fa-solid fa-chart-pie me-3" style="width: 20px;"></i> Analytics</a>
                <a href="profile" class="list-group-item list-group-item-action <?= $current_page == 'profile' ? 'active' : '' ?>"><i class="fa-solid fa-user me-3" style="width: 20px;"></i> Profile</a>
                <a href="skills" class="list-group-item list-group-item-action <?= $current_page == 'skills' ? 'active' : '' ?>"><i class="fa-solid fa-code me-3" style="width: 20px;"></i> Skills</a>
                <a href="portfolio" class="list-group-item list-group-item-action <?= $current_page == 'portfolio' ? 'active' : '' ?>"><i class="fa-solid fa-folder me-3" style="width: 20px;"></i> Projects</a>
                <a href="services" class="list-group-item list-group-item-action <?= $current_page == 'services' ? 'active' : '' ?>"><i class="fa-solid fa-briefcase me-3" style="width: 20px;"></i> Services</a>
                <a href="certificates" class="list-group-item list-group-item-action <?= $current_page == 'certificates' ? 'active' : '' ?>"><i class="fa-solid fa-certificate me-3" style="width: 20px;"></i> Certificates</a>
                <a href="messages" class="list-group-item list-group-item-action <?= $current_page == 'messages' ? 'active' : '' ?> d-flex justify-content-between align-items-center">
                    <span><i class="fa-solid fa-envelope me-3" style="width: 20px;"></i> Messages</span>
                    <?php if($msg_count > 0): ?>
                    <span class="badge bg-danger rounded-pill shadow-sm"><?= $msg_count ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="top-navbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn border shadow-sm d-md-none bg-white" id="menu-toggle"><i class="fa-solid fa-bars text-dark"></i></button>
                    <h4 class="mb-0 text-dark fw-bold"><?= ucfirst($current_page) ?></h4>
                </div>
                <div class="d-flex align-items-center gap-2 gap-sm-4">
                    <a href="../" target="_blank" class="btn btn-sm btn-light border px-2 px-sm-3 py-2 fw-bold text-secondary" title="Live Site"><i class="fa-solid fa-arrow-up-right-from-square me-sm-2"></i><span class="d-none d-sm-inline">Live Site</span></a>
                    <div class="dropdown">
                        <button class="btn btn-white border-0 dropdown-toggle d-flex align-items-center gap-2 px-0" type="button" data-bs-toggle="dropdown">
                            <img src="<?= htmlspecialchars($admin_avatar) ?>" class="rounded-circle shadow-sm" width="35" height="35" style="object-fit: cover; border: 2px solid #fff;">
                            <span class="fw-bold text-dark mx-1"><?= htmlspecialchars($_SESSION['admin_username']) ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3" style="border-radius: 12px;">
                            <li><a class="dropdown-item text-danger py-2 fw-bold" href="logout"><i class="fa-solid fa-right-from-bracket me-3"></i>Secure Logout</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="content-area">