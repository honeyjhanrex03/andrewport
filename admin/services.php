<?php
require_once 'includes/header.php';

function uploadFile(string $fileInput, string $uploadDir = '../assets/images/'): string|bool {
    if (isset($_FILES[$fileInput]) && $_FILES[$fileInput]['error'] == 0) {
        $ext = pathinfo($_FILES[$fileInput]['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        if (move_uploaded_file($_FILES[$fileInput]['tmp_name'], $uploadDir . $filename)) {
            return '/andrew/assets/images/' . $filename;
        }
    }
    return false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $icon_class = $_POST['icon'];
    $icon_color = $_POST['icon_color'] ?? '#ff6b57';
    
    if ($_POST['action'] == 'add') {
        $uploaded = uploadFile('icon_image');
        $icon_type = $uploaded ? 'image' : 'class';
        $icon_img = $uploaded ? $uploaded : '';
        
        $stmt = $pdo->prepare("INSERT INTO services (title, description, icon, icon_type, icon_image, icon_color) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $desc, $icon_class, $icon_type, $icon_img, $icon_color]);
        header("Location: services?success=Service Added"); exit;
        
    } elseif ($_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $uploaded = uploadFile('icon_image');
        
        if ($uploaded) {
            $stmt = $pdo->prepare("UPDATE services SET title=?, description=?, icon=?, icon_type='image', icon_image=?, icon_color=? WHERE id=?");
            $stmt->execute([$title, $desc, $icon_class, $uploaded, $icon_color, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE services SET title=?, description=?, icon=?, icon_color=? WHERE id=?");
            $stmt->execute([$title, $desc, $icon_class, $icon_color, $id]);
        }
        header("Location: services?success=Service Updated"); exit;
        
    } elseif ($_POST['action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM services WHERE id=?");
        $stmt->execute([$_POST['id']]);
        header("Location: services?success=Service Deleted"); exit;
    }
}

$services = $pdo->query("SELECT * FROM services ORDER BY id DESC")->fetchAll();
?>

<h2 class="fw-bold text-dark mb-4">Manage Services</h2>

<!-- ADD FORM CARD -->
<div class="card shadow-sm border-0 mb-5" style="border-radius: 12px;">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-4 text-dark">Add Service</h6>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <div class="row">
                <div class="col-md-5 mb-3">
                    <label class="form-label text-dark fw-bold small">Service Name *</label>
                    <input type="text" name="title" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" required>
                </div>
                <div class="col-md-5 mb-3">
                    <label class="form-label text-dark fw-bold small">Icon (Font Awesome Class)</label>
                    <input type="text" name="icon" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label text-dark fw-bold small">Icon Color</label>
                    <input type="color" name="icon_color" class="form-control bg-light border" style="border-radius: 8px; height: 46px; padding: 5px;" value="#ff6b57">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-dark fw-bold small">Or Upload Icon Image</label>
                    <input type="file" name="icon_image" class="form-control bg-light border" style="border-radius: 8px; padding: 8px 15px;" accept="image/*">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label text-dark fw-bold small">Description *</label>
                <textarea name="description" class="form-control bg-light border" style="border-radius: 8px; padding: 15px;" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-dark fw-bold px-4 py-2" style="border-radius: 8px;">Add Service</button>
        </form>
    </div>
</div>

<!-- LIST SERVICES -->
<div class="card overflow-hidden shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 border-0 py-3">Icon</th>
                        <th class="border-0 py-3">Title</th>
                        <th class="border-0 py-3">Description</th>
                        <th class="text-end pe-4 border-0 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($services as $s): ?>
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                <?php if(isset($s['icon_type']) && $s['icon_type'] == 'image' && !empty($s['icon_image'])): ?>
                                    <img src="<?= htmlspecialchars($s['icon_image']) ?>" style="max-width:100%; max-height:100%;">
                                <?php else: ?>
                                    <i class="<?= htmlspecialchars($s['icon']) ?> fs-4 text-dark"></i>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="fw-bold text-dark py-3"><?= htmlspecialchars($s['title']) ?></td>
                        <td class="text-muted py-3"><small><?= htmlspecialchars(substr($s['description'], 0, 80)) ?>...</small></td>
                        <td class="text-end pe-4 text-nowrap py-3">
                            <button class="btn btn-sm btn-light border text-primary rounded-circle me-1" style="width:35px;height:35px;" data-bs-toggle="modal" data-bs-target="#editModal<?= $s['id'] ?>"><i class="fa-solid fa-pen"></i></button>
                            <form method="POST" class="d-inline delete-form">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle" style="width:35px;height:35px;"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modals -->
<?php foreach($services as $s): ?>
<div class="modal fade" id="editModal<?= $s['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?= $s['id'] ?>">
                    
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label class="form-label text-dark fw-bold small">Service Name *</label>
                            <input type="text" name="title" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($s['title']) ?>" required>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label text-dark fw-bold small">Icon (Font Awesome Class)</label>
                            <input type="text" name="icon" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($s['icon']) ?>">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label text-dark fw-bold small">Icon Color</label>
                            <input type="color" name="icon_color" class="form-control bg-light border" style="border-radius: 8px; height: 46px; padding: 5px;" value="<?= htmlspecialchars($s['icon_color'] ?? '#ff6b57') ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold small">Or Upload Icon Image</label>
                            <input type="file" name="icon_image" class="form-control bg-light border" style="border-radius: 8px; padding: 8px 15px;" accept="image/*">
                            <small class="text-muted d-block mt-1">Leave empty to keep current icon</small>
                        </div>
                    </div>
                    <?php if(!empty($s['icon']) || !empty($s['icon_image'])): ?>
                    <div class="mb-4 p-3 bg-white rounded border text-center shadow-sm">
                        <span class="d-block small text-muted mb-2 fw-bold">CURRENT ICON / IMAGE</span>
                        <?php if(isset($s['icon_type']) && $s['icon_type'] == 'image'): ?>
                            <img src="<?= htmlspecialchars($s['icon_image']) ?>" style="height: 40px; max-width: 100%; object-fit: contain;">
                            <div class="small text-muted mt-2 text-break" style="font-size: 0.7rem;"><?= htmlspecialchars($s['icon_image']) ?></div>
                        <?php else: ?>
                            <i class="<?= htmlspecialchars($s['icon']) ?> fs-3 text-dark"></i>
                            <div class="small text-muted mt-2 fw-bold"><?= htmlspecialchars($s['icon']) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <div class="mb-4">
                        <label class="form-label text-dark fw-bold small">Description *</label>
                        <textarea name="description" class="form-control bg-light border" style="border-radius: 8px; padding: 15px;" rows="4" required><?= htmlspecialchars($s['description']) ?></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php require_once 'includes/footer.php'; ?>