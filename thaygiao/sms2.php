<?php
	ob_start();
	session_start();
	ini_set('max_execution_time', 2000);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
    include ("include/PHPExcel/IOFactory.php");
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);

    $rowCount = 1;
    $col = 'A';
    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "FirstName");$col++;
    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "LastName");$col++;
    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Mobile");$col++;
    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Message");$col++;

    $rowCount=2;

	$query="SELECT h.ID_HS,h.cmt,h.fullname,h.sdt_bo FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='1' WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_MON='1') AND h.lop='2' ORDER BY rand()";
    $result=mysqli_query($db,$query);
    while ($data=mysqli_fetch_assoc($result)) {
        $content="Kinh moi phu huynh em ".mb_strtoupper(str_replace("-"," ",unicode_convert($data["fullname"])),"UTF-8")." ($data[cmt]) den du buoi chia se \"Phuong phap giup con cai thien viec hoc trong nam hoc cap 3\" cua thay Duong.\n";
        $content.="Thoi gian: 8h den 10h toi ngay 4/9.\n";
        $content.="Dia diem: P501 - Tang 5 - Toa nha Tay Ha (19 To Huu).\n";
        $content.="Neu thay viec hoc cua con la quan trong thi phu huynh vui long bot chut thoi gian tham du. Tran trong thong bao!";
        if (is_numeric($data["sdt_bo"]) && $data["sdt_bo"] != "X" && $data["sdt_bo"] != "") {
            $col = "A";
            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "Ho");
            $col++;
            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "Ten");
            $col++;
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("$col" . $rowCount, format_phone($data["sdt_bo"]), PHPExcel_Cell_DataType::TYPE_STRING);
            $col++;
            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $content);
            $col++;
            $rowCount++;
        }
    }
    $query="SELECT h.ID_HS,h.cmt,h.fullname,h.sdt_me FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='1' WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_MON='1') AND h.lop='2' ORDER BY rand()";
    $result=mysqli_query($db,$query);
    while ($data=mysqli_fetch_assoc($result)) {
        $content="Kinh moi phu huynh em ".mb_strtoupper(str_replace("-"," ",unicode_convert($data["fullname"])),"UTF-8")." den du buoi chia se \"Phuong phap giup con cai thien viec hoc trong nam hoc cap 3\" cua thay Duong.\n";
        $content.="Thoi gian: 8h den 10h toi ngay 4/9.\n";
        $content.="Dia diem: P501 - Tang 5 - Toa nha Tay Ha (19 To Huu).\n";
        $content.="Neu thay viec hoc cua con la quan trong thi phu huynh vui long bot chut thoi gian tham du. Tran trong thong bao!";
        if (is_numeric($data["sdt_me"]) && $data["sdt_me"] != "X" && $data["sdt_me"] != "") {
            $col = "A";
            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "Ho");
            $col++;
            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "Ten");
            $col++;
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("$col" . $rowCount, format_phone($data["sdt_me"]), PHPExcel_Cell_DataType::TYPE_STRING);
            $col++;
            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $content);
            $col++;
            $rowCount++;
        }
    }

    ob_end_clean();
    // Instantiate a Writer to create an OfficeOpenXML Excel .xlsx file
    header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="thong-bao-sms.xls"');
    $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
    // Write the Excel file to filename some_excel_file.xlsx in the current directory
    $objWriter->save('php://output');

	ob_end_flush();
	require_once("../model/close_db.php");
?>