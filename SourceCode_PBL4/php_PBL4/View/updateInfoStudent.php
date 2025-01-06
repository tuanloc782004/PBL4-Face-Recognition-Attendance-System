<?php
    require_once '../Controller/studentsController.php';
    require_once '../ConnDB/connDB.php';

    header('Content-Type: text/html; charset=utf-8');

    if (isset($_POST['id_hv'], $_POST['ten'], $_POST['gender'], $_POST['date'], $_POST['email'], $_POST['address'])) {
        $id = $connectionDB->real_escape_string($_POST['id_hv']);
        $ten = $connectionDB->real_escape_string($_POST['ten']);
        $gender = $connectionDB->real_escape_string($_POST['gender']);
        $date = $connectionDB->real_escape_string($_POST['date']);
        $email = $connectionDB->real_escape_string($_POST['email']);
        $address = $connectionDB->real_escape_string($_POST['address']);

        $studentsController = new StudentsController($connectionDB);

        try {
            $studentsController->editInfoStudent($id, $ten, $gender, $date, $email, $address);
            echo '<script>
                    window.location.href = "classView.php";
                    document.querySelector(".main-card").style.display = "none";
                    document.querySelector(".suathongtin").style.display = "block";
                    document.querySelector(".attendance-list").classList.remove("dimmed"); 
                    document.querySelector(".user").classList.remove("dimmed");
                </script>';

            exit();
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing required form data']);
    }
?>