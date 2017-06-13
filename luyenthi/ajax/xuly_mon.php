<?php
    ob_start();
    session_start();
    require_once "../model/model.php";
    require_once "../access_hocsinh.php";
    $me = md5("1241996");

    if(isset($_POST["lmID"]) && is_numeric($_POST["lmID"]) && isset($_SESSION["my_id"])) {
        $lmID = $_POST["lmID"];
        $ID = $_SESSION["my_id"];
        $db = new Hoc_Sinh();
        if($db->getCheckHocSinh($ID,$lmID)) {
            $_SESSION["my_mon"] = $lmID;
        }
    }

    if(isset($_POST["idCD"]) && isset($_POST["lmID"])) {
        $idCD = $_POST["idCD"];
        $lmID = $_POST["lmID"];
        $db = new De_Thi();
        $result = $db->getNhomDeThiAllow($lmID, $idCD);
        if($result->num_rows != 0) {
            $stt = 1;
            while ($data = $result->fetch_assoc()) {
                echo"<tr>
                    <td class='text-center'>$stt</td>
                    <td class='text-center'>".formatDateTime($data["ngay"])."</td>
                    <td class='text-center'>$data[mota]</td>
                    <td class='text-center'>
                        <button type='button' class='btn btn-primary btn-sm bg-primary-400 chon-de' data-deID='$data[ID_DE]'>Chọn</button>
                    </td>
                </tr>";
                $stt++;
            }
        } else {
            echo"<tr>
                <td colspan='4' class='text-center'>Hiện không có đề chuyên đề nào!</td>
            </tr>";
        }
    }

    ob_end_flush();
?>

