<?php


class itinerary {
    public $itinerary_id;
    public $tour_id;
    public $day_number;
    public $start_time;
    public $end_time;
    public $activity;
    public $location;
}

class itineraryModel {
    public $conn;
    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy tất cả itinerary theo tour
    public function getByTour($tour_id) {
        $stmt = $this->conn->prepare("SELECT * FROM itinerary WHERE tour_id=:tour_id ORDER BY day_number");
        $stmt->execute(['tour_id'=>$tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm itinerary
    public function create(Itinerary $it) {
        $stmt = $this->conn->prepare("INSERT INTO itinerary(tour_id, day_number, start_time, end_time, activity, location)
                                      VALUES(:tour_id, :day_number, :start_time, :end_time, :activity, :location)");
        $stmt->execute([
            'tour_id' => $it->tour_id,
            'day_number' => $it->day_number,
            'start_time' => $it->start_time,
            'end_time' => $it->end_time,
            'activity' => $it->activity,
            'location' => $it->location
        ]);
        return $this->conn->lastInsertId();
    }

    // Cập nhật itinerary
    public function update(Itinerary $it) {
        $stmt = $this->conn->prepare("UPDATE itinerary SET 
                                        day_number=:day_number, start_time=:start_time, end_time=:end_time,
                                        activity=:activity, location=:location
                                      WHERE itinerary_id=:itinerary_id");
        $stmt->execute([
            'day_number'=>$it->day_number,
            'start_time'=>$it->start_time,
            'end_time'=>$it->end_time,
            'activity'=>$it->activity,
            'location'=>$it->location,
            'itinerary_id'=>$it->itinerary_id
        ]);
        return $stmt->rowCount();
    }

    // Xóa itinerary
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM itinerary WHERE itinerary_id=:id");
        $stmt->execute(['id'=>$id]);
        return $stmt->rowCount();
    }
}
?>
