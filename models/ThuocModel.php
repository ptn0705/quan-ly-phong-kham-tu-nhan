<?php
class ThuocModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll($keyword = '') {
        $sql = "SELECT * FROM thuoc WHERE 1=1";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (MaThuoc LIKE :kw OR TenThuoc LIKE :kw)";
            $params[':kw'] = "%$keyword%";
        }

        $sql .= " ORDER BY CAST(SUBSTRING(MaThuoc, 3) AS UNSIGNED) ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($maThuoc) {
        $stmt = $this->conn->prepare("SELECT * FROM thuoc WHERE MaThuoc = ?");
        $stmt->execute([$maThuoc]);
        return $stmt->fetch();
    }

    public function generateNewId() {
        $stmt = $this->conn->query("SELECT COALESCE(MAX(CAST(SUBSTRING(MaThuoc, 3) AS UNSIGNED)), 0) + 1 FROM thuoc");
        $next = $stmt->fetchColumn();
        return 'TH' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    public function create($data) {
        $sql = "INSERT INTO thuoc (MaThuoc, TenThuoc, DonViTinh, DonGiaThuoc, SoLuongTon, HanSuDung, CachDung)
                VALUES (:MaThuoc, :TenThuoc, :DonViTinh, :DonGiaThuoc, :SoLuongTon, :HanSuDung, :CachDung)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($data) {
        $sql = "UPDATE thuoc
                SET TenThuoc = :TenThuoc, DonViTinh = :DonViTinh, DonGiaThuoc = :DonGiaThuoc,
                    SoLuongTon = :SoLuongTon, HanSuDung = :HanSuDung, CachDung = :CachDung
                WHERE MaThuoc = :MaThuoc";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($maThuoc) {
        $stmt = $this->conn->prepare("DELETE FROM thuoc WHERE MaThuoc = ?");
        return $stmt->execute([$maThuoc]);
    }
}