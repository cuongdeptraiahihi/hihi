    <?php
    ob_start();
    session_start();
    ini_set('max_execution_time', 900);
    require_once("../model/open_db.php");
    require_once("../model/model.php");
    require_once("access_admin.php");
    $monID=$_SESSION["mon"];
    $mon_name=get_mon_name($monID);

    $start_month =7;
    $year=0;
    $i=0;
    $a=$b=$mon_arr=$mon_old_arr=$ten_old_arr=$mau_arr=$ten_arr=$mau_old_arr=array();
    $mau_arr=array("#58FAF4","#424242");
    $mau_old_arr=array("#FF0000","#0404B4","#FF8000","#FF0000");
    $result=get_all_lop_mon2($monID);
    while($data=mysqli_fetch_assoc($result)){
        $mon_arr[] = $data["ID_LM"];
        $ten_arr[] = $data["name"];
    }

    $result=get_all_lop_mon_cu($monID);
    while($data=mysqli_fetch_assoc($result)) {
        $mon_old_arr[] = $data["lop"];
        $ten_old_arr[] = $data["note"];
    }

    $count_arr = $count_arr_old = array();
    while($i<=23){
        for($j=0;$j<count($mon_arr);$j++) {
            if(!isset($count_arr[$mon_arr[$j]][$start_month])) {
                $count_arr[$mon_arr[$j]][$start_month] = 0;
            } else {
                $count_arr[$mon_arr[$j]][$start_month]++;
            }
            $query = "SELECT COUNT(ID_STT) AS dem,date_dong,ID_LM FROM tien_hoc WHERE date_dong LIKE '%-" . format_month_db($start_month) . "' AND ID_LM ='$mon_arr[$j]'  GROUP BY date_dong,ID_LM ORDER BY date_dong ASC LIMIT ".$count_arr[$mon_arr[$j]][$start_month].",1 ";
            $result = mysqli_query($db, $query);
            $data = mysqli_fetch_assoc($result);
            if(date_create($data["date_dong"]."-01") <= date_create(date("Y-m")."-01")) {
                $a["lop-$mon_arr[$j]"][] = array(
                    "thang" => format_month_db($start_month),
                    "soluong" => $data["dem"]
                );
            }
        }

        for($k=0;$k<count($mon_old_arr);$k++){
            if(!isset($count_arr_old[$mon_old_arr[$k]][$start_month])) {
                $count_arr_old[$mon_old_arr[$k]][$start_month] = 0;
            } else {
                $count_arr_old[$mon_old_arr[$k]][$start_month]++;
            }
            $query = "SELECT sl,note FROM tien_hoc_cu WHERE date_dong LIKE '%-" . format_month_db($start_month) . "' AND lop ='$mon_old_arr[$k]' ORDER BY date_dong ASC LIMIT ".$count_arr_old[$mon_old_arr[$k]][$start_month].",1 ";
            $result = mysqli_query($db, $query);
            $data = mysqli_fetch_assoc($result);
            $b["lop-$mon_old_arr[$k]"][] = array(
                "thang" => format_month_db($start_month),
                "soluong" => $data["sl"]
            );
        }
        $start_month++;
        if ($start_month == 13) {
            $year++;
            if($year>1) {
                $year = 1;
            }
            $start_month = 1;
        }
        $i++;
    }

    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <title>THỐNG KÊ TĂNG TRƯỞNG</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->

        <?php
        if($_SESSION["mobile"]==1) {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/thaygiao/css/mbocuc.css'>";
        } else {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/thaygiao/css/bocuc.css'>";
        }
        ?>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/jquery-ui.css">

        <style>
            td.active {background: yellow;}
            #MAIN > #main-mid {width:100%;}#MAIN > #main-mid .status {position:relative;}.main-bot {width:100%;}.main-top {width:100%;position:relative;height:450px;}#chartContainer {width:100%;height:100%;}#chartPhu {position:absolute;z-index:9;right:0;width:400px;top:0;height:200px;overflow:hidden;border-radius:200px;}#chartContainer2 {width:100%;height:100%;}.khoang-ma,.only-ma {display:none;}#list-danhsach {background:#FFF;}
        </style>

        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.js"></script>
        <script src="http://localhost/www/TDUONG/thaygiao/js/iscroll.js"></script>
        <script type="text/javascript">
            document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
            <?php if(isset($_SESSION["bieudo"]) && $_SESSION["bieudo"]==0) { ?>
            window.onload = function () {
                var myScroll = new IScroll('#main-wapper', { scrollX: true, scrollY: false, mouseWheel: false});
                var myScroll2 = new IScroll('#main-wapper2', { scrollX: true, scrollY: false, mouseWheel: false});
                var chart = new CanvasJS.Chart("chartContainer",
                    {
                        animationEnabled: true,
                        axisX:{
                            indexLabelFontFamily:"Arial" ,
                            labelFontFamily:"Arial" ,
                            gridColor: "Silver",
                            tickColor: "silver",
                            gridThickness: 0,
                            labelFontColor: "#3E606F",
                            labelFontSize: 16,
                            labelFontWeight: "normal",
                            interval: 1
                        },
                        axisY: {
                            title: "SỐ LƯỢNG HỌC SINH",
                            titleFontFamily: "Arial",
                            titleFontWeight: "normal",
                            titleFontSize: 16,
                            gridThickness: 1,
                            indexLabelFontFamily:"Arial" ,
                            gridColor: "Silver",
                            tickColor: "silver",
                            labelFontColor: "#3E606F",
                            labelFontSize: 16,
                            labelFontWeight: "normal",
                            minimum: 0
                        },
                        toolTip:{
                            shared: false
                        },
                        legend: {
                            fontSize: 14,
                            fontFamily: "Arial",
                            fontColor: "#3E606F",
                            horizontalAlign: "right",
                            verticalAlign: "top"
                        },
                        theme: "theme2",
                        dataPointMaxWidth: 20,
                        data: [
                        <?php

                            for($j=0;$j<count($mon_arr);$j++){
                            ?>
                            {
                                type: "line",
                                showInLegend: true,
                                gridThickness: 1,
                                lineThickness: 1,
                                name: "<?php echo $ten_arr[$j]; ?>",
                                markerType: "none",
                                color: "<?php echo $mau_arr[$j]; ?>",
                                toolTipContent: "<?php echo $ten_arr[$j]; ?> ({label}): {y}",
                                dataPoints: [
                                    <?php
                                    $i = 0;
                                    while ($i < count($a["lop-$mon_arr[$j]"])) {
                                        if (is_numeric($a["lop-$mon_arr[$j]"][$i]["soluong"])) {
                                            echo "{ label: '" . $a["lop-$mon_arr[$j]"][$i]["thang"] . "', y: " . $a["lop-$mon_arr[$j]"][$i]["soluong"] . "},";
                                        } else {
                                            echo "{ label: '" . $a["lop-$mon_arr[$j]"][$i]["thang"] . "', y: '" . $a["lop-$mon_arr[$j]"][$i]["soluong"] . "'},";
                                        }
                                        $i++;
                                    }
                                    ?>
                                ]
                            },
                            <?php } ?>

                            <?php

                            for($k=0;$k<count($mon_old_arr);$k++){
                            ?>
                            {
                                type: "line",
                                showInLegend: true,
                                gridThickness: 1,
                                lineThickness: 1,
                                name: "<?php echo $ten_old_arr[$k]; ?>",
                                markerType: "none",
                                color: "<?php echo $mau_old_arr[$k]; ?>",
                                toolTipContent: "<?php echo $ten_old_arr[$k]; ?> ({label}): {y}",
                                dataPoints: [
                                    <?php
                                    $i = 0;
                                    while ($i < count($b["lop-$mon_old_arr[$k]"])) {
                                        if (is_numeric($b["lop-$mon_old_arr[$k]"][$i]["soluong"])) {
                                            echo "{ label: '" . $b["lop-$mon_old_arr[$k]"][$i]["thang"] . "', y: " . $b["lop-$mon_old_arr[$k]"][$i]["soluong"] . "},";
                                        } else {
                                            echo "{ label: '" . $b["lop-$mon_old_arr[$k]"][$i]["thang"] . "', y: '" . $b["lop-$mon_old_arr[$k]"][$i]["soluong"] . "'},";
                                        }
                                        $i++;
                                    }
                                    ?>
                                ]

                            },
                            <?php
                            }
                        ?>
                        ]
                    });

                chart.render();
            }
            <?php } else { ?>
            window.onload = function() {
                var myScroll2 = new IScroll('#main-wapper2', { scrollX: true, scrollY: false, mouseWheel: false});
            }
            <?php } ?>
        </script>
        <script>
            $(document).ready(function() {
            });
        </script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/thaygiao/js/canvasjs.min.js"></script>

    </head>

    <body>

    <div class="popup" id="popup-loading">
        <p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
    </div>

    <div id="BODY">

        <?php require_once("include/TOP.php"); ?>

        <div id="MAIN">

            <div id="main-mid">
                <h2>Thống kê tăng trưởng <span style="font-weight:600;">môn <?php echo $mon_name; ?></span></h2>
                <div>
                    <div class="status">
                        <div class="main-top" style="display:block;overflow:auto;" id="main-wapper">
                            <div id="chartContainer" style="width:100%;">
                            </div>
                            <div id="chartPhu">
                                <div id="chartContainer2">
                                </div>
                            </div>
                        </div>
                        <div class="main-bot" style="display:block;">
                            <div class="clear" id="main-wapper2" style="width:100%;overflow:auto;">
                                <div></div>
                                <table class="table" id="list-danhsach" style="margin-top:30px;">

                                    <tr style="background:#3E606F;">
                                        <th style="min-width:60px;"><span>Tháng</span></th>
                                        <?php
                                         for($n=0;$n<count($ten_old_arr);$n++) {
                                             echo "<th style = 'max-width:100px;'><span >$ten_old_arr[$n]</span ></th>";
                                         }
                                        for($n=0;$n<count($ten_arr);$n++) {
                                            echo "<th style = 'max-width:100px;'><span >$ten_arr[$n]</span ></th>";
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                    $start_month=7;
                                    $i=0;
                                    while($i<=23) {
                                        echo "<tr>";
                                        echo "<td style='min-width:60px;'><span>" . format_month_db($start_month) . "</span></td>";
                                        for ($j = 0; $j < count($mon_old_arr); $j++) {
                                            echo "<td style = 'max-width:100px;'><span>" . $b["lop-$mon_old_arr[$j]"][$i]["soluong"] . "</span ></td>";
                                        }

                                        for ($k = 0; $k < count($mon_arr); $k++) {
                                            if(isset($a["lop-$mon_arr[$k]"][$i])) {
                                                echo "<td style = 'max-width:100px;'><span>" . $a["lop-$mon_arr[$k]"][$i]["soluong"] . "</span ></td>";
                                            }
                                        }


                                        $start_month++;
                                        if ($start_month == 13) {
                                            $start_month = 1;
                                        }

                                        $i++;
                                        echo "</tr>";
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
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