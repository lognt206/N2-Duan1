<?php 
class Departure{
    public $departure_id;
    public $tour_id;
    public $departure_date;   // sửa từ departure_name
    public $return_date;
    public $meeting_point;
    public $guide_id;
    public $notes;
     public $tour_name;
}

class DepartureModel{
    private $conn;

    public function __construct(){
        $this->conn = connectDB();
    }

    // Lấy tất cả lịch khởi hành
    public function all(){
        try {
            $sql = "SELECT d.*, t.tour_name 
            FROM departure d 
            LEFT JOIN tour t ON d.tour_id = t.tour_id";
            $data = $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            $list = [];
            foreach($data as $row){
                $dep = new Departure();
                $dep->departure_id   = $row['departure_id'];
                $dep->tour_id        = $row['tour_id'];
                $dep->departure_date = $row['departure_date'] ?? ''; // sửa key
                $dep->return_date    = $row['return_date'] ?? '';
                $dep->meeting_point  = $row['meeting_point'];
                $dep->guide_id       = $row['guide_id'];
                $dep->notes          = $row['notes'];
                $list[] = $dep;
            }
            return $list;
        } catch (PDOException $e){
            echo "Lỗi: " . $e->getMessage();
        }
    }

    // Lấy 1 lịch theo id
    public function find($id){
        try{
            $sql = "SELECT * FROM `departure` WHERE departure_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row){
                $dep = new Departure();
                $dep->departure_id   = $row['departure_id'];
                $dep->tour_id        = $row['tour_id'];
                $dep->departure_date = $row['departure_date'] ?? '';
                $dep->return_date    = $row['return_date'] ?? '';
                $dep->meeting_point  = $row['meeting_point'];
                $dep->guide_id       = $row['guide_id'];
                $dep->notes          = $row['notes'];
                return $dep;
            }
            return null;
        } catch (PDOException $e){
            echo "Lỗi: " . $e->getMessage();
        }
    }

    // Thêm lịch
    public function create(Departure $dep){
        try{
            $sql = "INSERT INTO `departure` (tour_id, departure_date, return_date, meeting_point, guide_id, notes)
                    VALUES (:tour_id, :departure_date, :return_date, :meeting_point, :guide_id, :notes)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':tour_id'        => $dep->tour_id,
                ':departure_date' => $dep->departure_date,
                ':return_date'    => $dep->return_date,
                ':meeting_point'  => $dep->meeting_point,
                ':guide_id'       => $dep->guide_id,
                ':notes'          => $dep->notes
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e){
            echo "Lỗi: " . $e->getMessage();
        }
    }

    // Xóa lịch
    public function delete_departure($id){
        try{
            $sql = "DELETE FROM `departure` WHERE departure_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e){
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }

    // Cập nhật lịch
    public function update(Departure $dep){
        try{
            $sql = "UPDATE `departure` SET
                        tour_id = :tour_id,
                        departure_date = :departure_date,
                        return_date = :return_date,
                        meeting_point = :meeting_point,
                        guide_id = :guide_id,
                        notes = :notes
                    WHERE departure_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':tour_id'        => $dep->tour_id,
                ':departure_date' => $dep->departure_date,
                ':return_date'    => $dep->return_date,
                ':meeting_point'  => $dep->meeting_point,
                ':guide_id'       => $dep->guide_id,
                ':notes'          => $dep->notes,
                ':id'             => $dep->departure_id
            ]);
            return true;
        } catch (PDOException $e){
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }
}
?>
