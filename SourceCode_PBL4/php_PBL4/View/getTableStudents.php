<?php
    require_once '../Controller/studentsController.php';
    require_once '../ConnDB/connDB.php';

    $studentsController = new StudentsController($connectionDB);
    $id_lop = isset($_POST['id_lop']) ? $_POST['id_lop'] : null;

    $students = $studentsController->getAllStudentInClass($id_lop);
    $html = '<table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ và tên</th>
                        <th>Ngày sinh</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>';
    if (count($students) > 0) {
        foreach ($students as $row) {
            $html .= '<tr data-id="' . htmlspecialchars($row['ID']) . '">';
            $html .= '<td>' . htmlspecialchars($row['ID']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['Ten']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['NgaySinh']) . '</td>';
            $html .= '<td>
                        <button class="btninfo" data-idxembtn="' . htmlspecialchars($row['ID']) . '"><i class="las la-info"></i></button>
                        <button class="btnedit" data-idsuabtn="' . htmlspecialchars($row['ID']) . '"><i class="las la-edit"></i></button>
                        <button class="btntrash" data-idxoabtn="' . htmlspecialchars($row['ID']) . '"><i class="las la-trash"></i></button>
                    </td>';
            $html .= '</tr>';
        }
    } else {
        $html .= '<tr><td colspan="4">Không có học sinh cấp độ này.</td></tr>';
    }
    $html .= '</tbody></table>';
    echo $html;
?>
