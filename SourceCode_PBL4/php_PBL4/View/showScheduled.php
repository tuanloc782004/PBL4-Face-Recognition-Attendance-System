<?php
    require_once '../Controller/scheduledController.php';
    
    $scheduledController = new ScheduledController();
    $today = isset($_POST['today']) ? $_POST['today'] : date('Y-m-d'); 
    $id_day = $scheduledController->changeDatyToId($today);
    echo '<script>console.log("Converted iddays: ' . json_encode($id_day) . '");</script>';
    $classDays = $scheduledController->getClassByIdDay($id_day);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học cùng Nihongo | Điểm danh</title>
    <link href="image/logoFaceWeb.png" rel="icon">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php require_once 'includes/sidebar.php'; ?>

    <section class="home">
        <section class="user-list">
            <?php require_once 'includes/adminView.php'; ?>

            <div class="attendance">
                <div class="title">
                    <h3>Lịch học</h3>
                    <div class="date">
                        <label for="today">Ngày học</label>
                        <input type="date" id="today" name="date" disabled>
                    </div>
                </div>
                <div class="classes">
                    <form action="">
                        <table>
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Lớp</th>
                                    <th>Giờ bắt đầu</th>
                                    <th>Giờ kết thúc</th>
                                    <th>Điểm danh</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $stt = 0;
                                    if (!empty($classDays)) {
                                        foreach ($classDays as $classDay) {
                                            echo '<tr>';
                                            $stt++;
                                                echo '<td> '. $stt. ' </td>';
                                                echo '<td> '. htmlspecialchars($classDay["TenLop"]). ' </td>';
                                                echo '<td> '. htmlspecialchars($classDay["GioBatDau"]) .' </td>';
                                                echo '<td> '. htmlspecialchars($classDay["GioKetThuc"]) .' </td>';
                                                echo '<td> 
                                                        <form action="checkCamera.php" method="GET">
                                                            <input type="hidden" name="ID" value="' . htmlspecialchars($classDay["ID"]) . '">
                                                            <button type="submit" class="btnCheck"><i class="las la-calendar-check icon"></i></button>
                                                        </form>
                                                    </td>';
                                            echo '</tr>';
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </form>

                </div>

            </div>

        </section>
    </section>
    <script src="javascript/toggle.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const newToday = new Date();
            const setDay = newToday.toISOString().split('T')[0];
            document.getElementById("today").value = setDay;
            fetch("attendanceView.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "today=" + setDay
            })
            .then(response => response.text())
            .then(data => {
                console.log("Ngày hiện tại đã gửi:", setDay);
                console.log("Dữ liệu từ server:", data);
            })
            .catch(error => console.error("Lỗi khi gửi dữ liệu:", error));
        });
</script>

</body>
</html>
