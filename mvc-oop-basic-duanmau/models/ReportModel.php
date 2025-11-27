<?php
class ReportModel {
    
public $conn;

    public function __construct() {
        $this->conn = connectDB();
    }
    public function createReport() {
        $sql = "
            INSERT INTO report (tour_id, total_revenue, total_expense, report_date)
            SELECT 
                t.tour_id,
                IFNULL(r1.total_revenue, 0),
                IFNULL(r2.total_expense, 0),
                CURDATE()
            FROM tour t
            LEFT JOIN (
                SELECT tour_id, SUM(num_people * price) AS total_revenue
                FROM booking
                WHERE status = 1
                GROUP BY tour_id
            ) r1 ON r1.tour_id = t.tour_id
            LEFT JOIN (
                SELECT tour_id, SUM(amount) AS total_expense
                FROM tour_cost
                GROUP BY tour_id
            ) r2 ON r2.tour_id = t.tour_id;
        ";

        return $this->pdo_execute($sql);
    }

    public function listReport() {
        return $this->pdo_query("
            SELECT r.*, t.tour_name 
            FROM report r 
            JOIN tour t ON r.tour_id = t.tour_id
            ORDER BY r.report_date DESC
        ");
    }
}
