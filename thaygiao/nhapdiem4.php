<?php
	ob_start();
	session_start();
    ini_set('max_execution_time', 900);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $lmID = $_SESSION["lmID"];
    $monID = get_mon_of_lop($lmID);
    $lop_mon_name=get_lop_mon_name($lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>NHẬP ĐIỀM</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}#MAIN #main-mid .status .table tr td span.ketqua {font-weight:600;font-size:1.25em;}.see-kq {position:absolute;z-index:9;top:10px;font-weight:600;font-size:1.5em;}.win {color:red;}.lose {color:black;}.draw {color:yellow;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			$("#xem").click(function() {
				if(confirm("Bạn có chắc chắn?")) {
					return true;
				} else {
					return false;
				}
			});
            $("#select-buoi").change(function () {
                $("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3"), ngay = $("#select-buoi").find("option:selected").attr("data-ngay"), "" != ngay && $.ajax({
                    async: true,
                    data: "monID2=" + <?php echo $monID; ?> + "&lmID2=" + 0 + "&ngay=" + ngay,
                    type: "post",
                    url: "http://localhost/www/TDUONG/thaygiao/xuly-mon/",
                    success: function(a) {
                        $("#select-ca").html(a), $("#popup-loading").fadeOut("fast"), $("#BODY").css("opacity", "1")
                    }
                })
            });
            $("#select-ca").change(function () {
                var cum = $(this).find("option:selected").attr("data-cum");
                $("#cum").val(cum);
            });
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <?php
                    $error=false;
                    $file=$buoiID=$caID=$cum=NULL;
                    $html = "<table class='table' style='margin-top:25px;overflow-x: auto;'>
                        <tr style='background: #3E606F;'>
                            <th><span>Mã số</span></th>
                            <th><span>Điểm</span></th>
                            <th><span>Đề</span></th>
                        </tr>
                    </table>";
					if(isset($_POST["xem"])) {
					    if(isset($_POST["select-buoi"]) && $_POST["select-buoi"]!=0) {
					        $buoiID=$_POST["select-buoi"];
                        }
                        if(isset($_POST["select-ca"]) && $_POST["select-ca"]!=0) {
                            $caID=$_POST["select-ca"];
                        }
                        if(isset($_POST["cum"])) {
                            $cum=$_POST["cum"];
                        }
                        if($_FILES["submit-file"]["error"]>0) {
                            $error=true;
                        } else if($buoiID && $caID && $cum) {
                            $start = microtime(true);
                            $file = $_FILES["submit-file"]["name"];
                            move_uploaded_file($_FILES["submit-file"]["tmp_name"], "../import/" . $_FILES["submit-file"]["name"]);
                            include("include/PHPExcel/IOFactory.php");
                            $objPHPExcel = PHPExcel_IOFactory::load("../import/". $file);

                            $buoi=get_ngay_buoikt($buoiID);

                            $temp=check_exited_buoi(0, $caID, $buoi, 0, $monID);
                            if(count($temp)==0) {
                                $temp=add_diemdanh_buoi(0, $caID, $cum, $buoi, 0, $monID);
                                diemdanh_nghi_dai($temp[1], $buoi, 0, $monID);
                            }
                            $ddID=$temp[0];
                            $cumID=$temp[1];

                            $html = "<table class='table' style='margin-top:25px;overflow-x: auto;'>
                                <tr style='background: #3E606F;'>
                                    <th><span>Mã số</span></th>
                                    <th><span>Điểm</span></th>
                                    <th><span>Đề</span></th>
                                </tr>";
                            $result_arr=array();
                            $content1=$content2=$content3=$content4="";
                            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                                $highestRow = $worksheet->getHighestRow();
                                for ($row = 2; $row <= $highestRow; $row++) {
                                    $maso = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                                    $hsID = get_hs_id($maso);
                                    if($hsID != 0) {
                                        $diem = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                                        $de = get_de_hs($hsID, $lmID);
                                        if($de == "G" || $de == "B" || $de == "Y") {
                                            $query="SELECT ID_DIEM FROM diemkt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_LM='$lmID'";
                                            $result=mysqli_query($db,$query);
                                            if(mysqli_num_rows($result) != 0) {
                                                $data=mysqli_fetch_assoc($result);
                                                $query2="UPDATE diemkt SET diem='$diem',de='$de' WHERE ID_DIEM='$data[ID_DIEM]'";
                                                mysqli_query($db,$query2);
                                            } else {
                                                $content1 .= ",('$buoiID','$hsID','$diem','0','$de','0','','$lmID','0')";
                                                $content2 .= ",('$hsID','$buoiID','Điểm thi ngày " . format_dateup($buoi) . " của bạn là $diem điểm.','diem-thi','$lmID',now(),'small','new')";
                                                $content3 .= ",('$ddID','$hsID','1','0','0','0')";
                                            }
                                            $html .= "<tr>
                                                <td><span>$maso</span></td>
                                                <td><span>$diem</span></td>
                                                <td><span>$de</span></td>
                                            </tr>";
                                        } else {
                                            $html .= "<tr>
                                                <td><span>$maso</span></td>
                                                <td><span>$de</span></td>
                                                <td><span>Học sinh không học môn này!</span></td>
                                            </tr>";
                                        }
                                    } else {
                                        $html .= "<tr>
                                            <td><span>$maso</span></td>
                                            <td colspan='2'><span>Không có học sinh này!</span></td>
                                        </tr>";
                                    }
                                }
                            }
                            $time_elapsed_secs = format_diem(microtime(true) - $start);
                            $html .= '<tr><td colspan="3"><span>Thời gian chạy: '.$time_elapsed_secs.'s</span></td></tr>';
                            $html .= '</table>';

                            if($content1 != "" && $content2 != "" && $content3 != "") {
                                $content1 = substr($content1, 1);
                                $query2 = "INSERT INTO diemkt(ID_BUOI,ID_HS,diem,loai,de,note,made,ID_LM,more) VALUES $content1";
                                mysqli_query($db, $query2);
                                $content2 = substr($content2, 1);
                                $query2 = "INSERT INTO thongbao(ID_HS,object,content,danhmuc,ID_LM,datetime,loai,status) VALUES $content2";
                                mysqli_query($db, $query2);
                                $content3 = substr($content3, 1);
                                $query2 = "INSERT INTO diemdanh(ID_DD,ID_HS,ca_check,is_hoc,is_tinh,is_kt) VALUES $content3";
                                mysqli_query($db, $query2);
                            }
                        }
					}
				?>
                
                <div id="main-mid">
                	<h2>Nhập điểm từ file Excel <span style="font-weight:600;">môn <?php echo $lop_mon_name; ?></span></h2>
                	<div>
                    	<div class="status">
                        	<form action="http://localhost/www/TDUONG/thaygiao/nhapdiem4.php" method="post" enctype="multipart/form-data">
                        		<table class="table">
                                    <tr>
                                        <td style="width:50%;"><span>Buổi kiểm tra</span></td>
                                        <td style="width:50%;">
                                            <select class="input buoi" name="select-buoi" id="select-buoi" style="height:auto;width:100%;">
                                                <option value="0" data-ngay="">Chọn buổi kiểm tra</option>
                                            <?php
                                                $result5=get_all_buoikt($monID,10);
                                                while($data5=mysqli_fetch_assoc($result5)) {
                                                    echo"<option value='$data5[ID_BUOI]' data-ngay='$data5[ngay]'>".format_dateup($data5["ngay"])."</option>";
                                                }
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>Ca kiểm tra (sẽ phạt sai ca)</span></td>
                                        <td>
                                            <select class="input" name="select-ca" id="select-ca" style="height:auto;width:100%;">
                                                <option value="0">Chọn ca hiện hành</option>
                                            </select>
                                            <input type="hidden" name="cum" id="cum" value="0" />
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td><span>File excel nhập điểm ( *.xlsx, *.xls )</span></td>
                                        <td>
                                            <input type="file" class="submit" name="submit-file" />
                                        </td>
                                    </tr>
                                    <tr>
                                    	<th colspan="2"><input class="submit" style="width:50%;font-size:1.375em;" name="xem" type="submit" id="xem" value="Nhập và Chấm" /></th>
                                    </tr>
                                </table>
                                <?php echo $html; ?>
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