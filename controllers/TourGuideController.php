<?php
require_once __DIR__ . '/../models/ScheduleModel.php';
require_once __DIR__ . '/../models/Guidemodel.php';
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/tourmodel.php';
require_once __DIR__ . '/../models/CustomerModel.php';
require_once __DIR__ . '/../models/itineraryModel.php';
class TourGuideController
{
    public $modelTourGuide;
    public $UserModel;
    public $ScheduleModel;
    public $BookingModel;
    public $modelTour;
    public $modelCustomer;
    public $itineraryModel;

    public function __construct()
    {
        $this->ScheduleModel = new ScheduleModel();
        $this->modelTourGuide = new TourGuideModel();
        $this->BookingModel = new BookingModel();
        $this->modelTour = new TourModel();
        $this->modelCustomer = new CustomerModel();
        $this->itineraryModel = new itineraryModel();
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
            $tours[$i]['tinh_trang'] = "Chưa có dữ liệu";
            $tours[$i]['trangthai'] = "unknown";
        } 
        elseif ($kthuc < $today) {
            $tours[$i]['tinh_trang'] = "Đã hoàn thành";
            $tours[$i]['trangthai'] = "completed";
        } 
        elseif ($bdau <= $today && $kthuc >= $today) {
            $tours[$i]['tinh_trang'] = "Đang thực hiện";
            $tours[$i]['trangthai'] = "in_progress";
        } 
        else {
            $tours[$i]['tinh_trang'] = "Sắp khởi hành";
            $tours[$i]['trangthai'] = "upcoming";
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
    $today= date('Y-m-d');

    if ($booking_id) {

        // SỬA LỖI Ở ĐÂY
        // Dùng đúng hàm có trong BookingModel
        $tour_detail_data = $this->BookingModel->getBookingFullDetail($booking_id);

    } else {
        echo "Không tìm thấy Chi tiết tour";
        exit;
    }

    $bdau= $tour_detail_data['departure_date'] ?? null;
    $kthuc= $tour_detail_data['return_date'] ?? null;
    $tour_detail_data['is_completed'] = false;
    if($bdau && $kthuc){
        if($kthuc<$today){
            $tour_detail_data['is_completed'] = true ;
        }
    }
    // lấy lịch trình theo tour
    $tour_detail_data['itineraries'] = [];
    $tour_id = $tour_detail_data['tour_id'] ?? null;

    if ($tour_id) {
        $tour_detail_data['itineraries'] = $this->itineraryModel->getByTour($tour_id);
    }

    require_once './views/guide/lich_lam_viec/tour_detail.php';
}

    // ----------------------------
    // CHECK IN TOUR
    // ----------------------------
    // public function check_in()
    // {   
    //     session_start();
    //     $tour_id = $_GET['tour_id'] ?? null;
    //     if(!$tour_id){
    //         echo "Không tìm thấy tour";
    //         exit;
    //     }
    //     //sd tourmodel để lấy ra chi tiết tour
    //     $tourModel = new TourModel();
    //     $tour_detail_data = $this->modelTour->find($tour_id);
    //     if(!$tour_detail_data){
    //         echo "Tour không tồn tại";
    //         exit;
    //     }
    //     $customers = $this->modelTourGuide->customer_tour($tour_id);
    //     require_once './views/guide/lich_lam_viec/check_in.php';
    // }

    // ----------------------------
    // YÊU CẦU ĐẶC BIỆT
    // ----------------------------
    public function special_request()
    {
        require_once './views/guide/yeu_cau_dbiet/special_request.php';
    }
}
