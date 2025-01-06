<?php
    class StudentsModel {
        private $connection;
    
        public function __construct($connectionDB) {
            $this->connection = $connectionDB;
        }
    
        public function getAllStudentInClass($id_lop) {
            $query = "SELECT hv.ID, hv.Ten, hv.NgaySinh, hv.ID_Lop, hv.Email
                      FROM hocvien AS hv
                      JOIN lop ON hv.ID_Lop = lop.ID
                      WHERE lop.ID = ?";
    
            $stmt = $this->connection->prepare($query);    
            $stmt->bind_param("i", $id_lop);
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        public function countStudents() {
            $query = "SELECT COUNT(*) AS count FROM hocvien";
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $this->connection->error);
            }
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
            $count = 0;
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
            return $count;
        }

        public function showInfoStudent($id_hv) {
            $query = "SELECT * FROM hocvien WHERE ID = ?";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $this->connection->error);
            }
            
            $stmt->bind_param("i", $id_hv);
            
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                throw new Exception("No student found with ID: $id_hv");
            }
            
            return $result->fetch_all(MYSQLI_ASSOC); 
        }

        public function getInfoStudent($id_hv) {
            $query = "SELECT * FROM hocvien WHERE ID = ?";
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $this->connection->error);
            }
            $stmt->bind_param("i", $id_hv);
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
            $result = $stmt->get_result();
            if ($result->num_rows === 0) {
                return null; 
            }
            return $result->fetch_assoc(); // Trả về một dòng
        }

        public function editInfoStudent($id_hv, $ten, $gioitinh, $ngaysinh, $email, $diachi)  {
            $query = "UPDATE hocvien SET 
                            Ten = ?,
                            GioiTinh = ?,
                            NgaySinh = ?,
                            Email = ?,
                            DiaChi = ?
                        WHERE ID = ?";
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $this->connection->error);
            }
            $stmt->bind_param("sssssi",$ten, $gioitinh, $ngaysinh, $email, $diachi, $id_hv);
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
            if ($stmt->affected_rows === 0) {
                throw new Exception("No student found with ID: $id_hv");
            }
            
        }

        public function addNewStudent($ten, $gioitinh, $ngaysinh, $email, $diachi, $id_lop) {
            $query = "INSERT INTO hocvien (Ten, GioiTinh, NgaySinh, Email, DiaChi, ID_Lop) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $this->connection->error);
            }
            $stmt->bind_param("sssssi", $ten, $gioitinh, $ngaysinh, $email, $diachi, $id_lop);
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
            if ($stmt->affected_rows === 0) {
                throw new Exception("No student was added.");
            }
        }

        public function deleteStudent($id_hv) {
            $query = "DELETE FROM hocvien WHERE ID = ?";
            $stmt = $this->connection->prepare($query);
            
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $this->connection->error);
            }
            
            $stmt->bind_param("i", $id_hv);
            
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }            
            return $stmt->affected_rows > 0; 
        }

        public function attendanceStudent($id_qlbh, $id_hv, $status) {
            $query = "INSERT INTO diemdanh (ID_QuanLiNgayHoc, ID_HocVien, TrangThai) VALUES (?, ?, ?)";
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $this->connection->error);
            }
            $stmt->bind_param("iis", $id_qlbh, $id_hv, $status);
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
            if ($stmt->affected_rows === 0) {
                throw new Exception("No student was added.");
            }
        }

        public function getIdQLBH($id_lop, $id_day) {
            $query = "SELECT ID FROM quanlyngayhoc WHERE ID_Lop = ? and ID_NgayHoc = ?";
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $this->connection->error);
            }
            $stmt->bind_param("ii", $id_lop, $id_day);
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
            if ($stmt->affected_rows === 0) {
                throw new Exception("No student was added.");
            }
        }

    }

?>