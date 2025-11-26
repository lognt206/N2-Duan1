<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/Guidemodel.php';

class logincontroller {

    public $userModel;
    public $guideModel;

    const ROLE_ADMIN = 1;
    const ROLE_GUIDE = 2;

    public function __construct() {
        $this->userModel  = new UserModel();
        $this->guideModel = new TourGuideModel();
    }

    public function home() {
        include "views/dangnhap/home.php";
    }

    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $loi = "";

        if(isset($_POST['dangnhap'])) {
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if($email === '' || $password === '') {
                $loi = "Vui lòng nhập email và mật khẩu.";
                include "views/dangnhap/login.php";
                return;
            }

            // Lấy thông tin user theo email
            $user = $this->userModel->findByEmail($email);

            if(!$user) {
                $loi = "Email không tồn tại.";
                include "views/dangnhap/login.php";
                return;
            }

            // Kiểm tra mật khẩu (hash)
            if (!password_verify($password, $user->password)) {
                $loi = "Mật khẩu không đúng.";
                include "views/dangnhap/login.php";
                return;
            }

            // --- Lưu session chung ---
            $_SESSION['user'] = [
                'user_id'   => $user->user_id,
                'role'      => (int)$user->role,
                'full_name' => $user->full_name,
                'email'     => $user->email
            ];

            // Nếu là hướng dẫn viên
            if($_SESSION['user']['role'] === self::ROLE_GUIDE) {
                $guide = $this->guideModel->getByUserId($user->user_id);

                if($guide) {
                    $guideObj = is_array($guide) ? (object)$guide : $guide;
                    $_SESSION['user']['full_name'] = $guideObj->full_name;
                    $_SESSION['user']['guide_id']  = $guideObj->guide_id;
                    $_SESSION['user']['photo']     = $guideObj->photo ?? null;
                    $_SESSION['user']['languages'] = $guideObj->languages ?? null;
                }
            }

            // Chuyển hướng theo role
            if($_SESSION['user']['role'] === self::ROLE_ADMIN) {
                header("Location:?act=dashboard");
                exit;
            } else {
                header("Location:?act=header");
                exit;
            }
        }

        include "views/dangnhap/login.php";
    }

    public function dangxuat() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: ?act=login");
        exit;
    }
}
?>
