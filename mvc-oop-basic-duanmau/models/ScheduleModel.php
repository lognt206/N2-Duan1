<?php
class ScheduleModel {
    public $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy lịch làm việc của hướng dẫn viên dựa vào user_id trong session
    public function getByGuide($user_id) {
        // Lấy guide_id thực sự từ bảng guide
        $sqlGuide = "SELECT guide_id FROM guide WHERE user_id = :user_id LIMIT 1";
        $stmtGuide = $this->conn->prepare($sqlGuide);
        $stmtGuide->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmtGuide->execute();
        $guide = $stmtGuide->fetch(PDO::FETCH_ASSOC);

        if (!$guide) {
            return []; // Không tìm thấy guide
        }

        $guide_id = $guide['guide_id'];

        // Lấy lịch tour của guide
        $sql = "SELECT 
                    s.schedule_id,
                    s.status AS schedule_status,
                    s.created_at,
                    d.departure_id,
                    d.departure_date,
                    d.return_date,
                    d.meeting_point,
                    t.tour_id,
                    t.tour_name,
                    t.price,
                    t.status AS tour_status
                FROM schedule s
                JOIN departure d ON s.departure_id = d.departure_id
                JOIN tour t ON s.tour_id = t.tour_id
                WHERE s.guide_id = :guide_id
                ORDER BY d.departure_date ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':guide_id', $guide_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
