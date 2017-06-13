<?php
	ob_start();
	//session_start();
	require("../model/open_db.php");
	require("../model/model.php");
	session_start();
    $thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
    if(isset($_SESSION["ID_HS"])) {
        $hsID = $_SESSION["ID_HS"];
    } else {
        $hsID = 0;
    }
	if(isset($_SESSION["mon"])) {
		$monID = $_SESSION["mon"];
	} else {
		$monID = 0;
	}

//    if(isset($_POST["fb-app-id"])) {
//        $id=addslashes(trim($_POST["fb-app-id"]));
//        if(is_numeric($id)) {
//            $hsID=get_hs_from_fb($id);
//        } else {
//            $hsID=0;
//        }
//        echo encode_data($hsID, md5("1241996"));
//    }
	
	if(isset($_POST["lmID"]) && is_numeric($_POST["lmID"])) {
		$lmID=$_POST["lmID"];
		if(valid_id($lmID)) {
            if(check_access_mon($hsID,$lmID)) {
                if(check_hs_nghi($hsID,$lmID)) {
                    echo format_dateup(get_date_nghi($hsID,$lmID));
                } else {
                    echo"ok";
                }
            } else {
                echo"none";
            }
		}
	}

	if(isset($_POST["encode"])) {
	    $id=$_POST["encode"];
        echo encode_data($id, md5("1241996"));
    }

    if(isset($_POST["fbid"]) && isset($_POST["fbname"])) {
        $fbid = addslashes(trim($_POST["fbid"]));
        $fbname = addslashes(trim($_POST["fbname"]));
        update_login_times($hsID, 3);
//        $login_times = get_login_times($hsID);
//        if($login_times >= 1) {
//            $check_face = get_hs_from_fb($fbid);
//            if ($check_face == 0 || $check_face == $hsID) {
//                update_facebook_id($hsID, $fbid);
//                $pattern="/[^0-9]/";
//                $fbid=preg_replace($pattern,"",$fbid);
//                add_options("https://www.facebook.com/$fbid", "dangky-face", $fbname, $hsID);
//                if($login_times == 1) {
//                    $query = "SELECT ID_STT FROM fb_messenger WHERE ID_HS='$hsID' AND progress='new'";
//                    $result = mysqli_query($db, $query);
//                    if (mysqli_num_rows($result) != 0) {
//                        $query = "UPDATE fb_messenger SET progress='normal' WHERE ID_HS='$hsID'";
//                        mysqli_query($db, $query);
//                        update_login_times($hsID, 3);
//                        $check = "ok";
//                    } else {
//                        $check = "no-mess";
//                    }
//                } else {
//                    $check = "ok";
//                }
//            } else {
//                $check = get_cmt_hs($check_face);
//            }
//        } else {
//            $check = "back";
//        }
        $check = "ok";
        echo $check;
    }

    if(isset($_POST["sdt_check"])) {
        $sdt = addslashes(trim($_POST["sdt_check"]));
        echo is_check_phone($hsID, $sdt);
    }

    if(isset($_POST["step_code"]) && isset($_POST["step_name"]) && isset($_POST["step_gender"]) && isset($_POST["step_birth"]) && isset($_POST["step_sdt"]) && isset($_POST["step_truong"]) && isset($_POST["step_name_bo"]) && isset($_POST["step_sdt_bo"]) && isset($_POST["step_name_me"]) && isset($_POST["step_sdt_me"])) {
        $code = addslashes(trim($_POST["step_code"]));
        $name = addslashes(trim($_POST["step_name"]));
        $gender = addslashes($_POST["step_gender"]);
        $birth = format_date_o(addslashes(trim($_POST["step_birth"])));
        $sdt = addslashes(trim($_POST["step_sdt"]));
        $truong = addslashes(trim($_POST["step_truong"]));
        $name_bo = addslashes(trim($_POST["step_name_bo"]));
        $sdt_bo = addslashes(trim($_POST["step_sdt_bo"]));
        $name_me = addslashes(trim($_POST["step_name_me"]));
        $sdt_me = addslashes(trim($_POST["step_sdt_me"]));
        $check_sdt = is_check_phone($hsID, $sdt);
        if($check_sdt == 0 || $code != "X") {
            $check = "ok";
//            $check = validAccountKit($hsID, $sdt, $code);
            if ($check == "ok") {
                edit_phuhuynh2($hsID, $name_bo, "", "", "", 1);
                edit_phuhuynh2($hsID, $name_me, "", "", "", 0);
                $query = "UPDATE hocsinh SET fullname='$name',namestring='" . unicode_convert($name) . "',birth='$birth',gender='$gender',sdt='$sdt',truong='$truong',sdt_bo='$sdt_bo',sdt_me='$sdt_me' WHERE ID_HS='$hsID'";
                mysqli_query($db, $query);
                check_phone($hsID, $sdt);
                update_login_times($hsID, 1);
            }
        } else if($check_sdt == 0 && $code == "X") {
            $check = "lol";
        } else {
            $check = "ok";
            edit_phuhuynh2($hsID, $name_bo, "", "", "", 1);
            edit_phuhuynh2($hsID, $name_me, "", "", "", 0);
            $query = "UPDATE hocsinh SET fullname='$name',namestring='" . unicode_convert($name) . "',birth='$birth',gender='$gender',truong='$truong',sdt_bo='$sdt_bo',sdt_me='$sdt_me' WHERE ID_HS='$hsID'";
            mysqli_query($db, $query);
            update_login_times($hsID, 1);
        }
        echo $check;
    }

    if(isset($_POST["step_ca"])) {
        $ca_arr=$_POST["step_ca"];
        $ca_arr=json_decode($ca_arr,true);
        $login_times = get_login_times($hsID);
        if($login_times >= 3) {
            $n = count($ca_arr);
            for ($i = 0; $i < $n; $i++) {
                $dataencode = explode("-", decode_data($ca_arr[$i]["data"], md5("1241996")));
                $caID = $dataencode[0];
                $cum = $dataencode[1];
                $lmID = $dataencode[2];
                if (valid_id($caID) && valid_id($cum) && valid_id($lmID)) {
                    add_hs_to_ca_codinh($caID, $hsID, $cum);
                    $result = get_info_ca($caID);
                    $data = mysqli_fetch_assoc($result);
                    add_log($hsID, "Setup tài khoản: đổi cố định sang ca " . $thu_string[$data["thu"] - 1] . ", $data[gio]", "doi-ca-$lmID");
                }
            }
            update_login_times($hsID, 4);
            echo "ok";
        } else {
            echo "back";
        }
    }

    if (isset($_POST["search_truong"]) && $_POST["search_truong"]!="") {
        $search=unicode_convert($_POST["search_truong"]);
        $result=search_truong($search);
        $n=mysqli_num_rows($result);
        if($n==0) {
            echo"<option value='0'>Không có trường này!</option>";
        } else {
            echo"<option value='0'>Tìm thấy $n trường</option>";
            while($data=mysqli_fetch_assoc($result)) {
                echo"<option value='$data[ID_T]'>$data[name]</option>";
            }
        }
    }

    if(isset($_POST["email"])) {
        $email=addslashes(trim($_POST["email"]));
        $query="UPDATE hocsinh SET email='$email' WHERE ID_HS='$hsID'";
        mysqli_query($db, $query);
        echo"ok";
    }

    if(isset($_POST["pass_old"]) && isset($_POST["pass_new"])) {
        $pass_old=addslashes(trim($_POST["pass_old"]));
        $pass_new=addslashes(trim($_POST["pass_new"]));
        if(valid_pass($pass_new)) {
            $query = "SELECT ID_HS FROM hocsinh WHERE ID_HS='$hsID' AND password='" . md5($pass_old) . "'";
            $result = mysqli_query($db, $query);
            if (mysqli_num_rows($result) != 0) {
                $query = "UPDATE hocsinh SET password='" . md5($pass_new) . "' WHERE ID_HS='$hsID'";
                mysqli_query($db, $query);
                add_log($hsID,"Đổi mật khẩu thành $pass_new","doi-mat-khau");
                echo $pass_new;
            } else {
                echo "none";
            }
        } else {
            echo"fuck";
        }
    }

    if(isset($_POST["pass_check"])) {
        if(isset($_SESSION["cmt"])) {
            $cmt=$_SESSION["cmt"];
            $query = "SELECT ID_HS FROM hocsinh WHERE ID_HS='$hsID' AND password='" . md5($cmt) . "'";
            $result = mysqli_query($db, $query);
            if (mysqli_num_rows($result) != 0) {
                return "ok";
            } else {
                return "no";
            }
        } else {
            return "none";
        }
    }
	
	require("../model/close_db.php");
	ob_end_flush();
?>