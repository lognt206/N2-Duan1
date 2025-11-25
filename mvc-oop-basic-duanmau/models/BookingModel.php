<?php
class BookingModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả booking kèm tên tệp khách (group)
    public function all()
    {
        $sql = "SELECT 
                    b.booking_id,
                    b.booking_date,
                    b.num_people,
                    b.booking_type,
                    b.status,
                    b.notes,
                    t.tour_name,
                    g.full_name AS guide_name,
                    d.departure_date,
                    d.return_date,
                    grp.group_name AS customer_group
                FROM booking b
                JOIN tour t ON b.tour_id = t.tour_id
                LEFT JOIN tourguide g ON b.guide_id = g.guide_id
                LEFT JOIN departure d ON b.departure_id = d.departure_id
                LEFT JOIN customer_group grp ON b.group_id = grp.group_id
                ORDER BY b.booking_id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Chi tiết booking
   public function detail($id)
{
    $sql = "SELECT 
                b.*,
                t.tour_name,
                t.price AS tour_price,
                g.full_name AS guide_name,
                g.contact AS guide_contact,
                d.departure_date,
                d.return_date,
                d.meeting_point,
                b.group_id,
                grp.group_name AS customer_group
            FROM booking b
            JOIN tour t ON b.tour_id = t.tour_id
            LEFT JOIN tourguide g ON b.guide_id = g.guide_id
            LEFT JOIN departure d ON b.departure_id = d.departure_id
            LEFT JOIN customer_group grp ON b.group_id = grp.group_id
            WHERE b.booking_id = ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    // Lấy danh sách khách trong nhóm nếu group_id tồn tại
    if (!empty($booking['group_id'])) {
        $stmt2 = $this->conn->prepare("SELECT full_name FROM customer WHERE group_id = ?");
        $stmt2->execute([$booking['group_id']]);
        $booking['customers'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $booking['customers'] = [];
    }

    return $booking;
}

    // Thêm booking
    public function insert($data)
    {
        $sql = "INSERT INTO booking 
                    (tour_id, guide_id, departure_id, booking_date, num_people, booking_type, status, notes, group_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['tour_id'],
            !empty($data['guide_id']) ? $data['guide_id'] : null,
            !empty($data['departure_id']) ? $data['departure_id'] : null,
            $data['booking_date'] ?? null,
            $data['num_people'] ?? null,
            $data['booking_type'] ?? 1,
            $data['status'] ?? 1,
            $data['notes'] ?? null,
            $data['group_id']
        ]);
    }

    // Cập nhật booking
    public function update($data)
    {
        $sql = "UPDATE booking SET
                    tour_id = ?, 
                    guide_id = ?, 
                    departure_id = ?,
                    booking_date = ?, 
                    num_people = ?, 
                    booking_type = ?, 
                    status = ?, 
                    notes = ?,
                    group_id = ?
                WHERE booking_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['tour_id'],
            !empty($data['guide_id']) ? $data['guide_id'] : null,
            !empty($data['departure_id']) ? $data['departure_id'] : null,
            $data['booking_date'] ?? null,
            $data['num_people'] ?? null,
            $data['booking_type'] ?? 1,
            $data['status'] ?? 1,
            $data['notes'] ?? null,
            $data['group_id'],
            $data['booking_id']
        ]);
    }

    // Xóa booking
    public function delete($id)
    {
        $sql = "DELETE FROM booking WHERE booking_id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
    // Trong BookingModel.php
public function updateStatus($booking_id, $status)
{
    $sql = "UPDATE booking SET status = ? WHERE booking_id = ?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$status, $booking_id]);
}

}
?>
