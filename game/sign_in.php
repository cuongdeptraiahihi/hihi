<?php include("include/start-game.php"); ?>
<?php
    $error = "";
    $maso = $pass = NULL;
    if(isset($_POST["submit"])) {
        if(isset($_POST["maso"])) {
            $maso = addslashes(trim($_POST["maso"]));
        }
        if(isset($_POST["pass"])) {
            $pass = md5(addslashes(trim($_POST["pass"])));
        }
        if($maso && $pass) {
            $result = login($maso, $pass);
            if(mysqli_num_rows($result) == 1) {
                $_SESSION = array();
                session_destroy();
                session_start();
                session_regenerate_id();
                get_login($result,1);
                header("location:http://localhost/www/TDUONG/game/");
                exit();
            } else {
                $error = "Không tồn tại thông tin bạn cung cấp!";
            }
        } else {
            $error = "Vui lòng nhập đầy đủ thông tin!";
        }
    }
?>
    <body>
        <h2>Đăng nhập</h2>
        <p><span>Điền thông tin tài khoản Bgo của bạn dưới đây!</span></p>
        <form action="http://localhost/www/TDUONG/game/dang-nhap/" method="post">
            <table>
                <tr>
                    <td><input type="text" autocomplete="off" name="maso" placeholder="Mã số học sinh"/></td>
                </tr>
                <tr>
                    <td><input type="password" autocomplete="off" name="pass" placeholder="Mật khẩu"/></td>
                </tr>
                <?php if($error != "") { ?>
                    <tr>
                        <td style="color: red;font-size: 14px;font-weight: 600;">
                            <?php echo $error; ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><button name="submit" style="padding: 10px;">Đăng nhập</button></td>
                </tr>
            </table>
        </form>
    </body>
<?php include("include/end-game.php"); ?>