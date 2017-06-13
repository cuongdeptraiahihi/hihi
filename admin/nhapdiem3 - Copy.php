<?php
	ob_start();
	session_start();
    ini_set('max_execution_time', 900);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $monID=$_SESSION["mon"];
    $mon_name=get_mon_name($monID);

    $mon_arr=array();
    $result=get_all_lop_mon2($monID);
    while($data=mysqli_fetch_assoc($result)) {
        $mon_arr[$data["ID_LM"]]=$data["name"];
    }
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
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
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
                    url: "http://localhost/www/TDUONG/admin/xuly-mon/",
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
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <?php
                    $error=false;
                    $file=$buoiID=$caID=$cum=NULL;
                    $html = "";
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

                            $nhom_string="";
                            $nhom_arr=$cd_arr=array();
                            $query="SELECT ID_N,ID_LM FROM nhom_de WHERE object='$buoiID'";
                            $result = mysqli_query($db, $query);
                            while($data=mysqli_fetch_assoc($result)) {
                                $nhom_arr[$data["ID_LM"]]=$data["ID_N"];
                                $nhom_string.=",'$data[ID_N]'";
                            }
                            $nhom_string=substr($nhom_string,1);

                            $query = "SELECT n.ID_C,e.ID_CD,e.ID_LM FROM de_noi_dung AS n
                            INNER JOIN de_thi AS t ON t.ID_DE=n.ID_DE AND t.main='1' AND t.nhom IN ($nhom_string)
                            INNER JOIN cau_hoi AS c ON c.ID_C=n.ID_C
                            INNER JOIN chuyende AS e ON e.maso IN (SELECT maso FROM chuyen_de_con WHERE ID_CD=c.ID_CD)
                            ORDER BY n.ID_C ASC,e.ID_LM ASC";
                            $result = mysqli_query($db, $query);
                            while($data=mysqli_fetch_assoc($result)) {
                                $cd_arr[$data["ID_C"]][$data["ID_LM"]]=$data["ID_CD"];
                            }

//                            $loaide_arr=array();
//                            $query="SELECT ID_D,name FROM loai_de";
//                            $result = mysqli_query($db, $query);
//                            while($data=mysqli_fetch_assoc($result)) {
//                                $loaide_arr[$data["name"]]=$data["ID_D"];
//                            }

                            $de_arr=array();
                            $query = "SELECT n.ID_C,n.sort AS csort,a.sort AS dsort,t.maso,t.loai FROM de_noi_dung AS n
                            INNER JOIN de_thi AS t ON t.ID_DE=n.ID_DE AND t.nhom IN ($nhom_string)
                            INNER JOIN de_cau_dap_an AS a ON a.ID_DE=n.ID_DE
                            INNER JOIN dap_an_ngan AS d ON d.ID_DA=a.ID_DA AND d.ID_C=n.ID_C AND d.main='1'
                            ORDER BY t.maso ASC,n.sort ASC";
                            $result = mysqli_query($db, $query);
                            while($data=mysqli_fetch_assoc($result)) {
                                if(!isset($de_arr[$data["maso"]])) {
                                    $de_arr[$data["maso"]]["loai"]=$data["loai"];
                                }
                                $de_arr[$data["maso"]]["csort"]=array(
                                    "ID_C" => $data["ID_C"],
                                    "csort" => $data["csort"],
                                    "dsort" => $data["dsort"]
                                );
                            }

                            $tb = get_cmt_diem_loai2(0);
                            $buoi=get_ngay_buoikt($buoiID);
                            $is_phat = get_is_phat($monID);

                            $temp=check_exited_buoi(0, $caID, $buoi, 0, $monID);
                            if(count($temp)==0) {
                                $temp=add_diemdanh_buoi(0, $caID, $cum, $buoi, 0, $monID);
                                diemdanh_nghi_dai($temp[1], $buoi, 0, $monID);
                            }
                            $ddID=$temp[0];
                            $cumID=$temp[1];

                            $stt=insert_diem_count($buoiID,0);
                            $html = "<table class='table' style='margin-top:25px;'>
                                <tr style='background: #3E606F;'>
                                    <th><span>STT</span></th>
                                    <th><span>Mã số</span></th>
                                    <th><span>Mã đề</span></th>
                                    <th><span>Đề</span></th>
                                </tr>";
                            $result_arr=array();
                            $content1=$content2=$content3=$content4="";
                            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                                $highestRow = $worksheet->getHighestRow();
                                for ($row = 2; $row <= $highestRow; $row++) {
                                    $html .= "<tr>";

                                    $temp=$worksheet->getCellByColumnAndRow(2, $row)->getValue();
                                    $maso=substr($temp,0,2) . "-". substr($temp,2);

                                    if(valid_maso($maso)) {
                                        $html .= "<td><span>$stt</span></td>";
                                        $html .= "<td><span>$maso</span></td>";

                                        $made=$worksheet->getCellByColumnAndRow(3, $row)->getValue();

                                        $html .= "<td><span>$made</span></td>";
                                        $query0 = "SELECT h.ID_HS,m.ID_LM,m.de,k.ID_DIEM,c.ID_CA FROM hocsinh AS h
                                        INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS
                                        INNER JOIN ca_hientai AS c ON c.ID_HS=h.ID_HS AND c.cum='$cum'
                                        LEFT JOIN diemkt AS k ON k.ID_BUOI='$buoiID' AND k.ID_HS=h.ID_HS AND k.ID_LM=m.ID_LM
                                        WHERE h.cmt='$maso'
                                        ORDER BY m.ID_STT ASC
                                        LIMIT 1";
                                        $result0 = mysqli_query($db, $query0);
                                        if (mysqli_num_rows($result0) != 0) {
                                            $data0 = mysqli_fetch_assoc($result0);

                                            if (isset($data0["ID_DIEM"]) && is_numeric($data0["ID_DIEM"])) {
                                                $html .= "</tr>";
                                                continue;
                                            }

                                            $html .= "<td><span>$data0[de]</span></td>";

                                            $query = "SELECT n.ID_C,n.sort AS csort,a.sort AS dsort FROM de_noi_dung AS n
                                            INNER JOIN de_thi AS t ON t.ID_DE=n.ID_DE AND t.maso='$made' AND t.nhom='".$nhom_arr[$data0["ID_LM"]]."' AND t.loai='".$loaide_arr[$data0["de"]]."'
                                            INNER JOIN de_cau_dap_an AS a ON a.ID_DE=n.ID_DE
                                            INNER JOIN dap_an_ngan AS d ON d.ID_DA=a.ID_DA AND d.ID_C=n.ID_C AND d.main='1'
                                            ORDER BY n.sort ASC";
                                            $result = mysqli_query($db, $query);
                                            $num = mysqli_num_rows($result);
                                            $diem = 0;
                                            if ($num != 0) {
                                                $diem_each = format_diem(10 / $num);
                                                while ($data = mysqli_fetch_assoc($result)) {
                                                    $dapan = $worksheet->getCellByColumnAndRow(5 + $data["csort"], $row)->getValue();
                                                    if ($dapan == "A")
                                                        $da_sort = 1;
                                                    else if ($dapan == "B")
                                                        $da_sort = 2;
                                                    else if ($dapan == "C")
                                                        $da_sort = 3;
                                                    else if ($dapan == "D")
                                                        $da_sort = 4;
                                                    else if ($dapan == "-")
                                                        $da_sort = 5;
                                                    else $da_sort = NULL;

                                                    if ($da_sort == $data["dsort"]) {
                                                        if (isset($data0["ID_DIEM"]) && is_numeric($data0["ID_DIEM"])) {
//                                                            update_chuyende_diem3($buoiID, $data0["ID_HS"], $cd_arr[$data["ID_C"]][$data0["ID_LM"]], $diem_each . "/" . $diem_each, $data["csort"], $da_sort, "", $data0["ID_LM"]);
                                                        } else {
                                                            $content1 .= ",('$buoiID','" . $cd_arr[$data["ID_C"]][$data0["ID_LM"]] . "','$data0[ID_HS]','$diem_each/$diem_each','$data[csort]','$da_sort','','$data0[ID_LM]')";
                                                        }
                                                        $diem += $diem_each;
                                                        $html .= "<td style='background:#29B6F6;'><span style='color:#FFF;'>$dapan</span></td>";
                                                    } else {
                                                        if (isset($data0["ID_DIEM"]) && is_numeric($data0["ID_DIEM"])) {
//                                                            update_chuyende_diem3($buoiID, $data0["ID_HS"], $cd_arr[$data["ID_C"]][$data0["ID_LM"]], "0/" . $diem_each, $data["csort"], $da_sort, "", $data0["ID_LM"]);
                                                        } else {
                                                            $content1 .= ",('$buoiID','" . $cd_arr[$data["ID_C"]][$data0["ID_LM"]] . "','$data0[ID_HS]','0/$diem_each','$data[csort]','$da_sort','','$data0[ID_LM]')";
                                                        }
                                                        $html .= "<td><span>$dapan</span></td>";
                                                    }
                                                }

                                                if (isset($data0["ID_DIEM"]) && is_numeric($data0["ID_DIEM"])) {
//                                                    update_diem_hs($data0["ID_HS"], $buoiID, $diem, $data0["de"], 0, 0, $made, $data0["ID_LM"]);
                                                } else {
                                                    $content2 .= ",('$buoiID','$data0[ID_HS]','$diem','0','$data0[de]','0','$made','$data0[ID_LM]','$stt')";
                                                }
                                                $html .= "<td><span>$diem</span></td>";

                                                $phat = 0;
//                                                if ($is_phat) {
//                                                    if (!check_binh_voi($data0["ID_HS"], $buoiID, $data0["ID_LM"])) {
//                                                        if ($diem < 5.25 && check_exited_thachdau4($data0["ID_HS"], $buoi, $data0["ID_LM"])) {
//
//                                                        } else {
//                                                            $phat = get_phat_diemkt($data0["ID_HS"], $diem, $data0["de"], 1, 0, $data0["ID_LM"], $mon_arr[$data0["ID_LM"]], $buoiID, $buoi, false);
//                                                        }
//                                                    }
//                                                }
                                                $content3 .= ",('$data0[ID_HS]','$buoiID','Điểm thi ngày " . format_dateup($buoi) . " của bạn là $diem điểm. $tb. Mã lấy bài là $stt','diem-thi','$data0[ID_LM]',now(),'small','new')";
                                                if ($phat != 0) {
                                                    $html .= "<td><span>$phat</span></td>";
                                                } else {
                                                    $html .= "<td><span></span></td>";
                                                }

                                                $stt++;
                                            } else {
                                                $html .= "<td><span>Sai mã đề</span></td>";
                                            }

                                            if($data0["ID_CA"]!=$caID) {
                                                $caCheck = 0;
                                                $html .= "<td><span>Sai ca</span></td>";
                                            } else {
                                                $caCheck=1;
                                                $html .= "<td><span>Đúng ca</span></td>";
                                            }

                                            $content4 .= ",('$ddID','$data0[ID_HS]','$caCheck','0','0','0')";
                                        }
                                    }

                                    $html .= "</tr>";
                                }
                            }
                            $time_elapsed_secs = format_diem(microtime(true) - $start);
                            $html .= '<tr><td colspan="4"><span>Thời gian chạy: '.$time_elapsed_secs.'s</span></td></tr>';
                            $html .= '</table>';

//                            $content1=substr($content1,1);
//                            $query2="INSERT INTO chuyende_diem(ID_BUOI,ID_CD,ID_HS,diem,cau,y,note,ID_LM) VALUES $content1";
//                            mysqli_query($db, $query2);
//                            $content2=substr($content2,1);
//                            $query2="INSERT INTO diemkt(ID_BUOI,ID_HS,diem,loai,de,note,made,ID_LM,more) VALUES $content2";
//                            mysqli_query($db, $query2);
//                            $content3=substr($content3,1);
//                            $query2="INSERT INTO thongbao(ID_HS,object,content,danhmuc,ID_LM,datetime,loai,status) VALUES $content3";
//                            mysqli_query($db, $query2);
//                            $content4=substr($content4,1);
//                            $query2="INSERT INTO diemdanh(ID_DD,ID_HS,ca_check,is_hoc,is_tinh,is_kt) VALUES $content4";
//                            mysqli_query($db, $query2);
                        }
					}
				?>
                
                <div id="main-mid">
                	<h2>Nhập điểm từ file Excel <span style="font-weight:600;">môn <?php echo $mon_name; ?></span></h2>
                	<div>
                    	<div class="status">
                        	<form action="http://localhost/www/TDUONG/admin/nhapdiem3.php" method="post" enctype="multipart/form-data">
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