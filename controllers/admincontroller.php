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

    }
public function dashboard() {
    // Tổng số tour
    $totalTours = $this->modelTour->countTours();

    // Tổng số khách hàng
    $totalCustomers = $this->modelCustomer->count();

    // Tổng số booking
    $totalBookings = $this->BookingModel->count();

    // Tổng doanh thu (tính theo số lượng khách * giá tour)
    $totalRevenue = $this->BookingModel->sumRevenue();

    // Dữ liệu biểu đồ doanh thu theo từng tour
    $chartLabels = [];
    $chartValues = [];
    $tours = $this->modelTour->all();

    foreach ($tours as $tour) {
        $chartLabels[] = $tour['tour_name'];
        $chartValues[] = $this->BookingModel->sumRevenueByTour($tour['tour_id']);
    }

    // Load view dashboard
    include "views/admin/dashboard.php";
}
// tour

    public function tour(){
        $tours = $this->modelTour->all();

       foreach ($tours as $tour) {
    $tour['partners'] = $this->modelTourPartner->getPartnersByTour($tour['tour_id']);
    $tour['itineraries'] = $this->itineraryModel->getByTour($tour['tour_id']);
}

        include "views/admin/tour/noidung.php";
    }

    public function create() {
        $partners = $this->PartnerModel->all();
        $categories = $this->categoryModel->all();
        include "views/admin/tour/create.php";
    }

   public function store() {
    if($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    $tour = new Tour();
    $tour->tour_name = $_POST['tour_name'] ?? "";
    $tour->category_id = $_POST['tour_category'] ?? 0;

    // Validate category
    $category = $this->categoryModel->find($tour->category_id);
    if(!$category || $category->status != 1){
        $_SESSION['error'] = "Danh mục này đang inactive!";
        header("Location: ?act=create");
        exit;
    }

    $tour->description = $_POST['description'] ?? "";
    $tour->price = $_POST['price'] ?? 0;
    $tour->status = $_POST['status'] ?? 1;

    // Policy
    $tour->policy = null;
    if(isset($_FILES['policy']) && $_FILES['policy']['error'] === 0){
        $dir = 'uploads/policy/';
        if(!is_dir($dir)) mkdir($dir,0777,true);
        $file = $dir.time().'_'.basename($_FILES['policy']['name']);
        if(move_uploaded_file($_FILES['policy']['tmp_name'],$file)) $tour->policy = $file;
    }

    // Image
    $tour->image = null;
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        if(!is_dir('uploads/')) mkdir('uploads/',0777,true);
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $tour->image = 'uploads/'.time().'_'.uniqid().'.'.$ext;
        move_uploaded_file($_FILES['image']['tmp_name'],$tour->image);
    }

    // Tạo tour
    $tour_id = $this->modelTour->create($tour); // cần return ID
    if(!$tour_id) die("Không thể tạo tour!");

    // Partner
    $partner_ids = $_POST['supplier'] ?? [];
    $this->handleTourPartners($tour_id, $partner_ids);

    // Gallery
    if(isset($_FILES['gallery'])){
        $files = $_FILES['gallery'];
        for($i=0;$i<count($files['name']);$i++){
            if($files['error'][$i]===0){
                $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                $file = 'uploads/'.time().'_'.uniqid().'.'.$ext;
                move_uploaded_file($files['tmp_name'][$i],$file);
                $this->modelTourImage->addImages($tour_id,[$file]);
            }
        }
    }

    // Itinerary
    $its = $_POST['itinerary'] ?? [];
    foreach($its as $it){
        $itinerary = new Itinerary();
        $itinerary->tour_id = $tour_id;
        $itinerary->day_number = $it['day_number'] ?? 1;
        $itinerary->start_time = $it['start_time'] ?? '';
        $itinerary->end_time = $it['end_time'] ?? '';
        $itinerary->activity = $it['activity'] ?? '';
        $itinerary->location = $it['location'] ?? '';
        $this->itineraryModel->create($itinerary);
    }

    header("Location:?act=tour");
    exit;
}



   public function update() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tour = new Tour();
        $tour->tour_id     = $_POST['tour_id'];
        $tour->tour_name   = $_POST['tour_name'] ?? "";
        $tour->category_id = $_POST['tour_category'] ?? 1;
        $tour->description = $_POST['description'] ?? "";
        $tour->price       = $_POST['price'] ?? 0;
        $tour->status      = $_POST['status'] ?? 1;

        // Xử lý file chính sách
        if(isset($_FILES['policy']) && $_FILES['policy']['error'] === 0){
            $uploadDir = 'uploads/policy/';
            if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $fileName = time() . '_' . basename($_FILES['policy']['name']);
            $targetFile = $uploadDir . $fileName;

            if(move_uploaded_file($_FILES['policy']['tmp_name'], $targetFile)){
                $tour->policy = $targetFile;
            } else {
                $tour->policy = $_POST['old_policy'] ?? null;
            }
        } else {
            $tour->policy = $_POST['old_policy'] ?? null;
        }

        // Xử lý ảnh tour
        if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = 'uploads/' . time() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $fileName);
            $tour->image = $fileName;
        } else {
            $tour->image = $_POST['old_image'] ?? null;
        }

        // Cập nhật tour
        $this->modelTour->update($tour);

        // Cập nhật đối tác
        $partner_ids = $_POST['supplier'] ?? [];
        $this->handleTourPartners($tour->tour_id, $partner_ids);

        // Cập nhật gallery
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
        foreach($oldItineraries as $it){
            $this->itineraryModel->delete($it['itinerary_id']);
        }

        // Thêm itinerary mới
        $itineraries = $_POST['itinerary'] ?? [];
        foreach($itineraries as $it){
            $itinerary = new Itinerary();
            $itinerary->tour_id    = $tour->tour_id;
            $itinerary->day_number = $it['day_number'] ?? 1;
            $itinerary->start_time = $it['start_time'] ?? '';
            $itinerary->end_time   = $it['end_time'] ?? '';
            $itinerary->activity   = $it['activity'] ?? '';
            $itinerary->location   = $it['location'] ?? '';
            $this->itineraryModel->create($itinerary);
        }

        header("Location: index.php?act=tour");
        exit;
    }

    // Hiển thị form sửa tour
    if(isset($_GET['id'])){
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

// admin hướng dẫn viên

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
public function update_guide() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(!isset($_POST['guide_id']) || empty($_POST['guide_id'])) {
            echo "Lỗi: Không tìm thấy guide_id";
            exit();
        }

        $guide = new Guide();
        $guide->guide_id = $_POST['guide_id'];
        $guide->full_name = $_POST['full_name'] ?? "";
        $guide->birth_date = $_POST['birth_date'] ?? "";
        $guide->contact = $_POST['contact'] ?? "";
        $guide->certificate = $_POST['certificate'] ?? "";
        $guide->languages = $_POST['languages'] ?? "";
        $guide->experience = $_POST['experience'] ?? "";
        $guide->health_condition = $_POST['health_condition'] ?? "";
        $guide->rating = $_POST['rating'] ?? 0;
        $guide->category = $_POST['category'] ?? 1;

        // Xử lý ảnh
        if(isset($_FILES['photo']['name']) && $_FILES['photo']['error'] === 0){
            $file_name = 'uploads/' . time() . '_' . $_FILES['photo']['name'];
            move_uploaded_file($_FILES['photo']['tmp_name'], $file_name);
            $guide->photo = $file_name;
        } else {
            $guide->photo = $_POST['old_photo'] ?? null;
        }

        $this->modelTourGuide->update_guide($guide);
        header("Location: ?act=guideadmin");
        exit();
    }

    // Lấy dữ liệu HDV để fill form
    if(isset($_GET['id'])) {
        $guide_id = $_GET['id'];
        $guide = $this->modelTourGuide->findById($guide_id); // Dùng guide_id
        if(!$guide) {
            echo "Không tìm thấy hướng dẫn viên.";
            exit();
        }
        include "views/admin/guideadmin/update_guide.php";
        exit();
    }
}

// đặt tour
public function booking()
{
    $booking = $this->BookingModel->all();
    include "views/admin/booking/noidung.php";
}

public function createbooking()
{
    $tours = $this->modelTour->all();
    $guides = $this->modelTourGuide->allguide();
    $departures = $this->DepartureModel->all();
    $customers = $this->modelCustomer->allcustomer(); // Lấy tất cả khách

    // Kiểm tra khách đã có tour trùng ngày (nếu quay lại form)
    $customers_with_status = [];
    $departure_id = $_SESSION['old']['departure_id'] ?? 0;
    foreach ($customers as $c) {
        $c['hasBooking'] = $this->BookingModel->customerHasBooking($c['customer_id'], $departure_id);
        $customers_with_status[] = $c;
    }

    include "views/admin/booking/createbooking.php";
}
public function storebooking()
{
    $tour_id      = $_POST['tour_id'] ?? null;
    $guide_id     = $_POST['guide_id'] ?? null;
    $departure_id = $_POST['departure_id'] ?? null;
    $booking_date = $_POST['booking_date'] ?? date('Y-m-d');
    $booking_type = $_POST['booking_type'] ?? 1;
    $status       = $_POST['status'] ?? 4;
    $notes        = $_POST['notes'] ?? '';
    $meeting_point = $_POST['meeting_point'] ?? '';

    $customer_ids = $_POST['customer_ids'] ?? []; // khách cũ
    $new_name     = $_POST['new_customer_name'] ?? '';
    $new_contact  = $_POST['new_customer_contact'] ?? '';
    $new_gender   = $_POST['new_customer_gender'] ?? '';
    $new_birth    = $_POST['new_customer_birth_year'] ?? '';
    $new_id_number= $_POST['new_customer_id_number'] ?? '';
    $new_special  = $_POST['new_customer_special_request'] ?? '';

    // --- Thêm khách mới nếu có ---
    if (!empty($new_name)) {
        $existing = $this->modelCustomer->findByContact($new_contact);
        if ($existing) {
            $customer_ids[] = $existing['customer_id'];
        } else {
            $newCustomer = new Customer();
            $newCustomer->full_name       = $new_name;
            $newCustomer->contact         = $new_contact;
            $newCustomer->gender          = $new_gender;
            $newCustomer->birth_year      = $new_birth ?: null;
            $newCustomer->id_number       = $new_id_number;
            $newCustomer->special_request = $new_special;
            $newCustomer->payment_status  = 0;

            $new_id = $this->modelCustomer->create_customer($newCustomer);
            if ($new_id) {
                $customer_ids[] = $new_id;
            }
        }
    }

    // --- Kiểm tra nếu không có khách ---
    if (empty($customer_ids)) {
        $_SESSION['error'] = "Vui lòng chọn hoặc thêm khách hàng!";
        $_SESSION['old'] = $_POST;
        header("Location: ?act=createbooking");
        exit;
    }

    // --- Tạo mới departure nếu chưa có ---
    if (!$departure_id && !empty($_POST['departure_date'])) {
        $departureData = [
            'tour_id'        => $tour_id,
            'departure_date' => $_POST['departure_date'],
            'return_date'    => $_POST['return_date'],
            'meeting_point'  => $meeting_point,
        ];
        $departure_id = $this->DepartureModel->insert($departureData);
    }

    // --- Kiểm tra khách trùng lịch ---
    $valid_customers = [];
    $conflict_customers = [];
    foreach ($customer_ids as $cid) {
        if ($this->BookingModel->customerHasBooking($cid, $departure_id)) {
            $conflict_customers[] = $cid;
        } else {
            $valid_customers[] = $cid;
        }
    }

    if (empty($valid_customers)) {
        $_SESSION['error'] = "Tất cả khách đã có tour trùng ngày!";
        $_SESSION['old'] = $_POST;
        header("Location: ?act=createbooking");
        exit;
    }

    if (!empty($conflict_customers)) {
        $_SESSION['warning'] = "Một số khách đã có tour trùng ngày và sẽ không được thêm: " . implode(', ', $conflict_customers);
    }

    // --- Lưu booking ---
    $bookingData = [
        'tour_id'      => $tour_id,
        'guide_id'     => $guide_id,
        'departure_id' => $departure_id,
        'booking_date' => $booking_date,
        'num_people'   => count($valid_customers),
        'booking_type' => $booking_type,
        'status'       => $status,
        'notes'        => $notes,
        'customer_ids' => $valid_customers,
    ];

    $this->BookingModel->insert($bookingData);

    $_SESSION['success'] = "Tạo booking thành công!";
    header("Location: ?act=booking");
    exit;
}



public function updatebooking()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $booking_id = $_POST['booking_id'];
        $booking = $this->BookingModel->getBookingFullDetail($booking_id);

        if (!$booking || $booking['status'] != 0) {
            $_SESSION['error'] = "Chỉ có booking chờ xác nhận mới được sửa!";
            header("Location: ?act=booking");
            exit;
        }

        // Lấy danh sách khách được chọn
        $customer_ids = $_POST['customer_ids'] ?? [];

        // Nếu có khách mới
        if(!empty($_POST['new_customer_name'])){
            $customer = new Customer();
            $customer->full_name = $_POST['new_customer_name'];
            $customer->gender    = $_POST['new_customer_gender'] ?? 0;
            $customer->birth_year= $_POST['new_customer_birth'] ?? null;
            $customer->id_number = $_POST['new_customer_id'] ?? '';
            $customer->contact   = $_POST['new_customer_contact'] ?? '';
            $customer->payment_status = $_POST['new_customer_payment'] ?? 0;
            $customer->special_request = $_POST['new_customer_request'] ?? '';

            // Lưu khách mới và lấy ID
            $newCustomerId = $this->modelCustomer->create_customer($customer);
            $customer_ids[] = $newCustomerId;
        }

        // Chuẩn bị dữ liệu cho update
        $data = [
            'booking_id'   => $booking_id,
            'tour_id'      => $_POST['tour_id'],
            'guide_id'     => $_POST['guide_id'] ?? null,
            'departure_id' => $_POST['departure_id'] ?? null, // chỉ dùng departure_id, không dùng departure_date
            'booking_date' => $_POST['booking_date'],
            'num_people'   => count($customer_ids),
            'booking_type' => $_POST['booking_type'] ?? 1,
            'status'       => 0,
            'notes'        => $_POST['notes'] ?? '',
            'customer_ids' => $customer_ids
        ];

        try {
            $this->BookingModel->update($data);
            $_SESSION['success'] = "Cập nhật booking thành công!";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header("Location: ?act=booking");
        exit;
    }

    // Hiển thị form
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $booking = $this->BookingModel->getBookingFullDetail($id);

        if (!$booking || $booking['status'] != 0) {
            $_SESSION['error'] = "Chỉ có booking chờ xác nhận mới được sửa!";
            header("Location: ?act=booking");
            exit;
        }

        $tours = $this->modelTour->all();
        $guides = $this->modelTourGuide->allguide();
        $departures = $this->DepartureModel->all();
        $customers = $this->modelCustomer->allcustomer();

        $_SESSION['old'] = [
            'tour_id' => $booking['tour_id'],
            'guide_id'=> $booking['guide_id'],
            'departure_id'=> $booking['departure_id'],
            'booking_date'=> $booking['booking_date'],
            'booking_type'=> $booking['booking_type'],
            'status' => $booking['status'],
            'notes'  => $booking['notes'],
            'customer_ids' => $booking['customer_ids'] ?? []
        ];

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
    $booking = $this->BookingModel->getBookingFullDetail($id);

    if (!$booking) {
        echo "Không tìm thấy booking #$id";
        exit;
    }

    include "views/admin/booking/detail.php"; 
}

public function deletebooking()
{
    if (!isset($_GET['id'])) {
        header("Location: ?act=booking");
        exit;
    }

    $id = $_GET['id'];

    // gọi model xóa booking
    $this->BookingModel->delete($id);

    $_SESSION['success'] = "Xóa đặt tour thành công!";
    header("Location: ?act=booking");
    exit;
}

public function addcustomerajax()
{
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? '';
    $address = $data['address'] ?? '';
    $dob = $data['dob'] ?? '';

    if(!$name){
        echo json_encode(['success'=>false, 'error'=>'Vui lòng nhập tên khách']); exit;
    }

    $existing = $this->CustomerModel->findByEmailOrPhone($email, $phone);
    if($existing){
        echo json_encode(['success'=>true, 'customer_id'=>$existing['customer_id']]);
        exit;
    }

    $new_id = $this->CustomerModel->insert([
        'full_name' => $name,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'dob' => $dob
    ]);

    echo json_encode(['success'=>true, 'customer_id'=>$new_id]);
}




// khách hàng 


 // Hiển thị danh sách khách
public function customer() {
    $customers = $this->modelCustomer->allcustomer();
    include "views/admin/customer/noidung.php";
}

// Thêm khách hàng
public function create_customer() {
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $customer = new Customer();
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
            $error = $e->getMessage();
        }
    }

    include "views/admin/customer/create_customer.php";
}

// Cập nhật khách hàng
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

        $this->modelCustomer->update_customer($customer);
        header("Location: ?act=customer");
        exit();
    }

    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $customer = $this->modelCustomer->find_customer($id);
        if (!$customer) {
            echo "Không tìm thấy khách hàng";
            exit();
        }
        include "views/admin/customer/update_customer.php";
    }
}

// Xóa khách hàng
public function delete_customer() {
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $this->modelCustomer->delete_customer($id);
    }
    header("Location: ?act=customer");
    exit();
}




// đối tác 

public function partner() {
   
        $partners = $this->PartnerModel->all();
        include "views/admin/partner/noidung.php";
    }


    // danh mục tour
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




 public function delete_danhmuc() {
    $id = $_GET['id'] ?? 0;

    $categoryModel = new CategoryModel();

    // Chặn xóa nếu đang được sử dụng
    if ($categoryModel->isUsed($id)) {
        $_SESSION['error'] = "❌ Không thể xóa danh mục vì đang được sử dụng bởi tour!";
        header("Location: ?act=category");
        exit;
    }

    // Xóa khi không bị ràng buộc
    $categoryModel->delete($id);
    $_SESSION['success'] = "✔ Xóa danh mục thành công!";
    header("Location: ?act=category");
    exit;
}

// tài khoản 

public function accoun() {
    $err = "";
    $accounts = $this->UserModel->all(); 

    $ketqua = []; // khởi tạo mảng kết quả

    if (isset($_POST['tim'])) {
        $user = trim($_POST['user'] ?? '');
        if ($user === '') {
            $err = "Bạn chưa nhập tên người dùng";
        }

        foreach ($accounts as $tt) {
            // Tìm theo email hoặc full_name
            if (stripos($tt->email, $user) !== false || stripos($tt->full_name, $user) !== false) {
                $ketqua[] = $tt;
            }
        }

        if (empty($ketqua)) {
            $err = "Không tìm thấy";
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