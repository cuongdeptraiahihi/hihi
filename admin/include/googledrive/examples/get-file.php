<?php
/*
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
date_default_timezone_set("Asia/Ho_Chi_Minh");
include_once __DIR__ . '/../vendor/autoload.php';
include_once "templates/base.php";

echo pageHeader("Get File");

/*************************************************
 * Ensure you've downloaded your oauth credentials
 ************************************************/
if (!$oauth_credentials = getOAuthCredentialsFile()) {
  echo missingOAuth2CredentialsWarning();
  return;
}

/************************************************
 * The redirect URI is to the current page, e.g:
 * http://localhost:8080/simple-file-upload.php
 ************************************************/
$redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

$client = new Google_Client();
$client->setAuthConfig($oauth_credentials);
$client->setRedirectUri($redirect_uri);
$client->addScope("https://www.googleapis.com/auth/drive");
$service = new Google_Service_Drive($client);

// add "?logout" to the URL to remove a token from the session
if (isset($_REQUEST['logout'])) {
  unset($_SESSION['upload_token']);
}

/************************************************
 * If we have a code back from the OAuth 2.0 flow,
 * we need to exchange that with the
 * Google_Client::fetchAccessTokenWithAuthCode()
 * function. We store the resultant access token
 * bundle in the session, and redirect to ourself.
 ************************************************/
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token);

  // store in the session also
  $_SESSION['upload_token'] = $token;

  // redirect back to the example
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
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

/************************************************
 * If we're signed in then lets try to upload our
 * file. For larger files, see fileupload.php.
 ************************************************/
?>

<div class="box">
  <?php if (isset($authUrl)): ?>
    <div class="request">
      <a class='login' href='<?= $authUrl ?>'>Connect Me!</a>
    </div>
  <?php endif ?>
  Hello
  <?php
  $pageToken = null;
  do {
    $response = $service->files->listFiles(array(
        'q' => "'0B1PMdktEQz-nY0phbXdvSTVENjg' in parents",
        'spaces' => 'drive',
        'pageToken' => $pageToken,
        'fields' => 'nextPageToken, files(id, name)',
    ));
    foreach ($response->files as $file) {
      printf("Found file: %s (%s), %s<br />", $file->name, $file->id, "https://drive.google.com/file/d/".$file->id."/view?usp=sharing");
//      $fileId = $file->id;
//      $service->getClient()->setUseBatch(true);
//      try {
//        $batch = $service->createBatch();
//
//        $userPermission = new Google_Service_Drive_Permission(array(
//            'type' => 'user',
//            'role' => 'reader',
//            'emailAddress' => 'ceo.blackgold@gmail.com'
//        ));
//        $request = $service->permissions->create(
//            $fileId, $userPermission, array('fields' => 'id','sendNotificationEmail' => false));
//        $batch->add($request, 'user');
//
//        $userPermission = new Google_Service_Drive_Permission(array(
//            'type' => 'user',
//            'role' => 'reader',
//            'emailAddress' => 'phanthuha93@gmail.com'
//        ));
//        $request = $service->permissions->create(
//            $fileId, $userPermission, array('fields' => 'id','sendNotificationEmail' => false));
//        $batch->add($request, 'user');
//        $results = $batch->execute();
//
//        foreach ($results as $result) {
//          if ($result instanceof Google_Service_Exception) {
//            // Handle error
//            printf($result);
//          } else {
//            printf("Permission ID: %s<br />", $result->id);
//          }
//        }
//      } finally {
//        $service->getClient()->setUseBatch(false);
//      }
      break;
    }
  } while ($pageToken != null);
  ?>

</div>
<?php
// Lấy folder mimeType='application/vnd.google-apps.folder'
// Lấy mọi phần tử trong thư mục có id '$folderId' in parents
?>

<?= pageFooter(__FILE__) ?>
