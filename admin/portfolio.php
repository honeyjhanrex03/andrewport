<?php
require_once 'includes/header.php';
function uploadFile($fileInput, $uploadDir = '../assets/images/') {
    if (isset($_FILES[$fileInput]) && $_FILES[$fileInput]['error'] == 0) {
        $ext = pathinfo($_FILES[$fileInput]['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        if (move_uploaded_file($_FILES[$fileInput]['tmp_name'], $uploadDir . $filename)) {
            return BASE_URL . 'assets/images/' . $filename;
        }
    }
    return false;
}

function uploadMultipleFiles($fileInput, $uploadDir = '../assets/images/') {
    $paths = [];
    if (isset($_FILES[$fileInput]) && is_array($_FILES[$fileInput]['name'])) {
        $total = count($_FILES[$fileInput]['name']);
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        for ($i = 0; $i < $total; $i++) {
            if ($_FILES[$fileInput]['error'][$i] == 0) {
                $ext = pathinfo($_FILES[$fileInput]['name'][$i], PATHINFO_EXTENSION);
                $filename = uniqid() . '_' . $i . '.' . $ext;
                if (move_uploaded_file($_FILES[$fileInput]['tmp_name'][$i], $uploadDir . $filename)) {
                    $paths[] = BASE_URL . 'assets/images/' . $filename;
                }
            }
        }
    }
    return $paths;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $link = $_POST['link'];
    $ts = $_POST['tech_stack'];
    $client = $_POST['client'];
    $pdate = $_POST['project_date'];
    
    if ($_POST['action'] == 'add') {
        $main_img = uploadFile('main_image');
        $multi_imgs = uploadMultipleFiles('additional_images');
        $add_imgs_json = !empty($multi_imgs) ? json_encode($multi_imgs) : null;
        
        $stmt = $pdo->prepare("INSERT INTO portfolio (title, category, image_path, additional_images, link, description, tech_stack, client, project_date) VALUES (?, 'Project', ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $main_img ? $main_img : '', $add_imgs_json, $link, $desc, $ts, $client, $pdate]);
        header("Location: portfolio.php?success=Project Added"); exit;
        
    } elseif ($_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $main_img = uploadFile('main_image');
        $multi_imgs = uploadMultipleFiles('additional_images');
        
        $sql = "UPDATE portfolio SET title=?, description=?, link=?, tech_stack=?, client=?, project_date=?";
        $params = [$title, $desc, $link, $ts, $client, $pdate];
        
        if ($main_img) {
            $sql .= ", image_path=?";
            $params[] = $main_img;
        }
        if (!empty($multi_imgs)) {
            $sql .= ", additional_images=?";
            $params[] = json_encode($multi_imgs);
        }
        $sql .= " WHERE id=?";
        $params[] = $id;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        header("Location: portfolio.php?success=Project Updated"); exit;
        
    } elseif ($_POST['action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM portfolio WHERE id=?");
        $stmt->execute([$_POST['id']]);
        header("Location: portfolio.php?success=Project Deleted"); exit;
    }
}
$projects = $pdo->query("SELECT * FROM portfolio ORDER BY id DESC")->fetchAll();
?>
<h2 class="fw-bold text-dark mb-4">Manage Projects</h2>
<div class="card shadow-sm border-0 mb-5" style="border-radius: 12px;">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-4 text-dark">Add Project</h6>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <div class="mb-3">
                <label class="form-label text-dark fw-bold small">Title *</label>
                <input type="text" name="title" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-dark fw-bold small">Description *</label>
                <textarea name="description" class="form-control bg-light border" style="border-radius: 8px; padding: 15px;" rows="4" required></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-dark fw-bold small">Tech Stack (comma separated)</label>
                    <input type="text" name="tech_stack" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-dark fw-bold small">Project Link</label>
                    <input type="text" name="link" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-dark fw-bold small">Client</label>
                    <input type="text" name="client" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-dark fw-bold small">Project Date</label>
                    <input type="text" name="project_date" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label text-dark fw-bold small">Main Project Image</label>
                <input type="file" name="main_image" class="form-control bg-light border" style="border-radius: 8px; padding: 8px 15px;" accept="image/*">
            </div>
            <div class="mb-3">
                <label class="form-label text-dark fw-bold small">Additional Project Images (Multiple)</label>
                <input type="file" name="additional_images[]" class="form-control bg-light border" style="border-radius: 8px; padding: 8px 15px;" accept="image/*" multiple>
                <small class="text-muted">JPG/PNG only. Select multiple images for carousel.</small>
            </div>
            <button type="submit" class="btn btn-dark fw-bold px-4 py-2" style="border-radius: 8px;">Add Project</button>
        </form>
    </div>
</div>
<div class="card overflow-hidden shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0 border-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4 border-0 py-3">Title</th>
                    <th class="border-0 py-3">Client</th>
                    <th class="text-end pe-4 border-0 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($projects as $p): ?>
                <tr>
                    <td class="ps-4 fw-bold py-3"><?= htmlspecialchars($p['title']) ?></td>
                    <td class="py-3"><?= htmlspecialchars($p['client'] ?? '') ?></td>
                    <td class="text-end pe-4 py-3">
                        <button class="btn btn-sm btn-light border text-primary rounded-circle me-1" style="width:35px;height:35px;" data-bs-toggle="modal" data-bs-target="#editModal<?= $p['id'] ?>"><i class="fa-solid fa-pen"></i></button>
                        <form method="POST" class="d-inline delete-form">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle" style="width:35px;height:35px;"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php foreach($projects as $p): ?>
<div class="modal fade" id="editModal<?= $p['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold small">Title *</label>
                        <input type="text" name="title" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($p['title']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold small">Description *</label>
                        <textarea name="description" class="form-control bg-light border" style="border-radius: 8px; padding: 15px;" rows="4" required><?= htmlspecialchars($p['description']) ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold small">Tech Stack</label>
                            <input type="text" name="tech_stack" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($p['tech_stack'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold small">Project Link</label>
                            <input type="text" name="link" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($p['link']) ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold small">Client</label>
                            <input type="text" name="client" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($p['client'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold small">Project Date</label>
                            <input type="text" name="project_date" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($p['project_date'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold small">Main Project Image (Leave empty to keep current)</label>
                        <input type="file" name="main_image" class="form-control bg-light border" style="border-radius: 8px; padding: 8px 15px;" accept="image/*">
                        <?php if(!empty($p['image_path'])): ?>
                            <div class="mt-2 p-2 border rounded bg-light d-inline-block">
                                <img src="<?= htmlspecialchars(get_asset_url($p['image_path'])) ?>" style="height: 40px;">
                                <span class="small ms-2 text-muted">Current Main</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold small">Additional Project Images (Multiple)</label>
                        <input type="file" name="additional_images[]" class="form-control bg-light border" style="border-radius: 8px; padding: 8px 15px;" accept="image/*" multiple>
                        <small class="text-muted d-block mt-1">Uploading new files will replace existing additional images.</small>
                        <?php 
                        $current_additional = json_decode($p['additional_images'], true);
                        if(!empty($current_additional) && is_array($current_additional)): 
                        ?>
                            <div class="mt-2 p-2 border rounded bg-light">
                                <span class="d-block small text-muted mb-2">Current Additional Images:</span>
                                <?php foreach($current_additional as $img): ?>
                                    <img src="<?= htmlspecialchars(get_asset_url($img)) ?>" class="me-2 rounded border" style="height: 40px; width: 40px; object-fit: cover;">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
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


