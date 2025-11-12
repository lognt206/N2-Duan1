<?php
class GuideModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db; // $db là kết nối PDO hoặc mysqli
    }

    // Lấy tất cả HDV
    public function getAllGuides() {
        $sql = "SELECT guide_id, full_name, contact as phone, email, category as specialization, 
                       TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) as experience_years, status
                FROM TourGuide";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy HDV theo id
    public function getGuideById($id) {
        $sql = "SELECT * FROM TourGuide WHERE guide_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm HDV
    public function addGuide($data) {
        $sql = "INSERT INTO TourGuide (full_name, birth_date, photo, contact, certificate, languages, experience, health_condition, rating, category)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['full_name'], $data['birth_date'], $data['photo'], $data['contact'], 
            $data['certificate'], $data['languages'], $data['experience'], $data['health_condition'], 
            $data['rating'], $data['category']
        ]);
    }

    // Cập nhật HDV
    public function updateGuide($id, $data) {
        $sql = "UPDATE TourGuide SET full_name=?, birth_date=?, photo=?, contact=?, certificate=?, languages=?, experience=?, health_condition=?, rating=?, category=? WHERE guide_id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['full_name'], $data['birth_date'], $data['photo'], $data['contact'], 
            $data['certificate'], $data['languages'], $data['experience'], $data['health_condition'], 
            $data['rating'], $data['category'], $id
        ]);
    }

    // Xóa HDV
    public function deleteGuide($id) {
        $sql = "DELETE FROM TourGuide WHERE guide_id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
