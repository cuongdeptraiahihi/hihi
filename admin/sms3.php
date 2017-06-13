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

	$query="SELECT h.ID_HS,h.cmt,h.fullname,h.sdt_bo FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='1' WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_MON='1') ORDER BY rand()";
    $result=mysqli_query($db,$query);
    while ($data=mysqli_fetch_assoc($result)) {
        $content="+ Kinh gui phu huynh em ".mb_strtoupper(str_replace("-"," ",unicode_convert($data["fullname"])),"UTF-8")." ($data[cmt]).\n";
        $content.="+ Day la video buoi dien thuyet ve Phuong phap giup con hoc tot ngay 4/9 https://www.facebook.com/diengia.phanduong/posts/931122333666026\n";
        $content.="+ Em rat mong nhan duoc nhung nhan xet va gop y cua anh chi de hoan thien hon.\n";
        $content.="+ Neu anh chi thay buoi dien thuyet bo ich thi anh chi co the chia se clip nay cho nguoi than cua minh.";
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
    $query="SELECT h.ID_HS,h.cmt,h.fullname,h.sdt_me FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='1' WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_MON='1') ORDER BY rand()";
    $result=mysqli_query($db,$query);
    while ($data=mysqli_fetch_assoc($result)) {
        $content="+ Kinh gui phu huynh em ".mb_strtoupper(str_replace("-"," ",unicode_convert($data["fullname"])),"UTF-8")." ($data[cmt]).\n";
        $content.="+ Day la video buoi dien thuyet ve Phuong phap giup con hoc tot ngay 4/9 https://www.facebook.com/diengia.phanduong/posts/931122333666026\n";
        $content.="+ Em rat mong nhan duoc nhung nhan xet va gop y cua anh chi de hoan thien hon.\n";
        $content.="+ Neu anh chi thay buoi dien thuyet bo ich thi anh chi co the chia se clip nay cho nguoi than cua minh.";
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