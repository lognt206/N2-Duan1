<?php
require_once __DIR__ . '/../commons/env.php'; // Kết nối DB

class TourDetailModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy tất cả chi tiết theo tour_id
    public function getByTour($tour_id) {
        $sql = "SELECT * FROM tour_details WHERE tour_id = ? ORDER BY detail_id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết theo ID
    public function find($detail_id) {
        $sql = "SELECT * FROM tour_details WHERE detail_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$detail_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm chi tiết tour
    public function insert($data) {
        $sql = "INSERT INTO tour_details (tour_id, title, content) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $data['tour_id'],
            $data['title'],
            $data['content']
        ]);
        return $this->conn->lastInsertId();
    }

    // Cập nhật chi tiết tour
    public function update($data) {
        $sql = "UPDATE tour_details SET title=?, content=? WHERE detail_id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['content'],
            $data['detail_id']
        ]);
    }

    // Xóa chi tiết tour
    public function delete($detail_id) {
        $sql = "DELETE FROM tour_details WHERE detail_id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$detail_id]);
    }
}
?>
