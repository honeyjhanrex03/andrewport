<?php 
require_once 'includes/header.php'; 

// Fetch all portfolio projects
$port_stmt = $pdo->query("SELECT * FROM portfolio ORDER BY id DESC");
$portfolios = $port_stmt->fetchAll();
?>

<!-- Title Section -->
<section class="section-padding" style="padding-top: 120px; padding-bottom: 80px; min-height: 80vh;">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <h6 class="text-uppercase" style="color: var(--accent-color); letter-spacing: 2px; font-weight: 600;">Showcase</h6>
            <h2 class="display-5 fw-bold text-white mb-3">My Portfolio</h2>
            <div style="width: 60px; height: 4px; background: var(--accent-color); margin: 0 auto; border-radius: 2px;"></div>
            <p class="text-white mt-4" style="max-width: 600px; margin: 0 auto; opacity: 0.8;">
                A showcase of my recent academic projects, UI/UX designs, and data entry samples. Click any project to view details and image galleries.
            </p>
        </div>
        
        <div class="row g-5 mt-3">
            <?php foreach($portfolios as $portfolio): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card bg-transparent border-0 h-100 group project-card" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#projectModal<?= $portfolio['id'] ?>">
                    <div class="position-relative overflow-hidden mb-4 shadow-lg" style="border-radius: 16px !important; background: rgba(255,255,255,0.02);">
                        <?php if(!empty($portfolio['image_path'])): ?>
                            <img src="<?= htmlspecialchars($portfolio['image_path']) ?>" class="img-fluid w-100" alt="<?= htmlspecialchars($portfolio['title']) ?>" style="height: 250px; object-fit: cover; transition: transform 0.5s ease;">
                        <?php else: ?>
                            <div style="height: 250px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.05);">
                                <i class="fa-solid fa-image text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="project-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(255, 107, 87, 0.85); opacity: 0; transition: 0.3s;">
                            <span class="btn btn-light fw-bold rounded-pill px-4 py-2" style="color: var(--accent-color);"><i class="fa-solid fa-eye me-2"></i>View Project</span>
                        </div>
                    </div>
                    <div class="px-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h4 class="text-white fw-bold mb-0" style="line-height: 1.3; font-size: 1.25rem;"><?= htmlspecialchars($portfolio['title']) ?></h4>
                        </div>
                        <p class="text-secondary mt-2 mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= htmlspecialchars($portfolio['description']) ?></p>
                    </div>
                </div>
            </div>

            <!-- Enhanced Fullscreen Modal for Project Details -->
            <div class="modal fade" id="projectModal<?= $portfolio['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content border-0" style="background: #161821; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px rgba(0,0,0,0.5);">
                        <div class="modal-header border-0 pb-0 position-absolute top-0 end-0 z-3" style="padding: 1.5rem;">
                            <button type="button" class="btn-close btn-close-white shadow-none bg-dark rounded-circle p-3" data-bs-dismiss="modal" aria-label="Close" style="opacity: 0.9;"></button>
                        </div>
                        
                        <div class="modal-body p-0">
                            <div class="row g-0">
                                <!-- Left Side: Carousel -->
                                <div class="col-lg-7 bg-black position-relative" style="min-height: 400px; display: flex; align-items: center;">
                                    <?php 
                                    // Parse additional images JSON
                                    $additional = json_decode($portfolio['additional_images'], true);
                                    $has_additional = !empty($additional) && is_array($additional);
                                    ?>
                                    
                                    <?php if($has_additional || !empty($portfolio['image_path'])): ?>
                                        <div id="carousel<?= $portfolio['id'] ?>" class="carousel slide carousel-fade w-100 h-100" data-bs-ride="carousel" data-bs-interval="2500">
                                            
                                            <!-- Indicators -->
                                            <?php if($has_additional): ?>
                                            <div class="carousel-indicators mb-4">
                                                <button type="button" data-bs-target="#carousel<?= $portfolio['id'] ?>" data-bs-slide-to="0" class="active"></button>
                                                <?php foreach($additional as $idx => $img): ?>
                                                    <button type="button" data-bs-target="#carousel<?= $portfolio['id'] ?>" data-bs-slide-to="<?= $idx + 1 ?>"></button>
                                                <?php endforeach; ?>
                                            </div>
                                            <?php endif; ?>

                                            <div class="carousel-inner h-100">
                                                <?php if(!empty($portfolio['image_path'])): ?>
                                                <div class="carousel-item active h-100">
                                                    <div style="height: 100%; min-height: 500px; display: flex; align-items: center; justify-content: center; padding: 2rem;">
                                                        <img src="<?= htmlspecialchars($portfolio['image_path']) ?>" class="d-block w-100 rounded shadow" style="max-height: 80vh; object-fit: contain;" alt="Main Image">
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                
                                                <?php if($has_additional): ?>
                                                    <?php foreach($additional as $idx => $img): ?>
                                                    <div class="carousel-item h-100 <?= (empty($portfolio['image_path']) && $idx === 0) ? 'active' : '' ?>">
                                                        <div style="height: 100%; min-height: 500px; display: flex; align-items: center; justify-content: center; padding: 2rem;">
                                                            <img src="<?= htmlspecialchars($img) ?>" class="d-block w-100 rounded shadow" style="max-height: 80vh; object-fit: contain;" alt="Additional Image <?= $idx + 1 ?>">
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if($has_additional): ?>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?= $portfolio['id'] ?>" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon p-3 rounded-circle bg-dark bg-opacity-50" aria-hidden="true"></span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carousel<?= $portfolio['id'] ?>" data-bs-slide="next">
                                                <span class="carousel-control-next-icon p-3 rounded-circle bg-dark bg-opacity-50" aria-hidden="true"></span>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="w-100 text-center py-5">
                                            <i class="fa-solid fa-image text-muted" style="font-size: 5rem; opacity: 0.2;"></i>
                                            <p class="text-muted mt-3">No images available</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Right Side: Details -->
                                <div class="col-lg-5 p-5 d-flex flex-column" style="background: #1e212b; max-height: 90vh; overflow-y: auto;">
                                    <h3 class="fw-bold text-white mb-4" style="line-height: 1.4;"><?= htmlspecialchars($portfolio['title']) ?></h3>
                                    
                                    <!-- Project Overview -->
                                    <div class="mb-4 p-4 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fa-solid fa-file-alt fs-5 me-3" style="color: var(--accent-color);"></i>
                                            <h5 class="fw-bold text-white mb-0">Project Overview</h5>
                                        </div>
                                        <p class="text-secondary mb-0" style="line-height: 1.8;">
                                            <?= nl2br(htmlspecialchars($portfolio['description'])) ?>
                                        </p>
                                    </div>
                                    
                                    <!-- Technologies -->
                                    <?php if(!empty($portfolio['tech_stack'])): ?>
                                    <div class="mb-4 p-4 rounded" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fa-solid fa-code fs-5 me-3" style="color: var(--accent-color);"></i>
                                            <h5 class="fw-bold text-white mb-0">Technologies</h5>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php 
                                            $techs = explode(',', $portfolio['tech_stack']);
                                            foreach($techs as $tech): 
                                                $tech = trim($tech);
                                                if(empty($tech)) continue;
                                            ?>
                                                <span class="badge" style="background: rgba(255,255,255,0.1); color: #fff; font-weight: 500; padding: 8px 15px; border-radius: 20px;">
                                                    <?= htmlspecialchars($tech) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- Client & Date -->
                                    <div class="row g-3 mb-4">
                                        <?php if(!empty($portfolio['client'])): ?>
                                        <div class="col-sm-6">
                                            <div class="p-4 rounded h-100" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fa-solid fa-user-tie text-muted me-2"></i>
                                                    <h6 class="fw-bold text-white mb-0">Client / Context</h6>
                                                </div>
                                                <p class="text-secondary mb-0 small"><?= htmlspecialchars($portfolio['client']) ?></p>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($portfolio['project_date'])): ?>
                                        <div class="col-sm-6">
                                            <div class="p-4 rounded h-100" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fa-regular fa-calendar text-muted me-2"></i>
                                                    <h6 class="fw-bold text-white mb-0">Project Date</h6>
                                                </div>
                                                <p class="text-secondary mb-0 small"><?= htmlspecialchars($portfolio['project_date']) ?></p>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Visit Project Button -->
                                    <?php if(!empty($portfolio['link'])): ?>
                                    <div class="mt-auto">
                                        <a href="<?= htmlspecialchars($portfolio['link']) ?>" target="_blank" class="btn w-100 fw-bold py-3 text-white" style="background: var(--accent-color); border-radius: 12px; font-size: 1.1rem; transition: all 0.3s;">
                                            Visit Live Project <i class="fa-solid fa-arrow-up-right-from-square ms-2"></i>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if(empty($portfolios)): ?>
                <div class="col-12 text-center py-5">
                    <div class="p-5" style="background: rgba(255,255,255,0.02); border-radius: 16px; border: 1px dashed rgba(255,255,255,0.1);">
                        <i class="fa-solid fa-briefcase text-white mb-3" style="font-size: 3rem; opacity: 0.2;"></i>
                        <h4 class="text-white opacity-75">No projects added yet</h4>
                        <p class="text-white opacity-50">Portfolio projects will appear here once added from the admin dashboard.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Custom CSS & Animations -->
<style>
    .project-card:hover img {
        transform: scale(1.05);
    }
    .project-card:hover .project-overlay {
        opacity: 1 !important;
    }
    
    /* Make Modal scrollable cleanly inside */
    .modal-xl {
        max-width: 1200px;
    }
    
    .carousel-item {
        transition: transform 1.5s ease-in-out, opacity 1s ease-in-out;
    }
</style>

<?php require_once 'includes/footer.php'; ?>

