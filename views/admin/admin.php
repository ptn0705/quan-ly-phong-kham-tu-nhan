<?php
$pageTitle = "Quản lý Tài khoản & Phân quyền";
require_once __DIR__ . '/../layouts/admin_header.php';

$roles = [
    'QuanTriVien'  => 'Quản trị viên',
    'ChuPhongKham' => 'Chủ phòng khám',
    'LeTan'        => 'Lễ tân',
    'BacSi'        => 'Bác sĩ',
    'ThuNgan'      => 'Thu ngân'
];
?>

<div class="row g-4">
    <!-- Form Thêm / Sửa & Phân quyền -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white fw-bold">
                <?= $editND ? 'CẬP NHẬT / PHÂN QUYỀN (' . htmlspecialchars($editND['MaND']) . ')' : 'THÊM TÀI KHOẢN NHÂN VIÊN' ?>
            </div>
            <div class="card-body">
                <?php if (!empty($_SESSION['err_msg'])): ?>
                    <div class="alert alert-danger py-2 small"><?= htmlspecialchars($_SESSION['err_msg']); unset($_SESSION['err_msg']); ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=admin_nguoidung">
                    <?php if ($editND): ?>
                        <input type="hidden" name="is_update" value="1">
                        <input type="hidden" name="MaND" value="<?= htmlspecialchars($editND['MaND']) ?>">
                    <?php endif; ?>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Tên đăng nhập (*)</label>
                        <input type="text" name="TenDangNhap" class="form-control" required
                               <?= $editND ? 'readonly' : '' ?>
                               value="<?= htmlspecialchars($editND['TenDangNhap'] ?? '') ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">
                            Mật khẩu <?= $editND ? '(Để trống nếu không đổi)' : '(*)' ?>
                        </label>
                        <input type="text" name="MatKhau" class="form-control" <?= $editND ? '' : 'required value="123456"' ?>>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Họ và tên (*)</label>
                        <input type="text" name="HoTen" class="form-control" required
                               value="<?= htmlspecialchars($editND['HoTen'] ?? '') ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Vai trò truy cập (*)</label>
                        <select name="VaiTro" class="form-select" required>
                            <?php foreach ($roles as $key => $label): ?>
                                <option value="<?= $key ?>" <?= (($editND['VaiTro'] ?? '') === $key) ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Số điện thoại</label>
                        <input type="text" name="SoDienThoai" class="form-control"
                               value="<?= htmlspecialchars($editND['SoDienThoai'] ?? '') ?>">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <?= $editND ? 'Lưu thay đổi' : 'Tạo tài khoản' ?>
                        </button>
                        <?php if ($editND): ?>
                            <a href="index.php?page=admin_nguoidung" class="btn btn-secondary">Hủy</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Danh sách tài khoản -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold text-primary py-3">
                DANH SÁCH TÀI KHOẢN NHÂN VIÊN
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã ND</th>
                            <th>Tài khoản</th>
                            <th>Họ tên</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dsNguoiDung as $nd): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($nd['MaND']) ?></td>
                            <td><?= htmlspecialchars($nd['TenDangNhap']) ?></td>
                            <td><?= htmlspecialchars($nd['HoTen']) ?></td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    <?= $roles[$nd['VaiTro']] ?? htmlspecialchars($nd['VaiTro']) ?>
                                </span>
                            </td>
                            <td>
                                <?= $nd['TrangThai'] ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-danger">Đã khóa</span>' ?>
                            </td>
                            <td class="text-end">
                                <a href="index.php?page=admin_nguoidung&edit=<?= urlencode($nd['MaND']) ?>" class="btn btn-sm btn-warning">Sửa / Quyền</a>
                                <a href="index.php?page=admin_nguoidung&toggle=<?= urlencode($nd['MaND']) ?>" class="btn btn-sm btn-outline-secondary">
                                    <?= $nd['TrangThai'] ? 'Khóa' : 'Mở' ?>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>