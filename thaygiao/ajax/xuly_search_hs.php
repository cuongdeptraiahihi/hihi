<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
require("../../model/model.php");
require("../access_admin.php");
	
	if (isset($_GET["search"])) {
		$search=unicode_convert($_GET["search"]);
		$result=search_hocsinh($search);
		if(mysqli_num_rows($result)==0) {
			echo"<li><p>Không có học sinh này!</p></li>";
		} else {
			while($data=mysqli_fetch_assoc($result)) {
				echo"<li><a href='javascript:void(0)' data-hsID='$data[ID_HS]' data-cmt='$data[cmt]'>$data[cmt] - $data[fullname]<span>".format_dateup($data["birth"])."</span></a></li>";
			}
		}
	}
	
	if (isset($_GET["search2"])) {
		$search=unicode_convert($_GET["search2"]);
		$result=search_hocsinh($search);
		if(mysqli_num_rows($result)==0) {
			echo"<li><p>Không có học sinh này!</p></li>";
		} else {
			while($data=mysqli_fetch_assoc($result)) {
				echo"<li><a href='http://localhost/www/TDUONG/thaygiao/thuong/$data[ID_HS]/' data-hsID='$data[ID_HS]'>$data[cmt] - $data[fullname]</a></li>";
			}
		}
	}
	
	if (isset($_GET["search3"])) {
		$search=unicode_convert($_GET["search3"]);
		$result=search_hocsinh($search);
		if(mysqli_num_rows($result)==0) {
			echo"<li><p>Không có học sinh này!</p></li>";
		} else {
			while($data=mysqli_fetch_assoc($result)) {
				echo"<li><a href='http://localhost/www/TDUONG/thaygiao/phat/$data[ID_HS]/' data-hsID='$data[ID_HS]'>$data[cmt] - $data[fullname]</a></li>";
			}
		}
	}
	
	if (isset($_GET["search_short"]) && isset($_GET["lmID"])) {
		$search=$_GET["search_short"];
		$lmID=$_GET["lmID"];
		$result=search_hocsinh_cmt($search, $lmID);
		if(mysqli_num_rows($result)==0) {
			echo"<li><p>Không có học sinh này!</p></li>";
		} else {
			while($data=mysqli_fetch_assoc($result)) {
				echo"<li><a href='javascript:void(0)' data-hsID='$data[ID_HS]' data-cmt='$data[cmt]'>$data[cmt] - $data[fullname] ($data[vantay])</a></li>";
			}
		}
	}

    if (isset($_GET["search_sdt"])) {
        $search=$_GET["search_sdt"];
        $result=search_hocsinh_sdt($search);
        if(mysqli_num_rows($result)==0) {
            echo"<li><p>Không có học sinh này!</p></li>";
        } else {
            while($data=mysqli_fetch_assoc($result)) {
                if(stripos($data["sdt"],$search) === false) {
                    if(stripos($data["sdt_bo"],$search) === false) {
                        if(stripos($data["sdt_me"],$search) === false) {
                            echo"<li><p>Không có học sinh này!</p></li>";
                        } else {
                            echo"<li><a href='javascript:void(0)' data-hsID='$data[ID_HS]' data-cmt='$data[cmt]'>$data[cmt] - $data[fullname] ($data[vantay]) - Mẹ</a></li>";
                        }
                    } else {
                        echo"<li><a href='javascript:void(0)' data-hsID='$data[ID_HS]' data-cmt='$data[cmt]'>$data[cmt] - $data[fullname] ($data[vantay]) - Bố</a></li>";
                    }
                } else {
                    echo"<li><a href='javascript:void(0)' data-hsID='$data[ID_HS]' data-cmt='$data[cmt]'>$data[cmt] - $data[fullname] ($data[vantay]) - HS</a></li>";
                }
            }
        }
    }
	
	if (isset($_GET["search_full"]) && isset($_GET["monID"])) {
		$search=$_GET["search_full"];
		$monID=$_GET["monID"];
		$result=search_hocsinh_full($search, $monID);
		if(mysqli_num_rows($result)==0) {
			echo"<li><p>Không có học sinh này!</p></li>";
		} else {
			while($data=mysqli_fetch_assoc($result)) {
				echo"<li><a href='javascript:void(0)' data-hsID='$data[ID_HS]' data-cmt='$data[cmt]'>$data[cmt] - $data[fullname] ($data[vantay])</a></li>";
			}
		}
	}
	
	if (isset($_GET["search_van"]) && is_numeric($_GET["search_van"])) {
		$search=$_GET["search_van"];
		$result=search_hocsinh_van($search);
		if(mysqli_num_rows($result)==0) {
			echo"<li><p>Không có học sinh này!</p></li>";
		} else {
			while($data=mysqli_fetch_assoc($result)) {
				echo"<li><a href='javascript:void(0)' data-hsID='$data[ID_HS]' data-cmt='$data[cmt]'>$data[cmt] - $data[fullname]</a></li>";
			}
		}
	}
	
	if (isset($_GET["search_name"]) && $_GET["search_name"]!="") {
		$search=unicode_convert($_GET["search_name"]);
		$result=search_hocsinh_name($search);
		if(mysqli_num_rows($result)==0) {
			echo"<li><p>Không có học sinh này!</p></li>";
		} else {
			while($data=mysqli_fetch_assoc($result)) {
				echo"<li><a href='javascript:void(0)' data-hsID='$data[ID_HS]' data-cmt='$data[cmt]'>$data[cmt] - $data[fullname]</a></li>";
			}
		}
	}
	
	if (isset($_GET["search_truong"]) && $_GET["search_truong"]!="") {
		$search=unicode_convert($_GET["search_truong"]);
		$result=search_truong($search);
		if(mysqli_num_rows($result)==0) {
			echo"<li><p>Không có trường này!</p></li>";
		} else {
			while($data=mysqli_fetch_assoc($result)) {
				echo"<li><a href='javascript:void(0)' data-truong='$data[ID_T]'>$data[name]</a></li>";
			}
		}
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>