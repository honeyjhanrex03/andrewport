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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $name = $_POST['name'];
    $level = $_POST['level'];
    $icon_class = $_POST['icon_class'];
    
    if ($_POST['action'] == 'add') {
        $uploaded = uploadFile('icon_image');
        $icon_type = $uploaded ? 'image' : 'class';
        $icon_val = $uploaded ? $uploaded : $icon_class;
        
        $stmt = $pdo->prepare("INSERT INTO skills (name, level, icon_type, icon_value) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $level, $icon_type, $icon_val]);
        header("Location: skills.php?success=Skill Added"); exit;
        
    } elseif ($_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $uploaded = uploadFile('icon_image');
        
        if ($uploaded) {
            $stmt = $pdo->prepare("UPDATE skills SET name=?, level=?, icon_type='image', icon_value=? WHERE id=?");
            $stmt->execute([$name, $level, $uploaded, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE skills SET name=?, level=?, icon_value=? WHERE id=?");
            $stmt->execute([$name, $level, $icon_class, $id]);
        }
        header("Location: skills.php?success=Skill Updated"); exit;
        
    } elseif ($_POST['action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM skills WHERE id=?");
        $stmt->execute([$_POST['id']]);
        header("Location: skills.php?success=Skill Deleted"); exit;
    }
}
$skills = $pdo->query("SELECT * FROM skills ORDER BY id DESC")->fetchAll();
?>
<h2 class="fw-bold text-dark mb-4">Manage Skills</h2>
<div class="card shadow-sm border-0 mb-5" style="border-radius: 12px;">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-4 text-dark">Add Skill</h6>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label text-dark fw-bold small">Skill Name *</label>
                    <input type="text" name="name" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label text-dark fw-bold small">Level (0-100) *</label>
                    <input type="number" name="level" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" required min="0" max="100" value="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label text-dark fw-bold small">Upload Icon Image (PNG/JPG/SVG)</label>
                    <input type="file" name="icon_image" class="form-control bg-light border" style="border-radius: 8px; padding: 8px 15px;">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-4">
                    <label class="form-label text-dark fw-bold small">OR Enter Icon Class/URL</label>
                    <input type="text" name="icon_class" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;">
                </div>
            </div>
            <button type="submit" class="btn btn-dark fw-bold px-4 py-2" style="border-radius: 8px;">Add Skill</button>
        </form>
    </div>
</div>
<div class="card overflow-hidden shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0 border-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4 border-0 py-3">Skill</th>
                    <th class="border-0 py-3">Level</th>
                    <th class="text-end pe-4 border-0 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($skills as $s): ?>
                <tr>
                    <td class="ps-4 fw-bold py-3"><?= htmlspecialchars($s['name']) ?></td>
                    <td class="py-3"><?= isset($s['level']) ? $s['level'] : 0 ?>%</td>
                    <td class="text-end pe-4 py-3">
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

<?php foreach($skills as $s): ?>
<div class="modal fade" id="editModal<?= $s['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Skill</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?= $s['id'] ?>">
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-dark fw-bold small">Skill Name *</label>
                            <input type="text" name="name" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($s['name']) ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-dark fw-bold small">Level (0-100) *</label>
                            <input type="number" name="level" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= isset($s['level']) ? $s['level'] : 0 ?>" required min="0" max="100">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-dark fw-bold small">Upload New Image</label>
                            <input type="file" name="icon_image" class="form-control bg-light border" style="border-radius: 8px; padding: 8px 15px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class="form-label text-dark fw-bold small">OR Enter Icon Class/URL</label>
                            <input type="text" name="icon_class" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= (isset($s['icon_type']) && $s['icon_type'] == 'class') ? htmlspecialchars($s['icon_value']) : '' ?>">
                        </div>
                    </div>
                    <?php if(!empty($s['icon_value'])): ?>
                    <div class="mb-4 p-3 bg-white rounded border text-center shadow-sm">
                        <span class="d-block small text-muted mb-2 fw-bold">CURRENT ICON / IMAGE</span>
                        <?php if(isset($s['icon_type']) && $s['icon_type'] == 'image'): ?>
                            <img src="<?= htmlspecialchars($s['icon_value']) ?>" style="height: 40px; max-width: 100%; object-fit: contain;">
                            <div class="small text-muted mt-2 text-break" style="font-size: 0.7rem;"><?= htmlspecialchars($s['icon_value']) ?></div>
                        <?php else: ?>
                            <i class="<?= htmlspecialchars($s['icon_value']) ?> fs-3 text-dark"></i>
                            <div class="small text-muted mt-2 fw-bold"><?= htmlspecialchars($s['icon_value']) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
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
