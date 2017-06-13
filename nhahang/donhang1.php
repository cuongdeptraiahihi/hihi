<?php
    ob_start();
    session_start();
    require_once("../model/open_db.php");
    require_once("../model/model.php");
    if(!$_SESSION['id-nh']) {
        header('location: http://localhost/www/TDUONG/nhahang/login-food.php');
        exit();
    }
    $idnh=$_SESSION['id-nh'];
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Nhà hàng</title>
        <style type="text/css">
            <!--
            -->
        </style>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
        <script src="https://localhost/www/TDUONG/js/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                    $("button.del-donhang").click(function() {
                        var del_tr = $(this).closest("tr");
                        var id = $(this).closest("tr").attr("data-id");
                        if(confirm("Bạn có chắc chắn xóa đơn hàng này?")) {
                            if(id!=0 && $.isNumeric(id)) {
                                $.ajax({
                                    async: false,
                                    data: "id1=" + id,
                                    type: "post",
                                    url: "http://localhost/www/TDUONG/nhahang/xuly-doan/",
                                    success: function (result) {
                                        del_tr.remove();
                                    }
                                });
                            } else {
                                alert("Dữ liệu không chính xác!");
                            }
                        }
                    });
                    $("button.finish").click(function() {
                        var me = $(this).closest("tr");
                        var id = $(this).closest("tr").attr("data-id");
                        if(id!=0 && $.isNumeric(id)) {
                            $.ajax({
                                async: false,
                                data: "id2=" + id,
                                type: "post",
                                url: "http://localhost/www/TDUONG/nhahang/xuly-doan/",
                                success: function (result) {
                                    me.fadeOut("fast");
                                }
                            });
                        } else {
                            alert("Dữ liệu không chính xác!");
                        }
                    });
            });
        </script>
    </head>
    <body>
    <div class="main" style="text-align:center; padding: 10px;">
        <button class="a"><a href="http://localhost/www/TDUONG/nhahang/sanpham1.php">Sản phẩm</a></button>
        <button class="b"><a href="http://localhost/www/TDUONG/nhahang/donhang1.php">Đơn hàng</a></button>
        <button class="c"><a href="http://localhost/www/TDUONG/nhahang/lichsu1.php">Lịch sử</a></button>
        <button><a href="http://localhost/www/TDUONG/nhahang/logout-food.php">Đăng xuất</a></button>
    </div>
    <div class="don-hang" style="padding-top: 20px;">
        <table border="3" width="90%">
            <tr>
                <td width="5%">STT</td>
                <td width="25%">Học sinh</td>
                <td width="20%">Sản phẩm</td>
                <td width="5%">Số lượng</td>
                <td width="15%">Giá tiền</td>
                <td width="20%">Ngày đặt</td>
                <td width="10%"></td>
            </tr>
            <?php  $result1=getlistDonhang($idnh);
            $i=1;
            while ($data1=mysqli_fetch_assoc($result1)) {
                echo "<tr data-id='$data1[ID_STT]'>
                    <td>$i</td>
                    <td>$data1[fullname] ($data1[cmt])</td>
                    <td>$data1[ten]</td>
                    <td>$data1[so_luong]</td>
                    <td>$data1[gia_tien]</td>
                    <td>$data1[datetime]</td>
                    <td><button class='del-donhang'>Xoá</button> | <button class='finish'>Hoàn thành</button></td>
                </tr>";
                $i++;
            }
            ?>
        </table>
    </div>
    </body>
    </html>
<?php ob_end_flush();
require_once("../model/close_db.php");
?>