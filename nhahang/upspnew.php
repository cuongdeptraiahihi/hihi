<?php
    ob_start();
    session_start();
    require_once("../model/open_db.php");
    require_once("../model/model.php");
    $idnh=$_SESSION['id-nh'];
?>
<?php

    if(isset ($_POST["OK"]) && isset ($_POST["name"]) && isset ($_POST["cost"]) && isset ($_POST["discount"]) && isset ($_POST["mota"])) {
        if($_FILES["fileToUpload"]["type"] != "image/jpeg"  && $_FILES["fileToUpload"]["type"] != "image/png" && $_FILES["fileToUpload"]["error"] > 0) {
            echo "Bạn hãy upload ảnh dưới dạng jpeg hoặc png!";
        } else {
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],"images/".$_FILES["fileToUpload"]["name"]);
            $name=$_POST["name"];
            $gia=$_POST["cost"];
            $giam=$_POST["discount"];
            $mota=$_POST["mota"];
            $query = "INSERT INTO san_pham(ten,anh,mota,gia_tien,giam_gia,ID_NH) 
                                VALUES ('$name','".$_FILES["fileToUpload"]["name"]."','$mota','$gia','$giam','$idnh')";
            $result = mysqli_query($db, $query);
            header('location: http://localhost/www/TDUONG/nhahang/sanpham1.php');
            exit();
        }

    }
?>
    <form action="http://localhost/www/TDUONG/nhahang/upspnew.php" method="post" enctype="multipart/form-data">
    <table>
                <tr>
                    <td>Tên sản phẩm</td>
                    <td><input class="name" name="name" type="text" autocomplete="off" size="30"/></td>
                </tr>
                <tr>
                    <td>Ảnh sản phẩm</td>
                    <td><input class="image" type="file" name="fileToUpload"/></td>
                </tr>
                <tr>
                    <td>Mô tả</td>
                    <td><textarea name='mota' class='mota' cols='50' rows='7'></textarea></td>
                </tr>
                <tr>
                    <td>Giá tiền</td>
                    <td><input class="cost" name="cost" type="number" autocomplete="off" size="10"/></td>
                </tr>
                <tr>
                    <td>Giảm giá</td>
                    <td><input class="discount" name="discount" type="number" value="0" autocomplete="off" size="10"/></td>
                </tr>

    </table>
            <button class="submit" id="popup-view" name="OK" type="submit" value="Upload">OK</button>
    </form>
<?php
    ob_end_flush();
    require_once("../model/close_db.php");
?>