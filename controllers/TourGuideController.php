<?php
require_once __DIR__ . '/../models/ScheduleModel.php';
require_once __DIR__ . '/../models/Guidemodel.php';
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/tourmodel.php';
require_once __DIR__ . '/../models/CustomerModel.php';
require_once __DIR__ . '/../models/TourlogModel.php';
require_once __DIR__ . '/../models/itineraryModel.php';


class TourGuideController
{
    public $modelTourGuide;
    public $UserModel;
    public $ScheduleModel;
    public $BookingModel;
    public $modelTour;
    public $modelCustomer;
    public $tourlog;

public $itineraryModel;   


    public function __construct()
    {
        $this->ScheduleModel = new ScheduleModel();
        $this->modelTourGuide = new TourGuideModel();
        $this->BookingModel = new BookingModel();
        $this->modelTour = new TourModel();
        $this->modelCustomer = new CustomerModel();
        $this->tourlog = new TourlogModel();
$this->itineraryModel = new ItineraryModel();  
    }

    // ----------------------------
    // DASHBOARD
    // ----------------------------
    public function header()
{
    session_start();
    $title = "Trang chủ hướng dẫn viên";

    $guideId = $_SESSION['user']['guide_id'] ?? 0;
    $today = date('Y-m-d');

    // -------------------------------
    // Tour đã hoàn thành
    // -------------------------------
    $totalCompletedTours = $this->BookingModel->countCompletedToursByGuide($guideId);

    // -------------------------------
    // Tour sắp khởi hành
    // -------------------------------
    // Lấy tất cả tour mà hướng dẫn viên được phân công
    $allTours = $this->ScheduleModel->getByGuide($guideId);

    $totalUpcoming = 0;
    $totalSchedule = 0; // tour đang thực hiện
    $totalReports = 0;

    foreach ($allTours as $tour) {
        $departure = $tour['departure_date'] ?? null;
        $return = $tour['return_date'] ?? null;
        $statusBooking = $tour['status'] ?? 0;

        if (!$departure || !$return) continue;

        // Tour sắp khởi hành
        if ($departure > $today) {
            $totalUpcoming++;
        }
        // Lịch làm việc (tour đang thực hiện)
        elseif ($departure <= $today && $return >= $today) {
            $totalSchedule++;
        }
        // Nhật ký tour (tour đã hoàn thành, dùng status = 1)
        elseif ($return < $today && $statusBooking == 1) {
            $totalReports++;
        }
    }

    require_once './views/guide/header.php';
}


  public function schedule() {
    session_start();

    $guideId = $_SESSION['user']['guide_id'] ?? 0;

    // Lấy lịch làm việc
    $tours = $this->ScheduleModel->getByGuide($guideId);

    $today = date('Y-m-d');

    // FIX FOREACH SAI → SỬA BẰNG KEY
    foreach ($tours as $i => $tour) {
        $bdau  = $tour['departure_date'] ?? null;
        $kthuc = $tour['return_date'] ?? null;

        if (!$bdau || !$kthuc) {
            $tours[$i]['status'] = 0;
        } 
        elseif ($kthuc < $today) {
            $tours[$i]['status'] = 1;
        } 
        elseif ($bdau <= $today && $kthuc >= $today) {
            $tours[$i]['status'] = 2;
        } 
        else {
            $tours[$i]['status'] = 0;
        }

        // ẢNH TOUR – LẤY TỪ cột t.image
        $tours[$i]['tour_image'] = $tour['tour_image'] ?? 'default.png';
    }

    $lich_lam_viec = $tours;

    include "views/guide/lich_lam_viec/schedule.php";
}


    // ----------------------------
    // TRANG CÁ NHÂN
    // ----------------------------
    public function profile()
    {   //ktra đăng nhập, k có user đó thì về login
        session_start();
        if(!isset($_SESSION['user'])){
            header("Location:?act=login");
            exit;
        }
        $guideId = $_SESSION['user']['guide_id'] ?? 0;
        //lấy user_id đã lưu khi login vào
        $user_id = $_SESSION['user']['user_id'];
        $tourguideModel = new TourGuideModel();
        $tourguide = $this->modelTourGuide->find_guide($user_id);
        // -------------------------------
        // Tour đã hoàn thành/ đã dẫn
        // -------------------------------
        $totalCompletedTours = $this->BookingModel->countCompletedToursByGuide($guideId);
        require_once './views/guide/thong_tin_ca_nhan/profile.php';
    }

    // ----------------------------
    // sửa NHẬT KÝ TOUR
    // ----------------------------
    public function edit_nhat_ky()
    {   $log_id = $_GET['id'] ?? null;
        $tour_log = null; // Khởi tạo biến để tránh lỗi Undefined

        if ($log_id) {
            $log_id= (int)$log_id;
            // 2. Lấy dữ liệu nhật ký tour từ Model
            // Gọi hàm trong TourlogModel để lấy chi tiết bản ghi
            $tour_log = $this->tourlog->getTourLogById($log_id); 

            // 3. Kiểm tra dữ liệu trả về
            if (!$tour_log) {
                echo "Không tìm thấy Nhật ký Tour có ID: " . htmlspecialchars($log_id);
                exit;
            }
        } else {
            echo "Thiếu ID Nhật ký Tour.";
            exit;
        }
        require_once './views/guide/nhat_ky/edit_nhat_ky.php';
    }
   public function delete_nhatky()
{
    $log_id = (int)($_GET['id'] ?? 0);
    if ($log_id <= 0) {
        die("Thiếu ID nhật ký");
    }

    $tourlog = $this->tourlog->getTourLogById($log_id);
    if (!$tourlog) {
        die("Nhật ký không tồn tại");
    }

    $booking = $this->BookingModel->findById($tourlog['booking_id']);
    if (!$booking || (int)$booking['status'] !== 2) {
        die("Chỉ được xóa nhật ký khi tour đang thực hiện");
    }

    $this->tourlog->deleteTourLogById($log_id);

    header("Location: index.php?act=tour_detail&id=" . $tourlog['booking_id']);
    exit;
}



   public function update_nhat_ky()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?act=schedule');
        exit;
    }

    $log_id     = (int)($_POST['log_id'] ?? 0);
    $booking_id = (int)($_POST['booking_id'] ?? 0);

    $customer_feedback = $_POST['customer_feedback'] ?? '';
    $guide_review      = $_POST['guide_review'] ?? '';
    $old_photo         = $_POST['old_photo'] ?? '';
    $photo_file        = $_FILES['photo_file'] ?? null;

    if ($log_id <= 0) {
        die("Thiếu ID nhật ký");
    }

    // 🔒 KIỂM TRA TRẠNG THÁI TOUR
    $booking = $this->BookingModel->findById($booking_id);
    if (!$booking || (int)$booking['status'] !== 2) {
        die("Chỉ được sửa nhật ký khi tour đang thực hiện");
    }

    // ---------- UPLOAD ẢNH ----------
    $photo_path = $old_photo;

    if ($photo_file && $photo_file['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $ext = pathinfo($photo_file['name'], PATHINFO_EXTENSION);
        $new_name = "log_" . $log_id . "_" . time() . "." . $ext;
        $target_file = $target_dir . $new_name;

        if (move_uploaded_file($photo_file['tmp_name'], $target_file)) {
            $photo_path = $target_file;

            if ($old_photo && file_exists($old_photo)) {
                unlink($old_photo);
            }
        }
    }

    $updated = $this->tourlog->updateTourLog(
        $log_id,
        $customer_feedback,
        $guide_review,
        $photo_path
    );

    if (!$updated) {
        die("Cập nhật nhật ký thất bại");
    }

    header("Location: index.php?act=tour_detail&id=" . $booking_id);
    exit;
}



    public function create_tourlog_view(){
        $departure_id=$_GET['departure_id'] ?? null;
        $booking_id=$_GET['booking_id'] ?? null;
        if(!$departure_id || !$booking_id){
            echo "Lỗi thiếu id tour hoặc booking";
            exit;
        }
        require_once './views/guide/nhat_ky/create_nhat_ky.php';
    }

    public function create_tourlog()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?act=schedule');
        exit;
    }

    $departure_id = (int)($_POST['departure_id'] ?? 0);
    $booking_id   = (int)($_POST['booking_id'] ?? 0);

    $customer_feedback = $_POST['customer_feedback'] ?? '';
    $customer_rating   = $_POST['customer_rating'] ?? null;
    $guide_review      = $_POST['guide_review'] ?? '';
    $log_date          = date('Y-m-d');

    if ($departure_id <= 0 || $booking_id <= 0) {
        die("Thiếu thông tin tour hoặc booking");
    }

    // 🔒 CHỈ CHO PHÉP TOUR ĐANG THỰC HIỆN
    $booking = $this->BookingModel->findById($booking_id);
    if (!$booking || (int)$booking['status'] !== 2) {
        die("Chỉ được thêm nhật ký khi tour đang thực hiện");
    }

    // ---------- UPLOAD ẢNH ----------
    $photo_path = null;
    if (!empty($_FILES['photo_file']['name'])) {
        $target_dir = "uploads/";
        $ext = pathinfo($_FILES['photo_file']['name'], PATHINFO_EXTENSION);
        $new_name = "log_" . $departure_id . "_" . time() . "." . $ext;
        $target_file = $target_dir . $new_name;

        if (move_uploaded_file($_FILES['photo_file']['tmp_name'], $target_file)) {
            $photo_path = $target_file;
        }
    }

    // ---------- LƯU DB ----------
    $data = [
        'departure_id' => $departure_id,
        'log_date'     => $log_date,
        'content'      => $customer_feedback, // PHẢN HỒI KHÁCH
        'guide_review' => $guide_review,
        'photo'        => $photo_path
    ];

    $saved = $this->tourlog->create_tourlog($data);

    if (!$saved) {
        die("Lỗi lưu nhật ký tour");
    }

    header("Location: index.php?act=tour_detail&id=" . $booking_id . "&tab=report");
    exit;
}




    // ----------------------------
    // CHI TIẾT TOUR
    // ----------------------------
   public function tour_detail()
{
    $booking_id = $_GET['id'] ?? null;
    //cột check-in
    $today = date('Y-m-d');

    if (!$booking_id) {
        echo "Thiếu booking_id";
        exit;
    }

    // Lấy chi tiết booking + tour + customer
    $tour_detail_data = $this->BookingModel->getBookingFullDetail($booking_id);

    if (!$tour_detail_data) {
        echo "Không tìm thấy thông tin tour";
        exit;
    }

    // -------------------------------
    // KIỂM TRA TRẠNG THÁI TOUR
    // -------------------------------
    $status = $tour_detail_data['status'] ?? 0;

    // -------------------------------
    // LẤY NHẬT KÝ TOUR CHỈ KHI ĐÃ HOÀN THÀNH
    // -------------------------------
    $tourlogs = [];
    $statusInt = (int)$status; // đổi sang số để so sánh chuẩn hơn
    if ($statusInt === 1 ||$statusInt === 2) {
        $departure_id = $tour_detail_data['departure_id'] ?? null;
        if ($departure_id) {
            $tourlogs = $this->tourlog->find_Tour_log($departure_id);
        }
    }


    //cột check-in
    $bdau=$tour_detail_data['departure_date'] ?? null;
    $kthuc=$tour_detail_data['return_date'] ?? null;
    $tour_detail_data['is-comleted']=false;
    if($bdau && $kthuc){
        if($kthuc<$today){
            $tour_detail_data['is-comleted']=true;
        }
    }

    // -------------------------------
    // LẤY LỊCH TRÌNH THEO tour_id
    // -------------------------------

    $tour_id = $tour_detail_data['tour_id'] ?? null;
    $itineraries = [];

    if ($tour_id) {
        $itineraries = $this->itineraryModel->getByTour($tour_id);
    }

    $tour_detail_data['itineraries'] = $itineraries;
    $tour_detail_data['tourlogs'] = $tourlogs;

    require_once './views/guide/lich_lam_viec/tour_detail.php';
}


    // ----------------------------
    // CHECK IN TOUR
    // ----------------------------
    public function update_check_in()
    {   
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header("Location: index.php?act=schedule");
            exit();
        }
        $booking_id=$_GET['booking_id'] ?? null;
        $checked_customer_ids = $_POST['check-in'] ?? [];
        if($booking_id){
            echo "lỗi thiếu id booking";
            exit();
        }
        require_once './views/guide/lich_lam_viec/check_in.php';
    }

    // ----------------------------
    // YÊU CẦU ĐẶC BIỆT
    // ----------------------------
    public function special_request()
    {
        require_once './views/guide/yeu_cau_dbiet/special_request.php';
    }

public function finish_tour()
{
    session_start();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?act=schedule");
        exit;
    }

    $booking_id = (int)($_POST['booking_id'] ?? 0);

    if ($booking_id <= 0) {
        echo "Booking không hợp lệ";
        exit;
    }

    $success = $this->BookingModel->finishTourByBooking($booking_id);

    if ($success) {
        $_SESSION['success'] = "Tour đã được kết thúc";
    } else {
        $_SESSION['error'] = "Kết thúc tour thất bại";
    }

    header("Location: index.php?act=tour_detail&id=" . $booking_id);
    exit;
}





}
