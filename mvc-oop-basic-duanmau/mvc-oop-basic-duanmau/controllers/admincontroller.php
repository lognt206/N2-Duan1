<?php

require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/tourmodel.php';
require_once __DIR__ . '/../models/TourGuideModel.php'; // ← THÊM MODEL HDV

class admincontroller
{
    public $categoryModel;
    public $modelTour;
    public $modelGuide; // ← THÊM PROPERTY

    public function __construct()
    {
        $this->modelTour = new TourModel();
        $this->categoryModel = new CategoryModel();
        $this->modelGuide = new TourGuideModel(); // ← KHỞI TẠO MODEL
    }

    public function dashboard()
    {
        include "views/admin/dashboard.php";
    }

    // ========== TOUR ==========
    public function tour()
    {
        $tours = $this->modelTour->all();
        include "views/admin/tour/noidung.php";
    }

    public function create()
    {
        include "views/admin/tour/create.php";
    }

    public function edit()
    {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];

            // Lấy thông tin tour theo ID
            $tour = $this->modelTour->find($id);

            // Lấy danh sách category (nếu form cần)
            $categories = $this->categoryModel->all();

            if (!$tour) {
                echo "Không tìm thấy tour!";
                exit();
            }

            // Load giao diện update
            include "views/admin/tour/update.php";
            exit();
        }

        echo "Request không hợp lệ.";
        exit();
    }


    public function store()
    {
        $tour = new Tour();
        $tour->tour_name = $_POST['tour_name'];
        $tour->category_id = $_POST['tour_category'];
        $tour->description  = $_POST['description'];
        $tour->price        = $_POST['price'];
        $tour->policy       = $_POST['policy'];
        $tour->supplier     = $_POST['supplier'];
        $tour->status       = $_POST['status'];
        $this->modelTour->create($tour);
        $tours = $this->modelTour->all();
        include "views/admin/tour/noidung.php";
        exit();
    }

    public function delete()
    {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $this->modelTour->delete($id);
        }
        $tours = $this->modelTour->all();
        include "views/admin/tour/noidung.php";
        exit();
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['tour_id'])) {
                echo "Lỗi: Không tìm thấy tour_id.";
                exit();
            }

            $tour = new Tour();
            $tour->tour_id      = $_POST['tour_id'];
            $tour->tour_name    = $_POST['tour_name'] ?? "";
            $tour->category_id  = $_POST['tour_category'] ?? "";
            $tour->description  = $_POST['description'] ?? "";
            $tour->price        = $_POST['price'] ?? 0;
            $tour->policy       = $_POST['policy'] ?? "";
            $tour->supplier     = $_POST['supplier'] ?? "";
            $tour->status       = $_POST['status'] ?? 1;

            $this->modelTour->update($tour);

            $tours = $this->modelTour->all();
            include "views/admin/tour/noidung.php";
            exit();
        }

        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $tour = $this->modelTour->find($id);

            if (!$tour) {
                echo "Không tìm thấy tour!";
                exit();
            }

            include "views/admin/tour/update.php";
            exit();
        }

        echo "Request không hợp lệ.";
    }

    // ========== QUẢN LÝ HƯỚNG DẪN VIÊN (MỚI) ==========

    // Hiển thị danh sách HDV
    public function guideadmin()
    {
        $guides = $this->modelGuide->getAllGuides();
        include "views/admin/guideadmin/noidung.php";
    }

    // Form thêm HDV
    public function guideadmin_create()
    {
        $availableUsers = $this->modelGuide->getAvailableUsers();
        include "views/admin/guideadmin/create.php";
    }

    // Xử lý thêm HDV
    public function guideadmin_store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array(
                'user_id' => $_POST['user_id'],
                'full_name' => $_POST['full_name'],
                'birth_date' => $_POST['birth_date'],
                'photo' => '',
                'contact' => $_POST['contact'],
                'certificate' => '',
                'languages' => $_POST['languages'],
                'experience' => $_POST['experience'],
                'health_condition' => $_POST['health_condition'],
                'rating' => $_POST['rating'] ?? 0,
                'category' => $_POST['category'] ?? 1
            );

            // Upload ảnh nếu có
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                $photo = $this->modelGuide->uploadPhoto($_FILES['photo']);
                if ($photo) {
                    $data['photo'] = $photo;
                }
            }

            // Upload chứng chỉ nếu có
            if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] === 0) {
                $certificate = $this->modelGuide->uploadCertificate($_FILES['certificate']);
                if ($certificate) {
                    $data['certificate'] = $certificate;
                }
            }

            if ($this->modelGuide->addGuide($data)) {
                $_SESSION['success'] = "Thêm hướng dẫn viên thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi thêm hướng dẫn viên!";
            }

            $guides = $this->modelGuide->getAllGuides();
            include "views/admin/guideadmin/noidung.php";
            exit();
        }
    }

    // Form sửa HDV
    public function guideadmin_edit()
    {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $guide = $this->modelGuide->getGuideById($id);
            $availableUsers = $this->modelGuide->getAvailableUsers();

            if (!$guide) {
                $_SESSION['error'] = "Không tìm thấy hướng dẫn viên!";
                $guides = $this->modelGuide->getAllGuides();
                include "views/admin/guideadmin/noidung.php";
                exit();
            }

            include "views/admin/guideadmin/update.php";
        }
    }

    // Xử lý cập nhật HDV
    public function guideadmin_update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['guide_id'];
            $guide = $this->modelGuide->getGuideById($id);

            $data = array(
                'user_id' => $_POST['user_id'],
                'full_name' => $_POST['full_name'],
                'birth_date' => $_POST['birth_date'],
                'photo' => $guide['photo'],
                'contact' => $_POST['contact'],
                'certificate' => $guide['certificate'],
                'languages' => $_POST['languages'],
                'experience' => $_POST['experience'],
                'health_condition' => $_POST['health_condition'],
                'rating' => $_POST['rating'] ?? 0,
                'category' => $_POST['category'] ?? 1
            );

            // Upload ảnh mới nếu có
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                $photo = $this->modelGuide->uploadPhoto($_FILES['photo']);
                if ($photo) {
                    // Xóa ảnh cũ
                    if ($guide['photo'] && file_exists($guide['photo'])) {
                        unlink($guide['photo']);
                    }
                    $data['photo'] = $photo;
                }
            }

            // Upload chứng chỉ mới nếu có
            if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] === 0) {
                $certificate = $this->modelGuide->uploadCertificate($_FILES['certificate']);
                if ($certificate) {
                    // Xóa chứng chỉ cũ
                    if ($guide['certificate'] && file_exists($guide['certificate'])) {
                        unlink($guide['certificate']);
                    }
                    $data['certificate'] = $certificate;
                }
            }

            if ($this->modelGuide->updateGuide($id, $data)) {
                $_SESSION['success'] = "Cập nhật hướng dẫn viên thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật hướng dẫn viên!";
            }

            $guides = $this->modelGuide->getAllGuides();
            include "views/admin/guideadmin/noidung.php";
            exit();
        }
    }

    // Xóa HDV
    public function guideadmin_delete()
    {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];

            // Kiểm tra HDV có tour đang hoạt động không
            if ($this->modelGuide->hasActiveTours($id)) {
                $_SESSION['error'] = "Không thể xóa! Hướng dẫn viên đang có tour hoạt động.";
            } else {
                $guide = $this->modelGuide->getGuideById($id);

                if ($this->modelGuide->deleteGuide($id)) {
                    // Xóa ảnh và chứng chỉ
                    if ($guide['photo'] && file_exists($guide['photo'])) {
                        unlink($guide['photo']);
                    }
                    if ($guide['certificate'] && file_exists($guide['certificate'])) {
                        unlink($guide['certificate']);
                    }

                    $_SESSION['success'] = "Xóa hướng dẫn viên thành công!";
                } else {
                    $_SESSION['error'] = "Có lỗi xảy ra khi xóa hướng dẫn viên!";
                }
            }
        }

        $guides = $this->modelGuide->getAllGuides();
        include "views/admin/guideadmin/noidung.php";
        exit();
    }

    // ========== BOOKING, CUSTOMER, PARTNER ==========
    public function booking()
    {
        include "views/admin/booking/noidung.php";
    }

    public function customer()
    {
        include "views/admin/customer/noidung.php";
    }

    public function partner()
    {
        include "views/admin/partner/noidung.php";
    }

    // ========== CATEGORY ==========
    public function category()
    {
        $danhsach = $this->categoryModel->all();
        $thanhcong = "";
        $loi = "";
        $danhmuc = new Category();

        if (isset($_POST['create_danhmuc'])) {
            $danhmuc->category_id  = $_POST['category_id'];
            $danhmuc->category_name  = $_POST['category_name'];
            $danhmuc->status  = $_POST['status'];

            if (empty($danhmuc->category_name)) {
                $loi = "Kiểm tra lại dữ liệu";
            } else {
                $ketqua = $this->categoryModel->create_danhmuc($danhmuc);
                if ($ketqua === 1) {
                    $thanhcong = "Tạo danh mục thành công";
                    $danhsach = $this->categoryModel->all();
                } else {
                    $loi = "Tạo danh mục thất bại";
                }
            }
        }
        include "views/admin/category/noidung.php";
    }
}
