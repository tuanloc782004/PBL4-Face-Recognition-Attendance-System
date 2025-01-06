<?php
    require_once '../Controller/studentsController.php';
    require_once '../ConnDB/connDB.php';

    header('Content-Type: text/html; charset=utf-8');

    if (isset($_POST['ten_them'], $_POST['gender_them'], $_POST['date_them'], $_POST['email_them'], $_POST['diachi_them'], $_POST['id_lop'])) {
        $ten = $connectionDB->real_escape_string(trim($_POST['ten_them']));
        $gender = $connectionDB->real_escape_string(trim($_POST['gender_them']));
        $date = $connectionDB->real_escape_string(trim($_POST['date_them']));
        $email = $connectionDB->real_escape_string(trim($_POST['email_them']));
        $address = $connectionDB->real_escape_string(trim($_POST['diachi_them'])); 
        $id_lop = $connectionDB->real_escape_string(trim($_POST['id_lop']));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            exit();
        }
        $studentsController = new StudentsController($connectionDB);

        try {
            $studentsController->addNewStudent($ten, $gender, $date, $email, $address, $id_lop);
            
            header("Location: showCamera.php");
            exit();
        } catch (Exception $e) {
            error_log($e->getMessage()); 
            echo json_encode(['success' => false, 'message' => 'An error occurred while adding the student']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing required form data']);
    }
?>

