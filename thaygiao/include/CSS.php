<?php
	$resultb=get_active_background();
	$datab=mysqli_fetch_assoc($resultb);
	if($datab["note2"]!="") {
		$temp=explode("-",$datab["note2"]);
		$opacity=$temp[0];
		if($temp[1]==1) {
			$backall="255,255,255";
			$mauall="rgba(255,255,255,$opacity)";
		} else {
			$backall="0,0,0";
			$mauall="rgba(0,0,0,$opacity)";
		}
	} else {
		$opacity=0.15;
		$backall="0,0,0";
		$mauall="rgba(0,0,0,$opacity)";
	}
echo"#myback {background-image:url(https://localhost/www/TDUONG/images/$datab[content]);}.back, #NAVBAR>ul>li, #chart-li1 ul, #MAIN .main-div > ul > li > ul, #back-one > ul > nav li .noi {background:$mauall;}#MAIN .main-div #main-table table tr#tr-me, #MAIN .main-div #main-table table tr.tr-big, .page-number ul li:hover {background:rgba($backall,".($opacity+0.2).")}";
?>