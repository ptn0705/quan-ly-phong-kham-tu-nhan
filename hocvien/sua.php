<?php
require_once 'db.php';

$error = '';

// 1. Kiểm tra xem có truyền Mã học viên trên URL không
if (!isset($_GET['Mahv']) || empty($_GET['Mahv'])) {
    header("Location: index.php");
    exit;
}

$mahv = $_GET['Mahv'];

// 2. Lấy thông tin hiện tại của học viên
try {
    $stmt = $pdo->prepare("SELECT * FROM hocvien WHERE Mahv = ?");
    $stmt->execute([$mahv]);
    $hv = $stmt->fetch();

    if (!$hv) {
        die("Không tìm thấy học viên với mã này!");
    }
} catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
}

// 3. Xử lý khi người dùng nhấn nút Cập nhật
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tenhv = trim($_POST['tenhv']);
    $ngaysinh = !empty($_POST['ngaysinh']) ? $_POST['ngaysinh'] : null;
    $gioitinh = $_POST['gioitinh'];
    $sdt = trim($_POST['sdt']);

    try {
        $updateStmt = $pdo->prepare("UPDATE hocvien SET Tenhv = ?, Ngaysinh = ?, GioiTinh = ?, Sdt = ? WHERE Mahv = ?");
        $updateStmt->execute([$tenhv, $ngaysinh, $gioitinh, $sdt, $mahv]);
        
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = "Lỗi khi cập nhật dữ liệu: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Thông Tin Học Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0 text-center">SỬA THÔNG TIN HỌC VIÊN</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="mahv" class="form-label fw-bold">Mã học viên</label>
                                    <!-- Thêm thuộc tính readonly và class bg-light để chặn sửa đổi -->
                                    <input type="text" class="form-control bg-light" id="mahv" value="<?= htmlspecialchars($hv['Mahv']) ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="tenhv" class="form-label fw-bold">Họ và tên (*)</label>
                                    <input type="text" class="form-control" id="tenhv" name="tenhv" value="<?= htmlspecialchars($hv['Tenhv']) ?>" required>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="ngaysinh" class="form-label fw-bold">Ngày sinh</label>
                                    <input type="date" class="form-control" id="ngaysinh" name="ngaysinh" value="<?= !empty($hv['Ngaysinh']) ? $hv['Ngaysinh'] : '' ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="gioitinh" class="form-label fw-bold">Giới tính</label>
                                    <select class="form-select" id="gioitinh" name="gioitinh">
                                        <option value="Nam" <?= $hv['GioiTinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                                        <option value="Nữ" <?= $hv['GioiTinh'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="sdt" class="form-label fw-bold">Số điện thoại</label>
                                <input type="text" class="form-control" id="sdt" name="sdt" value="<?= htmlspecialchars($hv['Sdt']) ?>">
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-secondary px-4">Quay lại</a>
                                <button type="submit" class="btn btn-primary px-4">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>