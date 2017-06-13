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
                    $("button.del-sp").click(function () {
                        var del_tr = $(this).closest("tr");
                        var id = $(this).closest("tr").attr("data-id");
                        if (confirm("Bạn có chắc chắn xóa sản phẩm này?")) {
                            if (id != 0 && $.isNumeric(id)) {
                                $.ajax({
                                    async: false,
                                    data: "id=" + id,
                                    type: "post",
                                    url: "http://localhost/www/TDUONG/nhahang/xuly_doan.php",
                                    success: function (result) {
                                        del_tr.remove();
                                    }
                                });
                            } else {
                                alert("Dữ liệu không chính xác!");
                            }
                        }
                    });

                    $("button.edit-sp").click(function () {
                        var id = $(this).closest("tr").attr("data-id");
                        if (id != 0 && $.isNumeric(id)) {
                            $.ajax({
                                async: false,
                                data: "id4=" + id,
                                type: "post",
                                url: "http://localhost/www/TDUONG/nhahang/xuly_doan.php",
                                success: function (result) {
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
    <div class="san-pham" style="padding-top: 20px; margin:auto;">
        <form action="edit.php" method="post" enctype="multipart/form-data">
            <button class="sanpham-new"><a href="http://localhost/www/TDUONG/nhahang/upspnew.php">Đăng sản phẩm mới</a></button>
        </form>
        <table  border="3" width="90%" style="margin-top:10px;">
            <tr>
                <td width="5%">STT</td>
                <td width="20%">Tên sản phẩm</td>
                <td width="20%">Ảnh sản phẩm</td>
                <td width="20%">Mô tả</td>
                <td width="10%">Giá tiền</td>
                <td width="10%">Giảm giá</td>
                <td width="15%"></td>
            </tr>
            <?php $result=getlistSanpham($idnh);
            $i=1;
            while ($data=mysqli_fetch_assoc($result)) {
                echo "<tr data-id='$data[ID_SP]'>
                    <td>$i</td>
                    <td>$data[ten]</td>
                    <td><img src='http://localhost/www/TDUONG/nhahang/images/$data[anh]' width='290px' height='163px'/></td>
                    <td>$data[mota]</td>
                    <td>$data[gia_tien]</td>
                    <td>$data[giam_gia]</td>
                    <td><button class='del-sp'>Xóa</button> | <button class='edit-sp'><a href='http://localhost/www/TDUONG/nhahang/edit.php?id=$data[ID_SP]'>Sửa</a></button></td>
                </tr>";
                $i++;
            }
            ?>
        </table>
    </div>
    </body>
</html>
<?php
    ob_end_flush();
    require_once("../model/close_db.php");
?>