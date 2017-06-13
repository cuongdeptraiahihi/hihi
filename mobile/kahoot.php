<?php
	ob_start();
	////session_start();
	require_once("include/is_mobile.php");
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
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        
        <style>
			* {padding:0;margin:0;}a {text-decoration:none;}ul, li {list-style-type:none;}body {width:100%;height:100%;background:#D1DBBD;font-family: "FontLight";letter-spacing:0.5px;}#BODY {width:100%;height:100%;}#MAIN {width:100%;height:100%;}.kahoot iframe {width:100%;height:100%;position:fixed;z-index:99;left:0;top:0;}#foot {position:fixed;left:0;top:0;z-index:999;background:red;padding:10px;}#foot p {font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#FFF;}
        </style>
        
        <script src="http://localhost/www/TDUONG/mobile/js/jquery.min.js"></script>
        <script type="text/javascript">
		</script>
       
	</head>

    <body>
                             
      	<div id="SIDEBACK"><div id="BODY">
        
        	<div id="MAIN">
                <div class="kahoot"><iframe id="foo" src="https://kahoot.it/"></iframe></div>
                <div id="foot"><p>Bgo.edu.vn</p></div>
            </div>
        
        </div>
        
    </body>
</html>

<?php
	ob_end_flush();
?>