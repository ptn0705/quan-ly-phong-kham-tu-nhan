<?php
require_once __DIR__ . '/../models/PhongKhamModel.php';

class PhongKhamController {
    private $model;

    public function __construct($db) {
        $this->model = new PhongKhamModel($db);
    }

    public function index() {
        $keyword = trim($_GET['keyword'] ?? '');
        $dsPhongKham = $this->model->getAll($keyword);
        $editPK = null;

        if (!empty($_GET['edit'])) {
            $editPK = $this->model->getById($_GET['edit']);
        }

        require_once __DIR__ . '/../views/admin/phongkham/index.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $isUpdate = !empty($_POST['is_update']);
                $data = [
                    ':MaPK'  => $isUpdate ? trim($_POST['MaPK']) : $this->model->generateNewId(),
                    ':TenPK' => trim($_POST['TenPK']),
                    ':MoTa'  => trim($_POST['MoTa'] ?? '')
                ];

                if ($isUpdate) {
                    $this->model->update($data);
                    $_SESSION['success_msg'] = "Cập nhật phòng khám thành công!";
                } else {
                    $this->model->create($data);
                    $_SESSION['success_msg'] = "Thêm mới phòng khám thành công!";
                }
            } catch (PDOException $e) {
                $_SESSION['err_msg'] = "Lỗi khi lưu phòng khám: " . $e->getMessage();
            }
        }
        header("Location: index.php?page=admin_phongkham");
        exit;
    }

    public function delete() {
        $id = $_GET['id'] ?? '';
        if ($id !== '') {
            try {
                $this->model->delete($id);
                $_SESSION['success_msg'] = "Đã xóa phòng khám!";
            } catch (PDOException $e) {
                $_SESSION['err_msg'] = "Không thể xóa phòng khám đã có lịch sử lượt khám!";
            }
        }
        header("Location: index.php?page=admin_phongkham");
        exit;
    }
}