<?php
ob_start();
session_start();
ini_set('max_execution_time', 300);
require_once("../model/open_db.php");
require_once("../model/model.php");
require_once("access_admin.php");
require_once("include/googledrive/vendor/autoload.php");
require_once("include/googledrive/examples/templates/base.php");

if (!$oauth_credentials = getOAuthCredentialsFile()) {
    echo missingOAuth2CredentialsWarning();
    return;
}

$redirect_uri = 'http://localhost/www/TDUONG/admin/connect_google.php';

$client = new Google_Client();
$client->setAuthConfig($oauth_credentials);
$client->setRedirectUri($redirect_uri);
$client->addScope("https://www.googleapis.com/auth/drive");
$service = new Google_Service_Drive($client);

// add "?logout" to the URL to remove a token from the session
if (isset($_REQUEST['logout'])) {
    unset($_SESSION['upload_token']);
}

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // store in the session also
    $_SESSION['upload_token'] = $token;

    // redirect back to the example
    header("location:".$redirect_uri);
    exit();
}

// set the access token as part of the client
if (!empty($_SESSION['upload_token'])) {
    $client->setAccessToken($_SESSION['upload_token']);
    if ($client->isAccessTokenExpired()) {
        unset($_SESSION['upload_token']);
    }
} else {
    $authUrl = $client->createAuthUrl();
}

if(isset($authUrl)) {
    echo"<a href='".$authUrl."'>Connect!</a>";
} else {
    echo"OK";
    if(isset($_SESSION["G_DRIVE_CALLBACK"])) {
        header("location:".$_SESSION["G_DRIVE_CALLBACK"]);
        exit();
    }
}