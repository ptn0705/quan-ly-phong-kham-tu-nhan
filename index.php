<?php
session_start();
require_once __DIR__ . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

// Mặc định vào thẳng controller 'benhnhan' để test và chấm điểm CRUD MVP
$controller = $_GET['controller'] ?? 'benhnhan';
$action     = $_GET['action'] ?? 'index';

switch ($controller) {
    case 'auth':
        require_once __DIR__ . '/controllers/AuthController.php';
        $ctrl = new AuthController($db);
        break;
    case 'home':
        require_once __DIR__ . '/controllers/HomeController.php';
        $ctrl = new HomeController($db);
        break;
    case 'benhnhan':
    default:
        require_once __DIR__ . '/controllers/BenhNhanController.php';
        $ctrl = new BenhNhanController($db);
        break;
}

if (method_exists($ctrl, $action)) {
    $ctrl->$action();
} else {
    die("Không tìm thấy chức năng yêu cầu!");
}