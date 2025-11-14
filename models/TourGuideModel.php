<?php 
// Có class chứa các function thực thi tương tác với cơ sở dữ liệu 
class TourGuideModel 
{
    public $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Viết truy vấn danh sách sản phẩm 
    public function getLichLamViec(int $guideId)
    {
        $sql = "SELECT 
            d.departure_id, --mã tour
            t.tour_name, --tên tour
            d.departure_date, --ngày bdau
            d.return_date, -- ngày kthuc
            d.meeting_point --địa điểm tập trung
            FROM departure d
            JOIN tour t ON d.tour_id = t.tour_id
            WHERE d.guide_id = " .$guideId ."
            ORDER  BY d.departure_date DESC;
        ";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
