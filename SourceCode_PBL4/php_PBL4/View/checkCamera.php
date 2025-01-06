<?php
    require_once '../ConnDB/connDB.php';
    require_once '../Controller/studentsController.php';
    $studentsController = new StudentsController($connectionDB);
    if (isset($_GET['ID']) && isset($_GET['id_day'])) {
        $id_lop = $_GET['ID'];
        $id_day = $_GET['id_day'];
        $students = $studentsController->getAllStudentInClass($id_lop);
        $id_qlbh = $studentsController->getIdQLBH($id_lop, $id_day);
    } else {
        echo "Không tìm thấy ID lớp và ID Ngày.";
    }

    if (!empty($students)) {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học cùng Nihongo | Điểm danh</title>
    <link href="image/logoFaceWeb.png" rel="icon">
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="css/styles.css">

    <style>
        .studentCard #check {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            position: absolute;
            top: -15px;
            right: -10px;
            background-color: var(---primary--color-light);
            align-items: center;
            display: flex;
            justify-content: center;
            border: 2px double var(--primary--color);
        }

        #check i {
            font-size: 18px;
            color: black;
        }

        #check.active {
            background-color: var(--primary--color) !important;
            border: 2px double white;
        }

        #check.active i {
            font-size: 18px;
            color: white;
        }
    </style>
</head>

<body>
    <?php require_once 'includes/sidebar.php'; ?>

    <section class="home">
        <section class="user-list">
            <?php require_once 'includes/adminView.php'; ?>

            <div class="checkcam">
                <div class="containerVideo">
                    <img id="cameraFeed" alt="Camera Feed" style="max-width: 100%; height: auto;" />
                    <h4 id="detectedName">ID: Unknown</h4>
                    <h4 id="detectedTime">Time: </h4>
                </div>
                <div class="list-button">
                    <button class="btnshowclasses">Điểm danh</button>
                </div>
                <div class="studentCards" style="display: none">
                    <?php
                    $stt = 0;
                    foreach ($students as $row) {
                        $stt++;
                        echo '<div class="studentCard">';
                        echo '<img src="image/logostudent.png" alt="">';
                        echo '<div class="info">';
                        echo '<small>STT: ' . $stt . '</small>';
                        echo '<p id="idStudent" data-id="' . htmlspecialchars($row["ID"]) . '">ID: ' . htmlspecialchars($row["ID"]) . '</p>';
                        echo '<h5>' . htmlspecialchars($row["Ten"]) . '</h5>';
                        echo '</div>';
                        echo ' <button id="check"><i class=" icon las la-times"></i></button>';
                        echo '</div>';
                    }
                    ?>
                    <div class="list-button">
                        <button id="sendEmailButton" class="btnshowclasses">Gửi Email</button>
                    </div>
                </div>
            </div>


        </section>

    </section>

    <script src="javascript/attendance.js" ></script>
    

    <script src="javascript/toggle.js"></script>
    <script>
        const btnshowclasses = document.querySelector(".btnshowclasses");
        const studentCards = document.querySelector(".studentCards");

        btnshowclasses.addEventListener("click", function (event) {
            event.preventDefault();
            studentCards.style.display = "flex";
            studentCards.style.flexWrap = "wrap";
        })
    </script>
</body>

</html>
<?php
    } else {
        echo "Không có sinh viên nào trong lớp hoặc có lỗi trong truy vấn.";
    }
?>