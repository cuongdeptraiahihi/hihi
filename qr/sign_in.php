<?php
	ob_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
    header("Content-type: application/json");

    $ketqua = null;

    if(isset($_GET["username"]) && isset($_GET["password"])) {
        $username = addslashes($_GET["username"]);
        $password = addslashes($_GET["password"]);
        $result = login_admin($username, $password);

        $json = array(
            "ID" => "0",
            "Username" => "None",
            "Content" => "NONE"
        );

        if(mysqli_num_rows($result) != 0) {
            $data = mysqli_fetch_assoc($result);
            $id = $data["ID"];
            $json["ID"] = "$id";
            $json["Username"] = md5($id . "|" . $password);
            $json["Content"] = "OK";
        } else {
            $json["Content"] = "LOGIN_FAIL";
        }

        $ketqua = json_encode($json);
    }

    if(isset($_GET["userid"]) && isset($_GET["accesscode"])) {
        $id = addslashes($_GET["userid"]);
        $access = addslashes($_GET["accesscode"]);
        $result = get_admin($id);
        if(mysqli_num_rows($result) != 0) {
            $data = mysqli_fetch_assoc($result);
            if (md5($id . "|" . $data["password"]) == $access) {
                $json["Content"] = "OK";
                $json["Mon"] = $json["Lopmon"] = $json["Cahoc"] = array();
                $result1 = get_all_mon();
                while($data1 = mysqli_fetch_assoc($result1)) {
                    $json["Mon"][] = array(
                        "Id" => $data1["ID_MON"],
                        "Name" => $data1["name"]
                    );
                    $result2 = get_all_lop_mon2($data1["ID_MON"]);
                    while($data2 = mysqli_fetch_assoc($result2)){
                        $json["Lopmon"][] = array(
                            "Id" => $data2["ID_LM"],
                            "Name" => $data2["name"],
                            "MonID" => $data1["ID_MON"]
                        );
                        $stt = 0;
                        $result3 = get_all_cahoc_lop($data2["ID_LM"], $data1["ID_MON"]);
                        while($data3 = mysqli_fetch_assoc($result3)) {
                            if($data3["thu"] == 1) {
                                $mota = "Chủ Nhật";
                            } else {
                                $mota = "Thứ " . $data3["thu"];
                            }
                            $mota .= ", ca " . $data3["gio"];
                            $json["Cahoc"][] = array(
                                "Id" => $data3["ID_CA"],
                                "Mota" => $mota,
                                "LmID" => $data2["ID_LM"],
                                "MonID" => $data1["ID_MON"],
                                "Sort" => $stt
                            );
                            $stt++;
                        }
                    }
                }
            } else {
                $json["Content"] = "ACCESS_FAIL";
            }
        } else {
            $json["Content"] = "LOGIN_FAIL";
        }

        $ketqua = json_encode($json);
    }

    if($ketqua) {
        echo $ketqua;
    } else {
        echo json_encode(array(
            "Content" => "EMPTY"
        ));
    }

	ob_end_flush();
	require_once("../model/close_db.php");
?>