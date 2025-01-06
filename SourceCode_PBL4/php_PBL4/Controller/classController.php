<?php
    require_once '../Model/classModel.php';
    require_once '../ConnDB/connDB.php';

    class ClassController {
        private $model;
        public function __construct() {
            global $connectionDB;
            $this->model = new ClassModel($connectionDB); 
        }

        public function getAllClass() {
            try {
                $classes = $this->model->getAllClass();
                return $classes;
            }   
            catch(Exception $e) {
                echo "Error: ",  $e;
                return null;
            }
        }

        public function getClassId($id) {
            try {
                $class = $this->model->getClassById($id);
                return $class['ID']; 
            }
            catch(Exception $e) {
                echo "Error: ",  $e;
                return null;
            }
        }

        public function countClasses() {
            try {
                $countClasses = $this->model->countClasses();
                return [
                    'success' => true,
                    'class_count' => $countClasses
                ];
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
                return [
                    'success' => false,
                    'class_count' => 0
                ];
            }
        }      
    }
?>