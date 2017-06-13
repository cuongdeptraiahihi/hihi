<?php
    session_start();
    require_once("../model/model.php");
    require_once("access_admin.php");

    if(isset($_GET["deID"]) && is_numeric($_GET["deID"])) {
        $deID = $_GET["deID"];
    } else {
        $deID = 0;
    }
    $db = new De_Thi();
    $db2 = new Hoc_Sinh();

    $result = $db->getDeThiById($deID);
    $data = $result->fetch_assoc();

    $content = "";
    $result1 = $db2->getAllHocSinh($data["ID_LM"]);
    while($data1 = $result1->fetch_assoc()) {
        $content .= ",('$data1[ID_HS]','$data[nhom]','Đã có bài trắc nghiệm $data[mota]. Các e có 5 ngày để hoàn thành. Nếu không sẽ bị khóa chức năng trắc nghiệm!','mo-khoa','$data[ID_LM]',now(),'small','new')";
    }
    if($content != "") {
        $content = substr($content,1);
        $db2->addThongBao($content);
    }

    header("location:http://localhost/www/TDUONG/luyenthi/admin/trang-chu/");
    exit();


?>