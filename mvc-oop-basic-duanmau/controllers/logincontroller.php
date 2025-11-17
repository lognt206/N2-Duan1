<?php
// require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/UserModel.php';
// require_once __DIR__ . '/../models/CommentModel.php';
// require_once __DIR__ . '/../models/CategoryModel.php';
class logincontroller {
    // public $productModel;
    public $userModel;
    // public $commentModel;
    // public $categoryModel;

    public function __construct(){
        // $this->productModel  = new ProductModel();
        $this->userModel     = new UserModel();
        // $this->commentModel  = new CommentModel();
        // $this->categoryModel = new CategoryModel();
    }


 public function home(){
        include "views/dangnhap/home.php";
    }
  public function login(){
    session_start();
    $loi = "";
    if(isset($_POST['dangnhap'])){
        $email    = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Lấy user theo email
        $user = $this->userModel->findByEmail($email);

        if($user){
            // Kiểm tra tài khoản đã kích hoạt (status = 1)
            if(isset($user->status) && $user->status != 1){
                $loi = "Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị.";
            } else {
                // Kiểm tra mật khẩu
                if(( $user->password)){ //password_verify
                    // Lưu thông tin user vào session
                    $_SESSION['user'] = [
                        'user_id'   => $user->user_id,
                        'username' => $user->username,
                        'role' => $user->role
                    ];
                    // Chuyển hướng theo role
                    if($user->role == 1){
                        header("Location:?act=dashboard");
                        exit;
                    } else {
                        header("Location:?act=header");
                        exit;
                    }
                } else {
                    $loi = "Mật khẩu không đúng.";
                }
            }
        } else {
            $loi = "Email không tồn tại.";
        }
    }
    include "views/dangnhap/login.php";
}



    public function dangxuat(){
        session_start();
        session_destroy();
        header("Location: ?act=login"); 
        exit;
    }
}
?>