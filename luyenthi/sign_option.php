<?php
    ob_start();
    session_start();
    require_once("model/model.php");

    $me = md5("123456");
    $temp_code = md5("1241996");

    $check = false;
    $ip = $_SERVER['REMOTE_ADDR'];
    if(isset($_GET["code"])) {
        $code=decodeData(addslashes($_GET["code"]),$temp_code);
        if(stripos($code,"|") === false) {

        } else {
            $temp = explode("|",$code);
            if(count($temp) >= 3) {
                $hsID = $temp[0];
                $cmt = $temp[1];
                $lmID = $temp[2];
                $db = new Hoc_Sinh();
                $password = $db->checkHocSinhCode($hsID, $cmt);
                if($password) {
                    $result = $db->checkHocSinhDetail($cmt,$password);
                    if($result->num_rows != 0) {
                        $data = $result->fetch_assoc();
                        $data["ID_LM"] = $lmID;
                        $db->login($data);
                        if(isset($temp[3])) {
                            $check = true;
                            $db2 = new De_Thi();
                            $nID = $temp[3];
                            (new Thong_Ke())->cleanKetQuaNhomDe($hsID, $nID);
                            $result2 = $db2->getNhomDeById($nID);
                            $data2 = $result2->fetch_assoc();
                            $deID = $db2->getRandDeThiByNhom($nID, NULL);
                            $deID = (new Vao_Thi())->addHocSinhInDe($hsID, $deID, $nID, "in");
                            header("location:http://localhost/www/TDUONG/luyenthi/xem-truoc/" . encodeData($deID, $me) . "/$data2[code]/");
                            exit();
                        }
                    }
                }
            }
        }
    }

    if(!$check) {
        header("location:http://localhost/www/TDUONG/luyenthi/trang-chu/4/");
        exit();
    }

?>
