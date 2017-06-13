<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
    $lopID=$_SESSION["lop"];
    $lop_mon_name=get_lop_mon_name($lmID);
    $mon_name=get_mon_name($monID);
    add_options("6543210","super-admin-code","","");
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
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/admin/css/mbocuc.css'>";
        } else {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/admin/css/bocuc.css'>";
        }
        ?>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">

        <style>
			#MAIN > #main-mid {width:100%;}
            #main-note {position: fixed;z-index: 99;right: 0;top: 15%;width:40%;}
            .a-explain {position:absolute;z-index: 9;top:10px;left:60%;font-size:11px;padding:5px;border-radius: 6px;display: none;}
            .span-ex:hover a.a-explain {display: block;width:60px;}
            /*table tr td span a, table tr th span a {text-decoration: underline;}*/
        </style>

        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/iscroll.js"></script>
        <script>
			$(document).ready(function() {
			    $("a.link-face").each(function(index,element) {
			        if($(element).attr("href") == "#") {
			            $(element).css("text-decoration","none").attr("href","javascript:void(0)");
                    }
                });

			    $("#show th input").click(function() {
			        $(this).val("Đang load...");
                    $.ajax({
                        async: true,
                        data: "lmID4=" + <?php echo $lmID; ?>,
                        type: "post",
                        url: "http://localhost/www/TDUONG/admin/xuly-mon/",
                        success: function (result) {
                            $("#table-main").append(result);
                            $("#show").remove();
                        }
                    });
                });

                $("a.chot-nghi").click(function() {
                    var cumID = $(this).attr("data-cumID");
                    var lmID = $(this).attr("data-lmID");
                    var lm = $(this).attr("data-lm");
                    $("#popup-ok").attr("data-cumID",cumID).attr("data-lmID",lmID);
                    $("#popup-view").attr("data-link","http://localhost/www/TDUONG/admin/xem-nghi-hoc/"+cumID+"/"+lmID+"/<?php echo $monID; ?>/");
                    $("#BODY").css("opacity","0.3");
                    $("#popup-confirm").fadeIn("fast");
                })

                $("#popup-view").click(function () {
                    $(this).html("Đang tải...");
                    window.open($(this).attr("data-link"),"_blank");
                });

                $("#popup-ok").click(function () {
                    var cumID = $(this).attr("data-cumID");
                    var lmID = $(this).attr("data-lmID")
                    var code = $("input#code-add").val();
                    if(confirm("Bạn có chắc chắn không?") && code!="" && $.isNumeric(cumID) && $.isNumeric(lmID) && cumID!=0) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        $("#popup-confirm").fadeOut("fast");
                        $.ajax({
                            async: true,
                            data: "cumID_chot=" + cumID + "&lmID_chot=" + lmID + "&code=" + code,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
                            success: function (result) {
                                if(result == "none") {
                                    alert("Sai mã Super Admin!");
                                    $("#popup-loading").fadeOut("fast");
                                    $("#popup-confirm").fadeIn("fast");
                                } else {
                                    alert("Kết quả: " + result);
                                    location.reload();
                                }
                            }
                        });
                    }
                });

                $("table.table-nghi").each(function(index,element) {
                    var dem = $(element).find("input.chot-nghi").length;
                    if(dem==0) {
                        $(element).find("tr").remove();
                    }
                });

                $("table#list-note").delegate("span.check-hot","click",function() {
                    var me = $(this);
                    var hsID = $(this).attr("data-hsID");
                    if($(this).hasClass("is_chuy")) {
                        is_hot = 0;
                    } else {
                        is_hot = 1;
                    }
                    if(is_hot==1 || is_hot==0) {
                        me.val("...");
                        $.ajax({
                            async: true,
                            data: "is_hot=" + is_hot + "&hsID_hot=" + hsID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-hocsinh/",
                            success: function (result) {
                                console.log(result);
                                if(is_hot==1) {
                                    me.addClass("is_chuy").removeAttr("style").css("background","red");
                                } else {
                                    me.removeClass("is_chuy").css({"background":"cyan","border":"none","opacity":"0.4"});
                                }
                                me.val("OK");
                            }
                        });
                    }
                });

                $("table#list-note").delegate("span.check-chuy","click",function() {
                    var me = $(this);
                    var nID = $(this).attr("data-nID");
                    if($(this).hasClass("is_chuy")) {
                        is_hot = 0;
                    } else {
                        is_hot = 1;
                    }
                    if(is_hot==1 || is_hot==0) {
                        me.val("...");
                        $.ajax({
                            async: true,
                            data: "is_hot=" + is_hot + "&nID_hot=" + nID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-hocsinh/",
                            success: function (result) {
                                console.log(result);
                                if(is_hot==1) {
                                    me.addClass("is_chuy").removeAttr("style").css("background","red").html("NEW");
                                } else {
                                    me.removeClass("is_chuy").html("<i class='fa fa-check'></i>");
                                }
                            }
                        });
                    }
                });

                $("input#load-more").click(function () {
                    me = $(this);
                    var dem = me.attr("data-dem");
                    me.val("Đang load...");
                    if(dem > 0) {
                        $.ajax({
                            async: true,
                            data: "note_load=" + dem,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-mon/",
                            success: function (result) {
                                if(result == "none") {
                                    me.val("Hết");
                                } else {
                                    $(result).insertBefore(me.closest("tr"));
                                    me.attr("data-dem",$("table#list-note tr").length - 2);
                                    me.val("Load tiếp");
                                }
                            }
                        });
                    }
                });

                $("#main-note").delegate("tr th.th-close","click",function() {
                    $("#main-note").fadeOut("fast").html("");
                });

                $("table#list-note").delegate("tr > td.view-all","click",function(e) {
                    if(!$(e.target).is("span.check-chuy")) {
                        var hsID = $(this).attr("data-hsID");
                        var me = $(this);
                        if (hsID != 0 && $.isNumeric(hsID)) {
                            $("#main-note").hide().html("");
//                        $("#main-note").html("<table class='table' style='background: #FFF;'><tr><td colspan='2'><span>Đang tải</span></td></tr></table>");
                            $.ajax({
                                async: true,
                                data: "hsID_all_note=" + hsID,
                                type: "post",
                                url: "http://localhost/www/TDUONG/admin/xuly-hocsinh/",
                                success: function (result) {
                                    $("table#list-note tr td.view-all").css("background", "none");
                                    me.css("background", "cyan");
                                    $("#main-note").slideDown("fast").addClass("active").html(result);
                                }
                            });
                        }
                    }
                });
			});
		</script>
        <script type="text/javascript">
            //document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
            window.onload = function () {
                <?php
                    $mau_arr=array("#EF5350","#96c8f3","yellow","#e7a53f","#96c8f3");
                    $ketqua=$lmID_arr=$cum_arr=array();
                    $result=get_all_lop_mon2($monID);
                    $dem2=0;
                    $price=2000000;
                    while($data=mysqli_fetch_assoc($result)) {
                        $price += 10000*count_hs_in_group($data["ID_LM"]);
                        $max = 0;
                        $lmID_arr[$dem2] = array(
                            "ID_LM" => $data["ID_LM"],
                            "name" => $data["name"],
                            "max" => $max
                        );
                        $pre_date = date("Y-m-d");

                        $string="";
                        $query2 = "SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE ID_LM = '$data[ID_LM]' AND ID_MON = '$monID' ORDER BY ID_CUM DESC,date DESC LIMIT 14";
                        $result2 = mysqli_query($db, $query2);
                        while ($data2 = mysqli_fetch_assoc($result2)) {
                            $string .= ",'$data2[ID_CUM]'";
                        }

                        $con = "";
                        $cumID = 0;
                        $dem = 0;
                        $query4 = "SELECT ID_CUM,date FROM diemdanh_buoi WHERE ID_CUM IN (" . substr($string, 1) . ") AND ID_LM = '$data[ID_LM]' AND ID_MON = '$monID' ORDER BY ID_CUM DESC,date DESC";
                        $result4 = mysqli_query($db, $query4);
                        while ($data4 = mysqli_fetch_assoc($result4)) {
                            $thu = date("w",strtotime($data4["date"])) + 1;
                            if (($cumID != $data4["ID_CUM"] && $dem != 0)) {
                                $content0 = $content1 = $content2 = "";
                                $query3 = "SELECT h.cmt,d.is_phep,d.confirm FROM diemdanh_nghi AS d INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS WHERE d.ID_CUM = '$cumID' AND d.ID_LM = '$data[ID_LM]' AND d.ID_MON = '$monID' ORDER BY d.is_phep ASC,h.cmt ASC";
                                $result3 = mysqli_query($db, $query3);
                                $dem30 = $dem31 = $dem32 = 0;
                                while ($data3 = mysqli_fetch_assoc($result3)) {
                                    if ($data3["is_phep"] == 1) {
                                        $content1 .= $data3["cmt"] . " (P), ";
                                        $dem31++;
                                        if ($dem31 % 3 == 0) {
                                            $content1 .= "<br />";
                                        }
                                    } else {
                                        if($data3["confirm"] == 1) {
                                            $content2 .= $data3["cmt"] . ", ";
                                            $dem32++;
                                            if ($dem32 % 3 == 0) {
                                                $content2 .= "<br />";
                                            }
                                        } else {
                                            $content0 .= $data3["cmt"] . ", ";
                                            $dem30++;
                                            if ($dem30 % 3 == 0) {
                                                $content0 .= "<br />";
                                            }
                                        }
                                    }
                                }
                                $max = $max > ($dem30+$dem32) ? $max : ($dem30+$dem32);
                                $max = $max > $dem31 ? $max : $dem31;
                                $noidung0 = "{label: '" . substr($con, 3) . "', y: ".($dem30).", indexLabel: '";if($dem30!=0) {$noidung0.="$dem30";}$noidung0.="', name: '$dem30', content: 'http://localhost/www/TDUONG/admin/hoc-sinh-nghi-hoc/$cumID/$data[ID_LM]/$data[ID_LM]/0/'},";
                                $noidung1 = "{label: '" . substr($con, 3) . "', y: ".(-$dem31).", indexLabel: '";if($dem31!=0) {$noidung1.="$dem31";}$noidung1.="', name: '$dem31', content: 'http://localhost/www/TDUONG/admin/hoc-sinh-nghi-hoc/$cumID/$data[ID_LM]/$data[ID_LM]/1/'},";
                                $noidung2 = "{label: '" . substr($con, 3) . "', y: ".($dem32).", indexLabel: '', name: '$dem32', content: 'http://localhost/www/TDUONG/admin/hoc-sinh-nghi-hoc/$cumID/$data[ID_LM]/$data[ID_LM]/5/'},";
                                $ngay=get_last_cum_date($cumID,$data["ID_LM"],$monID);
                                $ketqua[$data["ID_LM"]][]=array(
                                    "ngay" => $ngay,
                                    "hoc" => $noidung1,
                                    "nghi" => $noidung0,
                                    "confirm" => $noidung2
                                );
                                $cum_arr[$data["ID_LM"]][] = array(
                                    "cumID" => $cumID,
                                    "lmID" => $data["ID_LM"],
                                    "ngay" => $ngay
                                );
                                $con = "";
                            }
                            $dem++;
                            if (stripos($con, format_date($data4["date"])) === false) {
                                $con .= " - " . format_date($data4["date"])." (T$thu)";
                            }
                            $cumID = $data4["ID_CUM"];
                        }
                        $lmID_arr[$dem2]["max"] = $max;
                        $dem2++;
                    }

                    $string="";
                    $query2 = "SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE ID_LM = '0' AND ID_MON = '$monID' ORDER BY ID_CUM DESC,date DESC LIMIT 4";
                    $result2 = mysqli_query($db, $query2);
                    while ($data2 = mysqli_fetch_assoc($result2)) {
                        $string .= ",'$data2[ID_CUM]'";
                    }

                    $con = "";
                    $cumID = 0;
                    $dem = 0;
                    $query4 = "SELECT ID_CUM,date FROM diemdanh_buoi WHERE ID_CUM IN (" . substr($string, 1) . ") AND ID_LM = '0' AND ID_MON = '$monID' ORDER BY ID_CUM DESC,date DESC";
                    $result4 = mysqli_query($db, $query4);
                    while ($data4 = mysqli_fetch_assoc($result4)) {
                        $thu = date("w",strtotime($data4["date"])) + 1;
                        if (($cumID != $data4["ID_CUM"] && $dem != 0)) {
                            for($i=0;$i<count($lmID_arr);$i++) {
                                $max = $lmID_arr[$i]["max"];
                                $content0 = $content1 = $content2 = "";
                                $query3 = "SELECT h.cmt,d.is_phep,d.confirm FROM diemdanh_nghi AS d INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_LM='".$lmID_arr[$i]["ID_LM"]."' WHERE d.ID_CUM = '$cumID' AND d.ID_LM = '0' AND d.ID_MON = '$monID' ORDER BY d.is_phep ASC,h.cmt ASC";
                                $result3 = mysqli_query($db, $query3);
                                $dem30 = $dem31 = $dem32 = 0;
                                while ($data3 = mysqli_fetch_assoc($result3)) {
                                    if ($data3["is_phep"] == 1) {
                                        $content1 .= $data3["cmt"] . " (P), ";
                                        $dem31++;
                                        if ($dem31 % 3 == 0) {
                                            $content1 .= "<br />";
                                        }
                                    } else {
                                        if($data3["confirm"] == 1) {
                                            $content2 .= $data3["cmt"] . ", ";
                                            $dem32++;
                                            if ($dem32 % 3 == 0) {
                                                $content2 .= "<br />";
                                            }
                                        } else {
                                            $content0 .= $data3["cmt"] . ", ";
                                            $dem30++;
                                            if ($dem30 % 3 == 0) {
                                                $content0 .= "<br />";
                                            }
                                        }
                                    }
                                }
                                $max = $max > ($dem30+$dem32) ? $max : ($dem30+$dem32);
                                $max = $max > $dem31 ? $max : $dem31;
                                $noidung0 = "{label: '" . substr($con, 3) . "', y: ".($dem30).", indexLabel: '";if($dem30!=0) {$noidung0.="$dem30";}$noidung0.="', name: '$dem30', content: 'http://localhost/www/TDUONG/admin/hoc-sinh-nghi-hoc/$cumID/0/".$lmID_arr[$i]["ID_LM"]."/0/'},";
                                $noidung1 = "{label: '" . substr($con, 3) . "', y: ".(-$dem31).", indexLabel: '";if($dem31!=0) {$noidung1.="$dem31";}$noidung1.="', name: '$dem31', content: 'http://localhost/www/TDUONG/admin/hoc-sinh-nghi-hoc/$cumID/0/".$lmID_arr[$i]["ID_LM"]."/1/'},";
                                $noidung2 = "{label: '" . substr($con, 3) . "', y: ".($dem32).", indexLabel: '', name: '$dem32', content: 'http://localhost/www/TDUONG/admin/hoc-sinh-nghi-hoc/$cumID/0/".$lmID_arr[$i]["ID_LM"]."/5/'},";
                                $ngay=get_last_cum_date($cumID,$data["ID_LM"],$monID);
                                $ketqua[$lmID_arr[$i]["ID_LM"]][]=array(
                                    "ngay" => $ngay,
                                    "hoc" => $noidung1,
                                    "nghi" => $noidung0,
                                    "confirm" => $noidung2
                                );
                                $cum_arr[$lmID_arr[$i]["ID_LM"]][] = array(
                                    "cumID" => $cumID,
                                    "lmID" => 0,
                                    "ngay" => $ngay
                                );
                                $lmID_arr[$i]["max"] = $max;
                            }
                            $con = "";
                        }
                        $dem++;
                        if (stripos($con, format_date($data4["date"])) === false) {
                            $con .= " - " . format_date($data4["date"])." (CN)";
                        }
                        $cumID = $data4["ID_CUM"];
                    }

                    for($i=0;$i<count($lmID_arr);$i++) {
                        usort($ketqua[$lmID_arr[$i]["ID_LM"]],"date_sort_desc");
                        usort($cum_arr[$lmID_arr[$i]["ID_LM"]],"date_sort_desc");
                    }

                    for($i=0;$i<count($lmID_arr);$i++) {
                        //echo"var myScroll = new IScroll('#main-wapper-$dem2', { scrollX: true, scrollY: false, mouseWheel: false});";
                        echo "var chart_$i = new CanvasJS.Chart('chartContainer_$i',
                        {
                            animationEnabled: true,
                            axisX:{
                                indexLabelFontFamily:'Arial' ,
                                labelFontFamily:'Arial' ,
                                gridColor: 'silver',
                                tickColor: '#FFF',
                                gridThickness: 0,
                                labelFontColor: '#FFF',
                                labelFontSize: 1,
                                labelFontWeight: 'normal',
                                labelMaxWidth: 80,
                                interval: 1,
                            },
                            axisY: {
                                indexLabelFontFamily:'Arial' ,
                                gridColor: 'silver',
                                tickColor: '#FFF',
                                gridThickness: 0,
                                labelFontColor: '#FFF',
                                labelFontSize: 1,
                                labelFontWeight: 'normal',
                                maximum: ".($lmID_arr[$i]["max"]+20).",
                                minimum: ".(-$lmID_arr[$i]["max"]-20).",
                                interval: ".ceil(($lmID_arr[$i]["max"]+20)/2).",
                            },
                            toolTip:{
                                shared:true,
                            },
                            legend: {
                                fontSize: 14,
                                fontFamily: 'Arial',
                                fontColor: '#3E606F',
                                horizontalAlign: 'left',
                                verticalAlign: 'top',
                                maxWidth: 150,
                            },
                            theme: 'theme2',
                            dataPointMaxWidth: 27,
                            data: [";
                                    echo "{
                                type: 'stackedColumn',
                                showInLegend: false,
                                indexLabelFontFamily:'Arial' ,
                                indexLabelFontColor: '" . $mau_arr[2] . "',
                                indexLabelFontWeight: 'bold',
                                indexLabelFontSize: 14,
                                indexLabelPlacement: 'outside',
                                name: 'Ko phép (đã xác nhận)',
                                color: '" . $mau_arr[2] . "',
                                toolTipContent: '{label}<br />Ko phép (đã xác nhận): {name}',
                                click: function(e) {
                                    window.location.href=e.dataPoint.content;
                                },
                                dataPoints: [";
                                    $j=count($ketqua[$lmID_arr[$i]["ID_LM"]])-1;
                                    while($j >=0) {
                                        echo $ketqua[$lmID_arr[$i]["ID_LM"]][$j]["confirm"] . "\n";
                                        $j--;
                                    }
                                    echo "]
                                },";
                                    echo "{
                                type: 'stackedColumn',
                                showInLegend: false,
                                indexLabelFontFamily:'Arial' ,
                                indexLabelFontColor: '" . $mau_arr[0] . "',
                                indexLabelFontWeight: 'bold',
                                indexLabelFontSize: 14,
                                indexLabelPlacement: 'outside',
                                name: 'Ko phép (chưa xác nhận)',
                                color: '" . $mau_arr[0] . "',
                                toolTipContent: 'Ko phép (chưa xác nhận): {name}',
                                click: function(e) {
                                    window.location.href=e.dataPoint.content;
                                },
                                dataPoints: [";
                                    $j=count($ketqua[$lmID_arr[$i]["ID_LM"]])-1;
                                    while($j >= 0) {
                                        echo $ketqua[$lmID_arr[$i]["ID_LM"]][$j]["nghi"] . "\n";
                                        $j--;
                                    }
                                    echo "]
                                },";
                                echo "{
                                    type: 'stackedColumn',
                                    showInLegend: false,
                                    indexLabelFontFamily:'Arial' ,
                                    indexLabelFontColor: '" . $mau_arr[1] . "',
                                    indexLabelFontWeight: 'bold',
                                    indexLabelFontSize: 14,
                                    indexLabelPlacement: 'outside',
                                    name: 'Có phép',
                                    color: '" . $mau_arr[1] . "',
                                    toolTipContent: 'Có phép: {name}',
                                    click: function(e) {
                                        window.location.href=e.dataPoint.content;
                                    },
                                    dataPoints: [";
                                        $j=count($ketqua[$lmID_arr[$i]["ID_LM"]])-1;
                                        while($j >= 0) {
                                            echo $ketqua[$lmID_arr[$i]["ID_LM"]][$j]["hoc"] . "\n";
                                            $j--;
                                        }
                                    echo "]
                                    }";
                            echo"]
                        });

                        chart_$i.render();";
                    }
                ?>
//                var chartGame = new CanvasJS.Chart("chartGame",
//                    {
//                        animationEnabled: true,
//                        interactivityEnabled: true,
//                        theme: "theme2",
//                        toolTip: {
//                            shared: false
//                        },
//                        backgroundColor: "",
//                        axisX: {
//                            labelFontFamily:"Arial" ,
//                            gridColor: "Silver",
//                            tickColor: "silver",
//                            labelFontColor: "#3E606F",
//                            labelFontSize: 12,
//                            labelText: "{name}",
//                        },
//                        axisY: {
//                            gridThickness: 1,
//                            indexLabelFontFamily:"Arial" ,
//                            gridColor: "Silver",
//                            tickColor: "silver",
//                            labelFontColor: "#3E606F",
//                            labelFontSize: 14,
//                            labelFontWeight: "normal",
//                        },
//                        dataPointMaxWidth: 43,
//                        data: [
//                        <?php
//                            for($i=0;$i<count($lmID_arr);$i++) {
//                                $mau=$mau_arr[$i];
//                                $hsin=count_hs_in_group($lmID_arr[$i]["ID_LM"]);
//                                $name=mb_strtoupper($lmID_arr[$i]["name"],"UTF-8");
//                        ?>
//                            {
//                                type: "column",
//                                showInLegend: false,
//                                indexLabelPlacement: "outside",
//                                indexLabelFontSize: 14,
//                                yValueFormatString: "#",
//                                indexLabelFontWeight: "bold",
//                                indexLabelFontFamily:"Arial" ,
//                                indexLabel: "{y}",
//                                labelFontSize: 14,
//                                toolTipContent: "<?php //echo $name; ?>//: {y}",
//                                dataPoints: [
//                                    { y: <?php //echo count_hs_mon_lop($lmID_arr[$i]["ID_LM"])-$hsin; ?>//, indexLabelFontColor: "<?php //echo $mau; ?>//", color: "<?php //echo $mau; ?>//", label: "KHÔNG THAM GIA"},
//                                    { y: <?php //echo $hsin; ?>//, indexLabelFontColor: "<?php //echo $mau; ?>//", color: "<?php //echo $mau; ?>//", label: "ĐÃ THAM GIA"},
//                                    { y: <?php //echo count_game_group($lmID_arr[$i]["ID_LM"]); ?>//, indexLabelFontColor: "<?php //echo $mau; ?>//", color: "<?php //echo $mau; ?>//", label: "SỐ NHÓM"}
//                                ]
//                            },
//                        <?php //} ?>
//                        ]
//                    });
//                chartGame.render();
            }
        </script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/admin/js/canvasjs.min.js"></script>

	</head>

    <body>

        <div class="popup" id="popup-loading">
            <p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
        </div>

        <div class="popup" id="popup-confirm" style="width:30%;left:35%;">
            <div class="popup-close"><i class="fa fa-close"></i></div>
            <p style="text-transform:uppercase;">Mã Super Admin</p>
            <div style="width:90%;margin:15px auto 15px auto;">
                <input id="code-add" type="password" class="input" autocomplete="off" placeholder="Nhập mã..." />
            </div>
            <div>
                <button class="submit" id="popup-ok">Chốt (Ko thông báo)</button>
                <button class="submit" id="popup-view" onclick="">Xem trước</button>
            </div>
        </div>

      	<div id="BODY">

        	<?php require_once("include/TOP.php"); ?>

            <div id="MAIN">

                <div id="main-mid">
                	<h2>BIỂU ĐỒ NGHỈ HỌC <span style="font-weight: 600;"><?php echo"môn $mon_name"; ?></span></h2>
                	<div>
                    	<div class="status" style="position: relative;">
                            <?php
                                for($dem=0;$dem<count($lmID_arr);$dem++) {
                                    echo"<div class='main-top' style='display: block;'>
                                        <input class='submit' type='button' value='".$lmID_arr[$dem]["name"]."' style='float:left;' />
                                    </div>
                                    <div class='main-top table3' style='display:block;overflow:hidden;height: 200px;clear:both;' id='main-wapper-$dem'>
                                        <div></div>
                                        <div id='chartContainer_$dem' style='width:100%;height: 100%;'></div>
                                    </div>";
                                    echo"<div class='main-top table3' style='display: block;position: absolute;margin-top: -102px;width:94%;'>
                                        <p style='border: 1px solid #3E606F;padding-left:1.2%;'>";
                                            for($i=count($cum_arr[$lmID_arr[$dem]["ID_LM"]])-1;$i>=0;$i--) {
                                                echo"<span style='display:block;width:6.21%;float:left;margin-top:-5px;position: relative;' class='span-ex'>";
                                                if(!check_done_options($cum_arr[$lmID_arr[$dem]["ID_LM"]][$i]["cumID"],"diemdanh-nghi",$cum_arr[$lmID_arr[$dem]["ID_LM"]][$i]["lmID"],$monID)) {
                                                    echo"<a href='javascript:void(0)' class='chot-nghi' data-lm='".$lmID_arr[$dem]["ID_LM"]."' data-lmID='".$cum_arr[$lmID_arr[$dem]["ID_LM"]][$i]["lmID"]."' data-cumID='".$cum_arr[$lmID_arr[$dem]["ID_LM"]][$i]["cumID"]."' style='background:yellow;border-radius:100px;display:block;height:10px;width:10px;margin:auto;'></a>
                                                    <a href='javascript:void(0)' class='a-explain' style='background:yellow;color:#3E606F;font-weight:600;'>".format_date($cum_arr[$lmID_arr[$dem]["ID_LM"]][$i]["ngay"])." chưa chốt!</a>";
                                                } else {
                                                    echo"<a href='http://localhost/www/TDUONG/admin/hoc-sinh-nghi-hoc/".$cum_arr[$lmID_arr[$dem]["ID_LM"]][$i]["cumID"]."/".$cum_arr[$lmID_arr[$dem]["ID_LM"]][$i]["lmID"]."/".$lmID_arr[$dem]["ID_LM"]."/0/' style='background:#3E606F;border-radius:100px;display:block;height:10px;width:10px;margin:auto;'></a>
                                                    <a href='javascript:void(0)' class='a-explain' style='background:#3E606F;color:#FFF;'>".format_date($cum_arr[$lmID_arr[$dem]["ID_LM"]][$i]["ngay"])." đã chốt!</a>";
                                                }
                                                echo"</span>";
                                            }
                                        echo"</p>
                                        <p class='fa fa-arrow-right' style='position: absolute;z-index:9;right:-5px;top:-7px;'></p>
                                    </div>";
                                }
                            ?>
                        </div>
                    </div>
<!--                    <h2>GHI CHÚ HỌC SINH</h2>-->
<!--                    <div>-->
<!--                        <div class="status" style="position: relative;">-->
<!--                            <div id="main-note" style="display: none;">-->
<!---->
<!--                            </div>-->
<!--                            <table class="table table3" id="list-note" cellspacing="3">-->
<!--                                --><?php
//                                $dem=0;
//                                $result=get_list_note(0,10);
//                                while($data=mysqli_fetch_assoc($result)) {
//                                    if($data["has"]!="") {
//                                        $day="<b>(".format_date($data["has"]).")</b>";
//                                    } else {
//                                        $day="";
//                                    }
//                                    echo "<tr>
//                                        <th style='background: #3E606F;width:5%;' class='hidden'><span>" . ($dem + 1) . "</span></th>
//                                        <th style='background: #EF5350;width:10%;min-width:70px;'><span><a href='http://localhost/www/TDUONG/admin/hoc-sinh-chi-tiet/ma/$data[maso]/' target='_blank'>$data[maso]</a></span></td>
//                                        <th style='background: #EF5350;width:15%;min-width:150px;'><span><a href='" . formatFacebook($data["facebook"]) . "' target='_blank' class='link-face'>$data[fullname]</a></span></td>
//                                        <td style='text-align:left;padding-left:15px;position:relative;cursor:pointer;' class='view-all' data-hsID='$data[ID_HS]'>
//                                            <span>$day " . nl2br($data["note"]) . "</span>";
//                                        if ($data["hot"] == 1) {
//                                            if($data["has"]!="") {
//                                                echo "<span class='note-count check-chuy is_chuy' data-nID='$data[ID]'>NEW</span>";
//                                            } else {
//                                                echo "<span class='note-count check-hot is_chuy' data-hsID='$data[ID]'>NEW</span>";
//                                            }
//                                        }
//                                        echo"</td>";
//                                    echo"</tr>";
//                                    $dem++;
//                                }
//                                ?>
<!--                                <tr>-->
<!--                                    <th colspan="4" style="text-align: right;"><input type='submit' data-dem="--><?php //echo $dem; ?><!--" id="load-more" class='submit' value='Load tiếp' /></th>-->
<!--                                </tr>-->
<!--                            </table>-->
<!--                        </div>-->
<!--                    </div>-->
                    <h2>DANH SÁCH HỌC SINH MỚI NGHỈ</h2>
                    <div>
                        <div class="status" style="position: relative;">
                            <table class="table table3" id="list-note" cellspacing="3">
                                <tr>
                                    <th style='background: #3E606F;width:5%;' class='hidden'><span>STT</span></th>
                                    <th style='background: #3E606F;width:10%;min-width:70px;'><span>Mã số</span></th>
                                    <th style='background: #3E606F;width:20%;min-width:150px;'><span>Họ và tên</span></th>
                                    <th style="background: #3E606F;"><span>Ngày vào học</span></th>
                                    <th style="background: #3E606F;"><span>Ngày nghỉ</span></th>
                                    <th style="background: #3E606F;width: 40%;"><span>Ghi chú cuối</span></th>
                                </tr>
                                <?php
                                $dem=0;
                                $query="SELECT h.ID_HS,h.cmt AS maso,h.fullname,h.facebook,m.date_in,n.date FROM hocsinh AS h
                                INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID'
                                INNER JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID'
                                ORDER BY n.ID_N DESC,n.date DESC
                                LIMIT 10";
                                $result=mysqli_query($db,$query);
                                while($data=mysqli_fetch_assoc($result)) {
                                    $query2="SELECT note FROM hocsinh_note WHERE ID_HS='$data[ID_HS]' ORDER BY ngay DESC LIMIT 1";
                                    $result2=mysqli_query($db,$query2);
                                    $data2=mysqli_fetch_assoc($result2);
                                    echo "<tr>
                                        <th style='background: #3E606F;' class='hidden'><span>" . ($dem + 1) . "</span></th>
                                        <th style='background: #EF5350;'><span><a href='http://localhost/www/TDUONG/admin/hoc-sinh-chi-tiet/ma/$data[maso]/' target='_blank'>$data[maso]</a></span></td>
                                        <th style='background: #EF5350;'><span><a href='" . formatFacebook($data["facebook"]) . "' target='_blank' class='link-face'>$data[fullname]</a></span></td>
                                        <td><span>".format_dateup($data["date_in"])."</span></td>
                                        <td><span>".format_dateup($data["date"])."</span></td>
                                        <td><span>$data2[note]</span></td>
                                    </tr>";
                                    $dem++;
                                }
                                ?>
<!--                                <tr>-->
<!--                                    <th colspan="4" style="text-align: right;"><input type='submit' data-dem="--><?php //echo $dem; ?><!--" id="load-more" class='submit' value='Load tiếp' /></th>-->
<!--                                </tr>-->
                            </table>
                        </div>
                    </div>
                    <h2>NHẮC NHỞ</h2>
                    <div>
                        <div class="status">
                            <table class="table">
                                <?php
                                $stt=0;
                                $result=get_all_lop_mon();
                                while($data=mysqli_fetch_assoc($result)) {
                                    $buoiID=get_new_buoikt($data["ID_MON"],1,1);
                                    $ngay=format_dateup(get_ngay_buoikt($buoiID));
                                    if(!check_done_options($buoiID, "phu-diem", $data["ID_LM"], $data["ID_MON"])) {
                                        echo"<tr>
                                            <th style='background: #3E606F;width:5%;min-width:70px;'><span>".($stt+1)."</span></th>
                                            <td style='text-align:left;padding-left:15px;'><span>Bạn chưa phủ điểm 0 lớp <strong>$data[name]</strong> ngày <strong>$ngay</strong></span></td>
                                        </tr>";
                                        $stt++;
                                    }
                                    if(!check_done_options($buoiID, "kq-thach-dau",$data["ID_LM"],$data["ID_MON"]) && !check_khoa($data["ID_LM"])) {
                                        echo"<tr>
                                            <th style='background: #3E606F;width:5%;min-width:70px;'><span>".($stt+1)."</span></th>
                                            <td style='text-align:left;padding-left:15px;'><span>Bạn chưa xét kết quả thách đấu lớp <strong>$data[name]</strong> ngày <strong>$ngay</strong></span></td>
                                        </tr>";
                                        $stt++;
                                    }
                                    if($data["ID_MON"]==1) {
                                        if (!check_done_options($buoiID, "cap-nhat-diem-1", $data["ID_LM"], $data["ID_MON"])) {
                                            echo "<tr>
                                                <th style='background: #3E606F;width:5%;min-width:70px;'><span>" . ($stt + 1) . "</span></th>
                                                <td style='text-align:left;padding-left:15px;'><span>Bạn chưa cập nhật điểm từ Bgo lên Luyện thi <strong>$data[name]</strong> ngày <strong>$ngay</strong></span></td>
                                            </tr>";
                                            $stt++;
                                        }
                                        if (!check_done_options($buoiID, "cap-nhat-diem-2", $data["ID_LM"], $data["ID_MON"])) {
                                            echo "<tr>
                                                <th style='background: #3E606F;width:5%;min-width:70px;'><span>" . ($stt + 1) . "</span></th>
                                                <td style='text-align:left;padding-left:15px;'><span>Bạn chưa cập nhật điểm từ Luyện thi về Bgo <strong>$data[name]</strong> ngày <strong>$ngay</strong></span></td>
                                            </tr>";
                                            $stt++;
                                        }
                                    }
//                                    if(!check_done_options($buoiID, "kq-ngoi-sao",$data["ID_LM"],$monID) && !check_khoa($data["ID_LM"])) {
//                                        echo"<li><a href='#' style='color:red;font-weight: 600;'>Bạn chưa xét kết quả ngôi sao hy vọng lớp $data[name] ngày $ngay</a></li>";
//                                    }
                                }
                                ?>
                                <tr></tr>
                            </table>
                        </div>
                    </div>
<!--                    <h2>Nhóm trò chơi</h2>-->
<!--                    <div>-->
<!--                        <div class="status">-->
<!--                            <table class="table">-->
<!--                                <tr>-->
<!--                                    <th style="background: #3E606F;"><span>Tiền thưởng</span></th>-->
<!--                                    <td><span>--><?php //echo format_price($price); ?><!--</span></td>-->
<!--                                    <td rowspan="2" colspan="5" style="width:15%;"><input type="button" onclick="location.href='http://localhost/www/TDUONG/admin/game/'" class="submit" value="Chi tiết" /></td>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <th style="background: #3E606F;"><span>Số tiền trừ</span></td>-->
<!--                                    <td><span>60.000đ</span></td>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <th colspan="7" style="width: 100%;">-->
<!--                                        <div id='chartGame' style='width:100%;height: 300px;margin: 25px auto 0 auto;'></div>-->
<!--                                    </th>-->
<!--                                </tr>-->
<!--                            </table>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <h2>HỌC SINH HAY MANG BÀI VỀ NHÀ <strong>(5 TUẦN)</strong></h2>-->
<!--                    <div>-->
<!--                        <div class="status">-->
<!--                            <table class="table">-->
<!--                            --><?php
//                                $dem=0;
//                                $buoi_string="";
//                                $result2=get_all_buoikt($monID,5);
//                                while($data2=mysqli_fetch_assoc($result2)) {
//                                    $buoi_string.=",'$data2[ID_BUOI]'";
//                                }
//                                $result2=get_all_lop_mon2($monID);
//                                while($data2=mysqli_fetch_assoc($result2)) {
//                                    echo"<tr style='background:#3E606F;'>
//                                        <th colspan='5'><span>$data2[name]</span></th>
//                                    </tr>";
//                                    $query = "SELECT d.ID_HS,h.cmt,h.fullname,h.facebook,COUNT(*) AS dem,l.name,AVG(d.diem) AS diemtb FROM diemkt AS d
//                                    INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS
//                                    INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_LM=d.ID_LM
//                                    INNER JOIN lop_mon AS l ON l.ID_LM=d.ID_LM AND l.ID_MON='$monID'
//                                    WHERE d.ID_BUOI IN (".substr($buoi_string,1).") AND d.loai='1' AND d.diem!='0' AND d.ID_LM='$data2[ID_LM]'
//                                    GROUP BY d.ID_HS
//                                    ORDER BY dem DESC LIMIT 5";
//                                    $result = mysqli_query($db, $query);
//                                    //echo mysqli_error($db);
//                                    while ($data = mysqli_fetch_assoc($result)) {
//                                        echo "<tr>
//                                            <th style='background: #3E606F;width:5%;' class='hidden'><span>" . ($dem + 1) . "</span></th>
//                                            <th style='background: #EF5350;width:10%;min-width:70px;'><span><a href='http://localhost/www/TDUONG/admin/hoc-sinh-chi-tiet/ma/$data[cmt]/' target='_blank'>$data[cmt]</a></span></td>
//                                            <th style='background: #EF5350;width:15%;min-width:150px;'><span><a href='" . formatFacebook($data["facebook"]) . "' target='_blank' class='link-face'>$data[fullname]</a></span></td>
//                                            <td><span>$data[dem] bài</span></td>
//                                            <td style='width:30%;' class='hidden'><span>".format_diem($data["diemtb"])."</span></td>
//                                        </tr>";
//                                        $dem++;
//                                    }
//                                }
//                            ?>
<!--                                <tr></tr>-->
<!--                            </table>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <h2>HỌC SINH HỌC KÉM</h2>-->
<!--                    <div>-->
<!--                        <div class="status">-->
<!--                            <table class="table" id="table-main">-->
<!--                                <tr id="show">-->
<!--                                    <th colspan="4" style="text-align: right;"><input type="submit" class="submit" value="Tải dữ liệu" /></th>-->
<!--                                </tr>-->
<!--                            </table>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
<!--                <div id="main-right">-->
<!--                	<div>-->
<!--                        <h3>Biểu đồ nhanh</h3>-->
<!--                        <ul>-->
<!--                            <li><a href="http://localhost/www/TDUONG/admin/tra-cuu/"># Tra cứu</a></li>-->
<!--                            <li><a href="http://localhost/www/TDUONG/admin/dong-bo/" target="_blank"># Đồng bộ</a></li>-->
<!--                            <li><a href="http://localhost/www/TDUONG/admin/hoc-sinh-thong-tin/--><?php //echo $lmID; ?><!--/--><?php //echo $monID; ?><!--/" target="_blank"># Biểu đồ thông tin học sinh</a></li>-->
<!--                            <li><a href="http://localhost/www/TDUONG/admin/hoc-sinh-thong-ke/--><?php //echo $lmID; ?><!--/--><?php //echo $monID; ?><!--/" target="_blank"># Biểu đồ điểm kiểm tra</a></li>-->
<!--                            <li><a href="http://localhost/www/TDUONG/admin/cai-dat-ca/--><?php //echo $lmID; ?><!--/--><?php //echo $monID; ?><!--/" target="_blank"># Biểu đồ ca học - ca thi</a></li>-->
<!--                            <li><a href="http://localhost/www/TDUONG/admin/thach-dau/" target="_blank"># Biểu đồ thách đấu</a></li>-->
<!--                            <li><a href="http://localhost/www/TDUONG/admin/ngoi-sao/" target="_blank"># Biểu đồ ngôi sao hy vọng</a></li>-->
<!--                            <li><a href="http://localhost/www/TDUONG/admin/hoc-sinh-nghi-nhieu/--><?php //echo $lmID; ?><!--/--><?php //echo $monID; ?><!--/" target="_blank"># Biểu đồ học sinh nghỉ nhiều</a></li>-->
<!--                        </ul>-->
<!--                  	</div>-->
<!--                    <div>-->
<!--                        <h3>Nhắc nhở</h3>-->
<!--                        <ul>-->
<!--                            --><?php
//                                $result=get_all_lop_mon();
//                                while($data=mysqli_fetch_assoc($result)) {
//                                    $buoiID=get_new_buoikt($data["ID_MON"],1,1);
//                                    $ngay=format_dateup(get_ngay_buoikt($buoiID));
//                                    if(!check_done_options($buoiID, "phu-diem", $data["ID_LM"], $data["ID_MON"])) {
//                                        echo"<li><a href='#' style='color:red;font-weight: 600;'>Bạn chưa phủ điểm 0 lớp $data[name] ngày $ngay</a></li>";
//                                    }
//                                    if(!check_done_options($buoiID, "kq-thach-dau",$data["ID_LM"],$data["ID_MON"]) && !check_khoa($data["ID_LM"])) {
//                                        echo"<li><a href='#' style='color:red;font-weight: 600;'>Bạn chưa xét kết quả thách đấu lớp $data[name] ngày $ngay</a></li>";
//                                    }
////                                    if(!check_done_options($buoiID, "kq-ngoi-sao",$data["ID_LM"],$monID) && !check_khoa($data["ID_LM"])) {
////                                        echo"<li><a href='#' style='color:red;font-weight: 600;'>Bạn chưa xét kết quả ngôi sao hy vọng lớp $data[name] ngày $ngay</a></li>";
////                                    }
//                                }
//                            ?>
<!--                        </ul>-->
<!--                    </div>-->
<!--                </div>-->
            </div>

        </div>

    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>