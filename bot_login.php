<?php
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    require_once "mess/DB_Functions.php";
    $ip=$_SERVER['REMOTE_ADDR'];
    if(isset($_GET["redirect_uri"])) {
        $urlFB = $_GET["redirect_uri"];
        $check = true;
    } else {
        $urlFB = "https://localhost/www/TDUONG/error/";
        $check = false;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <title>ĐĂNG NHẬP</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->

        <style>
            @import url(https://fonts.googleapis.com/css?family=Roboto:300);.container,.form{position:relative;z-index:1}.login-page{width:90%;padding:5% 0 0;margin:auto}.form{background:#FFF;max-width:400px;margin:0 auto 100px;padding: 15px 25px 25px 25px;text-align:center;box-shadow:0 0 20px 0 rgba(0,0,0,.2),0 5px 5px 0 rgba(0,0,0,.24)}.form button,.form input{outline:0;width:100%;border:0;padding:15px;font-size:14px;font-family:Roboto,sans-serif}.form input{background:#f2f2f2;margin:0 0 15px;box-sizing:border-box}.form button{text-transform:uppercase;background:#4CAF50;color:#FFF;-webkit-transition:all .3 ease;transition:all .3 ease;cursor:pointer}.form button:active,.form button:focus,.form button:hover{background:#43A047}.form .message{margin:15px 0 0;color:#b3b3b3;font-size:12px}.form .message a{color:#4CAF50;text-decoration:none}.form .register-form{display:none}.container{max-width:300px;margin:0 auto}.container:after,.container:before{content:"";display:block;clear:both}.container .info{margin:50px auto;text-align:center}.container .info h1{margin:0 0 15px;padding:0;font-size:36px;font-weight:300;color:#1a1a1a}.container .info span{color:#4d4d4d;font-size:12px}.container .info span a{color:#000;text-decoration:none}.container .info span .fa{color:#EF3B3A}body{background:#76b852;background:-webkit-linear-gradient(right,#76b852,#8DC26F);background:-moz-linear-gradient(right,#76b852,#8DC26F);background:-o-linear-gradient(right,#76b852,#8DC26F);background:linear-gradient(to left,#76b852,#8DC26F);font-family:Roboto,sans-serif;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
        </style>

        <script src="https://localhost/www/TDUONG/js/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("button#ok").click(function() {
                    if($("#cmt").val().trim() != "" && $("#pass").val().trim()!="") {
                        $(this).css("background","green").html("Đang đăng nhập!");
                        return true;
                    } else {
                        $(this).css("background","red").html("Vui lòng nhập đủ thông tin!");
                        return false;
                    }
                });
                $("#cmt, #pass").keyup(function () {
                    if($(this).val().trim() != "") {
                        $("button#ok").removeAttr("style").html("Đăng nhập");
                    }
                });
            });
        </script>
    </head>
    <body>

        <?php
            $error="";
            $cmt=$password=$sdt=NULL;
            if(isset($_POST["ok"]) && $check) {

                if(isset($_POST["cmt"])) {
                    $cmt=trim(addslashes($_POST["cmt"]));
                }

                if(isset($_POST["pass"])) {
                    $sdt=addslashes($_POST["pass"]);
                    $password=md5($sdt);
                }

                if($cmt && $password && $sdt) {
                    if(validMaso($cmt)) {
                        $db = new DB_Functions();
                        $id = $db->login($cmt, $password, $sdt);
                        if ($id != 0) {
//                        $db->addLog($id,"Đã đăng nhập vào BOT","login-error");
                            header("location:$urlFB&authorization_code=$id");
                            exit();
                        } else {
                            $error = "Không tồn tại thông tin đăng nhập!";
                        }
                    } else {
                        $error = "Mã số không chính xác!";
                    }
                } else {
                    $error = "Vui lòng nhập đầy đủ thông tin đăng nhập!";
                }
            }
        ?>

        <div class="login-page">
            <div class="form">
                <form class="login-form" action="https://localhost/www/TDUONG/bot-login/?redirect_uri=<?php echo $urlFB; ?>" method="post">
                    <p style="font-size: 26px;">Bgo Education</p>
                    <?php
                        if($error != "") {
                            echo"<p style='color: red;'>$error</p>";
                        }
                    ?>
                    <input type="text" autocomplete="off" id="cmt" name="cmt" placeholder="Mã số học sinh"/>
                    <input type="password" autocomplete="off" id="pass" name="pass" placeholder="Mật khẩu / SĐT phụ huynh"/>
                    <button type="submit" name="ok" id="ok">Đăng nhập</button>
                </form>
            </div>
        </div>
    </body>
</html>