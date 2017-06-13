<?php
	ob_start();
	session_start();
    ini_set('max_execution_time', 300);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    if(isset($_GET["lm"]) && is_numeric($_GET["lm"]) && isset($_GET["mon"]) && is_numeric($_GET["mon"])) {
        $lmID=$_GET["lm"];
        $monID=$_GET["mon"];
    } else {
        $lmID=$_SESSION["lmID"];
        $monID=$_SESSION["mon"];
    }
	if($lmID != 0) {
        $lmID = $_SESSION["lmID"];
    }
    $monID=$_SESSION["mon"];
    $mon_lop_name=get_lop_mon_name($lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>HỌC SINH NGHỈ NHIỀU</title>
        
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
        
        <style>
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid .status {position:relative;}.main-bot {width:100%;}.main-top {width:100%;position:relative;height:600px;}#chartContainer {width:100%;height:100%;}#chartPhu {position:absolute;z-index:9;right:0;width:400px;top:0;height:200px;overflow:hidden;border-radius:200px;}#chartContainer2 {width:100%;height:100%;}.khoang-ma,.only-ma {display:none;}#list-danhsach {background:#FFF;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/iscroll.js"></script>
        <script>
			<?php if($_SESSION["mobile"]==0) { ?>
			//document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
			window.onload = function() {
				var myScroll2 = new IScroll('#main-wapper2', { scrollX: true, scrollY: false, mouseWheel: false});
			}
			<?php } ?>
			$(document).ready(function() {
				
				var count_me = 0;
				$("table#list-danhsach tr").each(function(index, element) {
					dem = 0;
					$(element).find("td").each(function(index2, element2) {
						if($(element2).hasClass("hs-ko")) {
							dem++;
						} else {
							if(dem<2) {
								dem = 0;
							}
						}
					});
					if(dem>=2 && index>0) {
						count_me++;
					}
				});
				$("#status").html("Số học sinh nghỉ 2 buôi liên tiếp: " + count_me)

                $("table#list-danhsach").delegate("tr td:first-child > span.check-nghi > i.fa-square-o","click", function() {
                    //$("#list-danhsach tr td > span.check-nghi > i.fa-square-o").click(function() {
                    del_tr = $(this).closest("tr");
                    hsID = $(this).attr("data-hsID");
                    me_i = $(this);
                    if(confirm("Bạn có chắc chắn cho học sinh này nghỉ học?") && $.isNumeric(hsID) && hsID!=0) {
                        del_tr.css("opacity","0.3");
                        $.ajax({
                            async: true,
                            data: "hsID2=" + hsID + "&lmID2=" + <?php echo $lmID; ?>,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-thongtin/",
                            success: function(result) {
                                del_tr.fadeOut("fast");
                            }
                        });
                    }
                });

                $("table#list-danhsach tr td.hs-ko").click(function() {
                    me = $(this);
                    hsID = $(this).attr("data-hsID");
                    index = $(this).index();
                    cumID = $("table#list-danhsach tr:first-child th:eq("+index+")").attr("data-cum");
                    if($(this).hasClass("active")) {
                        is_phep = 0;
                    } else {
                        is_phep = 1;
                    }
                    if($.isNumeric(hsID) && $.isNumeric(cumID) && hsID!=0 && cumID!=0 && (is_phep==0 || is_phep==1)) {
                        me.css("opacity","0.3");
                        $.ajax({
                            async: true,
                            data: "cumID=" + cumID + "&hsID=" + hsID + "&is_phep=" + is_phep + "&lmID=" + <?php echo $lmID; ?> + "&monID=" + <?php echo $monID; ?> + "&is_bao=0",
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
                            success: function(result) {
                                if(result=="ok") {
                                    if(is_phep==0) {
                                        me.css("background","green");
                                        me.html("<span style='color:#FFF'>Ko phép</span>");
                                        me.removeClass("active");
                                    }
                                    if(is_phep==1) {
                                        me.css("background","orange");
                                        me.html("<span style='color:#FFF'>Phép</span><input type='checkbox' style='display: none;' class='check' checked='checked' />");
                                        me.addClass("active");
                                    }
                                } else {
                                    alert("Lỗi dữ liệu!");
                                }
                                me.css("opacity","1");
                            }
                        });
                    }
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
                
                <div id="main-mid">
                	<h2>Học sinh nghỉ nhiều <span style="font-weight:600;">môn <?php echo $mon_lop_name; ?></span></h2>
                	<div>
                    	<div class="status">
                            <div class="main-bot" style="display:block;">
                                <div class="clear" id="main-wapper2" style="width:100%;overflow:auto;">
                                    <div></div>
                                    <table class="table" id="list-danhsach">
                                        <tr style="background:#3E606F;">
                                            <th style="min-width:60px;"><span>Nghỉ hẳn</span></th>
                                            <th style="min-width:60px;"><span>STT</span></th>
                                            <th style="min-width:60px;"><span>Mã số</span></th>
                                            <th style="min-width:120px;"><span>Tên học sinh</span></th>
                                        <?php
                                            $cumID=$dem=0;
                                            $string=$con="";
                                            $query0="SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE ID_LM='$lmID' AND ID_MON='$monID' ORDER BY ID_CUM DESC LIMIT 11";
                                            $result0=mysqli_query($db,$query0);
                                            while($data0=mysqli_fetch_assoc($result0)) {
                                                $string.=",'$data0[ID_CUM]'";
                                            }
                                            $query1="SELECT ID_CUM,date FROM diemdanh_buoi WHERE ID_CUM IN (".substr($string,1).") AND ID_LM='$lmID' AND ID_MON='$monID' ORDER BY ID_CUM DESC,date DESC";
                                            $result1=mysqli_query($db,$query1);
                                            $all=mysqli_num_rows($result1);
                                            while($data1=mysqli_fetch_assoc($result1)) {
                                                $thu = date("w",strtotime($data1["date"])) + 1;
                                                if(($cumID!=$data1["ID_CUM"] && $dem!=0) || ($dem==$all-1)) {
                                                    echo"<th style='min-width:60px;cursor:pointer' data-cum='$cumID'><span>".substr($con,6)."</span></th>";
                                                    $con="";
                                                    if($dem==$all-1 && $cumID!=$data1["ID_CUM"]) {
                                                        if(stripos($con,format_date($data1["date"]))===false) {
                                                            $con.="<br />".format_date($data1["date"])." (T$thu)";
                                                        }
                                                        echo"<th style='min-width:60px;cursor:pointer' data-cum='$cumID'><span>".substr($con,6)."</span></th>";
                                                    }
                                                }
                                                $dem++;
                                                if(stripos($con,format_date($data1["date"]))===false) {
                                                    $con.="<br />".format_date($data1["date"])." (T$thu)";
                                                }
                                                if($cumID!=$data1["ID_CUM"]) {
                                                    $cum_arr[]=$data1["ID_CUM"];
                                                    $dem_arr[]=0;
                                                }
                                                $cumID=$data1["ID_CUM"];
                                            }
                                        ?>
                                        </tr>
                                        <?php
                                            if (isset($_GET["begin"]) && is_numeric($_GET["begin"])) {
                                                $position = $_GET["begin"];
                                            } else {
                                                $position = 0;
                                            }
                                            $stt=$position;
                                            $display = 20;
                                            $query2="SELECT h.ID_HS,h.cmt,h.fullname,m.date_in FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID') ORDER BY (SELECT COUNT(n.ID_STT) AS dem FROM diemdanh_nghi AS n WHERE n.ID_CUM IN (".substr($string,1).") AND n.ID_HS=h.ID_HS AND n.ID_LM='$lmID' AND n.ID_MON='$monID') DESC,h.ID_HS ASC LIMIT $position,$display";
                                            $result2=mysqli_query($db,$query2);
                                            while($data2=mysqli_fetch_assoc($result2)) {
                                                $date_in=date_create($data2["date_in"]);
                                                if($stt%2!=0) {
                                                    echo"<tr style='background:#D1DBBD'>";
                                                } else {
                                                    echo"<tr>";
                                                }
                                                echo"<td><span class='check-nghi'><i class='fa fa-square-o' data-hsID='$data2[ID_HS]' style='font-size:1.5em !important;'></i></span></td>
												<td><span>".($stt+1)."</span></td>
												<td><span>$data2[cmt]<span></td>
												<td><span>$data2[fullname]</span></td>";
                                                $query3="SELECT ID_CUM,date FROM diemdanh_buoi WHERE ID_CUM IN (".substr($string,1).") AND ID_LM='$lmID' AND ID_MON='$monID' GROUP BY ID_CUM ORDER BY ID_CUM DESC,date DESC";
                                                $result3=mysqli_query($db,$query3);
                                                $me=0;
                                                while($data3=mysqli_fetch_assoc($result3)) {
                                                    $date=date_create($data3["date"]);
                                                    if($date<$date_in) {
                                                        echo"<td><span>C</span></td>";
                                                    } else {
                                                        $result4=check_di_hoc($data2["ID_HS"],$data3["ID_CUM"],$lmID,$monID);
                                                        if($result4!=false) {
                                                            echo "<td><span class='fa fa-check'></span></td>";
                                                        } else {
                                                            if(get_lydo_nghi($data3["ID_CUM"],$data2["ID_HS"],$lmID,$monID)) {
                                                                echo"<td class='hs-ko cum-$data3[ID_CUM] active' data-hsID='$data2[ID_HS]' style='background:orange;'><span style='color:#FFF'>Phép</span></td>";
                                                            } else {
                                                                echo"<td class='hs-ko cum-$data3[ID_CUM]' data-hsID='$data2[ID_HS]' style='background:green;'><span style='color:#FFF'>Ko phép</span></td>";
                                                            }
                                                        }
                                                    }
                                                    $me++;
                                                }
                                                echo"</tr>";
                                                $stt++;
                                            }
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="page-number">
                            <ul>
                                <li><a href='http://localhost/www/TDUONG/admin/hoc-sinh-nghi-nhieu/<?php echo $lmID; ?>/<?php echo $monID; ?>/page/<?php if($position>0){echo ($position-20);}else{echo 0;} ?>/'><</a></li>
                                <li><a href='http://localhost/www/TDUONG/admin/hoc-sinh-nghi-nhieu/<?php echo $lmID; ?>/<?php echo $monID; ?>/page/<?php echo ($position+20); ?>/'>></a></li>
                            </ul>
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