<?php
	/*if (!isset($_SESSION["ID_HS"]) || !isset($_SESSION["fullname"]) || !isset($_SESSION["code"])) {
		header("location:http://localhost/www/TDUONG/mobile/dang-xuat/");
		exit();
	}*/
	if(stripos($_SERVER['REQUEST_URI'],".php")===false) {
		
	} else {
		header("location:http://localhost/www/TDUONG/mobile/error/");
		exit();
	}

    if(is_ddos($_SERVER['REMOTE_ADDR'],date("Y-m-d"))) {
        header("location:http://localhost/www/TDUONG/mobile/ddos/");
        exit();
    } else {
        if(!login_check()) {
            header("location:http://localhost/www/TDUONG/mobile/dang-xuat/");
            exit();
        } else {
            if(isset($_SESSION["lmID"]) && check_access_mon($_SESSION["ID_HS"],$_SESSION["lmID"])) {

            } else {
                if(stripos($_SERVER['REQUEST_URI'],"/mon/")===false && stripos($_SERVER['REQUEST_URI'],"/setup/")===false && stripos($_SERVER['REQUEST_URI'],"/xuly-")===false) {
                    header("location:http://localhost/www/TDUONG/mobile/mon/");
                    exit();
                }
            }
        }
    }

//    if($_SESSION["test"]==0 && check_testing()) {
//        $_SESSION["test"]=1;
//    }

//	if(check_show_tien()) {
//		$_SESSION["show_tien"]=1;
//	} else {
//		$_SESSION["show_tien"]=0;
//	}
    $_SESSION["show_tien"]=1;
?>