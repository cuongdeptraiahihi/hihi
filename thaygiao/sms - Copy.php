<?php
	ob_start();
	session_start();
	ini_set('max_execution_time', 3000);
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

    $temp=get_next_time(date("Y"),date("m"));
    $temp=explode("-",$temp);
    $thang_toi=$temp[1]+1-1;

    $tien_hoc2=$tien_hoc3=array();
    $tien_hoc2["tien_toan_du"]=get_muctien("cuoi_thang_truoc_toan");
    $tien_hoc2["tien_toan_muon"]=get_muctien("dau_thang_sau_toan");
    $tien_hoc2["tien_ly_du"]=get_muctien("cuoi_thang_truoc_ly");
    $tien_hoc2["tien_ly_muon"]=get_muctien("dau_thang_sau_ly");
    $tien_hoc2["tien_anh_du"]=get_muctien("cuoi_thang_truoc_anh");
    $tien_hoc2["tien_anh_muon"]=get_muctien("dau_thang_sau_anh");

    $tien_hoc3["tien_toan_du"]=format_price_sms($tien_hoc2["tien_toan_du"]);
    $tien_hoc3["tien_toan_muon"]=format_price_sms($tien_hoc2["tien_toan_muon"]);
    $tien_hoc3["tien_ly_du"]=format_price_sms($tien_hoc2["tien_ly_du"]);
    $tien_hoc3["tien_ly_muon"]=format_price_sms($tien_hoc2["tien_ly_muon"]);
    $tien_hoc3["tien_anh_du"]=format_price_sms($tien_hoc2["tien_anh_du"]);
    $tien_hoc3["tien_anh_muon"]=format_price_sms($tien_hoc2["tien_anh_muon"]);

    $rowCount=2;

	$query="SELECT ID_HS,cmt,fullname,sdt_bo,sdt_me FROM hocsinh ORDER BY cmt ASC";
    $result=mysqli_query($db,$query);
    while ($data=mysqli_fetch_assoc($result)) {
        $check=false;
//        $temp=split_month(get_lop_in($data["lop"]));
//        $nam=$temp[0];
//        $thang=$temp[1];
        $content="KINH GUI PHU HUYNH EM ".mb_strtoupper(str_replace("-"," ",unicode_convert($data["fullname"])),"UTF-8").".\n+ De quan ly ket qua hoc tap, lich hoc,... cua con, phu huynh truy cap vao www.bgo.edu.vn voi ten dang nhap: \"$data[cmt]\" va mat khau: \"{sdt}\". Neu can tro giup, phu huynh co the an nut \"Ho tro\" tren website hoac goi 09.827.827.64\n";
        $query2="SELECT m.date_in,m.ID_LM,l.ID_MON,n.ID_N FROM hocsinh_mon AS m 
        INNER JOIN lop_mon AS l ON l.ID_LM=m.ID_LM 
        LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=m.ID_HS AND n.ID_LM=m.ID_LM 
        WHERE m.ID_HS='$data[ID_HS]' ORDER BY m.ID_LM ASC";
        $result2=mysqli_query($db,$query2);
        while ($data2=mysqli_fetch_assoc($result2)) {
            $temp=split_month(get_lop_mon_in($data2["ID_LM"]));
            $nam=$temp[0];
            $thang=$temp[1];
            if(!isset($data2["ID_N"])) {
                $check=true;
                $temp2=split_month($data2["date_in"]);
                $nam_in=$temp2[0];
                $thang_in=$temp2[1];
                $mon_name=unicode_convert(get_mon_name($data2["ID_MON"]));
                $discount=get_discount_hs($data["ID_HS"],$data2["ID_MON"]);
                if($discount==0) {
                    $pre = " Hoc phi mon ".ucfirst($mon_name)." T$thang_toi la " . $tien_hoc3["tien_" . $mon_name . "_muon"] . ", neu dong truoc 1/$thang_toi thi se giam con " . $tien_hoc3["tien_" . $mon_name . "_du"] . ".\n";
                } else {
                    $price=$tien_hoc2["tien_".$mon_name."_du"] - ($tien_hoc2["tien_".$mon_name."_du"]*$discount/100);
                    $pre = " Hoc phi mon ".ucfirst($mon_name)." T$thang_toi la " . format_price_sms($price) . " (da giam ".$discount."%).\n";
                }
                $tien_con=0;
                $con="";
                for($i=1;$i<=24;$i++) {
                    $tien_hoc = check_dong_tien_hoc($data["ID_HS"], $data2["ID_LM"], "$nam-$thang");
                    if (count($tien_hoc) == 0) {
                        if (($nam == $nam_in && $thang >= $thang_in) || ($nam > $nam_in)) {
                            if (check_nghi_dai_thang("$nam-$thang", $data["ID_HS"], $data2["ID_LM"])) {

                            } else {
                                $temp3=$thang+1-1;
                                $con.=",$temp3";
                                $query3="SELECT content FROM options WHERE type='edit-tien-hoc-$data2[ID_LM]' AND note='$nam-$thang' AND note2='$data[ID_HS]'";
                                $result3=mysqli_query($db,$query3);
                                if(mysqli_num_rows($result3)==0) {
                                    if ($discount == 0) {
                                        if (stripos($data2["date_in"], "$nam-$thang") === false) {
                                            $tien_con += $tien_hoc2["tien_" . $mon_name . "_muon"];
                                        } else {
                                            if (get_day_from_date($data2["date_in"]) <= 7) {
                                                $tien_con += $tien_hoc2["tien_" . $mon_name . "_muon"];
                                            } else {
                                                $temp = du_kien_tien_hoc_buoi2("$nam-$thang", get_day_from_date($data2["date_in"]), $data["ID_HS"], $data2["ID_LM"], $data2["ID_MON"]);
                                                $tien_con += $temp[0];
                                            }
                                        }
                                    } else {
                                        if (stripos($data2["date_in"], "$nam-$thang") === false) {
                                            $tien_con += $tien_hoc2["tien_" . $mon_name . "_du"] - ($tien_hoc2["tien_" . $mon_name . "_du"] * $discount / 100);
                                        } else {
                                            if (get_day_from_date($data2["date_in"]) <= 7) {
                                                $tien_con += $tien_hoc2["tien_" . $mon_name . "_du"] - ($tien_hoc2["tien_" . $mon_name . "_du"] * $discount / 100);
                                            } else {
                                                $temp = du_kien_tien_hoc_buoi2("$nam-$thang", get_day_from_date($data2["date_in"]), $data["ID_HS"], $data2["ID_LM"], $data2["ID_MON"]);
                                                $tien_con += $temp[0] - ($temp[0] * $discount / 100);
                                            }
                                        }
                                    }
                                } else {
                                    $data3=mysqli_fetch_assoc($result3);
                                    $tien_con += $data3["content"]*1000;
                                }
                            }
                        }
                    }
                    if("$nam-$thang"==date("Y-m")) {
                        break;
                    }
                    $thang++;
                    if($thang==13) {
                        $thang="01";
                        $nam++;
                    } else {
                        if($thang<10) {
                            $thang="0".$thang;
                        } else {
                            $thang="$thang";
                        }
                    }
                }
                if($con!="") {
                    if(strlen($con)>=2) {
                        $tong=" tong";
                    } else {
                        $tong="";
                    }
                    if($discount==0) {
                        $content .= "+ Em chua hoan thanh hoc phi mon " . ucfirst($mon_name) . " T" . substr($con, 1) . "$tong la " . format_price_sms($tien_con) . ".$pre";
                    } else {
                        $content .= "+ Em chua hoan thanh hoc phi mon " . ucfirst($mon_name) . " T" . substr($con, 1) . "$tong la " . format_price_sms($tien_con) . " (da giam " . $discount . "%).$pre";
                    }
                } else {
                    $content .= "+".$pre;
                }
            }
        }
        if($check) {
            $tien_phat = get_tien_hs($data["ID_HS"]);
            if ($tien_phat < 0) {
                $tien_phat=abs($tien_phat);
                $content .= "+ Tien phat can dong la " . format_price_sms($tien_phat) . ".\n";
            }
            $content .= " Tran trong thong bao.";
            if (is_numeric($data["sdt_bo"]) && $data["sdt_bo"] != "X" && $data["sdt_bo"] != "") {
                $col = "A";
                $content_bo = str_replace("{sdt}", $data["sdt_bo"], $content);
                $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "Ho");
                $col++;
                $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "Ten");
                $col++;
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("$col" . $rowCount, format_phone($data["sdt_bo"]), PHPExcel_Cell_DataType::TYPE_STRING);
                $col++;
                $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $content_bo);
                $col++;
                $rowCount++;
            }
            if (is_numeric($data["sdt_me"]) && $data["sdt_me"] != "X" && $data["sdt_me"] != "") {
                $col = "A";
                $content_me = str_replace("{sdt}", $data["sdt_me"], $content);
                $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "Ho");
                $col++;
                $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "Ten");
                $col++;
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("$col" . $rowCount, format_phone($data["sdt_me"]), PHPExcel_Cell_DataType::TYPE_STRING);
                $col++;
                $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $content_me);
                $col++;
                $rowCount++;
            }
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