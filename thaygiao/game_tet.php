<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>GAME TẾT</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.fa {font-size:1.375em !important;}
            .tDnD_whileDrag {
                background: #69b42e;
                opacity: 1 !important;
            }
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script type="text/javascript" src="http://localhost/www/TDUONG/thaygiao/js/jquery.tablednd.js"></script>
        <script type="text/javascript">
		$(document).ready(function() {
            $("#list-game").tableDnD({
                onDragStart: function (table, row) {
                    $(table).find("tr").css("opacity", 0.5);
                },
                onDragStop: function (table, row) {
                    update_order();
                }
            });
            function update_order() {
                $("#list-game").hide();
                $("#popup-loading").fadeIn("fast");
                $("#BODY").css("opacity","0.3");
                var stt = 1;
                var ajax_data = "[";
                $("#list-game tr").each(function(index, element) {
                    if(!$(element).hasClass("nodrag nodrop")) {
                        ajax_data += '{"id":"' + $(element).attr("data-id") + '","stt":"' + stt + '"},';
                        stt++;
                    }
                });
                ajax_data += "]";
                ajax_data = ajax_data.replace(",]","]");
                if(ajax_data != "[]") {
                    $.ajax({
                        async: true,
                        data: "table_order=" + ajax_data,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-mon/",
                        success: function(result) {
                            location.reload();
                        }
                    });
                }
            }
            $(".tai-anh").click(function () {
                var me = $(this);
                var anh = $(this).attr("data-anh");
                if(anh != "" && $.isNumeric(anh) && anh > 0) {
                    me.val("Đang load...");
                    $.ajax({
                        async: true,
                        data: "anh_flickr=" + anh,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-mon/",
                        success: function(result) {
                            me.closest("td").html(result);
                            me.hide();
                        }
                    });
                } else {
                    alert("Lỗi dữ liệu!");
                }
            });
            $("table#list-game tr").delegate("td input.hide","click",function() {
                var me = $(this);
                var id = $(this).attr("data-id");
                if($.isNumeric(id) && id > 0) {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity","0.3");
                    $.ajax({
                        async: true,
                        data: "vong_hide=" + id,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-mon/",
                        success: function(result) {
                            me.closest("tr").find("td:eq(5)").html("Ẩn");
                            me.val("Hiện").removeClass("hide").addClass("show");
                            $("#BODY").css("opacity","1");
                            $("#popup-loading").fadeOut("fast");
                        }
                    });
                } else {
                    alert("Lỗi dữ liệu!");
                }
            });
            $("table#list-game tr").delegate("td input.show","click",function() {
                var me = $(this);
                var id = $(this).attr("data-id");
                if($.isNumeric(id) && id > 0) {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity","0.3");
                    $.ajax({
                        async: true,
                        data: "vong_show=" + id,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-mon/",
                        success: function(result) {
                            me.closest("tr").find("td:eq(5)").html("Hoạt động");
                            me.val("Ẩn").removeClass("show").addClass("hide");
                            $("#BODY").css("opacity","1");
                            $("#popup-loading").fadeOut("fast");
                        }
                    });
                } else {
                    alert("Lỗi dữ liệu!");
                }
            });
            $("table#list-game tr").delegate("td input.delete","click",function() {
                var me = $(this);
                var id = $(this).attr("data-id");
                if($.isNumeric(id) && id > 0) {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity","0.3");
                    $.ajax({
                        async: true,
                        data: "vong_delete=" + id,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-mon/",
                        success: function(result) {
                            me.closest("tr").remove();
                            $("#BODY").css("opacity","1");
                            $("#popup-loading").fadeOut("fast");
                        }
                    });
                } else {
                    alert("Lỗi dữ liệu!");
                }
            });
            $("#reset").click(function () {
                if(confirm("Bạn có chắc chắn không?")) {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity","0.3");
                    $.ajax({
                        async: true,
                        data: "vong_reset=1",
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-mon/",
                        success: function(result) {
                            location.reload();
                        }
                    });
                }
            });
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>Các vòng chơi</h2>
                	<div>
                    	<div class="status">	
                            <table class="table" id="list-game">
                                <tr style="background:#3E606F;" class="nodrag nodrop">
                                    <th style="width:5%;"><span>Vòng</span></th>
                                    <th style="width:15%;"><span>Tên miền</span></th>
                                    <th class="hidden"><span>Mô tả</span></th>
                                    <th class="hidden" style="width:20%;"><span>Ảnh</span></th>
                                    <th class="hidden" style="width:10%;"><span>Hoàn thành</span></th>
                                    <th class="hidden"><span>Trạng thái</span></th>
                                    <th style="width:20%;"></th>
                                </tr>
                                <?php
                                    $total = count_game_group(0);
                                    $vong_arr = array();
                                    $query = "SELECT g.ID_STT,g.level,g.anh,g.mota,g.domain,g.status,COUNT(u.ID_STT) AS dem FROM game_level AS g
                                    LEFT JOIN game_unlock AS u ON u.level=g.level
                                    GROUP BY g.level
                                    ORDER BY g.level ASC";
                                    $result = mysqli_query($db, $query);
                                    while($data=mysqli_fetch_assoc($result)) {
                                        if($data["status"]==1) {
                                            $vong_arr[] = array(
                                                "level" => $data["level"],
                                                "anh" => $data["anh"],
                                                "mota" => $data["mota"],
                                                "domain" => $data["domain"],
                                                "status" => $data["status"]
                                            );
                                            echo"<tr data-id='$data[ID_STT]'>";
                                        } else {
                                            echo"<tr class='nodrag nodrop'>";
                                        }
                                        echo"<td><span>$data[level]</span></td>
                                            <td style='background: #EF5350;'><a style='color: #FFF;' href='http://localhost/www/TDUONG/game/$data[domain]/' target='_blank'>$data[domain]</a></td>
                                            <td class='hidden'><span>$data[mota]</span></td>
                                            <td class='hidden'><input type='button' class='submit tai-anh' data-anh='$data[anh]' value='Tải ảnh' /></td>
                                            <td class='hidden'><span>$data[dem] / $total</span></td>";
                                            if($data["status"]==1) {
                                                echo"<td class='hidden'><span>Hoạt động</span></td>";
                                                echo"<td><input type='button' class='submit' value='Sửa' onclick=\"location.href='http://localhost/www/TDUONG/thaygiao/sua-game-tet/$data[ID_STT]/'\" />";
                                                echo"<input type='button' class='submit hide' data-id='$data[ID_STT]' value='Ẩn' />";
                                            } else {
                                                echo"<td class='hidden'><span>Ẩn</span></td>";
                                                echo"<td><input type='button' class='submit' value='Sửa' onclick=\"location.href='http://localhost/www/TDUONG/thaygiao/sua-game-tet/$data[ID_STT]/'\" />";
                                                echo"<input type='button' class='submit show' data-id='$data[ID_STT]' value='Hiện' />";
                                            }
                                            if($data["level"]!=0) {
                                                echo "<input type='button' class='submit delete' data-id='$data[ID_STT]' value='Xóa' /></td>";
                                            }
                                        echo"</tr>";
                                    }
                                ?>
                            </table>
                            <table class="table" style="margin-top: 25px;min-width: 600px;">
                                <tr>
                                    <td colspan="50">
                                        <input type="button" class="submit" value="Thêm vòng" onclick="location.href='http://localhost/www/TDUONG/thaygiao/them-game-tet/'" />
                                        <input type="button" class="submit" value="Reset" id="reset" />
                                    </td>
                                </tr>
                                <tr style="background:#3E606F;">
                                    <th class="hidden" style="width:5%;"><span>STT</span></th>
                                    <th style="width:15%;"><span>Tên đội</span></th>
                                    <th style="width:10%;"><span>SĐT</span></th>
                                    <?php
                                        $n = count($vong_arr);
                                        for($i = 0; $i < $n; $i++) {
                                            if($vong_arr[$i]["level"] != 0) {
                                                echo "<th style='width: 100px;'><span>Chặng " . $vong_arr[$i]["level"] . "</span></th>";
                                            }
                                        }
                                    ?>
                                </tr>
                                <?php
                                    $count_arr = array();
                                    $query = "SELECT ID_N,level,datetime FROM game_unlock ORDER BY ID_N ASC";
                                    $result = mysqli_query($db, $query);
                                    while($data=mysqli_fetch_assoc($result)) {
                                        $count_arr[$data["ID_N"]."-".$data["level"]] = 1;
                                    }
                                    $stt=1;
                                    $query = "SELECT g.ID_N,g.name,h.sdt,COUNT(u.ID_STT) AS dem FROM game_group AS g
                                    INNER JOIN hocsinh AS h ON h.ID_HS=g.ID_HS
                                    LEFT JOIN game_unlock AS u ON u.ID_N=g.ID_N
                                    GROUP BY g.ID_N
                                    ORDER BY dem DESC,g.name ASC";
                                    $result = mysqli_query($db, $query);
                                    while($data=mysqli_fetch_assoc($result)) {
                                        echo"<tr>
                                            <td class='hidden'><span>$stt</span></td>
                                            <td><span>$data[name]</span></td>
                                            <td><span>".format_phone($data["sdt"])."</span></td>";
                                            for($i = 0; $i < $n; $i++) {
                                                if($vong_arr[$i]["level"] != 0) {
                                                    if (isset($count_arr[$data["ID_N"] . "-" . $vong_arr[$i]["level"]])) {
                                                        echo "<td style='background: #69b42e;'><span></span></td>";
                                                    } else {
                                                        echo "<td><span></span></td>";
                                                    }
                                                }
                                            }
                                        echo"</tr>";
                                        $stt++;
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