<?php
    ob_start();
    session_start();
    require_once("../model/open_db.php");
    require_once("../model/model.php");
?>
<?php
    $name=$pass=NULL;
    if(isset ($_POST["login"])) {
        if(isset($_POST["username"])) {
            $name = addslashes(trim($_POST["username"]));
        }
        if(isset($_POST["password"])) {
            $pass = md5(trim($_POST["password"]));
        }
        if($name && $pass) {
            $query = "SELECT ID_NH,SDT,dia_chi FROM nha_hang WHERE username='$name' AND password='$pass'";
            $result=mysqli_query($db,$query);
            if(mysqli_num_rows($result) != 0) {
                $data=mysqli_fetch_assoc($result);
                $_SESSION['id-nh']=$data['ID_NH'];
                $_SESSION['phone']=$data['SDT'];
                $_SESSION['address']=$data['dia_chi'];
                header("location: http://localhost/www/TDUONG/nhahang/donhang1.php");
                exit();
            } else {
                echo "Sai username hoặc mật khẩu!";
            }
        } else {
            echo "Vui lòng nhập đầy đủ thông tin!";
        }
    }
?>
    <h3>Đăng nhập</h3>
    <form action="login-food.php" method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Username</td>
                <td><input type="text" name="username" autocomplete="off"</td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password"</td>
            </tr>
            <tr>
                <td></td>
                <td><button name="login" class="log">Login</button></td>
            </tr>
        </table>
    </form>





<?php ob_end_flush();
require_once("../model/close_db.php");
?>
