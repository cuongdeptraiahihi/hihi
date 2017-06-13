<?php
	ob_start();
	session_start();
    ini_set('max_execution_time', 900);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    require_once("vendor/autoload.php");
    if(isset($_GET["lm"]) && is_numeric($_GET["lm"])) {
        $lmID=$_GET["lm"];
    } else {
        $lmID=0;
    }
    $lmID=$_SESSION["lmID"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>DANH BẠ</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.fa {font-size:1.375em !important;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
		    $("#update-danh-ba").click(function () {
                if(confirm("Bạn có chắc chắn, sẽ mất nhiều thời gian!")) {
                    return true;
                } else {
                    return false;
                }
            });

            $("#cap-quyen").click(function () {
                if(confirm("Bạn có chắc chắn, bạn sẽ được hỏi về quyền truy cập danh bạn tài khoản Google!")) {
                    window.open("http://localhost/www/TDUONG/admin/cap-quyen/","_blank");
                } else {
                    return false;
                }
            });
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>

            <?php
//            echo json_encode(rapidweb\googlecontacts\helpers\GoogleHelper::getAccessToken(rapidweb\googlecontacts\helpers\GoogleHelper::getClient()));
            $contacts = rapidweb\googlecontacts\factories\ContactFactory::getAll();

            $array_var = json_encode($contacts);
//            echo $array_var;
            $data=json_decode($array_var, true);
            $n=count($data);
            ?>
            
            <div id="MAIN">

                <div id="main-mid">
                	<h2>DANH BẠ</h2>
                	<div>
                    	<div class="status">
                            <form action="http://localhost/www/TDUONG/admin/danh-ba/<?php echo $lmID; ?>/" method="post">
                                <table class="table table3">
                                    <tr>
                                        <th style="border: none;" colspan="3">
                                            <input class="submit" type="submit" name="update-google" id="update-danh-ba" value="Tạo mới trên Google" />
                                            <input class="submit" type="button" id="cap-quyen" value="Cấp quyền cho Bgo" />
                                        </th>
                                    </tr>
                                    <tr style="background:#3E606F;">
                                        <th style="width:5%;"><span>STT</span></th>
                                        <th style="width:20%;"><span>Họ tên</span></th>
                                        <th style="width:15%;"><span>Số điện thoại</span></th>
                                    </tr>
                                    <?php
                                        $names=",'0'";
                                        $string=",'0'";
                                        $update_arr = array();
//                                    $check = false;
                                        for($i=0;$i<5;$i++) {

                                            $temp=explode("-",$data[$i]["name"]);
                                            if(isset($temp[1])) {
                                                $me = $temp[0] . "-" . $temp[1];
                                            } else {
                                                $me = $temp[0];
                                            }
                                            if(!valid_maso($me)) {
                                                continue;
                                            }
//                                            if($me == "99-0405") {
//                                                $check = true;
//                                            }
//                                            if(!$check) {
//                                                continue;
//                                            }
//                                            $query2="SELECT avata FROM hocsinh WHERE cmt='".$temp[0] . "-" . $temp[1]."'";
//                                            $result2=mysqli_query($db,$query2);
//                                            $data2=mysqli_fetch_assoc($result2);
                                            $names .= ",'$me'";

//                                            add_google_contact_url($data[$i]["name"],$data[$i]["selfURL"]);

//                                            $update_arr[$data[$i]["name"]] = $data[$i]["selfURL"];

                                            if(isset($data[$i]["phoneNumber"][0]["type"])) {
                                                $phone = format_google_phone($data[$i]["phoneNumber"][0]["number"]);
                                            } else {
                                                $phone = "cc";
                                            }
                                            if($phone=="cc") {
                                                continue;
                                            }
//                                            $result2=get_hs_by_sdt($phone1,$phone2,$lmID);
//                                            $data2=mysqli_fetch_assoc($result2);
//                                            if(!isset($data2["cmt"]) && !valid_id($data2["cmt"])) {
//                                                if(stripos($string,$data2["cmt"])===false) {
//                                                    $string .= ",'$me'";
//                                                }
//                                            } else {
//                                                echo "<tr>
//                                                    <td class='hidden'><span>" . ($i + 1) . "</span></td>
//                                                    <td><span>" . $data[$i]["name"] . "</span></td>
//                                                    <td><span>" . format_mobile_click($phone1) . " " . format_mobile_click($phone2) . "</span></td>
//                                                    <td><span>$data2[cmt]</span></td>
//                                                    <td><span>$data2[fullname]</span></td>
//                                                </tr>";
//                                            }
//                                            rapidweb\googlecontacts\factories\ContactFactory::getPhoto($data[$i]["photoURL"])
                                            echo "<tr>
                                                    <td><span>" . ($i + 1) . "</span></td>
                                                    <td><span>" . $data[$i]["name"] . "</span></td>
                                                    <td><span>" . format_mobile_click($phone) . "</span></td>
                                                </tr>";
//                                            echo "(".$temp[0] . "-" . $temp[1].")".rapidweb\googlecontacts\factories\ContactFactory::updatePhoto($data[$i]["photoURL"], "../hocsinh/avata/".$data2["avata"]);
//                                            <td style='text-align:left;padding-left:15px;line-height:22px;'><span>SĐT: ".format_mobile_click($data2["sdt"])."<br />Bố: ".format_mobile_click($data2["sdt_bo"])."<br />Mẹ: ".format_mobile_click($data2["sdt_me"])."</span></td>
                                        }
//                                        echo rapidweb\googlecontacts\factories\ContactFactory::getPhoto($data[1]["photoURL"]);
                                    ?>
                                </table>
                            </form>
                        </div>
                    </div>
               	</div>
            
            </div>
            <?php
            if(isset($_POST["update-google"])) {
                $content = "";

//                $content .= "UPDATE:-------------------------------\n";
//                $query2 = "SELECT h.ID_HS,h.cmt,h.sdt,h.sdt_bo,h.sdt_me FROM hocsinh AS h
//                INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID'
//                WHERE h.cmt IN (" . substr($string, 1) . ") AND ((h.sdt!='' AND h.sdt!='X') OR (h.sdt_bo!='' AND h.sdt_bo!='X') OR (h.sdt_me!='' AND h.sdt_me!='X')) AND h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID')
//                ORDER BY h.cmt ASC";
//                $result2 = mysqli_query($db, $query2);
//                while ($data2 = mysqli_fetch_assoc($result2)) {
//                    $contact = rapidweb\googlecontacts\factories\ContactFactory::getBySelfURL($update_arr[$data2["cmt"] . "-HS"]);
//                    $contact->name = $data2["cmt"] . "-HS";
//                    $contact->phoneNumber = $data2["sdt"];
//                    $contactAfterUpdate = rapidweb\googlecontacts\factories\ContactFactory::submitUpdates($contact);
//                    $content .= json_encode($contactAfterUpdate) . "\n";
//
//                    $contact = rapidweb\googlecontacts\factories\ContactFactory::getBySelfURL($update_arr[$data2["cmt"] . "-Bố"]);
//                    $contact->name = $data2["cmt"] . "-Bố";
//                    $contact->phoneNumber = $data2["sdt_bo"];
//                    $contactAfterUpdate = rapidweb\googlecontacts\factories\ContactFactory::submitUpdates($contact);
//                    $content .= json_encode($contactAfterUpdate) . "\n";
//
//                    $contact = rapidweb\googlecontacts\factories\ContactFactory::getBySelfURL($update_arr[$data2["cmt"] . "-Mẹ"]);
//                    $contact->name = $data2["cmt"] . "-Mẹ";
//                    $contact->phoneNumber = $data2["sdt_me"];
//                    $contactAfterUpdate = rapidweb\googlecontacts\factories\ContactFactory::submitUpdates($contact);
//                    $content .= json_encode($contactAfterUpdate) . "\n";
//                }

                $content.="CREATE:-------------------------------\n";
                $query2="SELECT h.ID_HS,h.cmt,h.sdt,h.sdt_bo,h.sdt_me FROM hocsinh AS h 
                INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
                WHERE h.cmt NOT IN (".substr($names,1).") AND ((h.sdt!='' AND h.sdt!='X') OR (h.sdt_bo!='' AND h.sdt_bo!='X') OR (h.sdt_me!='' AND h.sdt_me!='X')) AND h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID')
                ORDER BY h.cmt ASC";
                $result2=mysqli_query($db,$query2);
                while($data2=mysqli_fetch_assoc($result2)) {
                    $name=$data2["cmt"]."-HS";
                    $phoneNumber=$data2["sdt"];
                    $newContact=rapidweb\googlecontacts\factories\ContactFactory::create($name, $phoneNumber, "");
                    $content.=json_encode($newContact)."\n";

                    $name=$data2["cmt"]."-Bố";
                    $phoneNumber=$data2["sdt_bo"];
                    $newContact=rapidweb\googlecontacts\factories\ContactFactory::create($name, $phoneNumber, "");
                    $content.=json_encode($newContact)."\n";

                    $name=$data2["cmt"]."-Mẹ";
                    $phoneNumber=$data2["sdt_me"];
                    $newContact=rapidweb\googlecontacts\factories\ContactFactory::create($name, $phoneNumber, "");
                    $content.=json_encode($newContact)."\n";
                }

                file_put_contents('google_contacts_result.txt', $content);
                header("location:http://localhost/www/TDUONG/admin/danh-ba/$lmID/");
                exit();
            }
            ?>
        </div>
        
    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>