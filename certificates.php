<?php
require_once 'includes/header.php';

// Fetch certificates ordered by newest first
$certificates = $pdo->query("SELECT * FROM certificates ORDER BY year DESC, id DESC")->fetchAll();
?>

<!-- Title Section -->
<section class="section-padding" style="padding-top: 120px; padding-bottom: 80px; min-height: 80vh;">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <h6 class="text-uppercase" style="color: var(--accent-color); letter-spacing: 2px; font-weight: 600;">Achievements</h6>
            <h2 class="display-5 fw-bold text-white mb-3">Licenses & Certificates</h2>
            <div style="width: 60px; height: 4px; background: var(--accent-color); margin: 0 auto; border-radius: 2px;"></div>
            <p class="text-white mt-4" style="max-width: 600px; margin: 0 auto; opacity: 0.8;">
                A collection of my professional certifications, awards, and completed industry trainings.
            </p>
        </div>

        <!-- Certificates Grid (2 columns matching screenshot) -->
        <div class="row g-5 mt-2" data-masonry='{"percentPosition": true }'>
            <?php foreach($certificates as $index => $c): ?>
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 border-0 cert-card shadow-lg" style="background: rgba(255,255,255,0.02); border-radius: 16px; overflow: hidden; transition: transform 0.3s ease;">
                        
                        <!-- Certificate Image (Clickable for Modal) -->
                        <div class="cert-img-wrapper position-relative" style="background: #ffffff; cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.05);" data-bs-toggle="modal" data-bs-target="#certModal<?= $c['id'] ?>">
                            <?php if(!empty($c['image_path'])): 
                                $img_src = $c['image_path'];
                                if (BASE_URL === '/' && strpos($img_src, '/andrew/') === 0) {
                                    $img_src = substr($img_src, 7);
                                }
                            ?>
                                <img src="<?= htmlspecialchars($img_src) ?>" class="w-100" style="height: auto; object-fit: contain;" alt="Certificate">
                            <?php else: ?>
                                <div style="height: 250px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.02);">
                                    <i class="fa-solid fa-certificate text-muted" style="font-size: 5rem; opacity: 0.2;"></i>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Hover Overlay -->
                            <div class="cert-overlay">
                                <i class="fa-solid fa-magnifying-glass-plus fs-1 text-white"></i>
                            </div>
                        </div>

                        <!-- Certificate Body -->
                        <div class="card-body p-5">
                            <h4 class="fw-bold text-white mb-2" style="line-height: 1.4;"><?= htmlspecialchars($c['title']) ?></h4>
                            <p class="text-muted small mb-3">
                                <?= htmlspecialchars($c['month']) ?> <?= htmlspecialchars($c['year']) ?>
                            </p>
                            
                            <h6 class="fw-bold mb-3" style="color: var(--accent-color);"><?= htmlspecialchars($c['issued_by']) ?></h6>
                            
                            <p class="text-white mb-4" style="opacity: 0.7; line-height: 1.7; font-size: 0.95rem;">
                                <?= nl2br(htmlspecialchars($c['description'])) ?>
                            </p>
                            
                            <!-- Keywords/Pills -->
                            <?php if(!empty($c['keywords'])): ?>
                                <div class="d-flex flex-wrap gap-2 mt-auto">
                                    <?php 
                                    $keywords = explode(',', $c['keywords']);
                                    foreach($keywords as $kw): 
                                        $kw = trim($kw);
                                        if(empty($kw)) continue;
                                    ?>
                                        <span class="badge" style="background: rgba(255,255,255,0.05); color: #e2e8f0; font-weight: 500; padding: 8px 12px; border-radius: 20px; font-size: 0.75rem; letter-spacing: 0.5px;">
                                            <?= htmlspecialchars($kw) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div> <!-- End of Masonry Grid -->

        <!-- Modals outside Masonry grid -->
        <?php foreach($certificates as $c): ?>
            <!-- Enlarged Boxed Modal for this Certificate -->
            <div class="modal fade" id="certModal<?= $c['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 85vw;">
                    <div class="modal-content border-0 shadow-lg" style="background: #ffffff; border-radius: 12px; overflow: hidden;">
                        
                        <!-- Dark Header matching reference -->
                        <div class="modal-header bg-dark border-0" style="padding: 12px 20px;">
                            <h5 class="modal-title text-white fs-6 fw-semibold mb-0"><?= htmlspecialchars($c['title']) ?></h5>
                            <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.8;"></button>
                        </div>

                        <!-- Full Image Body -->
                        <div class="modal-body p-0 text-center bg-light">
                            <?php if(!empty($c['image_path'])): 
                                $img_src = $c['image_path'];
                                if (BASE_URL === '/' && strpos($img_src, '/andrew/') === 0) {
                                    $img_src = substr($img_src, 7);
                                }
                            ?>
                                <img src="<?= htmlspecialchars($img_src) ?>" class="w-100 h-auto" alt="Certificate Full View" style="max-height: 85vh; object-fit: contain;">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

            <?php if(empty($certificates)): ?>
                <div class="col-12 text-center py-5">
                    <div class="p-5" style="background: rgba(255,255,255,0.02); border-radius: 16px; border: 1px dashed rgba(255,255,255,0.1);">
                        <i class="fa-solid fa-certificate text-white mb-3" style="font-size: 3rem; opacity: 0.2;"></i>
                        <h4 class="text-white opacity-75">No certificates added yet</h4>
                        <p class="text-white opacity-50">Certificates will appear here once added from the admin dashboard.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Custom CSS & Hover Effects -->
<style>
    .cert-card:hover { 
        transform: translateY(-8px); 
        box-shadow: 0 20px 40px rgba(0,0,0,0.4) !important;
    }
    
    .cert-img-wrapper {
        position: relative;
    }
    
    .cert-overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255, 107, 87, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .cert-img-wrapper:hover .cert-overlay {
        opacity: 1;
    }
</style>

<!-- Masonry script for optimal grid layout if cards have different heights -->
<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async></script>

<?php require_once 'includes/footer.php'; ?>


