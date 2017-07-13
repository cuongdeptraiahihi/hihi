<?php
ob_start();
session_start();
require_once("../model/open_db.php");
require_once("../model/model.php");
require_once("access_admin.php");

if(isset($_GET["date"])) {
    $date=addslashes($_GET["date"]);
    $temp=explode("-", $date);
    $nam=format_month_db($temp[0]);
    if(isset($temp[1])) {
        $thang=format_month_db($temp[1]);
    } else {
        $thang=format_month_db(date("m"));
    }
    $ngay=31;
} else {
    $ngay= format_month_db(date("d"));
    $thang=format_month_db(date("m"));
    $nam=date("Y");
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
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/jquery-ui.css">

        <style>
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
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.js"></script>
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.multidatespicker.js"></script>
        <script src="http://localhost/www/TDUONG/thaygiao/js/iscroll.js"></script>
        <script type="text/javascript">
            window.onload = function () {
                var me = $(window).width()*12/1349;
                console.log(me);
//                var myScroll = new IScroll('#main-wapper', {scrollX: true, scrollY: false, mouseWheel: false, startX: -72*(<?php //echo date("d"); ?>//-me)});
            }
            $(document).ready(function() {
                $("#main-wapper").scrollLeft(10000);

                $("table#list-diemdanh tr td.check").click(function() {
                    if(!$(this).hasClass("active")) {
                        $("table#list-diemdanh tr td.check").removeClass("active");
                        $("span.button, span.tich").hide();
                        $("span.tich").show();
                        $(this).find("span.tich").hide();
                        $(this).find("span.button").show();
                        $(this).addClass("active");
                    } else {
                        $(this).find("span.tich").show();
                        $(this).find("span.button").hide();
                        $(this).removeClass("active");
                    }
                });

                var data_arr = [];
                var dem_arr = 0;
                $(".table3 tr").delegate("td.lich-on","click",function() {
                    var me = $(this);
                    var buoi = $(this).attr("data-buoi");
                    var thu = $(this).attr("data-thu");
                    var id = parseInt($(this).closest("tr").attr("data-id"));
                    var a = "<i class='fa fa-check id-" + id + "'></i>";
                    if (buoi != "" && thu != 0 && $.isNumeric(thu) && id != 0 && $.isNumeric(id)) {
                        data_arr[dem_arr] = id + "-" + buoi + "-" + thu + "-1";
                        console.log(data_arr[dem_arr]);
                        dem_arr++;
//                            $.ajax({
//                                async: false,
//                                data: "buoi=" + buoi + "&thu=" + thu + "&id=" + id,
//                                type: "post",
//                                url: "http://localhost/www/TDUONG/thaygiao/xuly-trogiang/",
//                                success: function (result) {
                               me.removeClass("lich-on").addClass("lich-off")
                               me.find("span").html(a);
//                                    count_sum();
//                                }
//                            });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });

                $(".table3 tr").delegate("td.lich-off","click",function() {
                    var me = $(this);
                    var buoi = $(this).attr("data-buoi");
                    var thu = $(this).attr("data-thu");
                    var id = parseInt($(this).closest("tr").attr("data-id"));
                    if (buoi != "" && thu != 0 && $.isNumeric(thu) && id != 0 && $.isNumeric(id)) {
                        data_arr[dem_arr] = id + "-" + buoi + "-" + thu + "-0";
                        console.log(data_arr[dem_arr]);
                        dem_arr++;
//                        $.ajax({
//                            async: false,
//                            data: "buoi3=" + buoi + "&thu3=" + thu + "&id3=" + id,
//                            type: "post",
//                            url: "http://localhost/www/TDUONG/thaygiao/xuly-trogiang/",
//                            success: function (result) {
                                me.removeClass("lich-off").addClass("lich-on");
                                me.find("span").html("");
//                                count_sum();
//                            }
//                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });

                $("button.edit").click(function () {
                    var me = $(this);
                    me.html("...");
                    var id = parseInt($(this).closest("td").attr("data-id"));
                    var ajax_data="[";
                    for (i = 0; i < data_arr.length; i++) {
                        res = data_arr[i].split("-");
                        if(res[0] == id) {
                            ajax_data += '{"buoi":"' + res[1] + '","thu":"' + res[2] + '","status":"' + res[3] + '"},';
                        }
                    }
                    ajax_data += '{"id":"' + id + '"}';
                    ajax_data += "]";
                    console.log(ajax_data);
                    $("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3");
                    $.ajax({
                        async: true,
                        data: "data1=" + ajax_data,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-trogiang/",
                        success: function(result) {
                            me.html("Xong");
                            count_sum();
                            var value = $("input.edit-name-" + id).val().trim();
                            if($.isNumeric(id) && id != 0) {
                                $("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3");
                                if (value.length == 0) {
                                    if (confirm("Bạn có muốn xóa trợ giảng này")) {
                                        $.ajax({
                                            async: false,
                                            data: "id_xoa=" + id,
                                            type: "post",
                                            url: "http://localhost/www/TDUONG/thaygiao/xuly-trogiang/",
                                            success: function (result) {
                                                location.reload();
                                            }
                                        });
                                    }
                                } else {
                                    $.ajax({
                                        async: true,
                                        data: "name_edit=" + value + "&id_edit=" + id,
                                        type: "post",
                                        url: "http://localhost/www/TDUONG/thaygiao/xuly-trogiang/",
                                        success: function (result) {
                                            $("#BODY").css("opacity", "1");
                                            $("#popup-loading").fadeOut("fast");
                                            console.log("Thành công!");
                                        }
                                    });
                                }
                            }
                        }
                    });
                });

                $(".table3 tr td span i.fa-plus").click(function () {
                    var me = $(this).closest("td").find("span.tich");
                    var ngay = $(this).closest("td").attr("data-ngay");
                    var id = $(this).closest("td").attr("data-id");
                    var check = "<i class='fa fa-check'></i>";
                    if (ngay != "" && id != 0 && $.isNumeric(id)) {
                        $("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3");
                        $.ajax({
                            async: false,
                            data: "ngay2=" + ngay + "&id2=" + id,
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-trogiang/",
                            success: function (result) {
                                if(result=="ok") {
                                    me.append(check);
                                    update_diemdanh();
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
                    var id = $(this).closest("td").attr("data-id");
                    if (ngay != "" && id != 0 && $.isNumeric(id)) {
                        $("div#popup-confirm").fadeIn("fast");
                        $("button#popup-view").attr("data-ngay", ngay).attr("data-id", id);
                    }
                });

                $("button#popup-addok").click(function () {
                    var name = $(".name-trogiang").val().trim();
                    if(name != "") {
                        $("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3");
                        $.ajax({
                            async: false,
                            data: "name=" + name,
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-trogiang/",
                            success: function (result) {
                                $(".popup").fadeOut("fast");
                                location.reload();
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });

                $("button#popup-view").click(function () {
                    var ngay = $(this).attr("data-ngay");
                    var id = $(this).attr("data-id");
                    var date_bu = "";
                    var bu = 0;
                    if ($('.check-k-bu').is(':checked')) {
                        bu = 1;
                    } else {
                        date_bu = $("input.date-bu").val();
                    }
                    if (ngay != "" && id != 0 && $.isNumeric(id)) {
                        $("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3");
                        $.ajax({
                            async: false,
                            data: "ngay=" + ngay + "&id=" + id + "&ngay_bu=" + date_bu + "&bu=" + bu,
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-trogiang/",
                            success: function (result) {
                                $(".popup").fadeOut("fast");
                                location.reload();
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });

                $(".check-k-bu").click(function () {
                    if($(this).is(":checked")) {
                        $("input.date-bu").hide();
                    } else {
                        $("input.date-bu").show();
                    }
                });

                count_sum();
                function count_sum() {
                    $(".table3 tr td.sum").each(function (index, element) {
                        var id = $(element).attr("data-id");
                        var sum = $(".table3").find("tr td.day i.id-" + id).length;
                        $(element).find("span").html(sum);
                    });
                }

                $(".date-bu").datepicker({
                    dateFormat: 'yy-mm-dd',
                    defaultDate: new Date('<?php echo date("Y-m-d"); ?>')
                });

                var min_height = 100;
                $("table#list-diemdanh tr").each(function(index, element) {
                    var height = $(element).height();
                    min_height = min_height > height ? height : min_height;
                    $("table#list-name tr:eq(" + index + ") td:eq(1)").html($(element).find("td span.tich i").length);
                });

                $("table#list-name tr").each(function(index, element) {
                    if(index > 0) {
                        $(element).css("height",min_height);
                    }
                });

                function update_diemdanh() {
                    $("table#list-diemdanh tr").each(function(index, element) {
                        $("table#list-name tr:eq(" + index + ") td:eq(1)").html($(element).find("td span.tich i").length);
                    });
                }

                $("#add-trogiang").click(function () {
                    $("div#popup-them").fadeIn("fast");
                });
            });
        </script>

    </head>

    <body>

    <div class="popup" id="popup-loading">
        <p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
    </div>

    <div class="popup" id="popup-them" style="width:30%;left:35%;">
        <div class="popup-close"><i class="fa fa-close"></i></div>
        <p style="text-transform:uppercase;">Thêm trợ giảng</p>
        <div style="width:90%;margin:15px auto 15px auto;">
            <input type="text" class="input name-trogiang" autocomplete="off" placeholder="Họ tên trợ giảng" />
        </div>
        <button class="submit" id="popup-addok" type="button">OK</button>
    </div>

    <div class="popup" id="popup-confirm" style="width:30%;left:35%;">
        <div class="popup-close"><i class="fa fa-close"></i></div>
        <p style="text-transform:uppercase;">Lịch bù</p>
        <div style="width:90%;margin:15px auto 15px auto;">
            <input type="text" class="input date-bu" autocomplete="off" placeholder="Click để chọn ngày bù" />
        </div>
        <div style="width:90%;margin:15px auto 15px auto;"><input class="check-k-bu check" type="checkbox"/> Không bù</div>
        <button class="submit" id="popup-view" type="button">OK</button>
    </div>

    <div id="BODY">

        <?php require_once("include/TOP.php"); ?>

        <div id="MAIN">

            <div id="main-mid">
                <h2>Lịch làm việc trợ giảng</h2>
                <div>
                    <div class="status" style="position: relative;">
                        <table class="table table3" id="list-lich">
                            <tr style="height:50px;">
                                <td style='background: #3E606F;width:8%;'><span style="color:#FFF;"></span></td>
                                <td style='background: #3E606F;width:8%;'><span style="color:#FFF;">Thứ 2</span></td>
                                <td style='background: #3E606F;width:8%;'><span style="color:#FFF;">Thứ 3</span></td>
                                <td style='background: #3E606F;width:8%;'><span style="color:#FFF;">Thứ 4</span></td>
                                <td style='background: #3E606F;width:8%;'><span style="color:#FFF;">Thứ 5</span></td>
                                <td style='background: #3E606F;width:8%;'><span style="color:#FFF;">Thứ 6</span></td>
                                <td style='background: #3E606F;width:8%;'><span style="color:#FFF;">Thứ 7</span></td>
                                <td style='background: #3E606F;width:8%;'><span style="color:#FFF;">Chủ Nhật</span></td>
                            </tr>
                            <?php
                            $string="";
                            $trogiang_arr=array();
                            $query = "SELECT ID_TG AS ID,name FROM trogiang_info WHERE loai='cham-cong'";
                            $result = mysqli_query($db, $query);
                            while ($data=mysqli_fetch_assoc($result)) {
                                $trogiang_arr[]=array(
                                    "name" => $data["name"],
                                    "ID"   => $data["ID"]
                                );
                                $string.="<tr>
                                    <td><span>$data[name]</span></td>
                                    <td><span id='trogiang-$data[ID]'></span></td>
                                </tr>";
                            }
                            $m=count($trogiang_arr);
                            $lich_arr=array();
                            $query2="SELECT ID,buoi,thu FROM trogiang_lich ORDER BY ID ASC";
                            $result2 = mysqli_query($db, $query2);
                            while($data2=mysqli_fetch_assoc($result2)) {
                                $temp = "$data2[ID]-$data2[buoi]-$data2[thu]";
                                $lich_arr[$temp] = 1;
                            }
                            $buoi_arr = array();
                            $buoi_arr[] = array(
                                "buoi" => "S",
                                "text" => "Sáng (8h - 12h)"
                            );
                            $buoi_arr[] = array(
                                "buoi" => "C",
                                "text" => "Chiều (3h45 - 6h45)"
                            );
                            $buoi_arr[] = array(
                                "buoi" => "T",
                                "text" => "Tối (6h45 - 9h45)"
                            );
                            $mau=array("#2E9AFE","#F781F3","#F3F781","#00FF80","#FE642E","#A4A4A4","#81BEF7","#8181F7","#8258FA","#DF013A","#69b42e","pink");
                            $c=count($mau);
                            for($i=0;$i<count($buoi_arr);$i++) {
                                echo "<tr>";
                                echo "<td style='padding:0;'><span>" . $buoi_arr[$i]["text"] . "</span></td>";
                                for ($k = 2; $k <= 8; $k++) {
                                    $a = "";
                                    for($j=0;$j<$m;$j++) {
                                        if(isset($lich_arr[$trogiang_arr[$j]["ID"]."-".$buoi_arr[$i]["buoi"]."-".$k])) {
                                            $b=$trogiang_arr[$j]["ID"]%$c;
                                            $a .= "<span  style='display: block; background:$mau[$b];padding:5px 0 5px 0;'>".$trogiang_arr[$j]["name"] . "<br/></span>";
                                        }
                                    }
                                    echo "<td style='padding:0;vertical-align: top;'><span>$a</span></td>";
                                }
                                echo "</tr>";
                            }
                            ?>
                        </table>
                    </div>

                </div>
                <h2>Điểm danh trợ giảng</h2>
                <div>
                    <div class="status" style="position: relative;">
<!--                        <div id="main-wapper" class="clear">-->
                            <div></div>
                            <table class="table table3" id="list-name" style="position: absolute;z-index:9;width:24%;min-width:0;background-color: #FFF;">
                                <tr style="height:52px;">
                                    <th style='background: #3E606F;'><span>Trợ giảng</span></th>
                                    <th style="background: #3E606F;width:29%;"><span>Tổng kết</span></th>
                                </tr>
                                <?php echo $string; ?>
                                <tr>
                                    <td colspan="2"><a href="http://localhost/www/TDUONG/thaygiao/diem-danh-tro-giang/<?php echo get_last_time($nam, $thang); ?>/"><< Tháng trước</a></td>
                                </tr>
                            </table>
                            <div id="main-wapper" style="margin-left: 25%;overflow-x: auto;width: 74%;display: block;">
                                <div></div>
                                <table class="table table3" id="list-diemdanh">
                                    <tr style="height:50px;">
                                        <?php
                                        $thu_string=array("CN","T2","T3","T4","T5","T6","T7");

                                        for($i=1;$i<=$ngay;$i++) {
                                            $thu=date("w", strtotime("$nam-$thang-$i"));
                                            echo "<th style='min-width:70px;background: #3E606F;'><span>".$thu_string[$thu]."<br />".format_month_db($i)."/$thang</span></th>";
                                        }
                                        ?>
                                    </tr>
                                        <?php
                                        $diemdanh_arr=array();
                                        $query2 = "SELECT ID,ngay,COUNT(ID_STT) AS dem FROM trogiang_diemdanh WHERE ngay LIKE '$nam-$thang-%' AND trang_thai='-1' GROUP BY ID,ngay";
                                        $result2 = mysqli_query($db, $query2);
                                        while($data2=mysqli_fetch_assoc($result2)) {
                                            $diemdanh_arr[$data2["ID"]][$data2["ngay"]]=$data2["dem"];
                                        }
                                        for($k = 0; $k < count($trogiang_arr); $k++) {
                                            echo "<tr>";
                                            for ($i = 1; $i <= $ngay; $i++) {
                                                $i = format_month_db($i);
                                                echo "<td class='check' data-ngay='$nam-$thang-$i' data-id='".$trogiang_arr[$k]["ID"]."'><span class='tich'>";
                                                if(isset($diemdanh_arr[$trogiang_arr[$k]["ID"]]["$nam-$thang-$i"])) {
                                                    for ($j = 1; $j <= $diemdanh_arr[$trogiang_arr[$k]["ID"]]["$nam-$thang-$i"]; $j++) {
                                                        echo "<i class='fa fa-check'></i>";
                                                    }
                                                }
                                                echo "</span><span class='button' style='display: none;'><i class='fa fa-minus'></i> | <i class='fa fa-plus'></i></span></td>";
                                            }
                                            echo "</tr>";
                                        }
                                        ?>
                                </table>
                            </div>
<!--                        </div>-->
                    </div>
                </div>
                <h2>Đăng kí lịch</h2>
                <div>
                    <div class="status" style="position: relative;">
                        <table class="table table3" id="list-lich">
                            <tr style="height:50px;">
                                <td style='background: #3E606F;'><button class="submit" id="add-trogiang">Thêm</button></td>
                                <td style='background: #3E606F;width:8%;'><span style="color:#FFF;">Buổi</span></td>
                                <td style='background: #3E606F;width:8%;'><span style="color:#FFF;">Thứ 2</span></td>
                                <td style='background: #3E606F;width:8%;'><span style="color:#FFF;">Thứ 3</span></td>
                                <td style='background: #3E606F;width:8%;'><span style="color:#FFF;">Thứ 4</span></td>
                                <td style='background: #3E606F;width:8%;'><span style="color:#FFF;">Thứ 5</span></td>
                                <td style='background: #3E606F;width:8%;'><span style="color:#FFF;">Thứ 6</span></td>
                                <td style='background: #3E606F;width:8%;'><span style="color:#FFF;">Thứ 7</span></td>
                                <td style='background: #3E606F;width:8%;'><span style="color:#FFF;">Chủ Nhật</span></td>
                                <td style='background: #3E606F;width:12%;'><span style="color:#FFF;">Tổng kết</span></td>
                            </tr>
<!--                            <col style="width: auto;" />-->
<!--                            <col style="width: 8%;" />-->
<!--                            <col style="width: 8%;" />-->
<!--                            <col style="width: 8%;" />-->
<!--                            <col style="width: 8%;" />-->
<!--                            <col style="width: 8%;" />-->
<!--                            <col style="width: 8%;" />-->
<!--                            <col style="width: 8%;" />-->
<!--                            <col style="width: 8%;" />-->
<!--                            <col style="width: 8%;" />-->
                            <?php
                                $buoi_arr=array("S","C","T");
                                for($k=0;$k<$m;$k++) {
                                    if($k % 2 != 0) {
                                        $back = "background:#D1DBBD;";
                                    } else {
                                        $back = "background:#FFF;";
                                    }
                                    for($j=0;$j<count($buoi_arr);$j++) {
                                        echo "<tr class='$buoi_arr[$j]' data-id='" . $trogiang_arr[$k]["ID"] . "'>";
                                       if($j==0) {
                                           echo "<td style='$back' rowspan='3'><input type='text' class='input edit-name-".$trogiang_arr[$k]["ID"]."' style='padding-top: 0;padding-bottom: 0;$back' value='".$trogiang_arr[$k]["name"]."' /></td>";
                                       }
                                           echo "<td style='$back'><span>$buoi_arr[$j]</span></td>";
                                           for ($i = 2; $i < 10; $i++) {
                                               $temp = $trogiang_arr[$k]["ID"]."-".$buoi_arr[$j]."-$i";
                                               if (isset($lich_arr[$temp])) {
                                                   $show = "<i class='fa fa-check id-" . $trogiang_arr[$k]["ID"] . "'></i>";
                                                   $class = "lich-off";
                                               } else {
                                                   $show = "";
                                                   $class = "lich-on";
                                               }
                                               if ($i == 9) {
                                                   if($j==0) {
                                                       echo "<td class='sum' style='$back;text-align:center;position:relative;cursor:pointer;' rowspan='3' data-id='" . $trogiang_arr[$k]["ID"] . "'><span></span>
                                                        <button class='edit submit'>Sửa</button></td>
                                                       </td>";
                                                   }
                                               } else {
                                                   echo "<td class='day $class' style='$back;text-align:center;position:relative;cursor:pointer;'  data-buoi='$buoi_arr[$j]' data-thu='$i'>
                                                        <span>$show</span>
                                                        <p style='display: none;'>";
                                                        if($i == 8) {
                                                            echo"CN";
                                                        } else {
                                                            echo"Thứ $i";
                                                        }
                                                        echo"</p>
                                                    </td>";
                                               }

                                           }

                                        echo "</tr>";
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