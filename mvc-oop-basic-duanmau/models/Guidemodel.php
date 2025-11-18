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
        d.departure_id,
        t.tour_name,
        d.departure_date,
        d.return_date,
        d.meeting_point
    FROM departure d
    JOIN tour t ON d.tour_id = t.tour_id
    WHERE d.guide_id = :guideId
    ORDER BY d.departure_date DESC";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['guideId' => $guideId]);
    $lich_lam_viec = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Thêm trạng thái tính toán
    $today = date('Y-m-d');
    foreach($lich_lam_viec as &$tour){
        if($today < $tour['departure_date']){
            $tour['trangthai'] = 'upcoming';
            $tour['tinh_trang'] = 'Sắp khởi hành';
        } elseif($today >= $tour['departure_date'] && $today <= $tour['return_date']){
            $tour['trangthai'] = 'in_progress';
            $tour['tinh_trang'] = 'Đang thực hiện';
        } else {
            $tour['trangthai'] = 'completed';
            $tour['tinh_trang'] = 'Đã hoàn thành';
        }
    }

    return $lich_lam_viec;
}

}
