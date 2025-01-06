<?php
    class AdminModel {
        private $connection;

        public function __construct($connectionDB) {
            $this->connection = $connectionDB;
        }

        public function showAdmin() {
            $query = "SELECT username FROM admin";
            $result = $this->connection->query($query);
            if (!$result) {
                throw new Exception("Query failed: " . $this->connection->error);
            }
            return $result;
        }

        public function login($username, $password) {
            $query = "SELECT * FROM admin WHERE username = ? AND password = ?";
            $stmt = $this->connection->prepare($query);
            if (!$stmt) {
                throw new Exception("Statement preparation failed: " . $this->connection->error);
            }
            $stmt->bind_param("ss", $username, $password);

            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
            $result = $stmt->get_result();
            return $result->num_rows > 0;
        }
    }
?>
