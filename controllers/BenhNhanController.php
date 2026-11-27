<?php
require_once __DIR__ . '/../models/BenhNhanModel.php';

class BenhNhanController {
    private $model;

    public function __construct($db) {
        $this->model = new BenhNhanModel($db);
    }

    public function index() {
        $keyword = trim($_GET['keyword'] ?? '');
        $dsBenhNhan = $this->model->getAll($keyword);
        $editBN = null;

        if (!empty($_GET['edit'])) {
            $editBN = $this->model->getById($_GET['edit']);
        }
        require_once __DIR__ . '/../views/user/benhnhan/index.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $isUpdate = !empty($_POST['is_update']);
                $data = [
                    ':MaBN'        => $isUpdate ? trim($_POST['MaBN']) : $this->model->generateNewId(),
                    ':HoTenBN'     => trim($_POST['HoTenBN']),
                    ':NgaySinh'    => $_POST['NgaySinh'],
                    ':GioiTinh'    => $_POST['GioiTinh'],
                    ':SoDienThoai' => trim($_POST['SoDienThoai']),
                    ':DiaChi'      => trim($_POST['DiaChi']),
                    ':TienSuBenh'  => trim($_POST['TienSuBenh'])
                ];

                if ($isUpdate) {
                    $this->model->update($data);
                } else {
                    $this->model->create($data);
                }
                
                header("Location: index.php?page=benhnhan");
                exit;
            } catch (Exception $e) {
                die("Lỗi khi lưu bệnh nhân: " . $e->getMessage());
            }
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? '';
        if ($id !== '') {
            try {
                $this->model->delete($id);
            } catch (PDOException $e) {
                $_SESSION['err_msg'] = "Không thể xóa bệnh nhân đã có lịch sử lượt khám!";
            }
        }
        header("Location: index.php?page=benhnhan");
        exit;
    }
    public function lichSuKhamBenh() {
        $maBN = trim($_GET['id'] ?? '');
        if ($maBN === '') {
            header("Location: index.php?page=benhnhan");
            exit;
        }

        $benhNhan = $this->model->getById($maBN);
        if (!$benhNhan) {
            $_SESSION['err_msg'] = "Không tìm thấy hồ sơ bệnh nhân!";
            header("Location: index.php?page=benhnhan");
            exit;
        }

        $lichSuKham = $this->model->getLichSuKham($maBN);

        // Gắn kèm chỉ định cận lâm sàng và đơn thuốc cho từng lượt khám
        foreach ($lichSuKham as &$lk) {
            $lk['dsDichVu']  = $this->model->getDichVuTheoLuotKham($lk['MaLK']);
            $lk['dsThuoc']   = $this->model->getDonThuocTheoLuotKham($lk['MaLK']);
        }
        unset($lk);

        require_once __DIR__ . '/../views/user/benhnhan/lichsukham.php';
    }

}