<?php
	ob_start();
	//session_start();
	require("../model/open_db.php");
	require("../model/model.php");
	session_start();
	require_once("../access_hocsinh.php");
	$hsID=$_SESSION["ID_HS"];
	$monID=$_SESSION["mon"];
    $code=$_SESSION["code"];
    $lmID=$_SESSION["lmID"];

    $result0=get_id_group_hs($hsID);
    if(mysqli_num_rows($result0)!=0) {
        $data0 = mysqli_fetch_assoc($result0);
        $level=$data0["level"];
        $nID=$data0["ID_N"];
        $dubi=check_nhom_du_bi($nID);
    } else {
        $level=$nID=0;
        $dubi=false;
    }

	if(isset($_POST["nID_list"])) {
		$nID=$_POST["nID_list"];
        echo"<table id='list'>
        <tr id='table-head' class='back tr-big'>
            <th style='width: 5%;'><span>STT</span></th>
            <th><span>Thành viên</span></th>
            <th><span>Mã số</span></th>
            <th><span>SĐT</span></th>
            <th><span>Facebook</span></th>
            <th><span></span></th>
        </tr>";

        $stt=1;
        $query5="SELECT h.ID_HS,h.cmt,h.fullname,h.sdt,h.facebook,l.level FROM list_group AS l
        INNER JOIN hocsinh AS h ON h.ID_HS=l.ID_HS
        WHERE l.ID_N='$nID'
        ORDER BY l.level ASC";
        $result5=mysqli_query($db,$query5);
        while($data5=mysqli_fetch_assoc($result5)) {
            $facebook=formatFacebook($data5["facebook"]);
            if($facebook=="#")
                $show="";
            else $show="Xem";
            echo "<tr class='tr-me back tr-fixed'>
            <td><span>$stt</span></td>
            <td><span>$data5[fullname]</span></td>
            <td><span>$data5[cmt]</span></td>
            <td><span>$data5[sdt]</span></td>
            <td><a href='$facebook' style='color:#FFF;text-decoration: underline;' target='_blank'>$show</a></td>";
            if($data5["level"]==1) {
                echo"<td><span>Trưởng nhóm</span></td>";
            } else if($level==1 && $data5["ID_HS"]!=$hsID) {
                echo"<td>
                    <button class='submit kick-submit' data-hsID='".encode_data($data5["ID_HS"],$code)."'>Kick</button>
                    <button class='submit captain-submit' data-hsID='".encode_data($data5["ID_HS"],$code)."'>Captain</button>
                </td>";
            } else {
                echo"<td><span></span></td>";
            }
            echo"</tr>";
            $stt++;
        }
    echo"</table>";
	}

    if(isset($_POST["id"])) {
        $id=$_POST["id"];
        if(valid_id($id)) {
            delete_hocvien($id);
            echo"ok";
        }
    }

    if (isset($_GET["search_truong"]) && $_GET["search_truong"]!="") {
        $search=unicode_convert($_GET["search_truong"]);
        $result=search_truong($search);
        if(mysqli_num_rows($result)==0) {
            echo"<li style='color:white;'><p>Không có trường này!</p></li>";
        } else {
            while($data=mysqli_fetch_assoc($result)) {
                echo"<li><a style='color:white;' href='javascript:void(0)' data-truong='$data[ID_T]'>$data[name]</a></li>";
            }
        }
    }

    if(isset($_POST["id2"])) {
        $idn=$_POST["id2"];
        $query="SELECT COUNT(ID_STT) AS dem FROM hocvien_info WHERE ID_N='$idn'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        echo $data["dem"];
    }

    if(isset($_POST["link-fb"])) {
        $linkfb=addslashes($_POST["link-fb"]);
        if(check_hs_tuyen_fb($linkfb)) {
            echo "Học sinh này đã được mời!";
        } else {
            echo "Học sinh này chưa được mời!";
        }
    }

    if(isset($_POST["link-sdt"]) && is_numeric($_POST["link-sdt"])) {
        $linksdt=str_replace(array("+84","+"),"",addslashes($_POST["link-sdt"]));
        if(check_hs_tuyen_sdt($linksdt)) {
            echo "Học sinh này đã được mời!";
        } else {
            echo "Học sinh này chưa được mời!";
        }
    }

    require("../model/close_db.php");
	ob_end_flush();
?>