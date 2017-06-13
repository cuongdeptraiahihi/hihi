<?php
	ob_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	session_start();
    if(!$_SESSION['id']) {
        header('location: http://localhost/www/TDUONG/trogiang/dang-nhap/');
        exit();
    }
    $id=$_SESSION['id'];

    if (isset($_POST["tbID"])) {
        $tbID=$_POST["tbID"];
        tat_thong_bao_tro_giang($tbID);
    }

	if(isset($_POST["action"])) {
        $action = $_POST["action"];
        if ($action == "get") {
            $dem_tb=0;
            $result = get_thong_bao_trogiang($id);
            while ($data = mysqli_fetch_assoc($result)) {
                $action = "";
                echo "<li><a href='http://localhost/www/TDUONG/trogiang/tong-quan/' data-tbID='$data[ID_TB]'>
                    <ol class='tb-con'>
                    <p>Bạn có một ghi chú mới!</p>
                    </ol>
                    <ol class='tb-action'>
                        <i class='fa fa-bell-slash' title='Tắt'></i>
                        $action
                    </ol>
                     <div class='clear'></div>
                </a></li>";
                $dem_tb++;
            }
        }
    }
	
	require("../../model/close_db.php");
	ob_end_flush();
?>