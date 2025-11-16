<?php



require_once __DIR__ . '/../models/CategoryModel.php';

require_once __DIR__ . '/../models/tourmodel.php';

class admincontroller {
 public $categoryModel;
public $modelTour;

public function __construct() {
  $this->modelTour = new TourModel();
        $this->categoryModel = new CategoryModel();
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
        include "views/admin/guideadmin/noidung.php";
    }
public function booking() {
        include "views/admin/booking/noidung.php";
    }
public function customer() {
        include "views/admin/customer/noidung.php";
    }
public function partner() {
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
}


?>