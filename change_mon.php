<?php
	ob_start();
	//session_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();
	require_once("access_hocsinh.php");
	if(isset($_GET["lmID"]) && is_numeric($_GET["lmID"])) {
		$lmID=$_GET["lmID"];
		$result=check_access_mon2($_SESSION["ID_HS"],$lmID);
		if($result!=false) {
			$_SESSION["lmID"]=$lmID;
			$data=mysqli_fetch_assoc($result);
			$_SESSION["de"]=$data["de"];
            $monID=get_mon_of_lop($lmID);
            $_SESSION["mon"]=$monID;
			$result2=get_mon_info($monID);
			$data2=mysqli_fetch_assoc($result2);
            $_SESSION["thang"]=$data2["thang"];
            $_SESSION["is_ct"]=$data2["ct"];
            unset($_SESSION["count-hoc-tinh"]);
            unset($_SESSION["di-hoc"]);
            foreach ($_SESSION as $key => $value) {
                if(stripos($key, "chuyende-") === false) {

                } else {
                    unset($value);
                }
            }
		} 
		header("location:https://localhost/www/TDUONG/tong-quan/");
		exit();
	} else {
		header("location:https://localhost/www/TDUONG/tong-quan/");
		exit();
	}
	
	ob_end_flush();
	require_once("model/close_db.php");
?>