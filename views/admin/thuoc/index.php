<?php
$pageTitle = 'Quản lý Kho thuốc';
require_once __DIR__ . '/../../layouts/admin_header.php';

$today = date('Y-m-d');
$next30Days = date('Y-m-d', strtotime('+30 days'));
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
                <?= $editThuoc ? 'Cập nhật Thuốc (' . htmlspecialchars($editThuoc['MaThuoc']) . ')' : 'Thêm Thuốc mới' ?>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=admin_thuoc&action=save">
                    <?php if ($editThuoc): ?>
                        <input type="hidden" name="is_update" value="1">
                        <input type="hidden" name="MaThuoc" value="<?= htmlspecialchars($editThuoc['MaThuoc']) ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Tên thuốc <span class="text-danger">*</span></label>
                        <input type="text" name="TenThuoc" class="form-control" required
                               placeholder="VD: Paracetamol 500mg"
                               value="<?= htmlspecialchars($editThuoc['TenThuoc'] ?? '') ?>">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Đơn vị tính <span class="text-danger">*</span></label>
                            <input type="text" name="DonViTinh" class="form-control" required
                                   placeholder="Viên / Vỉ / Chai..."
                                   value="<?= htmlspecialchars($editThuoc['DonViTinh'] ?? 'Viên') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Đơn giá (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="DonGiaThuoc" class="form-control" min="0" step="500" required
                                   value="<?= isset($editThuoc['DonGiaThuoc']) ? (int)$editThuoc['DonGiaThuoc'] : 0 ?>">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Số lượng tồn <span class="text-danger">*</span></label>
                            <input type="number" name="SoLuongTon" class="form-control" min="0" required
                                   value="<?= isset($editThuoc['SoLuongTon']) ? (int)$editThuoc['SoLuongTon'] : 0 ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Hạn sử dụng <span class="text-danger">*</span></label>
                            <input type="date" name="HanSuDung" class="form-control" required
                                   value="<?= htmlspecialchars($editThuoc['HanSuDung'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cách dùng mặc định</label>
                        <textarea name="CachDung" class="form-control" rows="2"
                                  placeholder="VD: Uống sau ăn, theo chỉ định bác sĩ"><?= htmlspecialchars($editThuoc['CachDung'] ?? '') ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <?= $editThuoc ? 'Lưu thay đổi' : 'Thêm vào kho' ?>
                        </button>
                        <?php if ($editThuoc): ?>
                            <a href="index.php?page=admin_thuoc" class="btn btn-secondary">Hủy</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Danh sách Thuốc -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span class="fw-bold">Danh mục Kho thuốc (<?= count($dsThuoc) ?>)</span>
                <form method="GET" action="index.php" class="d-flex gap-2">
                    <input type="hidden" name="page" value="admin_thuoc">
                    <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Tìm mã / tên thuốc..."
                           value="<?= htmlspecialchars($keyword) ?>">
                    <button type="submit" class="btn btn-sm btn-outline-primary">Tìm</button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã</th>
                            <th>Tên thuốc</th>
                            <th>ĐVT</th>
                            <th class="text-end">Đơn giá</th>
                            <th class="text-center">Tồn kho</th>
                            <th>Hạn SD</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dsThuoc)): ?>
                            <tr><td colspan="7" class="text-center text-muted py-3">Chưa có thuốc nào trong kho.</td></tr>
                        <?php else: ?>
                            <?php foreach ($dsThuoc as $th): ?>
                                <?php
                                    $ton = (int)$th['SoLuongTon'];
                                    $tonBadge = $ton === 0 ? 'danger' : ($ton <= 20 ? 'warning text-dark' : 'success');

                                    $hsd = $th['HanSuDung'];
                                    $hsdClass = '';
                                    $hsdNote = '';
                                    if ($hsd < $today) {
                                        $hsdClass = 'text-danger fw-bold';
                                        $hsdNote = '<br><span class="badge bg-danger">Hết hạn</span>';
                                    } elseif ($hsd <= $next30Days) {
                                        $hsdClass = 'text-warning fw-bold';
                                        $hsdNote = '<br><span class="badge bg-warning text-dark">Sắp hết hạn</span>';
                                    }
                                ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($th['MaThuoc']) ?></td>
                                    <td>
                                        <div><?= htmlspecialchars($th['TenThuoc']) ?></div>
                                        <?php if (!empty($th['CachDung'])): ?>
                                            <small class="text-muted"><?= htmlspecialchars($th['CachDung']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($th['DonViTinh']) ?></td>
                                    <td class="text-end"><?= number_format($th['DonGiaThuoc'], 0, ',', '.') ?> đ</td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $tonBadge ?>"><?= $ton ?></span>
                                    </td>
                                    <td class="<?= $hsdClass ?>">
                                        <?= date('d/m/Y', strtotime($hsd)) ?><?= $hsdNote ?>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <a href="index.php?page=admin_thuoc&edit=<?= urlencode($th['MaThuoc']) ?>" class="btn btn-sm btn-outline-primary">Sửa</a>
                                        <a href="index.php?page=admin_thuoc&action=delete&id=<?= urlencode($th['MaThuoc']) ?>"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Xóa thuốc này khỏi kho?')">Xóa</a>
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