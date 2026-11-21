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
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=benhnhan"><i class="bi bi-people me-1"></i>Bệnh nhân</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=luotkham"><i class="bi bi-calendar2-check me-1"></i>Lượt khám</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=hoadon"><i class="bi bi-receipt me-1"></i>Hóa đơn</a>
                </li>
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