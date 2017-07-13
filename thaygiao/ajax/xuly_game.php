<?php
	ob_start();
	session_start();
    ini_set('max_execution_time', 300);
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];

    if(isset($_POST["id"])) {
        $idn=$_POST["id"];
        $query="SELECT COUNT(ID_STT) AS dem FROM hocvien_info WHERE ID_N='$idn'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        echo $data["dem"];
    }

    if(isset($_POST["id3"])) {
        $idn=$_POST["id3"];
        $query="SELECT COUNT(ID_STT) AS dem FROM list_group WHERE ID_N='$idn'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        echo $data["dem"];
    }

    if(isset($_POST["hvID"]) && isset($_POST["status"])) {
        $hvID=$_POST["hvID"];
        $status=$_POST["status"];
        $query="UPDATE hocvien_info SET status='$status' WHERE ID_STT='$hvID'";
        mysqli_query($db,$query);
    }

    if(isset($_POST["hvID"]) && isset($_POST["status_done"])) {
        $hvID=$_POST["hvID"];
        $status=$_POST["status_done"];
        $query="UPDATE hocvien_info SET status_done='$status' WHERE ID_STT='$hvID'";
        mysqli_query($db,$query);
    }

    if(isset($_POST["id2"])) {
        $id = $_POST["id2"];
        delete_hocvien($id);
        echo "ok";
    }

    if(isset($_POST["gameID"]) && isset($_POST["lop"])) {
        $gameID=$_POST["gameID"];
        $lop=addslashes($_POST["lop"]);
        $query="UPDATE options SET note2='$lop' WHERE ID_O='$gameID'";
        mysqli_query($db,$query);
    }

    if(isset($_POST["gameID"]) && isset($_POST["status"])) {
        $gameID=$_POST["gameID"];
        $status=addslashes($_POST["status"]);
        $query="UPDATE options SET note='$status' WHERE ID_O='$gameID'";
        mysqli_query($db,$query);
    }

    if(isset($_POST["moi_note"]) && isset($_POST["moi_id"])) {
        $note=addslashes($_POST["moi_note"]);
        $id=$_POST["moi_id"];
        $query="UPDATE hocvien_info SET note='$note' WHERE ID_STT='$id'";
        mysqli_query($db,$query);
    }

    if(isset($_POST["num_sl"]) && isset($_POST["n_id"]) && isset($_POST["ngay"])) {
        $num=addslashes($_POST["num_sl"]);
        $nID=addslashes($_POST["n_id"]);
        $ngay=addslashes($_POST["ngay"]);
        add_options2($num, "da-phat-ve", $nID, $ngay);
    }

    if(isset($_POST["ngay_sl"]) && isset($_POST["ngay"])) {
        $num=addslashes($_POST["ngay_sl"]);
        $ngay=addslashes($_POST["ngay"]);
        add_options2($num, "sl-buoi-phat-ve", "", $ngay);
    }

    if(isset($_POST["text"]) && isset($_POST["hsid"]) && isset($_POST["sttID"])) {
        $text=addslashes($_POST["text"]);
        $hsID=$_POST["hsid"];
        $sttID=$_POST["sttID"];
        $query="SELECT note FROM hocvien_info WHERE ID_STT='$sttID'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        $query="UPDATE hocvien_info SET note='".$data["note"]."<br />".$text."' WHERE ID_STT='$sttID'";
        mysqli_query($db,$query);
        add_thong_bao_hs($hsID, $sttID, $text, "push-noti", get_mon_first_hs($hsID, $monID));
    }

    if(isset($_POST["check"]) && isset($_POST["sttID"])) {
        $check=$_POST["check"];
        $sttID=$_POST["sttID"];
        $query="UPDATE hocvien_info SET diemdanh='$check' WHERE ID_STT='$sttID'";
        mysqli_query($db,$query);
    }

    require("../../model/close_db.php");
	ob_end_flush();
?>