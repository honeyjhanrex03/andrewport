<?php
require_once 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id=?");
    $stmt->execute([$_POST['id']]);
    header("Location: messages.php?success=Message Deleted"); exit;
}

$messages = $pdo->query("SELECT * FROM messages ORDER BY id DESC")->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1 fw-bold text-dark">Inbox</h3>
        <p class="text-muted mb-0 small">Read and manage inquiries from your live site.</p>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 border-0">Sender</th>
                        <th class="border-0">Email</th>
                        <th class="border-0">Subject / Message</th>
                        <th class="text-end pe-4 border-0">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($messages as $m): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold" style="width:45px;height:45px;">
                                    <?= strtoupper(substr($m['name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark"><?= htmlspecialchars($m['name']) ?></h6>
                                    <small class="text-muted"><i class="fa-regular fa-clock me-1"></i><?= htmlspecialchars(date('M d, Y', strtotime($m['created_at']))) ?></small>
                                </div>
                            </div>
                        </td>
                        <td><a href="mailto:<?= htmlspecialchars($m['email']) ?>" class="text-decoration-none fw-500"><?= htmlspecialchars($m['email']) ?></a></td>
                        <td>
                            <small class="text-muted text-truncate d-inline-block" style="max-width:350px;"><?= htmlspecialchars($m['message']) ?></small>
                        </td>
                        <td class="text-end pe-4">
                            <form method="POST" class="d-inline delete-form">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle" style="width:35px;height:35px;"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($messages)): ?>
                    <tr><td colspan="4" class="text-center py-5 text-muted"><i class="fa-solid fa-inbox fs-1 mb-3 text-light"></i><br>Inbox is empty</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>