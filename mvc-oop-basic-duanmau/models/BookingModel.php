<?php
class BookingModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả booking, join cả tour, customer, guide, departure
    public function all()
    {
        $sql = "SELECT 
                    b.*, 
                    t.tour_name,
                    c.full_name AS customer_name,
                    g.full_name AS guide_name,
                    d.departure_date,
                    d.return_date
                FROM booking b
                JOIN tour t ON b.tour_id = t.tour_id
                JOIN customer c ON b.customer_id = c.customer_id
                LEFT JOIN tourguide g ON b.guide_id = g.guide_id
                LEFT JOIN departure d ON b.departure_id = d.departure_id
                ORDER BY b.booking_id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy booking theo ID
    public function find($id)
    {
        $sql = "SELECT 
                    b.*, 
                    t.tour_name,
                    c.full_name AS customer_name,
                    g.full_name AS guide_name,
                    d.departure_date,
                    d.return_date
                FROM booking b
                JOIN tour t ON b.tour_id = t.tour_id
                JOIN customer c ON b.customer_id = c.customer_id
                LEFT JOIN tourguide g ON b.guide_id = g.guide_id
                LEFT JOIN departure d ON b.departure_id = d.departure_id
                WHERE b.booking_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm booking
    public function insert($data)
    {
        $sql = "INSERT INTO booking 
                    (tour_id, guide_id, departure_id, booking_date, num_people, booking_type, status, notes, customer_id)
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
            $data['customer_id'],
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
                    customer_id = ?
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
            $data['customer_id'],
            $data['booking_id']
        ]);
    }

    // Update trạng thái
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE booking SET status=? WHERE booking_id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    // Xóa booking
    public function delete($id)
    {
        $sql = "DELETE FROM booking WHERE booking_id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
public function guideAvailable($guide_id, $departure_id) {
    $sql = "SELECT * FROM booking 
            WHERE guide_id = ? 
            AND departure_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$guide_id, $departure_id]);
    return $stmt->rowCount() == 0; // true nếu HDV rảnh
}
public function customerAvailable($customer_id, $departure_id) {
    $sql = "SELECT * FROM booking 
            WHERE customer_id = ? 
            AND departure_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$customer_id, $departure_id]);
    return $stmt->rowCount() == 0; // true nếu khách rảnh
}



}
