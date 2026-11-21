<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Quản lý Phòng khám</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">

<div class="card shadow border-0" style="width: 100%; max-width: 400px;">
    <div class="card-header bg-primary text-white text-center py-3">
        <h4 class="mb-0"><i class="bi bi-hospital me-2"></i>PHÒNG KHÁM TƯ NHÂN</h4>
        <small>Hệ thống quản lý nội bộ</small>
    </div>
    <div class="card-body p-4">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=login">
            <div class="mb-3">
                <label class="form-label fw-bold">Tên đăng nhập</label>
                <input type="text" name="username" class="form-control" required autofocus placeholder="admin / letan / bacsi">
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Mật khẩu</label>
                <input type="password" name="password" class="form-control" required placeholder="Nhập mật khẩu">
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold">Đăng nhập</button>
        </form>
    </div>
</div>

</body>
</html>