<?php
$pageTitle = 'Hồ sơ & Lịch sử khám bệnh';
require_once __DIR__ . '/../../layouts/user_header.php';

$trangThaiLabels = [
    'ChoKham'      => ['Chờ khám', 'warning text-dark'],
    'ChoThanhToan' => ['Chờ thanh toán', 'info text-dark'],
    'HoanTat'      => ['Hoàn tất', 'success']
];
$backUrl = (($_SESSION['user']['VaiTro'] ?? '') === 'BacSi')
    ? 'index.php?page=luotkham'
    : 'index.php?page=benhnhan';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold text-primary">
        Hồ sơ bệnh án: <?= htmlspecialchars($benhNhan['HoTenBN']) ?> (<?= htmlspecialchars($benhNhan['MaBN']) ?>)
    </h5>
    <a href="<?= $backUrl ?>" class="btn btn-sm btn-outline-secondary">← Quay lại</a>
</div>

<!-- Thông tin hành chính & Tiền sử bệnh -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <small class="text-muted d-block">Ngày sinh / Giới tính</small>
                <strong>
                    <?= !empty($benhNhan['NgaySinh']) ? date('d/m/Y', strtotime($benhNhan['NgaySinh'])) : '---' ?>
                    (<?= htmlspecialchars($benhNhan['GioiTinh'] ?? '') ?>)
                </strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">Số điện thoại</small>
                <strong><?= htmlspecialchars($benhNhan['SoDienThoai'] ?? '---') ?></strong>
            </div>
            <div class="col-md-4">
                <small class="text-muted d-block">Địa chỉ</small>
                <strong><?= htmlspecialchars($benhNhan['DiaChi'] ?? '---') ?></strong>
            </div>
            <div class="col-md-2 text-md-end">
                <small class="text-muted d-block">Tổng số lần khám</small>
                <span class="badge bg-primary fs-6"><?= count($lichSuKham) ?> lần</span>
            </div>
            <div class="col-12 border-top pt-2">
                <small class="text-muted d-block">Tiền sử bệnh / Dị ứng:</small>
                <?php if (!empty($benhNhan['TienSuBenh'])): ?>
                    <span class="text-danger fw-semibold"><?= nl2br(htmlspecialchars($benhNhan['TienSuBenh'])) ?></span>
                <?php else: ?>
                    <span class="text-muted fst-italic">Chưa ghi nhận tiền sử bệnh đặc biệt.</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Danh sách các lượt khám (Tái khám nhiều lần) -->
<h6 class="fw-bold mb-3">Lịch sử các lượt khám (mới nhất trước)</h6>

<?php if (empty($lichSuKham)): ?>
    <div class="alert alert-light border text-center text-muted">
        Bệnh nhân này chưa có lượt khám nào trong hệ thống.
    </div>
<?php else: ?>
    <?php $tongLuot = count($lichSuKham); ?>
    <?php foreach ($lichSuKham as $idx => $lk): ?>
        <?php
            $lanThu = $tongLuot - $idx;
            $tt = $trangThaiLabels[$lk['TrangThaiLK']] ?? [$lk['TrangThaiLK'], 'secondary'];
        ?>
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <span class="badge bg-dark me-2">Lần <?= $lanThu ?></span>
                    <strong>Ngày <?= date('d/m/Y', strtotime($lk['NgayKham'])) ?></strong>
                    <span class="text-muted ms-2">
                        (Mã lượt: <?= htmlspecialchars($lk['MaLK']) ?> · STT: <?= (int)$lk['SoThuTu'] ?>)
                    </span>
                </div>
                <span class="badge bg-<?= $tt[1] ?>"><?= $tt[0] ?></span>
            </div>

            <div class="card-body">
                <div class="row g-2 mb-3 small">
                    <div class="col-md-4">
                        <span class="text-muted">Phòng khám:</span>
                        <strong><?= htmlspecialchars($lk['TenPK'] ?? '---') ?></strong>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted">Dịch vụ khám ban đầu:</span>
                        <strong><?= htmlspecialchars($lk['TenDV'] ?? '---') ?></strong>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted">Bác sĩ phụ trách:</span>
                        <strong><?= htmlspecialchars($lk['TenBacSi'] ?? 'Chưa khám') ?></strong>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="p-2 bg-light rounded">
                            <small class="text-muted d-block fw-semibold">Triệu chứng:</small>
                            <?= !empty($lk['TrieuChung']) ? nl2br(htmlspecialchars($lk['TrieuChung'])) : '<span class="text-muted fst-italic">Chưa ghi nhận</span>' ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-2 bg-light rounded">
                            <small class="text-muted d-block fw-semibold">Chẩn đoán của bác sĩ:</small>
                            <?= !empty($lk['ChanDoan']) ? nl2br(htmlspecialchars($lk['ChanDoan'])) : '<span class="text-muted fst-italic">Chưa có chẩn đoán</span>' ?>
                        </div>
                    </div>
                </div>

                <!-- Bảng Cận lâm sàng (nếu có) -->
                <?php if (!empty($lk['dsDichVu'])): ?>
                    <div class="mb-3">
                        <small class="fw-bold text-primary d-block mb-1">Chỉ định cận lâm sàng & Kết quả:</small>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Dịch vụ</th>
                                        <th class="text-center" style="width:70px;">SL</th>
                                        <th>Kết quả</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lk['dsDichVu'] as $dv): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($dv['TenDV']) ?></td>
                                            <td class="text-center"><?= (int)$dv['SoLuong'] ?></td>
                                            <td><?= !empty($dv['KetQua']) ? nl2br(htmlspecialchars($dv['KetQua'])) : '<span class="text-muted fst-italic">Chờ kết quả</span>' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Bảng Đơn thuốc (nếu có) -->
                <?php if (!empty($lk['dsThuoc'])): ?>
                    <div>
                        <small class="fw-bold text-success d-block mb-1">Đơn thuốc đã kê:</small>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tên thuốc</th>
                                        <th class="text-center" style="width:90px;">Số lượng</th>
                                        <th>Liều dùng / Cách dùng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lk['dsThuoc'] as $t): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($t['TenThuoc']) ?></td>
                                            <td class="text-center"><?= (int)$t['SoLuongKe'] ?> <?= htmlspecialchars($t['DonViTinh']) ?></td>
                                            <td><?= htmlspecialchars($t['LieuDung'] ?? '') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/../../layouts/user_footer.php'; ?>