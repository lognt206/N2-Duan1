<?php
require_once __DIR__ . '/../models/ScheduleModel.php';
require_once __DIR__ . '/../models/Guidemodel.php';
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/tourmodel.php';
require_once __DIR__ . '/../models/CustomerModel.php';
require_once __DIR__ . '/../models/TourlogModel.php';
class TourGuideController
{
    public $modelTourGuide;
    public $UserModel;
    public $ScheduleModel;
    public $BookingModel;
    public $modelTour;
    public $modelCustomer;
    public $tourlog;

    public function __construct()
    {
        $this->ScheduleModel = new ScheduleModel();
        $this->modelTourGuide = new TourGuideModel();
        $this->BookingModel = new BookingModel();
        $this->modelTour = new TourModel();
        $this->modelCustomer = new CustomerModel();
        $this->tourlog = new TourlogModel();
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
    // sửa NHẬT KÝ TOUR
    // ----------------------------
    public function edit_nhat_ky()
    {   $log_id = $_GET['id'] ?? null;
        $tour_log = null; // Khởi tạo biến để tránh lỗi Undefined

        if ($log_id) {
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
    public function delete_nhatky(){
        if(isset($_GET['id'])){
            $log_id = (int)$_GET['id'];
            $tourlog = new Tourlog();
            $tourlog->log_id = $log_id;
            $this->tourlog->delete_nhatky($tourlog);
        }
        require_once './views/guide/nhat_ky/edit_nhat_ky.php';
    }
    public function update_nhat_ky()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?act=report');
        exit;
    }

    $log_id = $_POST['log_id'] ?? null;
    $guide_review = $_POST['guide_review'] ?? '';
    $old_photo = $_POST['old_photo'] ?? '';
    $photo_file = $_FILES['photo_file'] ?? null;
    $photo_path = $old_photo; // Mặc định giữ lại ảnh cũ

    if (!$log_id) {
        // Xử lý lỗi nếu thiếu ID
        echo "Lỗi: Thiếu ID Nhật ký Tour để cập nhật.";
        exit;
    }

    // --- Xử lý Upload Ảnh ---
    if ($photo_file && $photo_file['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $file_extension = pathinfo($photo_file['name'], PATHINFO_EXTENSION);
        $new_file_name = "log_" . $log_id . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $new_file_name;
        
        if (move_uploaded_file($photo_file['tmp_name'], $target_file)) {
            $photo_path = $target_file; // Cập nhật đường dẫn ảnh mới
            
            // Xóa ảnh cũ nếu nó tồn tại và không phải là ảnh mặc định
            if ($old_photo && file_exists($old_photo) && $old_photo !== 'uploads/logo.png') {
                unlink($old_photo);
            }
        } else {
            // Xử lý lỗi upload
            echo "Lỗi: Không thể tải ảnh lên.";
            // Bạn có thể chọn dừng hoặc tiếp tục với ảnh cũ
        }
    }
    // -------------------------

    // 4. Gọi Model để cập nhật dữ liệu
    $is_updated = $this->tourlog->updateTourLog(
        $log_id, 
        $guide_review, 
        $photo_path
    );

    if ($is_updated) {
        // Cập nhật thành công, chuyển hướng về trang Nhật ký Tour
        header('Location: index.php?act=report&message=updated_success');
        exit;
    } else {
        // Cập nhật thất bại
        echo "Cập nhật Nhật ký Tour thất bại. Vui lòng thử lại.";
    }
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

        //Nhật ký tour lấy theo departure_id
        $departure_id = $tour_detail_data['departure_id'];
        $tourlogs = $this->tourlog->find_Tour_log($departure_id );
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
