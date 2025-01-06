<?php
    require_once '../Controller/classController.php';
    require_once '../Controller/studentsController.php';

    $classController = new ClassController();
    $classes = $classController->getAllClass();
    $cardCounter = 0;

    $studentsController = new StudentsController($connectionDB);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học cùng Nihongo | Danh sách học viên</title>
    <link href="image/logoFaceWeb.png" rel="icon">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php require_once 'includes/sidebar.php'; ?>

    <section class="home">
        <section class="user-list">
            <?php require_once 'includes/adminView.php'; ?>

            <div class="main-card">
                <?php
                    if ($classes) {
                        echo '<div class="main-skills">';
                        while ($row = $classes->fetch_assoc()) {
                            $lop = htmlspecialchars($row['TenLop']);
                            if ($cardCounter > 0 && $cardCounter % 4 == 0) {
                                echo '</div>
                                <div class="main-skills">';
                            }
                                    echo '<div class="card">
                                            <div class="detail">
                                                <h3>' . htmlspecialchars($row['TenLop']) . '</h3>
                                            </div>
                                            <form class="" method="POST" action="">
                                                <input type="hidden" name="id_lop" value="'. htmlspecialchars($row['ID']) .'">
                                                <button data-idlop='. htmlspecialchars($row['ID']) .' id="'. htmlspecialchars($row['ID']) . '" type="submit" class="btnView">View</button>
                                            </form>
                                        </div>';
                            $cardCounter++;
                        }
                        echo '</div>';
                    }
                    echo '<button class="btnNew"><i class="las la-plus-circle"></i>New</button>';
                ?>
            </div>

            <div class="attendance-list" style="display: none;">
                <div class="title">
                    <h1>Danh sách tất cả các học viên</h1>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ và tên</th>
                            <th>Ngày sinh</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_lop'])) {
                                try {
                                    $id_lop = $connectionDB->real_escape_string($_POST['id_lop']); 
                                    echo $id_lop;
                                } catch (Exception $e){
                                    echo  $e;
                                }
                                $students = $studentsController->getAllStudentInClass($id_lop);
                                if (count($students) > 0) {
                                    foreach ($students as $row) {
                                        echo '<tr data-id="' . htmlspecialchars($row['ID']) . '">';
                                        echo '<td>' . htmlspecialchars($row['ID']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['Ten']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['NgaySinh']) . '</td>';
                                        echo '<td>
                                                <button class="btninfo" data-idxembtn="' . htmlspecialchars($row['ID']) . '"><i class="las la-info"></i></button>
                                                <button class="btnedit" data-idsuabtn="' . htmlspecialchars($row['ID']) . '"><i class="las la-edit"></i></button>
                                                <button class="btntrash" data-idxoabtn="' . htmlspecialchars($row['ID']) . '"><i class="las la-trash"></i></button>
                                            </td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="4">Không có học sinh cấp độ này.</td></tr>';
                                }
                            }                                                               
                        ?>
                    </tbody>
                </table>
                <button class="btnThoat">Thoát</button>
            </div>


            <!-- ====XEM THONG TIN HOC VIEN==== -->
            <section id="xemthongtin" class="xemthongtin" style="display: none;">
                <div class="header-info">
                    <h3>Profile</h3>
                    <button class="btnClose"><i class="las la-times"></i></button>
                </div>
                <form class="main-info">
                    <div class="text-info">
                        <div>
                            <label for="idxem">ID:</label>
                            <input type="text" name="" id="idxem" disabled>
                        </div>
                        <div>
                            <label for="tenxem">Họ và tên:</label>
                            <input type="text" name="" id="tenxem" disabled>
                        </div>
                        <div class="gender">
                            <label for="gendersua">Giới tính:</label>
                            <div class="gender-detail">
                                <input disabled type="radio" name="gender" id="genderxemnu" value="Nu">Nữ
                                <input disabled type="radio" name="gender" id="genderxemnam" value="Nam">Nam
                            </div>
                        </div>
                        <div>
                            <label for="datexem">Ngày sinh:</label>
                            <input type="date" name="" id="datexem" disabled>
                        </div>
                        <div>
                            <label for="emailxem">E-mail:</label>
                            <input type="email" name="" id="emailxem" disabled>
                        </div>
                        <div>
                            <label for="diachixem">Địa chỉ</label>
                            <input type="text" name="" id="diachixem" disabled>
                        </div>
                    </div>
                </form>
            </section>  

            <!-- ====SUA THONG TIN HOC VIEN==== -->
            <section id="suathongtin" class="suathongtin" style="display: none;">
                <div class="header-info">
                    <h3>Profile</h3>
                    <div>
                        <button class="btnClose1"><i class="las la-times"></i></button>
                    </div>
                </div>
                <form class="main-info" id="editForm" method="POST" action="updateInfoStudent.php">
                    <div class="text-info">
                        <div>
                            <label for="idsua">ID:</label>
                            <input type="text" name="id_hv" id="idsua" readonly>
                            <!-- <input type="text" id="idsua_display" disabled>  -->
                        </div>
                        <div>
                            <label for="tensua">Họ và tên:</label>
                            <input type="text" name="ten" id="tensua">
                        </div>
                        <div class="gender">
                            <label for="gendersua">Giới tính:</label>
                            <div class="gender-detail">
                                <input type="radio" name="gender" id="gendersuanu" value="Nu"> Nữ
                                <input type="radio" name="gender" id="gendersuanam" value="Nam"> Nam
                            </div>
                        </div>
                        <div>
                            <label for="datesua">Ngày sinh:</label>
                            <input type="date" name="date" id="datesua">
                        </div>
                        <div>
                            <label for="emailsua">E-mail:</label>
                            <input type="email" name="email" id="emailsua">
                        </div>
                        <div>
                            <label for="diachisua">Địa chỉ</label>
                            <input type="text" name="address" id="diachisua">
                        </div>
                        <button class="btnUpdate" type="submit">Update</button>
                    </div>
                </form>

            </section>  
            
            <!-- ====THEM HOC VIEN==== -->
            <section class="themhocvien" style="display: none;">
                <div class="header-info" >
                    <h3>Profile</h3>
                    <div>
                        <button class="btnClose2"><i class="las la-times"></i></button>
                    </div>
                </div>
                <form class="main-info"  id="addForm" method="POST" action="addNewStudent.php">
                    <div class="text-info">
                        <div>
                            <label for="tenthem">Họ và tên:</label>
                            <input type="text" name="ten_them" id="tenthem" require>
                        </div>
                        <div class="gender">
                            <label for="genderthem">Giới tính:</label>
                            <div class="gender-detail">
                                <input type="radio" name="gender_them" id="genderthemnu" value="Nu">Nữ
                                <input type="radio" name="gender_them" id="genderthemnam" value="Nam">Nam
                            </div>
                        </div>
                        <div>
                            <label for="datethem">Ngày sinh:</label>
                            <input type="date" name="date_them" id="datethem" require>
                        </div>
                        <div>
                            <label for="emailthem">E-mail:</label>
                            <input type="email" name="email_them" id="emailthem"  require>
                        </div>
                        <div>
                            <label for="diachithem">Địa chỉ</label>
                            <input type="text" name="diachi_them" id="diachithem" require>
                        </div>
                        <div style="display: flex;">
                            <label for="select-lop">Chọn lớp:</label>
                            <?php
                                $classes->data_seek(0);
                                echo '<select class="select-lop" name="id_lop" id="select-lop"  require>';
                                    echo '<option value="" disabled selected>Chọn lớp</option>';
                                while ($row = $classes->fetch_assoc()){
                                    echo '<option value="'. htmlspecialchars($row["ID"]) .'">'. htmlspecialchars($row["TenLop"]) .'</option>';
                                }
                                echo '</select>';
                            ?>
                        </div>
                        <button type="submit" class="btnSave">Chụp ảnh</button>
                    </div>
                </form>
            </section>  

            <div id="confirmBox" class="confirm-box hidden">
                <div class="confirm-content">
                    <p>Bạn có chắc chắn muốn xóa không?</p>
                    <div class="confirm-buttons">
                        <button id="btnYes">Có</button>
                        <button id="btnNo">Không</button>
                    </div>
                </div>
            </div>

        </section>

    </section>

    <script src="javascript/toggle.js"></script>
    <script src="javascript/classjs.js"></script>
</body>
</html>
