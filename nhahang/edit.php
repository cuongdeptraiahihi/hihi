<?php
    ob_start();
    session_start();
    require_once("../model/open_db.php");
    require_once("../model/model.php");
    $idnh=$_SESSION['id-nh'];
?>

<?php
if(isset ($_GET["id"])) {
    $id=$_GET["id"];
} else {
    $id=0;
}

if(isset ($_POST["OK"])) {
    $name=$_POST["name"];
    $gia=$_POST["cost"];
    $giam=$_POST["discount"];
    $mota=$_POST["mota"];
    if( $_FILES["fileToUpload"]["error"] >0) {
        $query = "UPDATE san_pham SET ten='$name',mota='$mota',gia_tien='$gia',giam_gia='$giam' WHERE ID_SP='$id' AND ID_NH='$idnh'";
        $result = mysqli_query($db, $query);
    } else {
        if($_FILES["fileToUpload"]["type"] != "image/jpeg" && $_FILES["fileToUpload"]["type"] != "image/png"){
            echo "Bạn chỉ được upload ảnh dạng jpeg hoặc png!";
        } else {
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],"images/".$_FILES["fileToUpload"]["name"]);
            $query = "UPDATE san_pham SET ten='$name',anh='".$_FILES["fileToUpload"]["name"]."',mota='$mota',gia_tien='$gia',giam_gia='$giam' WHERE ID_SP='$id' AND ID_NH='$idnh'";
            $result = mysqli_query($db, $query);
        }
    }
    header('location: http://localhost/www/TDUONG/nhahang/sanpham1.php');
    exit();
}
if($id != 0) {
    $query = "SELECT * FROM san_pham WHERE ID_SP='$id' AND ID_NH='$idnh'";
    $result = mysqli_query($db, $query);
    $data=mysqli_fetch_assoc($result);
    echo "<form action='edit.php?id=$id' method='post' enctype='multipart/form-data'>
        <table>
            <tr>
                <td>Tên sản phẩm</td>
                <td><input class='name' name='name' type='text' autocomplete='off' size='30' value='$data[ten]'/></td>
            </tr>
            <tr>
                <td>Ảnh sản phẩm</td>
                <td><img src='http://localhost/www/TDUONG/nhahang/images/$data[anh]'/></td>
            </tr>
            <tr>
                <td>Mô tả</td>
                <td><textarea name='mota' class='mota' cols='50' rows='7'>$data[mota]</textarea></td>
            </tr>
            <tr>
                <td></td>
                <td><input class='image' type='file' name='fileToUpload'/></td>
            </tr>
            <tr>
                <td>Giá tiền</td>
                <td><input class='cost' name='cost' type='number' autocomplete='off' size='10' value='$data[gia_tien]'/></td>
            </tr>
            <tr>
                <td>Giảm giá</td>
                <td><input class='discount' name='discount' type='number' autocomplete='off' size='10' value='$data[giam_gia]'/></td>
            </tr>

        </table>
        <button class='submit' id='popup-view' name='OK' type='submit' value='Upload'>OK</button>
    </form>";
    }
?>
<?php
    ob_end_flush();
    require_once("../model/close_db.php");
?>