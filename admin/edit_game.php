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
    <title>GAME</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
    <table border="2" width="100%">
       <tr>
           <td>Vòng</td>
           <td>Tên miền</td>
           <td>Mô tả</td>
           <td>Ảnh</td>
           <td></td>
       </tr>
        <?php
        $i=1;
        $query = "SELECT ID_STT,level,anh,mota,domain FROM game_level";
        $result = mysqli_query($db, $query);
        while($data=mysqli_fetch_assoc($result)) {

            $result_data = "";
            $f = new Flick("bc196e763e5476b218d50fc79fd7f278", "c73a724daf1b18fa", "http://localhost/www/TDUONG/game/callback.php");
            $photo_respones = $f->request("flickr.photos.getSizes", array("photo_id" => $data["anh"]));
            $photo_respones = json_decode($photo_respones, true);
            if(isset($photo_respones["stat"]) && $photo_respones["stat"] == "ok") {
                $photo = $photo_respones["sizes"]["size"];
                $m = count($photo);
                for($j = 0; $j < $m; $j++) {
                    if($photo[$j]["label"] == "Original") {
                        $result_data = "<img style='width:100%;max-width:500px;' src='".$photo[$j]["source"]."' />";
                        break;
                    }
                }
            }

            echo "<tr> 
                <td>$data[level]</td>  
                <td><a href='http://localhost/www/TDUONG/game/$data[domain]/' target='_blank'>$data[domain]</a></td>
                <td>$data[mota]</td>
                <td>$result_data</td>
                <td><a href='form_edit_game.php?id=$data[ID_STT]'>Sửa</a></td>
            </tr>";
            $i++;
        }
        ?>
    </table>

</body>

<?php
ob_end_flush();
require_once("../model/close_db.php");
?>
