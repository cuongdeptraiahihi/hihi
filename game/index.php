<?php include("include/start-game.php"); ?>
<?php include("../model/Flick.php"); ?>
<?php
    $check_n = false;
    global $nID, $hsID;
    $nID = 0;
    $hsID = addslashes($_SESSION["ID_HS"]);
    $query2="SELECT ID_N FROM list_group WHERE ID_HS='$hsID'";
    $result2=mysqli_query($db,$query2);
    if(mysqli_num_rows($result2) != 0) {
        $data2 = mysqli_fetch_assoc($result2);
        $nID = $data2["ID_N"];
        $check_n = true;
    }

    $url = $_SERVER["REQUEST_URI"];
    $temp = explode("/", $url);
    $domain = addslashes(trim($temp[count($temp)-2]));
    if(ctype_alpha($domain)) {
        $query0 = "SELECT level FROM game_level WHERE domain='$domain'";
        $result0 = mysqli_query($db, $query0);
        if(mysqli_num_rows($result0) != 0) {
            $data0 = mysqli_fetch_assoc($result0);
            $vong = $data0["level"];
        } else {
            $query0 = "SELECT domain,level FROM game_level WHERE status='1' ORDER BY level ASC LIMIT 1";
            $result0 = mysqli_query($db, $query0);
            $data0 = mysqli_fetch_assoc($result0);
            header("location:http://localhost/www/TDUONG/game/$data0[domain]/");
            exit();
        }
    } else {
        $query0 = "SELECT domain,level FROM game_level WHERE status='1' ORDER BY level ASC LIMIT 1";
        $result0 = mysqli_query($db, $query0);
        $data0 = mysqli_fetch_assoc($result0);
        header("location:http://localhost/www/TDUONG/game/$data0[domain]/");
        exit();
    }
    $vong = addslashes($vong);

    $check=false;
    $error="";
    $result="";
    $passencode = NULL;
    if(isset ($_POST["submit"]) && is_numeric($vong)) {
        $passencode=md5(trim(addslashes($_POST["pass"])));
        if($passencode) {
            $query1= "SELECT pass,anh,mota FROM game_level WHERE level='$vong' AND pass='$passencode'";
            $result1=mysqli_query($db,$query1);
            if(mysqli_num_rows($result1) != 0) {
                add_game_unlock($nID ,$hsID, $vong);
                $data1=mysqli_fetch_assoc($result1);
                $check=true;
                $result_data = "";

                $f = new Flick("bc196e763e5476b218d50fc79fd7f278", "c73a724daf1b18fa", "http://localhost/www/TDUONG/game/callback.php");
                $photo_respones = $f->request("flickr.photos.getSizes", array("photo_id" => $data1["anh"]));
                $photo_respones = json_decode($photo_respones, true);
                if(isset($photo_respones["stat"]) && $photo_respones["stat"] == "ok") {
                    $photo = $photo_respones["sizes"]["size"];
                    $m = count($photo);
                    for($j = 0; $j < $m; $j++) {
                        if($photo[$j]["label"] == "Original") {
                            $result_data .= "<img style='width:100%;' src='".$photo[$j]["source"]."' />";
                            break;
                        }
                    }
                }

                $result="<div style='width:100%;max-width: 700px;margin: 20px auto;'><p style='font-size:18px;line-height: 22px;text-align: center;margin-top: 20px;'>$data1[mota]</p><br />".$result_data."</div>";
            } else {
                add_log($hsID, "Mở khóa thất bại với mật khẩu $passencode tại chặng $vong", "game-tet-0");
                $error="Mật khẩu chưa chính xác! =))) GG";
            }
        } else {
            $error="Vui lòng nhập mật khẩu!";
        }
    }
?>
    <body>
    <?php include("include/body-game.php"); ?>
    <?php
        if($check_n) {
            if (!$check) { ?>
                <?php
                if ($vong == 0) {
                    $str = "Demo";
                    echo "<h2>Demo</h2><p><span>Mật khẩu là 123456</span></p><br />";
                } else {
                    $str = "Chặng $vong";
                    echo "<h2>Chặng $vong</h2>";
                }
                ?>
                <!--        <p><span>@ --><?php //echo date("Y"); ?><!-- Bgo Education</span></p>-->
                <form action="http://localhost/www/TDUONG/game/<?php echo $domain; ?>/" method="post">
                    <table>
                        <tr>
                            <td><input type="password" autocomplete="off" name="pass"
                                       placeholder="Mật khẩu <?php echo $str; ?>"/></td>
                        </tr>
                        <?php if ($error != "") { ?>
                            <tr>
                                <td style="color: red;font-size: 14px;font-weight: 600;">
                                    <?php echo $error; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td>
                                <button name="submit" style="padding: 10px;">OK</button>
                            </td>
                        </tr>
                    </table>
                </form>
            <?php } else {
                echo $result;
            }
        } else {
            echo"Bạn không thuộc nhóm nào cả!";
        }
        ?>

    <?php
//    $f = new Flick("bc196e763e5476b218d50fc79fd7f278", "c73a724daf1b18fa", "http://localhost/www/TDUONG/game/callback.php");
//    $respones = $f->request("flickr.photos.search", array("user_id" => true, "tags" => "game-vong".$vong));
//    //                echo $respones."<br />";
//    $respones = json_decode($respones, true);
//    if($respones["stat"] == "ok") {
//        $photos = $respones["photos"]["photo"];
//        $n = count($photos);
//        for($i = 0; $i < $n; $i++) {
//            $photo_respones = $f->request("flickr.photos.getSizes", array("photo_id" => $photos[$i]["id"]));
////                        echo $photo_respones."<br />";
//            $photo_respones = json_decode($photo_respones, true);
//            if($photo_respones["stat"] == "ok") {
//                $photo = $photo_respones["sizes"]["size"];
//                $m = count($photo);
//                for($j = 0; $j < $m; $j++) {
//                    if($photo[$j]["label"] == "Large") {
//                        $result_data .= "<img style='width:100%:' src='".$photo[$j]["source"]."' />";
//                        break;
//                    }
//                }
//            }
//        }
//    }
//        require_once "../model/Flick.php";
//        $f = new Flick("bc196e763e5476b218d50fc79fd7f278", "c73a724daf1b18fa", "http://localhost/www/TDUONG/game/callback.php");
//        $respone = $f->signRequest();
//
//        $_SESSION["oauth_token"] = $respone["oauth_token"];
//        $_SESSION["oauth_token_secret"] = $respone["oauth_token_secret"];
//
//        $oauth_token = $respone["oauth_token"];
//        $f->auth("delete", $oauth_token);
    ?>



    </body>
<?php include("include/end-game.php"); ?>