<?php
    ob_start();
    session_start();
    require_once("../model/open_db.php");
    require_once("../model/model.php");
    require_once("access_admin.php");
    require_once("../model/Flick.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <title>THÊM GAME TẾT</title>

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

            $pass=$mota=$domain=NULL;
            if(isset ($_POST["OK"])) {
                if(isset($_POST["pass"]) && !empty($_POST["pass"])) {
                    $pass=md5(addslashes(trim($_POST["pass"])));
                }
                if(isset($_POST["mota"])) {
                    $mota = addslashes(trim($_POST["mota"]));
                }
                if(isset($_POST["domain"])) {
                    $domain = addslashes(trim($_POST["domain"]));
                }
                if($pass && $domain) {
                    if ($_FILES["fileToUpload"]["error"] > 0) {

                    } else {
                        if ($_FILES["fileToUpload"]["type"] != "image/jpeg" && $_FILES["fileToUpload"]["type"] != "image/png") {
                            echo "Bạn chỉ được upload ảnh dạng jpeg hoặc png!";
                        } else {
                            $query0 = "SELECT MAX(level) AS max FROM game_level";
                            $result0 = mysqli_query($db,$query0);
                            $data0 = mysqli_fetch_assoc($result0);
                            $level = $data0["max"] + 1;
                            $query0 = "INSERT INTO game_level(level,pass,anh,mota,domain,status)
                                                        VALUE('$level','$pass','','$mota','$domain','1')";
                            mysqli_query($db,$query0);
                            $id=mysqli_insert_id($db);
                            $filename = $_FILES["fileToUpload"]["name"];
                            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "images/" . $filename);
                            $f = new Flick("bc196e763e5476b218d50fc79fd7f278", "c73a724daf1b18fa", "http://localhost/www/TDUONG/game/callback.php");
                            $respones = $f->upload("images/" . $filename, "anh-game-vong-$level", "game-vong$level");
                            if ($respones["stat"] == "ok") {
                                $photoid = $respones["photoid"]["_content"];
                                $query = "UPDATE game_level SET anh='" . $photoid . "' WHERE ID_STT='$id'";
                                $result = mysqli_query($db, $query);
                            }
                            header('location: http://localhost/www/TDUONG/thaygiao/game-tet/');
                            exit();
                        }
                    }
                }
            }
        ?>

        <div id="BODY">

            <?php require_once("include/TOP.php"); ?>

            <div id="MAIN">

                <div id="main-mid">
                    <h2>Thêm vòng mới</h2>
                    <div>
                        <div class="status">
                            <form action='http://localhost/www/TDUONG/thaygiao/them-game-tet/' method='post' enctype='multipart/form-data'>
                                <table class="table">
                                    <tr>
                                        <td style="width: 20%;"><span>Mật khẩu </span></td>
                                        <td><input type='text' class="input" name='pass' placeholder='Nhập mk muốn thay'/></td>
                                    </tr>
                                    <tr>
                                        <td><span>Ảnh</span></td>
                                        <td><input class='image input' type='file' name='fileToUpload'/></td>
                                    </tr>
                                    <tr>
                                        <td><span>Tên miền</span></td>
                                        <td><input class='input' type='text' name='domain' /></td>
                                    </tr>
                                    <tr>
                                        <td><span>Mô tả</span></td>
                                        <td><input class='input' type='text' name='mota' /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button class='submit' id='popup-view' name='OK' type='submit' value='Upload'>Thêm</button></td>
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