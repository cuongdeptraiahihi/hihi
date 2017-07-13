<?php
    ob_start();
    session_start();
    require_once("../model/open_db.php");
    require_once("../model/model.php");
    require_once("access_admin.php");
    require_once("../model/Flick.php");

    if(isset($_GET["id"]) && is_numeric($_GET["id"])) {
        $id = $_GET["id"];
    } else {
        $id = 0;
    }
    $query0 = "SELECT * FROM game_level WHERE ID_STT='$id'";
    $result0 = mysqli_query($db, $query0);
    $data0=mysqli_fetch_assoc($result0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <title>SỬA GAME TẾT</title>

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
        </style>

        <?php require_once("include/SCRIPT.php"); ?>
        <script>
            $(document).ready(function() {
            });
        </script>

    </head>

    <body>

        <div class="popup" id="popup-loading">
            <p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
        </div>

        <?php
            $result_data = "";
            $f = new Flick("bc196e763e5476b218d50fc79fd7f278", "c73a724daf1b18fa", "http://localhost/www/TDUONG/game/callback.php");
            $photo_respones = $f->request("flickr.photos.getSizes", array("photo_id" => $data0["anh"]));
            $photo_respones = json_decode($photo_respones, true);
            if(isset($photo_respones["stat"]) && $photo_respones["stat"] == "ok") {
                $photo = $photo_respones["sizes"]["size"];
                $m = count($photo);
                for($j = 0; $j < $m; $j++) {
                    if($photo[$j]["label"] == "Original") {
                        $result_data = "<img style='width:100%;max-width:250px;' src='".$photo[$j]["source"]."' />";
                        break;
                    }
                }
            }

            $pass=$mota=$domain=NULL;
            if(isset ($_POST["OK"])) {
                if(isset($_POST["pass"]) && !empty($_POST["pass"])) {
                    $pass=md5(addslashes(trim($_POST["pass"])));
                } else {
                    $pass=$data0["pass"];
                }
                if(isset($_POST["mota"])) {
                    $mota = addslashes(trim($_POST["mota"]));
                }
                if(isset($_POST["domain"])) {
                    $domain = addslashes(trim($_POST["domain"]));
                }
                if($pass && $domain) {
                    if ($_FILES["fileToUpload"]["error"] > 0) {
                        $query = "UPDATE game_level SET pass='$pass',mota='$mota',domain='$domain' WHERE ID_STT='$data0[ID_STT]'";
                        $result = mysqli_query($db, $query);
                    } else {
                        if ($_FILES["fileToUpload"]["type"] != "image/jpeg" && $_FILES["fileToUpload"]["type"] != "image/png") {
                            echo "Bạn chỉ được upload ảnh dạng jpeg hoặc png!";
                        } else {
                            $filename = $_FILES["fileToUpload"]["name"];
                            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "images/" . $filename);
                            $respones = $f->upload("images/" . $filename, "anh-game-vong-$data0[level]", "game-vong$data0[level]");
                            if ($respones["stat"] == "ok") {
                                $photoid = $respones["photoid"]["_content"];
                                $query = "UPDATE game_level SET pass='$pass',anh='" . $photoid . "',mota='$mota',domain='$domain' WHERE ID_STT='$data0[ID_STT]'";
                                $result = mysqli_query($db, $query);
                            }
                        }
                    }
                    header('location: http://localhost/www/TDUONG/thaygiao/game-tet/');
                    exit();
                }
            }
        ?>

        <div id="BODY">

            <?php require_once("include/TOP.php"); ?>

            <div id="MAIN">

                <div id="main-mid">
                    <h2>Sửa vòng <strong><?php echo $data0["domain"]; ?></strong></h2>
                    <div>
                        <div class="status">
                            <form action='http://localhost/www/TDUONG/thaygiao/sua-game-tet/<?php echo $data0["ID_STT"]; ?>/' method='post' enctype='multipart/form-data'>
                                <table class="table">
                                    <tr>
                                        <td style="width: 20%;"><span>Mật khẩu (nếu muốn thay)</span></td>
                                        <td><input type='text' class="input" name='pass' placeholder='Nhập mk muốn thay'/></td>
                                    </tr>
                                    <tr>
                                        <td><span>Ảnh</span></td>
                                        <td><?php echo $result_data; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><input class='image input' type='file' name='fileToUpload'/></td>
                                    </tr>
                                    <tr>
                                        <td><span>Tên miền</span></td>
                                        <td><input class='input' type='text' name='domain' value='<?php echo $data0["domain"]; ?>'/></td>
                                    </tr>
                                    <tr>
                                        <td><span>Mô tả</span></td>
                                        <td><input class='input' type='text' name='mota' value='<?php echo $data0["mota"]; ?>'/></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button class='submit' id='popup-view' name='OK' type='submit' value='Upload'>OK</button></td>
                                    </tr>
                                </table>
                            </form>
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