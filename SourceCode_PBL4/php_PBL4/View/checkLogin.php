<?php
    require_once '../ConnDB/connDB.php';
    require_once '../Controller/adminController.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
        $username = $connectionDB->real_escape_string($_POST["username"]);
        $password = $connectionDB->real_escape_string($_POST["password"]);
        $adminController = new AdminController($connectionDB);
        $login = $adminController->login($username, $password);
        if ($login) {
            header("Location: homeView.php");
            exit;
        } else {
            echo '
                <script>alert("Đăng nhập thất bại. Vui lòng kiểm tra lại tên đăng nhập và mật khẩu.")</script>
            ';
        }
    }
?>