<?php  
class Guide {
    public $guide_id;
    public $user_id;
    public $full_name;
    public $birth_date;
    public $photo;
    public $contact;
    public $certificate;
    public $languages;
    public $experience;
    public $health_condition;
    public $rating;
    public $category;
}

class TourGuideModel {
    public $conn;
    public function __construct() {
        $this->conn = connectDB();
    }

    public function allguide() {
        try {
            $sql = "SELECT * FROM `tourguide`";
            $data = $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } catch (PDOException $err) {
            echo "Lỗi : " . $err->getMessage();
            return [];
        }
    }

    public function find_guide($id){
        try{
            // ? vì PDO tự động điền user_id khi hdv đăng nhập vào
            $sql = "SELECT * FROM `tourguide` WHERE user_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $guide = $stmt->fetch(PDO::FETCH_ASSOC);
            return $guide;
        }catch (PDOException $err) {
            echo "Lỗi : " . $err->getMessage();
            return null;
        }
    }

public function findById($guide_id){
    try {
        $sql = "SELECT * FROM `tourguide` WHERE guide_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$guide_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $err) {
        echo "Lỗi: " . $err->getMessage();
        return null;
    }
}


    public function create_guide(Guide $guide) {
        try {
            $sql = "INSERT INTO `tourguide` 
                (`full_name`, `birth_date`, `photo`, `contact`, `certificate`, `languages`, `experience`, `health_condition`, `rating`, `category`, `user_id`)
                VALUES 
                (:full_name, :birth_date, :photo, :contact, :certificate, :languages, :experience, :health_condition, :rating, :category, :user_id)";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':full_name'        => $guide->full_name,
                ':birth_date'       => $guide->birth_date ?: null,
                ':photo'            => $guide->photo ?: null,
                ':contact'          => $guide->contact,
                ':certificate'      => $guide->certificate ?: null,
                ':languages'        => $guide->languages,
                ':experience'       => $guide->experience,
                ':health_condition' => $guide->health_condition ?: null,
                ':rating'           => $guide->rating,
                ':category'         => $guide->category,
                ':user_id'          => $guide->user_id
            ]);
            return $stmt->rowCount();
        } catch (PDOException $err) {
            echo "Lỗi tạo guide: " . $err->getMessage();
            return 0;
        }
    }

    public function update_guide(Guide $guide){                 
        try{
            $id=(int)$guide->guide_id;
            $sql="UPDATE `tourguide` 
                SET 
                    `full_name`         = '".$guide->full_name."',
                    `birth_date`        = '".$guide->birth_date."',
                    `photo`             = '".$guide->photo."',
                    `contact`           = '".$guide->contact."',
                    `certificate`       = '".$guide->certificate."',
                    `languages`         = '".$guide->languages."',
                    `experience`        = '".$guide->experience."',
                    `health_condition`  = '".$guide->health_condition."',
                    `rating`            = '".$guide->rating."',
                    `category`          = '".$guide->category."'
                WHERE `tourguide`.`guide_id` = $id;";
            $data=$this->conn->exec($sql);
            return $data;
        }catch (PDOException $err) {
            echo "Lỗi truy vấn sản phẩm: " . $err->getMessage();
        }
    }

    public function delete_guide($id) {
        $sql = "DELETE FROM `tourguide` WHERE `guide_id` = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }
public function getByUserId($user_id)
{
    $sql = "SELECT * FROM tourguide WHERE user_id = :user_id LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    //lấy customer theo tour
   public function customer_tour($tour_id){
        // schedule -> booking -> customer_group -> customer
        // tìm tour hdv đc phân công-> xem có booking nào -> có group_id nào -> khách
        $sql = "SELECT c.full_name, c.gender, c.birth_year, c.contact, c.payment_status, c.special_request
                FROM schedule s
                JOIN booking b ON b.tour_id= s.tour_id
                JOIN customer_group cg ON b.group_id=cg.group_id
                JOIN customer c ON c.group_id=cg.group_id
                WHERE b.tour_id = :tour_id AND s.guide_id = :guide_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['tour_id'=>$tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
