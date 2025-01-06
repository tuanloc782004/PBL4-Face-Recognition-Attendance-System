<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'phpMailer/PHPMailer-master/src/Exception.php';
    require 'phpMailer/PHPMailer-master/src/PHPMailer.php';
    require 'phpMailer/PHPMailer-master/src/SMTP.php';
    require_once '../ConnDB/connDB.php';
    require_once '../Controller/studentsController.php';

    $studentsController = new StudentsController($connectionDB);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // $presentIDs = json_decode($_POST['presentIDs'], true);
        // $absentIDs = json_decode($_POST['absentIDs'], true);
        $requestBody = file_get_contents('php://input'); // Get raw JSON data
        $data = json_decode($requestBody, true); // Decode JSON to an array

        $presentIDs = $data['presentIDs'] ?? []; // Default to empty array if key is missing
        $absentIDs = $data['absentIDs'] ?? [];
        if (!is_array($presentIDs) || !is_array($absentIDs)) {
            die('Invalid data format');
        }
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'phuongsuga333@gmail.com'; 
            $mail->Password = 'jhow mqds wyyt ykgq'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('phuongsuga333@gmail.com', 'Admin');
            $mail->addReplyTo('phuongsuga333@gmail.com', 'Admin');
            foreach ($presentIDs as $studentID) {
                $studentInfo = $studentsController->getInfoStudent($studentID);
                if ($studentInfo) {
                    $mail->addAddress($studentInfo['Email']);
                
                    $message = "📢 THÔNG BÁO\n
                                Kính gửi học viên,\n
                                Hôm nay, bạn đã có mặt trong buổi học.\n
                                Chúc bạn học tập hiệu quả và đạt nhiều thành tích tốt!\n
                                Trân trọng,\n
                                [Ban giám hiệu/ Giáo viên phụ trách]";
    
                    $mail->Subject = 'Thông báo Điểm danh';
                    $mail->Body = $message;
                    
                    $mail->send();
                    $mail->clearAddresses();
                }
            }
            foreach ($absentIDs as $studentID) {
                $studentInfo = $studentsController->getInfoStudent($studentID);
                if ($studentInfo) {
                    $mail->addAddress($studentInfo['Email']); 
                
                    $message = "📢 THÔNG BÁO\n
                                Kính gửi học viên,\n
                                Hôm nay, bạn đã vắng mặt trong buổi học.\n
                                Vui lòng đến lớp vào ngày học tiếp theo.\n
                                Nếu có lý do chính đáng, vui lòng liên hệ giáo viên phụ trách để được hỗ trợ.\n
                                Trân trọng,\n
                                [Ban giám hiệu/ Giáo viên phụ trách]";
    
                    $mail->Subject = 'Thông báo Điểm danh';
                    $mail->Body = $message;
                    
                    $mail->send();
                    $mail->clearAddresses();
                }         
            }

            echo "
                <script>
                    alert('Đã gửi email thành công');
                    window.location.href = '../attendanceView.php';
                </script>
            ";
        } catch (Exception $e) {
            echo "Lỗi khi gửi email: {$mail->ErrorInfo}";
        }
    }
?>
