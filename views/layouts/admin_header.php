<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Quản trị Phòng khám' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar { min-width: 240px; max-width: 240px; min-height: 100vh; }
        .sidebar .nav-link { color: #adb5bd; padding: 10px 16px; border-radius: 6px; margin-bottom: 4px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #0d6efd; }
    </style>
</head>
<body class="bg-light">

<div class="d-flex">
    <aside class="sidebar bg-dark text-white p-3 d-flex flex-column">
        <a href="index.php?page=admin_dashboard" class="text-white text-decoration-none fs-5 fw-bold mb-4 text-center border-bottom pb-3">
            <i class="bi bi-shield-plus me-2"></i>ADMIN CLINIC
        </a>
        <ul class="nav flex-column flex-grow-1">
            <li class="nav-item">
                <a href="index.php?page=admin_dashboard" class="nav-link"><i class="bi bi-speedometer2 me-2"></i>Tổng quan</a>
            </li>
            <li class="nav-item">
                <a href="index.php?page=admin_nguoidung" class="nav-link"><i class="bi bi-person-gear me-2"></i>Tài khoản & Phân quyền</a>
            </li>
        </ul>
        <div class="border-top pt-3">
            <a href="index.php?page=benhnhan" class="btn btn-outline-light btn-sm w-100 mb-2">
                <i class="bi bi-arrow-left-right me-1"></i>Sang giao diện NV
            </a>
            <a href="index.php?page=logout" class="btn btn-danger btn-sm w-100">
                <i class="bi bi-box-arrow-right me-1"></i>Đăng xuất
            </a>
        </div>
    </aside>

    <div class="flex-grow-1 d-flex flex-column">
        <header class="bg-white shadow-sm px-4 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold"><?= $pageTitle ?? 'Quản trị hệ thống' ?></h5>
            <span class="text-muted small">
                <i class="bi bi-person-circle me-1"></i>
                Xin chào, <strong><?= htmlspecialchars($_SESSION['user']['HoTen'] ?? 'Quản trị viên') ?></strong>
            </span>
        </header>

        <main class="p-4 flex-grow-1">