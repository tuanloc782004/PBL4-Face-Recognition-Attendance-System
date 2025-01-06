<?php
    require_once '../Controller/scheduledController.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['today'])) {
        $scheduledController = new ScheduledController();
        $today = $_POST['today'];
        $id_day = $scheduledController->changeDatyToId($today);
        $classDays = $scheduledController->getClassByIdDay($id_day);
        $output = '';
        if (!empty($classDays)) {
            $stt = 0;
            foreach ($classDays as $classDay) {
                $stt++;
                $output .= '<tr>';
                $output .= '<td>' . $stt . '</td>';
                $output .= '<td>' . htmlspecialchars($classDay["TenLop"]) . '</td>';
                $output .= '<td>' . htmlspecialchars($classDay["GioBatDau"]) . '</td>';
                $output .= '<td>' . htmlspecialchars($classDay["GioKetThuc"]) . '</td>';
                $output .= '<td>
                            <form action="checkCamera.php" method="GET">
                                <input type="hidden" name="ID" value="' . htmlspecialchars($classDay["ID"]) . '">
                                <input type="hidden" name="id_day" value="' . htmlspecialchars($id_day) . '"> 
                                <button type="submit" class="btnCheck" data-start-time="' . htmlspecialchars($classDay["GioBatDau"]) . '" data-end-time="' . htmlspecialchars($classDay["GioKetThuc"]) . '">
                                    <i class="las la-calendar-check icon"></i>
                                </button>
                            </form>
                        </td>';
                $output .= '</tr>';
            }
        } else {
            $output .= '<tr><td colspan="5">Không có lớp học nào trong ngày hôm nay.</td></tr>';
        }
        echo $output;
        exit;
    }
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
                        <input type="date" id="today" name="date" readonly>
                        <button class="btnAttendance" id="btnClassInDay" type="button">Điểm danh</button>
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
                            <tbody id="classList">
                                <tr>
                                    <td colspan="5">Hãy nhấn "Điểm danh" để xem danh sách lớp.</td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>

        </section>
    </section>
    <script src="javascript/toggle.js"></script>
    <script src="javascript/getDay.js"></script>
    
</body>
</html>
