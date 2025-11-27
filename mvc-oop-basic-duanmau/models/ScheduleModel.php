<?php
class Schedule{
    public $schedule_id;
    public $guide_id;
    public $tour_id;
    public $departure_id;
    public $status;
    public $created_at;
}
class ScheduleModel{
    public $conn;
    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy lịch làm việc của HDV từ booking
    public function getByGuide($guide_id) {
        $sql = "SELECT b.booking_id, b.status AS schedule_status,
                       d.departure_date, d.return_date, d.meeting_point,
                       t.tour_name, t.price, t.status AS tour_status
                FROM booking b
                JOIN departure d ON b.departure_id = d.departure_id
                JOIN tour t ON b.tour_id = t.tour_id
                WHERE b.guide_id = :guide_id
                ORDER BY d.departure_date ASC";


        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['guide_id'=>$guide_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
