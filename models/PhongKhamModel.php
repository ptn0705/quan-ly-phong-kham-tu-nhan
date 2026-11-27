<?php
class PhongKhamModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll($keyword = '') {
        $sql = "SELECT * FROM phongkham WHERE 1=1";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND (MaPK LIKE :kw OR TenPK LIKE :kw OR MoTa LIKE :kw)";
            $params[':kw'] = "%$keyword%";
        }

        $sql .= " ORDER BY CAST(SUBSTRING(MaPK, 3) AS UNSIGNED) ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($maPK) {
        $stmt = $this->conn->prepare("SELECT * FROM phongkham WHERE MaPK = ?");
        $stmt->execute([$maPK]);
        return $stmt->fetch();
    }

    public function generateNewId() {
        $stmt = $this->conn->query("SELECT COALESCE(MAX(CAST(SUBSTRING(MaPK, 3) AS UNSIGNED)), 0) + 1 FROM phongkham");
        $next = $stmt->fetchColumn();
        return 'PK' . str_pad($next, 2, '0', STR_PAD_LEFT);
    }

    public function create($data) {
        $sql = "INSERT INTO phongkham (MaPK, TenPK, MoTa) VALUES (:MaPK, :TenPK, :MoTa)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($data) {
        $sql = "UPDATE phongkham SET TenPK = :TenPK, MoTa = :MoTa WHERE MaPK = :MaPK";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($maPK) {
        $stmt = $this->conn->prepare("DELETE FROM phongkham WHERE MaPK = ?");
        return $stmt->execute([$maPK]);
    }
}