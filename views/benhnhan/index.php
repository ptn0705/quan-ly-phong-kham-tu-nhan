<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php
$editBN = $editBN ?? null;
$keyword = $keyword ?? '';
$dsBenhNhan = $dsBenhNhan ?? [];
?>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white fw-bold">
                <?= $editBN ? 'CẬP NHẬT BỆNH NHÂN (' . htmlspecialchars($editBN['MaBN']) . ')' : 'THÊM BỆNH NHÂN MỚI' ?>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?controller=benhnhan&action=save">
                    <?php if ($editBN): ?>
                        <input type="hidden" name="is_update" value="1">
                        <input type="hidden" name="MaBN" value="<?= htmlspecialchars($editBN['MaBN']) ?>">
                    <?php endif; ?>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Họ và tên (*)</label>
                        <input type="text" name="HoTenBN" class="form-control" required value="<?= htmlspecialchars($editBN['HoTenBN'] ?? '') ?>">
                    </div>
                    <div class="row mb-2">
                        <div class="col-7">
                            <label class="form-label small fw-semibold">Ngày sinh (*)</label>
                            <input type="date" name="NgaySinh" class="form-control" required value="<?= htmlspecialchars($editBN['NgaySinh'] ?? '') ?>">
                        </div>
                        <div class="col-5">
                            <label class="form-label small fw-semibold">Giới tính</label>
                            <select name="GioiTinh" class="form-select">
                                <?php foreach (['Nam', 'Nữ', 'Khác'] as $gt): ?>
                                    <option value="<?= $gt ?>" <?= (($editBN['GioiTinh'] ?? '') === $gt) ? 'selected' : '' ?>><?= $gt ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Số điện thoại (*)</label>
                        <input type="text" name="SoDienThoai" class="form-control" required value="<?= htmlspecialchars($editBN['SoDienThoai'] ?? '') ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Địa chỉ</label>
                        <input type="text" name="DiaChi" class="form-control" value="<?= htmlspecialchars($editBN['DiaChi'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tiền sử bệnh</label>
                        <textarea name="TienSuBenh" class="form-control" rows="2"><?= htmlspecialchars($editBN['TienSuBenh'] ?? '') ?></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success w-100 fw-bold"><?= $editBN ? 'Lưu cập nhật' : 'Thêm mới' ?></button>
                        <?php if ($editBN): ?>
                            <a href="index.php?controller=benhnhan&action=index" class="btn btn-secondary">Hủy</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="col-md-8">
        <?php if (!empty($_SESSION['err_msg'])): ?>
            <div class="alert alert-danger py-2"><?= htmlspecialchars($_SESSION['err_msg']); unset($_SESSION['err_msg']); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-bold text-success">DANH SÁCH BỆNH NHÂN (CRUD MVP)</h6>
                <form class="d-flex gap-2" method="GET" action="index.php">
                    <input type="hidden" name="controller" value="benhnhan">
                    <input type="hidden" name="action" value="index">
                    <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Tìm tên hoặc SĐT..." value="<?= htmlspecialchars($keyword) ?>">
                    <button class="btn btn-sm btn-outline-success" type="submit">Tìm</button>
                </form>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã BN</th>
                            <th>Họ tên</th>
                            <th>Ngày sinh</th>
                            <th>Giới tính</th>
                            <th>SĐT</th>
                            <th>Tiền sử bệnh</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dsBenhNhan as $bn): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($bn['MaBN']) ?></td>
                            <td><?= htmlspecialchars($bn['HoTenBN']) ?></td>
                            <td><?= htmlspecialchars($bn['NgaySinh']) ?></td>
                            <td><?= htmlspecialchars($bn['GioiTinh']) ?></td>
                            <td><?= htmlspecialchars($bn['SoDienThoai']) ?></td>
                            <td class="small text-muted"><?= htmlspecialchars($bn['TienSuBenh']) ?></td>
                            <td class="text-end">
                                <a href="index.php?controller=benhnhan&action=index&edit=<?= urlencode($bn['MaBN']) ?>" class="btn btn-sm btn-warning">Sửa</a>
                                <a href="index.php?controller=benhnhan&action=delete&id=<?= urlencode($bn['MaBN']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa bệnh nhân này?')">Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
