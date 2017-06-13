<?php

	$current= "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	$_SESSION["last_page"]=$current;
	$desktop_url=str_ireplace("m.bgo.edu.vn","bgo.edu.vn",$current);
	//require("include/mobile.php");

?>