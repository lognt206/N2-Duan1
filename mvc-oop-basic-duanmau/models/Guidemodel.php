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

    public function find_guide($user_id){
        try{
            // ? vì PDO tự động điền user_id khi hdv đăng nhập vào
            $sql = "SELECT * FROM `tourguide` WHERE user_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$user_id]);
            $guide = $stmt->fetch(PDO::FETCH_ASSOC);
            return $guide;
        }catch (PDOException $err) {
            echo "Lỗi : " . $err->getMessage();
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

}
?>
