<?php
ob_start();
session_start();
require_once("../model/open_db.php");
require_once("../model/model.php");
require_once("access_admin.php");
if(isset($_GET["nID"]) && is_numeric($_GET["nID"])) {
    $nID=$_GET["nID"];
} else {
    $nID=0;
}
$lmID=$_SESSION["lmID"];
$lop_mon_name=get_lop_mon_name($lmID);
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
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/admin/css/mbocuc.css'>";
        } else {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/admin/css/bocuc.css'>";
        }
        ?>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/jquery-ui.css">

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
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.js"></script>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.multidatespicker.js"></script>
        <script src="http://localhost/www/TDUONG/admin/js/iscroll.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                $("button.del").click(function() {
                    var me=$(this).closest("tr");
                    var id = me.attr("data-id");
                    if(confirm("Bạn có chắc chắn không?")) {
                        if ($.isNumeric(id) && id != 0) {
                            $.ajax({
                                async: true,
                                data: "id2=" + id,
                                type: "post",
                                url: "http://localhost/www/TDUONG/admin/xuly-game/",
                                success: function (result) {
                                    me.fadeOut("fast").removeClass("stt");
                                    dem();
                                }
                            });
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
                            url: "http://localhost/www/TDUONG/admin/xuly-game/",
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
                            url: "http://localhost/www/TDUONG/admin/xuly-game/",
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
                            url: "http://localhost/www/TDUONG/admin/xuly-game/",
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
                function dem() {
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


    <div id="BODY">

        <?php require_once("include/TOP.php"); ?>

        <div id="MAIN">

            <div id="main-mid">

                <h2>Danh sách thành viên nhóm</h2>
                <div>
                    <div class="status" style="position: relative;">
                        <table class="table table3" id="list-lich">
                            <tr>
                                <td style='background: #3E606F;width:10%;'><span style="color:#FFF;">STT</span></td>
                                <td style='background: #3E606F;'><span style="color:#FFF;">Tên</span></td>
                                <td style='background: #3E606F;width:10%;'><span style="color:#FFF;">Mã số</span></td>
                                <td style='background: #3E606F;width:15%;'><span style="color:#FFF;">SĐT</span></td>
                                <td style='background: #3E606F;width:10%;'><span style="color:#FFF;">Facebook</span></td>
                                <td style='background: #3E606F;width:10%;'><span style="color:#FFF;"></span></td>
                            </tr>
                            <?php
                            $stt=1;
                            $query5="SELECT h.ID_HS,h.cmt,h.fullname,h.sdt,h.facebook,l.level FROM list_group AS l
                            INNER JOIN hocsinh AS h ON h.ID_HS=l.ID_HS
                            WHERE l.ID_N='$nID'
                            ORDER BY l.level ASC";
                            $result5=mysqli_query($db,$query5);
                            while($data5=mysqli_fetch_assoc($result5)) {
                                $facebook=formatFacebook($data5["facebook"]);
                                if($facebook=="#")
                                    $show="";
                                else $show="Xem";
                                echo "<tr class='tr-me back tr-fixed'>
                                    <td><span>$stt</span></td>
                                    <td><span>$data5[fullname]</span></td>
                                    <td><span>$data5[cmt]</span></td>
                                    <td><span>$data5[sdt]</span></td>
                                    <td><a href='$facebook' style='color:black;text-decoration: underline;' target='_blank'>$show</a></td>";
                                    if($data5["level"]==1) {
                                        echo"<td><span>Trưởng nhóm</span></td>";
                                    } else {
                                        echo"<td><span></span></td>";
                                    }
                                echo"</tr>";
                                $stt++;
                            }

                            ?>

                        </table>
                    </div>

                </div>

                <h2>Danh sách người nhận vé</h2>
                <div>
                    <div class="status" style="position: relative;">
                        <?php
                            $query1="SELECT content FROM options WHERE type='buoi-phat-ve' ORDER BY content ASC";
                            $result1=mysqli_query($db,$query1);
                            while($data1=mysqli_fetch_assoc($result1)) {
                        ?>
                        <table class="table table3" id="list-lich" style="margin-top: 20px;">
                            <tr>
                                <td colspan="9"><span><strong><?php echo format_dateup($data1["content"]); ?></strong></span></td>
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
                            WHERE h.ID_N='$nID' AND h.ca='$data1[content]'
                            ORDER BY h.status_done DESC,h.anh_done DESC,h.status DESC";
                            $result6=mysqli_query($db,$query6);
                            while($data6=mysqli_fetch_assoc($result6)) {
                                echo "<tr data-id='$data6[ID_STT]' class='tr-me back tr-fixed stt'>
                                    <td class='stt'><span>$stt</span></td>
                                    <td><span>$data6[the]</span></td>
                                    <td><span><a href='".formatFacebook($data6["facebook"])."' target='_blank' style='text-decoration: underline;'>$data6[name]</a></span></td>
                                    <td>";
                                if($data6["anh"] && trim($data6["anh"])!="") {
                                    echo"<span style='font-weight: 600;color: #69b42e;'><a href='http://localhost/www/TDUONG/admin/hoc-vien-media/$data6[ID_STT]/' target='_blank'>Có ảnh</a></span>";
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
                                    echo"<span style='font-weight: 600;color: #69b42e;'><a href='http://localhost/www/TDUONG/admin/hoc-vien-media/$data6[ID_STT]/' target='_blank'>Có ảnh</a></span>";
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
                                    <td><a href='http://localhost/www/TDUONG/admin/hoc-vien-media/$data6[ID_STT]/' target='_blank'>Xem</a></td>
                                </tr>";
                                $stt++;
                            }
                            ?>

                        </table>
                        <?php } ?>
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