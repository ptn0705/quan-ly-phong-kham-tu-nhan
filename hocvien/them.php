<?php
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mahv = trim($_POST['mahv']);
    $tenhv = trim($_POST['tenhv']);
    $ngaysinh = !empty($_POST['ngaysinh']) ? $_POST['ngaysinh'] : null;
    $gioitinh = $_POST['gioitinh'];
    $sdt = trim($_POST['sdt']);

    try {
        $checkStmt = $pdo->prepare("SELECT Mahv FROM hocvien WHERE Mahv = ?");
        $checkStmt->execute([$mahv]);
        
        if ($checkStmt->rowCount() > 0) {
            $error = "Mã học viên <strong>'$mahv'</strong> đã tồn tại. Vui lòng chọn mã khác!";
        } else {
            $insertStmt = $pdo->prepare("INSERT INTO hocvien (Mahv, Tenhv, Ngaysinh, GioiTinh, Sdt) VALUES (?, ?, ?, ?, ?)");
            $insertStmt->execute([$mahv, $tenhv, $ngaysinh, $gioitinh, $sdt]);
            header("Location: index.php");
            exit;
        }
    } catch (PDOException $e) {
        $error = "Lỗi khi thêm dữ liệu: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Học viên Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white py-3">
                        <h4 class="mb-0 text-center">THÊM HỌC VIÊN MỚI</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="mahv" class="form-label fw-bold">Mã học viên (*)</label>
                                    <input type="text" class="form-control" id="mahv" name="mahv">
                                </div>
                                <div class="col-md-6">
                                    <label for="tenhv" class="form-label fw-bold">Họ và tên (*)</label>
                                    <input type="text" class="form-control" id="tenhv" name="tenhv" required placeholder="Nhập tên học viên">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="ngaysinh" class="form-label fw-bold">Ngày sinh</label>
                                    <input type="date" class="form-control" id="ngaysinh" name="ngaysinh">
                                </div>
                                <div class="col-md-6">
                                    <label for="gioitinh" class="form-label fw-bold">Giới tính</label>
                                    <select class="form-select" id="gioitinh" name="gioitinh">
                                        <option value="Nam">Nam</option>
                                        <option value="Nữ">Nữ</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="sdt" class="form-label fw-bold">Số điện thoại</label>
                                <input type="text" class="form-control" id="sdt" name="sdt" placeholder="Nhập số điện thoại">
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-secondary px-4">Quay lại</a>
                                <button type="submit" class="btn btn-success px-4">Lưu thông tin</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>