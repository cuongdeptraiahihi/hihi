<?php
	ob_start();
	////session_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();
	require_once("model/is_mobile.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>KAHOOTIT</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
       	<link rel="stylesheet" href="https://localhost/www/TDUONG/css/new.css" type="text/css" />
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="https://localhost/www/TDUONG/css/font-awesome.min.css">
        
        <style>
			* {padding:0;margin:0;}a {text-decoration:none;}ul, li {list-style-type:none;}body {width:100%;height:100%;background:#D1DBBD;font-family: "FontLight";letter-spacing:0.5px;}#BODY {width:100%;height:100%;}#MAIN {width:100%;height:100%;}.kahoot iframe {width:100%;height:100%;position:fixed;z-index:99;left:0;top:0;}
			
			p.ng-binding {display:none;}
        </style>
        
        <script src="https://localhost/www/TDUONG/js/jquery.min.js"></script>
        <script type="text/javascript">
			$(document).ready(function() {
			});
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
        
        	<div id="MAIN">
                <div class="kahoot"><iframe id="foo" src="https://kahoot.it/"></iframe></div>
            </div>
        
        </div>
        
    </body>
</html>

<?php
	ob_end_flush();
	require_once("model/close_db.php");
?>