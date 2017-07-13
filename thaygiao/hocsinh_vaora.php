<?php
ob_start();
session_start();
require_once("../model/open_db.php");
require_once("../model/model.php");
require_once("access_admin.php");
$lmID=$_SESSION["lmID"];
$songay_arr= ["","31","28","31","30","31","30","31","31","30","31","30","31"];
if(isset($_GET["type"])) {
    $type=$_GET["type"];
} else {
    $type=NULL;
}
if(isset($_GET["date"])) {
    $date=$_GET["date"];
} else {
    $date=NULL;
}
?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <title>TRANG CHỦ</title>

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

        <style>
            #MAIN > #main-mid {width:100%;}
            #main-note {position: fixed;z-index: 99;right: 0;top: 15%;width:40%;}
            .a-explain {position:absolute;z-index: 9;top:10px;left:60%;font-size:11px;padding:5px;border-radius: 6px;display: none;}
            .span-ex:hover a.a-explain {display: block;width:60px;}
            /*table tr td span a, table tr th span a {text-decoration: underline;}*/
        </style>

        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/thaygiao/js/iscroll.js"></script>
        <script type="text/javascript">
            window.onload = function () {
                var chartGame = new CanvasJS.Chart("chartGame",
                    {
                        animationEnabled: true,
                        interactivityEnabled: true,
                        theme: "theme2",
                        toolTip: {
                            shared: false
                        },
                        backgroundColor: "",
                        axisX: {
                            labelFontFamily:"Arial" ,
                            gridColor: "Silver",
                            tickColor: "silver",
                            labelFontColor: "#3E606F",
                            labelFontSize: 12,
                            labelText: "{name}",
                        },
                        axisY: {
                            gridThickness: 1,
                            indexLabelFontFamily:"Arial" ,
                            gridColor: "Silver",
                            tickColor: "silver",
                            labelFontColor: "#3E606F",
                            labelFontSize: 14,
                            labelFontWeight: "normal",
                        },
                        dataPointMaxWidth: 25,
                        data: [
                            {
                                type: "column",
                                showInLegend: false,
                                indexLabelPlacement: "outside",
                                indexLabelFontSize: 14,
                                yValueFormatString: "#",
                                indexLabelFontWeight: "bold",
                                indexLabelFontFamily:"Arial",
                                indexLabel: "{y}",
                                labelFontSize: 14,
                                toolTipContent: "Vào: {y}",
                                click: function(e) {
                                    window.location.href=e.dataPoint.content;
                                },
                                dataPoints: [
                                    <?php
                                    $query8="SELECT MONTH(date_in) AS m,YEAR(date_in) AS y FROM lop_mon WHERE ID_LM='$lmID'";
                                    $result8=mysqli_query($db,$query8);
                                    $data8=mysqli_fetch_assoc($result8);
                                    $a=$data8["m"];
                                    $b=$data8["y"];
                                    $m=date("m");
                                    $y=date("Y");
                                    while(true) {
                                        $aa=format_month_db($a);
                                        $c="T".$aa."/".substr("$b",2);
                                        $result5=count_hs_vao($aa,$b,$lmID);
                                        $data5=mysqli_fetch_assoc($result5);
                                        echo "{y:$data5[dem], indexLabelFontColor: '', color: '', label: '$c', content: 'http://localhost/www/TDUONG/thaygiao/hoc-sinh-vao-ra/in/$b-".format_month_db($a)."/'},";
                                        $a++;
                                        if($a==13) {
                                            $a= 01;
                                            $b++;
                                        }
                                        if($a==$m+1 && $b==$y) {
                                            break;
                                        }
                                    }
                                    ?>
                                ]
                            },

                            {
                                type: "column",
                                showInLegend: false,
                                indexLabelPlacement: "outside",
                                indexLabelFontSize: 14,
                                yValueFormatString: "#",
                                indexLabelFontWeight: "bold",
                                indexLabelFontFamily:"Arial",
                                indexLabel: "{y}",
                                labelFontSize: 14,
                                toolTipContent: "Ra: {y}",
                                click: function(e) {
                                    window.location.href=e.dataPoint.content;
                                },
                                dataPoints: [
                                    <?php
                                    $dem=0;
                                    $query8="SELECT MONTH(date_in) AS m,YEAR(date_in) AS y FROM lop_mon WHERE ID_LM='$lmID'";
                                    $result8=mysqli_query($db,$query8);
                                    $data8=mysqli_fetch_assoc($result8);
                                    $a=$data8["m"];
                                    $b=$data8["y"];
                                    $m=date("m");
                                    $y=date("Y");
                                    while(true) {
                                        $aa=format_month_db($a);
                                        $c="T".$aa."/".substr("$b",2);
                                        $result6=count_hs_ra($aa,$b,$lmID);
                                        $data6=mysqli_fetch_assoc($result6);
                                        $query7="SELECT COUNT(ID_STT) AS dem FROM nghi_temp WHERE start <= '$b-$aa-01' AND '$b-$aa-$songay_arr[$a]' <= end AND ID_LM ='$lmID'";
                                        $result7=mysqli_query($db,$query7);
                                        $data7=mysqli_fetch_assoc($result7);
                                        echo "{y: ".($data6["dem"]+$data7["dem"]).", indexLabelFontColor: '', color: '', label: '', content: 'http://localhost/www/TDUONG/thaygiao/hoc-sinh-vao-ra/out/$b-".format_month_db($a)."/'},";
                                        $a++;
                                        if($a==13) {
                                            $a= 01;
                                            $b++;
                                        }
                                        $dem++;
                                        if($a==$m+1 && $b==$y) {
                                            break;
                                        }
                                    }
                                    ?>
                                ]
                            },
                    ]
                    });
                chartGame.render();
                var myScroll2 = new IScroll('#main-wapper', { scrollX: true, scrollY: false, mouseWheel: false, startX: -17*<?php echo $dem; ?>});
            }
        </script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/thaygiao/js/canvasjs.min.js"></script>

    </head>

    <body>
    <div id="BODY">

        <?php require_once("include/TOP.php"); ?>

        <div id="MAIN">
            <div id="main-mid">
                <h2>Biểu đồ thống kê học sinh vào - ra</h2>
                <div>
                    <div class="status" id="main-wapper">
                        <div></div>
                        <div id='chartGame' style='width:1500px;height: 300px;'></div>
                        <table class="table">
                            <tr style="background:#3E606F;">
                                <th><span>STT</span></th>
                                <th><span>Mã số</span></th>
                                <th><span>Họ tên</span></th>
                                <th><span>Ngày vào học</span></th>
                                <th><span>Ngày nghỉ học</span></th>
                            </tr>
                            <?php
                                if($type=="in") {
                                    $stt=1;
                                    $query="SELECT h.ID_HS,h.cmt,h.fullname,m.date_in FROM hocsinh AS h
                                    INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' AND m.date_in LIKE '$date-%'
                                    ORDER BY m.date_in DESC";
                                    $result=mysqli_query($db,$query);
                                    while($data=mysqli_fetch_assoc($result)) {
                                        echo"<tr>
                                            <td><span>$stt</span></td>
                                            <td><span>$data[cmt]</span></td>
                                            <td><span>$data[fullname]</span></td>
                                            <td><span>".format_dateup($data["date_in"])."</span></td>
                                            <td></td>
                                        </tr>";
                                        $stt++;
                                    }
                                } else if($type=="out") {
                                    $stt=1;
                                    $query="SELECT h.ID_HS,h.cmt,h.fullname,n.date FROM hocsinh AS h
                                    INNER JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID' AND n.date LIKE '$date-%'
                                    ORDER BY n.date DESC";
                                    $result=mysqli_query($db,$query);
                                    while($data=mysqli_fetch_assoc($result)) {
                                        echo"<tr>
                                            <td><span>$stt</span></td>
                                            <td><span>$data[cmt]</span></td>
                                            <td><span>$data[fullname]</span></td>
                                            <td></td>
                                            <td><span>".format_dateup($data["date"])."</span></td>
                                        </tr>";
                                        $stt++;
                                    }
                                    $query="SELECT h.ID_HS,h.cmt,h.fullname,t.start,t.end FROM hocsinh AS h
                                    INNER JOIN nghi_temp AS t ON t.ID_HS=h.ID_HS AND t.ID_LM='$lmID' AND t.start <= '$date-01' AND '$date-$songay_arr[$a]' <= t.end
                                    ORDER BY t.ID_STT DESC";
                                    $result=mysqli_query($db,$query);
                                    while($data=mysqli_fetch_assoc($result)) {
                                        echo"<tr>
                                            <td><span>$stt</span></td>
                                            <td><span>$data[cmt]</span></td>
                                            <td><span>$data[fullname]</span></td>
                                            <td colspan='2'><span>".format_dateup($data["start"])." - ".format_dateup($data["end"])."</span></td>
                                        </tr>";
                                        $stt++;
                                    }
                                }
                            ?>
                        </table>
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