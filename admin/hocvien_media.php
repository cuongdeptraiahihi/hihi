<?php
ob_start();
session_start();
require_once("../model/open_db.php");
require_once("../model/model.php");
require_once("access_admin.php");
if(isset($_GET["sttID"]) && is_numeric($_GET["sttID"])) {
    $sttID=$_GET["sttID"];
} else {
    $sttID=0;
}
$lmID=$_SESSION["lmID"];

$query6="SELECT h.*,t.name AS truong FROM hocvien_info AS h INNER JOIN truong AS t ON t.ID_T=h.school WHERE h.ID_STT='$sttID'";
$result6=mysqli_query($db,$query6);
$data6=mysqli_fetch_assoc($result6);
?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <title>ẢNH VÀ VIDEO</title>

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
            /*.img-zoom {zoom: 150%;}*/
            .img-zoom {width: 80% !important;max-width: none !important;}
        </style>

        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.js"></script>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.multidatespicker.js"></script>
        <script src="http://localhost/www/TDUONG/admin/js/iscroll.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
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

                $("#add-note").typeWatch({
                    captureLength: 1,
                    callback: function (value) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        var note = value.replace(/\+/g,"-").replace(/(?:\r\n|\r|\n)/g, "<br />");
                        $.ajax({
                            async: true,
                            data: "moi_note=" + note + "&moi_id=<?php echo $sttID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-game/",
                            success: function(result) {
                                console.log("OK");
                                $("#BODY").css("opacity","1");
                                $("#popup-loading").fadeOut("fast");
                            }
                        });
                    }
                });

                $("#add-thongbao-ok").click(function () {
                    var text = $("#add-thongbao").val().trim();
                    if(confirm("Bạn có chắc chắn gửi thông báo đến bạn học sinh đã thêm bạn này?")) {
                        if(text != "") {
                            $("#popup-loading").fadeIn("fast");
                            $("#BODY").css("opacity","0.3");
                            text = text.replace(/\+/g,"-").replace(/(?:\r\n|\r|\n)/g, "<br />");
                            $.ajax({
                                async: true,
                                data: "text=" + text + "&hsid=<?php echo $data6["ID_HS"]; ?>&sttID=<?php echo $data6["ID_STT"]; ?>",
                                type: "post",
                                url: "http://localhost/www/TDUONG/admin/xuly-game/",
                                success: function(result) {
                                    $("#add-thongbao-ok").html("Đã gửi");
                                    $("#BODY").css("opacity","1");
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

                $("img.img-hs").click(function () {
                    $(this).toggleClass("img-zoom");
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

                <h2>Ảnh và Video</h2>
                <div>
                    <div class="status" style="position: relative;">
                        <table class="table table3" id="list-lich">
                            <?php
                                echo"<tr style='background:#3E606F;'>
                                    <th colspan='5'><span>Thông tin</span></th>
                                </tr>
                                <tr>
                                    <td style='width: 15%;'><span>$data6[name]</span></td>
                                    <td><span>".str_replace("-"," lên ",$data6["class"])."</span></td>
                                    <td><span>$data6[truong]</span></td>
                                    <td><span>$data6[sdt]</span></td>
                                    <td><span>$data6[sdt_phuhuynh]</span></td>
                                </tr>
                                <tr>
                                    <td><span>Ghi chú</span></td>
                                    <td colspan='2'><textarea style='resize:none;box-sizing: border-box;overflow: hidden;' placeholder='Tự động lưu' rows='3' class='input' id='add-note'>".str_replace("<br />","\n",$data6["note"])."</textarea></td>
                                    <th colspan='2'>
                                        <textarea style='resize:none;box-sizing: border-box;overflow: hidden;width:87%;float:left;' placeholder='Thông báo cho nhóm trưởng' rows='2' class='input' id='add-thongbao'></textarea>
                                        <button class='submit' type='button' style='float:left;width:10%;' id='add-thongbao-ok'>Gửi</button>
                                    </th>
                                </tr>
                                <tr style='background:#3E606F;' data-id='$data6[ID_STT]'>
                                    <th><span>Quá trình mời</span></th>
                                    <th><span>";
                                    for($i=0;$i<$data6["status"];$i++) {
                                        echo"<i class='fa fa-star status_new rate-star done-star has-star' style='font-size: 1.25em;'></i>";
                                    }
                                    for($i=0;$i<5-$data6["status"];$i++) {
                                        echo"<i class='fa fa-star status_new rate-star need-star' style='font-size: 1.25em;'></i>";
                                    }
                                    echo"</span></th>
                                </tr>";
                                if($data6["anh"]) {
                                    echo"<tr>
                                        <td colspan='5'>";
                                        $temp=explode("|", $data6["anh"]);
                                        for($i = 0; $i < count($temp) ; $i++) {
                                            echo"<img class='img-hs' src='https://localhost/www/TDUONG/hocsinh/game/$data6[ID_N]/$temp[$i]' style='max-width: 350px;margin: 5px;width: 100%' />";
                                        }
                                        echo"</td>
                                    </tr>";
                                }
                                echo"<tr style='background:#3E606F;' data-id='$data6[ID_STT]'>
                                    <th><span>Phản hồi</span></th>
                                    <th><span>";
                                    for($i=0;$i<$data6["status_done"];$i++) {
                                        echo"<i class='fa fa-star status_done rate-star done-star has-star' style='font-size: 1.25em;'></i>";
                                    }
                                    for($i=0;$i<5-$data6["status_done"];$i++) {
                                        echo"<i class='fa fa-star status_done rate-star need-star' style='font-size: 1.25em;'></i>";
                                    }
                                    $ca_str = "";
                                    $temp=explode("|", $data6["cahoc"]);
                                    if(count($temp)==3) {
                                        for ($i = 0; $i < count($temp); $i++) {
                                            $temp2 = explode("-", $temp[$i]);
                                            $ca_str .= " | " . "Thứ " . $temp2[0] . ", " . $temp2[1] . " - " . $temp2[2];
                                        }
                                    }
                                    $ca_str=trim($ca_str, " | ");
                                    echo"</span></th>
                                    <th colspan='3'><span>Lịch học thử: $ca_str</span></th>
                                </tr>";
                                if($data6["anh_done"]) {
                                    echo"<tr>
                                        <td colspan='5'>";
                                        $temp=explode("|", $data6["anh_done"]);
                                        for($i = 0; $i < count($temp) ; $i++) {
                                            echo"<img class='img-hs' src='https://localhost/www/TDUONG/hocsinh/game/done/$data6[ID_N]/$temp[$i]' style='max-width: 350px;margin: 5px;width: 100%' />";
                                        }
                                        echo"</td>
                                    </tr>";
                                }
                            ?>
                            <tr></tr>
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