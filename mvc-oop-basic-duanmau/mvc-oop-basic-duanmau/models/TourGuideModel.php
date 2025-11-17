<?php
class TourGuideModel {
    private $conn;
    
    public function __construct() {
        $this->conn = connectDB();
    }
    
    // Lấy tất cả HDV
    public function all() {
        $sql = "SELECT tg.*, u.username, u.email, u.phone 
                FROM tourguide tg 
                LEFT JOIN user u ON tg.user_id = u.user_id 
                ORDER BY tg.guide_id DESC";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    // Lấy HDV theo ID
    public function find($id) {
        $sql = "SELECT tg.*, u.username, u.email, u.phone 
                FROM tourguide tg 
                LEFT JOIN user u ON tg.user_id = u.user_id 
                WHERE tg.guide_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
    
    // Lấy user chưa là HDV
    public function getAvailableUsers() {
        $sql = "SELECT u.* FROM user u 
                WHERE u.user_id NOT IN (SELECT user_id FROM tourguide) 
                AND u.status = 1
                ORDER BY u.full_name";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    // Thêm HDV
    public function create($guide) {
        $sql = "INSERT INTO tourguide (user_id, full_name, birth_date, photo, contact, 
                certificate, languages, experience, health_condition, rating, category) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "issssssssdi", 
            $guide->user_id,
            $guide->full_name,
            $guide->birth_date,
            $guide->photo,
            $guide->contact,
            $guide->certificate,
            $guide->languages,
            $guide->experience,
            $guide->health_condition,
            $guide->rating,
            $guide->category
        );
        
        return mysqli_stmt_execute($stmt);
    }
    
    // Cập nhật HDV
    public function update($guide) {
        $sql = "UPDATE tourguide SET 
                user_id = ?, 
                full_name = ?, 
                birth_date = ?, 
                photo = ?, 
                contact = ?, 
                certificate = ?, 
                languages = ?, 
                experience = ?, 
                health_condition = ?, 
                rating = ?, 
                category = ? 
                WHERE guide_id = ?";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "issssssssdii", 
            $guide->user_id,
            $guide->full_name,
            $guide->birth_date,
            $guide->photo,
            $guide->contact,
            $guide->certificate,
            $guide->languages,
            $guide->experience,
            $guide->health_condition,
            $guide->rating,
            $guide->category,
            $guide->guide_id
        );
        
        return mysqli_stmt_execute($stmt);
    }
    
    // Xóa HDV
    public function delete($id) {
        $sql = "DELETE FROM tourguide WHERE guide_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt);
    }
    
    // Kiểm tra HDV có tour không
    public function hasActiveTours($id) {
        $sql = "SELECT COUNT(*) as count FROM departure 
                WHERE guide_id = ? AND departure_date >= CURDATE()";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['count'] > 0;
    }
    
    // Upload ảnh
    public function uploadPhoto($file) {
        $target_dir = "uploads/guides/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        
        if (in_array($file_extension, $allowed_types)) {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                return $target_file;
            }
        }
        return false;
    }
    
    // Upload chứng chỉ
    public function uploadCertificate($file) {
        $target_dir = "uploads/certificates/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        $allowed_types = array('pdf', 'jpg', 'jpeg', 'png');
        
        if (in_array($file_extension, $allowed_types)) {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                return $target_file;
            }
        }
        return false;
    }
}
?>