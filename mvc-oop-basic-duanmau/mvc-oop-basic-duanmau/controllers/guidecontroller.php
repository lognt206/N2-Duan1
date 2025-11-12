<?php
require_once "models/guide.php";

class GuideController {
    private $model;

    public function __construct() {
        $this->model = new Guide();
    }

    // Hiển thị danh sách HDV
    public function guide() {
        $guides = $this->model->getAll();
        include "views/admin/guide/noidung.php";
    }

    // Thêm mới HDV
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý upload ảnh nếu có
            $photo = '';
            if (!empty($_FILES['photo']['name'])) {
                $photo = 'uploads/' . time() . '_' . $_FILES['photo']['name'];
                move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
            }

            $data = [
                'user_id' => $_POST['user_id'],
                'full_name' => $_POST['full_name'],
                'birth_date' => $_POST['birth_date'],
                'photo' => $photo,
                'contact' => $_POST['contact'],
                'languages' => $_POST['languages'],
                'experience' => $_POST['experience'],
                'health_condition' => $_POST['health_condition'],
                'rating' => $_POST['rating'],
                'category' => $_POST['category']
            ];

            $this->model->create($data);
            header("Location: ?controller=guides");
        } else {
            include "views/admin/guides/create.php";
        }
    }

    // Chỉnh sửa HDV
    public function edit() {
        $id = $_GET['id'];
        $guide = $this->model->getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $photo = $guide['photo']; // giữ ảnh cũ
            if (!empty($_FILES['photo']['name'])) {
                $photo = 'uploads/' . time() . '_' . $_FILES['photo']['name'];
                move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
            }

            $data = [
                'full_name' => $_POST['full_name'],
                'birth_date' => $_POST['birth_date'],
                'photo' => $photo,
                'contact' => $_POST['contact'],
                'languages' => $_POST['languages'],
                'experience' => $_POST['experience'],
                'health_condition' => $_POST['health_condition'],
                'rating' => $_POST['rating'],
                'category' => $_POST['category']
            ];

            $this->model->update($id, $data);
            header("Location: ?controller=guides");
        } else {
            include "views/admin/guides/edit.php";
        }
    }

    // Xóa HDV
    public function delete() {
        $id = $_GET['id'];
        $this->model->delete($id);
        header("Location: ?controller=guides");
    }
}
?>
