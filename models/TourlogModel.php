<?php
class Tourlog{
    public $log_id;
    public $departure_id;
    public $log_date;
    public $content;
    public $photo;
    public $guide_review;
}
class TourlogModel{
    public $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }
    public function find_Tour_log($departure_id ){
        try {
            $sql = "SELECT * FROM `tourlog` WHERE departure_id=:departure_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':departure_id' => $departure_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {error_log("Lỗi truy vấn Nhật ký tour: " . $err->getMessage());
            echo "Lỗi : " . $err->getMessage();
            return [];
        }
    }
    public function getTourLogById($logId)
    {
        $sql = "SELECT tl.*, b.booking_id FROM `tourlog` tl
        JOIN booking b ON tl.departure_id= b.departure_id WHERE tl.log_id = :log_id";
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':log_id', $logId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Trả về một hàng dữ liệu duy nhất
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $err) {
            error_log("Lỗi truy vấn getTourLogById: " . $err->getMessage());
            // Trả về null nếu có lỗi
            return null;
        }
    }
    public function updateTourLog($logId, $guideReview, $photoPath)
{
    // Bắt đầu câu lệnh SQL UPDATE
    $sql = "UPDATE `tourlog` SET guide_review = :guide_review";
    
    // Nếu có đường dẫn ảnh mới, thêm cột 'photo' vào câu lệnh UPDATE
    if ($photoPath) {
        $sql .= ", photo = :photo";
    }
    
    // Thêm điều kiện WHERE để cập nhật đúng bản ghi
    $sql .= " WHERE log_id = :log_id";

    try {
        $stmt = $this->conn->prepare($sql);
        
        // Bind các tham số chung
        $stmt->bindParam(':guide_review', $guideReview, PDO::PARAM_STR);
        $stmt->bindParam(':log_id', $logId, PDO::PARAM_INT);
        
        // Bind tham số ảnh nếu có
        if ($photoPath) {
            $stmt->bindParam(':photo', $photoPath, PDO::PARAM_STR);
        }

        // Thực thi
        return $stmt->execute();
        
    } catch (PDOException $err) {
        error_log("Lỗi truy vấn updateTourLog: " . $err->getMessage());
        return false;
    }
}

    public function delete_nhatky(Tourlog $tourlog) {
        $sql="UPDATE `tourlog` SET `photo`=:photo, `guide_review`=:guide_review WHERE log_id = :log_id";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute([':photo' => '',
                ':guide_review' => '',
                ':log_id' => $tourlog->log_id,]);
        return $stmt->rowCount();
    }
    public function create_tourlog($data){
        try{
            $sql="INSERT INTO tourlog (departure_id, log_date, photo, guide_review)
                    VALUES (:departure_id, :log_date, :photo, :guide_review)";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute([
                        'departure_id'  =>(int)$data['departure_id'],
                        'log_date'      =>$data['log_date'], 
                        'photo'         =>$data['photo'], 
                        'guide_review'  =>$data['guide_review'],
                    ]);
                    return true;
        }catch(PDOException $err){
            error_log("Lỗi tạo nhật ký tour: " . $err->getMessage());
            return false;
        }
    }
}
?>