<?php
	ob_start();
	session_start();
    ini_set('max_execution_time', 2000);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $lmID=$_SESSION["lmID"];
    $monID=$_SESSION["mon"];
    $mon_name=get_mon_name($monID);
    $lop_mon_name=get_lop_mon_name($lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>SMS MỜI HỌP PHỤ HUYNH</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:77.5%;margin-right:0;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
		    $("#xuat-ok").click(function() {
		        if(confirm("Bạn có chắc chắn? Vui lòng đợi...")) {
		            return true;
                } else {
                    return false;
                }
            });
		});
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>

            <?php
            $temp=get_next_time(date("Y"),date("m"));
            $temp=explode("-",$temp);
            $thang_toi=$temp[1]+1-1;

            $content=NULL;
            if(isset($_POST["xuat-ok"])) {
                if(isset($_POST["content"])) {
                    $content=addslashes($_POST["content"]);
                    if(isset($_POST["xao"])) {
                        $xao="ORDER BY rand()";
                    } else {
                        $xao="ORDER BY h.cmt ASC";
                    }
                    include ("include/PHPExcel/IOFactory.php");
                    $objPHPExcel = new PHPExcel();
                    $objPHPExcel->setActiveSheetIndex(0);

                    $rowCount = 1;
                    $col = 'A';
                    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "STT");$col++;
                    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Mã số");$col++;
                    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Mobile");$col++;
                    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Message");$col++;

                    $rowCount=2;

                    $stt=1;
                    $query="SELECT h.ID_HS,h.cmt,h.fullname,h.sdt_bo FROM hocsinh AS h 
                    INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
                    WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID') $xao";
                    $result=mysqli_query($db,$query);
                    while ($data=mysqli_fetch_assoc($result)) {
                        $pre_con=$content;
                        $pre_con=str_replace("{ho_ten_hoc_sinh}",mb_strtoupper(str_replace("-"," ",unicode_convert($data["fullname"])),"UTF-8"),$pre_con);
                        $pre_con=str_replace("{maso_hoc_sinh}",$data["cmt"],$pre_con);
                        $pre_con=str_replace("{sdt_phu_huynh}",$data["sdt_bo"],$pre_con);
                        $pre_con=str_replace("{mon_hoc}","Môn " . ucfirst(str_replace("-"," ",unicode_convert($mon_name))),$pre_con);
                        if (is_numeric($data["sdt_bo"]) && $data["sdt_bo"] != "X" && $data["sdt_bo"] != "") {
                            $col = "A";
                            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $stt);
                            $col++;
                            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $data["cmt"]);
                            $col++;
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("$col" . $rowCount, format_phone($data["sdt_bo"]), PHPExcel_Cell_DataType::TYPE_STRING);
                            $col++;
                            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $pre_con);
                            $col++;
                            $rowCount++;
                            $stt++;
                        }
                    }
                    $query="SELECT h.ID_HS,h.cmt,h.fullname,h.sdt_me FROM hocsinh AS h 
                    INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
                    WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID') $xao";
                    $result=mysqli_query($db,$query);
                    while ($data=mysqli_fetch_assoc($result)) {
                        $pre_con=$content;
                        $pre_con=str_replace("{ho_ten_hoc_sinh}",mb_strtoupper(str_replace("-"," ",unicode_convert($data["fullname"])),"UTF-8"),$pre_con);
                        $pre_con=str_replace("{maso_hoc_sinh}",$data["cmt"],$pre_con);
                        $pre_con=str_replace("{sdt_phu_huynh}",$data["sdt_me"],$pre_con);
                        $pre_con=str_replace("{mon_hoc}","Môn " . ucfirst(str_replace("-"," ",unicode_convert($mon_name))),$pre_con);
                        if (is_numeric($data["sdt_me"]) && $data["sdt_me"] != "X" && $data["sdt_me"] != "") {
                            $col = "A";
                            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $stt);
                            $col++;
                            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $data["cmt"]);
                            $col++;
                            $objPHPExcel->getActiveSheet()->setCellValueExplicit("$col" . $rowCount, format_phone($data["sdt_me"]), PHPExcel_Cell_DataType::TYPE_STRING);
                            $col++;
                            $objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $pre_con);
                            $col++;
                            $rowCount++;
                            $stt++;
                        }
                    }

                    ob_end_clean();
                    // Instantiate a Writer to create an OfficeOpenXML Excel .xlsx file
                    header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment; filename="sms-moi-hop.xls"');
                    $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
                    // Write the Excel file to filename some_excel_file.xlsx in the current directory
                    $objWriter->save('php://output');
                }
            }

            $url = "http://g3g4.vn:8008/smsws/api/sendSms.jsp";
            $username = "phanduong";
            $password = "123456";
            $content = "";
            $type= "1";
            $brandname = "";
            $target = "";

            $error = "";

            if(isset($_POST["send-ok"])) {
                if(isset($_POST["content"])) {
                    if($_POST["content"] != "") {
                        $content = addslashes($_POST["content"]);
                        $content=trim($content);
                        $pre_con="";
                        $temp=explode("\n",$content);
                        for($i=0;$i<count($temp);$i++) {
                            $me=trim($temp[$i]);
                            $me=str_replace(" ","%20",$me);
                            $pre_con.=$me."%0A";
                        }
                        $content=$pre_con;
                        if (isset($_POST["xao"])) {
                            $xao = "ORDER BY rand()";
                        } else {
                            $xao = "ORDER BY h.cmt ASC";
                        }
                        $query = "SELECT h.ID_HS,h.cmt,h.fullname,h.sdt_bo FROM hocsinh AS h 
                        INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
                        WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID') $xao";
                        $result = mysqli_query($db, $query);
                        while ($data = mysqli_fetch_assoc($result)) {
                            $pre_con = $content;
                            $pre_con = str_replace("{ho_ten_hoc_sinh}", mb_strtoupper(str_replace("-", " ", unicode_convert($data["fullname"])), "UTF-8"), $pre_con);
                            $pre_con = str_replace("{maso_hoc_sinh}", $data["cmt"], $pre_con);
                            $pre_con = str_replace("{sdt_phu_huynh}", $data["sdt_bo"], $pre_con);
                            $pre_con = str_replace("{mon_hoc}","Môn " . ucfirst(str_replace("-"," ",unicode_convert($mon_name))),$pre_con);
                            if (is_numeric($data["sdt_bo"]) && $data["sdt_bo"] != "X" && $data["sdt_bo"] != "") {
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url . "?username=" . $username . "&password=" . $password . "&mobile=" . $data["sdt_bo"] . "&content=" . $pre_con . "&type=" . $type . "&target=abc123xyz");
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_HEADER, false);
                                $output = curl_exec($ch);
                                curl_close($ch);
                                $error .= $output . "<br />";
                            }
                        }
                        $query = "SELECT h.ID_HS,h.cmt,h.fullname,h.sdt_me FROM hocsinh AS h 
                        INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
                        WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID') $xao";
                        $result = mysqli_query($db, $query);
                        while ($data = mysqli_fetch_assoc($result)) {
                            $pre_con = $content;
                            $pre_con = str_replace("{ho_ten_hoc_sinh}", mb_strtoupper(str_replace("-", " ", unicode_convert($data["fullname"])), "UTF-8"), $pre_con);
                            $pre_con = str_replace("{maso_hoc_sinh}", $data["cmt"], $pre_con);
                            $pre_con = str_replace("{sdt_phu_huynh}", $data["sdt_me"], $pre_con);
                            $pre_con = str_replace("{mon_hoc}","Môn " . ucfirst(str_replace("-"," ",unicode_convert($mon_name))),$pre_con);
                            if (is_numeric($data["sdt_me"]) && $data["sdt_me"] != "X" && $data["sdt_me"] != "") {
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url . "?username=" . $username . "&password=" . $password . "&mobile=" . $data["sdt_me"] . "&content=" . $pre_con . "&type=" . $type . "&target=abc123xyz");
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_HEADER, false);
                                $output = curl_exec($ch);
                                curl_close($ch);
                                $error .= $output . "<br />";
                            }
                        }
                    }
                }
            }

            ?>
            
            <div id="MAIN">
            
            	<div id="main-left">
                    
                    <div>
                    	<h3>Menu</h3>
                        <ul>
                            <li class="action"><a href="http://localhost/www/TDUONG/thaygiao/sms/"><i class="fa fa-newspaper-o"></i>Thông báo tiền học tháng <?php echo $thang_toi; ?> (Excel)</a></li>
                            <li class="action"><a href="http://localhost/www/TDUONG/thaygiao/sms-tuy-chon/"><i class="fa fa-newspaper-o"></i>Nội dung tùy chọn</a></li>
                            <li class="action"><a href="http://localhost/www/TDUONG/thaygiao/sms-tu-dong/"><i class="fa fa-newspaper-o"></i>SMS đơn lẻ</a></li>
                        </ul>
                    </div>
                    
                </div>
                
                <div id="main-mid">
                	<h2>Mời họp phụ huynh <span style="font-weight: 600;"><?php echo "môn $lop_mon_name"; ?></span></h2>
                	<div>
                    	<div class="status">
                            <?php echo $error; ?>
                            <form action="http://localhost/www/TDUONG/thaygiao/sms-tuy-chon/" method="post">
                                <table class="table">
                                    <tr style="background: #3E606F;">
                                        <th colspan="3"><span>Danh sách các biến</span></th>
                                    </tr>
                                    <tr>
                                        <td style="width: 10%;"><span>1</span></td>
                                        <td><span>Họ tên học sinh</span></td>
                                        <td><span>{ho_ten_hoc_sinh}</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>2</span></td>
                                        <td><span>Mã số học sinh</span></td>
                                        <td><span>{maso_hoc_sinh}</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>3</span></td>
                                        <td><span>Số điện thoại phụ huynh</span></td>
                                        <td><span>{sdt_phu_huynh}</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>4</span></td>
                                        <td><span>Tên môn học "Môn ..."</span></td>
                                        <td><span>{mon_hoc}</span></td>
                                    </tr>
                                    <tr style="background: #3E606F;">
                                        <th colspan="3"><span>Nội dung</span></th>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><textarea class="input" name="content" style="resize: vertical;padding: 2.5%;" rows="10" placeholder="Nội dung kèm các biến, ko có dấu"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td><span>Xáo trộn?</span></td>
                                        <td><input type="checkbox" class="input" name="xao" /></td>
                                        <td>
                                            <input type="submit" class="submit" value="Xuất Excel" id="xuat-ok" name="xuat-ok"/>
                                            <input type="submit" class="submit" value="Gửi luôn (Qua SMS đối tác)" id="xuat-ok" name="send-ok"/>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
               	</div>
            
            </div>
        
        </div>
        
    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>