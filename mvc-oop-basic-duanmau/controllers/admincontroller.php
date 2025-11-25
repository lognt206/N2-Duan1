<?php
require_once __DIR__ . '/../models/PartnerModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/Guidemodel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/tourmodel.php';
require_once __DIR__ . '/../models/TourImageModel.php';
require_once __DIR__ . '/../models/CustomerModel.php';
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/tourPartnerModel.php';
require_once __DIR__ . '/../models/DepartureModel.php';
require_once __DIR__ . '/../models/itineraryModel.php';
require_once __DIR__ . '/../models/CustomerGroupModel.php';


class admincontroller {
    public $categoryModel;
    public $modelTour;
    public $modelTourImage;
    public $modelTourGuide;
    public $modelCustomer;
    public $BookingModel;
    public $PartnerModel;
    public $UserModel;
    public $modelTourPartner;
    public $DepartureModel;
    public $itineraryModel;
    public $CustomerGroupModel;


    public function __construct() {
        $this->modelTour = new TourModel();
        $this->modelTourImage = new TourImageModel();
        $this->categoryModel = new CategoryModel();
        $this->modelTourGuide = new TourGuideModel();
        $this->modelCustomer = new CustomerModel();
        $this->BookingModel = new BookingModel();
        $this->PartnerModel = new PartnerModel();
        $this->UserModel = new UserModel();
        $this->modelTourPartner = new TourPartnerModel();
        $this->DepartureModel = new DepartureModel();
        $this->itineraryModel = new itineraryModel();
        $this->CustomerGroupModel = new CustomerGroupModel();

    }
public function dashboard() {
        include "views/admin/dashboard.php";
    }
    public function tour() {
        $tours = $this->modelTour->all();

        foreach ($tours as &$tour) {
            $tour['partners'] = $this->modelTourPartner->getPartnersByTour($tour['tour_id']);
            $tour['itineraries'] = $this->itineraryModel->getByTour($tour['tour_id']);
            $tour['images'] = $this->modelTourImage->getImagesByTour($tour['tour_id']);
        }

        include "views/admin/tour/noidung.php";
    }

    public function create() {
        $partners = $this->PartnerModel->all();
        $categories = $this->categoryModel->all();
        include "views/admin/tour/create.php";
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tour = new Tour();
            $tour->tour_name   = $_POST['tour_name'] ?? "";
            $tour->category_id = $_POST['tour_category'] ?? 1;
            $tour->description = $_POST['description'] ?? "";
            $tour->price       = $_POST['price'] ?? 0;
            $tour->policy      = $_POST['policy'] ?? "";
            $tour->status      = $_POST['status'] ?? 1;

            // Xử lý ảnh đại diện
            if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $file_name = 'uploads/' . time() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $file_name);
                $tour->image = $file_name;
            } else {
                $tour->image = null;
            }

            // Tạo tour
            $tour_id = $this->modelTour->create($tour);
            if (!$tour_id) die("Không thể tạo tour!");

            // Thêm partner
            $partner_ids = $_POST['supplier'] ?? [];
            $this->handleTourPartners($tour_id, $partner_ids);

            // Thêm gallery nhiều ảnh
            if(isset($_FILES['gallery'])){
                $gallery_files = $_FILES['gallery'];
                $gallery_paths = [];
                for($i=0; $i<count($gallery_files['name']); $i++){
                    if($gallery_files['error'][$i] === 0){
                        $ext = pathinfo($gallery_files['name'][$i], PATHINFO_EXTENSION);
                        $file_name = 'uploads/' . time() . '_' . $i . '.' . $ext;
                        move_uploaded_file($gallery_files['tmp_name'][$i], $file_name);
                        $gallery_paths[] = $file_name;
                    }
                }
                if(!empty($gallery_paths)){
                    $this->modelTourImage->addImages($tour_id, $gallery_paths);
                }
            }

            // Thêm itinerary
            $itineraries = $_POST['itinerary'] ?? [];
            foreach ($itineraries as $it) {
                $itinerary = new Itinerary();
                $itinerary->tour_id = $tour_id;
                $itinerary->day_number = $it['day_number'] ?? 1;
                $itinerary->start_time = $it['start_time'] ?? '';
                $itinerary->end_time = $it['end_time'] ?? '';
                $itinerary->activity = $it['activity'] ?? '';
                $itinerary->location = $it['location'] ?? '';
                $this->itineraryModel->create($itinerary);
            }

            header("Location: index.php?act=tour");
            exit;
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tour = new Tour();
            $tour->tour_id     = $_POST['tour_id'];
            $tour->tour_name   = $_POST['tour_name'] ?? "";
            $tour->category_id = $_POST['tour_category'] ?? 1;
            $tour->description = $_POST['description'] ?? "";
            $tour->price       = $_POST['price'] ?? 0;
            $tour->policy      = $_POST['policy'] ?? "";
            $tour->status      = $_POST['status'] ?? 1;

            // Xử lý ảnh đại diện
            if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $file_name = 'uploads/' . time() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $file_name);
                $tour->image = $file_name;
            } else {
                $tour->image = $_POST['old_image'] ?? null;
            }

            // Update tour
            $this->modelTour->update($tour);

            // Update partner
            $partner_ids = $_POST['supplier'] ?? [];
            $this->handleTourPartners($tour->tour_id, $partner_ids);

            // Update gallery
            if(isset($_FILES['gallery'])){
                $gallery_files = $_FILES['gallery'];
                $gallery_paths = [];
                for($i=0; $i<count($gallery_files['name']); $i++){
                    if($gallery_files['error'][$i] === 0){
                        $ext = pathinfo($gallery_files['name'][$i], PATHINFO_EXTENSION);
                        $file_name = 'uploads/' . time() . '_' . $i . '.' . $ext;
                        move_uploaded_file($gallery_files['tmp_name'][$i], $file_name);
                        $gallery_paths[] = $file_name;
                    }
                }
                if(!empty($gallery_paths)){
                    $this->modelTourImage->updateImages($tour->tour_id, $gallery_paths);
                }
            }

            // Xóa itinerary cũ
            $oldItineraries = $this->itineraryModel->getByTour($tour->tour_id);
            foreach ($oldItineraries as $it) {
                $this->itineraryModel->delete($it['itinerary_id']);
            }

            // Thêm itinerary mới
            $itineraries = $_POST['itinerary'] ?? [];
            foreach ($itineraries as $it) {
                $itinerary = new Itinerary();
                $itinerary->tour_id = $tour->tour_id;
                $itinerary->day_number = $it['day_number'] ?? 1;
                $itinerary->start_time = $it['start_time'] ?? '';
                $itinerary->end_time = $it['end_time'] ?? '';
                $itinerary->activity = $it['activity'] ?? '';
                $itinerary->location = $it['location'] ?? '';
                $this->itineraryModel->create($itinerary);
            }

            header("Location: index.php?act=tour");
            exit;
        }

        if (isset($_GET['id'])) {
            $tour_id = $_GET['id'];
            $tour = $this->modelTour->find($tour_id);
            $partners = $this->PartnerModel->all();
            $tour_partners = $this->modelTourPartner->getPartnersByTour($tour_id);
            $selectedPartners = array_column($tour_partners, 'partner_id');
            $itineraries = $this->itineraryModel->getByTour($tour_id);
            $gallery = $this->modelTourImage->getImagesByTour($tour_id);

            include "views/admin/tour/update.php";
            exit;
        }
    }

    private function handleTourPartners($tour_id, $partner_ids) {
        $this->modelTourPartner->deletePartnersByTour($tour_id);
        foreach ($partner_ids as $pid) {
            $this->modelTourPartner->addPartnerToTour($tour_id, $pid);
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
    $groups = $this->BookingModel->allGroups(); // lấy danh sách tệp khách hàng
    $guides = $this->modelTourGuide->allguide();
    $departures = $this->DepartureModel->all(); 

    include "views/admin/booking/createbooking.php";
}

public function storebooking()
{
    $tour_id      = $_POST['tour_id'];
    $group_id     = $_POST['group_id'] ?? null; // chỉ chọn 1 tệp khách
    $guide_id     = $_POST['guide_id'] ?? null;
    $departure_id = $_POST['departure_id'] ?? null;
    $booking_date = $_POST['booking_date'];
    $num_people   = $_POST['num_people'];
    $booking_type = $_POST['booking_type'];
    $status       = $_POST['status'];
    $notes        = $_POST['notes'];

    // Check trùng lịch với hướng dẫn viên
    if ($guide_id && $departure_id && !$this->BookingModel->guideAvailable($guide_id, $departure_id)) {
        $_SESSION['error'] = "Hướng dẫn viên đã trùng lịch!";
        header("Location: ?act=createbooking");
        exit;
    }

    // Nếu cần check trùng lịch theo group, bạn có thể thêm hàm kiểm tra
    // if ($group_id && !$this->BookingModel->groupAvailable($group_id, $departure_id)) { ... }

    $data = [
        'tour_id'      => $tour_id,
        'group_id'     => $group_id,
        'guide_id'     => $guide_id,
        'departure_id' => $departure_id,
        'booking_date' => $booking_date,
        'num_people'   => $num_people,
        'booking_type' => $booking_type,
        'status'       => $status,
        'notes'        => $notes,
    ];

    $this->BookingModel->insert($data); // sửa insert trong model để chỉ dùng group_id

    $_SESSION['success'] = "Tạo booking thành công!";
    header("Location: ?act=booking");
    exit;
}

public function updatebooking()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $booking_id   = $_POST['booking_id'];
        $tour_id      = $_POST['tour_id'];
        $group_id     = $_POST['group_id'] ?? null;
        $guide_id     = $_POST['guide_id'] ?? null;
        $departure_id = $_POST['departure_id'] ?? null;
        $booking_date = $_POST['booking_date'];
        $num_people   = $_POST['num_people'];
        $booking_type = $_POST['booking_type'];
        $status       = $_POST['status'];
        $notes        = $_POST['notes'];

        $data = [
            'booking_id'   => $booking_id,
            'tour_id'      => $tour_id,
            'group_id'     => $group_id,
            'guide_id'     => $guide_id,
            'departure_id' => $departure_id,
            'booking_date' => $booking_date,
            'num_people'   => $num_people,
            'booking_type' => $booking_type,
            'status'       => $status,
            'notes'        => $notes,
        ];

        $this->BookingModel->update($data);

        $_SESSION['success'] = "Cập nhật booking thành công!";
        header("Location: ?act=booking");
        exit;
    }

    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $booking = $this->BookingModel->detail($id);
        $tours = $this->modelTour->all();
        $guides = $this->modelTourGuide->allguide();
        $groups = $this->BookingModel->allGroups(); // danh sách tệp khách
        $departures = $this->DepartureModel->all();

        include "views/admin/booking/updatebooking.php";
        exit;
    }
}

public function bookingDetail() {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ?act=booking");
        exit;
    }

    $id = (int)$_GET['id'];
    $booking = $this->BookingModel->detail($id); 

    if (!$booking) {
        echo "Không tìm thấy booking #$id";
        exit;
    }

    $tours = $this->modelTour->all();
    $guides = $this->modelTourGuide->allguide();
    $groups = $this->BookingModel->all(); // danh sách tệp khách
    $departures = $this->DepartureModel->all();

    include "views/admin/booking/detail.php"; 
}









 public function customer() {
        $customers = $this->modelCustomer->allcustomer();
        include "views/admin/customer/noidung.php";
    }

   public function create_customer() {
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $customer = new Customer();
        $customer->group_id        = $_POST['group_id'] ?? null; // thêm dòng này
        $customer->full_name       = $_POST['full_name'] ?? "";
        $customer->gender          = $_POST['gender'] ?? 1;
        $customer->birth_year      = $_POST['birth_year'] ?: null;
        $customer->id_number       = $_POST['id_number'] ?: null;
        $customer->contact         = $_POST['contact'] ?: null;
        $customer->payment_status  = $_POST['payment_status'] ?? 0;
        $customer->special_request = $_POST['special_request'] ?: null;

        try {
            $this->modelCustomer->create_customer($customer);
            header("Location: ?act=customer");
            exit();
        } catch (Exception $e) {
            // hiển thị lỗi nếu tạo khách hàng thất bại
            $error = $e->getMessage();
        }
    }

    // Lấy danh sách nhóm khách để show trong form select
    $groups = $this->CustomerGroupModel->all(); 
    include "views/admin/customer/create_customer.php";
}

    public function update_customer() {
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $customer = new Customer();
        $customer->customer_id     = $_POST['customer_id'];
        $customer->full_name       = $_POST['full_name'] ?? "";
        $customer->gender          = $_POST['gender'] ?? 1;
        $customer->birth_year      = $_POST['birth_year'] ?: null;
        $customer->id_number       = $_POST['id_number'] ?: null;
        $customer->contact         = $_POST['contact'] ?: null;
        $customer->payment_status  = $_POST['payment_status'] ?? 0;
        $customer->special_request = $_POST['special_request'] ?: null;
        $customer->group_id        = $_POST['group_id'] ?? null; // Thêm dòng này

        if (!$customer->group_id) {
            echo "Vui lòng chọn nhóm khách.";
            exit();
        }

        $this->modelCustomer->update_customer($customer);
        header("Location: ?act=customer");
        exit();
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $customer = $this->modelCustomer->find_customer($id);
        if (!$customer) {
            echo "Không tìm thấy khách hàng";
            exit();
        }
        // Lấy danh sách nhóm khách để hiển thị dropdown
        $groupModel = new CustomerGroupModel();
        $groups = $groupModel->all();

        include "views/admin/customer/update_customer.php";
    }
}


    public function delete_customer() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $this->modelCustomer->delete_customer($id);
        }
        header("Location: ?act=customer");
        exit();
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