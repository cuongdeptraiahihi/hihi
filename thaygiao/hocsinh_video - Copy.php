<?php
	ob_start();
	session_start();
    ini_set('max_execution_time', 300);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    require_once("include/googledrive/vendor/autoload.php");
    require_once("include/googledrive/examples/templates/base.php");
    if(isset($_GET["hsID"]) && is_numeric($_GET["hsID"]) && isset($_GET["lmID"]) && is_numeric($_GET["lmID"])) {
        $hsID=$_GET["hsID"];
        $lmID=$_GET["lmID"];
        $monID=get_mon_of_lop($lmID);
    } else {
        $hsID=$lmID=$monID=0;
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
    } else {
        $authUrl = $client->createAuthUrl();
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
			    $("table#list-video tr").delegate("td .unlock-file","click",function() {
                    var me = $(this);
                    var idFile = $(this).attr("data-idFile");
                    if(idFile != "") {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        $.ajax({
                            async: true,
                            data: "idShare=" + idFile + "&hsID=<?php echo $hsID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-video/",
                            success: function(result) {
                                if(result == "ok") {
                                    me.removeClass("fa-square-o").addClass("fa-check-square-o");
                                    me.removeClass("unlock-file").addClass("lock-file");
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
                    }
                });
                $("table#list-video tr").delegate("td .lock-file","click",function() {
                    var me = $(this);
                    var idFile = $(this).attr("data-idFile");
                    var perm = $(this).attr("data-perm");
                    if(idFile != "" && perm != "") {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        $.ajax({
                            async: true,
                            data: "idLock=" + idFile + "&perm=" + perm + "&hsID=<?php echo $hsID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-video/",
                            success: function(result) {
                                if(result == "ok") {
                                    me.removeClass("fa-check-square-o").addClass("fa-square-o");
                                    me.removeClass("lock-file").addClass("unlock-file");
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
                        } else {
                            alert("Email không hợp lệ!");
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
                                <table class="table" id="list-video" style="max-width: 30%;">
                                    <?php
                                        if(isset($authUrl)) {
                                            echo"<tr>
                                                <td colspan='2'><span><a class='login' href='http://localhost/www/TDUONG/thaygiao/connect_google.php' target='_blank'>Hãy kết nối Google Drive</a></span></td>
                                            </tr>";
                                        } else {
                                    ?>
                                    <tr>
                                        <td style="width:50%;"><span><strong>Đã kết nối Google Drive</strong></span></td>
                                        <td><input type="button" class="submit" value="Cập nhật quyền" id="update-role" /></td>
                                    </tr>
                                    <tr>
                                        <td style="width:50%;"><span>Email học sinh</span></td>
                                        <td><input class="input" value="<?php echo $data0["email"]; ?>" id="email-hs" /></td>
                                    </tr>
                                </table>
                                    <?php
                                        $video_arr=array();
                                        $query="SELECT * FROM options WHERE type='share-video' AND note='$hsID' ORDER BY ID_O DESC";
                                        $result=mysqli_query($db,$query);
                                        while($data=mysqli_fetch_assoc($result)) {
                                            $temp=explode("|",$data["content"]);
                                            $id=$temp[0];
                                            $permId=$temp[1];
                                            $video_arr[$id] = array(
                                                "oID" => $data["ID_O"],
                                                "datetime" => $data["note2"],
                                                "permId" => $permId
                                            );
                                        }

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
                                                echo"<table class='table' style='margin-top: 10px;margin-left: ".(10*$margin)."%;width: ".(100-10*$margin)."%;max-width: 30%;'>
                                                    <tr>
                                                        <td style='width:50%;'><span><a href='https://drive.google.com/file/d/" . $file->id . "/view?usp=sharing' target='_blank' style='text-decoration: underline;'>" . $file->name . "</a></span></td>
                                                        <td><span>".$file->createdTime."</span></td>
                                                    </tr>
                                                </table>";
                                                $folder_arr=showFolderGDrive($service, $file->id, 1);
                                                $n = count($folder_arr);
                                                for($i = 0; $i < $n; $i++) {
                                                    if($folder_arr[$i]["type"] == "folder") {
                                                        echo"<table class='table' style='margin-top: 10px;margin-left: ".(10*$folder_arr[$i]["level"])."%;width: ".(100-10*$folder_arr[$i]["level"])."%;max-width: 30%;'>
                                                            <tr>
                                                                <td style='width:50%;'><span><a href='https://drive.google.com/file/d/" . $folder_arr[$i]["id"] . "/view?usp=sharing' target='_blank' style='text-decoration: underline;'>" . $folder_arr[$i]["name"] . "</a></span></td>
                                                                <td><span>".$folder_arr[$i]["createdTime"]."</span></td>
                                                            </tr>
                                                        </table>";
                                                    } else {
                                                        echo"<table class='table' style='margin-top: 10px;margin-left: ".(10*$folder_arr[$i]["level"])."%;width: ".(100-10*$folder_arr[$i]["level"])."%;max-width: 60%;'>
                                                            <tr>
                                                                <td style='width:25%;'><span><a href='https://drive.google.com/file/d/" . $folder_arr[$i]["id"] . "/view?usp=sharing' target='_blank' style='text-decoration: underline;'>" . $folder_arr[$i]["name"] . "</a></span></td>
                                                                <td style='width:30%;'>";
                                                                    if (stripos($folder_arr[$i]["mimeType"], "video") === false) {
                                                                        echo"File";
                                                                    } else {
                                                                        echo "<iframe src='https://drive.google.com/file/d/".$folder_arr[$i]["id"]."/preview' width='200' height='150' allowfullscreen></iframe>";
                                                                    }
                                                                echo"</td>
                                                                <td style='width:20%;'><span>".$folder_arr[$i]["createdTime"]."</span></td>
                                                                <td style='width:5%;'><span>";
                                                                if(isset($video_arr[$folder_arr[$i]["id"]])) {
                                                                    echo"<i class='fa fa-check-square-o lock-file' data-idFile='".$folder_arr[$i]["id"]."' data-perm='".$video_arr[$folder_arr[$i]["id"]]["permId"]."'></i>";
                                                                    $date_open = format_datetime($video_arr[$folder_arr[$i]["id"]]["datetime"]);
                                                                } else {
                                                                    echo"<i class='fa fa-square-o unlock-file' data-idFile='".$folder_arr[$i]["id"]."'></i>";
                                                                    $date_open = "";
                                                                }
                                                                echo"</span></td>
                                                                <td><span>$date_open</span></td>
                                                            </tr>
                                                        </table>";
                                                    }
                                                }
                                                break;
                                            }
                                        } while ($pageToken != null);
                                        ?>
                                    <?php } ?>
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