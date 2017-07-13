<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
    require("../../model/model.php");
    require("../access_admin.php");
    require_once("../include/googledrive/vendor/autoload.php");
    require_once("../include/googledrive/examples/templates/base.php");

    if (!$oauth_credentials = getOAuthCredentialsFile()) {
//        echo missingOAuth2CredentialsWarning();
        return;
    }

    $redirect_uri = 'http://localhost/www/TDUONG/thaygiao/connect_google.php';

    $client = new Google_Client();
    $client->setAuthConfig($oauth_credentials);
    $client->setRedirectUri($redirect_uri);
    $client->addScope("https://www.googleapis.com/auth/drive");
    $service = new Google_Service_Drive($client);

    // set the access token as part of the client
    if (!empty($_SESSION['upload_token'])) {
        $client->setAccessToken($_SESSION['upload_token']);
        if ($client->isAccessTokenExpired()) {
            unset($_SESSION['upload_token']);
        }
    } else {
        $authUrl = $client->createAuthUrl();
    }
	
	if (isset($_POST["videoID"])) {
		$videoID=$_POST["videoID"];
		kill_video($videoID);
	}

	if(isset($_POST["ajax_share"]) && !isset($authUrl)) {
	    $ajax=json_decode($_POST["ajax_share"],true);
        $n=count($ajax)-1;
        $id=addslashes($ajax[$n]["idFile"]);
        $name=addslashes($ajax[$n]["name"]);
        $type=$ajax[$n]["type"];
        $date_up=$ajax[$n]["date_up"];
        $date_close=$ajax[$n]["date_close"];
        $nID=$ajax[$n]["luyenthi"];
        $lmID=$ajax[$n]["lmID"];
        $hsID=$ajax[$n]["hsID"];
        $query="SELECT email FROM hocsinh WHERE ID_HS='$hsID' AND email LIKE '%@gmail.com'";
        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result) != 0 && valid_id($date_close)) {
            $data=mysqli_fetch_assoc($result);
            $email=$data["email"];
            $now=date_create(date("Y-m-d"));
            date_add($now,date_interval_create_from_date_string("+$date_close days"));
            $date_close=date_format($now,"Y-m-d");

            $service->getClient()->setUseBatch(true);
            try {
                $batch = $service->createBatch();
                $userPermission = new Google_Service_Drive_Permission(array(
                    'type' => 'user',
                    'role' => 'reader',
                    'emailAddress' => $email
                ));
                $request = $service->permissions->create(
                    $id, $userPermission, array('fields' => 'id','sendNotificationEmail' => false));
                $batch->add($request, 'user');
                $results = $batch->execute();

                foreach ($results as $result) {
                    if ($result instanceof Google_Service_Exception) {
                        echo"error";
                    } else {
                        $permId=$result->id;
                        $query="INSERT INTO google_drive(ID_HS,ID_FILE,ID_PERM,date_close,date_open,date_up,name,type,dad,ID_LM,ID_N)
                                                    VALUES('$hsID','$id','$permId','$date_close',now(),'$date_up','$name','$type','0','$lmID','$nID')";
                        mysqli_query($db,$query);
                        $dad=mysqli_insert_id($db);
                        $content=array();
                        for($i=0;$i<$n;$i++) {
                            $content[] = "('$hsID','".addslashes($ajax[$i]["id"])."','".$ajax[$i]["type"]."','$dad','$lmID')";
                        }
                        if(count($content) > 0) {
                            $content=implode(",",$content);
                            $query="INSERT INTO google_drive(ID_HS,ID_FILE,type,dad,ID_LM) VALUES $content";
                            mysqli_query($db,$query);
                        }
                        echo $permId."|".$dad;
                    }
                    break;
                }
            } finally {
                $service->getClient()->setUseBatch(false);
            }
        } else {
            echo"none";
        }
    }

    if(isset($_POST["idLock"]) && isset($_POST["hsID"]) && isset($_POST["sttID"]) && isset($_POST["perm"]) && !isset($authUrl)) {
        $id = addslashes(trim($_POST["idLock"]));
        $hsID = $_POST["hsID"];
        $sttID = $_POST["sttID"];
        $permId = addslashes(trim($_POST["perm"]));
        $service->getClient()->setUseBatch(true);
        try {
            $batch = $service->createBatch();
            $request = $service->permissions->delete($id, $permId);
            $batch->add($request, 'user');
            $results = $batch->execute();

            foreach ($results as $result) {
                if ($result instanceof Google_Service_Exception) {
                    echo"error";
                } else {
                    $query="DELETE FROM google_drive WHERE ID_HS='$hsID' AND ID_FILE='$id' AND ID_PERM='$permId'";
                    mysqli_query($db,$query);
                    $query="DELETE FROM google_drive WHERE ID_HS='$hsID' AND dad='$sttID'";
                    mysqli_query($db,$query);
                    echo"ok";
                }
                break;
            }
        } finally {
            $service->getClient()->setUseBatch(false);
        }
    }

    if(isset($_POST["mon_name"]) &&isset($_POST["lmID_url"]) && isset($_POST["lop"]) && !isset($authUrl)) {
        $mon_name=$_POST["mon_name"];
        $lmID=$_POST["lmID_url"];
        $lop=$_POST["lop"];
        $pageToken = null;
        do {
            $response = $service->files->listFiles(array(
                "q" => "name='$mon_name'",
                "spaces" => "drive",
                "pageToken" => $pageToken,
                "fields" => "nextPageToken, files(id, name, createdTime, mimeType)",
            ));
            foreach ($response->files as $file) {
                $response_con = $service->files->listFiles(array(
                    "q" => "'".$file->id."' in parents",
                    "spaces" => "drive",
                    "fields" => "files(id, name, createdTime, mimeType)",
                ));
                foreach ($response_con->files as $file_con) {
                    if (stripos($file_con->mimeType, "folder") != false) {
                        if ($file_con->name == $lop) {
                            $idLop=$file_con->id;
                            add_options2($file->id."|".$idLop,"gdrive_url",$lmID,"");
                            echo $file->id."|".$idLop;
                            break;
                        }
                    }
                }
                break;
            }
        } while ($pageToken != null);
        $_SESSION["gdrive"] = NULL;
        unset($_SESSION["gdrive"]);
        if(isset($_COKKIE["gdrive"])) {
            unset($_COKKIE["gdrive"]);
        }
        setcookie("gdrive", "", time() - 86400*30, "/");
    }

    if(isset($_POST["ajax_role"]) && !isset($authUrl)) {
        $ajax=json_decode($_POST["ajax_role"],true);
        $n=count($ajax);
        for($i=0;$i<$n;$i++) {
            $file = new Google_Service_Drive_DriveFile();
            $file->setViewersCanCopyContent(false);
            $file->setWritersCanShare(false);
            $result = $service->files->update(
                $ajax[$i]["id"],
                $file,
                array(
                    "uploadType" => "multipart"
                )
            );
            echo $result->id."|";
        }
        $_SESSION["gdrive"] = NULL;
        unset($_SESSION["gdrive"]);
    }

    if(isset($_POST["nID"]) && isset($_POST["fileID"]) && isset($_POST["lmID"])) {
        $nID=$_POST["nID"];
        $fileID=addslashes($_POST["fileID"]);
        $lmID=$_POST["lmID"];
        $query="UPDATE google_drive SET ID_N='$nID' WHERE ID_FILE='$fileID'";
        mysqli_query($db,$query);
        if(mysqli_affected_rows($db) == 0) {
            $query="INSERT INTO google_drive(ID_HS,ID_FILE,ID_LM,ID_N)
                                    VALUES('0','$fileID','$lmID','$nID')";
            mysqli_query($db,$query);
        }
    }

    if(isset($_POST["ajax_luyenthi"])) {
        $ajax=json_decode($_POST["ajax_luyenthi"],true);
        $n=count($ajax)-1;
        $lmID=$ajax[$n]["lmID"];
        $content=array();
        for($i=0;$i<$n;$i++) {
            $nameConvert = unicode_convert($ajax[$i]["name"]);
            $query = "SELECT nhom FROM de_thi WHERE string LIKE '%$nameConvert%' ORDER BY string ASC LIMIT 1";
            $result = mysqli_query($db, $query);
            if (mysqli_num_rows($result) != 0) {
                $data = mysqli_fetch_assoc($result);
                $query = "UPDATE google_drive SET ID_N='$data[nhom]' WHERE ID_FILE='" . $ajax[$i]["id"] . "'";
                mysqli_query($db, $query);
                if (mysqli_affected_rows($db) == 0) {
                    $content[] = "('0','" . $ajax[$i]["id"] . "','$lmID','$data[nhom]')";
                }
            }
        }
        if(count($content)>0) {
            $content=implode(",",$content);
            $query = "INSERT INTO google_drive(ID_HS,ID_FILE,ID_LM,ID_N) VALUES $content";
            mysqli_query($db, $query);
        }
    }
	
	require("../../model/close_db.php");
	ob_end_flush();
?>