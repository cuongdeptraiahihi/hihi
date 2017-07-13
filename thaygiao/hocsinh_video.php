<?php
	ob_start();
	session_start();
    ini_set('max_execution_time', 300);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    require_once("include/googledrive/vendor/autoload.php");
    require_once("include/googledrive/examples/templates/base.php");
    $start=microtime(true);
    $thu_string=array("CN","T2","T3","T4","T5","T6","T7");
    if(isset($_GET["hsID"]) && is_numeric($_GET["hsID"]) && isset($_GET["lmID"]) && is_numeric($_GET["lmID"])) {
        $hsID=$_GET["hsID"];
        $lmID=$_GET["lmID"];
    } else {
        $hsID=$lmID=0;
    }
    $mon_lop_name=get_lop_mon_name($lmID);
    $temp=explode(" ",$mon_lop_name);
    $lop = "x";
    $mon_name=array();
    $n = count($temp);
    for($i = 0; $i < $n; $i++) {
        if($i < $n-1) {
            $mon_name[] = $temp[$i];
        } else {
            $lop = $temp[$i];
        }
    }
    $mon_name=implode(" ",$mon_name);
    $result0=get_hs_short_detail($hsID,$lmID);
    $data0=mysqli_fetch_assoc($result0);

    if (!$oauth_credentials = getOAuthCredentialsFile()) {
        echo missingOAuth2CredentialsWarning();
        return;
    }

    $redirect_uri = 'http://localhost/www/TDUONG/thaygiao/connect_google.php';

    $client = new Google_Client();
    $client->setAuthConfig($oauth_credentials);
    $client->setRedirectUri($redirect_uri);
    $client->addScope("https://www.googleapis.com/auth/drive");
    $service = new Google_Service_Drive($client);

    // set the access token as part of the client
    if (!empty($_SESSION['upload_token'])) {
        $client->setAccessToken($_SESSION['upload_token']);
        if ($client->isAccessTokenExpired()) {
            unset($_SESSION['upload_token']);
        }
        unset($_SESSION["G_DRIVE_CALLBACK"]);
    } else {
        $authUrl = $client->createAuthUrl();
        $_SESSION["G_DRIVE_CALLBACK"] = "http://localhost/www/TDUONG/thaygiao/hoc-sinh-video/".$hsID."/".$lmID."/";
        header("location:".$authUrl);
        exit();
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>HỖ TRỢ VIDEO</title>
        
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
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid .status {position:relative;}.main-bot {width:100%;}.main-top {width:100%;position:relative;height:600px;}#chartContainer {width:100%;height:100%;}#chartPhu {position:absolute;z-index:9;right:0;width:400px;top:0;height:200px;overflow:hidden;border-radius:200px;}#chartContainer2 {width:100%;height:100%;}.khoang-ma,.only-ma {display:none;}#list-danhsach {background:#FFF;}
            .fa {font-size:1.375em !important;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {
			    var cur_dem = 1;
                var count_row = 0;
			    $("table#list-video tr").each(function(index, element) {
			        if(index >= 3) {
                        var dem = $(element).find("td:first-child span").text();
                        if (dem != "" && $.isNumeric(dem) && cur_dem != dem) {
                            console.log("cc: " + dem + " - " + cur_dem + " - " + count_row);
                            $("table#list-video tr.tr-dem-" + cur_dem).find("td:eq(0), td:eq(1), td:eq(3), td:eq(4), td:eq(5), td:eq(6)").attr("rowspan", count_row);
                            count_row = 1;
                            cur_dem++;
                        } else {
                            count_row++;
                        }
                        console.log(dem + " - " + cur_dem + " - " + count_row);
                    }
                });
                $("select.luyenthi").change(function() {
                    var fileID = $(this).attr("data-id");
                    var nID = $(this).find("option:selected").val();
                    if(fileID != "" && $.isNumeric(nID) && nID>=0) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        $.ajax({
                            async: true,
                            data: "fileID=" + fileID + "&nID=" + nID + "&lmID=<?php echo $lmID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-video/",
                            success: function(result) {
                                console.log(result);
                                $("#BODY").css("opacity","1");
                                $("#popup-loading").fadeOut("fast");
                            }
                        });
                    } else {
                        alert("Lỗi Dữ liệu");
                    }
                });
			    $("table#list-video tr").delegate("td .unlock-file","click",function() {
                    var me = $(this);
                    var dem = me.closest("tr").find("td:first-child span").text();
                    var idFile = $(this).attr("data-idFile");
                    var date_close = $(this).closest("tr").find("td select.date-close").find("option:selected").val();
                    var luyenthi = $(this).closest("tr").find("td select.luyenthi").find("option:selected").val();
                    var name = $(this).attr("data-name");
                    var type = $(this).attr("data-type");
                    var date_up = $(this).attr("data-dateup");
                    if(idFile != "" && $.isNumeric(date_close) && date_close > 0 && name != "" && date_up != "") {
                        var ajax_data = "[";
                        $("table#list-video tr td i.file-id-" + dem).each(function(index, element) {
                            var id = $(element).attr("data-id");
                            var type_con = $(element).attr("data-type");
                            if(id != "" && (type_con == "file" || type_con == "video")) {
                                ajax_data += '{"id":"' + id + '","type":"' + type_con + '"},';
                            }
                        });
                        ajax_data += '{"idFile":"' + idFile + '","name":"' + name + '","type":"' + type + '","date_up":"' + date_up + '","date_close":"' + date_close + '","luyenthi":"' + luyenthi + '","lmID":"<?php echo $lmID; ?>","hsID":"<?php echo $hsID; ?>"}';
                        ajax_data += "]";
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        $.ajax({
                            async: true,
                            data: "ajax_share=" + ajax_data,
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-video/",
                            success: function(result) {
                                if(result == "none") {
                                    alert("Không tồn tại email thỏa mãn hoặc ngày hết hạn không hợp lệ!");
                                } else if(result == "error") {
                                    alert("Có lỗi đã xảy ra!");
                                } else {
                                    console.log(result);
                                    var temp = result.split("|");
                                    me.attr("data-perm",temp[0]).attr("data-sttID",temp[1]);
                                    me.removeClass("fa-square-o").addClass("fa-check-square-o");
                                    me.removeClass("unlock-file").addClass("lock-file");
                                }
                                $("#BODY").css("opacity","1");
                                $("#popup-loading").fadeOut("fast");
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });
                $("table#list-video tr").delegate("td .lock-file","click",function() {
                    var me = $(this);
                    var idFile = $(this).attr("data-idFile");
                    var perm = $(this).attr("data-perm");
                    var sttID = $(this).attr("data-sttID");
                    if(idFile != "" && perm != "" && $.isNumeric(sttID) && sttID > 0) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        $.ajax({
                            async: true,
                            data: "idLock=" + idFile + "&perm=" + perm + "&sttID=" + sttID + "&hsID=<?php echo $hsID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-video/",
                            success: function(result) {
                                if(result == "ok") {
                                    me.removeClass("fa-check-square-o").addClass("fa-square-o");
                                    me.removeClass("lock-file").addClass("unlock-file");
                                    me.removeAttr("data-perm").removeAttr("data-sttID");
                                } else if(result == "none") {
                                    alert("Không tồn tại email thỏa mãn!");
                                } else if(result == "error") {
                                    alert("Có lỗi đã xảy ra!");
                                } else {
                                    alert(result);
                                }
                                $("#BODY").css("opacity","1");
                                $("#popup-loading").fadeOut("fast");
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });
                $("#update-url").click(function() {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity","0.3");
                    $.ajax({
                        async: true,
                        data: "mon_name=<?php echo $mon_name; ?>&lmID_url=<?php echo $lmID; ?>&lop=<?php echo $lop; ?>",
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-video/",
                        success: function(result) {
                            console.log(result);
                            alert("Cập nhật thành công!");
                            $("#BODY").css("opacity","1");
                            $("#popup-loading").fadeOut("fast");
                        }
                    });
                });
                $("#update-luyenthi").click(function() {
                    var ajax_data = "[";
                    $("table#list-video tr td i.folder-big").each(function(index, element) {
                        var name = $(element).attr("data-name");
                        var id = $(element).attr("data-idFile");
                        if(name != "" && id != "" && $(element).closest("tr").find("td select.luyenthi").find("option:selected").val() != 0) {
                            ajax_data += '{"name":"' + name + '","id":"' + id + '"},';
                        }
                    });
                    ajax_data += '{"lmID":"<?php echo $lmID; ?>"}';
                    ajax_data += "]";
                    if(ajax_data != "[]") {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        $.ajax({
                            async: true,
                            data: "ajax_luyenthi=" + ajax_data,
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-video/",
                            success: function(result) {
                                console.log(result);
                                alert("Cập nhật thành công!");
                                $("#BODY").css("opacity","1");
                                $("#popup-loading").fadeOut("fast");
                            }
                        });
                    }
                });
                $("#update-role").click(function() {
                    var ajax_data = "[";
                    var count = 0;
                    $("table#list-video tr td i.list-id").each(function(index, element) {
                        var id = $(element).attr("data-id");
                        if(id != "") {
                            ajax_data += '{"id":"' + id + '"},';
                            count++;
                        }
                    });
                    ajax_data += "]";
                    ajax_data = ajax_data.replace(",]","]");
                    if(ajax_data != "[]" && count != 0) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        $.ajax({
                            async: true,
                            data: "ajax_role=" + ajax_data,
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-video/",
                            success: function(result) {
                                console.log(result);
                                alert("Cập nhật thành công!");
                                $("#BODY").css("opacity","1");
                                $("#popup-loading").fadeOut("fast");
                            }
                        });
                    }
                });
                $("#email-hs").typeWatch({
                    captureLength: 5,
                    callback: function (value) {
                        if(value != "" && value.search("@gmail.com")>0) {
                            $("#popup-loading").fadeIn("fast");
                            $("#BODY").css("opacity","0.3");
                            $.ajax({
                                async: true,
                                data: "email=" + value + "&hsID_email=<?php echo $hsID; ?>",
                                type: "post",
                                url: "http://localhost/www/TDUONG/thaygiao/xuly-mon/",
                                success: function(result) {
                                    console.log("Cập nhật email thành công!");
                                    $("#BODY").css("opacity","1");
                                    $("#popup-loading").fadeOut("fast");
                                }
                            });
                        }
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
                	<h2>Hỗ trợ video <span style="font-weight:600;">mã số <?php echo $data0["cmt"]; ?>, môn <?php echo $mon_lop_name; ?></span></h2>
                	<div>
                    	<div class="status">
                            <div class="main-bot" style="display:block;">
                                <table class="table" id="list-video">
                                    <?php
                                        if(isset($authUrl)) {
                                            echo"<tr>
                                                <td colspan='8'><span><a class='login' href='$authUrl' target='_blank'>Hãy kết nối Google Drive</a></span></td>
                                            </tr>";
                                        } else {
                                    ?>
                                    <tr>
                                        <td colspan="2" style="width:50%;"><span><strong>Đã kết nối Google Drive</strong></span></td>
                                        <td colspan="6"><input type="button" class="submit" value="Cập nhật đường dẫn" id="update-url" /><input type="button" class="submit" value="Cập nhật quyền" id="update-role" /><input type="button" class="submit" value="Cập nhật trắc nghiệm" id="update-luyenthi" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="width:50%;"><span>Email học sinh</span></td>
                                        <td colspan="6"><input class="input" value="<?php echo $data0["email"]; ?>" id="email-hs" /></td>
                                    </tr>
                                    <tr style="background: #3E606F;">
                                        <th style='width:5%;'><span>STT</span></th>
                                        <th style='width:10%;'><span>Ngày</span></th>
                                        <th style='width:30%;'><span>Tên + Link</span></th>
                                        <th style='width:5%;'><span>Share</span></th>
                                        <th><span>Ngày share</span></th>
                                        <th style="width: 15%;"><span>Ngày đóng</span></th>
                                        <th><span>Luyện thi</span></th>
                                        <th style="width: 5%;"><span>Quyền</span></th>
                                    </tr>
                                    <?php
                                        $video_arr = array();
                                        $result = get_video_share_hs($hsID, "X", $lmID);
                                        while ($data = mysqli_fetch_assoc($result)) {
                                            $video_arr[$data["ID_FILE"]] = array(
                                                "sttID" => $data["ID_STT"],
                                                "permId" => $data["ID_PERM"],
                                                "date_close" => $data["date_close"],
                                                "date_open" => $data["date_open"]
                                            );
                                        }

                                        $luyenthi=array();
                                        $query="SELECT n.ID_N,d.mota FROM nhom_de AS n 
                                        INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
                                        WHERE n.ID_LM='$lmID' AND n.type='chuyen-de' ORDER BY d.mota ASC";
                                        $result=mysqli_query($db,$query);
                                        while($data=mysqli_fetch_assoc($result)) {
                                            $luyenthi[]=array(
                                                "nID" => $data["ID_N"],
                                                "mota" => $data["mota"]
                                            );
                                        }

                                        $idMon=0;
                                        $query="SELECT ID_O,content FROM options WHERE type='gdrive_url' AND note='$lmID'";
                                        $result=mysqli_query($db,$query);
                                        if(mysqli_num_rows($result) != 0) {
                                            $data = mysqli_fetch_assoc($result);
                                            $content = explode("|", $data["content"]);
                                            $idBig=0;
                                            $level=0;
                                            foreach ($content as $con) {
                                                $idBig=$con;
                                                $level++;
                                                if($level == 1) {
                                                    $idMon = $idBig;
                                                }
                                            }
                                        } else {
                                            $idBig = 0;
                                            $level = 1;
                                            $pageToken = null;
                                            do {
                                                $margin=0;
                                                $response = $service->files->listFiles(array(
                                                    "q" => "name='$mon_name'",
                                                    "spaces" => "drive",
                                                    "pageToken" => $pageToken,
                                                    "fields" => "nextPageToken, files(id, name, createdTime, mimeType)",
                                                ));
                                                foreach ($response->files as $file) {
                                                    $idBig = $file->id;
                                                    $idMon = $idBig;
                                                    $level = 1;
                                                    break;
                                                }
                                            } while ($pageToken != null);
                                        }
                                        echo"<input type='hidden' id='id-big' value='$idMon' /><input type='hidden' value='$idBig' data-level='$level' />";
                                        $folder_arr=array();
                                        if(isset($_SESSION["gdrive"]) && $_SESSION["gdrive"]) {
                                            $folder_arr = $_SESSION["gdrive"];
                                        } else {
                                            showFolderGDrive($service, $idBig, $lop, $folder_arr, $level);
											$_SESSION["gdrive"] = $folder_arr;
                                        }
                                        $n = count($folder_arr);
                                        $tempTime = "";
                                        $stt = 1;
                                        for($i = 0; $i < $n; $i++) {
                                            if($tempTime != $folder_arr[$i]["parentName"]) {
                                                $dem = $stt;
                                                $stt++;
                                                $tempTime = $folder_arr[$i]["parentName"];
                                                $temp=explode(",",$tempTime);
                                                $dateup=$temp[0];
                                                $thu = $thu_string[date("w", strtotime($dateup))];
                                                $datestr = $thu." (".format_date($dateup).")";
                                                $dateup=$temp[1];
                                                $thu = $thu_string[date("w", strtotime($dateup))];
                                                $datestr .= ", ".$thu." (".format_date($dateup).")";
                                                $me="<td><span>$dem</span></td>
                                                <td><span>$datestr</span></td>";
                                            } else {
                                                $dem = $me = "";
                                            }
                                            echo"<tr class='tr-dem-$dem'>";
                                                echo $me;
                                                echo"<td><span><a href='https://drive.google.com/file/d/" . $folder_arr[$i]["id"] . "/view?usp=sharing' target='_blank' style='text-decoration: underline;'>" . $folder_arr[$i]["name"] . "</a></span></td>";
                                                if($dem != "") {
                                                    echo"<td><span>";
                                                    if (isset($video_arr[$folder_arr[$i]["parentId"]])) {
                                                        echo "<i class='fa fa-check-square-o lock-file folder-big' data-sttID='".$video_arr[$folder_arr[$i]["parentId"]]["sttID"]."' data-type='folder' data-name='".$folder_arr[$i]["parentFolder"]."' data-dateup='$tempTime' data-idFile='" . $folder_arr[$i]["parentId"] . "' data-perm='" . $video_arr[$folder_arr[$i]["parentId"]]["permId"] . "'></i>";
                                                        $date_open = format_datetime($video_arr[$folder_arr[$i]["parentId"]]["date_open"]);
                                                        $date_close = format_dateup($video_arr[$folder_arr[$i]["parentId"]]["date_close"]);
                                                    } else {
                                                        echo "<i class='fa fa-square-o unlock-file folder-big' data-idFile='" . $folder_arr[$i]["parentId"] . "' data-type='folder' data-name='".$folder_arr[$i]["parentFolder"]."' data-dateup='$tempTime'></i>";
                                                        $date_open = "";
                                                        $date_close = "";
                                                    }
                                                    echo"</span></td>
                                                    <td><span>$date_open</span></td>
                                                    <td>";
                                                    if($dem != "") {
                                                        if($date_close != "") {
                                                            echo"<span>Hết $date_close</span>";
                                                        } else {
                                                            echo "<select class='input date-close'>";
                                                            for ($j = 1; $j <= 7; $j++) {
                                                                echo "<option value='$j'>$j ngày</option>";
                                                            }
                                                            echo "</select>";
                                                        }
                                                    }
                                                    echo"</td>";
                                                    $query="SELECT DISTINCT ID_N FROM google_drive WHERE ID_FILE='".$folder_arr[$i]["id"]."' AND ID_N!='0'";
                                                    $result=mysqli_query($db,$query);
                                                    $data=mysqli_fetch_assoc($result);
                                                    echo"<td>
                                                    <select class='input luyenthi' data-id='".$folder_arr[$i]["id"]."'>
                                                        <option value='0'>Chọn 1 đề</option>";
                                                        $dem=0;
                                                        foreach ($luyenthi as $key => $value) {
                                                            echo"<option value='$value[nID]' ";if($data["ID_N"]==$value["nID"]){echo"selected='selected'";unset($luyenthi[$dem]);}echo">$value[mota]</option>";
                                                            $dem++;
                                                        }
                                                    echo"</select>
                                                </td>";
                                                } else {
                                                    $date_open = "";
                                                    $date_close = "";
                                                }
                                                if(stripos($folder_arr[$i]["mimeType"], "video") === false) {
                                                    $type="file";
                                                } else {
                                                    $type="video";
                                                }
                                                if($folder_arr[$i]["canView"] || $folder_arr[$i]["canShare"]) {
                                                    echo"<td style='background: red;'><span><i class='list-id  file-id-".($stt-1)."' data-type='$type' data-id='".$folder_arr[$i]["id"]."'></i></span></td>";
                                                } else {
                                                    echo"<td><span><i class='file-id-".($stt-1)."' data-type='$type' data-id='".$folder_arr[$i]["id"]."'></i></span></td>";
                                                }
                                            echo"</tr>";
                                        }
                                        ?>
                                    <?php } ?>
                                    <tr style='display:none;'>
                                        <td><span>999</span></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8"><span>Truy vấn mất: <?php echo format_diem(microtime(true) - $start); ?>s</span></td>
                                    </tr>
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