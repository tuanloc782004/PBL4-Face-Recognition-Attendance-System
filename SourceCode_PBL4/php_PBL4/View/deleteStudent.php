<?php
    require_once '../Controller/studentsController.php';
    require_once '../ConnDB/connDB.php';

    header('Content-Type: application/json');

    if (isset($_GET['id_hv'])) {
        $id_hv = intval($_GET['id_hv']); 
        $studentsController = new StudentsController($connectionDB); 
        $isDeleted = $studentsController->deleteStudent($id_hv); 

        if ($isDeleted) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete student or student not found.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
?>
