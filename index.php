<?php
session_start();
require_once 'config/database.php'; 
$database = new Database();
$pdo = $database->getConnection();

$page = $_GET['page'] ?? 'login';
$error = '';

// 1. Xử lý Đăng xuất
if ($page === 'logout') {
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}

// 2. Xử lý Đăng nhập
if ($page === 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM nguoidung WHERE TenDangNhap = ? AND TrangThai = 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password === $user['MatKhau']){
            $_SESSION['user'] = $user;

            
        // Tự động phân luồng theo VaiTro
        if (in_array($user['VaiTro'], ['QuanTriVien', 'ChuPhongKham'])) {
           header('Location: index.php?page=admin_dashboard');
        } elseif ($user['VaiTro'] === 'LeTan') {
           header('Location: index.php?page=benhnhan'); 
        } elseif ($user['VaiTro'] === 'BacSi') {
           header('Location: index.php?page=luotkham');
        } elseif ($user['VaiTro'] === 'ThuNgan') {
           header('Location: index.php?page=hoadon');
        } else {
           header('Location: index.php?page=login');
        }
        exit;

        } else {
            $error = 'Sai tên đăng nhập hoặc mật khẩu!';
        }
    }
    require_once 'views/login.php';
    exit;
}

// 3. Chặn truy cập nếu chưa đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit;
}

// 4. Điều hướng các trang Giao diện Nhân viên (User) và Quản trị (Admin)
switch ($page) {
    // --- GIAO DIỆN NHÂN VIÊN (USER) ---
    case 'benhnhan':
        require_once 'controllers/BenhNhanController.php';
        $controller = new BenhNhanController($pdo);
        $action = $_GET['action'] ?? 'index';
        if ($action === 'save') {
            $controller->save();
        } elseif ($action === 'delete') {
            $controller->delete();
        } else {
            $controller->index();
        }
        break;

    case 'luotkham':
        $pageTitle = 'Danh sách Lượt khám';
        require_once 'views/layouts/user_header.php';
        echo '<div class="alert alert-info">Chức năng Quản lý Lượt khám đang được hoàn thiện.</div>';
        require_once 'views/layouts/user_footer.php';
        break;

    case 'hoadon':
        $pageTitle = 'Quản lý Hóa đơn';
        require_once 'views/layouts/user_header.php';
        echo '<div class="alert alert-info">Chức năng Quản lý Hóa đơn đang được hoàn thiện.</div>';
        require_once 'views/layouts/user_footer.php';
        break;

    // --- GIAO DIỆN QUẢN TRỊ (ADMIN) ---
    case 'admin_dashboard':
        $pageTitle = 'Tổng quan Hệ thống';
        require_once 'views/layouts/admin_header.php';
        echo '<div class="card shadow-sm border-0 p-4"><h4>Chào mừng đến trang Quản trị Phòng khám</h4><p class="mb-0 text-muted">Chọn các danh mục bên thanh menu trái để quản lý.</p></div>';
        require_once 'views/layouts/admin_footer.php';
        break;

    case 'admin_nguoidung':
    case 'admin_phongkham':
    case 'admin_dichvu':
    case 'admin_thuoc':
        $pageTitle = 'Quản lý Danh mục';
        require_once 'views/layouts/admin_header.php';
        echo '<div class="alert alert-warning">Trang <strong>' . htmlspecialchars($page) . '</strong> đã kết nối layout Admin thành công.</div>';
        require_once 'views/layouts/admin_footer.php';
        break;

    default:
        header('Location: index.php?page=login');
        exit;
}