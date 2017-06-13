<?php
	ob_start();
	//session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	session_start();
    if(!$_SESSION['id']) {
        header('location: http://localhost/www/TDUONG/trogiang/dang-nhap/');
        exit();
    }
    $id=$_SESSION['id'];
	
	$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
	
	$mau="#FFF";

    $result0=get_info_tro_giang($id);
    $data0=mysqli_fetch_assoc($result0);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title><?php echo mb_strtoupper($data0["name"]); ?></title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
        
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/trogiang/css/tongquan.css"/>
        
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/trogiang/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/trogiang/css/hover.css" />
        <!--<link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/materialize.min.css" />-->     
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/trogiang/css/font-awesome.min.css">
        
        <style>
			<?php require_once("include/CSS.php"); ?>
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:36%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:30px;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:22px;}table tr:last-child td:first-child,table tr:last-child td:last-child {border-radius:0;}

             #MAIN > #main-mid {width:100%;}
            #main-note {position: fixed;z-index: 99;right: 0;top: 15%;width:40%;}
            .a-explain {position:absolute;z-index: 9;top:10px;left:60%;font-size:11px;padding:5px;border-radius: 6px;display: none;}
            .span-ex:hover a.a-explain {display: block;width:60px;}
            /*table tr td span a, table tr th span a {text-decoration: underline;}*/
            /*#list-diemdanh tr td.check:hover span.tich {display: none;}*/
            /*#list-diemdanh tr td.check:hover span.button {display: block !important;}*/
            #list-lich tr td.day:hover span {display: none;}
            #list-lich tr td.day:hover p {display: block !important;}
			
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
            $(document).ready(function() {
                $("#main-wapper").closest("div").scrollLeft(4000);

                $(".table3 tr td span i.fa-plus").click(function () {
                    var me = $(this).closest("td").find("span.tich");
                    var ngay = $(this).closest("td").attr("data-ngay");
                    var buoi = $(this).closest("td").attr("data-buoi");
                    var check = "<i class='fa fa-check'></i>";
                    if (ngay != "" && buoi != "") {
                        $("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3");
                        $.ajax({
                            async: false,
                            data: "ngay2=" + ngay + "&buoi2=" + buoi,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-trogiang/",
                            success: function (result) {
                                if(result=="okno") {
                                    me.append(check);
                                    count_sum();
                                }
                                $("#BODY").css("opacity", "1");
                                $("#popup-loading").fadeOut("fast");
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });

                $(".table3 tr td span i.fa-minus").click(function () {
                    var ngay = $(this).closest("td").attr("data-ngay");
                    var buoi = $(this).closest("td").attr("data-buoi");
                    if (ngay != "" && buoi != "") {
                        $("div#popup-confirm").fadeIn("fast");
                        $("button#popup-view").attr("data-ngay", ngay);
                    }
                });

                $("i.fa-close").click(function () {
                    $("#popup-confirm").hide();
                });

                $("button#popup-view").click(function () {
                    var ngay = $(this).attr("data-ngay");
                    var date_bu = "";
                    var bu = 0;
                    if ($('.check-k-bu').is(':checked')) {
                        bu = 1;
                    } else {
                        date_bu = $("input.date-bu").val();
                    }
                    if (ngay != "") {
                        $("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3");
                        $.ajax({
                            async: false,
                            data: "ngay=" + ngay + "&ngay_bu=" + date_bu + "&bu=" + bu,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-trogiang/",
                            success: function (result) {
                                $(".popup").fadeOut("fast");
                                location.reload();
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });


                $(".table-tkb tr td i.done").click(function () {
                    var me=$(this).closest("tr");
                    var note = $(this).attr("data-note");
                    var date= $(this).attr("data-ngay");
                    if(note != 0 && $.isNumeric(note) && date != "") {
                        $.ajax({
                            async: false,
                            data: "note=" + note + "&date=" + date,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-trogiang/",
                            success: function (result) {
                                me.fadeOut("fast").removeClass("stt");
                                stt();
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });

                count_sum();
                function count_sum() {
                    $(".table3 tr td#sum").each(function (index, element) {
                        var sum = $(".table3").find("tr td.check i.fa-check").length;
                        $(element).find("span").html(sum);
                    });
                }

                function stt() {
                    $("tr.stt").each(function(index,element) {
                        $(element).find("td.stt span").html(index+1);
                    });
                }


            });

		</script>

	</head>

    <body>
    <div class="popup" id="popup-loading">
        <p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
    </div>
    <div class="popup animated bounceIn" id="popup-loading">
        <p><img src="https://localhost/www/TDUONG/images/ajax-loader.gif" /></p>
    </div>

    <div id="BODY">

        <div id="MAIN">

            <div class="main-div back animated bounceInUp" id="main-top">
                <div id="main-person" style="height: 98px;">
                    <h1>Trợ giảng <?php echo $data0["name"];?></h1>
                    <?php $lich_lam=get_tro_giang_lich($id);
                        echo"<p style='margin-top:15px;'>$lich_lam</p>";
                    ?>
                </div>
                <div id="main-avata">
                    <img src="http://localhost/www/TDUONG/trogiang/avata/placeholder.jpg"/>
                    <a href="http://localhost/www/TDUONG/trogiang/ho-so/" title="Hồ sơ cá nhân">
                        <p><?php echo $data0["name"];?></p>
                        <i class="fa fa-pencil"></i>
                    </a>
                </div>
            </div>

            <div class="main-div animated bounceInUp">
                <div id="main-info">
                    <div class="main-1-left back" style="margin-right:0;max-height:none;width: 100%;float: none;">
                        <div>
                            <p class="main-title">Điểm danh trợ giảng</p>
                        </div>
                        <div class="status" style="position: relative;">
                            <table class="table table3 table-tkb" id="list-name" style="position: absolute;z-index:9;width:20%;min-width:0;margin-left:45px;">
                                <tr style="height:50px;">
                                    <td style="width:29%;"><span>Tổng kết</span></td>
                                    <td style="width:29%;"><span>Buổi</span></td>
                                </tr>

                                <?php
                                $buoi_arr=array("S","C","T");
                                $count=count($buoi_arr);
                                for($j=0;$j<$count;$j++) {
                                echo "<tr class='$buoi_arr[$j]'>";
                                    if($j==0) {
                                    echo "
                                          <td rowspan='3' id='sum'><span></span></td>";
                                    }
                                    echo "<td style='height:20px;'><span>$buoi_arr[$j]</span></td>";
                                }


                                ?>
                            </table>
                            <div id="main-wapper" style="margin-left: 24%;overflow-x: scroll;width: 74%;display: block;">
                                <table class="table table3 table-tkb" id="list-diemdanh">
                                    <tr style="height:50px;">
                                        <?php
                                        $ngay= date("d");
                                        $thang= date("m");
                                        $nam= date("Y");
                                        for($i=1;$i<=$ngay;$i++) {
                                            echo "<th style='min-width:70px;'><span>".format_month_db($i)."/$thang</span></th>";
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                    for($i=0;$i<$count;$i++) {
                                        echo "<tr>";
                                        for ($j = 1; $j <= $ngay; $j++) {
                                            $j = format_month_db($j);
                                                echo "<td class='check' data-ngay='$nam-$thang-$j' data-buoi='$buoi_arr[$i]'><span class='tich'>";
                                                $query2 = "SELECT COUNT(ID_STT) AS dem FROM trogiang_diemdanh WHERE ngay='$nam-".format_month_db($thang)."-$j' AND buoi='$buoi_arr[$i]' AND trang_thai='-1' AND ID='$id'";
                                                $result2 = mysqli_query($db, $query2);
                                                while ($data2 = mysqli_fetch_assoc($result2)) {
                                                    if ($data2["dem"] != 0) {
                                                        echo "<i class='fa fa-check'></i>";
                                                    } else {
                                                        echo "";
                                                    }
                                                }
                                                echo "</span><span class='button' style='display: none;cursor:pointer;'><i class='fa fa-minus'></i> | <i class='fa fa-plus'></i></span></td>";
                                        }
                                        echo "</tr>";
                                    }
                                    ?>

                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="main-div animated bounceInUp">
                <div id="main-info">
                    <div class="main-1-left back" style="margin-right:0;max-height:none;width: 100%;float: none;">
                        <div>
                            <p class="main-title">Ghi chú trợ giảng</p>
                        </div>
                        <table class="table table-tkb" id="list-lich" style="color:white;">
                            <tr style="height:50px;">
                                <td style='width:6%;'><span style="color:#FFF;">STT</span></td>
                                <td style='width:35%;'><span style="color:#FFF;">Nội dung</span></td>
                                <td style='width:10%;'><span style="color:#FFF;">Thời gian</span></td>
                                <td style='width:5%;background:none;'><span style="color:#FFF;"></span></td>
                            </tr>
                            <?php
                            $d=date("d");
                            $m=date("m");
                            $y=date("Y");
                            $mm=format_month_db($m);
                            $i=1;
                            $query1 = "SELECT content,datetime,ID_STT FROM trogiang_note WHERE ID_TG='$id' AND status='1' ORDER BY datetime DESC";
                            $result1 = mysqli_query($db, $query1);
                            while ($data1 = mysqli_fetch_assoc($result1)) {
                                echo "<tr class='stt'>
                                        <td class='stt'><span>$i</span></td>
                                        <td style='text-align:left;padding-left:15px;'>$data1[content]</td>
                                        <td>".format_datetime($data1["datetime"])."</td>
                                        <td><i class='fa fa-check done' style='cursor:pointer;' data-note='$data1[ID_STT]' data-ngay='$y-$mm-$d'></i></td>
                                    </tr>";
                                $i++;
                            }
                            ?>

                        </table>
                    </div>
                </div>
            </div>

            </div>

    </div>
    <?php require_once("include/MENU.php"); ?>

    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>