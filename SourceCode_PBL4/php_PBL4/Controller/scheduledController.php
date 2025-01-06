<?php
    require_once '../Model/scheduledModel.php';
    require_once '../ConnDB/connDB.php';

    class ScheduledController {
        private $model ;

        public function __construct() {
            global $connectionDB;
            $this->model = new ScheduledModel($connectionDB);
        }
        public function getAllTime() {
            try {
                $scheduled = $this->model->getAllTime();
                return $scheduled;
            }
            catch(Exception $e) {
                echo "Error: ",  $e;
                return null;
            }
        }

        public function getAllWeek() {
            try {
                $scheduled = $this->model->getAllWeek();
                return $scheduled;
            }
            catch(Exception $e) {
                echo "Error: ",  $e;
                return null;
            }
        }

        public function getAllDayByIdWeek($idweek) {
            try {
                $days = $this->model->getAllDayByIdWeek($idweek);
                return $days; 
            } catch(Exception $e) {
                echo "Error: " . $e->getMessage(); 
                return [];
            }
        }
        
        public function getClassByIdDayandHour($id_day, $id_giohoc) {
            try {
                $days = $this->model->getClassByIdDayandHour($id_day, $id_giohoc);
                return $days; 
            } catch(Exception $e) {
                echo "Error: " . $e->getMessage(); 
                return [];
            }
            
        }

        public function getClassByIdDay($id_day) {
            try {
                $classes = $this->model->getClassByIdDay($id_day);
                error_log("Classes fetched: " . print_r($classes, true)); // Log the fetched data
                return $classes;
            } catch(Exception $e) {
                echo "Error: " . $e->getMessage();
                return [];
            }
        }

        public function changeDatyToId($today) {
            try {
                $day = $this->model->changeDayToId($today);
                return $day; 
            } catch(Exception $e) {
                echo "Error: " . $e->getMessage(); 
                return [];
            }
            
        }
    }

    
?>