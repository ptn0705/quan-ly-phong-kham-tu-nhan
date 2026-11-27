<?php
session_start();
require_once 'config/database.php'; 
$database = new Database();
$pdo = $database->getConnection();

$page = $_GET['page'] ?? 'login';
$error = '';

if ($page === 'logout') {
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}


if ($page === 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM nguoidung WHERE TenDangNhap = ? AND TrangThai = 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password === $user['MatKhau']) {
            $_SESSION['user'] = $user;

            // Tự động phân luồng theo VaiTro
            if (in_array($user['VaiTro'], ['QuanTriVien'])) {
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


if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit;
}


$role = $_SESSION['user']['VaiTro'] ?? '';
$permissions = [
    'benhnhan'        => ['LeTan'],
    'luotkham'        => ['LeTan', 'BacSi'],
    'hoadon'          => ['ThuNgan'],
    'admin_dashboard' => ['QuanTriVien'],
    'admin_nguoidung' => ['QuanTriVien'],
];

if (isset($permissions[$page]) && !in_array($role, $permissions[$page])) {
    http_response_code(403);
    die('<div style="font-family:sans-serif;text-align:center;margin-top:50px;">
            <h3 style="color:red;">403 - Bạn không có quyền truy cập trang này!</h3>
            <a href="javascript:history.back()">Quay lại trang trước</a>
         </div>');
}


switch ($page) {
    case 'admin_dichvu':
        require_once 'controllers/DichVuController.php';
        $controller = new DichVuController($pdo);
        $action = $_GET['action'] ?? 'index';
        if ($action === 'save') {
            $controller->save();
        } elseif ($action === 'toggle') {
            $controller->toggle();
        } elseif ($action === 'delete') {
            $controller->delete();
        } else {
            $controller->index();
        }
         break;

         case 'admin_phongkham':
        require_once 'controllers/PhongKhamController.php';
        $controller = new PhongKhamController($pdo);
        $action = $_GET['action'] ?? 'index';
        if ($action === 'save') {
            $controller->save();
        } elseif ($action === 'delete') {
            $controller->delete();
        } else {
            $controller->index();
        }
        break;

    case 'admin_thuoc':
        require_once 'controllers/ThuocController.php';
        $controller = new ThuocController($pdo);
        $action = $_GET['action'] ?? 'index';
        if ($action === 'save') {
            $controller->save();
        } elseif ($action === 'delete') {
            $controller->delete();
        } else {
            $controller->index();
        }
        break;

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
        // Xử lý Thêm mới / Cập nhật & Phân quyền
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isUpdate = !empty($_POST['is_update']);
            $hoTen = trim($_POST['HoTen']);
            $vaiTro = $_POST['VaiTro'];
            $sdt = trim($_POST['SoDienThoai']);
            $matKhau = trim($_POST['MatKhau'] ?? '');

            try {
                if ($isUpdate) {
                    $maND = trim($_POST['MaND']);
                    if ($matKhau !== '') {
                        $stmt = $pdo->prepare("UPDATE nguoidung SET MatKhau = ?, HoTen = ?, VaiTro = ?, SoDienThoai = ? WHERE MaND = ?");
                        $stmt->execute([$matKhau, $hoTen, $vaiTro, $sdt, $maND]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE nguoidung SET HoTen = ?, VaiTro = ?, SoDienThoai = ? WHERE MaND = ?");
                        $stmt->execute([$hoTen, $vaiTro, $sdt, $maND]);
                    }
                } else {
                    $tenDN = trim($_POST['TenDangNhap']);
                    $stmtMax = $pdo->query("SELECT COALESCE(MAX(CAST(SUBSTRING(MaND, 3) AS UNSIGNED)), 0) + 1 FROM nguoidung");
                    $maND = 'ND' . str_pad($stmtMax->fetchColumn(), 3, '0', STR_PAD_LEFT);

                    $stmt = $pdo->prepare("INSERT INTO nguoidung (MaND, TenDangNhap, MatKhau, HoTen, VaiTro, SoDienThoai, TrangThai) VALUES (?, ?, ?, ?, ?, ?, 1)");
                    $stmt->execute([$maND, $tenDN, $matKhau ?: '123456', $hoTen, $vaiTro, $sdt]);
                }
            } catch (PDOException $e) {
                $_SESSION['err_msg'] = "Lỗi: Tên đăng nhập đã tồn tại hoặc dữ liệu không hợp lệ!";
            }

            header("Location: index.php?page=admin_nguoidung");
            exit;
        }

        // Xử lý Khóa / Mở khóa tài khoản
        if (!empty($_GET['toggle'])) {
            $stmt = $pdo->prepare("UPDATE nguoidung SET TrangThai = 1 - TrangThai WHERE MaND = ?");
            $stmt->execute([$_GET['toggle']]);
            header("Location: index.php?page=admin_nguoidung");
            exit;
        }

        // Lấy thông tin tài khoản đang chọn sửa (nếu có)
        $editND = null;
        if (!empty($_GET['edit'])) {
            $stmtEdit = $pdo->prepare("SELECT * FROM nguoidung WHERE MaND = ?");
            $stmtEdit->execute([$_GET['edit']]);
            $editND = $stmtEdit->fetch();
        }

        $dsNguoiDung = $pdo->query("SELECT * FROM nguoidung ORDER BY MaND ASC")->fetchAll();
        require_once 'views/admin/admin.php';
        break;

    default:
        header('Location: index.php?page=login');
        exit;
}