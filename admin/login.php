<?php
require_once '../config/database.php';
session_start();
if(isset($_SESSION['admin_logged_in'])) {
    header("Location: index");
    exit;
}
$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();
    if($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user['username'];
        header("Location: index");
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/images/logo.png?v=<?= time() ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #161821; color: #fff; display: flex; align-items: center; justify-content: center; height: 100vh; font-family: 'Poppins', sans-serif;}
        .login-card { background: #1e212b; padding: 40px; border-radius: 15px; width: 100%; max-width: 400px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid rgba(255,107,87,0.2); }
        .form-control { background: #161821 !important; border: 1px solid rgba(255,255,255,0.2) !important; color: #ffffff !important; padding: 12px; font-weight: 500; }
        .form-control:focus { background: #161821 !important; border-color: #ff6b57 !important; color: #ffffff !important; box-shadow: 0 0 0 0.25rem rgba(255,107,87,0.25) !important; }
        .form-control::placeholder { color: #a0a4b8 !important; opacity: 1 !important; }
        .btn-primary { background: #ff6b57; border: none; font-weight: 600; letter-spacing: 1px; }
        .btn-primary:hover { background: #ff523b; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <img src="<?= BASE_URL ?>assets/images/logo.png" alt="Logo" style="width: 60px; height: 60px; object-fit: contain; margin-bottom: 10px;">
            <h3 class="fw-bold m-0 text-white">Admin<span style="color: #a0a4b8; font-weight: 300;">Panel</span></h3>
        </div>
        <?php if($error): ?>
            <div class="alert alert-danger" style="background: rgba(220,53,69,0.1); border: 1px solid #dc3545; color: #ff8a93;"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="small mb-2 fw-bold" style="color: #a0a4b8 !important; letter-spacing: 1px;">USERNAME</label>
                <input type="text" name="username" class="form-control" required placeholder="admin">
            </div>
            <div class="mb-4">
                <label class="small mb-2 fw-bold" style="color: #a0a4b8 !important; letter-spacing: 1px;">PASSWORD</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary w-100 py-3 mt-2">Secure Login</button>
        </form>
    </div>
</body>
</html>
