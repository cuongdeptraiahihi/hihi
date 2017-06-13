<?php

	$current= "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	$_SESSION["last_page"]=$current;
	$desktop_url=$current;
	$mobile_url=str_ireplace("bgo.edu.vn","m.bgo.edu.vn",$current);
	require("model/mobile.php");

?>