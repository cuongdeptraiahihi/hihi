<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    if(isset($_GET["ngay"]) && isset($_GET["caID"]) && isset($_GET["lm"]) && isset($_GET["mon"]) && is_numeric($_GET["caID"]) && is_numeric($_GET["lm"]) && is_numeric($_GET["mon"])) {
        $ngay=str_replace("-","/",$_GET["ngay"]);
        $caID=$_GET["caID"];
        $lmID=$_GET["lm"];
        $monID=$_GET["mon"];
    } else {
        $ngay=date("d/m/Y");
        $caID=0;
        $lmID=0;
        $monID=0;
    }
    $cum=get_ca_cum($caID);
    $ngay_format=format_date_o($ngay);
	$lop_mon_name=get_lop_mon_name($lmID);
    $thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");

    $temp=check_exited_buoi(0, $caID, $ngay_format, $lmID, $monID);
    if(count($temp)==0) {
        $temp=add_diemdanh_buoi(0, $caID, $cum, $ngay_format,$lmID,$monID);
        diemdanh_nghi_dai($temp[1],$ngay_format,$lmID,$monID);
    }
    $ddID = $temp[0];
    $cumID = $temp[1];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>KIỂM TRA ĐIỂM DANH</title>
        
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

            $("input.diem-danh").click(function () {
                del_tr = $(this).closest("tr");
                ngay = "<?php echo $ngay; ?>";
                caID = <?php echo $caID; ?>;
                cdID = 0;
                hsID = $(this).attr("data-hsID");
                cum = <?php echo $cum; ?>;
                is_hoc = 0;
                is_tinh = 0;
                is_kt = 0;
                caCheck = 1;
                ajax_data = "ngay=" + ngay + "&monID=" + <?php echo $monID; ?> + "&lmID=" + <?php echo $lmID; ?> + "&caID=" + caID + "&cum0=" + cum + "&cdID=" + cdID + "&hsID=" + hsID + "&is_hoc=" + is_hoc + "&is_tinh=" + is_tinh + "&is_kt=" + is_kt + "&caCheck=" + caCheck;
                if($.isNumeric(caID) && $.isNumeric(cdID) && $.isNumeric(hsID)) {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity", "0.3");
                    $.ajax({
                        async: true,
                        data: ajax_data,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-diemdanh/",
                        success: function (a) {
                            if(a != "ok") {alert(a);}
                            $("#BODY").css("opacity", "1");
                            $("#popup-loading").fadeOut("fast");
                            del_tr.removeAttr("style");
                            del_tr.find("td input").remove();
                        }
                    });
                }
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
                    $file=NULL;
                    $html = "";
					if(isset($_POST["xem"])) {
                        if($_FILES["submit-file"]["error"]>0) {
                            $error=true;
                        } else {
                            $file = $_FILES["submit-file"]["name"];
                            move_uploaded_file($_FILES["submit-file"]["tmp_name"], "../import/" . $_FILES["submit-file"]["name"]);
                            include("include/PHPExcel/IOFactory.php");
                            $html = "<table class='table' style='margin-top:25px;'>";
                            $objPHPExcel = PHPExcel_IOFactory::load("../import/" . $file);
                            $stt = $dem = 0;
                            $html .= '<tr style=\'background:#3E606F;\'>';
                            $html .= '<th style=\'width:5%;\'><span>STT</span></th>';
                            $html .= '<th style=\'width:10%;\'><span>Vân tay</span></th>';
                            $html .= '<th style=\'width:15%;\'><span>Mã số</span></th>';
                            $html .= '<th style=\'width:20%;\'><span>Họ tên</span></th>';
                            $html .= '<th><span>Kết quả</span></th>';
                            $html .= '<th style=\'width:15%;\'><span>Điểm danh nhanh</span></th>';
                            $html .= '</tr>';
                            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                                $highestRow = $worksheet->getHighestRow();
                                $temp_van=array();
                                for ($row = 1; $row <= $highestRow; $row++) {
                                    $vantay = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                                    if(!isset($temp_van[$vantay]) && $vantay!=0) {
                                        $temp_van[$vantay] = 0;
                                        $result_arr = get_hs_by_van($vantay,$lmID,$monID);
                                        for ($i = 0; $i < count($result_arr); $i++) {
                                            $string = "";
                                            if (check_exited_diemdanh($cumID, $result_arr[$i]["ID_HS"], $lmID, $monID)) {
                                                $string = "Đã được điểm danh";
                                                $html .= "<tr>";
                                                $dem++;
                                            } else {
                                                $html .= "<tr style='background:yellow;'>";
                                            }
                                            $html .= '<td><span>' . ($stt + 1) . '</span></td>';
                                            $html .= '<td><span>' . $vantay . '</span></td>';
                                            $html .= '<td><span>' . $result_arr[$i]["cmt"] . '</span></td>';
                                            $html .= '<td><span>' . $result_arr[$i]["fullname"] . '</span></td>';
                                            $html .= '<td><span>' . $string . '</span></td>';
                                            if($string=="") {
                                                $html .= '<td><span><input class="submit diem-danh" data-hsID="'.$result_arr[$i]["ID_HS"].'" type="button" value="Đi muộn, ko KT" /></span></td>';
                                            } else {
                                                $html .= '<td><span></span></td>';
                                            }
                                            $html .= "</tr>";
                                            $stt++;
                                        }
                                    }
                                }
                            }

                            $result=get_all_diemdanh($ddID);
                            while($data=mysqli_fetch_assoc($result)) {
                                if(!isset($temp_van[$data["vantay"]])) {
                                    $html .= "<tr style='background:cyan;'>";
                                    $html .= '<td><span>' . ($stt + 1) . '</span></td>';
                                    $html .= '<td><span>' . $data["vantay"] . '</span></td>';
                                    $html .= '<td><span>' . $data["cmt"] . '</span></td>';
                                    $html .= '<td><span>' . $data["fullname"] . '</span></td>';
                                    $html .= '<td><span>Quên vân tay</span></td>';
                                    $html .= '<td><span></span></td>';
                                    $html .= "</tr>";
                                    $dem++;
                                }
                            }

                            $html .= "<tr>";
                            $html .= '<td><span>' . $stt . '</span></td>';
                            $html .= '<td colspan=\'3\'><span>Thống kê dữ liệu</span></td>';
                            $html .= '<td><span>Đã điểm danh: ' . $dem . ' / Chưa điểm danh: ' . ($stt-$dem) . '</span></td>';
                            $html .= '<td><span></span><td>';
                            $html .= "</tr>";
                            $html .= '</table>';
                        }
					}
					$result=get_info_ca($caID);
                    $data=mysqli_fetch_assoc($result);
                    $string=$thu_string[$data["thu"]-1].", $data[gio]";
				?>
                
                <div id="main-mid">
                	<h2>Kiểm tra điểm danh <span style="font-weight:600;">môn <?php echo $lop_mon_name."<br />ngày $ngay, $string"; ?></span></h2>
                	<div>
                    	<div class="status">
                        	<form action="http://localhost/www/TDUONG/thaygiao/kiem-tra-diem-danh/<?php echo str_replace("/","-",$ngay); ?>/<?php echo $caID; ?>/<?php echo $lmID; ?>/<?php echo $monID; ?>/" method="post" enctype="multipart/form-data">
                        		<table class="table">
                                    <tr>
                                    	<td style="width:50%;"><span>File excel điểm danh ( *.xlsx, *.xls )</span></td>
                                        <td style="width:50%;">
                                            <input type="file" class="submit" name="submit-file" />
                                        </td>
                                    </tr>
                                    <tr>
                                    	<th colspan="2"><input class="submit" style="width:50%;font-size:1.375em;" name="xem" type="submit" id="xem" value="Kiểm tra" /></th>
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