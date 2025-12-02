<?php
class TourPartner {
    public $tour_id;
    public $partner_id;
}

class TourPartnerModel {
    public $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy danh sách partner của 1 tour
    public function getPartnersByTour($tour_id) {
        $sql = "SELECT p.* 
                FROM tour_partner tp
                JOIN partner p ON tp.partner_id = p.partner_id
                WHERE tp.tour_id = :tour_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['tour_id' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm 1 liên kết tour - partner
    public function addPartnerToTour($tour_id, $partner_id) {
        $sql = "INSERT INTO tour_partner(tour_id, partner_id) VALUES(:tour_id, :partner_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['tour_id' => $tour_id, 'partner_id' => $partner_id]);
    }

    // Xóa tất cả partner của tour
    public function deletePartnersByTour($tour_id) {
        $sql = "DELETE FROM tour_partner WHERE tour_id = :tour_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['tour_id' => $tour_id]);
    }

    // Xóa 1 liên kết cụ thể
    public function deletePartner($tour_id, $partner_id) {
        $sql = "DELETE FROM tour_partner WHERE tour_id = :tour_id AND partner_id = :partner_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['tour_id' => $tour_id, 'partner_id' => $partner_id]);
    }

    // Lấy tất cả tour với partner (dạng mảng tour_id => partner_id[])
    public function allTourPartners() {
        $sql = "SELECT * FROM tour_partner";
        $stmt = $this->conn->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($data as $row) {
            $result[$row['tour_id']][] = $row['partner_id'];
        }
        return $result;
    }
}
?>
