<?php
$pageTitle = 'Quản lý Dịch vụ';
require_once __DIR__ . '/../../layouts/admin_header.php';

$loaiLabels = [
    'KhamBenh'        => ['Khám bệnh', 'primary'],
    'XetNghiem'       => ['Xét nghiệm', 'info'],
    'ChanDoanHinhAnh' => ['Chẩn đoán hình ảnh', 'warning']
];
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
                <?= $editDV ? 'Cập nhật Dịch vụ (' . htmlspecialchars($editDV['MaDV']) . ')' : 'Thêm Dịch vụ mới' ?>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=admin_dichvu&action=save">
                    <?php if ($editDV): ?>
                        <input type="hidden" name="is_update" value="1">
                        <input type="hidden" name="MaDV" value="<?= htmlspecialchars($editDV['MaDV']) ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Tên dịch vụ <span class="text-danger">*</span></label>
                        <input type="text" name="TenDV" class="form-control" required
                               placeholder="VD: Chụp X-quang phổi, Xét nghiệm máu..."
                               value="<?= htmlspecialchars($editDV['TenDV'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Loại dịch vụ <span class="text-danger">*</span></label>
                        <select name="LoaiDV" class="form-select" required>
                            <?php $curLoai = $editDV['LoaiDV'] ?? 'KhamBenh'; ?>
                            <option value="KhamBenh" <?= $curLoai === 'KhamBenh' ? 'selected' : '' ?>>Khám bệnh (Lễ tân chọn)</option>
                            <option value="XetNghiem" <?= $curLoai === 'XetNghiem' ? 'selected' : '' ?>>Xét nghiệm (Bác sĩ chỉ định)</option>
                            <option value="ChanDoanHinhAnh" <?= $curLoai === 'ChanDoanHinhAnh' ? 'selected' : '' ?>>Chẩn đoán hình ảnh (X-quang, Siêu âm...)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Đơn giá (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="DonGiaDV" class="form-control" min="0" step="1000" required
                               value="<?= isset($editDV['DonGiaDV']) ? (int)$editDV['DonGiaDV'] : 0 ?>">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <?= $editDV ? 'Lưu thay đổi' : 'Thêm dịch vụ' ?>
                        </button>
                        <?php if ($editDV): ?>
                            <a href="index.php?page=admin_dichvu" class="btn btn-secondary">Hủy</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Danh sách Dịch vụ -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span class="fw-bold">Danh mục Dịch vụ (<?= count($dsDichVu) ?>)</span>
                <form method="GET" action="index.php" class="d-flex gap-2">
                    <input type="hidden" name="page" value="admin_dichvu">
                    <select name="loai" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
                        <option value="">-- Tất cả loại --</option>
                        <option value="KhamBenh" <?= ($loaiDV === 'KhamBenh') ? 'selected' : '' ?>>Khám bệnh</option>
                        <option value="XetNghiem" <?= ($loaiDV === 'XetNghiem') ? 'selected' : '' ?>>Xét nghiệm</option>
                        <option value="ChanDoanHinhAnh" <?= ($loaiDV === 'ChanDoanHinhAnh') ? 'selected' : '' ?>>Chẩn đoán hình ảnh</option>
                    </select>
                    <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Tìm mã / tên..."
                           value="<?= htmlspecialchars($keyword) ?>">
                    <button type="submit" class="btn btn-sm btn-outline-primary">Tìm</button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã</th>
                            <th>Tên dịch vụ</th>
                            <th>Loại</th>
                            <th class="text-end">Đơn giá</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dsDichVu)): ?>
                            <tr><td colspan="6" class="text-center text-muted py-3">Chưa có dịch vụ nào.</td></tr>
                        <?php else: ?>
                            <?php foreach ($dsDichVu as $dv): ?>
                                <?php $badge = $loaiLabels[$dv['LoaiDV']] ?? [$dv['LoaiDV'], 'secondary']; ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($dv['MaDV']) ?></td>
                                    <td><?= htmlspecialchars($dv['TenDV']) ?></td>
                                    <td><span class="badge bg-<?= $badge[1] ?>"><?= $badge[0] ?></span></td>
                                    <td class="text-end"><?= number_format($dv['DonGiaDV'], 0, ',', '.') ?> đ</td>
                                    <td class="text-center">
                                        <?php if ($dv['TrangThai']): ?>
                                            <span class="badge bg-success">Đang dùng</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Ngừng</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <a href="index.php?page=admin_dichvu&edit=<?= urlencode($dv['MaDV']) ?>" class="btn btn-sm btn-outline-primary">Sửa</a>
                                        <a href="index.php?page=admin_dichvu&action=toggle&id=<?= urlencode($dv['MaDV']) ?>" class="btn btn-sm btn-outline-warning">
                                            <?= $dv['TrangThai'] ? 'Ẩn' : 'Mở' ?>
                                        </a>
                                        <a href="index.php?page=admin_dichvu&action=delete&id=<?= urlencode($dv['MaDV']) ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Xóa dịch vụ này?')">Xóa</a>
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