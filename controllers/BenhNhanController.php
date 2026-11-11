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
        require_once __DIR__ . '/../views/benhnhan/index.php';
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
                
                header("Location: index.php?controller=benhnhan&action=index");
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
        header("Location: index.php?controller=benhnhan&action=index");
        exit;
    }
}