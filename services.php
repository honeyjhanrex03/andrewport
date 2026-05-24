<?php 
require_once 'includes/header.php'; 

$svc_stmt = $pdo->query("SELECT * FROM services ORDER BY id ASC");
$services = $svc_stmt->fetchAll();
?>

<section class="py-5 mt-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title mb-3">My Services</h2>
            <p class="text-center mx-auto" style="max-width: 600px; font-size: 1.1rem; line-height: 1.8;">
                Leveraging my academic background in Information Systems and practical experience, I offer specialized services tailored to meet your technical and administrative needs.
            </p>
        </div>
        
        <div class="row g-4 mt-2">
            <?php foreach($services as $service): ?>
            <div class="col-md-4">
                <div class="service-card flex-column align-items-center text-center p-5 h-100 position-relative overflow-hidden">
                    <div class="service-icon mb-4" style="font-size: 3rem; position: relative; z-index: 2;">
                        <i class="<?= htmlspecialchars($service['icon']) ?>" style="color: <?= htmlspecialchars($service['icon_color'] ?? 'var(--accent-color)') ?>;"></i>
                    </div>
                    <h4 class="service-title mb-3 position-relative z-index-2" style="font-size: 1.3rem;"><?= htmlspecialchars($service['title']) ?></h4>
                    <p class="text-secondary text-sm position-relative z-index-2 mb-0">
                        <?= htmlspecialchars($service['description']) ?>
                    </p>
                    <div class="position-absolute" style="font-size: 10rem; right: -20px; bottom: -30px; opacity: 0.03; z-index: 1;">
                        <i class="<?= htmlspecialchars($service['icon']) ?>"></i>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if(empty($services)): ?>
                <div class="col-12 text-center py-5">
                    <div class="p-5" style="background: rgba(255,255,255,0.02); border-radius: 16px; border: 1px dashed rgba(255,255,255,0.1);">
                        <i class="fa-solid fa-server text-white mb-3" style="font-size: 3rem; opacity: 0.2;"></i>
                        <h4 class="text-white opacity-75">No services added yet</h4>
                        <p class="text-white opacity-50">Services will appear here once added from the admin dashboard.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
