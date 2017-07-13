<?php
    ob_start();
    session_start();
    require_once("../model/open_db.php");
    require_once("../model/model.php");
    require_once("access_admin.php");
    if(isset($_GET["ngay"])) {
        $ngay=$_GET["ngay"];
    } else {
        $ngay=0;
    }
    $lmID=$_SESSION["lmID"];
    $monID=$_SESSION["mon"];
    $lmID_arr=array();
    $result=get_all_lop_mon2($monID);
    while($data=mysqli_fetch_assoc($result)) {
        $lmID_arr[] = array(
            "ID_LM" => $data["ID_LM"],
            "name" => $data["name"]
        );
    }
    $n=count($lmID_arr);
    for($i = 0; $i < $n; $i++) {
        if($i != $n-1) {
            $lmID_arr[$i] = NULL;
        }
    }

    $query0="SELECT * FROM options WHERE content='$ngay' AND type='buoi-phat-ve'";
    $result0=mysqli_query($db,$query0);
    $data0=mysqli_fetch_assoc($result0);


    $query1="SELECT * FROM options WHERE type='sl-buoi-phat-ve' AND note2='$ngay'";
    $result1=mysqli_query($db,$query1);
    $data1=mysqli_fetch_assoc($result1);
    $sl=$data1["content"];
?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <title>DANH SÁCH NHÓM</title>

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
            #list-diemdanh tr td.check:hover span.tich {display: none;}
            #list-diemdanh tr td.check:hover span.button {display: block !important;}
            #list-lich tr td.day:hover span {display: none;}
            #list-lich tr td.day:hover p {display: block !important;}
            .has-star {color: yellow;opacity: 1 !important;}
            .need-star, .done-star {opacity: 0.3;}
        </style>

        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.js"></script>
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.multidatespicker.js"></script>
        <script src="http://localhost/www/TDUONG/thaygiao/js/iscroll.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                $("#chose-lop").change(function () {
                    var data = $(this).find("option:selected").val();
                    if(data == "none" || data == "10-11" || data == "11-12") {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity", "0.3");
                        $.ajax({
                            async: true,
                            data: "gameID=<?php echo $data0["ID_O"]; ?>&lop=" + data,
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-game/",
                            success: function(result) {
                                $("#BODY").css("opacity","1");
                                $("#popup-loading").fadeOut("fast");
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                        $("#BODY").css("opacity","1");
                        $("#popup-loading").fadeOut("fast");
                    }
                });

                $("#chose-status").change(function () {
                    var status = $(this).find("option:selected").val();
                    if(status == "on" || status == "off" || status == "freezee") {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity", "0.3");
                        $.ajax({
                            async: true,
                            data: "gameID=<?php echo $data0["ID_O"]; ?>&status=" + status,
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-game/",
                            success: function(result) {
                                $("#BODY").css("opacity","1");
                                $("#popup-loading").fadeOut("fast");
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                        $("#BODY").css("opacity","1");
                        $("#popup-loading").fadeOut("fast");
                    }
                });

                $(".edit-sl").typeWatch({
                    captureLength: 1,
                    callback: function (value) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        if($.isNumeric(value) && value != "") {
                            $.ajax({
                                async: true,
                                data: "ngay_sl=" + value + "&ngay=<?php echo $ngay; ?>",
                                type: "post",
                                url: "http://localhost/www/TDUONG/thaygiao/xuly-game/",
                                success: function (result) {
                                    console.log("OK");
                                    $("#BODY").css("opacity", "1");
                                    $("#popup-loading").fadeOut("fast");
                                }
                            });
                        } else {
                            alert("Dữ liệu không chính xác!");
                            $("#BODY").css("opacity","1");
                            $("#popup-loading").fadeOut("fast");
                        }
                    }
                });

                $(".edit-num").typeWatch({
                    captureLength: 1,
                    callback: function (value) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        var nID = $(this).attr("data-nID");
                        if($.isNumeric(value) && value != "" && $.isNumeric(nID) && nID > 0) {
                            $.ajax({
                                async: true,
                                data: "num_sl=" + value + "&n_id=" + nID + "&ngay=<?php echo $ngay; ?>",
                                type: "post",
                                url: "http://localhost/www/TDUONG/thaygiao/xuly-game/",
                                success: function (result) {
                                    console.log("OK");
                                    $("#BODY").css("opacity", "1");
                                    $("#popup-loading").fadeOut("fast");
                                }
                            });
                        } else {
                            alert("Dữ liệu không chính xác!");
                            $("#BODY").css("opacity","1");
                            $("#popup-loading").fadeOut("fast");
                        }
                    }
                });

                $("i.diemdanh").click(function () {
                    var me = $(this);
                    var sttID = me.closest("tr").attr("data-id");
                    var check = me.attr("data-check");
                    if((check == 0 || check == 1) && $.isNumeric(sttID) && sttID>0) {
                        $.ajax({
                            async: true,
                            data: "check=" + check + "&sttID=" + sttID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-game/",
                            success: function (result) {
                                if(check == 0) {
                                    me.removeClass("fa-check-square-o").addClass("fa-square-o").attr("data-check",1);
                                } else if(check == 1) {
                                    me.removeClass("fa-square-o").addClass("fa-check-square-o").attr("data-check",0);
                                }
                                $("#BODY").css("opacity", "1");
                                $("#popup-loading").fadeOut("fast");
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                        $("#BODY").css("opacity","1");
                        $("#popup-loading").fadeOut("fast");
                    }
                });

                $("i.rate-star").hover(function () {
                    var me = parseInt($(this).index());
                    $(this).closest("span").find("i").each(function(index, element) {
                        if(index <= me) {
                            $(element).addClass("has-star");
                        } else {
                            $(element).removeClass("has-star");
                        }
                    });
                }, function () {
                    $(this).closest("span").find("i.need-star").removeClass("has-star");
                    $(this).closest("span").find("i.done-star").addClass("has-star");
                });

                $("i.status_new").click(function () {
                    var me = $(this);
                    var point = me.closest("span").find("i.has-star").length;
                    var id = me.closest("tr").attr("data-id");
                    if($.isNumeric(id) && id>0 && point > 0 && point < 6) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        $.ajax({
                            async: true,
                            data: "hvID=" + id + "&status=" + point,
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-game/",
                            success: function(result) {
                                me.closest("span").find("i").each(function(index, element) {
                                    if(index <= point-1) {
                                        $(element).addClass("has-star").addClass("done-star").removeClass("need-star");
                                    } else {
                                        $(element).removeClass("has-star").addClass("need-star").removeClass("done-star");
                                    }
                                });
                                $("#BODY").css("opacity","1");
                                $("#popup-loading").fadeOut("fast");
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                        $("#BODY").css("opacity","1");
                        $("#popup-loading").fadeOut("fast");
                    }
                });

                $("i.status_done").click(function () {
                    var me = $(this);
                    var point = me.closest("span").find("i.has-star").length;
                    var id = me.closest("tr").attr("data-id");
                    if($.isNumeric(id) && id>0 && point > 0 && point < 6) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        $.ajax({
                            async: true,
                            data: "hvID=" + id + "&status_done=" + point,
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-game/",
                            success: function(result) {
                                me.closest("span").find("i").each(function(index, element) {
                                    if(index <= point-1) {
                                        $(element).addClass("has-star").addClass("done-star").removeClass("need-star");
                                    } else {
                                        $(element).removeClass("has-star").addClass("need-star").removeClass("done-star");
                                    }
                                });
                                $("#BODY").css("opacity","1");
                                $("#popup-loading").fadeOut("fast");
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                        $("#BODY").css("opacity","1");
                        $("#popup-loading").fadeOut("fast");
                    }
                });
            });

            window.onload = function () {
                var chart2 = new CanvasJS.Chart("chart2",
                    {
                        animationEnabled: true,
                        interactivityEnabled: true,
                        legendText: "{name}",
                        theme: "theme2",
                        toolTip: {
                            shared: true
                        },
                        backgroundColor: "",
                        legend: {
                            fontSize: 14,
                            fontFamily: "Arial",
                            fontColor: "#3E606F",
                            horizontalAlign: "center",
                            verticalAlign: "bottom",
                            fontWeight: "bold",
                        },
                        data: [{
                            type: "pie",
                            indexLabelPlacement: "inside",
                            indexLabelFontSize: 16,
                            indexLabelFontColor: "#FFF",
                            yValueFormatString: "#%",
                            showInLegend: true,
                            startAngle: -90,
                            indexLabel: "{y}",
                            toolTipContent: "{name}: {y} ({content} hs)",
                            dataPoints: [
                                <?php
                                    $query="SELECT COUNT(ID_STT) AS dem FROM hocvien_info WHERE ca='$ngay'";
                                    $result=mysqli_query($db,$query);
                                    $data=mysqli_fetch_assoc($result);
                                    $query1="SELECT COUNT(ID_STT) AS dem FROM hocvien_info WHERE ca='$ngay' AND diemdanh!='0'";
                                    $result1=mysqli_query($db,$query1);
                                    $data1=mysqli_fetch_assoc($result1);
                                ?>
                                {
                                    y: <?php echo $phantram = format_diem($data1["dem"]/$data["dem"]); ?>,
                                    color: "#3E606F",
                                    name: "Có đi",
                                    content: <?php echo $data1["dem"]; ?>
                                },
                                {
                                    y: <?php echo (1-$phantram); ?>,
                                    color: "pink",
                                    name: "Nhận vé ko đi",
                                    content: <?php echo ($data["dem"]-$data1["dem"]); ?>
                                }
                            ]
                        }]
                    });
                chart2.render();
                var chart3 = new CanvasJS.Chart("chart3",
                    {
                        animationEnabled: true,
                        interactivityEnabled: true,
                        legendText: "{name}",
                        theme: "theme2",
                        toolTip: {
                            shared: true
                        },
                        backgroundColor: "",
                        legend: {
                            fontSize: 14,
                            fontFamily: "Arial",
                            fontColor: "#3E606F",
                            horizontalAlign: "center",
                            verticalAlign: "bottom",
                            fontWeight: "bold",
                        },
                        data: [{
                            type: "pie",
                            indexLabelPlacement: "inside",
                            indexLabelFontSize: 16,
                            indexLabelFontColor: "#FFF",
                            yValueFormatString: "#%",
                            showInLegend: true,
                            startAngle: -90,
                            indexLabel: "{y}",
                            toolTipContent: "{name}: {y} ({content} hs)",
                            dataPoints: [
                                <?php
                                    $query2="SELECT COUNT(ID_STT) AS dem FROM hocvien_info WHERE ca='$ngay' AND diemdanh!='0' AND cahoc!=''";
                                    $result2=mysqli_query($db,$query2);
                                    $data2=mysqli_fetch_assoc($result2);
                                ?>
                                {
                                    y: <?php echo $phantram = format_diem($data2["dem"]/$data1["dem"]); ?>,
                                    color: "#3E606F",
                                    name: "Đăng ký học",
                                    content: <?php echo $data2["dem"]; ?>
                                },
                                {
                                    y: <?php echo (1-$phantram); ?>,
                                    color: "pink",
                                    name: "Không đăng ký",
                                    content: <?php echo ($data1["dem"]-$data2["dem"]); ?>
                                }
                            ]
                        }]
                    });
                chart3.render();
            }
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

                <h2>Danh sách người nhận vé ngày <strong><?php echo format_dateup($ngay); ?></strong></h2>
                <div>
                    <div class="status" style="position: relative;">
                        <table class="table table3" style="width: 49%;display: inline-table;">
                            <tr>
                                <?php
                                $n=count($lmID_arr);
                                for($i=0;$i<$n;$i++) {
                                    if($lmID_arr[$i]==NULL) {
                                        continue;
                                    }
                                    echo"<th style='background: #3E606F;width:".format_diem(100/$n)."%'><span>Danh sách nhóm ".$lmID_arr[$i]["name"]."</span></th>";
                                }
                                ?>
                            </tr>
                            <tr>
                                <?php
                                for($i=0;$i<$n;$i++) {
                                    if($lmID_arr[$i]==NULL) {
                                        continue;
                                    }
                                    echo"<td style='padding:0;vertical-align: top;'>
                                        <table class='table'>
                                            <tr>
                                                <td><span>Nhóm</span></td>
                                                <td><span>Số vé</span></td>
                                                <td style='width: 15%;'><span>Đã nhận</span></td>
                                                <td><span>Facebook</span></td>
                                            </tr>";
                                        $query="SELECT g.ID_N,g.name,g.password,g.facebook,o.content,COUNT(l.ID_STT) AS dem FROM game_group AS g 
                                        LEFT JOIN hocvien_info AS l ON l.ID_N=g.ID_N AND l.ca='$ngay'
                                        LEFT JOIN options AS o ON o.type='da-phat-ve' AND o.note=g.ID_N AND o.note2='$ngay'
                                        WHERE g.ID_LM='".$lmID_arr[$i]["ID_LM"]."' 
                                        GROUP BY g.ID_N
                                        ORDER BY dem DESC,g.ID_N ASC";
                                        $result=mysqli_query($db,$query);
                                        while($data=mysqli_fetch_assoc($result)) {
                                            $nID=$data["ID_N"];
                                            echo"<tr class='nhom' data-id='$nID'>
                                                <td><span><a href='http://localhost/www/TDUONG/thaygiao/hoc-vien-chi-tiet/$nID/' target='_blank'>#$nID, $data[name]</a></span></td>
                                                <td style='width: 15%;'><span>$data[dem]</span></td>
                                                <td><input type='number' class='input edit-num' value='$data[content]' placeholder='Số vẽ' style='text-align: center;' data-nID='$nID' /></td>
                                                <td><span><a href='".formatFacebook($data["facebook"])."' target='_blank' style='text-decoration:underline;'>Group fb</a></span></td>
                                            </tr>";
                                        }
                                        echo"</table>
                                    </td>";
                                }
                                ?>
                            </tr>
                        </table>
                        <table class="table table3" style="width: 49%;display: inline-table;">
                            <tr>
                                <th colspan="2" style="background: #3E606F;"><span>Cài đặt</span></th>
                            </tr>
                            <tr>
                                <td style="width: 30%;"><span>Mở khóa</span></td>
                                <td>
                                    <select id="chose-status" class="input">
                                        <option value="on" <?php if($data0["note"]=="on") {echo"selected='selected'";} ?>>Mở</option>
                                        <option value="off" <?php if($data0["note"]=="off") {echo"selected='selected'";} ?>>Khóa</option>
                                        <option value="freezee" <?php if($data0["note"]=="freezee") {echo"selected='selected'";} ?>>Mở nhưng ko sửa thông tin</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><span>Lớp</span></td>
                                <td>
                                    <select id="chose-lop" class="input">
                                        <option value="none" <?php if($data0["note2"]=="none" || $data0["note2"]=="") {echo"selected='selected'";} ?>>Chung</option>
                                        <option value="10-11" <?php if($data0["note2"]=="10-11") {echo"selected='selected'";} ?>>lớp 10 lên 11</option>
                                        <option value="11-12" <?php if($data0["note2"]=="11-12") {echo"selected='selected'";} ?>>lớp 11 lên 12</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><span>Số lượng</span></td>
                                <td><input type='number' class='input edit-sl' value='<?php echo $sl; ?>' min="0" max="500" placeholder='Số vẽ' style='text-align: center;' data-nID='$nID' /></td>
                            </tr>
                            <tr>
                                <td><span>Tỉ lệ<br />Đi nghe - Phát vé</span></td>
                                <td>
                                    <div id="chart2" style="height: 200px;">

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><span>Tỉ lệ<br />Đi nghe - Đăng ký học</span></td>
                                <td>
                                    <div id="chart3" style="height: 200px;">

                                    </div>
                                </td>
                            </tr>
                        </table>
                        <table class="table table3" id="list-lich" style="margin-top: 20px;">
                            <tr>
                                <td></td>
                                <td><input class="input" /></td>
                                <td><input class="input" /></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><input class="input" /></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr style="height:50px;">
                                <th style='background: #3E606F;width:5%;'><span style="color:#FFF;">STT</span></th>
                                <th style='background: #3E606F;width:10%;'><span style="color:#FFF;">Thẻ</span></th>
                                <th style='background: #3E606F;width:20%;'><span style="color:#FFF;">Họ Tên</span></th>
                                <th style='background: #3E606F;width:12%;'><span style="color:#FFF;">Quy trình mời</span></th>
                                <th style='background: #3E606F;width:12%;'><span style="color:#FFF;">Quy trình phản hồi</span></th>
                                <th style='background: #3E606F;width:5%;'><span style="color:#FFF;">ĐK học</span></th>
                                <th style='background: #3E606F;'><span style="color:#FFF;">Người mời</span></th>
                                <th style='background: #3E606F;width: 5%;'><span style="color:#FFF;">Điểm danh</span></th>
                                <th style='background: #3E606F;width:10%;'><span style="color:#FFF;"></span></th>
                            </tr>
                            <?php
                            $stt=1;
                            $query6="SELECT h.*,s.fullname,g.name AS nhom,g.facebook AS gfb FROM hocvien_info AS h 
                            INNER JOIN game_group AS g ON g.ID_N=h.ID_N
                            INNER JOIN hocsinh AS s ON s.ID_HS=h.ID_HS
                            WHERE h.ca='$ngay'
                            ORDER BY h.status_done DESC,h.anh_done DESC,h.status DESC";
                            $result6=mysqli_query($db,$query6);
                            while($data6=mysqli_fetch_assoc($result6)) {
                                echo "<tr data-id='$data6[ID_STT]' class='tr-me back tr-fixed stt'>
                                    <td class='stt'><span>$stt</span></td>
                                    <td><span>$data6[the]</span></td>
                                    <td><span><a href='".formatFacebook($data6["facebook"])."' target='_blank' style='text-decoration: underline;'>$data6[name]</a></span></td>
                                    <td>";
                                    if($data6["anh"] && trim($data6["anh"])!="") {
                                        echo"<span style='font-weight: 600;color: #69b42e;'><a href='http://localhost/www/TDUONG/thaygiao/hoc-vien-media/$data6[ID_STT]/' target='_blank'>Có ảnh</a></span>";
                                    } else {
                                        echo"<span></span>";
                                    }
                                        echo"<br /><span>";
                                        for($i=0;$i<$data6["status"];$i++) {
                                            echo"<i class='fa fa-star status_new rate-star done-star has-star' style='font-size: 1.25em;'></i>";
                                        }
                                        for($i=0;$i<5-$data6["status"];$i++) {
                                            echo"<i class='fa fa-star status_new rate-star need-star' style='font-size: 1.25em;'></i>";
                                        }
                                    echo"</span></td>
                                    <td>";
                                    if($data6["anh_done"] && trim($data6["anh_done"])!="") {
                                        echo"<span style='font-weight: 600;color: #69b42e;'><a href='http://localhost/www/TDUONG/thaygiao/hoc-vien-media/$data6[ID_STT]/' target='_blank'>Có ảnh</a></span>";
                                    } else {
                                        echo"<span></span>";
                                    }
                                        echo"<br /><span>";
                                        for($i=0;$i<$data6["status_done"];$i++) {
                                            echo"<i class='fa fa-star status_done rate-star done-star has-star' style='font-size: 1.25em;'></i>";
                                        }
                                        for($i=0;$i<5-$data6["status_done"];$i++) {
                                            echo"<i class='fa fa-star status_done rate-star need-star' style='font-size: 1.25em;'></i>";
                                        }
                                    echo"</span></td>
                                    <td><span>";
                                    if($data6["cahoc"]) {
                                        echo"<i class='fa fa-check' style='font-size: 1.25em;'></i>";
                                    }
                                    echo"</span></td>
                                    <td><span>$data6[fullname] (<a href='".formatFacebook($data6["gfb"])."' target='_blank' style='text-decoration: underline;'>$data6[nhom]</a>)</span></td>
                                    <td><span>";
                                    if($data6["diemdanh"]==1) {
                                        echo"<i class='fa fa-check-square-o diemdanh' data-check='0' style='font-size: 1.25em;'></i>";
                                    } else if($data6["diemdanh"]==0) {
                                        echo"<i class='fa fa-square-o diemdanh' data-check='1' style='font-size: 1.25em;'></i>";
                                    }
                                    echo"</span></td>
                                    <td><a href='http://localhost/www/TDUONG/thaygiao/hoc-vien-media/$data6[ID_STT]/' target='_blank'>Xem</a></td>
                                </tr>";
                                $stt++;
                            }
                            ?>
                        </table>
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