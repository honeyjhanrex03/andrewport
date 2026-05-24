<?php
require_once 'config/database.php';

$stmt = $pdo->query("SELECT cv_path, name FROM profile LIMIT 1");
$profile = $stmt->fetch();
$cv_path = get_asset_url($profile['cv_path'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($profile['name'] ?? 'Portfolio') ?> - Resume / CV</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/images/logo.png">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            background-color: #1e212b; /* Dark theme matching portfolio */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }
        .error-msg {
            color: #fff;
            text-align: center;
            margin-top: 20vh;
        }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ff6b57;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php if(!empty($cv_path)): ?>
        <!-- Using PDF.js or browser native PDF viewer via iframe -->
        <iframe src="<?= htmlspecialchars($cv_path) ?>#toolbar=1&navpanes=0&scrollbar=1&zoom=100" title="Resume PDF"></iframe>
    <?php else: ?>
        <div class="error-msg">
            <h2>Resume not available.</h2>
            <p>The CV document has not been uploaded yet.</p>
            <a href="<?= BASE_URL ?>" class="btn-back">Return to Portfolio</a>
        </div>
    <?php endif; ?>
</body>
</html>

