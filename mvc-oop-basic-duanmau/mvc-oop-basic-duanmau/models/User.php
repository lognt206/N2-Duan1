<?php
class User {
    public $user_id;
    public $username;
    public $password;
    public $full_name;
    public $email;
    public $phone;
    public $role;     // ADMIN | GUIDE
    public $status;   // Active | Inactive
}

class UserModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy tất cả user
    public function all() {
        try {
            $sql = "SELECT * FROM user";
            $stmt = $this->conn->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $users = [];
            foreach ($data as $row) {
                $user = new User();
                foreach ($row as $key => $value) {
                    if (property_exists($user, $key)) $user->$key = $value;
                }
                $users[] = $user;
            }
            return $users;
        } catch (PDOException $e) {
            error_log("Lỗi truy vấn all(): " . $e->getMessage());
            return [];
        }
    }

    // Tìm user theo ID
    public function find($id) {
        try {
            $sql = "SELECT * FROM user WHERE user_id = :id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $user = new User();
                foreach ($row as $key => $value) {
                    if (property_exists($user, $key)) $user->$key = $value;
                }
                return $user;
            }
            return null;
        } catch (PDOException $e) {
            error_log("Lỗi truy vấn find(): " . $e->getMessage());
            return null;
        }
    }

    // Tạo mới user
    public function create(User $user) {
        try {
            $sql = "INSERT INTO user 
                    (username, password, full_name, email, phone, role, status)
                    VALUES (:username, :password, :full_name, :email, :phone, :role, :status)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':username'  => $user->username,
                ':password'  => $user->password, // nhớ hash trước khi lưu!
                ':full_name' => $user->full_name,
                ':email'     => $user->email,
                ':phone'     => $user->phone,
                ':role'      => $user->role ?? 'GUIDE',
                ':status'    => $user->status ?? 'Active'
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi tạo user: " . $e->getMessage());
            return false;
        }
    }

    // Cập nhật trạng thái
    public function updateStatus($id, $status) {
        try {
            $sql = "UPDATE user SET status = :status WHERE user_id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':status' => $status,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log("Lỗi updateStatus: " . $e->getMessage());
            return false;
        }
    }

    // Tìm user theo email
    public function findByEmail($email) {
        try {
            $sql = "SELECT * FROM user WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':email' => $email]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $user = new User();
                foreach ($row as $key => $value) {
                    if (property_exists($user, $key)) $user->$key = $value;
                }
                return $user;
            }
            return null;
        } catch (PDOException $e) {
            error_log("Lỗi findByEmail: " . $e->getMessage());
            return null;
        }
    }
}
