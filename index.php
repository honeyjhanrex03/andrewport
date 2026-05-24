<?php 
require_once 'includes/header.php'; 

// Fetch skills
$skills_stmt = $pdo->query("SELECT * FROM skills");
$skills = $skills_stmt->fetchAll();
?>

<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0 order-2 order-lg-1">
                <div class="hero-subtitle">Hello .</div>
                <h1 class="hero-title">I'm <?= htmlspecialchars($profile['name']) ?><br><span><?= htmlspecialchars($profile['title']) ?></span></h1>
                <p class="mb-5 mt-3" style="max-width: 500px; line-height: 1.8;">
                    <?= htmlspecialchars(substr($profile['objective'], 0, 160)) ?>... Looking to use analytical and IT skills in a professional setting.
                </p>
                <div class="d-flex gap-3">
                    <a href="<?= BASE_URL ?>contact" class="btn-accent">Got a project?</a>
                    <?php if(!empty($profile['cv_path'])): ?>
                        <a href="<?= BASE_URL ?>view_cv" target="_blank" class="btn-outline-light"><i class="fa-solid fa-file-pdf me-2 text-danger"></i> View CV</a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>about" class="btn-outline-light">My resume</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 text-center order-1 order-lg-2 mb-5 mb-lg-0">
                <div class="hero-img-container">
                    <div class="hero-img-bg"></div>
                    <?php 
                    $raw_img = !empty($profile['profile_image']) ? $profile['profile_image'] : '/andrew/assets/images/andrew.jpg';
                    $img_src = htmlspecialchars(str_replace('/andrew/', BASE_URL, $raw_img));
                    ?>
                    <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($profile['name']) ?>" class="hero-img" onerror="this.src='https://via.placeholder.com/400x400/1e212b/ffffff?text=Profile+Image'">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Premium Animated Skills Marquee -->
<section class="py-4 border-top border-bottom position-relative overflow-hidden" style="border-color: var(--border-color) !important; background-color: #12141c;">
    <!-- Gradient Overlays for smooth fading edges -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(90deg, #12141c 0%, transparent 15%, transparent 85%, #12141c 100%); z-index: 2; pointer-events: none;"></div>
    
    <div class="marquee-wrapper d-flex" style="width: fit-content;">
        <?php 
        // Fetch skills ordered by level for the ticker
        $skills_ticker = $pdo->query("SELECT * FROM skills ORDER BY level DESC")->fetchAll();
        
        // Output two sets for continuous infinite scroll
        for($i = 0; $i < 2; $i++): 
        ?>
        <div class="marquee-content d-flex align-items-center">
            <?php foreach($skills_ticker as $skill): ?>
                <div class="marquee-item d-flex align-items-center mx-5" style="white-space: nowrap;">
                    <?php if(isset($skill['icon_type']) && $skill['icon_type'] == 'image' && !empty($skill['icon_value'])): ?>
                        <img src="<?= htmlspecialchars($skill['icon_value']) ?>" class="marquee-icon-img me-3" alt="<?= htmlspecialchars($skill['name']) ?>" style="width: 45px; height: 45px; object-fit: contain;">
                    <?php else: ?>
                        <!-- Fallback to FontAwesome if class is provided, else generic code icon -->
                        <i class="<?= htmlspecialchars($skill['icon_value'] ?? 'fa-solid fa-code') ?> me-3" style="font-size: 2.2rem; color: var(--accent-color);"></i>
                    <?php endif; ?>
                    <span class="marquee-text text-uppercase fw-bold" style="color: rgba(255,255,255,0.7); letter-spacing: 3px; font-size: 1.1rem;"><?= htmlspecialchars($skill['name']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endfor; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

