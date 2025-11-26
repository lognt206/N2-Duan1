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
    public function __construct(){
        $this->conn = connectDB();
    }
    public function Scheduleguideid($guide_id){
        $sql = "SELECT 
            s.tour_id AS Ma_Tour, 
            t.tour_name AS Ten_Tour, 
            d.departure_date AS Ngay_Bat_Dau, 
            d.return_date AS Ngay_Ket_Thuc,   
            d.meeting_point AS Dia_Diem_Tap_Trung, 
            s.status AS Trang_Thai 
            FROM `schedule` s
            INNER JOIN `tour` t ON s.tour_id = t.tour_id 
            INNER JOIN `departure` d ON s.departure_id = d.departure_id
            WHERE s.guide_id = :guide_id
            ORDER BY d.departure_date ASC;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['guide_id'=>$guide_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>