<?php
    require_once '../ConnDB/connDB.php';
    require_once '../Controller/studentsController.php';
    require_once '../Controller/classController.php';
    require_once '../Controller/scheduledController.php';

    $classController = new ClassController();
    $classescount = $classController->countClasses();

    $studentsController = new StudentsController($connectionDB);
    $studentscount = $studentsController->countStudents();
    
    $scheduledController = new ScheduledController();
    $timetable = $scheduledController->getAllTime();
    $weeks = $scheduledController->getAllWeek();
    session_start();
    if (isset($_POST["id_week"])) {
        $_SESSION["id_week"] = $_POST["id_week"];
    }
    $id_week = isset($_SESSION["id_week"]) ? $_SESSION["id_week"] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học cùng Nihongo | Trang chủ</title>
    <link href="image/logoFaceWeb.png" rel="icon">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php require_once 'includes/sidebar.php'; ?>

    <section class="home">
        <section class="user-list">
            <?php require_once 'includes/adminView.php'; ?>

            <div class="countCards">
                <div class="countCard">
                    <div class="card-main">
                        <h3>Số học viên</h3>
                        <h1>
                            <?php
                                if ($studentscount['success']) {
                                    echo $studentscount['student_count'];
                                } else {
                                    echo "Error retrieving count";
                                }
                            ?>
                        </h1>
                    </div>
                    <i class="la la-user"></i>
                </div>

                <div class="countCard">
                    <div class="card-main">
                        <h3>Số lớp học</h3>
                        <h1>
                            <?php
                                if ($classescount['success']) {
                                    echo $classescount['class_count'];
                                } else {
                                    echo "Error retrieving count";
                                }
                            ?>
                        </h1>
                    </div>
                    <i class="las la-chalkboard-teacher"></i>
                </div>
                
            </div>

            <section class="wrapper">
                <div class="weeks">
                    <?php
                        if ($weeks) {
                            echo '<ul class="week-list">';
                            echo '<p>Chọn tuần học: </p>';
                            while ($row = $weeks->fetch_assoc()) {
                                echo '
                                    <li>
                                        <form method="POST" action="">
                                            <input name="id_week" type="hidden" value="' . htmlspecialchars($row['ID']) . '">
                                            <button id="' . htmlspecialchars($row['ID']) . '" class="btnWeek" type="submit" value="' . htmlspecialchars($row['ID']) . '">' . htmlspecialchars($row['ID']) . '</button>
                                        </form>
                                    </li>';
                            }
                            echo '</ul>';
                        }                    
                    ?>
                </div>

                <div class="calendar" style="display: <?= $id_week ? 'block' : 'none' ?>;">
                    <div class="timetable">
                        <table>
                            <thead>
                                <?php
                                    if ($id_week) {
                                        $days = $scheduledController->getAllDayByIdWeek($id_week);
                                        if (count($days) > 0) {
                                            echo '<tr>';
                                                echo '<th>Thời gian</th>';
                                                
                                            foreach ($days as $row) {
                                                    echo '<th>' . htmlspecialchars($row['TenNgay']) . '</th>';
                                            }
                                            echo '</tr>';
                                        }
                                    }
                                ?>
                                <tr>
                                    <th></th>
                                    <th>Thứ hai</th>
                                    <th>Thứ ba</th>
                                    <th>Thứ tư</th>
                                    <th>Thứ năm</th>
                                    <th>Thứ sáu</th>
                                    <th>Thứ bảy</th>
                                    <th>Chủ nhật</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $timetableRows = [];
                                    $timetableIds = [];
                                    if ($timetable) {
                                        foreach ($timetable as $time) {
                                            $timetableRows[] = [
                                                'id' => $time['ID'],
                                                'start_time' => $time['GioBatDau'],
                                                'end_time' => $time['GioKetThuc']
                                            ];
                                            $timetableIds[] = $time['ID'];
                                        }
                                    }
                                    $classesForTimeSlots = [];
                                    $todays = $scheduledController->getAllDayByIdWeek($id_week);
                                    foreach ($todays as $today) {
                                        foreach ($timetableIds as $timetableId) {
                                            $classData = $scheduledController->getClassByIdDayandHour($today['ID'],  $timetableId);
                                            $classesForTimeSlots[$timetableId][$today['ID']] = $classData ? htmlspecialchars($classData['TenLop']) : 'Trống';
                                        }
                                    }
                                    foreach ($timetableRows as $timeRow) {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($timeRow['start_time']) . '<span class="separator">-</span>' . htmlspecialchars($timeRow['end_time']) . '</td>';
                                        foreach ($todays as $today) {
                                            if (isset($classesForTimeSlots[$timeRow['id']][$today['ID']])) {
                                                echo '<td>' . $classesForTimeSlots[$timeRow['id']][$today['ID']] . '</td>';
                                            }
                                        }
                                        echo '</tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>

        </section>

        
    </section>

    <script>
        const btnWeek = document.querySelectorAll(".btnWeek"),
        showday = document.getElementById("showday"),
        calendar = document.querySelector(".calendar");

        btnWeek.forEach(button => {
            button.addEventListener("click", function(event){
                event.preventDefault();
                button.classList.add("btnweekColor");
                this.closest("form").submit();
                calendar.style.display = "block";
            });
        });
    </script>

    <script src="javascript/toggle.js"></script>

</body>
</html>
