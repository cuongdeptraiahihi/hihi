<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	
	if (isset($_POST["action"])) {
		$action=$_POST["action"];
		if($action=="testing") {
			if(check_testing()) {
				turn_off_test();
			} else {
				turn_on_test();
			}
		} else if($action=="nhayde") {
			if(check_nhayde()) {
				turn_off_nhayde();
			} else {
				turn_on_nhayde();
			}
		} else if($action=="show_tien") {
			if(check_show_tien()) {
				turn_off_show_tien();
			} else {
				turn_on_show_tien();
			}
		} else {
            if(check_game()) {
                turn_off_game();
            } else {
                turn_on_game();
            }
		}
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>