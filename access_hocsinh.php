<?php
	/*if (!isset($_SESSION["ID_HS"]) || !isset($_SESSION["fullname"]) || !isset($_SESSION["code"])) {
		header("location:https://localhost/www/TDUONG/dang-xuat/");
		exit();
	}*/
//header("location:https://localhost/www/TDUONG/update.html");
//exit();

	if(stripos($_SERVER['REQUEST_URI'],".php")===false) {
		
	} else {
		header("location:https://localhost/www/TDUONG/error/");
		exit();
	}
	
	if(is_ddos($_SERVER['REMOTE_ADDR'],date("Y-m-d"))) {
		header("location:https://localhost/www/TDUONG/ddos/");
		exit();
	} else {
        if(!login_check()) {
            header("location:https://localhost/www/TDUONG/dang-xuat/");
            exit();
        } else {
            if(isset($_SESSION["lmID"]) && check_access_mon($_SESSION["ID_HS"],$_SESSION["lmID"])) {

            } else {
                if(stripos($_SERVER['REQUEST_URI'],"/mon/")===false && stripos($_SERVER['REQUEST_URI'],"/setup/")===false && stripos($_SERVER['REQUEST_URI'],"/xuly-")===false) {
                    header("location:https://localhost/www/TDUONG/mon/");
                    exit();
                }
            }
        }
    }

//	if($_SESSION["test"]==0 && check_testing()) {
//		$_SESSION["test"]=1;
//	}

//	if(check_show_tien()) {
//		$_SESSION["show_tien"]=1;
//	} else {
//		$_SESSION["show_tien"]=0;
//	}
    $_SESSION["show_tien"]=1;
?>