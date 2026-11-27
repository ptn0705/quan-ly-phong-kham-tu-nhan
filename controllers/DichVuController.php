<?php
require_once __DIR__ . '/../models/DichVuModel.php';

class DichVuController {
    private $model;

    public function __construct($db) {
        $this->model = new DichVuModel($db);
    }

    public function index() {
        $keyword = trim($_GET['keyword'] ?? '');
        $loaiDV  = trim($_GET['loai'] ?? '');
        $dsDichVu = $this->model->getAll($keyword, $loaiDV);
        $editDV = null;

        if (!empty($_GET['edit'])) {
            $editDV = $this->model->getById($_GET['edit']);
        }

        require_once __DIR__ . '/../views/admin/dichvu/index.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $isUpdate = !empty($_POST['is_update']);
                $donGia = (float) ($_POST['DonGiaDV'] ?? 0);
                if ($donGia < 0) {
                    $_SESSION['err_msg'] = "Đơn giá dịch vụ không được nhỏ hơn 0!";
                    header("Location: index.php?page=admin_dichvu");
                    exit;
                }

                $data = [
                    ':MaDV'     => $isUpdate ? trim($_POST['MaDV']) : $this->model->generateNewId(),
                    ':TenDV'    => trim($_POST['TenDV']),
                    ':LoaiDV'   => $_POST['LoaiDV'],
                    ':DonGiaDV' => $donGia
                ];

                if ($isUpdate) {
                    $this->model->update($data);
                    $_SESSION['success_msg'] = "Cập nhật dịch vụ thành công!";
                } else {
                    $this->model->create($data);
                    $_SESSION['success_msg'] = "Thêm mới dịch vụ thành công!";
                }
            } catch (PDOException $e) {
                $_SESSION['err_msg'] = "Lỗi khi lưu dịch vụ: " . $e->getMessage();
            }
        }
        header("Location: index.php?page=admin_dichvu");
        exit;
    }

    public function toggle() {
        $id = $_GET['id'] ?? '';
        if ($id !== '') {
            $this->model->toggleStatus($id);
        }
        header("Location: index.php?page=admin_dichvu");
        exit;
    }

    public function delete() {
        $id = $_GET['id'] ?? '';
        if ($id !== '') {
            try {
                $this->model->delete($id);
                $_SESSION['success_msg'] = "Đã xóa dịch vụ!";
            } catch (PDOException $e) {
                $_SESSION['err_msg'] = "Không thể xóa dịch vụ đã phát sinh trong lượt khám hoặc chỉ định. Bạn hãy dùng nút Ẩn/Ngừng cung cấp!";
            }
        }
        header("Location: index.php?page=admin_dichvu");
        exit;
    }
}