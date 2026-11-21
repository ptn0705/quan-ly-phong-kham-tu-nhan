<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Hệ thống Nhân sự - Phòng khám' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?page=benhnhan">
            <i class="bi bi-hospital me-2"></i>Phòng khám tư nhân
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#staffNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="staffNavbar">
            <?php $role = $_SESSION['user']['VaiTro'] ?? ''; ?>
            <ul class="navbar-nav me-auto">
                <?php if ($role === 'LeTan'): ?>
                  <li class="nav-item">
                    <a class="nav-link" href="index.php?page=benhnhan">Bệnh nhân</a>
                  </li>
                <?php endif; ?>

                <?php if (in_array($role, ['LeTan', 'BacSi'])): ?>
                   <li class="nav-item">
                     <a class="nav-link" href="index.php?page=luotkham">Lượt khám</a>
                  </li>
                <?php endif; ?>

                <?php if ($role === 'ThuNgan'): ?>
                  <li class="nav-item">
                    <a class="nav-link" href="index.php?page=hoadon">Hóa đơn</a>
                  </li>
                <?php endif; ?>
            </ul>
            <div class="d-flex align-items-center text-white gap-3">
                <span class="small">
                    <i class="bi bi-person-badge me-1"></i>
                    <?= htmlspecialchars($_SESSION['user']['HoTen'] ?? 'Nhân viên') ?>
                </span>
                <a href="index.php?page=logout" class="btn btn-outline-light btn-sm">Đăng xuất</a>
            </div>
        </div>
    </div>
</nav>

<main class="container my-4 flex-grow-1">