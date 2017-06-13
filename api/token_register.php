<?php
    ob_start();
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();

    // json response array
    $response = array("error" => FALSE);

    if (isset($_POST['hsID']) && isset($_POST["token"])) {

    } else {
        // required post params is missing
        $response["error"] = TRUE;
        // Không có dữ liệu được gửi đến!!!
        $response["error_msg"] = "3";
        echo json_encode($response);
    }
    ob_end_flush();
?>