<?php
class BenhNhanModel{
    private $conn;

    public function __construct($db)
    {
       $this->conn = $db;
    }

    public function getAll($keyword = ''){
        if ($keyword !== ''){
            $sql = "select * from benhnhan where HoTenBN like :kw or SoDienThoai like :kw order by MaBN asc";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':kw' => "%$keyword%"]);
            return $stmt->fetchAll();
        }
        return $this->conn->query("select * from benhnhan order by MaBN asc")->fetchAll();
    }

    public function getById($maBN){
        $stmt = $this->conn->prepare("select * from benhnhan where MaBN = :maBN");
        $stmt->execute([':maBN' => $maBN]);
        return $stmt->fetch();
    }

    public function generateNewId(){
        $row = $this->conn->query("select MaBN from benhnhan order by MaBN desc limit 1")->fetch();
        if (!$row) 
            return 'BN001';
        $num = (int)substr($row['MaBN'], 2) + 1;
        return 'BN' . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    public function create($data){
    $sql = "INSERT INTO benhnhan (MaBN, HoTenBN, NgaySinh, GioiTinh, SoDienThoai, DiaChi, TienSuBenh)
            VALUES (:MaBN, :HoTenBN, :NgaySinh, :GioiTinh, :SoDienThoai, :DiaChi, :TienSuBenh)";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute($data);
    } 

    public function delete($maBN){
        $stmt = $this->conn->prepare("delete from benhnhan where MaBN = :maBN");
        return $stmt->execute([':maBN' =>$maBN]);
    }

    public function update($data){
        $sql = "update benhnhan set HoTenBN = :HoTenBN, NgaySinh = :NgaySinh, GioiTinh = :GioiTinh,
                SoDienThoai = :SoDienThoai, DiaChi = :DiaChi, TienSuBenh = :TienSuBenh
                WHERE MaBN = :MaBN";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
}