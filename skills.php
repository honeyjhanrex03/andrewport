<?php
require_once 'includes/header.php';

// Fetch skills sorted by level (highest to lowest)
$skills = $pdo->query("SELECT * FROM skills ORDER BY level DESC")->fetchAll();
?>

<!-- Title Section -->
<section class="section-padding" style="padding-top: 120px; padding-bottom: 80px; min-height: 80vh;">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <h6 class="text-uppercase" style="color: var(--accent-color); letter-spacing: 2px; font-weight: 600;">My Expertise</h6>
            <h2 class="display-5 fw-bold text-white mb-3">Professional Skills</h2>
            <div style="width: 60px; height: 4px; background: var(--accent-color); margin: 0 auto; border-radius: 2px;"></div>
            <p class="text-white mt-4" style="max-width: 600px; margin: 0 auto; opacity: 0.8;">
                A comprehensive overview of my technical abilities and proficiencies, sorted by mastery level.
            </p>
        </div>

        <!-- Skills Grid -->
        <div class="row g-4 mt-4">
            <?php foreach($skills as $s): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 skill-card" style="background: rgba(255,255,255,0.03); backdrop-filter: blur(10px); border-radius: 16px; transition: all 0.3s ease;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center icon-container" style="width: 55px; height: 55px; background: rgba(255, 107, 87, 0.1); transition: all 0.3s ease;">
                                        <?php if(isset($s['icon_type']) && $s['icon_type'] == 'image' && !empty($s['icon_value'])): ?>
                                            <img src="<?= htmlspecialchars($s['icon_value']) ?>" style="width: 28px; height: 28px; object-fit: contain;">
                                        <?php else: ?>
                                            <i class="<?= htmlspecialchars($s['icon_value'] ?? 'fa-solid fa-code') ?>" style="color: var(--accent-color); font-size: 1.4rem;"></i>
                                        <?php endif; ?>
                                    </div>
                                    <h5 class="mb-0 text-white fw-bold" style="letter-spacing: 0.5px;"><?= htmlspecialchars($s['name']) ?></h5>
                                </div>
                                <span class="fw-bold fs-4" style="color: var(--accent-color);"><?= isset($s['level']) ? $s['level'] : 0 ?>%</span>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="progress mt-2" style="height: 10px; background: rgba(255,255,255,0.08); border-radius: 10px; overflow: hidden; box-shadow: inset 0 1px 2px rgba(0,0,0,0.2);">
                                <div class="progress-bar custom-progress" role="progressbar" 
                                     style="width: 0%; transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1); background: var(--accent-color);" 
                                     data-target-width="<?= isset($s['level']) ? $s['level'] : 0 ?>%"
                                     aria-valuenow="<?= isset($s['level']) ? $s['level'] : 0 ?>" 
                                     aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <?php if(empty($skills)): ?>
                <div class="col-12 text-center py-5">
                    <div class="p-5" style="background: rgba(255,255,255,0.02); border-radius: 16px; border: 1px dashed rgba(255,255,255,0.1);">
                        <i class="fa-solid fa-code text-white mb-3" style="font-size: 3rem; opacity: 0.2;"></i>
                        <h4 class="text-white opacity-75">No skills added yet</h4>
                        <p class="text-white opacity-50">Skills will appear here once added from the admin dashboard.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Custom CSS & Animation -->
<style>
    .skill-card:hover { 
        transform: translateY(-8px); 
        background: rgba(255,255,255,0.06) !important; 
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }
    .skill-card:hover .icon-container {
        background: var(--accent-color) !important;
    }
    .skill-card:hover .icon-container i {
        color: #fff !important;
    }
    
    .custom-progress {
        background-image: linear-gradient(45deg, rgba(255,255,255,.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,.15) 50%, rgba(255,255,255,.15) 75%, transparent 75%, transparent);
        background-size: 1rem 1rem;
        animation: progress-bar-stripes 1s linear infinite;
    }
    
    @keyframes progress-bar-stripes {
        0% { background-position: 1rem 0; }
        100% { background-position: 0 0; }
    }
</style>

<script>
// Animate progress bars on load
document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
        const bars = document.querySelectorAll('.custom-progress');
        bars.forEach(bar => {
            bar.style.width = bar.getAttribute('data-target-width');
        });
    }, 200);
});
</script>

<?php require_once 'includes/footer.php'; ?>
