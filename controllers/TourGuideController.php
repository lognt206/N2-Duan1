<?php
require_once __DIR__ . '/../models/ScheduleModel.php';
require_once __DIR__ . '/../models/Guidemodel.php';
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/tourmodel.php';
require_once __DIR__ . '/../models/CustomerModel.php';
class TourGuideController
{
    public $modelTourGuide;
    public $UserModel;
    public $ScheduleModel;
    public $BookingModel;
    public $modelTour;
    public $modelCustomer;

    public function __construct()
    {
        $this->ScheduleModel = new ScheduleModel();
        $this->modelTourGuide = new TourGuideModel();
        $this->BookingModel = new BookingModel();
        $this->modelTour = new TourModel();
        $this->modelCustomer = new CustomerModel();
    }

    // ----------------------------
    // DASHBOARD
    // ----------------------------
    public function header()
    {
        $title = "Trang chủ hướng dẫn viên";
        require_once './views/guide/header.php';
    }

    public function schedule() {
    session_start();

    // Lấy guide_id từ session (hoặc mặc định 3 nếu chưa login)
    $guideId = $_SESSION['user']['guide_id'] ?? 3;

    // Lấy lịch làm việc từ model 1 lần duy nhất
    $tours = $this->ScheduleModel->getByGuide($guideId);

    $today = date('Y-m-d');

    // Thêm trạng thái cho từng tour
    foreach ($tours as $tour) {
        $bdau  = $tour['departure_date'] ?? null;
        $kthuc = $tour['return_date'] ?? null;
        $scheduleStatus = $tour['schedule_status'] ?? null;

        if ($scheduleStatus == 3) {
            $tour['tinh_trang'] = "Đã hủy";
            $tour['trangthai'] = "cancelled";
        } elseif (!$bdau || !$kthuc) {
            $tour['tinh_trang'] = "Chưa có dữ liệu";
            $tour['trangthai'] = "unknown";
        } elseif ($kthuc < $today) {
            $tour['tinh_trang'] = "Đã hoàn thành";
            $tour['trangthai'] = "completed";
        } elseif ($bdau <= $today && $kthuc >= $today) {
            $tour['tinh_trang'] = "Đang thực hiện";
            $tour['trangthai'] = "in_progress";
        } else {
            $tour['tinh_trang'] = "Sắp khởi hành";
            $tour['trangthai'] = "upcoming";
        }
    }

    // Gửi dữ liệu sang view
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
        //lấy user_id đã lưu khi login vào
        $user_id = $_SESSION['user']['user_id'];
        $tourguideModel = new TourGuideModel();
        $tourguide = $this->modelTourGuide->find_guide($user_id);
        require_once './views/guide/thong_tin_ca_nhan/profile.php';
    }

    // ----------------------------
    // NHẬT KÝ TOUR
    // ----------------------------
    public function report()
    {   
        require_once './views/guide/nhat_ky/report.php';
    }

    // ----------------------------
    // CHI TIẾT TOUR
    // ----------------------------
    public function tour_detail()
    {
        $booking_id = $_GET['id'] ?? null;
        if($booking_id){
            $tour_detail_data = $this->BookingModel->detail($booking_id);
        }else{
            echo "Không tìm thấy Chi tiết tour";
            exit;
        }

        require_once './views/guide/lich_lam_viec/tour_detail.php';
    }

    // ----------------------------
    // CHECK IN TOUR
    // ----------------------------
    public function check_in($tour_id = null)
    {   
        if($tour_id === null){
            $tour_id=$_GET['tour_id'] ?? null;
        }
        if($tour_id === null){
            echo "Không tìm thấy ID tour";
            exit;
        }
       $tour_detail = $this->BookingModel->detail($tour_id);

        // Nếu null/false thì gán mảng rỗng để tránh cảnh báo
        $tour_detail_data = $tour_detail ?: [];
        
        require_once './views/guide/lich_lam_viec/check_in.php';
    }

    // ----------------------------
    // YÊU CẦU ĐẶC BIỆT
    // ----------------------------
    public function special_request()
    {
        require_once './views/guide/yeu_cau_dbiet/special_request.php';
    }
}
