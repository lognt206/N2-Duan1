<?php
class BookingModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // 1. Lấy tất cả booking
    public function all()
    {
        $sql = "SELECT 
                    b.*, 
                    t.tour_name,
                    c.full_name AS customer_name,
                    g.full_name AS guide_name
                FROM booking b
                JOIN tour t ON b.tour_id = t.tour_id
                JOIN customer c ON b.customer_id = c.customer_id
                LEFT JOIN tourguide g ON b.guide_id = g.guide_id
                ORDER BY b.booking_id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Lấy booking theo ID
    public function find($id)
    {
        $sql = "SELECT 
                    b.*, 
                    t.tour_name,
                    c.full_name AS customer_name,
                    g.full_name AS guide_name
                FROM booking b
                JOIN tour t ON b.tour_id = t.tour_id
                JOIN customer c ON b.customer_id = c.customer_id
                LEFT JOIN tourguide g ON b.guide_id = g.guide_id
                WHERE b.booking_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Thêm booking
    public function insert($data)
    {
        $sql = "INSERT INTO booking 
                    (tour_id, customer_id, guide_id, booking_date, num_people, booking_type, status, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $data['tour_id'],
            $data['customer_id'],
            $data['guide_id'] ?? null,
            $data['booking_date'],
            $data['num_people'],
            $data['booking_type'],
            $data['status'],
            $data['notes']
        ]);

        return $this->conn->lastInsertId();
    }

    // 4. Cập nhật booking
    public function update($data)
    {
        $sql = "UPDATE booking SET 
                    tour_id = ?, 
                    customer_id = ?, 
                    guide_id = ?,
                    booking_date = ?, 
                    num_people = ?, 
                    booking_type = ?, 
                    status = ?, 
                    notes = ?
                WHERE booking_id = ?";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['tour_id'],
            $data['customer_id'],
            $data['guide_id'] ?? null,
            $data['booking_date'],
            $data['num_people'],
            $data['booking_type'],
            $data['status'],
            $data['notes'],
            $data['booking_id']
        ]);
    }

    // 5. Cập nhật trạng thái booking
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE booking SET status=? WHERE booking_id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    // 6. Xóa booking
    public function delete($id)
    {
        $sql = "DELETE FROM booking WHERE booking_id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    // 7. Lấy booking theo customer
    public function getByCustomer($customer_id)
    {
        $sql = "SELECT 
                    b.*, 
                    t.tour_name AS tour_name,
                    g.full_name AS guide_name
                FROM booking b
                JOIN tour t ON b.tour_id = t.tour_id
                LEFT JOIN tourguide g ON b.guide_id = g.guide_id
                WHERE b.customer_id = ?
                ORDER BY b.booking_id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$customer_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
