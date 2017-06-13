<?php
ob_start();
session_start();
require_once("../model/open_db.php");
require_once("../model/model.php");
require_once "../model/Flick.php";
$f = new Flick("bc196e763e5476b218d50fc79fd7f278", "c73a724daf1b18fa", "http://localhost/www/TDUONG/game/vong/3/");
if(isset($_GET["oauth_token"]) && isset($_GET["oauth_verifier"])) {
    $oauth_verifier = $_GET["oauth_verifier"];
    $_SESSION["oauth_verifier"] = $oauth_verifier;

    $oauth_token = $_GET["oauth_token"];
    $oauth_token_secret = $_SESSION["oauth_token_secret"];
    if($oauth_token != $_SESSION["oauth_token"]) {
        echo "Fail oauth_token";
    } else {
        $respones = $f->getAccessToken($oauth_token, $oauth_token_secret, $oauth_verifier);
        $content = "oauth_token=".$respones["oauth_token"]."&oauth_token_secret=".$respones["oauth_token_secret"];
        $id = str_replace("%40","@",$respones["user_nsid"]);
        add_options2($content, "flickr", $id, "admin");
        echo json_encode($respones);
    }
} else {
    echo "ERROR";
}
ob_end_flush();
require_once("../model/close_db.php");
?>