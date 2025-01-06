<?php
    class ScheduledModel {
        private $connection;

        public function __construct($connentionDB) {
            $this->connection = $connentionDB;
        }
        
        public function getAllTime() {
            $query =  "SELECT * FROM giohoc";
            $result = $this->connection->query($query);
            if (!$result) {
                throw new Exception("Query failed: " . $this->connection->error);
            }
            return $result; 
        }

        public function getAllWeek() {
            $query =  "SELECT * FROM tuanhoc";
            $result = $this->connection->query($query);
            if (!$result) {
                throw new Exception("Query failed: " . $this->connection->error);
            }
            return $result; 
        }

        public function getAllDayByIdWeek($idweek) {
            $query = "SELECT ngayhoc.ID, ngayhoc.TenNgay FROM ngayhoc JOIN tuanhoc ON tuanhoc.ID = ngayhoc.ID_TuanHoc WHERE tuanhoc.ID = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("i", $idweek);
        
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        public function getClassByIdDayandHour($id_day, $id_giohoc) {
            $query = "SELECT lop.ID AS LopID, lop.TenLop, giohoc.GioBatDau, giohoc.GioKetThuc, ngayhoc.TenNgay
                        FROM lop
                        JOIN giohoc ON lop.ID_GioHoc = giohoc.ID
                        JOIN quanlyngayhoc ON lop.ID = quanlyngayhoc.ID_Lop
                        JOIN ngayhoc ON quanlyngayhoc.ID_NgayHoc = ngayhoc.ID
                        WHERE giohoc.ID = ? AND ngayhoc.ID = ?;";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ii", $id_giohoc, $id_day);           
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        public function getClassByIdDay($id_day) {
            $query = "SELECT lop.ID, lop.TenLop, giohoc.GioBatDau, giohoc.GioKetThuc
                        -- AS LopID, TenLop, GioBatDau, GioKetThuc
                        FROM lop
                        JOIN giohoc ON lop.ID_GioHoc = giohoc.ID
                        JOIN quanlyngayhoc ON lop.ID = quanlyngayhoc.ID_Lop
                        JOIN ngayhoc ON quanlyngayhoc.ID_NgayHoc = ngayhoc.ID
                        WHERE ngayhoc.ID = ?;";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("i", $id_day);           
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function changeDayToId($today) {
            $query = "SELECT ID FROM `ngayhoc` WHERE TenNgay = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("s", $today);           
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row ? $row['ID'] : null;
        }
    }
?>