<?php
    session_start();
    require_once("sdk/autoload.php");

    $dropboxKey = "c9pikc6p1xnevnv";
    $dropboxSecret = "2h1alvyw1v8pb7w";
    $appName = "TDUONG";

    $appInfo = new Dropbox\AppInfo($dropboxKey,$dropboxSecret);

    $csrfTokenStore = new Dropbox\ArrayEntryStore($_SESSION, "dropbox-auth-csrf-token");
    $webAuth = new Dropbox\WebAuth($appInfo, $appName, 'https://localhost/www/TDUONG/dropbox_done.php', $csrfTokenStore);

    $query="SELECT * FROM options WHERE type='dropbox_token' ORDER BY note DESC LIMIT 1";
    $result=mysqli_query($db,$query);
    $data=mysqli_fetch_assoc($result);

    $userToken = $data["content"];

