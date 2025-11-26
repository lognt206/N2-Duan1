<?php
require_once __DIR__ . '/../models/ScheduleModel.php';

class TourGuideController
{
    public $modelTourGuide;
    public $ScheduleModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->modelTourGuide = new TourGuideModel();
        $this->ScheduleModel = new ScheduleModel();
    }

    // ----------------------------
    // DASHBOARD
    // ----------------------------
    public function header()
    {
        $title = "Trang chủ hướng dẫn viên";
        require_once './views/guide/header.php';
    }

    // ----------------------------
    // LỊCH LÀM VIỆC
    // ----------------------------
    public function schedule()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: ?act=login");
            exit;
        }

        // Lấy ID hướng dẫn viên
        $guide_id = $_SESSION['user']['user_id'];

        // Lấy lịch làm việc từ Model
        $lich_lam_viec = $this->ScheduleModel->getByGuide($guide_id);

        // Gửi sang view
        include "views/guide/lich_lam_viec/schedule.php";
    }

    // ----------------------------
    // TRANG CÁ NHÂN
    // ----------------------------
    public function profile()
    {
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
        require_once './views/guide/lich_lam_viec/tour_detail.php';
    }

    // ----------------------------
    // CHECK IN TOUR
    // ----------------------------
    public function check_in()
    {
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
