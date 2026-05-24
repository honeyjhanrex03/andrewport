<?php 
require_once 'includes/header.php'; 

// Fetch education
$edu_stmt = $pdo->query("SELECT * FROM education ORDER BY id ASC");
$educations = $edu_stmt->fetchAll();

// Fetch experiences
$exp_stmt = $pdo->query("SELECT * FROM experiences ORDER BY id ASC");
$experiences = $exp_stmt->fetchAll();

// Fetch references
$ref_stmt = $pdo->query("SELECT * FROM references_list ORDER BY id ASC");
$references = $ref_stmt->fetchAll();
?>

<section class="py-5 mt-5">
    <div class="container">
        <!-- Header & Objective -->
        <div class="row justify-content-center text-center mb-5 pb-4 border-bottom" style="border-color: rgba(255,255,255,0.05) !important;">
            <div class="col-lg-8">
                <h2 class="section-title mb-4">About Me</h2>
                <h4 class="mb-4 text-white" style="font-weight: 400; line-height: 1.6;">
                    Hello! I'm <span style="color: var(--accent-color); font-weight: 600;"><?= htmlspecialchars($profile['name']) ?></span>, an <?= htmlspecialchars($profile['title']) ?>.
                </h4>
                <p class="lh-lg " style="font-size: 1.05rem;">
                    <?= nl2br(htmlspecialchars($profile['objective'])) ?>
                </p>
            </div>
        </div>

        <!-- Stats Banner -->
        <div class="row mb-5 justify-content-center">
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="p-4 rounded text-center h-100" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                    <h2 class="display-4 text-white fw-bold mb-0">
                        <?= htmlspecialchars($profile['years_experience']) ?><span style="color: var(--accent-color);">+</span>
                    </h2>
                    <p class="mt-2 mb-0  text-uppercase" style="font-size: 0.85rem; font-weight: 600; letter-spacing: 1px;">Years Academic Experience</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="p-4 rounded text-center h-100" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                    <h2 class="display-4 text-white fw-bold mb-0">
                        <?= htmlspecialchars($profile['ojt_hours']) ?><span style="color: var(--accent-color);">h</span>
                    </h2>
                    <p class="mt-2 mb-0  text-uppercase" style="font-size: 0.85rem; font-weight: 600; letter-spacing: 1px;">OJT Training Completed</p>
                </div>
            </div>
        </div>

        <!-- Timeline Section -->
        <div class="row mt-5 pt-4">
            <!-- Education Timeline -->
            <div class="col-lg-6 mb-5 pr-lg-5">
                <h4 class="mb-5 d-flex align-items-center gap-3 text-white">
                    <div class="d-flex align-items-center justify-content-center rounded" style="width: 45px; height: 45px; background: rgba(255, 107, 87, 0.1); color: var(--accent-color);">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    My Education
                </h4>
                
                <div class="timeline">
                    <?php foreach($educations as $edu): ?>
                    <div class="timeline-item">
                        <span class="timeline-date"><?= htmlspecialchars($edu['years']) ?></span>
                        <h5 class="timeline-title"><?= htmlspecialchars($edu['degree']) ?></h5>
                        <p class="timeline-subtitle"><i class="fa-solid fa-building-columns me-2" style="font-size: 0.8rem;"></i><?= htmlspecialchars($edu['institution']) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Experience Timeline -->
            <div class="col-lg-6 mb-5 pl-lg-5">
                <h4 class="mb-5 d-flex align-items-center gap-3 text-white">
                    <div class="d-flex align-items-center justify-content-center rounded" style="width: 45px; height: 45px; background: rgba(255, 107, 87, 0.1); color: var(--accent-color);">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    My Experience
                </h4>
                
                <div class="timeline">
                    <?php foreach($experiences as $exp): ?>
                    <div class="timeline-item">
                        <span class="timeline-date"><?= htmlspecialchars($exp['dates']) ?></span>
                        <h5 class="timeline-title"><?= htmlspecialchars($exp['title']) ?></h5>
                        <p class="timeline-subtitle"><i class="fa-solid fa-building me-2" style="font-size: 0.8rem;"></i><?= htmlspecialchars($exp['company']) ?></p>
                        <p class="" style="font-size: 0.9rem; line-height: 1.6;"><?= htmlspecialchars($exp['description']) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- References -->
        <div class="row mt-4 border-top pt-5" style="border-color: rgba(255,255,255,0.05) !important;">
            <div class="col-12 text-center mb-5">
                <h4 class="text-white">References</h4>
            </div>
            <?php foreach($references as $ref): ?>
            <div class="col-md-6 mb-4">
                <div class="d-flex align-items-center p-4 rounded h-100 reference-card" style="background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.05);">
                    <div class="flex-shrink-0 me-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: rgba(255,107,87,0.1); color: var(--accent-color); font-size: 1.5rem;">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="text-white mb-1"><?= htmlspecialchars($ref['name']) ?></h5>
                        <p class="mb-0 " style="font-size: 0.9rem;"><?= $ref['title'] ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
