<?php
class DichVuModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll($keyword = '', $loaiDV = '') {
        $sql = "SELECT * FROM dichvu WHERE 1=1";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (MaDV LIKE :kw OR TenDV LIKE :kw)";
            $params[':kw'] = "%$keyword%";
        }

        if ($loaiDV !== '') {
            $sql .= " AND LoaiDV = :loai";
            $params[':loai'] = $loaiDV;
        }

        $sql .= " ORDER BY LoaiDV ASC, MaDV ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($maDV) {
        $stmt = $this->conn->prepare("SELECT * FROM dichvu WHERE MaDV = ?");
        $stmt->execute([$maDV]);
        return $stmt->fetch();
    }

    public function generateNewId() {
        $stmt = $this->conn->query("SELECT COALESCE(MAX(CAST(SUBSTRING(MaDV, 3) AS UNSIGNED)), 0) + 1 FROM dichvu");
        $next = $stmt->fetchColumn();
        return 'DV' . str_pad($next, 2, '0', STR_PAD_LEFT);
    }

    public function create($data) {
        $sql = "INSERT INTO dichvu (MaDV, TenDV, LoaiDV, DonGiaDV, TrangThai)
                VALUES (:MaDV, :TenDV, :LoaiDV, :DonGiaDV, 1)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($data) {
        $sql = "UPDATE dichvu
                SET TenDV = :TenDV, LoaiDV = :LoaiDV, DonGiaDV = :DonGiaDV
                WHERE MaDV = :MaDV";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function toggleStatus($maDV) {
        $stmt = $this->conn->prepare("UPDATE dichvu SET TrangThai = 1 - TrangThai WHERE MaDV = ?");
        return $stmt->execute([$maDV]);
    }

    public function delete($maDV) {
        $stmt = $this->conn->prepare("DELETE FROM dichvu WHERE MaDV = ?");
        return $stmt->execute([$maDV]);
    }
}