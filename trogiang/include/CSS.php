<?php
    $opacity=0.15;
    $backall="0,0,0";
    $mauall="rgba(0,0,0,$opacity)";
	echo"#myback {background-image:url(http://localhost/www/TDUONG/images/page4.jpg);}.back, #NAVBAR>ul>li, #chart-li1 ul, #MAIN .main-div > ul > li > ul, #back-one > ul > nav li .noi {background:$mauall;}#MAIN .main-div #main-table table tr#tr-me, #MAIN .main-div #main-table table tr.tr-big, .page-number ul li:hover {background:rgba($backall,".($opacity+0.2).")}";
?>