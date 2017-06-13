<?php
    ob_start();
    session_start();
    require_once "../model/model.php";
    require_once "access_admin.php";
    include ("../model/PHPExcel/IOFactory.php");
    if(isset($_SESSION["output"]) && $_SESSION["output"] != NULL) {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $temp = $_SESSION["output"];
        $excel = json_decode($temp[0], true);
        $n = count($excel);
        $rowCount = 1;
        $col = 'A';
        $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $temp[2]);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
        if($temp[1] == "hoc-sinh-out") {
            $rowCount = 2;
            $col = 'A';
            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "STT");
            $col++;
            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "Mã số");
            $col++;
            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "Họ và tên");
            $col++;
            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "Điểm");
            $col++;
            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "Thời gian");
            $col++;
            $rowCount++;
            for ($i = 0; $i < $n; $i++) {
                $col = "A";

                $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $excel[$i]["stt"]);
                $col++;
                $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $excel[$i]["maso"]);
                $col++;
                $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $excel[$i]["name"]);
                $col++;
                $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $excel[$i]["diem"]);
                $col++;
                $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $excel[$i]["time"]);
                $col++;

                $rowCount++;
            }
            ob_end_clean();
            header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="thong-ke-diem-kiem-tra.xlsx"');
            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $objWriter->save('php://output');
        }
        $_SESSION["output"] = NULL;
        unset($_SESSION["output"]);
    }
    echo"Hello";
?>