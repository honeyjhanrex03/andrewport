<?php
require_once 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $title = $_POST['title'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $linkedin = $_POST['linkedin'];
    $objective = $_POST['objective'];
    $years_experience = $_POST['years_experience'];
    $ojt_hours = $_POST['ojt_hours'];

    // Handle CV upload
    $cv_path = $_POST['current_cv'] ?? '';
    if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] == 0) {
        $ext = pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), ['pdf', 'doc', 'docx'])) {
            $filename = 'cv_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['cv_file']['tmp_name'], '../assets/' . $filename)) {
                $cv_path = BASE_URL . 'assets/' . $filename;
            }
        }
    }
    
    // Handle Profile Image upload
    $img_path = $_POST['current_image'] ?? '';
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp'])) {
            $filename = 'profile_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], '../assets/images/' . $filename)) {
                $img_path = BASE_URL . 'assets/images/' . $filename;
            }
        }
    }

    $stmt = $pdo->prepare("UPDATE profile SET name=?, title=?, phone=?, email=?, address=?, linkedin=?, objective=?, cv_path=?, profile_image=?, years_experience=?, ojt_hours=? WHERE id=1");
    $stmt->execute([$name, $title, $phone, $email, $address, $linkedin, $objective, $cv_path, $img_path, $years_experience, $ojt_hours]);
    
    $success = "Profile updated successfully!";
}

$stmt = $pdo->query("SELECT * FROM profile LIMIT 1");
$profile = $stmt->fetch();
if (!$profile) {
    // If no row exists, insert a default one
    $pdo->exec("INSERT INTO profile (id, name) VALUES (1, 'Your Name')");
    $profile = $pdo->query("SELECT * FROM profile LIMIT 1")->fetch();
}
?>

<h2 class="fw-bold text-dark mb-4">Manage Profile</h2>

<?php if(isset($success)): ?>
    <div class="alert alert-success bg-success bg-opacity-10 border-success text-success fw-bold rounded-3 mb-4">
        <i class="fa-solid fa-check-circle me-2"></i><?= $success ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-5" style="border-radius: 12px;">
    <div class="card-body p-5">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="update_profile" value="1">
            <input type="hidden" name="current_cv" value="<?= htmlspecialchars($profile['cv_path'] ?? '') ?>">
            <input type="hidden" name="current_image" value="<?= htmlspecialchars($profile['profile_image'] ?? '') ?>">

            <div class="row mb-4 align-items-center">
                <div class="col-md-3 text-center">
                    <?php if(!empty($profile['profile_image'])): ?>
                        <img src="<?= htmlspecialchars(str_replace('/andrew/', BASE_URL, $profile['profile_image'])) ?>" class="rounded-circle shadow" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid var(--admin-bg);">
                    <?php else: ?>
                        <div class="rounded-circle shadow d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px; background: #e9ecef; border: 4px solid var(--admin-bg);">
                            <i class="fa-solid fa-user text-secondary" style="font-size: 4rem;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-9">
                    <label class="form-label fw-bold text-dark small">Profile Image</label>
                    <input type="file" name="profile_image" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" accept="image/*">
                    <small class="text-muted d-block mt-1">Recommended size: 500x500px (JPG, PNG)</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-dark small">Full Name *</label>
                    <input type="text" name="name" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($profile['name'] ?? '') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-dark small">Professional Title *</label>
                    <input type="text" name="title" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($profile['title'] ?? '') ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-dark small">Email Address</label>
                    <input type="email" name="email" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($profile['email'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-dark small">Phone Number</label>
                    <input type="text" name="phone" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($profile['phone'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold text-dark small">Address / Location</label>
                <input type="text" name="address" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($profile['address'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold text-dark small">LinkedIn URL</label>
                <input type="url" name="linkedin" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($profile['linkedin'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold text-dark small">About Me (Objective)</label>
                <textarea name="objective" class="form-control bg-light border" style="border-radius: 8px; padding: 15px;" rows="5"><?= htmlspecialchars($profile['objective'] ?? '') ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-dark small">Years of Experience</label>
                    <input type="number" name="years_experience" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($profile['years_experience'] ?? 0) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-dark small">OJT Hours</label>
                    <input type="number" name="ojt_hours" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($profile['ojt_hours'] ?? 0) ?>">
                </div>
            </div>

            <div class="mb-4 p-4 rounded border bg-light">
                <label class="form-label fw-bold text-dark small d-block">Upload CV/Resume Document</label>
                <input type="file" name="cv_file" class="form-control mb-2" accept=".pdf,.doc,.docx">
                <?php if(!empty($profile['cv_path'])): ?>
                    <div class="small text-muted mt-2">
                        <i class="fa-solid fa-file-pdf text-danger me-1"></i> Current CV: <a href="<?= htmlspecialchars(str_replace('/andrew/', BASE_URL, $profile['cv_path'])) ?>" target="_blank" class="fw-bold"><?= basename($profile['cv_path']) ?></a>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary fw-bold px-5 py-3 rounded-pill shadow-sm" style="font-size: 1.1rem;">
                <i class="fa-solid fa-floppy-disk me-2"></i> Save Profile Settings
            </button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>


