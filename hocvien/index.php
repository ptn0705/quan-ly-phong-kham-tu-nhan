<?php
require_once 'db.php';

// Xử lý Xóa học viên
if (isset($_GET['delete'])) {
    $mahv = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM hocvien WHERE Mahv = ?");
        $stmt->execute([$mahv]);
        // Xóa xong tải lại trang để cập nhật danh sách
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Lỗi khi xóa: " . $e->getMessage() . "');</script>";
    }
}

// Truy vấn lấy toàn bộ danh sách học viên
$stmt = $pdo->query("SELECT * FROM hocvien ORDER BY Mahv ASC");
$hocviens = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Học viên</title>
    <!-- Tích hợp Bootstrap 5 CDN để làm đẹp giao diện -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary">Danh sách Học viên</h2>
            <a href="add.php" class="btn btn-success">Thêm học viên mới</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th style="width: 15%;">Mã học viên</th>
                        <th style="width: 25%;">Tên học viên</th>
                        <th style="width: 15%;">Ngày sinh</th>
                        <th style="width: 15%;">Giới tính</th>
                        <th style="width: 15%;">Số điện thoại</th>
                        <th style="width: 15%;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($hocviens) > 0): ?>
                        <?php foreach ($hocviens as $hv): ?>
                        <tr>
                            <td class="text-center fw-bold"><?= htmlspecialchars($hv['Mahv']) ?></td>
                            <td><?= htmlspecialchars($hv['Tenhv']) ?></td>
                            <td class="text-center"><?= !empty($hv['Ngaysinh']) ? date('d/m/Y', strtotime($hv['Ngaysinh'])) : '' ?></td>
                            <td class="text-center"><?= htmlspecialchars($hv['GioiTinh']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($hv['Sdt']) ?></td>
                            <td class="text-center">
                                <a href="edit.php?Mahv=<?= urlencode($hv['Mahv']) ?>" class="btn btn-primary btn-sm">Sửa</a>
                                <a href="?delete=<?= urlencode($hv['Mahv']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa học viên [<?= htmlspecialchars($hv['Mahv']) ?>] không?')">Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Chưa có dữ liệu học viên nào. Vui lòng thêm mới!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>