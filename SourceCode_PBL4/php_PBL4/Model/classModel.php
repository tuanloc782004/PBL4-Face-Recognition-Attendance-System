<?php
    class ClassModel {
        private $connection ;
        public function __construct($connectionDB) {
            $this->connection = $connectionDB;
        }

        public function getAllClass() {
            $query = "SELECT ID, TenLop, ID_CapDo FROM lop ";
            $result = $this->connection->query($query);
            if (!$result) {
                throw new Exception("Query failed: " . $this->connection->error);
            }
            return $result;
        }

        public function getClassById($id) {
            $query = "SELECT * FROM lop WHERE ID = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        public function countClasses() {
            $query = "SELECT COUNT(*) AS count FROM lop";
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
    }
?>