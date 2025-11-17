<?php
require_once __DIR__ . '/../models/User.php'; 

class LoginController {
    public $UserModel;
    public $ProductModel;

    public function __construct() {
        $this->UserModel = new UserModel();
        $this->ProductModel = new ProductModel();
    }

    public function home() {
        include "views/login/home.php";
    }
    public function login() {
        session_start();
        $loi = "";
        if (isset($_POST['dangnhap'])) {
            $email    = trim($_POST['email']);
            $password = trim($_POST['password']);

            // Lấy user theo email
            $user = $this->UserModel->findByEmail($email);

            if($user){
                // kiểm tra tài khoản đã kích hoạt (status = 1)
                if(isset($user->status) && $user->status != 1){
                    $loi = "Tai khoan da bi khoa. Vui long lien he quan tri.";
                } else {
                    // kiểm tra mật khau
                    if(password_verify($password, $user->password)){
                        // lưu thống tin user vào session
                        $_SESSION['user'] = [
                            'id'   => $user->id,
                            'name' => $user->name,
                            'role' => $user->role
                        ];
                        // chuyển hướng theo role
                        if($user->role == 0){
                            header("Location:?act=trangchu");
                            exit;
                        } else {
                            header("Location:?act=trangchu");
                            exit;
                        }
                    } else {
                        $loi = "Mat khau khong dung.";
                    }
                }
            } else {
                $loi = "Tai khoan khong ton tai.";
            }
        }
        include "views/login/login.php";
    }
        
    
}
?>