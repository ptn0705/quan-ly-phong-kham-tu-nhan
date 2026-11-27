<?php
$pageTitle = 'Quản lý Phòng khám';
require_once __DIR__ . '/../../layouts/admin_header.php';
?>

<?php if (!empty($_SESSION['err_msg'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= htmlspecialchars($_SESSION['err_msg']); unset($_SESSION['err_msg']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['success_msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= htmlspecialchars($_SESSION['success_msg']); unset($_SESSION['success_msg']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Form Thêm / Sửa -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">
                <?= $editPK ? 'Cập nhật Phòng khám (' . htmlspecialchars($editPK['MaPK']) . ')' : 'Thêm Phòng khám mới' ?>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=admin_phongkham&action=save">
                    <?php if ($editPK): ?>
                        <input type="hidden" name="is_update" value="1">
                        <input type="hidden" name="MaPK" value="<?= htmlspecialchars($editPK['MaPK']) ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Tên phòng khám <span class="text-danger">*</span></label>
                        <input type="text" name="TenPK" class="form-control" required
                               placeholder="VD: Phòng khám Nhi, Phòng siêu âm..."
                               value="<?= htmlspecialchars($editPK['TenPK'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả / Vị trí</label>
                        <textarea name="MoTa" class="form-control" rows="3"
                                  placeholder="VD: Tầng 2 - Khu chuyên khoa"><?= htmlspecialchars($editPK['MoTa'] ?? '') ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <?= $editPK ? 'Lưu thay đổi' : 'Thêm phòng khám' ?>
                        </button>
                        <?php if ($editPK): ?>
                            <a href="index.php?page=admin_phongkham" class="btn btn-secondary">Hủy</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Danh sách Phòng khám -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span class="fw-bold">Danh sách Phòng khám (<?= count($dsPhongKham) ?>)</span>
                <form method="GET" action="index.php" class="d-flex gap-2">
                    <input type="hidden" name="page" value="admin_phongkham">
                    <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Tìm mã / tên phòng..."
                           value="<?= htmlspecialchars($keyword) ?>">
                    <button type="submit" class="btn btn-sm btn-outline-primary">Tìm</button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã</th>
                            <th>Tên phòng khám</th>
                            <th>Mô tả</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dsPhongKham)): ?>
                            <tr><td colspan="4" class="text-center text-muted py-3">Chưa có phòng khám nào.</td></tr>
                        <?php else: ?>
                            <?php foreach ($dsPhongKham as $pk): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($pk['MaPK']) ?></td>
                                    <td><?= htmlspecialchars($pk['TenPK']) ?></td>
                                    <td class="text-muted"><?= htmlspecialchars($pk['MoTa'] ?? '') ?></td>
                                    <td class="text-end text-nowrap">
                                        <a href="index.php?page=admin_phongkham&edit=<?= urlencode($pk['MaPK']) ?>" class="btn btn-sm btn-outline-primary">Sửa</a>
                                        <a href="index.php?page=admin_phongkham&action=delete&id=<?= urlencode($pk['MaPK']) ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Xóa phòng khám này?')">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/admin_footer.php'; ?>