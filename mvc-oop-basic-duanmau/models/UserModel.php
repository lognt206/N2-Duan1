<?php
class User {
    public $user_id;
    public $username;
    public $password;
    public $full_name;
    public $email;
    public $phone;
    public $role;     // 1 = admin, 2 = user
    public $status;   // 1 = hoạt động, 0 = khóa
}

class UserModel{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả tài khoản
    public function all(){
        try{
            $sql = "SELECT * FROM `user`";
            $data = $this->conn->query($sql)->fetchAll();
            $dulieu = [];

            foreach($data as $tt){
                $user = new User();
                $user->user_id   = $tt['user_id'];
                $user->username  = $tt['username'];
                $user->password  = $tt['password'];
                $user->full_name = $tt['full_name'];
                $user->email     = $tt['email'];
                $user->phone     = $tt['phone'];
                $user->role      = (int)$tt['role'];
                $user->status    = (int)$tt['status'] ?? 1;
                $dulieu[] = $user;
            }
            return $dulieu;

        } catch (PDOException $err) {
            echo "Lỗi truy vấn tài khoản: " . $err->getMessage();
        }
    }

    // Tìm tài khoản theo ID
    public function find($id){
        try {
            $sql = "SELECT * FROM user WHERE user_id = :id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                $user = new User();
                $user->user_id   = $data['user_id'];
                $user->username  = $data['username'];
                $user->password  = $data['password'];
                $user->full_name = $data['full_name'];
                $user->email     = $data['email'];
                $user->phone     = $data['phone'];
                $user->role      = (int)$data['role'];
                $user->status    = (int)$data['status'] ?? 1;
                return $user;
            }

            return null;

        } catch (PDOException $err) {
            echo "Lỗi truy vấn: " . $err->getMessage();
            return null;
        }
    }

    // Tạo tài khoản mới
    public function create(User $user){
        try{
            $sql = "INSERT INTO `user`
                    (`username`, `password`, `full_name`, `email`, `phone`, `role`, `status`)
                    VALUES (:username, :password, :full_name, :email, :phone, :role, :status)";

            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                ':username' => $user->username,
                ':password' => $user->password,
                ':full_name' => $user->full_name,
                ':email' => $user->email,
                ':phone' => $user->phone,
                ':role' => $user->role,
                ':status' => $user->status ?? 1
            ]);

        } catch (PDOException $err) {
            echo "Lỗi tạo tài khoản: " . $err->getMessage();
        }
    }

    // Cập nhật trạng thái tài khoản (Active / Inactive)
    public function updateStatus($id, $status)
    {
        try {
            $sql = "UPDATE user SET status = :status WHERE user_id = :id";
            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                ':status' => $status,
                ':id' => $id
            ]);

        } catch (PDOException $e) {
            echo "Lỗi cập nhật trạng thái tài khoản: " . $e->getMessage();
            return false;
        }
    }

    // Tìm tài khoản theo email
    public function findByEmail($email){
        try {
            $sql = "SELECT * FROM user WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':email' => $email]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                $user = new User();
                $user->user_id   = $data['user_id'];
                $user->username  = $data['username'];
                $user->password  = $data['password'];
                $user->full_name = $data['full_name'];
                $user->email     = $data['email'];
                $user->phone     = $data['phone'];
                $user->role      = (int)$data['role'];
                $user->status    = (int)$data['status'] ?? 1;
                return $user;
            }

            return null;

        } catch(PDOException $e){
            echo "Lỗi truy vấn findByEmail: " . $e->getMessage();
            return null;
        }
    }
public function delete($id) {
    try {
        $sql = "DELETE FROM user WHERE user_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
        echo "Lỗi xóa tài khoản: " . $e->getMessage();
        return false;
    }
}



}
