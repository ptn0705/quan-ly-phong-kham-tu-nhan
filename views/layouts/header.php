<?php
$user = $_SESSION['user'] ?? null;
$vaiTro = $user['VaiTro'] ?? '';
$tenVaiTro = [
    'LeTan'        => 'Lễ tân',
    'BacSi'        => 'Bác sĩ',
    'ThuNgan'      => 'Thu ngân',
    'ChuPhongKham' => 'Chủ phòng khám',
    'QuanTriVien'  => 'Quản trị viên'
][$vaiTro] ?? $vaiTro;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Phòng khám Tư Nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Phòng khám tư nhân Thái Nguyên</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?controller=home&action=index">Trang chủ</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="index.php?controller=benhnhan&action=index">Hồ sơ Bệnh nhân</a></li>
            </ul>
            <?php if ($user): ?>
            <div class="d-flex align-items-center text-white">
                <span class="me-3">Xin chào, <strong><?= htmlspecialchars($user['HoTen']) ?></strong> (<?= $tenVaiTro ?>)</span>
                <a href="index.php?controller=auth&action=logout" class="btn btn-outline-light btn-sm">Đăng xuất</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container">