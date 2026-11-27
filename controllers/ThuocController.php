<?php
require_once __DIR__ . '/../models/ThuocModel.php';

class ThuocController {
    private $model;

    public function __construct($db) {
        $this->model = new ThuocModel($db);
    }

    public function index() {
        $keyword = trim($_GET['keyword'] ?? '');
        $dsThuoc = $this->model->getAll($keyword);
        $editThuoc = null;

        if (!empty($_GET['edit'])) {
            $editThuoc = $this->model->getById($_GET['edit']);
        }

        require_once __DIR__ . '/../views/admin/thuoc/index.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $isUpdate   = !empty($_POST['is_update']);
                $donGia     = (float) ($_POST['DonGiaThuoc'] ?? 0);
                $soLuongTon = (int) ($_POST['SoLuongTon'] ?? 0);

                if ($donGia < 0 || $soLuongTon < 0) {
                    $_SESSION['err_msg'] = "Đơn giá và số lượng tồn không được nhỏ hơn 0!";
                    header("Location: index.php?page=admin_thuoc");
                    exit;
                }

                $data = [
                    ':MaThuoc'     => $isUpdate ? trim($_POST['MaThuoc']) : $this->model->generateNewId(),
                    ':TenThuoc'    => trim($_POST['TenThuoc']),
                    ':DonViTinh'   => trim($_POST['DonViTinh']),
                    ':DonGiaThuoc' => $donGia,
                    ':SoLuongTon'  => $soLuongTon,
                    ':HanSuDung'   => $_POST['HanSuDung'],
                    ':CachDung'    => trim($_POST['CachDung'] ?? '')
                ];

                if ($isUpdate) {
                    $this->model->update($data);
                    $_SESSION['success_msg'] = "Cập nhật thông tin thuốc thành công!";
                } else {
                    $this->model->create($data);
                    $_SESSION['success_msg'] = "Thêm mới thuốc vào kho thành công!";
                }
            } catch (PDOException $e) {
                $_SESSION['err_msg'] = "Lỗi khi lưu thuốc: " . $e->getMessage();
            }
        }
        header("Location: index.php?page=admin_thuoc");
        exit;
    }

    public function delete() {
        $id = $_GET['id'] ?? '';
        if ($id !== '') {
            try {
                $this->model->delete($id);
                $_SESSION['success_msg'] = "Đã xóa thuốc khỏi kho!";
            } catch (PDOException $e) {
                $_SESSION['err_msg'] = "Không thể xóa thuốc đã từng được kê trong đơn thuốc!";
            }
        }
        header("Location: index.php?page=admin_thuoc");
        exit;
    }
}