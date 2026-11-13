<?php
// Nhận diện Localhost bất chấp port (ví dụ localhost:3000)
$host_server = $_SERVER['HTTP_HOST'] ?? '';
$isLocal = (strpos($host_server, 'localhost') !== false || strpos($host_server, '127.0.0.1') !== false);

if ($isLocal) {
    // Cấu hình Local (Laragon)
    $host = "127.0.0.1";
    $dbname = "ql_hocvien"; 
    $username = "root";
    $password = "";
} else {
    // Cấu hình InfinityFree
    $host = "sql310.infinityfree.com";
    $dbname = "if0_43274371_hoc_vien";
    $username = "if0_43274371";
    $password = "ptndb2005";
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>