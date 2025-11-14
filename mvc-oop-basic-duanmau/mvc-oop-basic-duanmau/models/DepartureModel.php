<?php 
class Departure{
    public $departure_id;
    public $tour_id;
    public $departure_name;
    public $return_date;
    public $meeting_point;
    public $guide_id;
    public $notes;
}
class DepartureModel{
    public $conn;
    public function __construct(){
        $this->conn = connectDB();
    }
    public function all(){
        try {
            $sql = "SELECT * FROM `departure`";
            $data = $this->conn->query($sql)->fetchAll();
            $danhsachdeparture = [];
            foreach($data as $value){
                $departure = new Departure();
                $departure->departure_id          =$value['departure_id'];
                $departure->tour_id               =$value['tour_id'];
                $departure->departure_name        =$value['departure_name'];
                $departure->meeting_point         =$value['meeting_point'];
                $departure->guide_id              =$value['guide_id'];
                $departure->notes                 =$value['notes'];
                $danhsachdeparture[]=$departure;
            }
            return $danhsachdeparture;
        }catch (PDOException $err){
            echo "Lỗi: ". $err->getMessage();
        }
    }

    public function find($id){
        try{
            $sql = "SELECT * FROM `departure` WHERE id = $id";
            $data = $this->conn->query($sql)->fetch();
            if($data !== false){
                $departure = new Departure();
                $departure->departure_id          =$data['departure_id'];
                $departure->tour_id               =$data['tour_id'];
                $departure->departure_name        =$data['departure_name'];
                $departure->meeting_point         =$data['meeting_point'];
                $departure->guide_id              =$data['guide_id'];
                $departure->notes                 =$data['notes'];
                return $departure;
            }
        }catch (PDOException $err){
            echo "Lỗi: ". $err->getMessage();
        }
    }

    public function create(Departure $departure){
        try{
            $sql = "INSERT INTO `departure` (`departure_id`, `tour_id`, `departure_name`, `meeting_point`, `guide_id`, `notes`)
            VALUES (NULL, '".$departure->departure_id."',
                          '".$departure->tour_id."',
                          '".$departure->departure_name."',
                          '".$departure->meeting_point."',
                          '".$departure->guide_id."',
                          '".$departure->notes."',);";
            $data = $this->conn->exec($sql);
            return $data;
        }catch (PDOException $err){
            echo "Lỗi: ". $err->getMessage();
        }
    }
    public function delete_departure($id){
        try{
            $sql = "DELETE FROM departure WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt ->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }catch (PDOException $err){
            echo "Lỗi : " . $err->getMessage();
            return false;
        }
    }
    public function update(Departure $departure){
        try{
            $id = (int)$departure->departure_id;
            $sql = "UPDATE `departure`
            SET `departure_id` = '".$departure->departure_id."',
            `tour_id` = '".$departure->tour_id."',
            `departure_name` = '".$departure->departure_name."',
            `meeting_point` = '".$departure->meeting_point."',
            `guide_id` = '".$departure->guide_id."',
            `notes` = '".$departure->notes."'
            WHERE `departure` .`id` = $id;";
            $data = $this->conn->exec($sql);
            return $data;
        }catch (PDOException $err){
            echo "Lỗi : " . $err->getMessage();
        }
    }
}
?>