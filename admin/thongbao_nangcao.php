<?php
	ob_start();
	session_start();
    ini_set('max_execution_time', 2000);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $monID=$_SESSION["mon"];
    $mon_name=get_mon_name($monID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>THÔNG BÁO</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->

        <?php
        if($_SESSION["mobile"]==1) {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/admin/css/mbocuc.css'>";
        } else {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/admin/css/bocuc.css'>";
        }
        ?>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/jquery-ui.css">
        
        <style>
            #CHANGE_LOP {display: none;}
			#MAIN > #main-mid {width:100%;}.input {text-align:center;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.js"></script>
        <script>
		$(document).ready(function() {
		    $("#send").click(function() {
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
            $error=false;
            $file=$html=NULL;
            if(isset($_POST["view"])) {
                if($_FILES["data-file"]["error"]>0) {
                    $error=true;
                } else {
                    $dem=0;
                    $file = $_FILES["data-file"]["name"];
                    move_uploaded_file($_FILES["data-file"]["tmp_name"], "../import/" . $_FILES["data-file"]["name"]);
                    include("include/PHPExcel/IOFactory.php");
                    $html = "<table border='1'>";
                    $objPHPExcel = PHPExcel_IOFactory::load("../import/" . $file);
                    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                        $highestRow = $worksheet->getHighestRow();
                        $stt = 0;
                        for ($row = 2; $row <= $highestRow; $row++) {
                            $maso = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                            $hsID = get_hs_id($maso);
                            $content = $worksheet->getCellByColumnAndRow(1, $row)->getValue();

                            $token=array();
                            if(!check_access_mon($hsID,$monID) || !valid_maso($maso) || !valid_id($hsID)) {
                                continue;
                            } else {
                                $token = get_token_hs($hsID);
                                $dem+=count($token);
                            }

                            $html .= "<tr>";

                            $html .= '<td>' . $stt . '</td>';
                            $html .= '<td>' . $maso . '</td>';
                            $html .= '<td>' . $content . '</td>';
                            $html .= '<td>-' . count($token) . '-</td>';
                            $html .= "</tr>";
                            $stt++;
                        }
                    }
                    $html .= "<tr>";

                    $html .= '<td colspan="3">Tổng số tin nhắn sẽ đc gửi</td>';
                    $html .= '<td>' . $dem . '</td>';
                    $html .= "</tr>";
                    $html .= '</table>';
                }
            }

            if(isset($_POST["send"])) {
                if($_FILES["data-file"]["error"]>0) {
                    $error=true;
                } else {
                    $file = $_FILES["data-file"]["name"];
                    move_uploaded_file($_FILES["data-file"]["tmp_name"], "../import/" . $_FILES["data-file"]["name"]);
                    include("include/PHPExcel/IOFactory.php");
                    $html = "<table border='1'>";
                    $objPHPExcel = PHPExcel_IOFactory::load("../import/" . $file);
                    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                        $highestRow = $worksheet->getHighestRow();
                        $stt = 0;
                        for ($row = 2; $row <= $highestRow; $row++) {
                            $maso = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                            $hsID = get_hs_id($maso);

                            if(!check_access_mon($hsID,$monID) || !valid_maso($maso) || !valid_id($hsID)) {
                                continue;
                            } else {
                                $token = get_token_hs($hsID);
                                if(count($token) > 0) {
                                    $content = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                                    add_thong_bao_hs($hsID,get_lop_hs($hsID),$content,"push-noti",$monID);
                                } else {
                                    continue;
                                }
                            }

                            $html .= "<tr>";
                            $html .= '<td>' . $stt . '</td>';
                            $html .= '<td>' . $maso . '</td>';

                            $query="SELECT content FROM log WHERE ID_HS='$hsID' AND type='push-noti' ORDER BY datetime DESC LIMIT 1";
                            $result=mysqli_query($db,$query);
                            $data=mysqli_fetch_assoc($result);
                            $html .= '<td>' . $data["content"] . '</td>';
                            $html .= "</tr>";
                            $stt++;
                        }
                    }
                    $html .= '</table>';
                }
            }
            ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>Thông báo tới học sinh <span style="font-weight: 600;"><?php echo "môn $mon_name"; ?></span></h2>
                	<div>
                    	<div class="status">
                            <form action="http://localhost/www/TDUONG/admin/thong-bao-nang-cao/" method="post" enctype="multipart/form-data">
                                <table class="table">
                                    <tr style="background:#3E606F;">
                                        <th style="width:15%;"><span>Chọn file Excel</span></th>
                                        <th><input type="file" class="submit" name="data-file" /></th>
                                        <th style="width: 15%;"><span>Hành động</span></th>
                                        <th style="width:20%;"><input type="submit" class="submit" value="Xem trước" name="view" style="margin-right: 10px;" /><input type="submit" name="send" class="submit" value="Gửi" id="send" /></th>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><span>Cấu trúc bảng Excel gồm 2 cột: Mã học sinh và Nội dung (Ko tính hàng đầu tiên)</span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><span style="font-weight: 600;">Học sinh nào không thuộc môn <?php echo $mon_name; ?> sẽ bị bỏ qua!!!</span></td>
                                    </tr>
                                </table>
                            </form>
                            <?php echo $html; ?>
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