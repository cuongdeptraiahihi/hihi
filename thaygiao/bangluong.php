<?php
	ob_start();
	session_start();
	ini_set('max_execution_time', 120);
	require_once("../model/open_db.php");
    require_once("../model/model.php");
    require_once("access_admin.php");
	$monID=$_SESSION["mon"];
	/*if(isset($_GET["lopID"]) && is_numeric($_GET["lopID"])) {
		$lopID=$_GET["lopID"];
	} else {
		$lopID=0;
	}
	$lopID=$_SESSION["lop"];*/
	//$mau=get_user_mau($hsID);
	$mau="#3E606F";
    $id=get_admin_id_mon($monID);
	
	$result2=get_mon_info($monID);
	$data2=mysqli_fetch_assoc($result2);
	$mon_name=$data2["name"];
	$num_thang=$data2["thang"]*1.5;
	
	//$lop_name=get_lop_name($lopID);
	$muc_tien=get_muctien("tien_hoc_".unicode_convert($mon_name));
    $tien_tra=get_muctien("tien_hoc_tra_".unicode_convert($mon_name));
    $tien_tra_old=$tien_tra;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>BẢNG LƯƠNG MÔN <?php echo mb_strtoupper($mon_name); ?></title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
        
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/tongquan_admin2.css"/>
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/jquery-ui.css" />
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#3E606F;cursor:pointer;}#COLOR i:hover {color:#3E606F;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-table table td {border:1px solid <?php echo $mau?>;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}#MAIN .main-div .main-chart3 nav.chart-button:hover {background:<?php echo $mau;?>;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:#3E606F;width:7px;height:7px;margin-left:2.5px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:35%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border:1px solid <?php echo $mau; ?>;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}.back {background-color:<?php echo $mau; ?>;}
			
			#MAIN .main-div #main-table .table td span {color:#3E606F;font-size:10px;}#MAIN .main-div #main-table .table td a {color:#3E606F;font-size:10px;}#MAIN .main-div #main-table .table td a:hover {text-decoration: underline;} #MAIN .main-div #main-table .table td span.span-boi {font-weight:600;text-transform:uppercase;}#MAIN .main-div #main-table .table td.td-boi {background:#3E606F;border-right:1px solid #FFF;}#MAIN .main-div #main-table .table td.td-boi span {color:#FFF;font-weight:600;text-transform:uppercase;}#MAIN .main-div #main-table .table td.td-boi span.span-boi2 {font-size:22px;}.input {background:none;color:#3E606F;font-size:10px;}
			
			#UPDATE {position:fixed;right:10px;top:10px;z-index:99;}
            #tro-giang tr.data-tr td {padding: 15px 0 15px 0 !important;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.js"></script>
        <script>
			$(document).ready(function() {
				function t(t, a, n) {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity", "0.3");
                    ajax_data = "[";
                    t.each(function(index,element) {
                        sttID = $(element).attr("data-sttID");
                        datetime = $(element).find("td input.input-date").val().trim();
                        tien = $(element).find("td input.input-tien").val().trim();
                        if(datetime!="" && datetime!=" " && $.isNumeric(sttID) && tien!="" && tien!=" ") {
                            ajax_data += '{"datetime":"' + datetime + '","tien":"' + tien + '","sttID":"' + sttID + '"},';
                        }
                    });
                    ajax_data+='{"monID":"<?php echo $monID; ?>"}]';
                    if(ajax_data!="") {
                        $.ajax({
                            async: true,
                            data: "data=" + Base64.encode(ajax_data) + "&note=" + a,
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-bangluong/",
                            success: function(result) {
                               location.reload();
                            }
                        })
                    }

				}
				$(".input-date").datepicker({
					dateFormat: "dd/mm/yy",
					changeMonth: true,
					changeYear: true
				});
                $("#tong-luong-show").html($("#tong-luong").val());
                $("input.update").click(function() {
					t($("#thanh-toan tr.data-tr"), "thanh_toan", $(this))
				}), $("input.update2").click(function() {
					t($("#tro-giang tr.data-tr"), "tro_giang", $(this))
				});

                var tr_height = $("#table-all tr").length -1;
                $("#table-all tr:eq(1)").append("<td rowspan='"+tr_height+"'><span class='span-boi'>"+$("#luong").val()+"</span></td><td rowspan='"+tr_height+"'><span class='span-boi'>"+$("#paid").val()+"</span></td><td rowspan='"+tr_height+"'><span class='span-boi'>"+$("#tong-pay").val()+"</span></td>");

			    $("table.main-table").each(function(index,element) {
			       if($(element).find("tr").length<=1) {
                        $(element).hide();
                   }
                });

                $("input.tong-luong").each(function(index,element) {
                   $("span#tong-luong-show-"+$(element).attr("data-lmID")).html($(element).val());
                });
			});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
            
            <div id="MAIN">
            
            	<div id="UPDATE">
                </div>
            	
              	<div class="main-div back" id="main-top">
                	<h1>Bảng lương chi tiết môn <?php echo $mon_name;?></h1>
                </div>
                
                <div class="main-div hideme">
                	<div id="main-table">
                    	<table class="table" style="margin-top:25px;" id="table-all">
                            <tr>
                                <td class="td-boi" style="width: 8%;"><span>Lớp</span></td>
                                <td class="td-boi" style="width: 23%;"><span>Tổng lương đến <?php echo format_month(get_last_time(date("Y"),date("m"))); ?></span></td>
                                <td class="td-boi" style="width: 23%;"><span>Tổng lương trợ giảng</span></td>
                                <td class="td-boi" style="width: 23%;"><span>Tổng tiền đã thanh toán</span></td>
                                <td class="td-boi" style="width: 23%;"><span>Số tiền nhận tiếp theo</span></td>
                            </tr>
                            <?php
                                $result=get_all_lop_mon2($monID);
                                while($data=mysqli_fetch_assoc($result)) {
                                    echo"<tr>
                                        <td><span class='span-boi'>$data[name]</span></td>
                                        <td><span class='span-boi' id='tong-luong-show-$data[ID_LM]'></span></td>
                                    </tr>";
                                }
                                $result=get_lop_mon_old($monID);
                                while($data=mysqli_fetch_assoc($result)) {
                                    echo"<tr>
                                        <td><span class='span-boi'>$data[content]</span></td>
                                        <td><span class='span-boi' id='tong-luong-show-$data[note]'></span></td>
                                    </tr>";
                                }
                            ?>
                        </table>
                        <table class="table" style="margin-top:25px;float:left;width: 47.5%;margin-right: 5%;" id="thanh-toan">
                        	<tr>
                            	<td class="td-boi" style="width:33.5%;" rowspan="37"><span>Các đợt<br />thanh<br />toán</span></td>
                                <td class="td-boi" style="width:33.25%;"><span>Ngày</span></td>
                                <td class="td-boi" style="width:33.25%;"><span>Tiền</span></td>
                            </tr>
                            <?php
                            $paid=0;
                            $result2=get_thanh_toan("",$monID,"thanh_toan");
                            while($data2=mysqli_fetch_assoc($result2)) {
                                echo"<tr class='data-tr' data-sttID='$data2[ID_STT]'>
                                    <td><input type='text' class='input input-date' value='".format_dateup($data2["datetime"])."' /></td>
                                    <td><input type='text' class='input input-tien' value='".format_price($data2["money"])."' /></td>
                                </tr>";
                                $paid+=$data2["money"];
                            }
                            echo"<tr class='data-tr' data-sttID='0'>
                                <td><input type='text' class='input input-date' placeholder='Click để chọn ngày' /></td>
                                <td><input type='text' class='input input-tien' placeholder='Số tiền' /></td>
                            </tr>";
                            ?>
                            <tr>
                            	<td colspan="3"><input type="submit" class="submit update" style="background:#3E606F;border-bottom:2px solid black;" value="Cập nhật" /></td>
                            </tr>
                        </table>
                        <table class="table" style="margin-top:25px;float:left;width: 47.5%;" id="tro-giang">
                        	<tr>
                            	<td class="td-boi" style="width:33.5%;" rowspan="37"><span>Lương<br />trợ<br />giảng</span></td>
                                <td class="td-boi" style="width:33.25%;"><span>Tháng</span></td>
                                <td class="td-boi" style="width:33.25%;"><span>Tiền</span></td>
                            </tr>
                            <?php
                            $luong=0;
//                            $temp=split_month(get_lop_mon_in(get_mon_main()));
//                            $nam=$temp[0];
//                            // Cộng 1 và trừ 1 để bỏ số 0 ở đầu tháng
//                            $thang=$temp[1];
//                            $count=1;
//                            while($count<=$num_thang) {
//                                $thang=format_month_db($thang);
//                                $luong_tinh=0;
//                                if($nam<date("Y") || ($nam==date("Y") && $thang<=date("m"))) {
//                                    if (!check_thanh_toan("$nam-$thang", $monID, "tro_giang")) {
//                                        $query2 = "SELECT o.ID_O,i.price,p.phan FROM options AS o INNER JOIN info_trogiang AS i ON i.date_in<='$nam-$thang-07' AND i.ID_O=o.ID_O INNER JOIN pay_trogiang AS p ON p.ID_O=o.ID_O AND p.ID_A='$id' WHERE o.type='tro-giang-code' AND o.note2='1' ORDER BY o.ID_O DESC";
//                                        $result2 = mysqli_query($db, $query2);
//                                        $luong_tam = 0;
//                                        while ($data2 = mysqli_fetch_assoc($result2)) {
//                                            $luong_tam = $data2["price"] * ($data2["phan"] / 100);
//                                            if ($luong_tam != 0) {
//                                                add_thanhtoan($luong_tam, "$nam-$thang", $data2["ID_O"], $monID, "tro_giang");
//                                            }
//                                            $luong_tinh += $luong_tam;
//                                        }
//                                    } else {
//                                        $result2 = get_thanh_toan("$nam-$thang", $monID, "tro_giang");
//                                        $data2 = mysqli_fetch_assoc($result2);
//                                        $luong_tinh = $data2["price"];
//                                    }
//                                }
//                                if($luong_tinh!=0) {
//                                    echo "<tr class='data-tr'>
//                                        <td><span>" . format_month("$nam-$thang") . "</span></td>
//                                        <td class='need-ex'>
//                                            <a href='http://localhost/www/TDUONG/thaygiao/bang-luong-tro-giang/$nam-$thang/' target='_blank'>" . format_price($luong_tinh) . "</a>
//                                        </td>
//                                    </tr>";
//                                    $luong+=$luong_tinh;
//                                }
//                                $temp=explode("-",get_next_time($nam,$thang));
//                                $nam=$temp[0];
//                                $thang=$temp[1];
//                                $count++;
//                            }
                            ?>
                        </table>
                        <div class="clear"></div>
                  	<?php
                        $total_all=0;
                        $result5=get_all_lop_mon2($monID);
                        while($data5=mysqli_fetch_assoc($result5)) {
                            echo"<div style='padding: 20px 0 20px 0;margin-top: 25px;text-align: center;background: #3E606F;'><h2 style='color:#FFF;text-transform: uppercase;font-size:22px;'>Lớp $data5[name]</h2></div>";
                            $year=date("Y");
                            $month=date("m");
                            $days_of_month=array("31","28","31","30","31","30","31","31","30","31","30","31");
                            $temp=split_month($data5["date_in"]);
                            $nam=$temp[0];
                            // Cộng 1 và trừ 1 để bỏ số 0 ở đầu tháng
                            $thang=$temp[1]+1-1;
                            $total_con=0;
                            $count=1;
                            while($count<=$num_thang) {
                                ?>
                                <table class="table main-table" style="margin-top:25px;">
                                    <tr>
                                        <td class="td-boi" style="border-bottom:1px solid #FFF;width:16%;"><span class='span-boi2'><?php echo $nam; ?></span></td>
                                        <td style="width:17%;"><span style="text-transform:uppercase">Học sinh đóng học bình thường</span>
                                        </td>
                                        <td style="width:16%;"><span style="text-transform:uppercase">Học sinh được giảm học phí</span>
                                        </td>
                                        <td style="width:16%;"><span style="text-transform:uppercase">Học sinh học không đóng đủ</span>
                                        </td>
                                        <td style="width:16%;"><span style="text-transform:uppercase">Học sinh chưa đóng học</span>
                                        </td>
<!--                                        <td style="width:14%;"><span style="text-transform:uppercase">Số học sinh nghỉ hẳn</span></td>-->
<!--                                        <td style="width:12%;"><span style="text-transform:uppercase">Tiền thưởng</span>-->
                                        </td>
                                        <td><span class="span-boi">Tổng</span></td>
                                    </tr>
                                    <?php
                                    while ($thang <= 12) {
                                        $thang = format_month_db($thang);
                                        if (($nam < $year) || ($nam == $year && $thang <= $month + 2)) {
                                            if ($nam % 4 == 0) {
                                                $last_day = 29;
                                            } else {
                                                $last_day = $days_of_month[$thang - 1];
                                            }
                                            $hs_total = $hs_nghi = $num = $hs_new = 0;
                                            if ($nam < $year || ($nam == $year && $thang <= $month + 2)) {
                                                $hs_arr = array("'0'");
                                                $hs_arr[] = "'0'";
                                                $tien = $hs_bt = 0;
                                                $tien_giam = $hs_giam = 0;
                                                $tien_new = $hs_new = 0;
                                                $tien_tra = $tien_tra_old;
                                                $query="SELECT t.ID_HS,t.money AS tien,g.ID_STT FROM tien_hoc AS t
                                                INNER JOIN hocsinh_mon AS m ON m.ID_HS=t.ID_HS AND m.ID_LM='$data5[ID_LM]'
                                                LEFT JOIN giam_gia AS g ON g.ID_HS=t.ID_HS AND g.ID_MON='$monID'
                                                WHERE t.ID_LM='$data5[ID_LM]' AND t.date_dong='$nam-$thang'";
                                                $result = mysqli_query($db, $query);
                                                while($data = mysqli_fetch_assoc($result)) {
                                                    if($data["tien"] >= $muc_tien && !isset($data["ID_STT"])) {
                                                        $tien += $data["tien"];
                                                        $hs_bt++;
                                                    } else if(isset($data["ID_STT"])) {
                                                        $tien_giam += $data["tien"]*(2/3);
                                                        $hs_giam++;
                                                    } else {
                                                        $tien_new += $data["tien"]*(2/3);
                                                        $hs_new++;
                                                    }
                                                    $hs_arr[] = "'".$data["ID_HS"]."'";
                                                }
                                                $hs_str = implode(",",$hs_arr);

                                                // Đếm tổng số học sinh ĐÃ đóng tiền mà là học sinh cũ (thời điểm vào học ở trước mùng 1 của tháng tính tiền)
//                                                $query="SELECT SUM(tien_hoc.money) AS tien,COUNT(tien_hoc.ID_STT) AS dem FROM tien_hoc INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=tien_hoc.ID_HS AND hocsinh_mon.date_in<='$nam-$thang-".get_last_day("$nam-$thang")."' AND hocsinh_mon.ID_LM='$data5[ID_LM]' WHERE tien_hoc.ID_HS NOT IN (SELECT ID_HS FROM giam_gia WHERE giam_gia.ID_MON='$monID') AND tien_hoc.money>=$muc_tien AND tien_hoc.ID_LM='$data5[ID_LM]' AND tien_hoc.date_dong='$nam-$thang'";
//                                                $result = mysqli_query($db, $query);
//                                                $data = mysqli_fetch_assoc($result);
//                                                $hs_bt = $data["dem"];
//                                                $tien = $data["tien"];

                                                // Đếm số học sinh được giảm học phí (Bao gồm cả học sinh cũ và học sinh mới)
//                                                $query="SELECT SUM(tien_hoc.money) AS tien,COUNT(tien_hoc.ID_STT) AS dem FROM tien_hoc INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=tien_hoc.ID_HS AND hocsinh_mon.date_in<='$nam-$thang-".get_last_day("$nam-$thang")."' AND hocsinh_mon.ID_LM='$data5[ID_LM]' INNER JOIN giam_gia ON giam_gia.ID_HS=tien_hoc.ID_HS AND giam_gia.ID_MON='$monID' WHERE tien_hoc.ID_LM='$data5[ID_LM]' AND tien_hoc.date_dong='$nam-$thang'";
//                                                $result = mysqli_query($db, $query);
//                                                $data = mysqli_fetch_assoc($result);
//                                                $hs_giam = $data["dem"];
//                                                $tien_giam = $data["tien"]*(2/3);

                                                // Lấy ra và đếm tổng số tiền học sinh mới đi học vào tháng này đã đóng, tức là chưa đủ buổi (cần phải có lịch học)
//                                                $query="SELECT SUM(tien_hoc.money) AS tien,COUNT(tien_hoc.ID_STT) AS dem FROM tien_hoc INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=tien_hoc.ID_HS AND hocsinh_mon.date_in<='$nam-$thang-".get_last_day("$nam-$thang")."' AND hocsinh_mon.ID_LM='$data5[ID_LM]' WHERE tien_hoc.ID_HS NOT IN (SELECT ID_HS FROM giam_gia WHERE giam_gia.ID_MON='$monID') AND tien_hoc.money<$muc_tien AND tien_hoc.ID_LM='$data5[ID_LM]' AND tien_hoc.date_dong='$nam-$thang'";
//                                                $result = mysqli_query($db, $query);
//                                                $data = mysqli_fetch_assoc($result);
//                                                $hs_new = $data["dem"];
//                                                $tien_new = $data["tien"]*(2/3);

                                                // Đếm số học sinh chưa đóng học phí
                                                /*$query1="SELECT h.ID_HS FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND (m.date_in<'$nam-$thang-01' OR m.date_in LIKE '$nam-$thang-%') AND m.ID_MON='$monID' WHERE h.ID_HS NOT IN (SELECT ID_HS FROM tien_hoc WHERE ID_MON='$monID' AND date_dong='$nam-$thang') AND h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_MON='$monID' AND (date<'$nam-$thang-01' OR date LIKE '$nam-$thang-%')) AND h.lop='$lopID'";
                                                $result1 = mysqli_query($db, $query1);
                                                $hs_no = mysqli_num_rows($result1);*/

                                                $query="SELECT COUNT(m.ID_HS) AS dem FROM hocsinh_mon AS m 
                                                WHERE m.date_in<='$nam-$thang-$last_day' 
                                                AND m.ID_LM='$data5[ID_LM]' 
                                                AND m.ID_HS NOT IN ($hs_str) 
                                                AND m.ID_HS NOT IN (SELECT ID_HS FROM nghi_temp WHERE start<='$nam-$thang-01' AND end>='$nam-$thang-$last_day' AND ID_LM='$data5[ID_LM]') 
                                                AND m.ID_HS NOT IN (SELECT ID_HS FROM giam_gia WHERE discount='100' AND ID_MON='$monID') 
                                                AND m.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$data5[ID_LM]')
                                                AND m.ID_HS NOT IN (SELECT note2 FROM options WHERE (content='0' OR content='0k') AND type='edit-tien-hoc-$data5[ID_LM]' AND note='$nam-$thang')";
                                                $result = mysqli_query($db, $query);
                                                $data = mysqli_fetch_assoc($result);
                                                $hs_no = $data["dem"];

                                                $query="SELECT COUNT(m.ID_HS) AS dem FROM hocsinh_mon AS m INNER JOIN options AS o ON o.content!='0' AND o.content!='0k' AND o.type='edit-tien-hoc-$data5[ID_LM]' AND o.note='$nam-$thang' AND o.note2=m.ID_HS WHERE m.date_in>='$nam-$thang-$last_day' AND m.ID_LM='$data5[ID_LM]' AND m.ID_HS NOT IN ($hs_str)";
                                                $result = mysqli_query($db, $query);
                                                $data = mysqli_fetch_assoc($result);
                                                $hs_no += $data["dem"];

                                                // Đếm số học sinh nghỉ trong tháng 8
//                                                $query="SELECT COUNT(hocsinh_nghi.ID_HS) AS dem FROM hocsinh_nghi INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh_nghi.ID_HS AND hocsinh_mon.ID_LM='$data5[ID_LM]' WHERE hocsinh_nghi.ID_LM='$data5[ID_LM]' AND hocsinh_nghi.date LIKE '$nam-$thang-%'";
//                                                $result = mysqli_query($db, $query);
//                                                $data = mysqli_fetch_assoc($result);
//                                                $hs_nghi = $data["dem"];

                                                // Lấy tiền thưởng kiểm tra
//                                                $query="SELECT SUM(tien_ra.price) AS thuong_price FROM tien_ra INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=tien_ra.ID_HS AND hocsinh_mon.ID_LM='$data5[ID_LM]' INNER JOIN buoikt ON buoikt.ngay LIKE '$nam-$thang-%' AND buoikt.ID_MON='$monID' WHERE tien_ra.string='kiemtra_$data5[ID_LM]' AND tien_ra.object=buoikt.ID_BUOI ORDER BY buoikt.ID_BUOI ASC";
//                                                $result = mysqli_query($db, $query);
//                                                $data = mysqli_fetch_assoc($result);
//                                                $tien_thuong = $data["thuong_price"];
                                                $tien_thuong = 0;
                                            }

                                            echo "<tr>
                                                <td class='td-boi' style='border-bottom:1px solid #FFF'><span>Tháng $thang</span></td>";
                                            if ($hs_bt != 0) {
                                                if($nam >= 2017 && $thang >= 6) {
                                                    echo "<td class='need-ex'>
                                                        <a href='http://localhost/www/TDUONG/thaygiao/bang-luong-chi-tiet/$data5[ID_LM]/$nam-$thang/1/' target='_blank'>$hs_bt em = " . format_price($tien*(2/3)) . "<br /> = " . format_price($hs_bt * $tien * (2/3)) . "</a>
                                                    </td>";
                                                    $tien_tra = $tien * (2/3);
                                                } else {
                                                    if ($hs_bt * $muc_tien > $tien) {
                                                        echo "<td class='need-ex' style='background:yellow'>
                                                            <a href='http://localhost/www/TDUONG/thaygiao/bang-luong-chi-tiet/$data5[ID_LM]/$nam-$thang/1/' target='_blank'>$hs_bt em x " . format_price($tien_tra) . "<br /> = " . format_price($hs_bt * $tien_tra) . "</a>
                                                        </td>";
                                                    } else {
                                                        echo "<td class='need-ex'>
                                                            <a href='http://localhost/www/TDUONG/thaygiao/bang-luong-chi-tiet/$data5[ID_LM]/$nam-$thang/1/' target='_blank'>$hs_bt em x " . format_price($tien_tra) . "<br /> = " . format_price($hs_bt * $tien_tra) . "</a>
                                                        </td>";
                                                    }
                                                    $tien_tra = $hs_bt * $tien_tra;
                                                }
                                            } else {
                                                echo "<td><span></span></td>";
                                            }
                                            if ($hs_giam != 0) {
                                                echo "<td>
                                                        <a href='http://localhost/www/TDUONG/thaygiao/bang-luong-chi-tiet/$data5[ID_LM]/$nam-$thang/2/' target='_blank'>$hs_giam em = " . format_price($tien_giam) . "</a>
                                                    </td>";
                                            } else {
                                                echo "<td><span></spam></td>";
                                            }
                                            if ($hs_new != 0) {
                                                echo "<td>
                                                        <a href='http://localhost/www/TDUONG/thaygiao/bang-luong-chi-tiet/$data5[ID_LM]/$nam-$thang/3/' target='_blank'>$hs_new em = " . format_price($tien_new) . "</a>
                                                    </td>";
                                            } else {
                                                echo "<td><span></span></td>";
                                            }
                                            if ($hs_no != 0) {
                                                echo "<td>
                                                        <a href='http://localhost/www/TDUONG/thaygiao/bang-luong-chi-tiet/$data5[ID_LM]/$nam-$thang/4/' target='_blank'>$hs_no em</a>
                                                    </td>";
                                            } else {
                                                echo "<td><span></span></td>";
                                            }
//                                            if ($hs_nghi != 0) {
//                                                echo "<td>
//                                                        <a href='http://localhost/www/TDUONG/thaygiao/bang-luong-chi-tiet/$data5[ID_LM]/$nam-$thang/5/' target='_blank'>$hs_nghi em</a>
//                                                    </td>";
//                                            } else {
//                                                echo "<td><span></spam></td>";
//                                            }
//                                            echo "<td>
//                                                    <a href='http://localhost/www/TDUONG/thaygiao/bang-luong-chi-tiet/$data5[ID_LM]/$nam-$thang/6/' target='_blank'>-" . format_price($tien_thuong) . "</a>
//                                                </td>";
                                            $total = $tien_tra + $tien_giam + $tien_new - $tien_thuong;
                                            if(date("Y-m") != "$nam-$thang") {
                                                $total_all += $total;
                                                $total_con += $total;
                                            }
                                            if ($total != 0) {
                                                echo "<td><span>" . format_price($total) . "</span></td>";
                                            } else {
                                                echo "<td><span></span></td>";
                                            }
                                            echo "</tr>";
                                        }
                                        $thang++;
                                        $count++;
                                        if ($count == 25) {
                                            break;
                                        }
                                    }
                                    $thang = 1;
                                    $nam++;
                                    ?>
                                </table>
                                <?php
                            }
                            echo"<input type='hidden' value='".format_price($total_con)."' class='tong-luong' data-lmID='$data5[ID_LM]' />";
						}
						$result5=get_lop_mon_old($monID);
                        while($data5=mysqli_fetch_assoc($result5)) {
                            echo"<div style='padding: 20px 0 20px 0;margin-top: 25px;text-align: center;background: #3E606F;'><h2 style='color:#FFF;text-transform: uppercase;font-size:22px;'>Lớp $data5[content]</h2></div>";
                            ?>
                            <table class="table main-table" style="margin-top:25px;">
                                <tr>
                                    <td class="td-boi" style="border-bottom:1px solid #FFF;width:16%;"><span class='span-boi2'></span></td>
                                    <td style="width:17%;"><span style="text-transform:uppercase">Học sinh đóng học bình thường</span>
                                    </td>
                                    <td style="width:16%;"><span style="text-transform:uppercase">Học sinh được giảm học phí</span>
                                    </td>
                                    <td style="width:16%;"><span style="text-transform:uppercase">Học sinh học không đóng đủ</span>
                                    </td>
                                    <td style="width:16%;"><span style="text-transform:uppercase">Học sinh chưa đóng học</span>
                                    </td>
                                    <!--                                        <td style="width:14%;"><span style="text-transform:uppercase">Số học sinh nghỉ hẳn</span></td>-->
                                    <!--                                        <td style="width:12%;"><span style="text-transform:uppercase">Tiền thưởng</span>-->
                                    </td>
                                    <td><span class="span-boi">Tổng</span></td>
                                </tr>
                                <?php
                                $total = 0;
                                $query="SELECT SUM(money) AS price,date_dong FROM tien_hoc2 WHERE ID_LM='$data5[note]' GROUP BY date_dong ORDER BY date_dong ASC";
                                $result=mysqli_query($db, $query);
                                while($data=mysqli_fetch_assoc($result)) {
                                    echo"<tr>
                                        <td class='td-boi' style='border-bottom:1px solid #FFF'><span>".format_month($data["date_dong"])."</span></td>
                                        <td colspan='4'><span></span></td>
                                        <td><span>".format_price($data["price"]*(2/3))."</span></td>
                                    </tr>";
                                    $total+=$data["price"]*(2/3);
                                }
                                $total_all += $total;
                                ?>
                            </table>
                            <?php
                            echo"<input type='hidden' value='".format_price($total)."' class='tong-luong' data-lmID='$data5[note]' />";
                        }
						echo"<input type='hidden' value='".format_price($total_all-$paid-$luong)."' id='tong-pay' />
						<input type='hidden' value='".format_price($paid)."' id='paid' />
						<input type='hidden' value='".format_price($luong)."' id='luong' />";
					?>
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