<?php
    require_once "model/model.php";
    require_once "access_hocsinh.php";
    $me = md5("123456");
    if(isset($_FILES) && count($_FILES) > 0 && isset($_POST["cID"]) && isset($_POST["hsID"])) {
        $cID = $_POST["cID"];
        $hsID = $_POST["hsID"];
        if(validId($hsID) && validId($cID)) {
            $type = "html";
            $xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
            $check = false;
            foreach ($_FILES as $file) {
                $n = $file['name'];
                $s = $file['size'];
                if (!$n) continue;

                $temp = explode(".", $n);
                $type = strtolower(end($temp));
                if (count($temp) >= 2 && ($type == "png" || $type == "jpg" || $type == "jpeg")) {
                    if ($s <= 2000000) {
                        move_uploaded_file($file["tmp_name"], "upload/cauhoi/" . $n);
                        $newname = $cID . "-" . $hsID . "." . $type;
                        rename("upload/cauhoi/" . $n, "upload/cauhoi/" . $newname);
                        (new Binh_Luan())->addBinhLuan($cID, $hsID, $newname, "image");
                        echo "http://localhost/www/TDUONG/luyenthi/upload/cauhoi/" . $newname;
                        $check = true;
                        break;
                    }
                }
            }
            if (!$check) {
                echo "size-type";
            }
        } else {
            echo "id";
        }
    } else {
        echo "none";
    }
?>