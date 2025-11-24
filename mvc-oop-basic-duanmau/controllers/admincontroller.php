<?php

require_once __DIR__ . '/../models/PartnerModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/Guidemodel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/tourmodel.php';
require_once __DIR__ . '/../models/CustomerModel.php';
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/tourPartnerModel.php';
require_once __DIR__ . '/../models/DepartureModel.php';


class admincontroller {
 public $categoryModel;
public $modelTour;
public $modelTourGuide;
public $modelCustomer;
public $BookingModel;
public $PartnerModel;
public $UserModel;
public $modelTourPartner;
public $DepartureModel;



public function __construct() {
  $this->modelTour = new TourModel();
        $this->categoryModel = new CategoryModel();
    $this->modelTourGuide = new TourGuideModel();
    $this->modelCustomer = new CustomerModel();
        $this->BookingModel = new BookingModel();
        $this->PartnerModel = new PartnerModel();
        $this->UserModel = new UserModel();
        $this->modelTourPartner = new TourPartnerModel();
                $this->DepartureModel = new DepartureModel();


    }

public function dashboard() {
        include "views/admin/dashboard.php";
    }

       public function tour() {
        $tours = $this->modelTour->all();

        // Lấy partner cho từng tour
        foreach ($tours as &$tour) {
            $tour['partners'] = $this->modelTourPartner->getPartnersByTour($tour['tour_id']);
        }

        include "views/admin/tour/noidung.php";
    }

    // Hiển thị form thêm tour
   public function create() {
    $partners = $this->PartnerModel->all(); 
    $categories = $this->categoryModel->all();
    
    include "views/admin/tour/create.php";
}

    // Lưu tour mới
    public function store() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $tour = new Tour();
        $tour->tour_name   = $_POST['tour_name'] ?? "";
        $tour->category_id = $_POST['tour_category'] ?? 1;
        $tour->description = $_POST['description'] ?? "";
        $tour->price       = $_POST['price'] ?? 0;
        $tour->policy      = $_POST['policy'] ?? "";
        $tour->status      = $_POST['status'] ?? 1;

        // Tạo tour và lấy ID
        $tour_id = $this->modelTour->create($tour);

        if (!$tour_id) {
            die("Không thể tạo tour, vui lòng kiểm tra database.");
        }

        // Thêm partner
        $partner_ids = $_POST['supplier'] ?? [];
        $this->handleTourPartners($tour_id, $partner_ids);

        header("Location: index.php?act=tour");
        exit;
    }
}

    // Cập nhật tour
    public function update() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!isset($_POST['tour_id'])) {
            echo "Không tìm thấy tour_id";
            exit();
        }

        $tour = new Tour();
        $tour->tour_id     = $_POST['tour_id'];
        $tour->tour_name   = $_POST['tour_name'] ?? "";
        $tour->category_id = $_POST['tour_category'] ?? 1;
        $tour->description = $_POST['description'] ?? "";
        $tour->price       = $_POST['price'] ?? 0;
        $tour->policy      = $_POST['policy'] ?? "";
        $tour->status      = $_POST['status'] ?? 1;

        // Cập nhật tour
        $this->modelTour->update($tour);

        // Lấy partner từ form (multi-select)
        $partner_ids = $_POST['supplier'] ?? [];
        $this->handleTourPartners($tour->tour_id, $partner_ids);

        header("Location: index.php?act=tour");
        exit();
    }

    if (isset($_GET['id'])) {
        $tour_id = $_GET['id'];
        $tour = $this->modelTour->find($tour_id);
        $partners = $this->PartnerModel->all();

        // Lấy partner đã chọn của tour
        $tour_partners = $this->modelTourPartner->getPartnersByTour($tour_id);
        $selectedPartners = [];
        if (!empty($tour_partners)) {
            foreach ($tour_partners as $tp) {
                $selectedPartners[] = $tp['partner_id'];
            }
        }

        include "views/admin/tour/update.php";
        exit();
    }
}


private function handleTourPartners($tour_id, $partner_ids) {

    $this->modelTourPartner->deletePartnersByTour($tour_id);

    if (!empty($partner_ids)) {
        foreach ($partner_ids as $pid) {
            $this->modelTourPartner->addPartnerToTour($tour_id, $pid);
        }
    }
}


public function delete() {
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $this->modelTour->delete($id);
    }
    header("Location: index.php?act=tour");
    exit;
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



public function booking()
{
    $booking = $this->BookingModel->all();
    include "views/admin/booking/noidung.php";
}
public function createbooking()
{
    $tours = $this->modelTour->all();
    $customers = $this->modelCustomer->allcustomer();
    $guides = $this->modelTourGuide->allguide();
    $departures = $this->DepartureModel->all(); 

    include "views/admin/booking/createbooking.php";
}

public function storebooking()
{
    $tour_id      = $_POST['tour_id'];
    $customer_ids = $_POST['customer_id']; // mảng nhiều khách
    $guide_id     = $_POST['guide_id'] ?? null;
    $departure_id = $_POST['departure_id'] ?? null;
    $booking_date = $_POST['booking_date'];
    $num_people   = $_POST['num_people'];
    $booking_type = $_POST['booking_type'];
    $status       = $_POST['status'];
    $notes        = $_POST['notes'];

    // Lưu lỗi riêng
    $guide_error = '';
    $customer_errors = [];

    // 1. Check trùng lịch cho HDV
    if ($guide_id && $departure_id) {
    if (!$this->BookingModel->guideAvailable($guide_id, $departure_id)) {
        $_SESSION['guide_error'] = "Hướng dẫn viên này đã trùng lịch làm việc!";
        header("Location: ?act=createbooking");
        exit;
    }
}

    // 2. Check trùng lịch cho từng khách hàng
   foreach ($customer_ids as $customer_id) {
    if (!$this->BookingModel->customerAvailable($customer_id, $departure_id)) {

        $c = $this->modelCustomer->find_customer($customer_id);

        $_SESSION['customer_errors'][] =
            "Khách hàng " . ($c['full_name'] ?? '') . " đã trùng lịch!";

        header("Location: ?act=createbooking");
        exit;
    }
}

    // Nếu có lỗi, lưu vào session và quay lại form
    if ($guide_error || !empty($customer_errors)) {
        $_SESSION['guide_error'] = $guide_error;
        $_SESSION['customer_errors'] = $customer_errors;
        header("Location: ?act=createbooking");
        exit;
    }

    // 3. Nếu không có lỗi, lưu booking
    foreach ($customer_ids as $customer_id) {
        $data = [
            'tour_id'      => $tour_id,
            'customer_id'  => $customer_id,
            'guide_id'     => $guide_id,
            'departure_id' => $departure_id,
            'booking_date' => $booking_date,
            'num_people'   => $num_people,
            'booking_type' => $booking_type,
            'status'       => $status,
            'notes'        => $notes,
        ];
        $this->BookingModel->insert($data);
    }

    $_SESSION['success'] = "Tạo booking thành công!";
    header("Location: ?act=booking");
    exit;
}


public function updatebooking() {
    // Nếu POST -> lưu dữ liệu cập nhật
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['booking_id'])) {
            echo "Không tìm thấy booking_id";
            exit;
        }

        $booking = [
            'booking_id'   => $_POST['booking_id'],
            'tour_id'      => $_POST['tour_id'] ?? null,
            'customer_id'  => $_POST['customer_id'] ?? null,
            'guide_id'     => $_POST['guide_id'] ?? null,
            'departure_id' => $_POST['departure_id'] ?? null,
            'booking_date' => $_POST['booking_date'] ?? null,
            'num_people'   => $_POST['num_people'] ?? 1,
            'booking_type' => $_POST['booking_type'] ?? 1,
            'status'       => $_POST['status'] ?? 1,
            'notes'        => $_POST['notes'] ?? '',
        ];

        // Gọi model để update
        $this->BookingModel->update($booking);

        header("Location: ?act=booking");
        exit;
    }

    // Nếu GET -> load form
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $booking = $this->BookingModel->find($id);
        if (!$booking) {
            echo "Không tìm thấy booking.";
            exit;
        }

        // Lấy danh sách tour, guide, customer, departures để chọn
        $tours = $this->modelTour->all();
        $guides = $this->modelTourGuide->allguide();
        $customers = $this->modelCustomer->allcustomer();
        $departures = $this->DepartureModel->all();

        include "views/admin/booking/updatebooking.php";
        exit;
    }
}
public function deletebooking()
{
    // Kiểm tra id tồn tại
    if (!isset($_GET['id'])) {
        $_SESSION['error'] = "Không tìm thấy booking để xóa!";
        header("Location: ?act=booking");
        exit;
    }

    $booking_id = (int)$_GET['id'];

    // Gọi model để xóa
    $deleted = $this->BookingModel->delete($booking_id);

    if ($deleted) {
        $_SESSION['success'] = "Xóa booking thành công!";
    } else {
        $_SESSION['error'] = "Xóa booking thất bại!";
    }

    header("Location: ?act=booking");
    exit;
}











public function customer() {
    $customers = $this->modelCustomer->allcustomer();
        include "views/admin/customer/noidung.php";
    }
public function create_customer(){
    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $customers = new Customer();
        $customers->booking_id           =$_POST['booking_id'];
        $customers->full_name            =$_POST['full_name'];
        $customers->gender               =$_POST['gender'];
        $customers->birth_year           =$_POST['birth_year'];
        $customers->id_number            =$_POST['id_number'];
        $customers->contact              =$_POST['contact'];
        $customers->payment_status       =$_POST['payment_status'];
        $customers->special_request      =$_POST['special_request'];
        $this->modelCustomer->create_customer($customers);
        $customers = $this->modelCustomer->allcustomer();
        include "views/admin/customer/noidung.php";
        exit();    
    }include "views/admin/customer/create_customer.php";
        exit();
}

public function delete_customer(){
    if(isset($_GET['id'])){
        $id=(int)$_GET['id'];
        $customers = $this->modelCustomer->delete_customer($id);
    }
    $customers = $this->modelCustomer->allcustomer();
    include "views/admin/customer/noidung.php";
}
public function update_customer(){
    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $customer = new Customer();
        $customer->customer_id           =$_POST['customer_id'];
        $customer->booking_id           =$_POST['booking_id'] ?? "";
        $customer->full_name            =$_POST['full_name'] ?? "";
        $customer->gender               =$_POST['gender'] ?? "";
        $customer->birth_year           =$_POST['birth_year'] ?? "";
        $customer->id_number            =$_POST['id_number'] ?? "";
        $customer->contact              =$_POST['contact'] ?? "";
        $customer->payment_status       =$_POST['payment_status'] ?? "";
        $customer->special_request      =$_POST['special_request'] ?? null;
        $this->modelCustomer->update_customer($customer);
        $customers = $this->modelCustomer->allcustomer();
        include "views/admin/customer/noidung.php";
        exit();    
    }
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $customer = $this->modelCustomer->find_customer($id);
        if(!$customer){
            echo "Không tìm thấy khách hàng";
            exit();
        }
    include "views/admin/customer/update_customer.php";
    exit();
    }
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
   public function updateStatus($id)
    {
        $id = $_GET['id'];
        $current_status = $_GET['status'];

        // Thay đổi tuần tự: 0 -> 1 -> 2 -> 3 -> 0
        $new_status = ($current_status + 1) % 4;

        $this->BookingModel->updateStatus($id, $new_status);

        // Quay lại danh sách
        header("Location:  ?act=booking");
        exit;
    }




}


?>