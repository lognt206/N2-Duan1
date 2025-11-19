<?php

require_once __DIR__ . '/../models/PartnerModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/Guidemodel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/tourmodel.php';

class admincontroller {
 public $categoryModel;
public $modelTour;
public $modelTourGuide;
public $UserModel;
public $PartnerModel;

public function __construct() {
  $this->modelTour = new TourModel();
        $this->categoryModel = new CategoryModel();
    $this->modelTourGuide = new TourGuideModel();
        $this->categoryModel = new CategoryModel();
    $this->UserModel = new UserModel();
$this->PartnerModel = new PartnerModel();


    }




public function dashboard() {
        include "views/admin/dashboard.php";
    }

public function tour() {
    $tours = $this->modelTour->all();
        include "views/admin/tour/noidung.php";
    }
public function create(){
    include "views/admin/tour/create.php";
}
public function store(){
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
    public function delete(){
    if(isset($_GET['id'])){
        $id = (int)$_GET['id'];
        $this->modelTour->delete($id);
    }
    $tours = $this->modelTour->all();
    include "views/admin/tour/noidung.php";
    exit();
}

public function update()
{
    // Nếu POST -> xử lý cập nhật
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

    // Nếu GET -> load form edit
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

public function guideadmin() {
    $guides = $this->modelTourGuide->allguide();
        include "views/admin/guideadmin/noidung.php";
    }
public function create_guide(){
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $guide = new Guide();
        $guide->full_name           = $_POST['full_name'];
        $guide->birth_date          = !empty($_POST['birth_date']) ? $_POST['birth_date'] : null;
        $guide->contact             = $_POST['contact'];
        $guide->languages           = $_POST['languages'];
        $guide->experience          = $_POST['experience'];
        $guide->health_condition    = $_POST['health_condition'];
        $guide->rating              = $_POST['rating'];
        $guide->category            = $_POST['category'];
        $guide->certificate         = $_POST['certificate'] ?? null; 
        $guide->user_id             = $_POST['user_id'] ?? 1; 
        // Xử lý ảnh
        if(isset($_FILES['photo']) && $_FILES['photo']['error'] === 0){
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $file_name = 'uploads/' . time() . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], $file_name);
            $guide->photo = $file_name;
        } else {
            $guide->photo = null;
        }

        $result = $this->modelTourGuide->create_guide($guide);

        if($result){
            header("Location: ?act=guideadmin");
            exit();
        } else {
            echo "Tạo mới hướng dẫn viên thất bại!";
        }
    }
    include "views/admin/guideadmin/create_guide.php";
}
public function delete_guide(){
    if(isset($_GET['id'])){
        $id = (int)$_GET['id'];
        $this->modelTourGuide->delete_guide($id);
    }
    $guides = $this->modelTourGuide->allguide();
    include "views/admin/guideadmin/noidung.php";
}
public function update_guide(){

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(!isset($_POST['guide_id'])){
            echo "Lỗi: Không tìm thấy guide_id";
            exit();
        }
        $guide = new Guide();
        $guide->guide_id         = $_POST['guide_id'];
        $guide->user_id          = 1;
        $guide->full_name        = $_POST['full_name'] ?? "";
        $guide->birth_date       = $_POST['birth_date'] ?? "";
        $guide->contact          = $_POST['contact'] ?? "";
        $guide->certificate      = $_POST['certificate'] ?? "";
        $guide->languages        = $_POST['languages'] ?? "";
        $guide->experience       = $_POST['experience'] ?? "";
        $guide->health_condition = $_POST['health_condition'] ?? "";
        $guide->rating           = $_POST['rating'] ?? "";
        $guide->category         = $_POST['category'] ?? "";
        //Xử lý ảnh
        if(isset($_FILES['photo']['name']) && $_FILES['photo']['error'] === 0){
            $file_name = 'uploads/' . time() . '.' . $_FILES['photo']['name'];
            move_uploaded_file($_FILES['photo']['tmp_name'], $file_name);
            $guide->photo = $file_name;
        } else {
            $guide->photo = $_POST['old_photo'];
        }

        $this->modelTourGuide->update_guide($guide);
        $guides = $this->modelTourGuide->allguide();
        include "views/admin/guideadmin/noidung.php";
        exit();
    }
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $guide = $this->modelTourGuide->find_guide($id);
        if((!$guide)){
            echo "Không tìm thấy hướng dẫn viên.";
            exit();
        }
        include "views/admin/guideadmin/update_guide.php";
        exit();
    }   
}
public function booking() {
        include "views/admin/booking/noidung.php";
    }
public function customer() {
        include "views/admin/customer/noidung.php";
    }
public function partner() {
   
        $partners = $this->PartnerModel->all();
        include "views/admin/partner/noidung.php";
    }
    public function category() {
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

public function category_create()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name   = trim($_POST['category_name'] ?? '');
        $status = $_POST['status'] ?? 1;

        if ($name === '') {
            $_SESSION['error'] = "Tên danh mục không được để trống!";
            header("Location: ?act=category/create");
            exit;
        }

        // Tạo object Category
        $category = new Category();
        $category->category_name = $name;
        $category->status = $status;

        // Gọi model
        $this->categoryModel->create_danhmuc($category);

        $_SESSION['success'] = "Thêm category thành công!";
        header("Location: ?act=category");
        exit;
    }

    include "./views/admin/category/create.php";
}


public function category_update($id) {
    // Lấy ID từ GET hoặc POST
    $id = $_GET['id'] ?? $_POST['category_id'] ?? null;

    if (!$id) {
        echo "Không tìm thấy danh mục!";
        exit;
    }

    // Lấy category từ model
    $category = $this->categoryModel->find($id);
    if (!$category) {
        echo "Danh mục không tồn tại!";
        exit;
    }

    // Xử lý POST (cập nhật)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = new Category();
    $category->category_id   = $_POST['category_id'] ?? 0;
    $category->category_name = $_POST['category_name'] ?? '';
    $category->status        = $_POST['status'] ?? 1;

    $this->categoryModel->update_danhmuc($category);

    $_SESSION['success'] = "Cập nhật danh mục thành công!";
    header("Location: ?act=category");
    exit;
}
    // Nếu GET -> load form
    include "views/admin/category/update.php";
}




 public function delete_danhmuc($id) {
        $danhmuc = $this->categoryModel->find($id);
        $thongbao = "";
        if (!$danhmuc) {
            $thongbao = "Danh mục không tồn tại!";
        } else if ($danhmuc->sum > 0) {
            $thongbao = "Không thể xóa khi danh mục vẫn còn sản phẩm.";
        } else {
            $ketqua = $this->categoryModel->delete_danhmuc($id);
            if ($ketqua === 1) {
                header("Location: ?act=category");
                exit;
            } else {
                $thongbao = "Xóa danh mục thất bại.";
            }
        }

        $danhsach = $this->categoryModel->all();
        include "views/admin/category/noidung.php";
    }


public function accoun() {
    $err = "";
        $accounts = $this->UserModel->all();

        if (isset($_POST['tim'])) {
            $user = $_POST['user'];
            if (empty($user)) {
                $err = "bạn chưa nhập tên người dùng";
            }

            foreach ($accounts as $tt) {
                if (stripos($tt->email, $user) !== false || stripos($tt->name, $user) !== false) {
                    $ketqua[] = $tt;
                }
            }

            if (empty($ketqua)) {
                $err = "không tìm thấy";
            } else {
                $accounts = $ketqua;
            }
        }
        include "views/admin/accoun/noidung.php";
    }
public function account_toggle($id) {
    $user = $this->UserModel->find($id);

    if (!$user) {
        echo "Tài khoản không tồn tại!";
        exit;
    }

    // Đổi trạng thái
    $newStatus = $user->status == 1 ? 0 : 1;
    $this->UserModel->updateStatus($id, $newStatus);

    header("Location: ?act=accoun");
    exit;
}
public function account_delete($id) 
{
    $user = $this->UserModel->find($id);

    if (!$user) {
        echo "Tài khoản không tồn tại!";
        exit;
    }

    $this->UserModel->delete($id);

    header("Location: ?act=accoun");
    exit;
}

}


?>