<?php
    require '../Model/studentsModel.php';
    class StudentsController {
        private $model;

        public function __construct($connectionDB) {
            $this->model = new StudentsModel($connectionDB); 
        }

        public function getAllStudentInClass($id_lop) {
            try {
                $students = $this->model->getAllStudentInClass($id_lop);
                return $students; 
            } catch(Exception $e) {
                echo "Error: " . $e->getMessage(); 
                return [];
            }
        }

        public function countStudents() {
            try {
                $countStudent = $this->model->countStudents();
                return [
                    'success' => true,
                    'student_count' => $countStudent
                ];
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
                return [
                    'success' => false,
                    'student_count' => 0
                ];
            }
        }        

        public function showInfoStudent($id_hv) {
            try {
                $students = $this->model->showInfoStudent($id_hv);
                return $students; 
            } catch (Exception $e) {
                error_log("Error fetching student info: " . $e->getMessage());
                return [];
            }
        }

        public function getInfoStudent($id_hv) {
            try {
                $students = $this->model->getInfoStudent($id_hv);
                return $students; 
            } catch (Exception $e) {
                error_log("Error fetching student info: " . $e->getMessage());
                return [];
            }
        }

        public function editInfoStudent($id_hv, $ten, $gioitinh, $ngaysinh, $email, $diachi) {
            try {
                $this->model->editInfoStudent($id_hv, $ten, $gioitinh, $ngaysinh, $email, $diachi);
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        
        public function addNewStudent($ten, $gioitinh, $ngaysinh, $email, $diachi, $id_lop) {
            try {
                $this->model->addNewStudent($ten, $gioitinh, $ngaysinh, $email, $diachi, $id_lop);
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }

        public function deleteStudent($id_hv) {
            try {
                return $this->model->deleteStudent($id_hv); 
            } catch (Exception $e) {
                error_log("Error fetching student info: " . $e->getMessage());
                return false;
            }
        }

        public function attendanceStudent($id_qlbh, $id_hv, $status) {
            try {
                $students = $this->model->attendanceStudent($id_qlbh, $id_hv, $status);
                return $students; 
            } catch (Exception $e) {
                error_log("Error fetching student info: " . $e->getMessage());
                return [];
            }
        }

        public function getIdQLBH($id_lop, $id_day) {
            try {
                $id_qlbh = $this->model->getIdQLBH($id_lop, $id_day);
                return $id_qlbh; 
            } catch (Exception $e) {
                error_log("Error fetching student info: " . $e->getMessage());
                return [];
            }
        }
    }

?>