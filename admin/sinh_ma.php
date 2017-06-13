<?php
//	ob_start();
	ini_set('max_execution_time', 2000);
//    include ("include/PHPExcel/IOFactory.php");
//    $objPHPExcel = new PHPExcel();
//    $objPHPExcel->setActiveSheetIndex(0);

    $rowCount = 1;

    $count = 0;
    $end = 9;
    for($a = 0; $a <= $end; $a++) {
        for($b = 0; $b <= $end; $b++) {
            for($c = 0; $c <= $end; $c++) {
                for($d = 0; $d <= $end; $d++) {
                    for($e = 0; $e <= $end; $e++) {
                        for($f = 0; $f <= $end; $f++) {
                            for($g = 0; $g <= $end; $g++) {
                                for($h = 0; $h <= $end; $h++) {
                                    for($i = 0; $i <= $end; $i++) {
                                        $col = "A";
                                        $number = $a."".$b."".$c."-".$d."".$e."".$f."-".$g."".$h."".$i;
                                        echo $number."<br />";
//                                        $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $number);
                                        $rowCount++;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

//    ob_end_clean();
//    // Instantiate a Writer to create an OfficeOpenXML Excel .xlsx file
//    header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//    header('Content-Disposition: attachment; filename="sinh_ma.xls"');
//    $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//    // Write the Excel file to filename some_excel_file.xlsx in the current directory
//    $objWriter->save('php://output');

//	ob_end_flush();
//	require_once("../model/close_db.php");
?>