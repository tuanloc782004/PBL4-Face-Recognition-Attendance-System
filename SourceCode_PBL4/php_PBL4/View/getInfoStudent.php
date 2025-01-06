<?php
    require_once '../Controller/studentsController.php';
    require_once '../ConnDB/connDB.php';

    header('Content-Type: application/json');

    if (isset($_GET['id_hv'])) {
        $id_hv = intval($_GET['id_hv']); 
        $studentsController = new StudentsController($connectionDB); 
        $student = $studentsController->showInfoStudent($id_hv);

        if (empty($student)) {
            echo json_encode(['success' => false, 'message' => 'Student not found or empty result']);
        } else {
            echo json_encode([
                'success' => true,
                'student' => $student[0] 
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
?>
