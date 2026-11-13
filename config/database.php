<?php
class Database {
    public function getConnection() {
        $isLocal = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', 'localhost:8888', '127.0.0.1']);

        if ($isLocal) {
            $host     = "127.0.0.1";
            $db_name  = "ql_hocvien";
            $username = "root";
            $password = "";
        } else {
            $host     = "sql310.infinityfree.com";
            $db_name  = "if0_43274371_hoc_vien";
            $username = "if0_43274371";
            $password = "ptndb2005";
        }

        try {
            $dsn = "mysql:host=$host;dbname=$db_name;charset=utf8mb4";
            $conn = new PDO($dsn, $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $conn;
        } catch (PDOException $e) {
            die("Lỗi kết nối CSDL: " . $e->getMessage());
        }
    }
}