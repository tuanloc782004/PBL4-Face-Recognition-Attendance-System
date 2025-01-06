<?php
    require_once '../Controller/AdminController.php';

    $adminController = new AdminController($connectionDB);
    $admin = $adminController->showAdmin();
?>

<div class="user">
    <div class="user-div">
        <img src="image/logostudent.png">
        <div class="user-div1">
            <?php
            if ($admin) {
                while ($row = $admin->fetch_assoc()) {
                    echo '<p>' . htmlspecialchars($row['username']) . '</p>';
                }
            } else {
                echo '<p>No admin found.</p>';
            }
            ?>
            <small>Admin</small>
        </div>
    </div>
</div>
