<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WOLFRAMALPHA</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">

    <style type="text/css">
        * {margin: 0 0 0 10px;padding: 0;}
        p {font-family: "Roboto", Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 17px;line-height: 33px;}
    </style>
</head>
<body>
<?php
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    require_once "mess/DB_Functions.php";
    include_once "wolframalpha/WolframAlphaEngine.php";

    $error = false;
    $ct = $response = NULL;
    $appID = "EJR373-9X94RRJ966";
    if(isset($_GET["sender"]) && isset($_GET["q"])) {
        $sender = $_GET["sender"];
        $db = new DB_Functions();
        $result = $db->getHocSinhFromFB($sender);
        if($result->num_rows != 0) {
            $ct = $_GET["q"];
            $qArgs = array();
            $engine = new WolframAlphaEngine($appID);
            $response = $engine->getResults($ct, $qArgs);
            if ($response->isError()) {
                $error = true;
                echo "<p><strong>Không có dữ liệu trả về!</strong></p>";
            } else {
                if (count($response->getPods()) > 0) {
                    foreach ($response->getPods() as $pod) {
                        echo "<p><strong>" . $pod->attributes['title'] . "</strong></p>";
                        foreach ($pod->getSubpods() as $subpod) {
                            echo "<p><img src='" . $subpod->image->attributes['src'] . "'></p>";
                        }
                    }
                }
            }
        } else {
            $error = true;
            echo "<p><strong>Bạn chưa đăng ký với Bot!</strong></p>";
        }
    } else {
        $error = true;
        echo "<p><strong>Vui lòng nhập công thức!</strong></p>";
    }
?>
</body>
</html>
