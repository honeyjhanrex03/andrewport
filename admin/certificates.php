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
    $title = $_POST['title'];
    $issued = $_POST['issued_by'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $keywords = $_POST['keywords'];
    $desc = $_POST['description'];
    
    if ($_POST['action'] == 'add') {
        $img = uploadFile('image');
        $stmt = $pdo->prepare("INSERT INTO certificates (title, issued_by, month, year, image_path, keywords, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $issued, $month, $year, $img ? $img : '', $keywords, $desc]);
        header("Location: certificates.php?success=Certificate Added"); exit;
        
    } elseif ($_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $img = uploadFile('image');
        
        if ($img) {
            $stmt = $pdo->prepare("UPDATE certificates SET title=?, issued_by=?, month=?, year=?, keywords=?, description=?, image_path=? WHERE id=?");
            $stmt->execute([$title, $issued, $month, $year, $keywords, $desc, $img, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE certificates SET title=?, issued_by=?, month=?, year=?, keywords=?, description=? WHERE id=?");
            $stmt->execute([$title, $issued, $month, $year, $keywords, $desc, $id]);
        }
        header("Location: certificates.php?success=Certificate Updated"); exit;
        
    } elseif ($_POST['action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM certificates WHERE id=?");
        $stmt->execute([$_POST['id']]);
        header("Location: certificates.php?success=Certificate Deleted"); exit;
    }
}
$certs = $pdo->query("SELECT * FROM certificates ORDER BY id DESC")->fetchAll();
?>
<h2 class="fw-bold text-dark mb-4">Manage Certificates</h2>
<div class="card shadow-sm border-0 mb-5" style="border-radius: 12px;">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-4 text-dark">Add Certificate</h6>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <div class="mb-3">
                <label class="form-label text-dark fw-bold small">Title *</label>
                <input type="text" name="title" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-dark fw-bold small">Issued By</label>
                    <input type="text" name="issued_by" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label text-dark fw-bold small">Month</label>
                    <select name="month" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;">
                        <option value="">Select Month</option>
                        <option value="January">January</option>
                        <option value="February">February</option>
                        <option value="March">March</option>
                        <!-- add others as needed -->
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label text-dark fw-bold small">Year</label>
                    <input type="number" name="year" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= date('Y') ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-dark fw-bold small">Certificate Image</label>
                    <input type="file" name="image" class="form-control bg-light border" style="border-radius: 8px; padding: 8px 15px;" accept="image/*">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-dark fw-bold small">Keywords (comma-separated)</label>
                    <input type="text" name="keywords" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" placeholder="e.g., .NET Framework, C# Programming">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label text-dark fw-bold small">Description</label>
                <textarea name="description" class="form-control bg-light border" style="border-radius: 8px; padding: 15px;" rows="4"></textarea>
            </div>
            <button type="submit" class="btn btn-dark fw-bold px-4 py-2" style="border-radius: 8px;">Add Certificate</button>
        </form>
    </div>
</div>
<div class="card overflow-hidden shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0 border-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4 border-0 py-3">Title</th>
                    <th class="border-0 py-3">Issued By</th>
                    <th class="text-end pe-4 border-0 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($certs as $c): ?>
                <tr>
                    <td class="ps-4 fw-bold py-3"><?= htmlspecialchars($c['title']) ?></td>
                    <td class="py-3"><?= htmlspecialchars($c['issued_by']) ?></td>
                    <td class="text-end pe-4 py-3">
                        <button class="btn btn-sm btn-light border text-primary rounded-circle me-1" style="width:35px;height:35px;" data-bs-toggle="modal" data-bs-target="#editModal<?= $c['id'] ?>"><i class="fa-solid fa-pen"></i></button>
                        <form method="POST" class="d-inline delete-form">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle" style="width:35px;height:35px;"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php foreach($certs as $c): ?>
<div class="modal fade" id="editModal<?= $c['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Certificate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold small">Title *</label>
                        <input type="text" name="title" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($c['title']) ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold small">Issued By</label>
                            <input type="text" name="issued_by" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($c['issued_by']) ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-dark fw-bold small">Month</label>
                            <input type="text" name="month" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($c['month'] ?? '') ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-dark fw-bold small">Year</label>
                            <input type="number" name="year" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($c['year'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold small">Certificate Image (Leave empty to keep)</label>
                            <input type="file" name="image" class="form-control bg-light border" style="border-radius: 8px; padding: 8px 15px;" accept="image/*">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold small">Keywords (comma-separated)</label>
                            <input type="text" name="keywords" class="form-control bg-light border" style="border-radius: 8px; padding: 10px 15px;" value="<?= htmlspecialchars($c['keywords'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-dark fw-bold small">Description</label>
                        <textarea name="description" class="form-control bg-light border" style="border-radius: 8px; padding: 15px;" rows="4"><?= htmlspecialchars($c['description'] ?? '') ?></textarea>
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

