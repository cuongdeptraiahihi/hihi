<?php

	date_default_timezone_set("Asia/Ho_Chi_Minh");
	require_once("PHPMailer/PHPMailerAutoload.php");
    require_once("Base32.php");
	use Base32\Base32;
	//ini_set('max_execution_time', 120);

    // Đã check
	function login($cmt, $password) {
		
		global $db;
	
		$query="SELECT ID_HS,cmt,password,fullname,avata,truong,lop,login_times FROM hocsinh WHERE cmt='$cmt' AND password='$password'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	function login_face($hsID) {

        global $db;

        $query="SELECT ID_HS,cmt,password,fullname,avata,truong,lop,login_times FROM hocsinh WHERE ID_HS='$hsID'";

        $result=mysqli_query($db,$query);

        return $result;
    }

    function update_login_times($hsID, $times) {
        global $db;
        $query="UPDATE hocsinh SET login_times='$times' WHERE ID_HS='$hsID'";
        mysqli_query($db,$query);
    }

    function get_login_times($hsID) {
        global $db;
        $query="SELECT login_times FROM hocsinh WHERE ID_HS='$hsID'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        return $data["login_times"];
    }

	// Đã check
	function get_login($result, $who) {
		
		$data=mysqli_fetch_assoc($result);
		
		$user_browser = $_SERVER['HTTP_USER_AGENT'];
        $temp_code=md5("1241996");

        if(isset($_COOKIE["cmt"])) {
            if($_COOKIE["cmt"]!=encode_data($data["cmt"],$temp_code)) {
                $cmt = decode_data($_COOKIE["cmt"],$temp_code);
                add_log($data["ID_HS"],"$data[cmt] - $data[fullname] đã được đăng nhập từ tài khoản $cmt, IP: ".$_SERVER["REMOTE_ADDR"].", ".$user_browser,"login");
            }
        }

		$_SESSION["ID_HS"]=preg_replace("/[^0-9]+/", "", $data["ID_HS"]);
		$_SESSION["cmt"]=$data["cmt"];
		$_SESSION["fullname"]=$data["fullname"];
        $_SESSION["ava"]=$data["avata"];
		$_SESSION["lop"]=$data["lop"];
		$_SESSION["truong"]=$data["truong"];
		
		$code=md5(rand_pass(16));
		$_SESSION["code"]=$code;
		$login=hash("sha512",$data["password"].$code);
		$_SESSION["login"]=$login;
        $_SESSION["show_tien"]=0;
        $_SESSION["who"]=$who;

        setcookie("cmt", "", time() - 86400*30, "/");
        setcookie("cmt", encode_data($data["cmt"],$temp_code), time() + 86400*30,"/");

		//update_code_hs($data["ID_HS"],$code);

		if($who==1) {
			add_log($data["ID_HS"],"$data[cmt] - $data[fullname] đã đăng nhập","login");
            $_SESSION["test"]=0;
		} else {
			add_log($data["ID_HS"],"Admin/Phụ huynh: $data[cmt] - $data[fullname] đã đăng nhập","login");
            $_SESSION["test"]=1;
		}

		return $data["ID_HS"];
	}
	
	function login_admin($name, $password) {
		
		global $db;
	
		$query="SELECT * FROM admin WHERE username='$name' AND password='$password' AND level='1' AND note='boss'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function check_admin($id, $name) {
		
		global $db;
		
		$query="SELECT * FROM admin WHERE ID='$id' AND username='$name' AND level='1' AND note='boss'";
			
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==1) {
			return true;
		} else {
			return false;
		}
	}
	
	function login_thaygiao($name, $password, $monID) {
	
		global $db;
	
		if(check_has_mon($monID)) {
			$query="SELECT * FROM admin WHERE username='$name' AND password='$password' AND level=2 AND note='$monID'";
			
			$result=mysqli_query($db,$query);
			
			return $result;
		} else {
			return NULL;
		}
	}
	
	function check_thaygiao_mon($id, $name, $monID) {
		
		global $db;
		
		$query="SELECT * FROM admin WHERE ID='$id' AND username='$name' AND level=2 AND note='$monID'";
			
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==1) {
			return true;
		} else {
			return false;
		}
	}
	
	function login_trogiang($name, $password) {
	
		global $db;
	
		$query="SELECT * FROM admin WHERE username='$name' AND password='$password' AND level='3'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function check_trogiang($id, $name) {
		
		global $db;
		
		$query="SELECT * FROM admin WHERE ID='$id' AND username='$name' AND level='3'";
			
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==1) {
			return true;
		} else {
			return false;
		}
	}

	// Đã check
	function login_phuhuynh($cmt, $sdt) {
		
		global $db;
		
		$query="SELECT ID_HS,cmt,password,fullname,avata,truong,lop,login_times FROM hocsinh WHERE cmt='$cmt' AND (sdt_bo='$sdt' OR sdt_me='$sdt')";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

    // Đã check
	function get_mon_first_hs($hsID, $monID) {
		
		global $db;
	
		$query="SELECT ID_LM FROM hocsinh_mon WHERE ID_HS='$hsID' AND ID_LM IN (SELECT ID_LM FROM lop_mon WHERE ID_MON='$monID') ORDER BY ID_STT ASC LIMIT 1";
		
		$result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["ID_LM"];
	}

	// Đã check
	function get_all_cahoc($cahoc_string) {
		
		global $db;
	
		$query="SELECT $cahoc_string.*,cagio.*,lop.name FROM $cahoc_string 
			INNER JOIN cagio ON cagio.ID_GIO=$cahoc_string.ID_GIO 
			INNER JOIN lop ON lop.ID_LOP=cagio.lop 
			ORDER BY $cahoc_string.thu ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_all_cahoc_lop($lmID,$monID) {
		
		global $db;

		$query="SELECT c.*,g.* FROM cahoc AS c 
			INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='$lmID' AND g.ID_MON='$monID'
			ORDER BY c.thu ASC,g.buoi ASC,g.thutu ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function unlock_all_ca_hoc($hsID,$lmID) {
		
		global $db;
	
		$query="SELECT ID_CA FROM cahoc WHERE cum IN (SELECT ID_CUM FROM cum WHERE link='0' AND ID_LM='$lmID')";
		
		$result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            turn_on_ca($hsID,$data["ID_CA"]);
        }
		
		return $result;
	}

	// Đã check
	function get_ca_base_thu2($current_thu, $lmID, $monID) {
		
		global $db;

		$query="SELECT c.ID_CA,c.cum,g.gio,u.link,g.buoi AS myBUOI,g.thutu AS myTHUTU FROM cahoc AS c 
			INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='$lmID' AND g.ID_MON='$monID' 
			INNER JOIN cum AS u ON u.ID_CUM=c.cum AND u.ID_LM='$lmID' AND u.ID_MON='$monID' 
			WHERE c.thu='$current_thu' 
			UNION ALL 
			SELECT c2.ID_CA,c2.cum,g2.gio,u2.link,g2.buoi AS myBUOI,g2.thutu AS myTHUTU FROM cahoc AS c2 
			INNER JOIN cagio AS g2 ON g2.ID_GIO=c2.ID_GIO AND g2.ID_LM='$lmID' AND g2.ID_MON='$monID'
			INNER JOIN cum AS u2 ON u2.link=c2.cum AND u2.ID_LM='$lmID' AND u2.ID_MON='$monID'
			WHERE c2.thu='$current_thu' 
			ORDER BY myBUOI ASC,myTHUTU ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_cahoc_cum_lop($cum,$lmID,$monID) {
		
		global $db;
	
		$query="SELECT c.ID_CA,c.thu,c.cum,g.gio FROM cahoc AS c
			INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='$lmID' AND g.ID_MON='$monID' 
			WHERE c.cum='$cum'
			ORDER BY c.thu ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_cakt_mon($monID) {
	
		global $db;
	
		$query="SELECT c.ID_CA,c.thu,c.cum,g.gio,g.buoi,d.name FROM cahoc AS c 
		INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='0' AND g.ID_MON='$monID'
		INNER JOIN dia_diem AS d ON d.ID_DD=c.ID_DD ORDER BY g.buoi ASC, g.thutu ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
    function get_num_hs_ca_de($caID, $lmID, $de) {

        global $db;

        $query="SELECT COUNT(ID_STT) AS dem FROM ca_codinh WHERE ID_CA='$caID' AND ID_HS IN (SELECT ID_HS FROM hocsinh_mon WHERE ID_LM='$lmID' AND de='$de')";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        return $data["dem"];
    }

	// Đã check
	function get_num_hs_ca_codinh($caID) {
		
		global $db;
	
		$query="SELECT COUNT(ID_STT) AS dem FROM ca_codinh WHERE ID_CA='$caID'";
	
		$result=mysqli_query($db,$query);	
		$data=mysqli_fetch_assoc($result);
		return $data["dem"];
	}

    // Đã check
    function get_num_hs_ca_codinh2($caID,$lmID) {

        global $db;

        $query="SELECT COUNT(ID_STT) AS dem FROM ca_codinh WHERE ID_CA='$caID' AND ID_HS IN (SELECT ID_HS FROM hocsinh_mon WHERE ID_LM='$lmID')";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        return $data["dem"];
    }

	// Đã check
	function get_num_hs_ca_hientai($caID) {
		
		global $db;
		
		$query="SELECT COUNT(ID_STT) AS dem FROM ca_hientai WHERE ID_CA='$caID'";
	
		$result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        return $data["dem"];
	}

	// Đã check
    function get_num_hs_ca_max_hientai($cum) {

        global $db;

        $query="SELECT COUNT(ID_STT) AS dem FROM ca_hientai WHERE ID_CA IN (SELECT ID_CA FROM cahoc WHERE cum='$cum') GROUP BY ID_CA";

        $max=0;
        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            $max = $max > $data["dem"] ? $max : $data["dem"];
        }

        return $max;
    }

    // Đã check
    function get_num_hs_ca_max_codinh($cum) {

        global $db;

        $query="SELECT COUNT(ID_STT) AS dem FROM ca_codinh WHERE ID_CA IN (SELECT ID_CA FROM cahoc WHERE cum='$cum') GROUP BY ID_CA";

        $max=0;
        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            $max = $max > $data["dem"] ? $max : $data["dem"];
        }

        return $max;
    }

    // Đã check
    function get_now_ca_hs($hsID, $cum) {

        global $db;

        $query="SELECT ID_CA FROM ca_hientai WHERE ID_HS='$hsID' AND ID_CA IN (SELECT ID_CA FROM cahoc WHERE cum='$cum') LIMIT 1";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["ID_CA"];
    }

    // Đã check
    function get_num_tb_ca($cum, $monID) {

        global $db;

        $query0="SELECT ID_LM FROM cum WHERE ID_CUM='$cum' AND ID_MON='$monID'";
        $result0=mysqli_query($db,$query0);
        $data0=mysqli_fetch_assoc($result0);
        if($data0["ID_LM"]==0) {
            $dem=count_hs_mon($monID);
        } else {
            $dem=count_hs_mon_lop($data0["ID_LM"]);
        }

        $query="SELECT COUNT(ID_CA) AS dem FROM cahoc WHERE cum='$cum'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return ($dem / $data["dem"]) + 30;
    }

    // Đã check
	function check_ca_full_codinh($caID, $monID) {

//        $next_num=get_num_hs_ca_codinh($caID);
//        $cum=get_ca_cum($caID);
//        $now_num=get_num_hs_ca_codinh(get_now_ca_hs($hsID,$cum));

//        if($next_num <= $now_num) {
//            return "vang";
//        } else {
//            if($next_num <= get_num_tb_ca($cum,$monID))
//                return "vang";
//            else return "quatai";
//        }
        return "vang";
	}

	// Đã check
	function check_ca_full_tam($caID, $monID) {

//        $next_num=get_num_hs_ca_hientai($caID);
//        $cum=get_ca_cum($caID);
//        $now_num=get_num_hs_ca_hientai(get_now_ca_hs($hsID,$cum));

//        if($next_num <= $now_num) {
//            return "vang";
//        } else {
//            if($next_num <= get_num_tb_ca($cum,$monID))
//                return "vang";
//            else return "quatai";
//        }
        return "vang";
	}

	// Đã check
	function add_hs_to_ca_codinh($caID, $hsID, $cum) {
		
		global $db;
		
		$query2="DELETE FROM ca_hientai WHERE ID_HS='$hsID' AND cum='$cum'";
		mysqli_query($db,$query2);
		$query3="DELETE FROM ca_codinh WHERE ID_HS='$hsID' AND cum='$cum'";
		mysqli_query($db,$query3);
		$query4="INSERT INTO ca_codinh(ID_CA,ID_HS,cum)
								value('$caID','$hsID','$cum')";
		mysqli_query($db,$query4);
		$query5="INSERT INTO ca_hientai(ID_CA,ID_HS,cum)
							value('$caID','$hsID','$cum')";
		mysqli_query($db,$query5);
	}

	// Đã check
	function get_ca_cum($caID) {
		
		global $db;
		
		$query="SELECT cum FROM cahoc WHERE ID_CA='$caID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["cum"];
	}

	// Đã check
	function add_hs_to_ca_tam($caID, $hsID, $cum) {
		
		global $db;

		$query2="DELETE FROM ca_hientai WHERE ID_HS='$hsID' AND cum='$cum'";
		mysqli_query($db,$query2);
		$query3="INSERT INTO ca_hientai(ID_CA,ID_HS,cum)
                                value('$caID','$hsID','$cum')";
		mysqli_query($db,$query3);
	}

	// Đã check
	function delete_hs_ca_tam($caID, $hsID, $cum) {
		
		global $db;
		
		$query="DELETE FROM ca_hientai WHERE ID_CA='$caID' AND ID_HS='$hsID'";
		mysqli_query($db,$query);
		$query2="SELECT ID_CA FROM ca_codinh WHERE ID_HS='$hsID' AND cum='$cum'";
		$result2=mysqli_query($db,$query2);
		$data2=mysqli_fetch_assoc($result2);
		$query3="INSERT INTO ca_hientai(ID_CA,ID_HS,cum)
									value('$data2[ID_CA]','$hsID','$cum')";
		mysqli_query($db,$query3);
	}

	// Đã check
	function get_tien_hs($hsID) {
		
		global $db;
	
		$query="SELECT taikhoan FROM hocsinh WHERE ID_HS='$hsID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["taikhoan"];
	}

	// Đã check
	function update_tien_hs($hsID, $money) {
		
		global $db;
		
		$query="UPDATE hocsinh SET taikhoan='$money' WHERE ID_HS='$hsID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function tru_tien_hs($hsID, $money, $note, $string, $object) {
		
		global $db;
		
		$query="INSERT INTO tien_vao(ID_HS,price,note,string,object,date,date_dong)
								value('$hsID','$money','$note','$string','$object',now(),now())";
								
		mysqli_query($db,$query);
		
		$tien=get_tien_hs($hsID);
		$con=$tien-$money;
		update_tien_hs($hsID, $con);
	}

	// Đã check
	function cong_tien_hs($hsID, $money, $note, $string, $object) {
		
		global $db;
	
		$query="INSERT INTO tien_ra(ID_HS,price,note,string,object,date,date_dong)
								value('$hsID','$money','$note','$string','$object',now(),now())";
	
		mysqli_query($db,$query);
		
		$tien=get_tien_hs($hsID);
		$con=$tien+$money;
		update_tien_hs($hsID, $con);
	}

	// Đã check
	function tru_tien_hs2($hsID, $money, $note, $string, $object, $date, $date_dong) {
		
		global $db;
		
		$query="INSERT INTO tien_vao(ID_HS,price,note,string,object,date,date_dong)
								value('$hsID','$money','$note','$string','$object','$date','$date_dong')";
								
		mysqli_query($db,$query);
		
		$tien=get_tien_hs($hsID);
		$con=$tien-$money;
		update_tien_hs($hsID, $con);
	}

	// Đã check
	function cong_tien_hs2($hsID, $money, $note, $string, $object, $seri, $date, $date_dong) {
		
		global $db;
	
		$query="INSERT INTO tien_ra(ID_HS,price,note,string,object,code,date,date_dong)
								value('$hsID','$money','$note','$string','$object','$seri','$date','$date_dong')";
	
		mysqli_query($db,$query);
		
		$tien=get_tien_hs($hsID);
		$con=$tien+$money;
		update_tien_hs($hsID, $con);
	}

	// Đã check
	function delete_thuong($idRA) {
		
		global $db;
		
		$result=get_thuong_detail($idRA);
		$data=mysqli_fetch_assoc($result);
		
		$tien=get_tien_hs($data["ID_HS"]);
		$con=$tien-$data["price"];
		update_tien_hs($data["ID_HS"], $con);
		
		$query2="DELETE FROM tien_ra WHERE ID_RA='$idRA'";
		mysqli_query($db,$query2);
	}

	// Đã check
	function delete_phat($idVAO) {
		
		global $db;
		
		$result=get_phat_detail($idVAO);
		$data=mysqli_fetch_assoc($result);
		
		$tien=get_tien_hs($data["ID_HS"]);
		$con=$tien+$data["price"];
		update_tien_hs($data["ID_HS"], $con);
		
		$query2="DELETE FROM tien_vao WHERE ID_VAO='$idVAO'";
		mysqli_query($db,$query2);
	}

	function delete_phat_thuong($hsID,$string,$object) {

        global $db;

        $tien = get_tien_hs($hsID);

        $query="SELECT * FROM tien_vao WHERE ID_HS='$hsID' AND string='$string' AND object='$object'";
        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)!=0) {
            $data = mysqli_fetch_assoc($result);

            $tien+= $data["price"];

            $query2 = "DELETE FROM tien_vao WHERE ID_VAO='$data[ID_VAO]'";
            mysqli_query($db, $query2);
        }

        $query="SELECT * FROM tien_ra WHERE ID_HS='$hsID' AND string='$string' AND object='$object'";
        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)!=0) {
            $data = mysqli_fetch_assoc($result);

            $tien-= $data["price"];

            $query2 = "DELETE FROM tien_ra WHERE ID_RA='$data[ID_RA]'";
            mysqli_query($db, $query2);
        }

        update_tien_hs($hsID, $tien);
    }

    // Đã check
	function get_thuong_hs($hsID) {
		
		global $db;
	
		$query="SELECT SUM(price) AS tien FROM tien_ra WHERE ID_HS='$hsID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		if(!isset($data["tien"])) {
			return 0;
		} else {
			return $data["tien"];
		}
	}

    function get_thuong_hs_con($hsID) {

        global $db;

        $query="SELECT SUM(price) AS tien FROM tien_ra WHERE ID_HS='$hsID' AND string!='nap-tien'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        if(!isset($data["tien"])) {
            return 0;
        } else {
            return $data["tien"];
        }
    }

    function get_thuong_hs_con2($hsID) {

        global $db;

        $query="SELECT * FROM tien_ra WHERE ID_HS='$hsID' AND string!='nap-tien'";

        $result=mysqli_query($db,$query);

        return $result;
    }

    function get_da_nap_hs($hsID) {

        global $db;

        $query="SELECT SUM(price) AS tien FROM tien_ra WHERE ID_HS='$hsID' AND string='nap-tien'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        if(!isset($data["tien"])) {
            return 0;
        } else {
            return $data["tien"];
        }
    }

    function get_da_rut_hs($hsID) {

        global $db;

        $query="SELECT SUM(price) AS tien FROM tien_vao WHERE ID_HS='$hsID' AND string='rut-tien'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        if(!isset($data["tien"])) {
            return 0;
        } else {
            return $data["tien"];
        }
    }
	
	function get_thuong_hs_kt($hsID) {
		
		global $db;
	
		$query="SELECT SUM(price) AS tien FROM tien_ra WHERE ID_HS='$hsID' AND string LIKE 'kiemtra_%'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		if(!isset($data["tien"])) {
			return 0;
		} else {
			return $data["tien"];
		}
	}

    function get_thuong_con_ad() {

        global $db;

        $query="SELECT SUM(price) AS tien FROM tien_ra WHERE string!='nap-tien'";

        $result=mysqli_query($db, $query);
        $data=mysqli_fetch_assoc($result);

        if(!isset($data["tien"])) {
            return 0;
        } else {
            return $data["tien"];
        }
    }
	
	function get_nap_ad() {

		global $db;

		$query="SELECT SUM(price) AS tien FROM tien_ra WHERE string='nap-tien'";

		$result=mysqli_query($db, $query);
		$data=mysqli_fetch_assoc($result);

		if(!isset($data["tien"])) {
			return 0;
		} else {
			return $data["tien"];
		}
	}

    function get_phat_hs_con($hsID) {

        global $db;

        $query="SELECT SUM(price) AS tien FROM tien_vao WHERE ID_HS='$hsID' AND string!='rut-tien'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        if(!isset($data["tien"])) {
            return 0;
        } else {
            return $data["tien"];
        }
    }

    function get_phat_hs_con2($hsID) {

        global $db;

        $query="SELECT * FROM tien_vao WHERE ID_HS='$hsID' AND string!='rut-tien'";

        $result=mysqli_query($db,$query);

        return $result;
    }

    // Đã check
	function get_phat_hs($hsID) {
		
		global $db;
	
		$query="SELECT SUM(price) AS tien FROM tien_vao WHERE ID_HS='$hsID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		if(!isset($data["tien"])) {
			return 0;
		} else {
			return $data["tien"];
		}
	}
	
	function get_phat_hs_kt($hsID) {
		
		global $db;
	
		$query="SELECT SUM(price) AS tien FROM tien_vao WHERE ID_HS='$hsID' AND string LIKE 'kiemtra_%'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		if(!isset($data["tien"])) {
			return 0;
		} else {
			return $data["tien"];
		}
	}
	
	function get_rut_ad() {
		
		global $db;
	
		$query="SELECT SUM(price) AS tien FROM tien_vao WHERE string='rut-tien'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		if(!isset($data["tien"])) {
			return 0;
		} else {
			return $data["tien"];
		}
	}

    function get_phat_con_ad() {

        global $db;

        $query="SELECT SUM(price) AS tien FROM tien_vao WHERE string!='rut-tien'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        if(!isset($data["tien"])) {
            return 0;
        } else {
            return $data["tien"];
        }
    }
	
	function get_all_ca_lop($lopID, $monID, $cahoc_string) {
		
		global $db;
		
		$query="SELECT * FROM $cahoc_string INNER JOIN cagio ON (cagio.lop='$lopID' OR cagio.lop='3') AND cagio.ID_MON='$monID' WHERE $cahoc_string.ID_GIO=cagio.ID_GIO ORDER BY $cahoc_string.thu ASC";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_all_lop() {
		
		global $db;
	
		$query="SELECT * FROM lop WHERE name!='Chung' ORDER BY ID_LOP ASC";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_all_lop2() {
		
		global $db;
	
		$query="SELECT * FROM lop";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function check_hs_in_ca_hientai($caID, $hsID) {
		
		global $db;
		
		$query="SELECT ID_STT FROM ca_hientai WHERE ID_CA='$caID' AND ID_HS='$hsID'";
		$result=mysqli_query($db,$query);
		
		if(mysqli_num_rows($result)==0) {
			return false;
		} else {
			return true;
		}
	}

	// Đã check
	function check_hs_in_ca_codinh($caID, $hsID) {
		
		global $db;
		
		$query="SELECT * FROM ca_codinh WHERE ID_CA='$caID' AND ID_HS='$hsID'";
		$result=mysqli_query($db,$query);
		
		if(mysqli_num_rows($result)==0) {
			return false;
		} else {
			return true;
		}
	}

	// Đã check
	function get_lop_name($lop) {
		
		global $db;
	
		$query="SELECT * FROM lop WHERE ID_LOP='$lop'";
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["name"];
		
	}
	
	function format_date($date) {
		
		$date=date_create($date);
		
		$new_date=date_format($date, 'd/m');
		
		return $new_date;
	}

    function format_timeback($time) {
        // giây
        $t = round($time);
        return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
    }
	
	function format_nghi($date) {
	
		$date=date_create($date);
		
		$new_date=date_format($date, 'Y-m');
		
		return $new_date;
	}

	// Đã check
	function format_money_vnd($money) {
		
		if($money==0) {
			$new_money=$money;
		} else {
			$new_money=number_format($money/1000, 0, ',', '.').'k';	
		}
		
		return $new_money;
	}

	// Đã check
	function format_price($money) {
		
		$new_money=number_format($money, 0, ',', '.').'đ';	
		
		return $new_money;
	}
	
	function format_price_short($money) {
		
		$new_money=number_format($money/1000, 0, ',', '.');	
		
		return $new_money;
	}

	// Đã check
	function format_phantram($phantram) {
		
		$new_phantram=number_format($phantram, 0, ',', '.').'%';	
		
		return $new_phantram;
	}
	
	function get_all_mau() {
		
		global $db;
	
		$query="SELECT * FROM mau";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_user_mau($hsID) {
		
		global $db;
	
		$query="SELECT mau.mau FROM hocsinh INNER JOIN mau WHERE hocsinh.ID_HS='$hsID' AND hocsinh.ID_MAU=mau.ID_MAU";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["mau"];
	}
	
	function change_user_mau($mauID, $hsID) {
		
		global $db;
	
		$query="UPDATE hocsinh SET ID_MAU='$mauID' WHERE ID_HS='$hsID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function check_maso_getID($maso) {
		
		global $db;
	
		$query="SELECT ID_HS FROM hocsinh WHERE cmt='$maso'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==0) {
			return 0;
		} else {
			$data=mysqli_fetch_assoc($result);
			return $data["ID_HS"];
		}
	}

	// Đã check
	function get_hs_id($cmt) {
		
		global $db;
	
		$query="SELECT ID_HS FROM hocsinh WHERE cmt='$cmt'";
		
		$result=mysqli_query($db,$query);
        if(mysqli_num_rows($result) != 0) {
            $data = mysqli_fetch_assoc($result);
            return $data["ID_HS"];
        } else {
            return 0;
        }
	}

	// Đã check
	function get_new_buoikt($monID,$position,$display) {
		
		global $db;
	
		$query="SELECT ID_BUOI FROM buoikt WHERE ID_MON='$monID' ORDER BY ngay DESC LIMIT $position,$display";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["ID_BUOI"];
	}

	// Đã check
	function count_buoi_kt() {
		
		global $db;
	
		$query="SELECT ID_BUOI FROM buoikt";
		
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
	}

	// Đã check
	function count_buoi_kt_hs($date_in,$monID) {
		
		global $db;
	
		$query="SELECT COUNT(ID_BUOI) AS dem FROM buoikt WHERE ngay>'$date_in' AND ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
		
		return $data["dem"];
	}

	// Đã check
	function get_all_mon() {
		
		global $db;
		
		$query="SELECT * FROM mon ORDER BY ID_MON ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
    function get_all_lop_mon() {

        global $db;

        $query="SELECT * FROM lop_mon ORDER BY ID_MON ASC,ID_LM ASC";

        $result=mysqli_query($db,$query);

        return $result;
    }

    // Đã check
    function get_all_lop_mon2($monID) {

        global $db;

        $query="SELECT * FROM lop_mon WHERE ID_MON='$monID' ORDER BY ID_MON ASC,ID_LM ASC";

        $result=mysqli_query($db,$query);

        return $result;
    }

    // Đã check
	function get_all_mon_hocsinh($hsID) {
		
		global $db;
		
		$query="SELECT l.ID_LM,l.name,l.ID_MON FROM lop_mon AS l INNER JOIN hocsinh_mon AS m WHERE m.ID_HS='$hsID' AND m.ID_LM=l.ID_LM ORDER BY m.ID_STT ASC";
		
		$result=mysqli_query($db,$query);
		$mons=array();
		while($data=mysqli_fetch_assoc($result)) {
			$mons[]=array(
				"lmID" => $data["ID_LM"],
				"name" => $data["name"],
                "monID" => $data["ID_MON"]
			);
		}
		
		return $mons;
	}
	
	function get_all_mon_admin() {
		
		global $db;
		
		$query="SELECT * FROM mon";
		
		$result=mysqli_query($db,$query);
		$mons=array();
		while($data=mysqli_fetch_assoc($result)) {
			$mons[]=array(
				"monID" => $data["ID_MON"],
				"name" => $data["name"]
			);
		}
		
		return $mons;
	}

	// Đã check
	function get_mon_info($monID) {
		
		global $db;
	
		$query="SELECT * FROM mon WHERE ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
    function get_mon_of_lop($lmID) {

        global $db;

        $query="SELECT ID_MON FROM lop_mon WHERE ID_LM='$lmID'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["ID_MON"];
    }
	
	function get_mon_ca_info($monID) {
		
		global $db;
	
		$query="SELECT ca,ca_codinh,ca_hientai FROM mon WHERE ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
		
	}
	
	function get_cahoc_string($monID) {
		
		global $db;
	
		$query="SELECT ca FROM mon WHERE ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["ca"];
	}
	
	function get_diemkt_string($monID) {
		
		global $db;
	
		$query="SELECT diem FROM mon WHERE ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["diem"];
	}
	
	function get_ca_codinh_string($monID) {
		
		global $db;
	
		$query="SELECT ca_codinh FROM mon WHERE ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["ca_codinh"];
	}

	// Đã check
	function get_chuyende($cdID) {
		
		global $db;
		
		$query="SELECT title FROM chuyende WHERE ID_CD='$cdID'";
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["title"];
	}
	
	function count_chuyende_con($dad) {
		
		global $db;
	
		$query="SELECT COUNT(ID_CD) AS dem FROM chuyende WHERE dad='$dad' AND del='1'";
		
		$result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["dem"];
	}
	
	function get_video_of_hs($position, $display, $hsID, $monID) {
		
		global $db;
	
		$query="SELECT video.* FROM video INNER JOIN video_quyen ON video_quyen.ID_HS='$hsID' WHERE video.ID_VIDEO=video_quyen.ID_VIDEO AND video.ID_MON='$monID' ORDER BY dateup DESC LIMIT $position,$display";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_video_of_hs_no($hsID, $monID) {
		
		global $db;
	
		$query="SELECT video.* FROM video INNER JOIN video_quyen ON video_quyen.ID_HS='$hsID' WHERE video.ID_VIDEO=video_quyen.ID_VIDEO AND video.ID_MON='$monID'";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_video_detail($videoID) {
		
		global $db;
		
		$query="SELECT * FROM video WHERE ID_VIDEO='$videoID'";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_video_same_cd($cdID, $hsID) {
		
		global $db;
	
		$query="SELECT video.* FROM video INNER JOIN video_quyen ON video_quyen.ID_HS='$hsID' WHERE video.ID_VIDEO=video_quyen.ID_VIDEO AND video.ID_CD='$cdID'";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_video_same_cd_sort($position, $display, $cdID, $hsID) {
		
		global $db;
	
		$query="SELECT video.* FROM video INNER JOIN video_quyen ON video_quyen.ID_HS='$hsID' WHERE video.ID_VIDEO=video_quyen.ID_VIDEO AND video.ID_CD='$cdID' ORDER BY dateup DESC LIMIT $position,$display";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_video_same_cdadmin($cdID) {
		
		global $db;
	
		$query="SELECT * FROM video WHERE ID_CD='$cdID' ORDER BY ID_VIDEO DESC";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_price_video($videoID) {
		
		global $db;
	
		$query="SELECT price FROM video WHERE ID_VIDEO='$videoID'";
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["price"];
	}
	
	function get_chuyende_id($videoID) {
		
		global $db;
	
		$query="SELECT ID_CD FROM video WHERE ID_VIDEO='$videoID'";
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["ID_CD"];
	}
	
	function get_new_chuyende_id() {
		
		global $db;
	
		$query="SELECT ID_CD FROM chuyende ORDER BY ID_CD DESC LIMIT 1";
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["ID_CD"];
	}

	// Đã check
	function get_all_chuyende($lmID) {
		
		global $db;
	
		$query="SELECT * FROM chuyende WHERE ID_LM='$lmID' AND del='1'";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_all_chuyende_all($lmID) {
		
		global $db;
	
		$query="SELECT c.*,l.name FROM chuyende AS c INNER JOIN lop_mon AS l ON l.ID_LM=c.ID_LM WHERE c.ID_LM='$lmID' AND c.del='1'";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function edit_chuyende($cdID, $title, $maso) {
		
		global $db;
	
		$query="UPDATE chuyende SET maso='$maso',title='$title' WHERE ID_CD='$cdID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function add_chuyende($maso, $title, $dad, $lmID) {
		
		global $db;
	
		$query="INSERT INTO chuyende(maso,title,dad,del,ID_LM)
								value('$maso','$title','$dad','1','$lmID')";
		
		mysqli_query($db,$query);
		
		return mysqli_insert_id($db);
	}

	// Đã check
	function check_access_mon($hsID, $lmID) {
		
		global $db;
	
		$query="SELECT ID_STT FROM hocsinh_mon WHERE ID_HS='$hsID' AND ID_LM='$lmID'";
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==1) {
			return true;
		} else {
			return false;
		}
	}

	// Đã check
	function check_access_mon2($hsID, $lmID) {
		
		global $db;
	
		$query="SELECT de FROM hocsinh_mon WHERE ID_HS='$hsID' AND ID_LM='$lmID'";
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==1) {
			return $result;
		} else {
			return false;
		}
	}
	
	function check_has_mon($monID) {
		
		global $db;
	
		$query="SELECT * FROM mon WHERE ID_MON='$monID'";
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==1) {
			return true;
		} else {
			return false;
		}
	}
	
	function check_access_video($hsID, $videoID) {
		
		global $db;
	
		$query="SELECT * FROM video_quyen WHERE ID_HS='$hsID' AND ID_VIDEO='$videoID'";
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==1) {
			return true;
		} else {
			return false;
		}
	}
	
	function get_all_chuyende_sort($position, $display, $monID) {
		
		global $db;
	
		$query="SELECT * FROM chuyende WHERE ID_MON='$monID' ORDER BY dad ASC LIMIT $position,$display";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_chuyende_con($dad) {
		
		global $db;
	
		$query="SELECT * FROM chuyende WHERE dad='$dad' AND del='1' ORDER BY ID_CD ASC";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_all_chuyende_con($lmID) {
		
		global $db;
	
		$query="SELECT * FROM chuyende WHERE dad!='0' AND ID_LM='$lmID'";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_chuyende_dad($lmID) {
	
		global $db;
	
		$query="SELECT * FROM chuyende WHERE dad='0' AND ID_LM='$lmID'";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_chuyende_dad2($lmID) {

		global $db;

		$query="SELECT * FROM chuyende WHERE dad='0' AND ID_LM='$lmID' AND del='1'";
		$result=mysqli_query($db,$query);

		return $result;
	}
	
	function undo_chuyende($cdID) {
		
		global $db;
	
		$query="UPDATE chuyende SET del='1' WHERE ID_CD='$cdID'";
		
		mysqli_query($db,$query);
		
		$query2="SELECT * FROM chuyende WHERE ID_CD='$cdID'";
		
		$result2=mysqli_query($db,$query2);
		
		return $result2;
	}

	// Đã check
	function get_one_chuyende($cdID) {
		
		global $db;
		
		$query="SELECT c.*,l.name FROM chuyende AS c INNER JOIN lop_mon AS l ON l.ID_LM=c.ID_LM WHERE c.ID_CD='$cdID'";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function delete_chuyende($cdID) {
		
		global $db;
	
		$query="UPDATE chuyende SET del='0' WHERE ID_CD='$cdID'";
		
		mysqli_query($db,$query);
	}
	
	function get_mon_base_chuyende($cdID) {
		
		global $db;
	
		$query="SELECT mon.* FROM mon INNER JOIN chuyende ON chuyende.ID_CD='$cdID' WHERE mon.ID_MON=chuyende.ID_MON";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function count_video_base_cd($cdID) {
		
		global $db;
	
		$query="SELECT * FROM video WHERE ID_CD='$cdID'";
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
	}
	
	function get_video_chuyende($position, $display, $cdID) {
		
		global $db;
	
		$query="SELECT * FROM video WHERE ID_CD='$cdID' LIMIT $position,$display";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_video($monID) {
		
		global $db;
	
		$query="SELECT * FROM video WHERE ID_MON='$monID' ORDER BY ID_VIDEO DESC";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_video_cd($cdID) {
		
		global $db;
	
		$query="SELECT * FROM video WHERE ID_CD='$cdID'";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_video2() {
		
		global $db;
	
		$query="SELECT * FROM video";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function check_video_base_hs($hsID, $videoID) {
		
		global $db;
	
		$query="SELECT * FROM video_quyen WHERE ID_HS='$hsID' AND ID_VIDEO='$videoID'";
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==1) {
			return true;
		} else {
			return false;
		}
	}
	
	function get_video_mon_new($begin, $display, $monID) {
		
		global $db;
	
		$query="SELECT * FROM video WHERE ID_MON='$monID' ORDER BY ID_VIDEO DESC LIMIT $begin,$display";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function search_video($search, $monID) {
		
		global $db;
	
		$query="SELECT * FROM video WHERE titlestring LIKE '%$search%' AND ID_MON='$monID' ORDER BY ID_VIDEO DESC LIMIT 5";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function search_video_hocsinh($search, $monID, $hsID) {
		
		global $db;
	
		$query="SELECT video.* FROM video INNER JOIN video_quyen ON video_quyen.ID_HS='$hsID' WHERE video.titlestring LIKE '%$search%' AND video.ID_MON='$monID' AND video_quyen.ID_VIDEO=video.ID_VIDEO ORDER BY ID_VIDEO DESC LIMIT 5";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function search_video_cd($search, $cdID) {
		
		global $db;
	
		$query="SELECT * FROM video WHERE titlestring LIKE '%$search%' AND ID_CD='$cdID' ORDER BY ID_VIDEO DESC LIMIT 5";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function search_video_cd_hocsinh($search, $cdID, $hsID) {
		
		global $db;
	
		$query="SELECT video.* FROM video INNER JOIN video_quyen ON video_quyen.ID_HS='$hsID' WHERE video.titlestring LIKE '%$search%' AND video.ID_CD='$cdID' AND video_quyen.ID_VIDEO=video.ID_VIDEO ORDER BY ID_VIDEO DESC LIMIT 5";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function format_dateup($date) {

		$date=date_create($date);

		$new_date=date_format($date, 'd/m/Y');
		
		return $new_date;
	}
	
	function format_date_o($date) {
		
		$temp=explode("/",$date);

        if(count($temp)==3) {
            $new_date = $temp[2] . "-" . format_month_db($temp[1]) . "-" . format_month_db($temp[0]);
        } else if(count($temp)==0) {
            $new_date = date("Y-m-d");
        } else {
            $new_date = $temp[1] . "-" . format_month_db($temp[0]);
        }
		
		return $new_date;
	}
	
	function unicode_convert($str)

	{

  	    if(!$str) return false;

  	    $unicode = array(

        		'a'=>array('á','à','ả','ã','ạ','ă','ắ','ặ','ằ','ẳ','ẵ','â','ấ','ầ','ẩ','ẫ','ậ','A','Á','À','Ả','Ã','Ạ','Ă','Ắ','Ặ','Ằ','Ẳ','Ẵ','Â','Ấ','Ầ','Ẩ','Ẫ','Ậ'),
				
				'b'=>array('B'),
				
				'c'=>array('C'),

  	            'd'=>array('đ','D','Đ'),

  	            'e'=>array('é','è','ẻ','ẽ','ẹ','ê','ế','ề','ể','ễ','ệ','E','É','È','Ẻ','Ẽ','Ẹ','Ê','Ế','Ề','Ể','Ễ','Ệ'),
				
				'f'=>array('F'),
				
				'g'=>array('G'),

				'h'=>array('H'),

  	            'i'=>array('í','ì','ỉ','ĩ','ị','I','Í','Ì','Ỉ','Ĩ','Ị'),

				'k'=>array('K'),
				
				'l'=>array('L'),

				'm'=>array('M'),

				'n'=>array('N'),

  	            'o'=>array('ó','ò','ỏ','õ','ọ','ô','ố','ồ','ổ','ỗ','ộ','ơ','ớ','ờ','ở','ỡ','ợ','O','Ó','Ò','Ỏ','Õ','Ọ','Ô','Ố','Ồ','Ổ','Ỗ','Ộ','Ơ','Ớ','Ờ','Ở','Ỡ','Ợ'),
				
				'r'=>array('R'),

				'z'=>array('Z'),

				'x'=>array('X'),

				'p'=>array('P'),

				'q'=>array('Q'),

  	            'u'=>array('ú','ù','ủ','ũ','ụ','ư','ứ','ừ','ử','ữ','ự','U','Ú','Ù','Ủ','Ũ','Ụ','Ư','Ứ','Ừ','Ử','Ữ','Ự'),

				'v'=>array('V'),

				't'=>array('T'),

				'w'=>array('W'),

				's'=>array('S'),

  	            'y'=>array('ý','ỳ','ỷ','ỹ','ỵ','Y','Ý','Ỳ','Ỷ','Ỹ','Ỵ'),

  	            //'-'=>array(' ','&quot;','.','?',':'),

				//''=>array('(',')',',')
                '-'=>array(' '),
                ''=>array('&quot;','?',':','/'),
                ''=>array('(',')',',','.','!')

  	        );



  	        foreach($unicode as $nonUnicode=>$uni){

  	        	foreach($uni as $value)

            	$str = str_replace($value,$nonUnicode,$str);

  	        }

    	return $str;

  	}
	
	function search_hocsinh($search) {
		
		global $db;
	
		$query="SELECT ID_HS,cmt,fullname,birth FROM hocsinh WHERE cmt LIKE '%$search%' OR namestring LIKE '%$search%' LIMIT 5";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function search_hocsinh_van($search) {
		
		global $db;
	
		$query="SELECT ID_HS,cmt,fullname,birth FROM hocsinh WHERE vantay='$search' LIMIT 5";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function search_hocsinh_name($search) {
		
		global $db;
	
		$query="SELECT ID_HS,cmt,fullname,birth FROM hocsinh WHERE namestring LIKE '%$search%' LIMIT 5";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function search_hocsinh_cmt($search, $lmID) {
		
		global $db;

        if($lmID!=0) {
            if(is_numeric($search)) {
                $query = "SELECT h.ID_HS,h.cmt,h.vantay,h.fullname,h.birth FROM hocsinh AS h 
            INNER JOIN hocsinh_mon AS m ON m.ID_LM='$lmID' AND m.ID_HS=h.ID_HS 
            WHERE h.vantay='$search' LIMIT 3";
            } else if(valid_maso($search)) {
                $query = "SELECT h.ID_HS,h.cmt,h.vantay,h.fullname,h.birth FROM hocsinh AS h 
            INNER JOIN hocsinh_mon AS m ON m.ID_LM='$lmID' AND m.ID_HS=h.ID_HS 
            WHERE h.cmt='$search' LIMIT 3";
            } else {
                $query = "SELECT h.ID_HS,h.cmt,h.vantay,h.fullname,h.birth FROM hocsinh AS h 
            INNER JOIN hocsinh_mon AS m ON m.ID_LM='$lmID' AND m.ID_HS=h.ID_HS 
            WHERE h.namestring LIKE '%" . unicode_convert($search) . "%' LIMIT 5";
            }
        } else {
            if(is_numeric($search)) {
                $query = "SELECT h.ID_HS,h.cmt,h.vantay,h.fullname,h.birth FROM hocsinh AS h 
            INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS 
            WHERE h.vantay='$search' LIMIT 3";
            } else if(valid_maso($search)) {
                $query = "SELECT h.ID_HS,h.cmt,h.vantay,h.fullname,h.birth FROM hocsinh AS h 
            INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS 
            WHERE h.cmt='$search' LIMIT 3";
            } else {
                $query = "SELECT h.ID_HS,h.cmt,h.vantay,h.fullname,h.birth FROM hocsinh AS h 
            INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS 
            WHERE h.namestring LIKE '%" . unicode_convert($search) . "%' LIMIT 5";
            }
        }
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
    function search_hocsinh_sdt($search) {

        global $db;

        $query="SELECT ID_HS,cmt,vantay,fullname,birth,sdt,sdt_bo,sdt_me FROM hocsinh WHERE sdt LIKE '$search%' OR sdt_bo LIKE '$search%' OR sdt_me LIKE '$search%' LIMIT 5";

        $result=mysqli_query($db,$query);

        return $result;
    }

    // Đã check
	function search_hocsinh_full($search, $monID) {
		
		global $db;

        if(is_numeric($search)) {
            $query = "SELECT h.ID_HS,h.cmt,h.vantay,h.fullname,h.birth FROM hocsinh AS h 
            INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS 
            INNER JOIN lop_mon AS l ON l.ID_LM=m.ID_LM AND l.ID_MON='$monID'
            WHERE h.vantay='$search' LIMIT 3";
        } else if(valid_maso($search)) {
            $query = "SELECT h.ID_HS,h.cmt,h.vantay,h.fullname,h.birth FROM hocsinh AS h 
            INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS 
            INNER JOIN lop_mon AS l ON l.ID_LM=m.ID_LM AND l.ID_MON='$monID'
            WHERE h.cmt='$search' LIMIT 3";
        } else {
            $query = "SELECT h.ID_HS,h.cmt,h.vantay,h.fullname,h.birth FROM hocsinh AS h 
            INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS 
            INNER JOIN lop_mon AS l ON l.ID_LM=m.ID_LM AND l.ID_MON='$monID'
            WHERE h.namestring LIKE '%" . unicode_convert($search) . "%' LIMIT 5";
        }
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function turn_on_video($hsID, $videoID) {
		
		global $db;
		
		if(!check_video_base_hs($hsID,$videoID)) {
			$query="INSERT INTO video_quyen(ID_HS,ID_VIDEO)
											value('$hsID','$videoID')";
			mysqli_query($db,$query);
		}
	}
	
	function turn_off_video($hsID, $videoID) {
		
		global $db;
	
		$query="DELETE FROM video_quyen WHERE ID_HS='$hsID' AND ID_VIDEO='$videoID'";
		mysqli_query($db,$query);
	}
	
	function turn_on_all_video($hsID, $cdID) {
		
		global $db;
		
		$result=get_video_same_cdadmin($cdID);
		while($data=mysqli_fetch_assoc($result)) {
			if(!check_video_base_hs($hsID,$data["ID_VIDEO"])) {
				$query="INSERT INTO video_quyen(ID_HS,ID_VIDEO)
										value('$hsID','$data[ID_VIDEO]')";
				mysqli_query($db,$query);
			}
		}
	}
	
	function turn_off_all_video($hsID) {
		
		global $db;
		
		$query="DELETE FROM video_quyen WHERE ID_HS='$hsID'";
		
		mysqli_query($db,$query);
	}
	
	function kill_video($videoID) {
		
		global $db;
	
		$query="DELETE FROM video WHERE ID_VIDEO='$videoID'";
		$query2="DELETE FROM video_quyen WHERE ID_VIDEO='$videoID'";
		mysqli_query($db,$query);
		mysqli_query($db,$query2);
	}
	
	function up_video($title, $mon, $video, $chuyende, $price) {
		
		global $db;
		
		$string=unicode_convert($title);
		$query="INSERT INTO video(ID_CD,title,titlestring,content,ID_MON,price,dateup)
							value('$chuyende','$title','$string','$video','$mon','$price',now())";
		mysqli_query($db,$query);
	}
	
	function edit_video($title, $mon, $video, $chuyende, $price, $videoID) {
		
		global $db;
		
		$string=unicode_convert($title);
		if($video != "none") {
			$query="UPDATE video SET ID_CD='$chuyende',title='$title',titlestring='$string',content='$video',ID_MON='$mon',price='$price',dateup=now() WHERE ID_VIDEO='$videoID'";
		} else {
			$query="UPDATE video SET ID_CD='$chuyende',title='$title',titlestring='$string',ID_MON='$mon',price='$price',dateup=now() WHERE ID_VIDEO='$videoID'";
		}
		mysqli_query($db,$query);
	}

	// Đã check
	function get_all_hocsinh_id($lmID) {
		
		global $db;
	
		$query="SELECT hocsinh.ID_HS FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' ORDER BY hocsinh.cmt ASC";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_thuong_hocsinh($hsID) {
		
		global $db;
	
		$query="SELECT * FROM tien_ra WHERE ID_HS='$hsID' ORDER BY date DESC,ID_RA DESC";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_thuong_hocsinh_sort($hsID,$position,$display) {
		
		global $db;
	
		$query="SELECT * FROM tien_ra WHERE ID_HS='$hsID' ORDER BY date DESC,ID_RA DESC LIMIT $position,$display";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_thuong_hocsinh_short($hsID) {
		
		global $db;
	
		$query="SELECT * FROM tien_ra WHERE ID_HS='$hsID' ORDER BY date DESC,ID_RA DESC LIMIT 5";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_thuong_hocsinh_kt($hsID,$type) {
		
		global $db;
	
		$query="SELECT * FROM tien_ra WHERE ID_HS='$hsID' AND string='kiemtra' ORDER BY date DESC,ID_RA DESC";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

    function get_nap_hocsinh($hsID) {

        global $db;

        $query="SELECT t.*,o.note AS note2 FROM tien_ra AS t INNER JOIN options AS o ON o.ID_O=t.object AND o.type='tro-giang-code' WHERE t.ID_HS='$hsID' AND t.string='nap-tien' ORDER BY t.date DESC,t.ID_RA DESC";
        $result=mysqli_query($db,$query);

        return $result;
    }

    function get_rut_hocsinh($hsID) {

        global $db;

        $query="SELECT * FROM tien_vao WHERE ID_HS='$hsID' AND string='rut-tien' ORDER BY date DESC,ID_VAO DESC";
        $result=mysqli_query($db,$query);

        return $result;
    }

    // Đã check
	function get_phat_hocsinh($hsID) {
		
		global $db;
	
		$query="SELECT * FROM tien_vao WHERE ID_HS='$hsID' ORDER BY date DESC,ID_VAO DESC";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_phat_hocsinh_sort($hsID,$position,$display) {
		
		global $db;
	
		$query="SELECT * FROM tien_vao WHERE ID_HS='$hsID' ORDER BY date DESC,ID_VAO DESC LIMIT $position,$display";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_phat_hocsinh_short($hsID) {
		
		global $db;
	
		$query="SELECT * FROM tien_vao WHERE ID_HS='$hsID' ORDER BY date DESC,ID_VAO DESC LIMIT 5";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_phat_hocsinh_kt($hsID,$type) {
		
		global $db;
	
		$query="SELECT * FROM tien_vao WHERE ID_HS='$hsID' AND string LIKE 'kiemtra_%' ORDER BY date DESC,ID_VAO DESC";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_thuong_admin() {
		
		global $db;
	
		$query="SELECT * FROM tien_ra ORDER BY date DESC,ID_RA DESC";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_thuong_admin_sort($position, $display) {
		
		global $db;
	
		$query="SELECT * FROM tien_ra ORDER BY date DESC,ID_RA DESC LIMIT $position,$display";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_phat_admin() {
	
		global $db;
	
		$query="SELECT * FROM tien_vao ORDER BY date DESC,ID_VAO DESC";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_phat_admin_sort($position, $display) {
		
		global $db;
	
		$query="SELECT * FROM tien_vao ORDER BY date DESC,ID_VAO DESC LIMIT $position,$display";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function check_hs_pay_video($hsID, $videoID) {
		
		global $db;
		
		$query="SELECT * FROM video_xem WHERE ID_HS='$hsID' AND ID_VIDEO='$videoID'";
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==1) {
			return true;
		} else {
			return false;
		}
	}
	
	function add_hs_pay_video($hsID,$videoID) {
		
		global $db;
		
		$query="INSERT INTO video_xem(ID_HS,ID_VIDEO,date)
								value('$hsID','$videoID',now())";
		mysqli_query($db,$query);
	}
	
	function get_day_left_video($hsID,$videoID) {
		
		global $db;
		
		$query="SELECT * FROM video_xem WHERE ID_HS='$hsID' AND ID_VIDEO='$videoID'";
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		$today=date("Y-m-d");
		$today_date=date_create($today);
		$date=date_create($data["date"]);
		$diff=date_diff($date,$today_date);
		$kc=7-$diff->format("%a");
		$kq="còn ".$kc." ngày";
		
		return $kq;
	}

	// Đã check
	function get_hs_short_detail($hsID, $lmID) {
		
		global $db;
	
		$query="SELECT h.cmt,h.vantay,h.fullname,h.email,h.avata,h.birth,h.gender,h.facebook,h.truong,h.sdt,h.sdt_bo,h.sdt_me,h.taikhoan,h.lop,m.de,m.level,m.date_in FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' WHERE h.ID_HS='$hsID'";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_hs_short_detail2($hsID) {
		
		global $db;
	
		$query="SELECT cmt,vantay,fullname,email,avata,birth,gender,facebook,truong,sdt,sdt_bo,sdt_me,taikhoan,lop FROM hocsinh WHERE ID_HS='$hsID'";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_new_buoikt_all() {
		
		global $db;
	
		$query="SELECT ID_BUOI,ngay FROM buoikt ORDER BY ngay DESC LIMIT 1";
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function edit_thuong($idRA, $note, $price) {
		
		global $db;
	
		$result=get_thuong_detail($idRA);
        $data=mysqli_fetch_assoc($result);

        $tien=get_tien_hs($data["ID_HS"]);
        $new=$tien-$data["price"]+$price;
        update_tien_hs($data["ID_HS"],$new);

        $query="UPDATE tien_ra SET note='$note',price='$price' WHERE ID_RA='$idRA'";
		mysqli_query($db,$query);
	}
	
	function edit_phat($idVAO, $note, $price) {
		
		global $db;

        $result=get_phat_detail($idVAO);
        $data=mysqli_fetch_assoc($result);

        $tien=get_tien_hs($data["ID_HS"]);
        $new=$tien+$data["price"]-$price;
        update_tien_hs($data["ID_HS"],$new);
	
		$query="UPDATE tien_vao SET note='$note',price='$price' WHERE ID_VAO='$idVAO'";
		
		mysqli_query($db,$query);
	}
	
	function get_ma_hocsinh($hsID) {
		
		global $db;
		
		$query="SELECT ID_HS,birth FROM hocsinh WHERE ID_HS='$hsID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		$birth_temp=explode("-",$data["birth"]);
		$birth="T".substr($birth_temp[0],2)."-".$hsID;
		return $birth;
	}

	// Đã check
	function get_mon_name($monID) {
		
		global $db;
	
		$query="SELECT name FROM mon WHERE ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["name"];
	}

    // Đã check
    function get_lop_mon_name($lmID) {

        global $db;

        $query="SELECT name FROM lop_mon WHERE ID_LM='$lmID'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["name"];
    }
	
	function update_diem_hs($hsID, $buoiID, $diem, $de, $loai, $note, $made, $lmID) {
		
		global $db;

        $query="UPDATE diemkt SET diem='$diem',loai='$loai',de='$de',note='$note' WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND made='$made' AND ID_LM='$lmID'";

        mysqli_query($db,$query);
	}

    function update_diem_hs3($hsID, $buoiID, $diem, $de, $loai, $note, $made, $lmID) {

        global $db;

        $query="UPDATE diemkt SET diem='$diem',loai='$loai',de='$de',note='$note',made='$made' WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_LM='$lmID'";

        mysqli_query($db,$query);
    }

    // Đã check
	function update_diem_hs2($hsID, $buoiID, $diem, $de, $loai, $note, $lmID) {
		
		global $db;
	
		$query="UPDATE diemkt SET diem='$diem',loai='$loai',de='$de',note='$note' WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_LM='$lmID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function insert_diem_hs2($hsID, $buoiID, $diem, $de, $loai, $note, $lmID) {

		global $db;

//        $count=insert_diem_count($buoiID,$lmID);
        $count = 0;
        $query="INSERT INTO diemkt(ID_BUOI,ID_HS,diem,loai,de,note,ID_LM,more) SELECT * FROM (SELECT '$buoiID' AS buoi,'$hsID' AS id,'$diem' AS diem,'$loai' AS loai,'$de' AS de,'$note' AS note,'$lmID' AS lm,'$count' AS max) AS tmp WHERE NOT EXISTS (SELECT ID_BUOI,ID_HS FROM diemkt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_LM='$lmID') LIMIT 1";

        mysqli_query($db,$query);

        return $count;
		/*$query="SELECT * FROM diemkt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID'";
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==0) {
			$query2="INSERT INTO $diem_string(ID_BUOI,ID_HS,diem,loai,de,note)
								value('$buoiID','$hsID','$diem','$loai','$de','$note')";
			mysqli_query($db,$query2);
		}*/
	}

    // Đã check
    function insert_diem_hs($hsID, $buoiID, $diem, $de, $loai, $note, $made, $lmID) {

        global $db;

//        $count=insert_diem_count($buoiID,$lmID);
        $count = 0;
        $query="INSERT INTO diemkt(ID_BUOI,ID_HS,diem,loai,de,note,made,ID_LM,more) SELECT * FROM (SELECT '$buoiID' AS buoi,'$hsID' AS id,'$diem' AS diem,'$loai' AS loai,'$de' AS de,'$note' AS note,'$made' AS made,'$lmID' AS lm,'$count' AS max) AS tmp WHERE NOT EXISTS (SELECT ID_BUOI,ID_HS FROM diemkt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_LM='$lmID') LIMIT 1";

        mysqli_query($db,$query);

        return $count;
    }

    // Đã check
    function insert_diem_count($buoiID, $lmID) {

        global $db;

        $query="SELECT MAX(more) AS max FROM diemkt WHERE ID_BUOI='$buoiID'";

        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)==0) {
            return 1;
        } else {
            $data = mysqli_fetch_assoc($result);
            return $data["max"] + 1;
        }
    }

    // Đã check
    function insert_hoc_sinh_cau($hsID, $cID, $deID, $is_dung) {

        global $db;

        $time=$num=0;
        $query="INSERT INTO hoc_sinh_cau(ID_HS,ID_C,num,time,ID_DE) SELECT * FROM (SELECT '$hsID' AS hs,'$cID' AS cid,'$num' AS num,'$time' AS time,'$deID' AS de) AS tmp WHERE NOT EXISTS (SELECT ID_STT FROM hoc_sinh_cau WHERE ID_HS='$hsID' AND ID_C='$cID' AND ID_DE='$deID') LIMIT 1";
        mysqli_query($db,$query);
    }

    // Đã check
    function get_dap_an_by_sort($deID, $cID, $sort) {

        global $db;

        $query="SELECT ID_DA FROM de_cau_dap_an WHERE ID_DE='$deID' AND ID_DA IN (SELECT ID_DA FROM dap_an_ngan WHERE ID_C='$cID') AND sort='$sort'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["ID_DA"];
    }

    // Đã check
    function update_luyen_de($deID, $hsID, $type_de, $diem, $time, $lmID) {

        global $db;

        $query="UPDATE hoc_sinh_luyen_de SET diem='$diem',time='$time' WHERE ID_DE='$deID' AND ID_HS='$hsID' AND type_de='$type_de' AND ID_LM='$lmID'";

        mysqli_query($db,$query);
    }

    // Đã check
    function insert_luyen_de($deID, $hsID, $type_de, $diem, $time, $lmID) {

        global $db;

//        $query="SELECT ID_STT FROM hoc_sinh_luyen_de WHERE ID_DE='$deID' AND ID_HS='$hsID' AND type_de='$type_de' AND ID_LM='$lmID'";
//        $result=mysqli_query($db,$query);
//        if(mysqli_num_rows($result)!=0) {
//            $data=mysqli_fetch_assoc($result);
//            $query="UPDATE hoc_sinh_luyen_de SET diem='$diem',time='$time' WHERE ID_STT='$data[ID_STT]'";
//        } else {
//            $query="INSERT INTO hoc_sinh_luyen_de(ID_DE,ID_HS,type_de,diem,time,datetime,ID_LM)
//                                            value('$deID','$hsID','$type_de','$diem','$time',now(),'$lmID')";
//        }
        $query="INSERT INTO hoc_sinh_luyen_de(ID_DE,ID_HS,type_de,diem,time,datetime,ID_LM) SELECT * FROM (SELECT '$deID' AS de,'$hsID' AS id,'$type_de' AS type,'$diem' AS diem,'$time' AS time,now() AS now,'$lmID' AS lm) AS tmp WHERE NOT EXISTS (SELECT ID_STT FROM hoc_sinh_luyen_de WHERE ID_DE='$deID' AND ID_HS='$hsID' AND type_de='$type_de' AND ID_LM='$lmID') LIMIT 1";
        mysqli_query($db,$query);
    }

    // Đã check
    function insert_chon_dap_an($hsID,$daID,$noteda,$cID,$is,$deID) {

        global $db;

        if($is) {
            delete_chon_dap_an($hsID,$cID,$deID);
        }
        $query="INSERT INTO hoc_sinh_cau(ID_HS,ID_C,ID_DA,num,time,ID_DE,note) SELECT * FROM (SELECT '$hsID' AS id,'$daID' AS da,'$deID' AS de,'$noteda' AS note) AS tmp WHERE NOT EXISTS (SELECT ID_STT FROM hoc_sinh_cau WHERE ID_DE='$deID' AND ID_HS='$hsID' AND ID_DA='$daID') LIMIT 1";

        mysqli_query($db,$query);
    }

    // Đã check
    function delete_chon_dap_an($hsID,$cID,$deID) {

        global $db;

        $query="DELETE FROM hoc_sinh_cau WHERE ID_HS='$hsID' AND ID_DA IN (SELECT ID_DA FROM dap_an_ngan WHERE ID_C='$cID') AND ID_DE='$deID'";

        mysqli_query($db,$query);
    }

    // Đã check
    function clean_ket_qua_de($hsID,$deID) {

        global $db;

        $query="DELETE FROM hoc_sinh_cau WHERE ID_HS='$hsID' AND ID_DE='$deID'";
        mysqli_query($db,$query);
        $query="DELETE FROM hoc_sinh_luyen_de WHERE ID_HS='$hsID' AND ID_DE='$deID'";
        mysqli_query($db,$query);
    }

    // Đã check
    function get_chon_dap_an($daID) {

        global $db;

        $query="SELECT num FROM dap_an_ngan WHERE ID_DA='$daID'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["num"];
    }
	
	function update_note_hs($hsID, $buoiID, $note, $diem_string) {
		
		global $db;
		
		$query="UPDATE diemkt SET note='$note' WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID'";
		
		mysqli_query($db,$query);
	}
	
	function update_loai_diem($hsID, $buoiID, $take_home, $diem_string) {
		
		global $db;
		
		$query="UPDATE diemkt SET loai='$take_home' WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function insert_new_buoikt($next_date,$monID) {
		
		global $db;
	
		$query="INSERT INTO buoikt(ngay,ID_MON) SELECT * FROM (SELECT '$next_date' AS buoi,'$monID' AS mon) AS tmp WHERE NOT EXISTS (SELECT ngay,ID_MON FROM buoikt WHERE ngay='$next_date' AND ID_MON='$monID') LIMIT 1";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function add_thachdau($meID, $hsID, $ngay, $chap, $tien, $lmID) {
		
		global $db;
	
		$query="INSERT INTO thachdau(ID_HS,ID_HS2,chap,tien,ketqua,buoi,status,ID_LM)
								value('$meID','$hsID','$chap','$tien','Z','$ngay','pending','$lmID')";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function check_exited_thachdau($meID, $hsID, $ngay, $lmID) {
		
		global $db;
		
		$query="SELECT ID_STT FROM thachdau WHERE ID_HS='$meID' AND ID_HS2='$hsID' AND buoi='$ngay' AND status='pending' AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==0) {
			return false;
		} else {
			return true;
		}
	}

	// Đã check
	function check_exited_thachdau2($meID, $hsID, $ngay, $lmID) {
		
		global $db;
		
		$query="SELECT ID_STT FROM thachdau WHERE ID_HS='$hsID' AND ID_HS2='$meID' AND buoi='$ngay' AND (status='pending' OR status='accept') AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==0) {
			return 0;
		} else {
			$data=mysqli_fetch_assoc($result);
			return $data["ID_STT"];
		}
	}

	// Đã check
	function del_thach_dau($tdID, $lmID) {

	    global $db;

        $result=callback_thachdau($tdID);
        $data=mysqli_fetch_assoc($result);
        $tien = get_muctien("thach-dau");
        if($data["status"]=="accept") {
            cong_tien_hs($data["ID_HS"], $tien, "Hoàn tiền do Admin hủy thách đấu cho ngày thi " . format_dateup($data["buoi"]), "thach-dau", "");
            add_thong_bao_hs($data["ID_HS"], $tdID, "Admin đã hủy trận thách đấu của bạn vào ngày thi " . format_dateup($data["buoi"]), "thach-dau", $lmID);
            cong_tien_hs($data["ID_HS2"], $tien, "Hoàn tiền do Admin hủy thách đấu cho ngày thi " . format_dateup($data["buoi"]), "thach-dau", "");
            add_thong_bao_hs($data["ID_HS2"], $tdID, "Admin đã hủy trận thách đấu của bạn vào ngày thi " . format_dateup($data["buoi"]), "thach-dau", $lmID);
            $query = "DELETE FROM thachdau WHERE ID_STT='$tdID'";
            mysqli_query($db, $query);
        } else if($data["status"]=="pending") {
            cong_tien_hs($data["ID_HS"], $tien, "Hoàn tiền do Admin hủy thách đấu cho ngày thi " . format_dateup($data["buoi"]), "thach-dau", "");
            add_thong_bao_hs($data["ID_HS"], $tdID, "Admin đã hủy trận thách đấu của bạn vào ngày thi " . format_dateup($data["buoi"]), "thach-dau", $lmID);
            $query = "DELETE FROM thachdau WHERE ID_STT='$tdID'";
            mysqli_query($db, $query);
        } else if($data["status"]=="done") {
            if($data["ketqua"]=="X") {
                tru_tien_hs($data["ID_HS"], $tien, "Thu lại tiền kết quả hòa thách đấu do có sự thay đổi về điểm số vào ngày thì " . format_dateup($data["buoi"]), "thach-dau", "");
                tru_tien_hs($data["ID_HS2"], $tien, "Thu lại tiền kết quả hòa thách đấu do có sự thay đổi về điểm số vào ngày thì " . format_dateup($data["buoi"]), "thach-dau", "");
            } else if($data["ketqua"]==$data["ID_HS"]) {
                tru_tien_hs($data["ID_HS"], 2*$tien, "Thu lại tiền thắng thách đấu do có sự thay đổi về điểm số vào ngày thì " . format_dateup($data["buoi"]), "thach-dau", "");
                update_level($data["ID_HS"],$lmID,get_level($data["ID_HS"],$lmID));
            } else if($data["ketqua"]==$data["ID_HS2"]) {
                tru_tien_hs($data["ID_HS2"], 2*$tien, "Thu lại tiền thắng thách đấu do có sự thay đổi về điểm số vào ngày thì " . format_dateup($data["buoi"]), "thach-dau", "");
                update_level($data["ID_HS2"],$lmID,get_level($data["ID_HS2"],$lmID));
            } else {

            }
            $query = "UPDATE thachdau SET ketqua='Z',status='accept' WHERE ID_STT='$tdID'";
            mysqli_query($db, $query);
            add_thong_bao_hs($data["ID_HS"],$tdID, "Kết quả thách đấu ngày thi " . format_dateup($data["buoi"]) . " đã bị hủy vì có sự thay đổi về điểm số! Kết quả mới sẽ sớm được cập nhật!","thach-dau",$lmID);
            add_thong_bao_hs($data["ID_HS2"],$tdID, "Kết quả thách đấu ngày thi " . format_dateup($data["buoi"]) . " đã bị hủy vì có sự thay đổi về điểm số! Kết quả mới sẽ sớm được cập nhật!","thach-dau",$lmID);
        } else {

        }
    }

    // Đã check
    function again_thach_dau($tdID, $lmID) {

        global $db;

        del_thach_dau($tdID,$lmID);
        $query="SELECT ID_STT,ID_HS,ID_HS2,chap,buoi FROM thachdau WHERE ID_STT='$tdID' AND status='accept'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        $buoi=$data["buoi"];
        $buoiID=get_id_buoikt($data["buoi"],get_mon_of_lop($lmID));
        $tien=get_muctien("thach-dau");
        $chap=abs($data["chap"]);
        $temp1=get_diem_hs3($data["ID_HS"], $buoiID, $lmID);
        $temp2=get_diem_hs3($data["ID_HS2"], $buoiID, $lmID);
        if($data["chap"]<0) {
            $diem1=$temp1[0]+$chap;
            $diem2=$temp2[0];
        } else {
            $diem1=$temp1[0];
            $diem2=$temp2[0]+$chap;
        }
        if($diem1>10) {$diem1=10;}
        if($diem2>10) {$diem2=10;}
        if(is_numeric($diem1) && is_numeric($diem2) && $diem1>=0 && $diem2>=0) {
            if($temp1[1]==0 && $temp2[1]==0) {
                if($diem1==$diem2) {
                    ketqua_thachdau($data["ID_STT"], "X");
                    cong_tien_hs($data["ID_HS"], $tien, "Hoàn tiền thách đấu do kết quả hòa ngày " . format_dateup($buoi), "thach-dau", "");
                    cong_tien_hs($data["ID_HS2"], $tien, "Hoàn tiền thách đấu do kết quả hòa ngày " . format_dateup($buoi), "thach-dau", "");
                } else if($diem1>$diem2) {
                    ketqua_thachdau($data["ID_STT"], $data["ID_HS"]);
                    cong_tien_hs($data["ID_HS"], 2*$tien, "Cộng tiền thắng (và hoàn tiền cọc) thách đấu ngày ".format_dateup($buoi),"thach-dau","");
                    add_thong_bao_hs($data["ID_HS"],$data["ID_STT"],"[Cập nhật] Chúc mừng bạn đã chiến thắng thách đấu ngày ".format_dateup($buoi)." và nhận ".format_price(2*$tien),"thach-dau",$lmID);
                    update_level($data["ID_HS"],$lmID,get_level($data["ID_HS"],$lmID));
                } else {
                    ketqua_thachdau($data["ID_STT"], $data["ID_HS2"]);
                    cong_tien_hs($data["ID_HS2"], 2*$tien, "Cộng tiền thắng (và hoàn tiền cọc) thách đấu ngày ".format_dateup($buoi),"thach-dau","");
                    add_thong_bao_hs($data["ID_HS2"],$data["ID_STT"],"[Cập nhật] Chúc mừng bạn đã chiến thắng thách đấu ngày ".format_dateup($buoi)." và nhận ".format_price(2*$tien),"thach-dau",$lmID);
                    update_level($data["ID_HS2"],$lmID,get_level($data["ID_HS2"],$lmID));
                }
            } else {
                if($temp1[1]==0 && $temp2[1]!=0) {
                    ketqua_thachdau($data["ID_STT"], $data["ID_HS"]);
                    cong_tien_hs($data["ID_HS"], 2*$tien, "Cộng tiền thắng (và hoàn tiền cọc) thách đấu ngày ".format_dateup($buoi),"thach-dau","");
                    update_level($data["ID_HS"],$lmID,get_level($data["ID_HS"],$lmID));
                    add_thong_bao_hs($data["ID_HS2"],$data["ID_STT"],"[Cập nhật] Bạn đã bị xử thua thách đấu do không làm bài trên lớp hoặc bị hủy bài ngày ".format_dateup($buoi),"thach-dau",$lmID);
                } else if($temp1[1]!=0 && $temp2[1]==0) {
                    ketqua_thachdau($data["ID_STT"], $data["ID_HS2"]);
                    cong_tien_hs($data["ID_HS2"], 2 * $tien, "Cộng tiền thắng (và hoàn tiền cọc) thách đấu ngày " . format_dateup($buoi), "thach-dau", "");
                    update_level($data["ID_HS2"],$lmID,get_level($data["ID_HS2"],$lmID));
                    add_thong_bao_hs($data["ID_HS"],$data["ID_STT"],"[Cập nhật] Bạn đã bị xử thua thách đấu do không làm bài trên lớp hoặc bị hủy bài ngày " . format_dateup($buoi), "thach-dau", $lmID);
                } else {
                    ketqua_thachdau($data["ID_STT"],"X");
                    add_thong_bao_hs($data["ID_HS"],$data["ID_STT"],"[Cập nhật] Bạn đã bị xử thua thách đấu do không làm bài trên lớp hoặc bị hủy bài ngày " . format_dateup($buoi), "thach-dau", $lmID);
                    add_thong_bao_hs($data["ID_HS2"],$data["ID_STT"],"[Cập nhật] Bạn đã bị xử thua thách đấu do không làm bài trên lớp hoặc bị hủy bài ngày " . format_dateup($buoi), "thach-dau", $lmID);
                }
            }
        }
    }

    // Đã check
	function get_cmt_hs($hsID) {
		
		global $db;
		
		$query="SELECT cmt FROM hocsinh WHERE ID_HS='$hsID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["cmt"];
	}

	function get_note_hs($hsID) {

        global $db;

        $query="SELECT note FROM hocsinh WHERE ID_HS='$hsID'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["note"];
    }

    // Đã check
	function get_current_thachdau($hsID, $lmID) {
		
		global $db;
		
		$query="SELECT * FROM thachdau WHERE (ID_HS='$hsID' OR ID_HS2='$hsID') AND ketqua='Z' AND status!='cancle' AND status!='done' AND ID_LM='$lmID' ORDER BY ID_STT DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_avata_hs($hsID) {
		
		global $db;
		
		$query="SELECT avata FROM hocsinh WHERE ID_HS='$hsID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["avata"];
	}
	
	function get_lop_hs($hsID) {
		
		global $db;
		
		$query="SELECT lop FROM hocsinh WHERE ID_HS='$hsID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["lop"];
	}
	
	function delete_thachdau($hsID, $tdID) {
		
		global $db;
		
		$query0="SELECT tien,buoi FROM thachdau WHERE ID_STT='$tdID'";
		$result0=mysqli_query($db,$query0);
		$data0=mysqli_fetch_assoc($result0);
		cong_tien_hs($hsID, $data0["tien"], "Hoàn tiền do hủy thách đấu cho ngày thi ".format_dateup($data0["buoi"]),"","");
		$query="DELETE FROM thachdau WHERE ID_STT='$tdID' AND ID_HS='$hsID' AND status='pending'";
		
		mysqli_query($db,$query);
	}
	
	function delete_thachdau_ad($hsID, $tdID) {
		
		global $db;
		
		$query0="SELECT tien,buoi FROM thachdau WHERE ID_STT='$tdID'";
		$result0=mysqli_query($db,$query0);
		$data0=mysqli_fetch_assoc($result0);
		cong_tien_hs($hsID, $data0["tien"], "Hoàn tiền do bị admin hủy lời thách đấu cho ngày thi ".format_dateup($data0["buoi"]),"","");
		$query="DELETE FROM thachdau WHERE ID_STT='$tdID' AND ID_HS='$hsID' AND status='pending'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function cancle_thachdau($hsID, $tdID) {
		
		global $db;
		
		$query="UPDATE thachdau SET status='cancle' WHERE ID_STT='$tdID' AND ID_HS2='$hsID' AND status='pending'";
		
		mysqli_query($db,$query);
		
		return callback_thachdau($tdID);
	}
	
	function accept_thachdau2($hsID, $tdID) {
		
		global $db;
		
		$query="UPDATE thachdau SET status='accept' WHERE ID_STT='$tdID' AND ID_HS2='$hsID' AND status='pending'";
		
		mysqli_query($db,$query);
		
	}
	
	function accept_thachdau($hsID, $tdID) {
		
		global $db;
		
		$query="UPDATE thachdau SET status='accept' WHERE ID_STT='$tdID' AND ID_HS2='$hsID' AND status='pending'";
		
		mysqli_query($db,$query);
		
		return callback_thachdau($tdID);
	}

	// Đã check
	function callback_thachdau($tdID) {
		
		global $db;
	
		$query="SELECT * FROM thachdau WHERE ID_STT='$tdID'";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function count_thachdau_win($hsID, $lmID) {
		
		global $db;
	
		$query="SELECT COUNT(ID_STT) AS dem FROM thachdau WHERE (ID_HS='$hsID' OR ID_HS2='$hsID') AND ketqua='$hsID' AND status='done' AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
		
		return $data["dem"];
	}

	// Đã check
	function count_ngoisao_win($hsID, $lmID) {
		
		global $db;
	
		$query="SELECT COUNT(ID_STT) AS dem FROM ngoi_sao WHERE ID_HS='$hsID' AND ketqua='1' AND status='done' AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["dem"];
	}

	// Đã check
	function count_thachdau_lose($hsID, $lmID) {
		
		global $db;
	
		$query="SELECT COUNT(ID_STT) AS dem FROM thachdau WHERE (ID_HS='$hsID' OR ID_HS2='$hsID') AND ketqua!='$hsID' AND ketqua!='Z' AND ketqua!='X' AND status='done' AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["dem"];
	}

	// Đã check
	function count_ngoisao_lose($hsID, $lmID) {
		
		global $db;
	
		$query="SELECT COUNT(ID_STT) AS dem FROM ngoi_sao WHERE ID_HS='$hsID' AND ketqua='0' AND status='done' AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["dem"];
	}

	// Đã check
    function count_hoc_tinh($hsID,$lmID) {

        global $db;

        $query="SELECT COUNT(d.ID_STT) AS dem FROM diemdanh_buoi AS b INNER JOIN diemdanh AS d ON d.ID_DD=b.ID_STT AND d.ID_HS='$hsID' AND d.is_kt='1' WHERE b.ID_LM='$lmID' ORDER BY b.ID_CUM DESC LIMIT 24";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        $dem=$data["dem"];

        $query="SELECT COUNT(d.ID_STT) AS dem FROM diemdanh_buoi AS b INNER JOIN diemdanh AS d ON d.ID_DD=b.ID_STT AND d.ID_HS='$hsID' AND d.is_hoc='1' AND d.is_kt='1' WHERE b.ID_LM='$lmID' ORDER BY b.ID_CUM DESC LIMIT 24";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        $is_hoc=$data["dem"];

        $query="SELECT COUNT(d.ID_STT) AS dem FROM diemdanh_buoi AS b INNER JOIN diemdanh AS d ON d.ID_DD=b.ID_STT AND d.ID_HS='$hsID' AND d.is_tinh='1' AND d.is_kt='1' WHERE b.ID_LM='$lmID' ORDER BY b.ID_CUM DESC LIMIT 24";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        $is_tinh=$data["dem"];

        return array($is_hoc,$is_tinh,$dem);
    }
	
	function get_ngay_buoikt($buoiID) {
		
		global $db;
	
		$query="SELECT ngay FROM buoikt WHERE ID_BUOI='$buoiID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["ngay"];
	}

	// Đã check
	function get_id_buoikt($ngay, $monID) {
		
		global $db;
	
		$query="SELECT ID_BUOI FROM buoikt WHERE ngay='$ngay' AND ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {	
			$data=mysqli_fetch_assoc($result);
			return $data["ID_BUOI"];
		} else {
			return 0;
		}			
	}

	// Đã check
	function get_history_thachdau($hsID, $lmID) {
		
		global $db;
	
		$query="SELECT * FROM thachdau WHERE (ID_HS='$hsID' OR ID_HS2='$hsID') AND ketqua!='Z' AND status='done' AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_pending_thachdau($monID, $position, $display) {
		
		global $db;
	
		$query="SELECT * FROM thachdau WHERE ketqua='Z' AND status='pending' AND ID_MON='$monID' ORDER BY ID_STT DESC LIMIT $position,$display";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_list_thachdau($hsID, $ngay, $lmID) {
		
		global $db;
	
		$query="SELECT * FROM thachdau WHERE (ID_HS='$hsID' OR ID_HS2='$hsID') AND buoi='$ngay' AND status!='cancle' AND status!='done' AND ID_LM='$lmID' ORDER BY ID_STT DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_all_list_thachdau($ngay, $lmID) {
		
		global $db;
	
		$query="SELECT * FROM thachdau WHERE buoi='$ngay' AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_pending_thachdau($monID) {
		
		global $db;
	
		$query="SELECT * FROM thachdau WHERE ketqua='Z' AND status='pending' AND ID_MON='$monID' ORDER BY ID_STT DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_done_thachdau($monID, $position, $display) {
		
		global $db;
	
		$query="SELECT * FROM thachdau WHERE ketqua!='Z' AND status='done' AND ID_MON='$monID' ORDER BY ID_STT DESC LIMIT $position,$display";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_done_thachdau($monID) {
		
		global $db;
	
		$query="SELECT * FROM thachdau WHERE ketqua!='Z' AND status='done' AND ID_MON='$monID' ORDER BY ID_STT DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_accept_thachdau($monID, $position, $display) {
		
		global $db;
	
		$query="SELECT * FROM thachdau WHERE ketqua='Z' AND status='accept' AND ID_MON='$monID' ORDER BY ID_STT DESC LIMIT $position,$display";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_accept_thachdau($monID) {
		
		global $db;
	
		$query="SELECT * FROM thachdau WHERE ketqua='Z' AND status='accept' AND ID_MON='$monID' ORDER BY ID_STT DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_thachdau_hs($hsID, $lmID) {
		
		global $db;
		
		$query="SELECT * FROM thachdau WHERE (ID_HS='$hsID' OR ID_HS2='$hsID') AND status!='cancle' AND ID_LM='$lmID' ORDER BY ID_STT DESC LIMIT 30";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function add_sync($note) {
		
		global $db;
	
		$query="INSERT INTO sync(datetime,note)
						value(now(), '$note')";
		mysqli_query($db,$query);
	}

	// Đã check
	function get_all_buoikt($monID,$limit) {
		
		global $db;
	
		$query="SELECT * FROM buoikt WHERE ID_MON='$monID' ORDER BY ID_BUOI DESC LIMIT $limit";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
    function get_all_buoikt2($monID) {

        global $db;

        $query="SELECT * FROM buoikt WHERE ID_MON='$monID' ORDER BY ID_BUOI DESC";

        $result=mysqli_query($db,$query);

        return $result;
    }

    // Đã check
	function get_de_hs($hsID, $lmID) {
		
		global $db;

        $query = "SELECT de FROM hocsinh_mon WHERE ID_HS='$hsID' AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["de"];
	}

	function get_mon_unknow($hsID, $monID) {

        global $db;

        $query = "SELECT de,ID_LM FROM hocsinh_mon WHERE ID_HS='$hsID' AND ID_LM IN (SELECT ID_LM FROM lop_mon WHERE ID_MON='$monID')";

        $result=mysqli_query($db,$query);

        return $result;
    }
	
	function get_diem_hs($hsID, $buoiID, $diem_string = NULL) {
		
		global $db;
	
		$query="SELECT diem FROM diemkt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["diem"];
	}

	// Đã check
	function get_diem_hs2($hsID, $ngay, $lmID) {
		
		global $db;
	
		$query="SELECT d.diem FROM buoikt AS b INNER JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS='$hsID' AND d.ID_LM='$lmID' WHERE b.ngay='$ngay' AND b.ID_MON='".get_mon_of_lop($lmID)."'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["diem"];
	}

	// Đã check
	function get_diem_hs3($hsID, $buoiID, $lmID) {
		
		global $db;
	
		$query="SELECT diem,loai FROM diemkt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return array($data["diem"],$data["loai"]);
	}
	
	function ketqua_thachdau($sttID, $kq) {
		
		global $db;
	
		$query="UPDATE thachdau SET ketqua='$kq',status='done' WHERE ID_STT='$sttID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function update_chuyende_diem($buoiID, $hsID, $idCD, $diemCD, $cau, $y, $sttID, $lmID) {
		
		global $db;
	
		if($sttID!=0) {
			$query="UPDATE chuyende_diem SET diem='$diemCD',cau='$cau',y='$y' WHERE ID_STT='$sttID' AND ID_BUOI='$buoiID' AND ID_CD='$idCD' AND ID_HS='$hsID' AND ID_LM='$lmID'";
		
			mysqli_query($db,$query);
		} else {
		    $query="DELETE FROM chuyende_diem WHERE ID_BUOI='$buoiID' AND ID_CD='$idCD' AND ID_HS='$hsID' AND cau='$cau' AND y='$y' AND ID_LM='$lmID'";
            mysqli_query($db,$query);
			$query="INSERT INTO chuyende_diem(ID_BUOI,ID_CD,ID_HS,diem,cau,y,ID_LM)
									value('$buoiID','$idCD','$hsID','$diemCD','$cau','$y','$lmID')";
		
			mysqli_query($db,$query);
		}
	}

    // Đã check
    function update_chuyende_diem2($buoiID, $hsID, $idCD, $diemCD, $cau, $y, $note, $sttID, $lmID) {

        global $db;

        if($sttID!=0) {
            $query="UPDATE chuyende_diem SET diem='$diemCD',cau='$cau',y='$y',note='$note' WHERE ID_STT='$sttID' AND ID_BUOI='$buoiID' AND ID_CD='$idCD' AND ID_HS='$hsID' AND ID_LM='$lmID'";

            mysqli_query($db,$query);
        } else {
            $query="DELETE FROM chuyende_diem WHERE ID_BUOI='$buoiID' AND ID_CD='$idCD' AND ID_HS='$hsID' AND cau='$cau' AND y='$y' AND ID_LM='$lmID'";
            mysqli_query($db,$query);
            $query="INSERT INTO chuyende_diem(ID_BUOI,ID_CD,ID_HS,diem,cau,y,note,ID_LM)
                                        value('$buoiID','$idCD','$hsID','$diemCD','$cau','$y','$note','$lmID')";

            mysqli_query($db,$query);
        }
    }

    // Đã check
    function update_chuyende_diem3($buoiID, $hsID, $idCD, $diemCD, $cau, $y, $note, $lmID) {

        global $db;

        $query="UPDATE chuyende_diem SET ID_CD='$idCD',diem='$diemCD',note='$note' WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND cau='$cau' AND y='$y' AND ID_LM='$lmID'";

        mysqli_query($db,$query);
    }

	// Đã check
	function insert_chuyende_diem($buoiID, $hsID, $idCD, $diemCD, $cau, $y, $lmID) {
		
		global $db;
		
		$query="INSERT INTO chuyende_diem(ID_BUOI,ID_CD,ID_HS,diem,cau,y,ID_LM) SELECT * FROM (SELECT '$buoiID' AS buoi,'$idCD' AS id,'$hsID' AS hs,'$diemCD' AS diem,'$cau' AS cau,'$y' AS y,'$lmID' AS lm) AS tmp WHERE NOT EXISTS (SELECT ID_STT FROM chuyende_diem WHERE ID_BUOI='$buoiID' AND ID_CD='$idCD' AND ID_HS='$hsID' AND cau='$cau' AND y='$y' AND ID_LM='$lmID') LIMIT 1";
		
		mysqli_query($db,$query);
	}

    // Đã check
    function insert_chuyende_diem2($buoiID, $hsID, $idCD, $diemCD, $cau, $y, $note, $lmID) {
    
        global $db;
    
        $query="INSERT INTO chuyende_diem(ID_BUOI,ID_CD,ID_HS,diem,cau,y,note,ID_LM) SELECT * FROM (SELECT '$buoiID' AS buoi,'$idCD' AS id,'$hsID' AS hs,'$diemCD' AS diem,'$cau' AS cau,'$y' AS y,'$note' AS note,'$lmID' AS lm) AS tmp WHERE NOT EXISTS (SELECT ID_STT FROM chuyende_diem WHERE ID_BUOI='$buoiID' AND ID_CD='$idCD' AND ID_HS='$hsID' AND cau='$cau' AND y='$y' AND ID_LM='$lmID') LIMIT 1";
    
        mysqli_query($db,$query);
    }

	// Đã check
	function insert_diemdanh_nghi($cumID, $hsID, $is_phep, $lmID, $monID) {
	
		global $db;
		
		if($cumID!=0 && !check_exited_diemdanh($cumID, $hsID, $lmID, $monID)) {
			$query="SELECT ID_STT FROM diemdanh_nghi WHERE ID_CUM='$cumID' AND ID_HS='$hsID' AND ID_LM='$lmID' AND ID_MON='$monID'";
			$result=mysqli_query($db,$query);
			if(mysqli_num_rows($result)==0) {
				$query2="INSERT INTO diemdanh_nghi(ID_CUM,ID_HS,is_phep,ID_LM,ID_MON)
											value('$cumID','$hsID','$is_phep','$lmID','$monID')";
				mysqli_query($db,$query2);
			} else {
				$query2="UPDATE diemdanh_nghi SET is_phep='$is_phep' WHERE ID_CUM='$cumID' AND ID_HS='$hsID' AND ID_LM='$lmID' AND ID_MON='$monID'";
				mysqli_query($db,$query2);
			}
		}
	}

    // Đã check
    function insert_diemdanh_nghi3($cumID, $hsID, $ngay, $is_phep, $nhan, $confirm, $anh, $sdt, $lmID, $monID) {

        global $db;

        $query="SELECT ID_STT FROM diemdanh_nghi WHERE ID_HS='$hsID' AND ngay='$ngay' AND ID_LM='$lmID' AND ID_MON='$monID'";
        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)==0) {
            $query2="INSERT INTO diemdanh_nghi(ID_CUM,ID_HS,is_phep,nhan,confirm,ngay,anh,sdt,ID_LM,ID_MON)
                                        VALUES('$cumID','$hsID','$is_phep','$nhan','$confirm','$ngay','$anh','$sdt','$lmID','$monID')";
            mysqli_query($db,$query2);
        }
    }

	// Đã check
    function insert_diemdanh_nghi2($cumID, $hsID, $is_phep, $lmID, $monID) {

        global $db;

        if($cumID!=0) {
            $query="SELECT ID_STT FROM diemdanh_nghi WHERE ID_CUM='$cumID' AND ID_HS='$hsID' AND ID_LM='$lmID' AND ID_MON='$monID'";
            $result=mysqli_query($db,$query);
            if(mysqli_num_rows($result)==0) {
                $query2="INSERT INTO diemdanh_nghi(ID_CUM,ID_HS,is_phep,ID_LM,ID_MON)
                                                value('$cumID','$hsID','$is_phep','$lmID','$monID')";
                mysqli_query($db,$query2);
            }
        }
    }

	// Đã check
	function del_diemdanh_nghi($cumID, $hsID, $lmID, $monID) {
	
		global $db;
		
		$query="DELETE FROM diemdanh_nghi WHERE ID_CUM='$cumID' AND ID_HS='$hsID' AND ID_LM='$lmID' AND ID_MON='$monID'";
		
		mysqli_query($db,$query);
	}
	
	function get_diemtb_month($hsID, $monID, $now) {
		
		global $db;
		
		$query="SELECT diemtb FROM diemtb_thang WHERE ID_HS='$hsID' AND ID_MON='$monID' AND datetime='$now'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["diemtb"]; 
	}

	// Đã check
	function tinh_diemtb_month2($hsID,$lmID) {
		
		global $db;
		
		$query="SELECT AVG(d.diem) AS diemtb FROM buoikt AS b INNER JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS='$hsID' AND (d.loai='0' OR d.loai='1') AND d.ID_LM='$lmID' ORDER BY b.ID_BUOI DESC LIMIT 5";
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		$diemtb=number_format((float)$data["diemtb"], 2, '.', '');

		return $diemtb;
	}

	// Đã check
	function tinh_diemtb_month($hsID, $now, $lmID) {
		
		global $db;
		
//		$query="SELECT AVG(d.diem) AS diemtb FROM buoikt AS b INNER JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS='$hsID' AND d.diem!='X' AND d.diem!='0' AND d.loai IN ('0','1') AND d.ID_LM='$lmID' WHERE b.ngay LIKE '$now-%' ORDER BY b.ID_BUOI DESC";
        $query="SELECT AVG(d.diem) AS diemtb FROM buoikt AS b INNER JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS='$hsID' AND d.diem!='X' AND d.diem!='0' AND d.loai IN ('0','1') AND d.ID_LM='$lmID' WHERE b.ngay LIKE '$now-%' GROUP BY d.de ORDER BY FIELD(d.de,'G','B','Y')";
        $result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		$diemtb=number_format((float)$data["diemtb"], 2, '.', '');
		
		return $diemtb;
	}

    // Đã check
    function tinh_diemtb_month3($hsID, $now, $lmID) {

        global $db;

        $monID=get_mon_of_lop($lmID);

        $query="SELECT AVG(d.diem) AS diemtb FROM buoikt AS b INNER JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS='$hsID' AND d.diem!='X' AND d.loai IN ('0','1') AND d.ID_LM='$lmID' WHERE b.ngay LIKE '$now-%' AND b.ID_MON='$monID' ORDER BY b.ID_BUOI DESC";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        $diemtb=number_format((float)$data["diemtb"], 2, '.', '');

        return $diemtb;
    }

	// Đã check
	function insert_diemtb_thang($hsID, $diemtb, $detb, $lmID, $now) {
		
		global $db;

        $query="INSERT diemtb_thang(ID_HS,diemtb,detb,datetime,ID_LM)
                                value('$hsID','$diemtb','$detb','$now','$lmID')";

        mysqli_query($db,$query);
	}

	// Đã check
	function update_diemtb_thang($hsID, $diemtb, $detb, $lmID, $now) {
		
		global $db;
		
		$query="UPDATE diemtb_thang SET diemtb='$diemtb' WHERE ID_HS='$hsID' AND ID_LM='$lmID' AND datetime='$now'";
								
		mysqli_query($db,$query);
	}

    // Đã check
	function get_last_month($month) {
		
		if($month==1) {
			$month=12;
		} else {
			$month--;
		}
		
		return format_month_db($month);
	}

	// Đã check
	function get_last_year($month, $year) {
	
		if($month==1) {
			return $year-1;
		} else {
			return $year;
		}
	}
	
	function count_CN() {
	
		$days_of_month=array("31","28","31","30","31","30","31","31","30","31","30","31");
		$month=get_last_month(date("m"));
		$year=get_last_year($month, date("Y"));
		if($year%4==0) {
			$days_of_month[1]="29";
		}
		$days=$days_of_month[$month-1];
		$dem=0;$i=1;
		while($i<=$days) {
			$jd=gregoriantojd($month,$i,$year);
			if(jddayofweek($jd,0)==0) {
				$dem++;
				$i+=7;
			} else {
				$i++;
			}
		}
		
		return $dem;
	}
	
	function get_last_month_diem($year,$month,$now) {
		if($now<=5) {
			$month=get_last_month($month);
		} else {
			$month=$month;
		}
		$month+1-1;
		
		return format_month_db($month);
	}
	
	function count_lastCN() {
	
		$days_of_month=array("31","28","31","30","31","30","31","31","30","31","30","31");
		$year=date("Y");
		if($year%4==0) {
			$days_of_month[1]="29";
		}
		$now=date("j");
		if($now<=5) {
			//$month=date("m")-1;
			$month=get_last_month(date("m"));
		} else {
			$month=date("m");
		}
		$days=$days_of_month[$month-1];
		$dem=0;$i=1;
		$last_cn=0;
		while($i<=$days) {
			$jd=gregoriantojd($month,$i,$year);
			if(jddayofweek($jd,0)==0) {
				$dem++;
				$last_cn=$i;
				$i+=7;
			} else {
				$i++;
			}
		}
			
		$want_day=$last_cn+5;
		if($want_day>$days) {
			$want_day=$want_day-$days;
		}
		
		return array($want_day,$dem);
	}
	
	function get_diff_10tuan() {
		
		global $db;
	
		$buoikt=array();$dem=0;
		$query="SELECT * FROM buoikt ORDER BY ngay DESC LIMIT 10";
		$result=mysqli_query($db,$query);
		while($data=mysqli_fetch_assoc($result)) {
			$buoikt[]=$data["ngay"];
		}
		$n=count($buoikt)-1;
		$first_day=$buoikt[$n];
		$sec_day=$buoikt[0];
		
		return "từ ".format_date($first_day)." đến ".format_date($sec_day);
	}

	// Đã check
	function update_de_hs($hsID, $de, $lmID) {
		
		global $db;
	
		$query="UPDATE hocsinh_mon SET de='$de' WHERE ID_HS='$hsID' AND ID_LM='$lmID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function update_de_hs2($hsID, $de, $lmID, $date_in) {
		
		global $db;
	
		$query="UPDATE hocsinh_mon SET de='$de',date_in='$date_in' WHERE ID_HS='$hsID' AND ID_LM='$lmID'";
		
		mysqli_query($db,$query);
	}
	
	function get_all_gio($monID) {
		
		global $db;
	
		$query="SELECT * FROM cagio WHERE ID_MON='$monID' ORDER BY ID_GIO ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_all_gio_lop($monID, $lmID) {
		
		global $db;
	
		$query="SELECT * FROM cagio WHERE ID_LM='$lmID' AND ID_MON='$monID' ORDER BY ID_GIO ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function delete_ca($caID) {
		
		global $db;
	
		$query="DELETE FROM cahoc WHERE ID_CA='$caID'";
		
		mysqli_query($db,$query);
		
		$query2="DELETE FROM ca_codinh WHERE ID_CA='$caID'";
		
		mysqli_query($db,$query2);
		
		$query3="DELETE FROM ca_hientai WHERE ID_CA='$caID'";
		
		mysqli_query($db,$query3);
		
	}

	// Đã check
	function edit_ca($caID, $siso, $max, $cum, $ddID) {
		
		global $db;
	
		$query="UPDATE cahoc SET siso='$siso',max='$max',cum='$cum',ID_DD='$ddID' WHERE ID_CA='$caID'";
		
		mysqli_query($db,$query);
	}
	
	function get_gio_ca($gioID) {
	
		global $db;
	
		$query="SELECT gio FROM cagio WHERE ID_GIO='$gioID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["gio"];
	}

	// Đã check
	function add_ca($thu, $siso, $max, $gio, $cum) {
		
		global $db;
		
		$query="INSERT INTO cahoc(thu,siso,max,ID_GIO,cum,ID_DD)
									value('$thu','$siso','$max','$gio','$cum','".get_lastest_dia_diem()."')";
									
		mysqli_query($db,$query);
	}

	// Đã check
	function get_all_hs_ca_hientai($caID) {
		
		global $db;
	
		$query="SELECT h.ID_HS,h.fullname,h.cmt,h.birth FROM ca_hientai AS c
		INNER JOIN hocsinh AS h ON h.ID_HS=c.ID_HS 
		WHERE c.ID_CA='$caID' ORDER BY h.cmt ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_all_hs_ca_codinh($caID) {
		
		global $db;
		
		$query="SELECT c.*,h.ID_HS,h.fullname,h.cmt,h.birth FROM ca_codinh AS c
		INNER JOIN hocsinh AS h ON h.ID_HS=c.ID_HS 
		WHERE c.ID_CA='$caID' ORDER BY h.cmt ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_cagio($monID) {
		
		global $db;
	
		$query="SELECT * FROM cagio INNER JOIN lop ON lop.ID_LOP=cagio.lop WHERE ID_MON='$monID' ORDER BY buoi ASC, thutu ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_all_cagio_lop($monID, $lmID) {
		
		global $db;
	
		$query="SELECT * FROM cagio WHERE ID_LM='$lmID' AND ID_MON='$monID' ORDER BY buoi ASC, thutu ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function count_ca_base_gio($gioID) {
	
		global $db;
	
		$query="SELECT ID_CA FROM cahoc WHERE ID_GIO='$gioID'";
		
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
	}

	// Đã check
	function delete_cagio($gioID) {
	
		global $db;
	
		$query="DELETE FROM cagio WHERE ID_GIO='$gioID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function edit_cagio($gioID, $gio, $buoi, $thutu) {
		
		global $db;
	
		$query="UPDATE cagio SET gio='$gio',buoi='$buoi',thutu='$thutu' WHERE ID_GIO='$gioID'";
		
		mysqli_query($db,$query);
	}
	
	function get_latest_cagio() {
		
		global $db;
	
		$query="SELECT ID_GIO FROM cagio ORDER BY ID_GIO DESC LIMIT 1";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["ID_GIO"];
	}

	// Đã check
	function add_cagio($gio, $mon, $lm, $buoi, $thutu) {
		
		global $db;
		
		$query="INSERT INTO cagio(gio,buoi,thutu,ID_LM,ID_MON)
							value('$gio','$buoi','$thutu','$lm','$mon')";
									
		mysqli_query($db,$query);
	}
	
	function get_all_muctien() {
		
		global $db;
	
		$query="SELECT * FROM muc_tien";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function edit_muctien($tienID, $mota, $tien) {
		
		global $db;
	
		if($mota!="") {
			$query="UPDATE muc_tien SET mota='$mota',tien='$tien' WHERE ID_TIEN='$tienID'";
		} else {
			$query="UPDATE muc_tien SET tien='$tien' WHERE ID_TIEN='$tienID'";
		}
		
		mysqli_query($db,$query);
	}
	
	function add_muctien($string, $mota, $tien) {
		
		global $db;
	
		$query="INSERT INTO muc_tien(mota,tien,string)
							value('$mota','$tien','$string')";
									
		mysqli_query($db,$query);
	}
	
	function check_string_muctien($string) {
	
		global $db;
		
		$query="SELECT ID_TIEN FROM muc_tien WHERE string='$string'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}
	
	function get_latest_muctien() {
		
		global $db;
	
		$query="SELECT ID_TIEN FROM muc_tien ORDER BY ID_TIEN DESC LIMIT 1";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["ID_TIEN"];
	}

	// Đã check
	function get_muctien($string) {
		
		global $db;
	
		$query="SELECT tien FROM muc_tien WHERE string='$string'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["tien"];
	}

	// Đã check
	function insert_new_nhayde($hsID, $new_de, $diemtb, $lmID) {
		
		global $db;
	
		$query="INSERT INTO nhayde(ID_HS,new_de,diemtb,ID_LM)
							value('$hsID','$new_de','$diemtb','$lmID')";
									
		mysqli_query($db,$query);
	}

	// Đã check
	function get_new_nhayde($lmID, $new_de) {
		
		global $db;
	
		$query="SELECT n.*,h.cmt,h.fullname FROM nhayde AS n 
		INNER JOIN hocsinh AS h ON h.ID_HS=n.ID_HS 
		INNER JOIN hocsinh_mon AS m ON m.ID_HS=n.ID_HS AND m.ID_LM='$lmID' 
		WHERE n.ID_LM='$lmID' AND n.new_de='$new_de' ORDER BY h.cmt ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function clean_new_nhayde2($lmID) {
		
		global $db;

        $query="DELETE FROM nhayde WHERE ID_HS IN (SELECT ID_HS FROM hocsinh_mon WHERE ID_LM='$lmID') AND ID_LM='$lmID'";

        mysqli_query($db,$query);

	}

	// Đã check
	function back_new_nhayde($hsID, $old_de, $lmID) {
		
		global $db;
	
		$query="UPDATE hocsinh_mon SET de='$old_de' WHERE ID_HS='$hsID' AND ID_LM='$lmID'";
		
		mysqli_query($db,$query);
		
		$query2="DELETE FROM nhayde WHERE ID_HS='$hsID' AND ID_LM='$lmID'";
		
		mysqli_query($db,$query2);

        delete_thongbao($hsID,1,"nhay-de",$lmID);
        delete_thongbao($hsID,0,"nhay-de",$lmID);
	}

	// Đã check
	function insert_diemdanh($ddID, $hsID, $is_tinh, $is_hoc, $is_kt, $caCheck) {
		
		global $db;
	
		$query="INSERT INTO diemdanh(ID_DD,ID_HS,ca_check,is_hoc,is_tinh,is_kt)
										value('$ddID','$hsID','$caCheck','$is_hoc','$is_tinh','$is_kt')";
										
		mysqli_query($db,$query);
	}

	// Đã check
	function check_exited_diemdanh($cumID, $hsID, $lmID, $monID) {
		
		global $db;
		
		$query="SELECT d.ID_STT FROM diemdanh AS d 
		INNER JOIN diemdanh_buoi AS b ON b.ID_STT=d.ID_DD AND b.ID_CUM='$cumID' AND b.ID_LM='$lmID' AND b.ID_MON='$monID' WHERE d.ID_HS='$hsID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}

	// Đã check
	function check_exited_diemdanh2($ddID, $hsID) {
		
		global $db;
		
		$query="SELECT * FROM diemdanh WHERE ID_DD='$ddID' AND ID_HS='$hsID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}
	
	function get_diemdanh_buoi_cd($cdID) {
		
		global $db;
	
		$query="SELECT * FROM diemdanh_buoi WHERE ID_CD='$cdID' ORDER BY date DESC";
		
		$result=mysqli_query($db,$query);
		$date="0000-00-00";
		$cd_day=array();
		while($data=mysqli_fetch_assoc($result)) {
			if($data["date"]!=$date) {
				$cd_array[]=array(
					"ddID" => $data["ID_STT"],
					"date" => $data["date"],
					"thu" => date("w", strtotime($data["date"]))+1
				);
				$date=$data["date"];
			}
		}
		
		return $cd_array;
	}
	
	function get_diemdanh_cd($ddID, $caID, $diemdanh_string) {
		
		global $db;
	
		$query="SELECT * FROM $diemdanh_string WHERE ID_DD='$ddID' AND ID_CA='$caID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function delete_diemdanh($sttID) {
		
		global $db;
	
		$query="DELETE FROM diemdanh WHERE ID_STT='$sttID'";
		
		mysqli_query($db,$query);
	}

    function delete_diemdanh2($cumID, $hsID, $lmID, $monID) {

        global $db;

        $query="DELETE FROM diemdanh WHERE ID_DD IN (SELECT ID_STT FROM diemdanh_buoi WHERE ID_CUM='$cumID' AND ID_LM='$lmID' AND ID_MON='$monID') AND ID_HS='$hsID'";

        mysqli_query($db,$query);
    }

	// Đã check
    function get_diemdanh_detail($sttID) {

        global $db;

        $query="SELECT d.ID_HS,d.ID_DD,b.ID_CUM,date,ID_LM,ID_MON FROM diemdanh AS d INNER JOIN diemdanh_buoi AS b ON b.ID_STT=d.ID_DD WHERE d.ID_STT='$sttID'";

        $result=mysqli_query($db,$query);

        return $result;
    }

	// Đã check
	function edit_diemdanh($sttID, $hsID, $is_hoc, $is_tinh, $is_kt) {
		
		global $db;
	
		$query="UPDATE diemdanh SET is_hoc='$is_hoc',is_tinh='$is_tinh',is_kt='$is_kt' WHERE ID_STT='$sttID' AND ID_HS='$hsID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function place_ngoisao_hs($hsID, $ngay, $tien, $lmID) {
		
		global $db;
	
		$query="INSERT INTO ngoi_sao(ID_HS,buoi,tien,ketqua,status,ID_LM)
								value('$hsID','$ngay','$tien','Z','pending','$lmID')";
								
		mysqli_query($db,$query);			
	}

	// Đã check
	function check_exited_ngoisao($hsID, $ngay, $lmID) {
		
		global $db;
	
		$query="SELECT * FROM ngoi_sao WHERE ID_HS='$hsID' AND buoi='$ngay' AND status='pending' AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==0) {
			return false;
		} else {
			return true;
		}
	}
	
	function get_current_ngoisao($hsID, $lmID) {
		
		global $db;
		
		$query="SELECT * FROM ngoi_sao WHERE ID_HS='$hsID' AND ketqua='Z' AND status='pending' AND ID_LM='$lmID' ORDER BY ID_STT DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_history_ngoisao($hsID, $lmID) {
		
		global $db;
	
		$query="SELECT n.*,d.diem FROM ngoi_sao AS n INNER JOIN buoikt AS b ON b.ngay=n.buoi INNER JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS='$hsID' WHERE n.ID_HS='$hsID' AND n.ketqua!='Z' AND n.status='done' AND n.ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function delete_ngoisao($hsID, $nsID) {
		
		global $db;
		
		$query0="SELECT * FROM ngoi_sao WHERE ID_STT='$nsID'";
		$result0=mysqli_query($db,$query0);
		$data0=mysqli_fetch_assoc($result0);
		cong_tien_hs($hsID, get_muctien("hope_star_coc"), "Hoàn tiền do hủy ngôi sao hy vọng cho ngày thi ".format_dateup($data0["buoi"]),"","");
		$query="DELETE FROM ngoi_sao WHERE ID_STT='$nsID' AND ID_HS='$hsID' AND status='pending'";
		
		mysqli_query($db,$query);
	}
	
	function delete_ngoisao_ad($hsID, $nsID) {
		
		global $db;
		
		$query0="SELECT * FROM ngoi_sao WHERE ID_STT='$nsID'";
		$result0=mysqli_query($db,$query0);
		$data0=mysqli_fetch_assoc($result0);
		cong_tien_hs($hsID, get_muctien("hope_star_coc"), "Hoàn tiền do bị admin hủy ngôi sao hy vọng cho ngày thi ".format_dateup($data0["buoi"]),"","");
		$query="DELETE FROM ngoi_sao WHERE ID_STT='$nsID' AND ID_HS='$hsID' AND status='pending'";
		
		mysqli_query($db,$query);
	}
	
	function ketqua_ngoisao($sttID, $kq) {
		
		global $db;
	
		$query="UPDATE ngoi_sao SET ketqua='$kq',status='done' WHERE ID_STT='$sttID'";
		
		mysqli_query($db,$query);
	}
	
	function get_all_sync_sort($position, $display) {
		
		global $db;
	
		$query="SELECT * FROM sync ORDER BY ID_SYNC DESC LIMIT $position,$display";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_sync() {
		
		global $db;
	
		$query="SELECT * FROM sync";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_pending_ngoisao($monID, $position, $display) {
		
		global $db;
	
		$query="SELECT * FROM ngoi_sao WHERE ketqua='Z' AND status='pending' AND ID_MON='$monID' ORDER BY ID_STT DESC LIMIT $position,$display";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_list_ngoisao($ngay, $monID, $lopID, $position, $display) {
		
		global $db;
	
		$query="SELECT * FROM ngoi_sao WHERE buoi='$ngay' AND ID_LOP='$lopID' AND ID_MON='$monID' ORDER BY ID_STT DESC LIMIT $position,$display";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_all_list_ngoisao($ngay, $lmID) {
		
		global $db;
	
		$query="SELECT * FROM ngoi_sao WHERE buoi='$ngay' AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_pending_ngoisao($monID) {
		
		global $db;
	
		$query="SELECT * FROM ngoi_sao WHERE ketqua='Z' AND status='pending' AND ID_MON='$monID' ORDER BY ID_STT DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_done_ngoisao($monID, $position, $display) {
		
		global $db;
	
		$query="SELECT * FROM ngoi_sao WHERE ketqua!='Z' AND status='done' AND ID_MON='$monID' ORDER BY ID_STT DESC LIMIT $position,$display";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_done_ngoisao($monID) {
		
		global $db;
	
		$query="SELECT * FROM ngoi_sao WHERE ketqua!='Z' AND status='done' AND ID_MON='$monID' ORDER BY ID_STT DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function callback_ngoisao($nsID) {
		
		global $db;
	
		$query="SELECT * FROM ngoi_sao WHERE ID_STT='$nsID'";
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_ngoisao_hs($hsID, $lmID) {
		
		global $db;
		
		$query="SELECT * FROM ngoi_sao WHERE ID_HS='$hsID' AND ID_LM='$lmID' ORDER BY ID_STT DESC LIMIT 50";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function delete_diemdanh_buoi($ddID) {
		
		global $db;
	
		$query="DELETE FROM diemdanh_buoi WHERE ID_STT='$ddID'";
		
		mysqli_query($db,$query);
	}
	
	function get_diemdanh_buoi($position, $display) {
		
		global $db;
	
		$query="SELECT * FROM diemdanh_buoi ORDER BY ID_STT DESC LIMIT $position,$display";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_diemdanh_buoi() {
		
		global $db;
	
		$query="SELECT * FROM diemdanh_buoi";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function add_diemdanh_buoi($cdID, $caID, $cum, $ngay, $lmID, $monID) {
		
		global $db;
		
		$cumID=get_cum_buoi($cdID, $ngay, $lmID, $monID, $cum);
		if($cumID==0) {
			//$now=DateTime::createFromFormat('Y-m-d', $ngay);
			$now=date_create($ngay);
			date_add($now,date_interval_create_from_date_string("-1 day"));
			$back_date=date_format($now,"Y-m-d");
			$cumID=get_cum_buoi($cdID, $back_date, $lmID, $monID, $cum);
			if($cumID==0) {
				$cumID=get_new_cum_buoi($lmID,$monID);
			}
		}
		
		$query="INSERT INTO diemdanh_buoi(ID_CD,ID_CUM,ID_CA,cum,date,ID_LM,ID_MON)
									value('$cdID','$cumID','$caID','$cum','$ngay','$lmID','$monID')";
									
		mysqli_query($db,$query);
		
		return check_exited_buoi($cdID,$caID,$ngay,$lmID,$monID);
		
	}

	// Đã check
	function get_cum_buoi($cdID, $ngay, $lmID, $monID, $cum) {
	
		global $db;

		if($cum) {
            $query = "SELECT ID_CUM FROM diemdanh_buoi WHERE ID_CD='$cdID' AND cum='$cum' AND date='$ngay' AND ID_LM='$lmID' AND ID_MON='$monID'";
        } else {
            $query = "SELECT ID_CUM FROM diemdanh_buoi WHERE ID_CD='$cdID' AND date='$ngay' AND ID_LM='$lmID' AND ID_MON='$monID'";
        }
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$data=mysqli_fetch_assoc($result);
			return $data["ID_CUM"];
		} else {
			return 0;
		}
	}

	// Đã check
    function get_cum_buoi_ngay($ngay, $lmID, $monID) {
    
        global $db;
    
        $query="SELECT ID_CUM FROM diemdanh_buoi WHERE date='$ngay' AND ID_LM='$lmID' AND ID_MON='$monID'";
    
        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)!=0) {
            $data=mysqli_fetch_assoc($result);
            return $data["ID_CUM"];
        } else {
            return 0;
        }
    }

    // Đã check
	function get_new_cum_buoi($lmID,$monID) {
		
		global $db;
		
		$query="SELECT ID_CUM FROM diemdanh_buoi WHERE ID_LM='$lmID' AND ID_MON='$monID' ORDER BY ID_CUM DESC LIMIT 1";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["ID_CUM"]+1;
	}
	
	function get_now_buoi() {
		
		global $db;
	
		$now=date("Y-m-d");
		$query="SELECT * FROM diemdanh_buoi WHERE ID_CD!='0' AND date='$now'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_now_buoi_kt() {
		
		global $db;
	
		$now=date("Y-m-d");
		$query="SELECT * FROM diemdanh_buoi WHERE ID_CD='0' AND date='$now'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_date_dd($ddID) {
		
		global $db;
	
		$query="SELECT date FROM diemdanh_buoi WHERE ID_STT='$ddID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["date"];
	}

	// Đã check
    function get_date_buoi($cumID,$lmID,$monID) {

        global $db;

        $query="SELECT date FROM diemdanh_buoi WHERE ID_CUM='$cumID' AND ID_LM='$lmID' AND ID_MON='$monID'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["date"];
    }

	// Đã check
	function check_exited_buoi($cdID, $caID, $ngay, $lmID, $monID) {
		
		global $db;
	
		$query="SELECT ID_STT,ID_CUM FROM diemdanh_buoi WHERE ID_CD='$cdID' AND ID_CA='$caID' AND date='$ngay' AND ID_LM='$lmID' AND ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$data=mysqli_fetch_assoc($result);
			return array($data["ID_STT"],$data["ID_CUM"]);
		} else {
			return array();
		}
	}
	
	function get_latest_undo_chuyende($dad) {
		
		global $db;
	
		$query="SELECT ID_CD FROM chuyende WHERE dad='$dad' AND del='0' ORDER BY ID_CD DESC";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["ID_CD"];
	}

	// Đã check
	function get_all_lido() {
		
		global $db;
	
		$query="SELECT * FROM lido ORDER BY ID_LD DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_all_lido2() {
		
		global $db;
	
		$query="SELECT * FROM lido ORDER BY ID_LD ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function count_lido($ldID, $diem_string) {
		
		global $db;
	
		$query="SELECT * FROM diemkt WHERE note='$ldID'";
		
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
	}
	
	function add_lido($name, $mau) {
		
		global $db;
		
		$string=unicode_convert($name);
	
		$query="INSERT INTO lido(name,string,mau)
							value('$name','$string','$mau')";
							
		mysqli_query($db,$query);
	}
	
	function edit_lido($ldID, $name, $mau) {
		
		global $db;
		
		$string=unicode_convert($name);
	
		$query="UPDATE lido SET name='$name',string='$string',mau='$mau' WHERE ID_LD='$ldID'";
		
		mysqli_query($db,$query);
	}
	
	function delete_diem($idDiem, $buoiID, $hsID, $lmID) {
		
		global $db;
	
		$query="DELETE FROM diemkt WHERE ID_DIEM='$idDiem' AND ID_LM='$lmID'";
		
		mysqli_query($db,$query);
		
		$query2="DELETE FROM chuyende_diem WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_LM='$lmID'";
		
		mysqli_query($db,$query2);
	}

	// Đã check
	function get_all_hs_nghi($lmID) {
		
		global $db;

        $query="SELECT n.*,h.cmt,h.fullname,h.facebook,h.sdt_bo,h.sdt_me,h.note FROM hocsinh_nghi AS n 
        INNER JOIN hocsinh AS h ON h.ID_HS=n.ID_HS 
        INNER JOIN hocsinh_mon AS m ON m.ID_HS=n.ID_HS AND m.ID_LM=n.ID_LM
        WHERE n.ID_LM='$lmID' ORDER BY n.date DESC";

		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function check_exited_nghihoc($hsID, $lmID) {
		
		global $db;
	
		$query="SELECT * FROM hocsinh_nghi WHERE ID_HS='$hsID' AND ID_LM='$lmID'";
	
		$result=mysqli_query($db,$query);
		
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}
	
	function add_nghihoc($hsID, $monID) {
		
		global $db;
	
		$query="INSERT INTO hocsinh_nghi(ID_HS,ID_MON,date)
									value('$hsID','$monID',now())";
									
		mysqli_query($db,$query);
		
	}

	// Đã check
	function add_nghihoc2($hsID, $date, $lmID) {
		
		global $db;
	
		$query="INSERT INTO hocsinh_nghi(ID_HS,ID_LM,date)
									value('$hsID','$lmID','$date')";
									
		mysqli_query($db,$query);
		
	}

	// Đã check
	function add_nghidai($hsID, $start, $end, $note, $loai, $lmID) {
		
		global $db;
	
		$query="INSERT INTO nghi_temp(ID_HS,start,end,note,loai,ID_LM)
									value('$hsID','$start','$end','$note','$loai','$lmID')";
									
		mysqli_query($db,$query);
		
	}
	
	function edit_nghidai($sttID,$start,$end,$note) {
	
		global $db;
		
		$query="UPDATE nghi_temp SET start='$start',end='$end',note='$note' WHERE ID_STT='$sttID'";
		
		mysqli_query($db,$query);
	}
	
	function delete_nghihoc($nID) {
		
		global $db;
	
		$query="DELETE FROM hocsinh_nghi WHERE ID_N='$nID'";
									
		mysqli_query($db,$query);
		
	}

	// Đã check
	function delete_nghidai($sttID) {
		
		global $db;
	
		$query="DELETE FROM nghi_temp WHERE ID_STT='$sttID'";
									
		mysqli_query($db,$query);
		
	}

	// Đã check
    function delete_nghidai2($hsID,$lmID) {

        global $db;

        $query="DELETE FROM nghi_temp WHERE ID_HS='$hsID' AND loai='1' AND ID_LM='$lmID'";

        mysqli_query($db,$query);

    }

    // Đã check
	function get_gender($gender) {
	
		if($gender==1) {
			return "Nam";
		} else {
			return "Nữ";
		}
		
	}

	// Đã check
	function count_hs_nghi_gender($gender, $lmID) {
		
		global $db;

        $query = "SELECT COUNT(n.ID_HS) AS dem FROM hocsinh_nghi AS n 
        INNER JOIN hocsinh_mon AS m ON m.ID_HS=n.ID_HS AND m.ID_LM='$lmID' 
        INNER JOIN hocsinh AS h ON h.ID_HS=n.ID_HS AND h.gender='$gender' 
        WHERE n.ID_LM='$lmID'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
		
		return $data["dem"];
		
	}

	// Đã check
	function count_hs_gender($gender, $lmID) {
		
		global $db;

        $query = "SELECT COUNT(h.ID_HS) AS dem FROM hocsinh AS h 
        INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
        WHERE h.gender='$gender'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["dem"];
	
	}

    // Đã check
	function check_more_lop_mon($hsID, $lmID, $monID) {

        global $db;

        $query="SELECT ID_STT FROM hocsinh_mon WHERE ID_HS='$hsID' AND ID_LM IN (SELECT ID_LM FROM lop_mon WHERE ID_LM!='$lmID' AND ID_MON='$monID')";

        $result=mysqli_query($db,$query);

        if(mysqli_num_rows($result)!=0) {
            return true;
        } else {
            return false;
        }
    }

    // Đã check
	function get_hs_tang_cuong($hsID, $lmID, $monID) {
		
		global $db;
	
		$query="SELECT c.thu,g.gio FROM ca_hientai AS h 
		INNER JOIN cahoc AS c ON c.ID_CA=h.ID_CA 
		INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM!='$lmID' AND g.ID_LM!='0' AND g.ID_MON='$monID' 
		WHERE h.ID_HS='$hsID'";

		$result=mysqli_query($db,$query);
		$ca_array=array();
		while($data=mysqli_fetch_assoc($result)) {
            $ca_array[]=" Thứ ".$data["thu"]." (".$data["gio"].") ";
		}
		
		$lich_hoc=implode("-", $ca_array);
		
		return $lich_hoc;
	}

	// Đã check
	function get_hs_lich_hoc($hsID, $lmID, $monID) {
		
		global $db;

        $query="SELECT c.thu,g.gio,g.buoi,g.thutu FROM ca_hientai AS h 
		INNER JOIN cahoc AS c ON c.ID_CA=h.ID_CA 
		INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='$lmID' AND g.ID_MON='$monID' 
		WHERE h.ID_HS='$hsID' ORDER BY c.thu ASC";

		$result=mysqli_query($db,$query);
		$ca_array=array();
		while($data=mysqli_fetch_assoc($result)) {
//            $ca_array[]=substr($data["buoi"],1,1)."".$data["thu"]."".$data["thutu"];
            $ca_array[]=substr($data["buoi"],1,1)."".$data["thu"];
		}
		
		$lich_hoc=implode(" - ", $ca_array);
		
		return $lich_hoc;
	}

    // Đã check
	function get_hs_lich_hoc2($hsID, $lmID, $monID) {
		
		global $db;
	
		$query="SELECT c.thu,g.gio FROM ca_hientai AS h 
		INNER JOIN cahoc AS c ON c.ID_CA=h.ID_CA 
		INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='$lmID' AND g.ID_MON='$monID' 
		WHERE h.ID_HS='$hsID' ORDER BY c.thu ASC";

		$result=mysqli_query($db,$query);
		$ca_array=array();
		while($data=mysqli_fetch_assoc($result)) {
            $ca_array[]=" Thứ ".$data["thu"]." (".$data["gio"].") ";
		}
		
		$lich_hoc=implode("-", $ca_array);
		
		return $lich_hoc;
	}

    // Đã check
	function get_hs_lich_hoc3($hsID, $lmID, $monID) {
		
		global $db;

        $query="SELECT c.thu,g.gio FROM ca_hientai AS h 
		INNER JOIN cahoc AS c ON c.ID_CA=h.ID_CA 
		INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='$lmID' AND g.ID_MON='$monID' 
		WHERE h.ID_HS='$hsID' ORDER BY c.thu ASC";

		$result=mysqli_query($db,$query);
		$ca_array=array();
		while($data=mysqli_fetch_assoc($result)) {
            $ca_array[]=" Thứ ".$data["thu"]." (".$data["gio"].") ";
		}
		
		$lich_hoc=implode("<br />", $ca_array);
		
		return $lich_hoc;
	}

    // Đã check
	function get_hs_lich_thi($hsID, $monID) {
		
		global $db;
		
		$query="SELECT c.thu,g.gio FROM ca_hientai AS h 
		INNER JOIN cahoc AS c ON c.ID_CA=h.ID_CA 
		INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='0' AND g.ID_MON='$monID' 
		WHERE h.ID_HS='$hsID'";

		$result=mysqli_query($db,$query);
		$lich_thi=NULL;
		while($data=mysqli_fetch_assoc($result)) {
            $lich_thi=" Chủ Nhật (".$data["gio"].")";
            break;
		}
		
		return $lich_thi;
	}

    function get_hs_lich_thi2($hsID, $monID) {

        global $db;

        $query="SELECT c.thu,g.gio FROM ca_hientai AS h 
            INNER JOIN cahoc AS c ON c.ID_CA=h.ID_CA 
            INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='0' AND g.ID_MON='$monID' 
            WHERE h.ID_HS='$hsID'";

        $result=mysqli_query($db,$query);
        $lich_thi=NULL;
        while($data=mysqli_fetch_assoc($result)) {
            $lich_thi=$data["gio"];
            break;
        }

        return $lich_thi;
    }

	// Đã check
	function hoc_lai($hsID, $lmID) {
		
		global $db;
	
		$query="DELETE FROM hocsinh_nghi WHERE ID_HS='$hsID' AND ID_LM='$lmID'";
		
		mysqli_query($db,$query);
		
	}

	// Đã check
	function nghi_hoc($hsID, $date_out, $lmID) {
		
		global $db;
		
		$query="INSERT INTO hocsinh_nghi(ID_HS,ID_LM,date) SELECT * FROM (SELECT '$hsID' AS hs,'$lmID' AS lm,'$date_out' AS now) AS tmp WHERE NOT EXISTS (SELECT ID_HS,ID_LM FROM hocsinh_nghi WHERE ID_HS='$hsID' AND ID_LM='$lmID') LIMIT 1";

		mysqli_query($db,$query);
	}
	
	function check_phone($hsID, $sdt) {
		
		global $db;
		
		$query="INSERT INTO check_phone(ID_HS,sdt_check) SELECT * FROM (SELECT '$hsID' AS hs,'$sdt' AS sdt) AS tmp WHERE NOT EXISTS (SELECT ID_HS,sdt_check FROM check_phone WHERE ID_HS='$hsID' AND sdt_check='$sdt') LIMIT 1";
		
		/*$query0="SELECT ID_C FROM check_phone WHERE ID_HS='$hsID' AND sdt_check='$sdt'";
		
		$result0=mysqli_query($db,$query0);
		if(mysqli_num_rows($result0)==0) {
			$query="INSERT INTO check_phone(ID_HS,sdt_check)
									value('$hsID','$sdt')";
									
			mysqli_query($db,$query);
		}*/
		mysqli_query($db,$query);
		
	}
	
	function uncheck_phone($hsID, $sdt) {
		
		global $db;
	
		$query="DELETE FROM check_phone WHERE ID_HS='$hsID' AND sdt_check='$sdt'";
								
		mysqli_query($db,$query);
	}
	
	function get_truong_hs($truong) {
		
		global $db;
	
		$query="SELECT name FROM truong WHERE ID_T='$truong'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["name"];
	}
	
	function get_next_hsID() {
		
		global $db;
	
		$query="SELECT ID_HS FROM hocsinh ORDER BY ID_HS DESC LIMIT 1";
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);

		return $data["ID_HS"]+1;
	}

	// Đã check
	function get_next_hs_lop($lopID) {
		
		global $db;
	
		$query="SELECT MAX(cmt) AS max FROM hocsinh WHERE lop='$lopID' ORDER BY cmt ASC";
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return substr($data["max"],3,4)+2-1;
	}

	// Đã check
	function get_lop_from_ma($cmt) {

        $temp = explode("-",$cmt);
        $kq = 0;

        $result=get_all_lop();
        while($data=mysqli_fetch_assoc($result)) {
            $temp2 = substr($data["name"],2,2);
            if($temp[0] == $temp2) {
                $kq = $data["ID_LOP"];
                break;
            }
        }

        return $kq;
    }

	// Đã check
	function add_new_hs($cmt, $van, $password, $name, $birth, $gender, $face, $truong, $sdt, $sdt_bo, $sdt_me, $lop) {
		
		global $db;
		if($gender==1) {
			$avata="male.jpg";
		} else {
			$avata="female.jpg";
		}
	
		$query="INSERT INTO hocsinh(cmt,vantay,password,fullname,namestring,avata,birth,gender,facebook,truong,sdt,sdt_bo,sdt_me,taikhoan,note,lop)
							value('$cmt','$van','$password','$name','".unicode_convert($name)."','$avata','$birth','$gender','$face','$truong','$sdt','$sdt_bo','$sdt_me','0','','$lop')";
							
		mysqli_query($db,$query);
        $id=mysqli_insert_id($db);

        if($sdt!="X" && $sdt!="") {
            upload_google_contact($cmt."-HS",$sdt);
        }
        if($sdt_bo!="X" && $sdt_bo!="") {
            upload_google_contact($cmt."-Bố",$sdt_bo);
        }
        if($sdt_me!="X" && $sdt_me!="") {
            upload_google_contact($cmt."-Mẹ",$sdt_me);
        }
		
		return $id;
	}

	// Đã check
	function edit_hs($hsID, $cmt, $van, $pass, $name, $birth, $gender, $face, $truong, $sdt, $sdt_bo, $sdt_me) {
		
		global $db;

        $result=get_hs_detail($cmt);
        $data=mysqli_fetch_assoc($result);
        if(valid_maso($cmt) && $sdt!="X" && $sdt!="" && $data["sdt"]!=$sdt) {
            update_google_contact($cmt."-HS",$sdt);
        }
        if(valid_maso($cmt) && $sdt_bo!="X" && $sdt_bo!="" && $data["sdt_bo"]!=$sdt_bo) {
            update_google_contact($cmt."-Bố",$sdt_bo);
        }
        if(valid_maso($cmt) && $sdt_me!="X" && $sdt_me!="" && $data["sdt_me"]!=$sdt_me) {
            update_google_contact($cmt."-Mẹ",$sdt_me);
        }

		if($pass!="") {
			$query="UPDATE hocsinh SET vantay='$van',password='$pass',fullname='$name',namestring='".unicode_convert($name)."',birth='$birth',gender='$gender',facebook='$face',truong='$truong',sdt='$sdt',sdt_bo='$sdt_bo',sdt_me='$sdt_me' WHERE ID_HS='$hsID'";
		} else {
			$query="UPDATE hocsinh SET vantay='$van',fullname='$name',namestring='".unicode_convert($name)."',birth='$birth',gender='$gender',facebook='$face',truong='$truong',sdt='$sdt',sdt_bo='$sdt_bo',sdt_me='$sdt_me' WHERE ID_HS='$hsID'";
		}
		
		mysqli_query($db,$query);
	}

	// Đã check
	function check_van_tay($vantay) {

        global $db;

        $query="SELECT ID_HS FROM hocsinh WHERE vantay='$vantay'";

        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result) != 0) {
            return true;
        } else {
            return false;
        }
    }

	// Đã check
	function add_hs_mon($hsID, $de, $date_in, $lmID) {
		
		global $db;
		
		$query0="SELECT ID_HS FROM hocsinh_mon WHERE ID_HS='$hsID' AND ID_LM='$lmID'";
		
		$result0=mysqli_query($db,$query0);
		
		if(mysqli_num_rows($result0)==0) {
			$query="INSERT INTO hocsinh_mon(ID_HS,de,ID_LM,date_in)
										value('$hsID','$de','$lmID','$date_in')";
										
			mysqli_query($db,$query);
		} else {
			$query="UPDATE hocsinh_mon SET date_in='$date_in' WHERE ID_HS='$hsID' AND ID_LM='$lmID'";
			mysqli_query($db,$query);
		}
		
	}
	
	function remove_hs_mon($hsID, $monID) {
		
		global $db;
	
		$query="DELETE FROM hocsinh_mon WHERE ID_HS='$hsID' AND ID_MON='$monID'";
		
		mysqli_query($db,$query);
		
	}
	
	function add_hs_to_ca($caID, $hsID, $cum, $ca_hientai_string, $ca_codinh_string) {
		
		global $db;
	
		$query="INSERT INTO $ca_codinh_string(ID_CA,ID_HS,cum)
										value('$caID','$hsID','$cum')";
										
		mysqli_query($db,$query);
		
		$query2="INSERT INTO $ca_hientai_string(ID_CA,ID_HS,cum)
										value('$caID','$hsID','$cum')";
									
		mysqli_query($db,$query2);
	}

	// Đã check
	function remove_hs_ca($caID, $hsID, $cum) {
		
		global $db;
	
		$query="DELETE FROM ca_codinh WHERE ID_CA='$caID' AND ID_HS='$hsID' AND cum='$cum'";
		
		mysqli_query($db,$query);
		
		$query2="DELETE FROM ca_hientai WHERE ID_CA='$caID' AND ID_HS='$hsID' AND cum='$cum'";
		
		mysqli_query($db,$query2);
	}

	// Đã check
	function edit_nghihoc($nID,$date_out) {

	    global $db;

        $query="UPDATE hocsinh_nghi SET date='$date_out' WHERE ID_N='$nID'";

        mysqli_query($db,$query);
    }

    // Đã check
    function edit_note_hocsinh($hsID,$note) {

        global $db;

        $query="UPDATE hocsinh SET note='$note' WHERE ID_HS='$hsID'";

        mysqli_query($db,$query);
    }

	// Đã check
	function remove_hs_all_ca($hsID, $lmID, $monID) {
		
		global $db;
		
		$query="DELETE FROM ca_codinh WHERE ID_HS='$hsID' AND cum IN (SELECT ID_CUM FROM cum WHERE ID_LM IN ('$lmID','0') AND ID_MON='$monID')";
		
		mysqli_query($db,$query);
		
		$query="DELETE FROM ca_hientai WHERE ID_HS='$hsID' AND cum IN (SELECT ID_CUM FROM cum WHERE ID_LM IN ('$lmID','0') AND ID_MON='$monID')";
		
		mysqli_query($db,$query);

        $query="DELETE FROM ca_quyen WHERE ID_HS='$hsID' AND ID_CA IN (SELECT c.ID_CA FROM cahoc AS c INNER JOIN cum AS u ON u.ID_CUM=c.cum AND u.ID_LM IN ('$lmID','0') AND u.ID_MON='$monID')";

        mysqli_query($db,$query);
	}

	// Đã check
	function get_hs_detail($cmt) {
		
		global $db;
		
		$query="SELECT ID_HS,cmt,vantay,fullname,avata,birth,gender,facebook,truong,sdt,sdt_bo,sdt_me,taikhoan,lop,hot FROM hocsinh WHERE cmt='$cmt'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function check_exited_hs($cmt, $lopID) {
		
		global $db;
		
		$query="SELECT ID_HS FROM hocsinh WHERE cmt='$cmt' AND lop='$lopID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}
	
	function check_exited_hocsinh($cmt) {
		
		global $db;
		
		$query="SELECT ID_HS FROM hocsinh WHERE cmt='$cmt'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}
	
	function check_exited_hocsinh2($cmt) {
		
		global $db;
		
		$query="SELECT ID_HS FROM hocsinh WHERE cmt='$cmt'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$data=mysqli_fetch_assoc($result);
			return $data["ID_HS"];
		} else {
			return 0;
		}
	}

	// Đã check
	function get_ca_mon_hs($hsID, $lmID, $monID) {
		
		global $db;
	
		$query="SELECT d.ID_CA,d.cum FROM ca_codinh AS d INNER JOIN cum AS c ON c.ID_CUM=d.cum AND c.ID_LM='$lmID' AND c.ID_MON='$monID' WHERE d.ID_HS='$hsID' ORDER BY d.cum ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function is_check_phone($hsID, $sdt) {
		
		global $db;
	
		$query="SELECT ID_C FROM check_phone WHERE ID_HS='$hsID' AND sdt_check='$sdt'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return 1;
		} else {
			return 0;
		}
	}
	
	function is_check_hop($hsID) {
	
		global $db;
		
		$query="SELECT ID_H FROM check_hop WHERE ID_HS='$hsID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return 1;
		} else {
			return 0;
		}
	}
	
	function get_info_truong($truong) {
		
		global $db;
		
		$query="SELECT name FROM truong WHERE ID_T='$truong'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_tinhtp() {
	
		global $db;
		
		$query="SELECT * FROM tinh_tp ORDER BY ID_TP ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_truong() {
	
		global $db;
		
		$query="SELECT * FROM truong ORDER BY name ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_quan($tpID) {
	
		global $db;
		
		$query="SELECT * FROM quan_huyen WHERE tinhtp='$tpID' ORDER BY ID_QH ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_quan_sort($tpID, $position, $display) {
	
		global $db;
		
		$query="SELECT * FROM quan_huyen WHERE tinhtp='$tpID' ORDER BY ID_QH ASC LIMIT $position,$display";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_truong_sort($quanID) {
	
		global $db;
		
		$query="SELECT * FROM truong WHERE quan='$quanID' ORDER BY name ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_quan($tpID) {
	
		global $db;
		
		$query="SELECT * FROM quan_huyen WHERE tinhtp='$tpID' ORDER BY ID_QH ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_truong($quanID) {
	
		global $db;
		
		$query="SELECT * FROM truong WHERE quan='$quanID' ORDER BY ID_T ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_tp_name($tpID) {
	
		global $db;
		
		$query="SELECT thanhpho FROM tinh_tp WHERE ID_TP='$tpID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["thanhpho"];
	}
	
	function get_quan_name($quanID) {
	
		global $db;
		
		$query="SELECT quanhuyen FROM quan_huyen WHERE ID_QH='$quanID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["quanhuyen"];
	}
	
	function count_truong_quan($quan) {
		
		global $db;
	
		$query="SELECT ID_T FROM truong WHERE quan='$quan'";
		
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
	}
	
	function count_hs_truong($truongID) {
	
		global $db;
	
		$query="SELECT ID_HS FROM hocsinh WHERE truong='$truongID'";
		
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
	}
	
	function edit_quanhuyen($quanID, $quan) {
	
		global $db;
		
		$query="UPDATE quan_huyen SET quanhuyen='$quan' WHERE ID_QH='$quanID'";
		
		mysqli_query($db,$query);
		
	}
	
	function edit_truong($truongID, $truong) {
	
		global $db;
		
		$query="UPDATE truong SET name='$truong',string='".unicode_convert($truong)."' WHERE ID_T='$truongID'";
		
		mysqli_query($db,$query);
		
	}
	
	function get_quan_truong($truongID) {
	
		global $db;
		
		$query="SELECT quan FROM truong WHERE ID_T='$truongID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["quan"];
	}
	
	function get_tp_quan($quanID) {
		
		global $db;
		
		$query="SELECT tinhtp FROM quan_huyen WHERE ID_QH='$quanID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["tinhtp"];
	}
	
	function uncheck_hop($hsID) {
	
		global $db;
	
		$query="DELETE FROM check_hop WHERE ID_HS='$hsID'";
		
		mysqli_query($db,$query);
	}
	
	function check_hop($hsID) {
	
		global $db;
		
		$query="INSERT INTO check_hop(ID_HS) SELECT * FROM (SELECT '$hsID' AS hs) AS tmp WHERE NOT EXISTS (SELECT ID_HS FROM check_hop WHERE ID_HS='$hsID') LIMIT 1";
	
		/*$query="INSERT INTO check_hop(ID_HS)
								value('$hsID')";*/
		
		mysqli_query($db,$query);
	}

	// Đã check
	function get_all_tien_am($lmID) {
		
		global $db;

        $query = "SELECT h.ID_HS,h.cmt,h.fullname,h.facebook,h.sdt_bo,h.sdt_me,h.birth,h.taikhoan,n.ID_N FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID' WHERE h.taikhoan<'0' ORDER BY h.taikhoan ASC";

		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function buoi_sort($a,$b) {
		return $a["buoi"] > $b["buoi"];
	}
	
	function hoc_sort_desc($a,$b) {
		return $a["hoc"] < $b["hoc"];
	}
	
	function hoc_sort_asc($a,$b) {
		return $a["hoc"] > $b["hoc"];
	}
	
	function nghi_sort_desc($a,$b) {
		return $a["nghi"] < $b["nghi"];
	}
	
	function nghi_sort_asc($a,$b) {
		return $a["nghi"] > $b["nghi"];
	}

    function diemtb_sort_asc($a,$b) {
        return $a["diemtb"] > $b["diemtb"];
    }
	
	function diemtb_sort_desc($a,$b) {
		return $a["diemtb"] < $b["diemtb"];
	}

    function date_sort_asc($a,$b) {
        return date_create($a["ngay"]) > date_create($b["ngay"]);
    }

    function date_sort_desc($a,$b) {
        return date_create($a["ngay"]) < date_create($b["ngay"]);
    }

	// Đã check
	function get_lichsu_nghi($hsID, $now) {
		
		global $db;
		
		$query="SELECT soluong FROM lichsu_nghi WHERE ID_HS='$hsID' AND date LIKE '$now-%'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["soluong"];
	}
	
	function count_di_thachdau($hsID, $monID) {
	
		global $db;
		
		$query="SELECT ID_STT FROM thachdau WHERE ID_HS='$hsID' AND status='done' AND ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
		
	}
	
	function count_nhan_thachdau($hsID, $monID) {
	
		global $db;
		
		$query="SELECT ID_STT FROM thachdau WHERE ID_HS2='$hsID' AND status='done' AND ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
		
	}
	
	function format_month($month) {
		
		$temp=explode("-",$month);
		
		return $temp[1]."/".$temp[0];
		
	}
	
	function format_month2($month) {
		
		$temp=explode("-",$month);
		
		return $temp[1]."/".($temp[0]%100);
		
	}
	
	function split_month($now) {
	
		$temp=explode("-",$now);
		
		return array($temp[0],$temp[1]);
	
	}
	
	function frand($min, $max, $decimals = 0) {
		
		$scale = pow(10, $decimals);
		
	  	return mt_rand($min * $scale, $max * $scale) / $scale;
	  
	}
	
	function get_background() {
		
		global $db;
		
		$query="SELECT * FROM options WHERE type='background' ORDER BY ID_O DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
		
	}
	
	function delete_background($backID) {
	
		global $db;
		
		$query="SELECT ID_O FROM options WHERE ID_O='$backID' AND type='background' AND note='none'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$query2="DELETE FROM options WHERE ID_O='$backID'";
			mysqli_query($db,$query2);
		}
		
	}
	
	function chose_background($backID) {
		
		global $db;
		
		$query="UPDATE options SET note='none' WHERE type='background' AND note='active'";
		mysqli_query($db,$query);
		
		$query2="UPDATE options SET note='active' WHERE ID_O='$backID' AND type='background'";
		mysqli_query($db,$query2);
		
	}
	
	function get_active_background() {
	
		global $db;
		
		$query="SELECT * FROM options WHERE type='background' AND note='active'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function up_background($image) {
	
		global $db;
		
		$query="UPDATE options SET note='none' WHERE type='background' AND note='active'";
		mysqli_query($db,$query);
		
		$query="INSERT INTO options(content,type,note)
							value('$image','background','active')";
							
		mysqli_query($db,$query);
							
	}

	// Đã check
	function check_cong_tien($hsID,$lmID) {
	
		global $db;
		
		$query="SELECT * FROM doica_history WHERE ID_HS='$hsID' AND ID_LM='$lmID'";
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==0) {
			$query2="INSERT INTO doica_history(ID_HS,ID_LM)
											value('$hsID','$lmID')";
			mysqli_query($db,$query2);
			return false;
		} else {
			return true;
		}
		
	}

	// Đã check
	function get_next_CN() {
		
		$now=date("Y-m-d");
		$jd=gregoriantojd(date("m"),date("j"),date("Y"));
		$now_thu=jddayofweek($jd,0)+1;
		$cn=8;
		$kc=$cn-$now_thu;
		$date=date_create($now);
		date_add($date,date_interval_create_from_date_string("$kc days"));
		$next_date=date_format($date,"Y-m-d");
		return $next_date;
		
	}
	
	function get_last_CN() {
		
		$now=date("Y-m-d");
		$now_thu=date("w", strtotime($now))+1;
		$cn=1;
		$kc=$now_thu-$cn;
		$date=date_create($now);
		date_add($date,date_interval_create_from_date_string("-$kc days"));
		$next_date=date_format($date,"Y-m-d");
		return $next_date;
		
	}

	// Đã check
	function get_lop_mon_in($lmID) {
		
		global $db;
		
		$query="SELECT date_in FROM lop_mon WHERE ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["date_in"];
		
	}

	// Đã check
    function get_date_in_hs($hsID,$lmID) {

        global $db;

        $query="SELECT date_in FROM hocsinh_mon WHERE ID_HS='$hsID' AND ID_LM='$lmID'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["date_in"];
    }

	// Đã check
	function add_tienhoc($hsID,$lmID,$oID,$money,$seri,$date_dong,$date_dong2,$note,$sttID) {
		
		global $db;
		
		if($sttID!=0) {
			$query="UPDATE tien_hoc SET money='$money',date_dong='$date_dong',date_dong2='$date_dong2',code='$seri',note='$note' WHERE ID_STT='$sttID' AND ID_HS='$hsID' AND ID_LM='$lmID' AND who='$oID'";
		} else {
			$query="INSERT INTO tien_hoc(ID_HS,money,ID_LM,date_nhap,date_dong,date_dong2,code,note,who)
								value('$hsID','$money','$lmID',now(),'$date_dong','$date_dong2','$seri','$note','$oID')";
		}
		mysqli_query($db,$query);
	}

	function check_same_seri($seri) {

	    global $db;

        if($seri!=0) {
            $query = "SELECT ID_STT FROM tien_hoc WHERE code='$seri'";

            $result = mysqli_query($db, $query);
            if (mysqli_num_rows($result) != 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
	
	function delete_tienhoc($sttID,$oID) {
	
		global $db;
		
		$query="DELETE FROM tien_hoc WHERE ID_STT='$sttID' AND who='$oID'";
		
		mysqli_query($db,$query);
	}
	
	function get_all_date_nhap() {
	
		global $db;
		
		$query="SELECT DISTINCT date_nhap FROM tien_hoc ORDER BY date_nhap DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function check_date_nhap($date) {
	
		global $db;
		
		$query="SELECT ID_STT FROM tien_hoc WHERE date_nhap='$date'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}
	
	function get_tien_hoc_date($date) {
	
		global $db;
		
		$query="SELECT tien_hoc.ID_HS,tien_hoc.money,tien_hoc.ID_MON,tien_hoc.date_nhap,hocsinh.cmt,hocsinh.fullname,hocsinh.birth,hocsinh.truong,hocsinh.lop FROM tien_hoc  INNER JOIN hocsinh ON hocsinh.ID_HS=tien_hoc.ID_HS WHERE tien_hoc.date_nhap='$date' ORDER BY ID_STT DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_tien_hoc_mon($monID) {
	
		global $db;
		
		$query="SELECT tien_hoc.ID_HS,tien_hoc.money,tien_hoc.ID_MON,tien_hoc.date_nhap,hocsinh.cmt,hocsinh.fullname,hocsinh.birth,hocsinh.truong,hocsinh.lop FROM tien_hoc  INNER JOIN hocsinh ON hocsinh.ID_HS=tien_hoc.ID_HS WHERE tien_hoc.ID_MON='$monID' ORDER BY ID_STT DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function check_code_admin($baomat) {
	
		global $db;
		
		$now=date("Y")."-".date("m");
		$query="SELECT ID_O FROM options WHERE content='$baomat' AND type='code' AND note='$now'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}

	// Đã check
	function open_thang_tienhoc($date, $monID) {
	
		global $db;
		
		if(check_open_tienhoc($date,$monID)) {
			return true;
		} else {
			$query2="INSERT INTO unlock_thang(datetime,ID_MON)
									value('$date','$monID')";
			mysqli_query($db,$query2);						
			return false;
		}
	}

	// Đã check
	function close_thang_tienhoc($date, $monID) {
		
		global $db;
		
		$query="DELETE FROM unlock_thang WHERE datetime='$date' AND ID_MON='$monID'";
		
		mysqli_query($db,$query);	
	}

	// Đã check
	function check_open_tienhoc($date, $monID) {
		
		global $db;
		
		$query="SELECT * FROM unlock_thang WHERE datetime='$date' AND ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}

	// Đã check
	function du_kien_tien_hoc($date_dong, $date_now, $hsID, $mon_name) {

        $mon_name=unicode_convert($mon_name);
		// Thời điểm đóng có dạng năm-tháng
		$temp_dong=explode("-",$date_dong);
		$year_dong=$temp_dong[0];
		$month_dong=$temp_dong[1];
		
		// Thời điểm đang xét có dạng năm-tháng-ngày
		$temp=explode("-",$date_now);
		$year=$temp[0];
		$month=$temp[1];
		$day=$temp[2];
		
		// Tính tiền học
		if($year==$year_dong) {
			// Cùng 1 năm
			if($month<$month_dong) {
				// Trọn gói
                $price=get_muctien("cuoi_thang_truoc_$mon_name");
                $discount=get_discount_hs($hsID,get_mon_id($mon_name));
				return $price-($price*$discount/100);
			} else if($month==$month_dong) {
				//if($day<=7) {
					// Theo buổi + nộp muộn
					$price=get_muctien("dau_thang_sau_$mon_name");
                    $discount=get_discount_hs($hsID,get_mon_id($mon_name));
                    return $price-($price*$discount/100);
				/*} else {
					// Theo buổi
                    $price=du_kien_tien_hoc_buoi($date_dong, 1, $hsID, $cahoc_string, $ca_codinh_string);
                    $discount=get_discount_hs($hsID,get_mon_id($mon_name));
                    return $price-($price*$discount/100);
				}*/
			} else {
				// Chưa biết làm gì?
                $price=get_muctien("dau_thang_sau_$mon_name");
                $discount=get_discount_hs($hsID,get_mon_id($mon_name));
                return $price-($price*$discount/100);
			}
		} else if($year>$year_dong) {
			// Khác năm
            $price=get_muctien("dau_thang_sau_$mon_name");
            $discount=get_discount_hs($hsID,get_mon_id($mon_name));
            return $price-($price*$discount/100);
		} else {
			// Chưa biết làm gì?
            $price=get_muctien("cuoi_thang_truoc_$mon_name");
            $discount=get_discount_hs($hsID,get_mon_id($mon_name));
            return $price-($price*$discount/100);
		}
		
	}

	// Đã check
	function du_kien_tien_hoc_buoi($date_dong, $ngay_dk, $hsID, $lmID, $monID) {
	
		global $db;
		
		// Thời điểm đóng có dạng năm-tháng
		$temp_dong=explode("-",$date_dong);
		$year_dong=$temp_dong[0];
		$month_dong=$temp_dong[1];
		
		$days_of_month=array("31","28","31","30","31","30","31","31","30","31","30","31");
		if($year_dong%4==0) {
			$days_of_month[1]="29";
		}
		
		// Lấy lịch học của học sinh
		$cac_thu=array();
		$query="SELECT c.thu FROM ca_codinh AS d 
		INNER JOIN cahoc AS c ON c.ID_CA=d.ID_CA AND c.ID_GIO IN (SELECT ID_GIO FROM cagio WHERE ID_LM='$lmID' AND ID_MON='$monID') WHERE d.ID_HS='$hsID' ORDER BY c.thu ASC";
		$result=mysqli_query($db,$query);
		while($data=mysqli_fetch_assoc($result)) {
			// Khởi tạo biến đếm, giá trị 0 là số lần xuất hiện thứ này
			$cac_thu[$data["thu"]]=0;
		}
		
		// Đếm số buổi học
		$dem=0;
		for($i=$ngay_dk;$i<=$days_of_month[$month_dong-1];$i++) {
			$jd=gregoriantojd($month_dong,$i,$year_dong);
			$now_thu=jddayofweek($jd,0)+1;
			// Nếu tồn tại thứ này (đã lưu ở trên)
			if(isset($cac_thu[$now_thu])) {
				// Cộng 1 vào biến đếm tổng
				$dem++;
			}
		}
		
		return $dem*get_muctien("tien_hoc_buoi");
	}

	// Đã check
	function du_kien_tien_hoc_buoi2($date_dong, $ngay_dk, $hsID, $lmID, $monID) {
	
		global $db;
		
		// Thời điểm đóng có dạng năm-tháng
		$temp_dong=explode("-",$date_dong);
		$year_dong=$temp_dong[0];
		$month_dong=$temp_dong[1];
		
		$days_of_month=array("31","28","31","30","31","30","31","31","30","31","30","31");
		if($year_dong%4==0) {
			$days_of_month[1]="29";
		}
		
		// Lấy lịch học của học sinh
		$cac_thu=array();
		$query="SELECT c.thu FROM ca_codinh AS d 
		INNER JOIN cahoc AS c ON c.ID_CA=d.ID_CA AND c.ID_GIO IN (SELECT ID_GIO FROM cagio WHERE (ID_LM='$lmID' OR ID_LM='0') AND ID_MON='$monID') WHERE d.ID_HS='$hsID' ORDER BY c.thu ASC";
		$result=mysqli_query($db,$query);
		while($data=mysqli_fetch_assoc($result)) {
			// Khởi tạo biến đếm, giá trị 0 là số lần xuất hiện thứ này
			$cac_thu[$data["thu"]]=0;
		}
		
		// Đếm số buổi học
		$dem=0;$kq="";
		for($i=$ngay_dk;$i<=$days_of_month[$month_dong-1];$i++) {
			$jd=gregoriantojd($month_dong,$i,$year_dong);
			$now_thu=jddayofweek($jd,0)+1;
            if($now_thu==1 && $monID!=1) {
                continue;
            }
			// Nếu tồn tại thứ này (đã lưu ở trên)
			if(isset($cac_thu[$now_thu]) && ($month_dong != 1 || ($month_dong == 1 && $i < 26))) {
				// Cộng 1 vào biến đếm tổng
				$kq.=",$year_dong-$month_dong-$i";
				$dem++;
			}
		}
		
		return array($dem*get_muctien("tien_hoc_buoi"),substr($kq,1));
	}

	// Đã check
	function get_discount_hs($hsID, $monID) {
	
		global $db;
		
		$query="SELECT discount FROM giam_gia WHERE ID_HS='$hsID' AND ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$data=mysqli_fetch_assoc($result);
			return $data["discount"];
		} else {
			return 0;
		}
	}

	// Đã check
	function update_discount_hs($hsID,$monID,$discount) {
	
		global $db;
		
		$query="SELECT ID_STT FROM giam_gia WHERE ID_HS='$hsID' AND ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			if($discount!=0) {
				$query2="UPDATE giam_gia SET discount='$discount' WHERE ID_HS='$hsID' AND ID_MON='$monID'";
				mysqli_query($db,$query2);
			} else {
				$query2="DELETE FROM giam_gia WHERE ID_HS='$hsID' AND ID_MON='$monID'";
				mysqli_query($db,$query2);
			}
		} else {
			if($discount!=0) {
				$query2="INSERT INTO giam_gia(ID_HS,discount,ID_MON)
										value('$hsID','$discount','$monID')";
				mysqli_query($db,$query2);
			}
		}
	}
	
	function get_day_from_date($date) {
	
		$temp=explode("-",$date);
	
		return $temp[2];
	
	}

    // Đã check
    function count_hs_mon($monID) {

        global $db;

        $query="SELECT COUNT(m.ID_HS) AS dem FROM hocsinh_mon AS m 
        INNER JOIN lop_mon AS l ON l.ID_LM=m.ID_LM AND l.ID_MON='$monID'
        WHERE m.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM=l.ID_LM)";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["dem"];
    }

	// Đã check
	function count_hs_mon_lop($lmID) {
	
		global $db;

        $query="SELECT COUNT(ID_HS) AS dem FROM hocsinh_mon WHERE ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID') AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
		
		return $data["dem"];
	}

	// Đã check
	function count_hs_nghi_mon($lmID) {
	
		global $db;
		
		$query="SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
	}

	// Đã check
	function get_full_muc_tien($string) {
	
		global $db;
		
		$query="SELECT * FROM muc_tien WHERE string='$string'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
    function edit_lop_mon($lmID, $name, $date_in) {

        global $db;

        $query="UPDATE lop_mon SET name='$name',date_in='$date_in' WHERE ID_LM='$lmID'";

        mysqli_query($db,$query);
    }

    // Đã check
	function edit_mon($monID, $name, $thang, $is_phat, $is_tinh, $nhayde, $auto) {
	
		global $db;
		
		$query="UPDATE mon SET name='$name',thang='$thang',is_phat='$is_phat',is_tinh='$is_tinh',is_nhayde='$nhayde',is_auto='$auto' WHERE ID_MON='$monID'";
		
		mysqli_query($db,$query);
	}
	
	function count_hs_in_lop($lopID) {
	
		global $db;
		
		$query="SELECT ID_HS FROM hocsinh WHERE lop='$lopID'";
		
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
	}

	// Đã check
	function edit_lop($lopID, $name) {
	
		global $db;
		
		$query="UPDATE lop SET name='$name' WHERE ID_LOP='$lopID'";
		
		mysqli_query($db,$query);
		
	}
	
	function delete_lop($lopID) {
	
		global $db;
		
		$query="DELETE FROM lop WHERE ID_LOP='$lopID'";
		
		mysqli_query($db,$query);
		
	}

	// Đã check
	function add_lop($lop) {
	
		global $db;
		
		$query="INSERT INTO lop(name)
							value('$lop')";
							
		mysqli_query($db,$query);
	}
	
	function get_thanh_toan($date,$monID,$note) {
	
		global $db;

        if($note=="thanh_toan") {
            $query = "SELECT ID_STT,money,datetime FROM tien_thanh_toan WHERE ID_MON='$monID' AND note='$note'";
        } else {
            $query = "SELECT SUM(money) AS price FROM tien_thanh_toan WHERE datetime='$date' AND ID_MON='$monID' AND note='$note'";
        }
		
		$result=mysqli_query($db,$query);
	
		return $result;
	}
	
	function delete_thanhtoan($sttID) {
	
		global $db;
		
		$query="DELETE FROM tien_thanh_toan WHERE ID_STT='$sttID'";
		
		mysqli_query($db,$query);
	
	}
	
	function add_thanhtoan($tien, $datetime, $object, $monID, $note) {
	
		global $db;
		
		$query="INSERT INTO tien_thanh_toan(money,datetime,object,ID_MON,note)
										value('$tien','$datetime','$object','$monID','$note')";
		
		mysqli_query($db,$query);
		
	}
	
	function update_thanhtoan($sttID, $tien, $datetime) {
	
		global $db;
		
		$query="UPDATE tien_thanh_toan SET money='$tien',datetime='$datetime' WHERE ID_STT='$sttID'";
		
		mysqli_query($db,$query);
		
	}

	// Đã check
	function add_thong_bao_hs($hsID, $object, $content, $danhmuc, $lmID) {
	
		global $db;
		
		$query="INSERT INTO thongbao(ID_HS,object,content,danhmuc,ID_LM,datetime,loai,status)
							value('$hsID','$object','$content','$danhmuc','$lmID',now(),'small','new')";
								
		mysqli_query($db,$query);

        if($hsID != 0) {
//            push_chatfuel($hsID, $content);
//            push_fb_messenger($hsID, $content);
//            $tokens = get_token_hs($hsID);
        } else {
//            $tokens = get_token_all($lmID);
        }
//        if (count($tokens) > 0) {
//            $msg = array("message" => $content);
//            $msg_status = push_thong_bao($tokens, $msg);
//            if($hsID != 0) {
//                add_log($hsID, $msg_status, "push-noti");
//            }
//            del_unuse_token($msg_status,$tokens);
//        }
	}

	// Đã check
	function add_thong_bao_hs2($hsID, $object, $content, $danhmuc, $lmID) {
	
		global $db;
		
		$query="INSERT INTO thongbao(ID_HS,object,content,danhmuc,ID_LM,datetime,loai,status)
							value('$hsID','$object','$content','$danhmuc','$lmID',now(),'big','new')";
								
		mysqli_query($db,$query);
		if($hsID != 0) {
            push_chatfuel($hsID, $content);
        }

//        $tokens = get_token_hs($hsID);
//        if(count($tokens) > 0) {
//            $msg = array("message" => $content);
//            $msg_status = push_thong_bao($tokens, $msg);
//            add_log($hsID, $msg_status, "push-noti");
//            del_unuse_token($msg_status,$tokens);
//        }
	}

	// Đã check
    function push_fb_messenger($hsID, $mess) {

        global $db;

        $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=EAANwK7FC0cABADXp0qNtZC1t69RgnrNyM1ppQBv3ZB3fYiWwQ7PGWY8q0SICz3Y7oWB8xqEHR2KkZC3u8lGcTaZAZAD2kMH5viDzzG6KXGqOZAXiwUGrhnb97CcdQ8mhnfTen6ZBrr3ZCO4ZAcpmU287bioI3h9ECwvufffZAwZAiKzDQZDZD';
        $mh = curl_multi_init();

        $query = "SELECT FB_ID FROM fb_messenger WHERE ID_HS='$hsID'";
        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            $ch = curl_init();
            $jsonData = '{
            "recipient":{
                "id":"' . $data["FB_ID"] . '"
                },
                "message":{
                    "text":"' . $mess . '"
                }
            }';
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

            curl_multi_add_handle($mh,$ch);
        }

        do {
            while(($execrun = curl_multi_exec($mh, $running)) == CURLM_CALL_MULTI_PERFORM);
            if($execrun != CURLM_OK)
                break;
            // a request was just completed -- find out which one
            while($done = curl_multi_info_read($mh)) {
                $info = curl_getinfo($done['handle']);
                curl_multi_remove_handle($mh, $done['handle']);
                if ($info['http_code'] != 200)  {
                    $running = 0;
                }
            }
        } while ($running);

//        do {
//            curl_multi_exec($mh, $running);
//            curl_multi_select($mh);
//        } while ($running > 0);
//
//        for($i = 0; $i < count($ch); $i++) {
//            curl_multi_remove_handle($mh, $ch[$i]);
//        }
        curl_multi_close($mh);
    }

	// Đã check
	function push_thong_bao($token, $msg) {

	    $url = "https://fcm.googleapis.com/fcm/send";
        $fields = array(
            "registration_ids" => $token,
            "data" => $msg
        );

        $headers = array(
            "Authorization:key = AIzaSyBUcZ4e-2qjN66RFqUO2Sjguj7ddtyYl-c",
            "Content-Type: application/json"
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);

        return $result;
    }

    // Đã check
    function get_token_hs($hsID) {

        global $db;

        $query = "SELECT token FROM token WHERE ID_HS='$hsID' ORDER BY time DESC LIMIT 6";

        $token=array();
        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            $token[] = $data["token"];
        }

        return $token;
    }

    // Đã check
    function get_token_all($lmID) {

        global $db;

        $token=array();
        $query="SELECT h.ID_HS FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID')";
        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            $query2 = "SELECT token FROM token WHERE ID_HS='$data[ID_HS]' ORDER BY time DESC LIMIT 6";
            $result2=mysqli_query($db,$query2);
            while($data2=mysqli_fetch_assoc($result2)) {
                $token[] = $data2["token"];
            }
        }

        return $token;
    }

    // Đã check
    function del_unuse_token($result_json, $tokens) {

        $data=json_decode($result_json, true);
        $result=$data["results"];
        $n=count($result);
        $string="";
        for($i=0;$i<$n;$i++) {
            if(isset($result[$i]["error"])) {
                if($result[$i]["error"] == "NotRegistered") {
                    $string .= ",'" . $tokens[$i] . "'";
                }
            }
        }
        if($string != "") {
            $string = substr($string, 1);
            del_token($string);
        }
    }

    function del_token($string) {

        global $db;

        $query="DELETE FROM token WHERE token IN ($string)";

        mysqli_query($db,$query);
    }

    // Đã check
	function count_thong_bao_hs($hsID,$lmID) {
	
		global $db;
		
		$query="SELECT COUNT(ID_TB) AS dem FROM thongbao WHERE ID_HS='$hsID' AND ID_LM='$lmID' AND loai='small' AND status='new'";
		
		$result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
		
		return $data["dem"];
	}

	// Đã check
	function get_thong_bao_hs($hsID,$lmID) {
	
		global $db;
		
		$query="SELECT * FROM thongbao WHERE ID_HS='$hsID' AND ID_LM='$lmID' AND status IN ('new','freezee') ORDER BY datetime DESC LIMIT 3";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	
	}

	// Đã check
	function get_all_thong_bao_hs_sort($hsID,$lmID,$position,$display) {
	
		global $db;
		
		$query="SELECT * FROM thongbao WHERE ID_HS='$hsID' AND ID_LM='$lmID' ORDER BY datetime DESC LIMIT $position,$display";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	
	}

	// Đã check
	function get_all_thong_bao_hs($hsID,$lmID) {
	
		global $db;
		
		$query="SELECT ID_TB FROM thongbao WHERE ID_HS='$hsID' AND ID_LM='$lmID' ORDER BY datetime DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	
	}

	// Đã check
	function freezee_thong_bao($tbID) {
	
		global $db;
		
		$query="UPDATE thongbao SET status='freezee' WHERE ID_TB='$tbID'";
		
		mysqli_query($db,$query);
		
	}

	// Đã check
	function get_thong_bao_big($hsID,$lmID) {
	
		global $db;
		
		$query="SELECT * FROM thongbao WHERE ID_HS='$hsID' AND ID_LM='$lmID' AND loai='big' AND status='new' ORDER BY ID_TB ASC LIMIT 1";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function xem_thong_bao($tbID) {
	
		global $db;
		
		$query="UPDATE thongbao SET status='old' WHERE ID_TB='$tbID'";
		
		mysqli_query($db,$query);
	
	}

	// Đã check
	function chuaxem_thong_bao($tbID) {
	
		global $db;
		
		$query="UPDATE thongbao SET status='new' WHERE ID_TB='$tbID'";
		
		mysqli_query($db,$query);
	
	}

	// Đã check
	function get_icon_danhmuc($danhmuc) {
	
		if($danhmuc=="thach-dau") {
			return "fa-trophy";
		} else if($danhmuc=="ngoi-sao") {
			return "fa-star";
		} else if($danhmuc=="diem-thi") {
			return "fa-graduation-cap";
		} else if($danhmuc=="bang-xep-hang") {
			return "fa-table";
		} else if($danhmuc=="nhay-de") {
			return "fa-graduation-cap";
		} else if($danhmuc=="doi-ca") {
			return "fa-exchange";
		} else if($danhmuc=="phat-nong") {
			return "fa-bombs";
		} else if($danhmuc=="nghi-hoc") {
			return "fa-bell";
		} else if($danhmuc=="nap-rut") {
            return "fa-money";
        } else if($danhmuc=="len-level") {
            return "fa-level-up";
        } else if($danhmuc=="push-noti") {
            return "fa-volume-up";
		} else if($danhmuc=="all") {
            return "fa-globe";
		} else {
			return "";
		}
		
	}

	// Đã check
	function get_past_time($last) {
	
		$now=date("Y-m-d H:i:s");
		
		$last=date_create($last);
		$now=date_create($now);
		
		$diff=date_diff($last,$now);
		$h=$diff->format("%h");
		$i=$diff->format("%i");
		$s=$diff->format("%s");
		$d=$diff->format("%a");
		$m=$diff->format("%m");
		
		if($m==0) {
			if($d==0) {
				if($h==0) {
					if($i==0) {
						if($s==0) {
							return "Vừa xong";
						} else {
							return "$s giây trước";
						}
					} else {
						return "$i phút trước";
					}
				} else {
					return "$h giờ trước";
				}
			} else {
				return "$d ngày trước";
			}
		} else {
			return "$m tháng trước";
		}
	}

	// Đã check
	function get_past_time2($last) {
	
		$now=date("Y-m-d H:i:s");
		
		$last=date_create($last);
		$now=date_create($now);
		
		$diff=date_diff($last,$now);
		$h=$diff->format("%h");
		$i=$diff->format("%i");
		$s=$diff->format("%s");
		$d=$diff->format("%a");
		$m=$diff->format("%m");
		
		if($m==0) {
			if($d==0) {
				if($h==0) {
					if($i==0) {
						if($s==0) {
							return "Mới";
						} else {
							return $s."s";
						}
					} else {
						return $i."p";
					}
				} else {
					return $h."h";
				}
			} else {
				return $d."d";
			}
		} else {
			return $m."m";
		}
	}

	// Đã check
	function get_avata_thay($monID) {
	
		global $db;
		
		$query="SELECT ava FROM admin WHERE level='2' AND note='$monID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["ava"];
	}
	
	function format_datetime($datetime) {
	
		$date=date_create($datetime);
		
		return date_format($date, 'H:i d/m/Y');
	}

	// Đã check
	function get_thi_daihoc($lopID) {
	
		global $db;
		
		$query="SELECT content FROM options WHERE type='thi-daihoc' AND note='$lopID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["content"];
	}
	
	function edit_thi_daihoc($lopID,$time) {
	
		global $db;
		
		$query="SELECT * FROM options WHERE type='thi-daihoc' AND note='$lopID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$query2="UPDATE options SET content='$time' WHERE type='thi-daihoc' AND note='$lopID'";
			mysqli_query($db,$query2);
		} else {
			$query2="INSERT INTO options(content,type,note)
									value('$time','thi-daihoc','$lopID')";
			mysqli_query($db,$query2);
		}
	}
	
	function get_lido_loai($loai) {
	
		switch($loai) {
			case 0:
				return "lam-bai-tren-lop";
				break;
			case 1:
				return "lam-bai-o-nha";
				break;
			case 2:
				echo"<td><span>Nghỉ học</span>";
				break;
			case 3:
				echo"<td style='background:".$lido_mau[$data2["note"]-1].";'><span style='color:#FFF'>".$lido[$data2["note"]-1]."</span>";	
				break;
			case 4:
				echo"<td><span>Mất bài,<br />nghỉ có phép</span>";
				break;
			case 5:
				echo"<td style='background:green;'><span style='color:#FFF'>Không đi thi</span>";
				break;
			default:
				echo"<td><span></span>";
				break;
		}
		
	}
	
	function get_phat_diemkt($hsID,$diem,$de,$loai,$note,$lmID,$mon_name,$buoiID,$ngay,$check) {
		// Chỉ xét phạt với các TH làm bài trên lớp, làm bài ở nhà, bị hủy bài và không đi thi
		if(is_numeric($diem) && ($loai==3 || $loai==1 || $loai==0 || $loai==5)) {
			if($diem==0 && $loai==3) {
				// Code trừ điểm theo lý do hủy bài
                $lido=get_lido_short($note);
                $tien=get_muctien("".unicode_convert($lido));
				if($check) {tru_tien_hs($hsID,$tien,"Hủy bài kiểm tra $mon_name ngày ".format_dateup($ngay)." vì ".$lido,"kiemtra_$lmID",$buoiID);}
				return "-".$tien;
			} if($loai==5) {
			    $tien=get_muctien("khong-di-thi");
				if($check) {tru_tien_hs($hsID,$tien,"Không đi kiểm tra $mon_name ngày ".format_dateup($ngay),"kiemtra_$lmID",$buoiID);}
				return "-".$tien;
			} else {
				if($diem<5) {
					// Trừ 10k
                    $tien=get_muctien("diem_kt_be_5.25");
					if($check) {tru_tien_hs($hsID,$tien,"Điểm kiểm tra $mon_name ngày ".format_dateup($ngay)." được $diem ($de)","kiemtra_$lmID",$buoiID);}
					return "-".$tien;
				} else {
					if($diem<8) {
						// Không bị sao cả
						return "";
					} else if($loai==0) {
						if($de=="G") {
                            if($diem<9) {
                                // Cộng 10k
                                $tien=get_muctien("diem_kt_be_9");
                                if($check) {cong_tien_hs($hsID,$tien,"Điểm kiểm tra $mon_name ngày ".format_dateup($ngay)." được $diem ($de)","kiemtra_$lmID",$buoiID);}
                                return "+".$tien;
                            } else {
                                if($diem<10) {
                                    // Cộng 20k
                                    $tien=get_muctien("diem_kt_be_10");
                                    if($check) {cong_tien_hs($hsID,$tien,"Điểm kiểm tra $mon_name ngày ".format_dateup($ngay)." được $diem ($de)","kiemtra_$lmID",$buoiID);}
                                    return "+".$tien;
                                } else {
                                    // Cộng 40k
                                    $tien=get_muctien("diem_kt_bang_10");
                                    if($check) {cong_tien_hs($hsID,$tien,"Điểm kiểm tra $mon_name ngày ".format_dateup($ngay)." được $diem ($de)","kiemtra_$lmID",$buoiID);}
                                    return "+".$tien;
                                }
                            }
						} else {
						    return "";
                        }
					} else {
					    return "";
                    }
				}
			}
		} else {
			return "";
		}
	}

	function get_last_loai($buoiID, $hsID, $lmID) {

	    global $db;

	    $query="SELECT loai FROM diemkt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_LM='$lmID'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["loai"];
    }
	
	function delete_phuhuynh($hsID,$gender) {
	
		global $db;	
		
		$query="DELETE FROM phuhuynh WHERE gender='$gender' AND ID_HS='$hsID'";
		
		mysqli_query($db,$query);
	}
	
	function add_phuhuynh2($hsID,$name,$job,$face,$mail,$gender) {
	
		global $db;

        $query="INSERT INTO phuhuynh(name,job,face,gender,mail,ID_HS) SELECT * FROM 
        (SELECT '$name' AS name,'$job' AS job,'$face' AS face,'$gender' AS gender,'$mail' AS mail,'$hsID' AS hsID) AS tmp WHERE NOT EXISTS 
        (SELECT ID_PH FROM phuhuynh WHERE gender='$gender' AND ID_HS='$hsID') LIMIT 1";

        mysqli_query($db,$query);
	}
	
	function edit_phuhuynh2($hsID,$name,$job,$face,$mail,$gender) {
	
		global $db;
		
		$query="UPDATE phuhuynh SET name='$name',job='$job',face='$face',mail='$mail' WHERE gender='$gender' AND ID_HS='$hsID'";

		mysqli_query($db,$query);
        if(mysqli_affected_rows($db) == 0) {
            add_phuhuynh2($hsID,$name,$job,$face,$mail,$gender);
        }
	}
	
	function add_phuhuynh($hsID,$name,$phone,$job,$face,$mail,$gender) {
	
		global $db;
		
		$query="INSERT INTO phuhuynh(name,job,face,gender,mail,ID_HS)
							value('$name','$job','$face','$gender','$mail','$hsID')";
		
		mysqli_query($db,$query);
		if($gender==1) {
			$query2="UPDATE hocsinh SET sdt_bo='$phone' WHERE ID_HS='$hsID'";
		} else {
			$query2="UPDATE hocsinh SET sdt_me='$phone' WHERE ID_HS='$hsID'";
		}
		mysqli_query($db,$query2);
	}
	
	function edit_phuhuynh($hsID,$name,$phone,$job,$face,$mail,$gender) {
	
		global $db;
		
		$query="UPDATE phuhuynh SET name='$name',job='$job',face='$face',mail='$mail' WHERE gender='$gender' AND ID_HS='$hsID'";

		mysqli_query($db,$query);
		if($gender==1) {
			$query2="UPDATE hocsinh SET sdt_bo='$phone' WHERE ID_HS='$hsID'";
		} else {
			$query2="UPDATE hocsinh SET sdt_me='$phone' WHERE ID_HS='$hsID'";
		}
		mysqli_query($db,$query2);
	}
	
	function check_phuhuynh($hsID,$gender) {
	
		global $db;
		
		$query="SELECT ID_PH FROM phuhuynh WHERE gender='$gender' AND ID_HS='$hsID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}
	
	function get_phuhuynh_hs($hsID,$gender) {
	
		global $db;

        if($gender == "X") {
            $query = "SELECT * FROM phuhuynh WHERE ID_HS='$hsID' ORDER BY gender ASC";
        } else {
            $query = "SELECT * FROM phuhuynh WHERE gender='$gender' AND ID_HS='$hsID'";
        }
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_cum($cum, $lopID, $monID) {
	
		global $db;
		
		$query="SELECT name FROM cum WHERE ma_cum='$cum' AND ID_LOP='$lopID' AND ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["name"];
	}

	// Đã check
	function get_one_cum($cum,$lmID,$monID) {

	    global $db;

        $query="SELECT * FROM cum WHERE ID_CUM='$cum' AND ID_LM='$lmID' AND ID_MON='$monID'";

        $result=mysqli_query($db,$query);

        return $result;

    }

    // Đã check
	function get_all_cum($lmID,$monID) {
	
		global $db;
		
		$query="SELECT * FROM cum WHERE ma_cum!='4' AND link='0' AND ID_LM='$lmID' AND ID_MON='$monID' ORDER BY ma_cum ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_all_cum_link($lmID, $monID) {
	
		global $db;
		
		$query="SELECT * FROM cum WHERE ma_cum!='4' AND link!='0' AND ID_LM='$lmID' AND ID_MON='$monID' ORDER BY ma_cum ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_all_cum2($lmID) {
	
		global $db;
		
		$query="SELECT * FROM cum WHERE ma_cum!='4' AND ID_LM='$lmID' ORDER BY ma_cum ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_cum_kt($monID) {
	
		global $db;
		
		$query="SELECT * FROM cum WHERE ma_cum='4' AND link='0' AND ID_LM='0' AND ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function check_has_ca($thu, $gioID) {
	
		global $db;
		
		$query="SELECT ID_CA,cum FROM cahoc WHERE thu='$thu' AND ID_GIO='$gioID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function edit_name_cum($name, $cum, $link, $lmID, $monID) {
	
		global $db;
		
		$query="UPDATE cum SET name='$name',link='$link' WHERE ID_CUM='$cum' AND ID_LM='$lmID' AND ID_MON='$monID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function add_cum($lmID, $monID) {
	
		global $db;
		
		$query="SELECT ma_cum FROM cum WHERE ID_LM='$lmID' AND ID_MON='$monID' ORDER BY ma_cum DESC LIMIT 1";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		$cum=$data["ma_cum"]+1;
		if($cum==4) {
			$cum=5;
		}
		
		$query2="INSERT INTO cum(name,ma_cum,ID_LM,ID_MON)
							value('','$cum','$lmID','$monID')";
							
		mysqli_query($db,$query2);
	}

	// Đã check
	function delete_cum($cum, $lmID, $monID) {
	
		global $db;
		
		$query="DELETE FROM cum WHERE ID_CUM='$cum' AND ID_LM='$lmID' AND ID_MON='$monID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function delete_catime($lmID) {
	
		global $db;
		
		$query="DELETE FROM doica_time WHERE ID_LM='$lmID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function add_catime($lmID,$start,$end) {
	
		global $db;
		
		$query="SELECT ID_STT FROM doica_time WHERE ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==0) {
			$query2="INSERT INTO doica_time(start,end,ID_LM)
										value('$start','$end','$lmID')";
		} else {
			$query2="UPDATE doica_time SET start='$start',end='$end' WHERE ID_LM='$lmID'";
		}
		mysqli_query($db,$query2);
	}

	// Đã check
	function check_on_catime($lmID) {
	
		global $db;
		
		$now=date("Y-m-d");
		
		$query="SELECT start,end FROM doica_time WHERE start<='$now' AND end>='$now' AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==0) {
			return false;
		} else {
			return true;
		}
	}

	// Đã check
	function check_unlock_ca_hs($hsID, $caID) {
		
		global $db;
	
		$query="SELECT * FROM ca_quyen WHERE ID_HS='$hsID' AND ID_CA='$caID'";
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==1) {
			return true;
		} else {
			return false;
		}
	}

	// Đã check
	function turn_on_ca($hsID, $caID) {
		
		global $db;
		
		$query="INSERT INTO ca_quyen(ID_HS,ID_CA) SELECT * FROM (SELECT '$hsID' AS hs,'$caID' AS ca) AS tmp WHERE NOT EXISTS (SELECT ID_HS,ID_CA FROM ca_quyen WHERE ID_HS='$hsID' AND ID_CA='$caID') LIMIT 1";

		mysqli_query($db,$query);
		
	}

	// Đã check
	function turn_off_ca($hsID, $caID) {
		
		global $db;
	
		$query="DELETE FROM ca_quyen WHERE ID_HS='$hsID' AND ID_CA='$caID'";
		mysqli_query($db,$query);
	}
	
	function valid_pass($password) {
		if (preg_match("/^[a-zA-Z][0-9a-zA-Z_!$@#^&]{5,255}$/", $password))
			return true;
		else
			return false;
	}
	
	function valid_email($email){
		$regex = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/"; 
		if (!preg_match( $regex, $email ) ) {
			return false;
		} 
		return true;
	}
	
	function valid_phone($phone){
		if(strlen($phone) < 9 || strlen($phone) > 11)
		{
			return false;
		}
		$regex = "/^[0-9]/"; 
		if (!preg_match( $regex, $phone ) ) {
			return false;
		} 
		return true;
	}
	
	function valid_text($text){
		$text1=strtolower($text);
		$regex = array();
		$regex[1] = "/union/";
		$regex[2] = "/database()/";
		$regex[3] = "/version/";
		$regex[4] = "/script/";
		$regex[5] = "/select/";
		$regex[6] = "/information_schema/";
		$regex[7] = "/insert/";
		$regex[8] = "/drop/";
		$regex[9] = "/load_file/";
		for($i=1;$i<=9;$i++)
		{
			if (preg_match( $regex[$i], $text1 ) ) {
				return false;
			} 
		}
		if($text1=="" || $text1==" " || $text1=="_" || $text1=="%" || $text1=="/") {
			return false;
		}
		return true;
	}
	
	function rand_pass($length) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

    function rand_maso($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
	
	function update_code_ad($code) {
	
		global $db;
		
		$query="UPDATE options SET content='$code',note='".date("Y")."-".date("m")."' WHERE type='code'";
		
		mysqli_query($db,$query);
	}
	
	function insert_code_ad($code) {
	
		global $db;
		
		$query="INSERT INTO options(content,type,note)
							value('$code','code','".date("Y-m")."')";
		
		mysqli_query($db,$query);
	}
	
	function insert_time_code($name) {
	
		global $db;
		
		$now=date("Y-m-d");
		
		$query="INSERT INTO options(content,type,note)
								value('$now','send_code','$name')";
								
		mysqli_query($db,$query);
	}
	
	function count_time_code($name) {
	
		global $db;
		
		$now=date("Y-m-d");
		
		$query="SELECT ID_O FROM options WHERE content='$now' AND type='send_code' AND note='$name'";
		
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
	}
	
	function send_email($sender, $mk, $reciver, $subject, $msg, $isHtml) {
	
		$mail = new PHPMailer();
						 
		$mail->IsSMTP();
		$mail->CharSet="utf-8"; 
		$mail->Host = "smtp.yandex.com";
		 
		$mail->Port = 465; 
		$mail->SMTPAuth = true;    
		$mail->SMTPSecure = 'ssl';     
		 
		$mail->Username = "$sender";  
		$mail->Password = "$mk"; 
		 
		$mail->FromName = 'BGO.EDU.VN';
		$mail->From = $sender;
		
		if(strpos($reciver,";")===false) { 
			$mail->AddAddress($reciver, "Me");
		} else {
			$temp=explode(";",$reciver);
			$mail->AddAddress($temp[0], "Me");
			for($i=1;$i<count($temp);$i++) {
				$mail->AddCC($temp[$i], "Me");
			}
		}
		 
		$mail->Subject = "$subject";
		$mail->IsHTML($isHtml);
		if($isHtml) {
			$mail->msgHTML($msg);
		} else {
		 	$mail->Body = $msg;
		}
		$mail->AltBody = $subject;
		 
		if(!$mail->Send())
		{
		   	$error = $mail->ErrorInfo;
		} else {
			$error = "ok";
		}
		
		return $error;
	}

	// Đã check
	function get_cmt_diem_loai($loai) {
	
		if($loai==0) {
			return "<br /><span style=\'font-size:10px\'>&#9899;</span> Làm bài trên lớp";
		} else if($loai==1) {
			return "<br /><span style=\'font-size:10px\'>&#9899;</span> Làm bài ở nhà";
		} else if($loai==2) {
			return "<br /><span style=\'font-size:10px\'>&#9899;</span> Nghỉ hẳn";
		} else if($loai==3) {
			return "huy";
		} else if($loai==4) {
			return "<br /><span style=\'font-size:10px\'>&#9899;</span> Mất bài, nghỉ phép";
		} else {
			return "<br /><span style=\'font-size:10px\'>&#9899;</span> Không đi thi";
		}
	}

	// Đã check
	function get_cmt_diem_loai2($loai) {
	
		if($loai==0) {
			return "Làm bài trên lớp";
		} else if($loai==1) {
			return "Làm bài ở nhà";
		} else if($loai==2) {
			return "Nghỉ hẳn";
		} else if($loai==3) {
			return "huy";
		} else if($loai==4) {
			return "Mất bài<br />nghỉ phép";
		} else {
			return "Không đi thi";
		}
	}
	
	function count_tailieu_sl($cdID) {
	
		global $db;
		
		$query="SELECT ID_TL FROM tailieu WHERE ID_DM='$cdID'";
		
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
	}

    function count_tailieu_sl2($dmID) {

        global $db;

        $query="SELECT ID_TL FROM tailieu WHERE ID_DM2='$dmID'";

        $result=mysqli_query($db,$query);

        return mysqli_num_rows($result);
    }

    // Đã check
	function get_tailieu($lmID,$monID,$position,$display) {
	
		global $db;
		
		$query="SELECT t.ID_TL,t.title AS name,t.titlestring,t.intro,t.price,t.type,t.pic,t.dateup,d.title FROM tailieu AS t INNER JOIN chuyende AS d ON d.ID_CD=t.ID_DM AND d.ID_LM='$lmID' 
		UNION 
		SELECT t2.ID_TL,t2.title AS name,t2.titlestring,t2.intro,t2.price,t2.type,t2.pic,t2.dateup,d2.title FROM tailieu AS t2 INNER JOIN danhmuc AS d2 ON d2.ID_DM=t2.ID_DM2 AND d2.ID_LM='$lmID' AND d2.ID_MON='$monID' ORDER BY ID_TL DESC LIMIT $position,$display";

		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_all_tailieu($lmID) {
	
		global $db;
		
		$query="SELECT t.ID_TL FROM tailieu AS t INNER JOIN chuyende AS d ON d.ID_CD=t.ID_DM AND d.ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function delete_tailieu($tlID) {
	
		global $db;
		
		$query="DELETE FROM tailieu WHERE ID_TL='$tlID'";
		
		mysqli_query($db,$query);
	}
	
	function up_tailieu_short($title, $intro, $content, $loai, $pic, $price, $danhmuc, $where) {
	
		global $db;

        if($where=="chuyende") {
            $query = "INSERT INTO tailieu(title,titlestring,intro,short_con,price,type,pic,dateup,ID_DM)
								value('$title','" . unicode_convert($title) . "','$intro','$content','$price','$loai','$pic',now(),'$danhmuc')";
        } else {
            $query = "INSERT INTO tailieu(title,titlestring,intro,short_con,price,type,pic,dateup,ID_DM2)
								value('$title','" . unicode_convert($title) . "','$intro','$content','$price','$loai','$pic',now(),'$danhmuc')";
        }
								
		mysqli_query($db,$query);
	}
	
	function up_tailieu_full($title, $intro, $content, $loai, $pic, $price, $danhmuc, $where) {
	
		global $db;

        if($where=="chuyende") {
            $query = "INSERT INTO tailieu(title,titlestring,intro,full_con,price,type,pic,dateup,ID_DM)
								value('$title','" . unicode_convert($title) . "','$intro','$content','$price','$loai','$pic',now(),'$danhmuc')";
        } else {
            $query = "INSERT INTO tailieu(title,titlestring,intro,full_con,price,type,pic,dateup,ID_DM2)
								value('$title','" . unicode_convert($title) . "','$intro','$content','$price','$loai','$pic',now(),'$danhmuc')";
        }
								
		mysqli_query($db,$query);
	}
	
	function edit_tailieu_short($title, $intro, $content, $loai, $pic, $price, $danhmuc, $where, $tlID) {
	
		global $db;

        if($where=="chuyende") {
            if ($content != "none") {
                $query = "UPDATE tailieu SET title='$title',titlestring='" . unicode_convert($title) . "',intro='$intro',short_con='$content',price='$price',type='$loai',pic='$pic',ID_DM='$danhmuc' WHERE ID_TL='$tlID'";
            } else {
                $query = "UPDATE tailieu SET title='$title',titlestring='" . unicode_convert($title) . "',intro='$intro',price='$price',type='$loai',pic='$pic',ID_DM='$danhmuc' WHERE ID_TL='$tlID'";
            }
        } else {
            if ($content != "none") {
                $query = "UPDATE tailieu SET title='$title',titlestring='" . unicode_convert($title) . "',intro='$intro',short_con='$content',price='$price',type='$loai',pic='$pic',ID_DM2='$danhmuc' WHERE ID_TL='$tlID'";
            } else {
                $query = "UPDATE tailieu SET title='$title',titlestring='" . unicode_convert($title) . "',intro='$intro',price='$price',type='$loai',pic='$pic',ID_DM2='$danhmuc' WHERE ID_TL='$tlID'";
            }
        }
								
		mysqli_query($db,$query);
	}
	
	function edit_tailieu_full($title, $intro, $content, $loai, $pic, $price, $danhmuc, $where, $tlID) {
	
		global $db;

        if($where=="chuyende") {
            $query = "UPDATE tailieu SET title='$title',titlestring='" . unicode_convert($title) . "',intro='$intro',full_con='$content',price='$price',type='$loai',pic='$pic',ID_DM='$danhmuc' WHERE ID_TL='$tlID'";
        } else {
            $query = "UPDATE tailieu SET title='$title',titlestring='" . unicode_convert($title) . "',intro='$intro',full_con='$content',price='$price',type='$loai',pic='$pic',ID_DM2='$danhmuc' WHERE ID_TL='$tlID'";
        }
		mysqli_query($db,$query);
	}

	// Đã check
	function get_tailieu_dm($cdID, $dmID) {
	
		global $db;
		
		$query="SELECT ID_TL,title AS name,titlestring,intro,short_con,price,type,pic,dateup,ID_DM FROM tailieu WHERE ID_DM='$cdID' AND ID_DM2='$dmID' ORDER BY dateup DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_one_tailieu($tlID) {
	
		global $db;
		
		$query="SELECT * FROM tailieu WHERE ID_TL='$tlID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function check_correct_ma($code, $now) {
	
		global $db;
		
		$query="SELECT ID_O FROM options WHERE content='$code' AND type='code' AND note='$now' ORDER BY ID_O DESC LIMIT 1";
		
		$result=mysqli_query($db,$query);
		
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}

	// Đã check
	function check_nhayde_G($hsID, $lmID) {
	
		global $db;
		
		$query="SELECT ID_TB,object,datetime FROM thongbao WHERE ID_HS='$hsID' AND danhmuc='nhay-de' AND ID_LM='$lmID' AND loai='big' AND status='new'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$data=mysqli_fetch_assoc($result);
			if($data["object"]==1) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function turn_off_freezee($hsID, $lmID) {
	
		global $db;
	
		$today=date("Y-m-d H:i:s");
		$today_date=date_create($today);
		
		$query="SELECT ID_TB,datetime FROM thongbao WHERE ID_HS='$hsID' AND danhmuc='nhay-de' AND ID_LM='$lmID' AND loai='big' AND status='freezee'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$data=mysqli_fetch_assoc($result);
			$date=date_create($data["datetime"]);
			$diff=date_diff($date,$today_date);
			$kc=$diff->format("%a");
			if($kc>3) {
				xem_thong_bao($data["ID_TB"]);
			}
		}
	}

	// Đã check
	function fix_khoa($lmID) {
	
		global $db;
		
		$query="INSERT INTO options(content,type,note)
								value('0','khoa','$lmID')";
								
		mysqli_query($db,$query);
		
	}
	
	function get_all_admin() {
	
		global $db;
		
		$query="SELECT ID,fullname,ava FROM admin ORDER BY level ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_lastest_msg($hsID,$ID) {
	
		global $db;
		
		$query="SELECT * FROM sms WHERE ID_HS='$hsID' AND ID='$ID' ORDER BY datetime DESC LIMIT 1";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function check_exited_admin($ID) {
	
		global $db;
		
		$query="SELECT ID FROM admin WHERE ID='$ID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}
	
	function get_all_msg($hsID,$ID) {
	
		global $db;
		
		$query="SELECT s.*,h.avata,a.ava FROM sms AS s INNER JOIN hocsinh AS h ON h.ID_HS='$hsID' INNER JOIN admin AS a ON a.ID='$ID' WHERE s.ID_HS='$hsID' AND s.ID='$ID' ORDER BY datetime ASC LIMIT 100";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_first_msg($hsID) {
	
		global $db;
		
		$query="SELECT ID FROM sms WHERE ID_HS='$hsID' ORDER BY datetime DESC LIMIT 1";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$data=mysqli_fetch_assoc($result);
			
			return $data["ID"];
		} else {
			return 0;
		}
	}
	
	function get_admin_name($id) {
	
		global $db;
		
		$query="SELECT fullname FROM admin WHERE ID='$id'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["fullname"];
	}
	
	function read_msg($hsID,$ID) {
	
		global $db;
		
		$query="UPDATE sms SET status='old' WHERE ID_HS='$hsID' AND ID='$ID'";
		
		mysqli_query($db,$query);
	}
	
	function add_msg_hs($hsID, $ID, $text) {
	
		global $db;
		
		$query="INSERT INTO sms(ID_HS,ID,who,content,type,datetime,status)
						value('$hsID','$ID','$hsID','$text','text',now(),'new')";
						
		mysqli_query($db,$query);
	}
	
	function get_group_facebook($lmID) {
	
		global $db;
		
		$query="SELECT ID_O,content FROM options WHERE type='facelop' AND note='$lmID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function edit_facebook($oID,$face,$lmID) {
	
		global $db;
		
		if($oID!=0) {
			$query="UPDATE options SET content='$face' WHERE type='facelop' AND note='$lmID'";
		} else {
			$query="INSERT INTO options(content,type,note)
								value('$face','facelop','$lmID')";
		}
		
		mysqli_query($db,$query);
	}

	// Đã check
	function valid_id($id) {

	    $id = $id+1-1;
	
		if($id!=0 && is_numeric($id)) {
			return true;
		} else {
			return false;
		}
	}

	// Đã check
	function get_facebook($lmID) {
	
		global $db;
		
		$query="SELECT content FROM options WHERE type='facelop' AND note='$lmID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["content"];
	}

	// Đã check
	function get_all_tailieu_cmt($tlID) {
	
		global $db;
		
		$query="SELECT * FROM comment WHERE ID_TL='$tlID' ORDER BY datetime DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_tailieu_cmt($tlID,$position,$display) {
	
		global $db;
		
		$query="SELECT * FROM comment WHERE ID_TL='$tlID' ORDER BY datetime DESC LIMIT $position,$display";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function count_tailieu_like($tlID) {
	
		global $db;
		
		$query="SELECT ID_L FROM likes WHERE ID_TL='$tlID'";
		
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
	}

	// Đã check
	function count_tailieu_cmt($tlID) {
	
		global $db;
		
		$query="SELECT ID_C FROM comment WHERE ID_TL='$tlID'";
		
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
	}

	// Đã check
	function add_tailieu_cmt($hsID, $tlID, $text) {
	
		global $db;
		
		$query="INSERT INTO comment(ID_HS,ID_TL,content,datetime)
							value('$hsID','$tlID','$text',now())";
							
		mysqli_query($db,$query);
	}

	// Đã check
	function get_lastest_cmt($tlID) {
	
		global $db;
		
		$query="SELECT * FROM comment WHERE ID_TL='$tlID' ORDER BY datetime DESC LIMIT 1";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function add_tailieu_like($hsID,$tlID) {
	
		global $db;
		
		$query="INSERT INTO likes(ID_HS,ID_TL)
							value('$hsID','$tlID')";
							
		mysqli_query($db,$query);
	}

	// Đã check
	function check_tailieu_like($hsID,$tlID) {
	
		global $db;
		
		$query="SELECT ID_L FROM likes WHERE ID_HS='$hsID' AND ID_TL='$tlID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}
	
	function delete_comment($cID) {
	
		global $db;
		
		$query="DELETE FROM comment WHERE ID_C='$cID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function check_tailieu_mon($tlID,$lmID) {
	
		global $db;
		
		$query="SELECT t.ID_TL FROM tailieu AS t INNER JOIN chuyende AS d ON d.ID_CD=t.ID_DM AND d.ID_LM='$lmID' WHERE t.ID_TL='$tlID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}
	
	function check_tailieu_mon2($tlID,$monID) {
	
		global $db;
		
		$query="SELECT t.ID_TL FROM tailieu AS t INNER JOIN chuyende AS d ON d.ID_CD=t.ID_DM AND d.ID_MON='$monID' WHERE t.ID_TL='$tlID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
            $query2="SELECT t.ID_TL FROM tailieu AS t INNER JOIN danhmuc AS d ON d.ID_DM=t.ID_DM2 AND d.ID_MON='$monID' WHERE t.ID_TL='$tlID'";
            $result2=mysqli_query($db,$query2);
            if(mysqli_num_rows($result2)!=0) {
                return true;
            } else {
                return false;
            }
		}
	}

	// Đã check
    function check_tailieu_mon3($tlID,$lmID) {

        global $db;

        $query="SELECT t.ID_TL FROM tailieu AS t INNER JOIN danhmuc AS d ON d.ID_DM=t.ID_DM2 AND d.ID_LM='$lmID' WHERE t.ID_TL='$tlID'";

        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)!=0) {
            return true;
        } else {
            return false;
        }
    }
	
	function update_avata_hs($hsID,$ava) {
	
		global $db;
		
		$query="UPDATE hocsinh SET avata='$ava' WHERE ID_HS='$hsID'";
		
		mysqli_query($db,$query);
	}
	
	function valid_image($image) {
	
		if(is_numeric(strpos($image,".png")) || is_numeric(strpos($image,".jpg")) || is_numeric(strpos($image,".gif")) || is_numeric(strpos($image,".jpeg"))) {
			return true; 
		} else {
			return false;
		}
	}

	// Đã check
	function change_mk_hs($cmt,$mk) {
		
		global $db;
		
		$query="UPDATE hocsinh SET password='$mk' WHERE cmt='$cmt'";
		
		mysqli_query($db,$query);
	}
	
	function update_code_hs($hsID,$code) {
	
		global $db;
		
		$query="UPDATE hocsinh SET code='$code' WHERE ID_HS='$hsID'";
		
		mysqli_query($db,$query);
	}
	
	function login_check() {
	
		global $db;
		
		if(isset($_SESSION["ID_HS"]) && isset($_SESSION["cmt"]) && isset($_SESSION["code"]) && isset($_SESSION["login"])) {
			$hsID=$_SESSION["ID_HS"];
			$cmt=$_SESSION["cmt"];
			$code=$_SESSION["code"];
			$login=$_SESSION["login"];
			
			//$user_browser = $_SERVER['HTTP_USER_AGENT'];
			
			$query="SELECT password FROM hocsinh WHERE ID_HS='$hsID' AND cmt='$cmt'";
			$result=mysqli_query($db,$query);
			if(mysqli_num_rows($result)==1) {
				$data=mysqli_fetch_assoc($result);
				$password=$data["password"];
				
				$ex_code=hash("sha512",$password.$code);
				if(hash_equals($login,$ex_code)) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function check_times_mail($hsID) {
	
		global $db;
		
		$query="SELECT content FROM log WHERE ID_HS='$hsID' AND type='bao-loi'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$data=mysqli_fetch_assoc($result);
			if($data["content"]<=4) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
	
	function update_times_mail($hsID) {
	
		global $db;
		
		$query="SELECT content FROM log WHERE ID_HS='$hsID' AND type='bao-loi'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$data=mysqli_fetch_assoc($result);
			$query2="UPDATE log SET content='".($data["content"]+1)."',datetime=now() WHERE ID_HS='$hsID' AND type='bao-loi'";
		} else {
			$query2="INSERT INTO log(ID_HS,content,type,datetime)
								value('$hsID','1','bao-loi',now())";
		}
		mysqli_query($db,$query2);
		
	}
	
	function clean($string) {
	   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	
	   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
	
	function encode_data($data, $code) {
		
		$cipher=MCRYPT_RIJNDAEL_128;
		$mode=MCRYPT_MODE_CBC;
		$key=$code;
		
		$ivs=mcrypt_get_iv_size($cipher, $mode);
		$iv=mcrypt_create_iv($ivs, MCRYPT_RAND);
		
		$data=mcrypt_encrypt($cipher,$key,$data,$mode,$iv);
		$data=$iv.$data;
		$data=Base32::encode($data);
		
		return $data;
	}
	
	function decode_data($data, $code) {
		
		$data=Base32::decode($data);
		
		$cipher=MCRYPT_RIJNDAEL_128;
		$mode=MCRYPT_MODE_CBC;
		$key=$code;
		
		$ivs=mcrypt_get_iv_size($cipher, $mode);
		$iv_dec=substr($data, 0, $ivs);
		
		$data=substr($data,$ivs);
		$data=mcrypt_decrypt($cipher,$key,$data,$mode,$iv_dec);
		
		return $data;
	}

	// Đã check
	function check_diemtb_thang($time,$lmID) {
		
		global $db;
		
		$query="SELECT ID_STT FROM diemtb_thang WHERE ID_LM='$lmID' AND datetime='$time' ORDER BY ID_STT DESC LIMIT 20";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
		
	}

	// Đã check
	function get_same_cum($lmID,$monID) {
	
		global $db;
		
		$query="SELECT ID_CUM,name FROM cum WHERE ma_cum!='4' AND ID_LM!='$lmID' AND ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_ma_cum($cumID) {
	
		global $db;
		
		$query="SELECT ma_cum FROM cum WHERE ID_CUM='$cumID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["ma_cum"];
	}

	// Đã check
	function edit_siso_ca($caID,$siso) {
	
		global $db;
		
		$query="UPDATE cahoc SET siso='$siso' WHERE ID_CA='$caID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function edit_max_ca($caID,$max) {
	
		global $db;
		
		$query="UPDATE cahoc SET max='$max' WHERE ID_CA='$caID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function check_isset_diem($buoiID,$lmID) {
	
		global $db;
		
		$query="SELECT ID_DIEM FROM diemkt WHERE ID_BUOI='$buoiID' AND ID_LM='$lmID' ORDER BY ID_DIEM DESC LIMIT 20";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}

	// Đã check
	function check_isset_diemtb($buoiID,$lmID) {
	
		global $db;
		
		$query="SELECT ID_STT FROM diemkt_tb WHERE ID_BUOI='$buoiID' AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}
	
	function format_month_db($date) {
	
		$date=$date+1-1;
		if($date<10) {
			return "0".$date;
		} else {
			return $date;
		}
	}

	// Đã check
	function check_hs_nghi($hsID, $lmID) {
	
		global $db;
		
		$query="SELECT ID_N FROM hocsinh_nghi WHERE ID_HS='$hsID' AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}

	// Đã check
	function get_date_nghi($hsID, $lmID) {
	
		global $db;
		
		$query="SELECT date FROM hocsinh_nghi WHERE ID_HS='$hsID' AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["date"];
		
	}

	// Đã check
	function check_face_hs($hsID) {
	
		global $db;
		
		$query="SELECT facebook FROM hocsinh WHERE ID_HS='$hsID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$data=mysqli_fetch_assoc($result);
			if($data["facebook"]=="X" || $data["facebook"]=="") {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	// Đã check
	function add_dangky_face($hsID,$face,$lmID) {
	
		global $db;
		
		$query="INSERT INTO options(content,type,note)
							value('$face','dangky-face-$lmID','$hsID')";
							
		mysqli_query($db,$query);
		
	}

	// Đã check
	function get_all_dangky_face($lmID) {
	
		global $db;

        $query = "SELECT o.*,h.cmt,h.fullname,h.lop FROM options AS o INNER JOIN hocsinh AS h ON h.ID_HS=o.note WHERE o.type='dangky-face-$lmID' ORDER BY o.ID_O ASC";

		$result=mysqli_query($db,$query);
		
		return $result;
	}

	function delete_options2($id, $type, $note, $note2) {

	    global $db;

        $query="DELETE FROM options WHERE content='$id' AND type='$type' AND note='$note' AND note2='$note2'";

        mysqli_query($db,$query);
    }
	
	function delete_options($oID) {
	
		global $db;
		
		$query="DELETE FROM options WHERE ID_O='$oID'";
		
		mysqli_query($db,$query);
	}
	
	function get_options($oID) {
	
		global $db;
		
		$query="SELECT * FROM options WHERE ID_O='$oID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

    function get_options_all($type,$limit) {

        global $db;

        $query="SELECT * FROM options WHERE type='$type' ORDER BY ID_O DESC LIMIT $limit";

        $result=mysqli_query($db,$query);

        return $result;
    }
	
	function update_face_hs($face,$hsID) {
	
		global $db;
		
		$query="UPDATE hocsinh SET facebook='$face' WHERE ID_HS='$hsID'";
		
		mysqli_query($db,$query);
		
	}

    function check_game() {

        global $db;

        $query="SELECT * FROM options WHERE type='game'";

        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)==0) {
            $query2="INSERT INTO options(content,type,note)
                                        value(now(),'game','0')";
            mysqli_query($db,$query2);
        } else {
            $data=mysqli_fetch_assoc($result);
            if($data["note"]==0) {
                return false;
            } else {
                return true;
            }
        }
    }
	
	function check_testing() {
	
		global $db;
		
		$query="SELECT * FROM options WHERE type='testing'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==0) {
			$query2="INSERT INTO options(content,type,note)
									value(now(),'testing','0')";
			mysqli_query($db,$query2);
		} else {
			$data=mysqli_fetch_assoc($result);
			if($data["note"]==0) {
				return false;
			} else {
				return true;
			}
		}
	}
	
	function check_nhayde() {
	
		global $db;
		
		$query="SELECT * FROM options WHERE type='nhayde'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==0) {
			$query2="INSERT INTO options(content,type,note)
									value(now(),'nhayde','1')";
			mysqli_query($db,$query2);
		} else {
			$data=mysqli_fetch_assoc($result);
			if($data["note"]==0) {
				return false;
			} else {
				return true;
			}
		}
	}
	
	function check_show_tien() {
	
		global $db;
		
		$query="SELECT * FROM options WHERE type='show_tien'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==0) {
			$query2="INSERT INTO options(content,type,note)
									value(now(),'show_tien','0')";
			mysqli_query($db,$query2);
		} else {
			$data=mysqli_fetch_assoc($result);
			if($data["note"]==0) {
				return false;
			} else {
				return true;
			}
		}
	}
	
	function turn_on_test() {
	
		global $db;
		
		$query="UPDATE options SET content=now(),note='1' WHERE type='testing'";
		
		mysqli_query($db,$query);
	}
	
	function turn_on_nhayde() {
	
		global $db;
		
		$query="UPDATE options SET content=now(),note='1' WHERE type='nhayde'";
		
		mysqli_query($db,$query);
	}
	
	function turn_on_show_tien() {
	
		global $db;
		
		$query="UPDATE options SET content=now(),note='1' WHERE type='show_tien'";
		
		mysqli_query($db,$query);
	}

    function turn_on_game() {

        global $db;

        $query="UPDATE options SET content=now(),note='1' WHERE type='game'";

        mysqli_query($db,$query);
    }

    function turn_off_game() {

        global $db;

        $query="UPDATE options SET content=now(),note='0' WHERE type='game'";

        mysqli_query($db,$query);
    }
	
	function turn_off_test() {
	
		global $db;
		
		$query="UPDATE options SET content='',note='0' WHERE type='testing'";
		
		mysqli_query($db,$query);
	}
	
	function turn_off_nhayde() {
	
		global $db;
		
		$query="UPDATE options SET content=now(),note='0' WHERE type='nhayde'";
		
		mysqli_query($db,$query);
	}
	
	function turn_off_show_tien() {
	
		global $db;
		
		$query="UPDATE options SET content=now(),note='0' WHERE type='show_tien'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function get_lido($ldID) {
	
		global $db;
		
		$query="SELECT * FROM lido WHERE ID_LD='$ldID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

    // Đã check
    function get_lido_short($ldID) {

        global $db;

        $query="SELECT name FROM lido WHERE ID_LD='$ldID'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["name"];
    }

	// Đã check
	function get_khoa($lmID) {
	
		global $db;
		
		$query="SELECT * FROM options WHERE type='khoa' AND note='$lmID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function check_khoa($lmID) {
	
		global $db;
		
		$query="SELECT content FROM options WHERE type='khoa' AND note='$lmID'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		if($data["content"]==0) {
			return false;
		} else {
			return true;
		}
	}

	// Đã check
	function turn_off_khoa($oID) {
	
		global $db;
		
		$query="UPDATE options SET content='0' WHERE ID_O='$oID'";
	
		mysqli_query($db,$query);
		
	}

	// Đã check
	function turn_on_khoa($oID) {
	
		global $db;
		
		$query="UPDATE options SET content='1' WHERE ID_O='$oID'";
	
		mysqli_query($db,$query);
		
	}

	// Đã check
	function change_opa($opa, $mau) {
	
		global $db;
		
		$query="UPDATE options SET note2='$opa-$mau' WHERE type='background' AND note='active'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function count_di_hoc($hsID,$date_in,$now,$lmID,$monID) {

        $newID=get_new_cum_buoi($lmID,$monID)-1;
        $di_hoc = count_hs_di_hoc($hsID,$date_in,$now,$lmID,$monID,$newID);
        $tong_hoc = count_all_di_hoc($hsID,$date_in,$now,$lmID,$monID,$newID);

        return array($tong_hoc, $di_hoc);
    }

    // Đã check
    function count_hs_di_hoc($hsID,$date_in,$now,$lmID,$monID,$newID) {

        global $db;

        if($now!=null) {
            $me="AND date LIKE '$now-%'";
        } else {
            $me="";
        }

        $di_hoc = 0;
//        $query="SELECT COUNT(d.ID_STT) AS dem FROM diemdanh AS d
//        INNER JOIN diemdanh_buoi AS b ON b.ID_STT=d.ID_DD AND b.ID_CUM IN (SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE ID_CUM!='$newID' AND date>='$date_in' $me AND ID_LM='$lmID' AND ID_MON='$monID') AND b.ID_LM='$lmID' AND b.ID_MON='$monID'
//        WHERE d.ID_HS='$hsID'";

        $query="SELECT COUNT(d.ID_STT) AS dem FROM diemdanh AS d
        WHERE d.ID_HS='$hsID' AND d.ID_DD IN (SELECT ID_STT FROM diemdanh_buoi WHERE ID_CUM IN (SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE ID_CUM!='$newID' AND date>='$date_in' $me AND ID_LM='$lmID' AND ID_MON='$monID') AND ID_LM='$lmID' AND ID_MON='$monID')";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        $di_hoc+=$data["dem"];


//        $query="SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE ID_CUM!='$newID' AND date>='$date_in' $me AND ID_LM='$lmID' AND ID_MON='$monID' ORDER BY ID_CUM ASC,date ASC";
//        $result=mysqli_query($db,$query);
//        while($data=mysqli_fetch_assoc($result)) {
//            $result4=check_di_hoc($hsID,$data["ID_CUM"],$lmID,$monID);
//            if($result4!=false) {
//                $di_hoc++;
//            }
//        }

        $lmID = 0;
        $newID = get_new_cum_buoi($lmID,$monID)-1;
        $query="SELECT COUNT(d.ID_STT) AS dem FROM diemdanh AS d
        WHERE d.ID_HS='$hsID' AND d.ID_DD IN (SELECT ID_STT FROM diemdanh_buoi WHERE ID_CUM IN (SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE ID_CUM!='$newID' AND date>='$date_in' $me AND ID_LM='$lmID' AND ID_MON='$monID') AND ID_LM='$lmID' AND ID_MON='$monID')";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        $di_hoc+=$data["dem"];



//        $query="SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE ID_CUM!='$newID' AND date>='$date_in' $me AND ID_LM='$lmID' AND ID_MON='$monID' ORDER BY ID_CUM ASC,date ASC";
//        $result=mysqli_query($db,$query);
//        while($data=mysqli_fetch_assoc($result)) {
//            $result4=check_di_hoc($hsID,$data["ID_CUM"],$lmID,$monID);
//            if($result4!=false) {
//                $di_hoc++;
//            }
//        }

        return $di_hoc;
    }

    // Đã check
    function count_all_di_hoc($hsID,$date_in,$now,$lmID,$monID,$newID) {

        global $db;

        if($now!=null) {
            $me="AND date LIKE '$now-%'";
        } else {
            $me="";
        }

        $query="SELECT COUNT(DISTINCT ID_CUM) AS dem FROM diemdanh_buoi WHERE ID_CUM!='$newID' AND date>='$date_in' $me AND ID_LM='$lmID' AND ID_MON='$monID' ORDER BY ID_CUM ASC,date ASC";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        $tong_hoc=$data["dem"];
//        $tong_hoc=mysqli_num_rows($result);

        $result2=get_nghi_dai_mon($hsID,$lmID);
        while($data2=mysqli_fetch_assoc($result2)) {
            $query="SELECT COUNT(DISTINCT ID_CUM) AS dem FROM diemdanh_buoi WHERE ID_CUM!='$newID' AND date>='$date_in' AND date>='$data2[start]' AND date<='$data2[end]' AND ID_LM='$lmID' AND ID_MON='$monID'";
            $result=mysqli_query($db,$query);
            $data=mysqli_fetch_assoc($result);
            $tong_hoc-=$data["dem"];
//            $tong_hoc-=mysqli_num_rows($result);
            $query="SELECT COUNT(DISTINCT ID_CUM) AS dem FROM diemdanh_buoi WHERE date>='$date_in' AND date>='$data2[start]' AND date<='$data2[end]' AND ID_LM='0' AND ID_MON='$monID'";
            $result=mysqli_query($db,$query);
            $data=mysqli_fetch_assoc($result);
            $tong_hoc-=$data["dem"];
//            $tong_hoc-=mysqli_num_rows($result);
        }

        $lmID = 0;
        $newID = get_new_cum_buoi($lmID,$monID)-1;
        $query="SELECT COUNT(DISTINCT ID_CUM) AS dem FROM diemdanh_buoi WHERE ID_CUM!='$newID' AND date>='$date_in' $me AND ID_LM='$lmID' AND ID_MON='$monID' ORDER BY ID_CUM ASC,date ASC";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        $tong_hoc+=$data["dem"];
//        $tong_hoc+=mysqli_num_rows($result);

        return $tong_hoc;

    }

	// Đã check
	function check_di_hoc($hsID, $cumID, $lmID, $monID) {
		
		global $db;
		
		$query="SELECT d.ID_STT,d.ca_check,d.is_hoc,d.is_tinh,d.is_kt FROM diemdanh AS d INNER JOIN diemdanh_buoi AS b ON b.ID_STT=d.ID_DD AND b.ID_CUM='$cumID' AND b.ID_LM='$lmID' AND b.ID_MON='$monID' WHERE d.ID_HS='$hsID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return $result;
		} else {
			return false;
		}
	}

	// Đã check
	function get_next_time($nam,$thang) {
		
		if($thang==12) {
			$nam++;
			$thang=1;
		} else {
			$thang++;
		}
		return $nam."-".format_month_db($thang);
	}
	
	function get_last_time($nam,$thang) {
		
		if($thang==1) {
			$nam--;
			$thang=12;
		} else {
			$thang--;
		}
		return $nam."-".format_month_db($thang);
	}

	// Đã check
	function get_diemdanh_nghi($hsID,$lmID,$monID) {
		
		global $db;
		
		$query="SELECT n.ID_CUM,n.is_phep,b.date,o.ID_O FROM diemdanh_nghi AS n 
		INNER JOIN diemdanh_buoi AS b ON b.ID_CUM=n.ID_CUM AND b.ID_LM=n.ID_LM AND b.ID_MON='$monID' 
		LEFT JOIN options AS o ON o.content=n.ID_CUM AND o.type='diemdanh-nghi' AND o.note='$lmID' AND o.note2='$monID'
		WHERE n.ID_HS='$hsID' AND n.ID_LM='$lmID' AND n.ID_MON='$monID' 
		ORDER BY b.date ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
    function check_binh_voi2($hsID, $lmID) {
        $date_in=date_create(get_date_in_hs($hsID,$lmID));
        $now=date_create(date("Y-m-d"));
        if($date_in<=$now) {
            $diff = date_diff($now, $date_in);
            $days = $diff->format("%a");
            if ($days <= 14) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

	// Đã check
	function check_binh_voi($hsID, $buoiID, $lmID) {
	
		global $db;
		
		$query="SELECT ID_BUOI FROM diemkt WHERE ID_HS='$hsID' AND loai IN ('0','1','3','5') AND ID_LM='$lmID' ORDER BY ID_BUOI ASC LIMIT 2";
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)<2) {
			return true;
		} else {
			$check=false;
			while($data=mysqli_fetch_assoc($result)) {
				if($data["ID_BUOI"]==$buoiID) {
					$check=true;
					break;
				}
			}
			return $check;
		}
	}
	
	function get_gender_ph($gender) {
	
		if($gender==1) {
			return "Bố";
		} else if($gender==0) {
			return "Mẹ";
		} else {
			return "";
		}
	}

	// Đã check
	function get_de_thang($hsID,$lmID,$time) {
	
		global $db;
		
		$query="SELECT detb FROM diemtb_thang WHERE ID_HS='$hsID' AND ID_LM='$lmID' AND datetime='$time'";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["detb"];
	}

	// Đã check
	function format_maso($pre_id, $lop_name) {
	
		global $db;
		
		if($pre_id<10) {
			$me=substr($lop_name,2,2)."-000".$pre_id;
		} else if($pre_id<100) {
			$me=substr($lop_name,2,2)."-00".$pre_id;
		} else if($pre_id<1000) {
			$me=substr($lop_name,2,2)."-0".$pre_id;
		} else {
			$me=substr($lop_name,2,2)."-".$pre_id;
		}
		
		return $me;
	}

	// Đã check
	function check_dong_tien_hoc($hsID, $lmID, $date_dong) {
	
		global $db;
		
		$query="SELECT SUM(money) AS price,date_dong2,note FROM tien_hoc WHERE ID_HS='$hsID' AND ID_LM='$lmID' AND date_dong='$date_dong'";
	
		$result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
		if(isset($data["date_dong2"])) {
			return array(format_price_sms($data["price"]),format_dateup($data["date_dong2"]),$data["note"]);
		} else {
			return array();
		}
	}

	// Đã check
	function fix_ca() {
		
		global $db;
		
		$kq="";
		$result=get_all_lop_mon();
		while($data=mysqli_fetch_assoc($result)) {
			
			$kq.="Môn $data[name] \n";
			
			$query2="SELECT h.ID_HS,h.cmt,n.ID_N FROM hocsinh AS h 
			INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$data[ID_LM]' 
			LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$data[ID_LM]' 
			ORDER BY h.ID_HS ASC";
			$result2=mysqli_query($db,$query2);
			while($data2=mysqli_fetch_assoc($result2)) {
				$query4="SELECT ID_STT FROM ca_codinh WHERE ID_HS='$data2[ID_HS]' AND cum IN (SELECT ID_CUM WHERE ID_LM='$data[ID_LM]')";
				$result4=mysqli_query($db,$query4);
				$codinh=mysqli_num_rows($result4);
				$query4="SELECT ID_STT FROM ca_hientai WHERE ID_HS='$data2[ID_HS]' AND cum IN (SELECT ID_CUM WHERE ID_LM='$data[ID_LM]')";
				$result4=mysqli_query($db,$query4);
				$hientai=mysqli_num_rows($result4);
				if($codinh==4 && $hientai==4) {
					// OK
				} else {
					if(isset($data2["ID_N"])) {
						if($codinh==0 && $hientai==0) {
							// Kệ
						} else {
							$kq.="$data2[ID_HS] - $data2[cmt] - $codinh/$hientai - nghi\n";
						}
					} else {
						$kq.="$data2[ID_HS] - $data2[cmt] - $codinh/$hientai\n";
					}
				}
			}
			
			$query3="DELETE FROM ca_codinh WHERE ID_CA='0' OR ID_HS='0' OR cum='0'";
			mysqli_query($db,$query3);
			$kq.="Xóa - ".mysqli_affected_rows($db);
			
			$query3="DELETE FROM ca_hientai WHERE ID_CA='0' OR ID_HS='0' OR cum='0'";
			mysqli_query($db,$query3);
			$kq.=" - ".mysqli_affected_rows($db);
			$kq.="\n";
			
			$query5="SELECT DISTINCT cum FROM ca_codinh ORDER BY cum ASC";
			$result5=mysqli_query($db,$query5);
			while($data5=mysqli_fetch_assoc($result5)) {
				$query6="SELECT ID_CA FROM cahoc WHERE cum='$data5[cum]'";
				$result6=mysqli_query($db,$query6);
				if(mysqli_num_rows($result6)==0) {
					$kq.="Sai cụm $data5[cum] cố định\n";
				}
			}
			
			$query5="SELECT DISTINCT cum FROM ca_hientai ORDER BY cum ASC";
			$result5=mysqli_query($db,$query5);
			while($data5=mysqli_fetch_assoc($result5)) {
				$query6="SELECT ID_CA FROM cahoc WHERE cum='$data5[cum]'";
				$result6=mysqli_query($db,$query6);
				if(mysqli_num_rows($result6)==0) {
					$kq.="Sai cụm $data5[cum] hiện tại\n";
				}
			}
		}
		
		return $kq;
	}

	// Đã check
	function fix_ca_tam() {
	
		global $db;
		
		$result=get_all_lop_mon();
		while($data=mysqli_fetch_assoc($result)) {
			$query2="TRUNCATE TABLE ca_hientai WHERE cum IN (SELECT ID_CUM WHERE ID_LM='$data[ID_LM]')";
			mysqli_query($db,$query2);
			$query3="SELECT ID_CA,ID_HS,cum FROM ca_codinh WHERE cum IN (SELECT ID_CUM WHERE ID_LM='$data[ID_LM]') ORDER BY ID_STT ASC";
			$result3=mysqli_query($db,$query3);
			while($data3=mysqli_fetch_assoc($result3)) {
				add_hs_to_ca_tam($data3["ID_CA"],$data3["ID_HS"],$data3["cum"]);
			}
		}
	}

	// Đã check
	function get_all_dia_diem() {
	
		global $db;
		
		$query="SELECT * FROM dia_diem ORDER BY ID_DD ASC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_one_dia_diem($ddID) {
	
		global $db;
		
		$query="SELECT * FROM dia_diem WHERE ID_DD='$ddID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function count_ca_dia_diem($ddID) {
	
		global $db;

        $query2="SELECT ID_CA FROM cahoc WHERE ID_DD='$ddID'";
        $result2=mysqli_query($db,$query2);
		
		return mysqli_num_rows($result2);
	}
	
	function add_dia_diem($name, $mota) {
		
		global $db;
		
		$query="INSERT INTO dia_diem(name,mota)
								value('$name','$mota')";
		
		mysqli_query($db,$query);
	}
	
	function delete_dia_diem($ddID) {
	
		global $db;
		
		$query="DELETE FROM dia_diem WHERE ID_DD='$ddID'";
		
		mysqli_query($db,$query);
	}
	
	function update_dia_diem($ddID, $name, $mota) {
	
		global $db;
		
		$query="UPDATE dia_diem SET name='$name',mota='$mota' WHERE ID_DD='$ddID'";
		
		mysqli_query($db,$query);
	}
	
	function update_dia_diem_ca($caID, $ddID, $ca) {
	
		global $db;
		
		$query="UPDATE $ca SET ID_DD='$ddID' WHERE ID_CA='$caID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function get_lastest_dia_diem() {
	
		global $db;
		
		$query="SELECT ID_DD FROM dia_diem ORDER BY ID_DD DESC LIMIT 1";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["ID_DD"];
	}
	
	function update_hs_gender($hsID,$gender) {
	
		global $db;
		
		$query="UPDATE hocsinh SET gender='$gender' WHERE ID_HS='$hsID'";
		
		mysqli_query($db,$query);
	}
	
	function update_hs_sdt($hsID,$sdt) {
	
		global $db;
		
		$query="UPDATE hocsinh SET sdt='$sdt' WHERE ID_HS='$hsID'";
		
		mysqli_query($db,$query);
	}

	// Đã check
	function check_dang_hoc($gio,$thu) {
	
		$now=date("H");
		$dayweek=date("w", strtotime(date("Y-m-d")))+1;
		$now=($now+1-1);
		$temp=explode("h",$gio);
		$temp[1]=substr($temp[1],3);
		if($thu==$dayweek) {
			if($now>=$temp[0] && $now<=$temp[1]) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

    // Đã check
    function get_lop_mon_main($monID) {

        global $db;

        $query="SELECT ID_LM FROM lop_mon WHERE ID_MON='$monID' ORDER BY ID_LM ASC LIMIT 1";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["ID_LM"];
    }

	// Đã check
	function get_mon_main() {
	
		global $db;
		
		$query="SELECT ID_LM FROM lop_mon ORDER BY ID_LM ASC LIMIT 1";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["ID_LM"];
	}

	// Đã check
	function get_lop_main() {
	
		global $db;
		
		$query="SELECT ID_LOP FROM lop WHERE ID_LOP!='3' ORDER BY ID_LOP ASC LIMIT 1";
		
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		
		return $data["ID_LOP"];
	}

	// Đã check
	function get_lydo_nghi($cumID,$hsID,$lmID,$monID) {
	
		global $db;
		
		$query="SELECT is_phep FROM diemdanh_nghi WHERE ID_CUM='$cumID' AND ID_HS='$hsID' AND ID_LM='$lmID' AND ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$data=mysqli_fetch_assoc($result);
			if($data["is_phep"]==1) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	// Đã check
    function get_diemdanh_nghi_buoi($cumID,$hsID,$lmID,$monID) {

        global $db;

        $query="SELECT * FROM diemdanh_nghi WHERE ID_CUM='$cumID' AND ID_HS='$hsID' AND ID_LM='$lmID' AND ID_MON='$monID'";

        $result=mysqli_query($db,$query);

        return $result;
    }
	
	function check_diemdanh_nghi($cumID,$hsID,$monID) {
		
		global $db;
		
		$query="SELECT ID_STT FROM diemdanh_nghi WHERE ID_CUM='$cumID' AND ID_HS='$hsID' AND ID_MON='$monID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}
	
	function format_lydo_nghi($is_phep) {
	
		if($is_phep==1) {
			return "Có phép";
		} else {
			return "Không phép";
		}
	}

	// Đã check
	function add_log($hsID,$content,$type) {
	
		global $db;
		
		$query="INSERT INTO log(ID_HS,content,type,datetime)
							value('$hsID','$content','$type',now())";
							
		mysqli_query($db,$query);
	}
	
	function delete_log($hsID,$type) {
	
		global $db;
		
		$query="DELETE FROM log WHERE ID_HS='$hsID' AND type='$type' LIMIT 1";
							
		mysqli_query($db,$query);
	}

    function delete_log2($sttID) {

        global $db;

        $query="DELETE FROM log WHERE ID_STT='$sttID'";

        mysqli_query($db,$query);
    }

    // Đã check
	function count_log($hsID,$type) {
	
		global $db;
		
		$query="SELECT ID_STT FROM log WHERE ID_HS='$hsID' AND type='$type'";
							
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
	}
	
	function get_log($time,$type) {
	
		global $db;
		
		$query="SELECT l.*,h.cmt,h.fullname FROM log AS l INNER JOIN hocsinh AS h ON h.ID_HS=l.ID_HS WHERE l.type='$type' AND l.datetime LIKE '$time-%' ORDER BY l.ID_STT DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_info_ca($caID) {
	
		global $db;
		
		$query="SELECT c.thu,g.gio FROM cahoc AS c INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO WHERE c.ID_CA='$caID'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_log_hs($hsID,$type) {
	
		global $db;
		
		$query="SELECT * FROM log WHERE ID_HS='$hsID' AND type='$type' ORDER BY datetime DESC LIMIT 30";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function phat_nong($idVAO,$obj) {
	
		global $db;
		
		$query="UPDATE tien_vao SET object='$obj' WHERE ID_VAO='$idVAO'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_phat_detail($idVAO) {
	
		global $db;
		
		$query="SELECT * FROM tien_vao WHERE ID_VAO='$idVAO'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

    function get_thuong_detail($idRA) {

        global $db;

        $query="SELECT * FROM tien_ra WHERE ID_RA='$idRA'";

        $result=mysqli_query($db,$query);

        return $result;
    }
	
	function count_phat_nong($hsID) {
	
		global $db;
		
		$query="SELECT ID_VAO FROM tien_vao WHERE ID_HS='$hsID' AND string='phat-nong' AND object='0'";
		
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
	}
	
	function format_phone($phone) {
		
		if($phone!="" && $phone!="X") {
			$temp=substr($phone,0,2);
            $temp2=substr($phone,0,1);
			if($temp=="84") {
				return "0".substr($phone,2);
			} else if($temp2 != "0") {
                return "0".$phone;
			} else {
				return $phone;
			}
		} else {
			return $phone;
		}
	}
	
	function check_code_trogiang($oID,$code) {
	
		global $db;
		
		$query="SELECT ID_O FROM options WHERE ID_O='$oID' AND content='$code' AND type='tro-giang-code'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
		
	}
	
	function add_info_buoikt($buoiID,$hsID,$nhap,$giay,$is_de,$is_du,$is_huy,$note,$made,$monID) {
	
		global $db;
		
		$query="SELECT ID_STT FROM info_buoikt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_MON='$monID'";
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$query2="UPDATE info_buoikt SET nhap='$nhap',giay='$giay',is_de='$is_de',is_du='$is_du',is_huy='$is_huy',note='$note',made='$made' WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_MON='$monID'";
			mysqli_query($db,$query2);
		} else {
			$query2="INSERT INTO info_buoikt(ID_BUOI,ID_HS,nhap,giay,is_de,is_du,is_huy,note,made,ID_MON)
									value('$buoiID','$hsID','$nhap','$giay','$is_de','$is_du','$is_huy','$note','$made','$monID')";
			mysqli_query($db,$query2);
		}
		
	}

    function add_info_buoikt2($buoiID,$hsID,$made,$monID) {

        global $db;

        $query="SELECT * FROM info_buoikt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_MON='$monID'";
        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)!=0) {
            $data=mysqli_fetch_assoc($result);
            $query2="UPDATE info_buoikt SET nhap='$data[nhap]',giay='$data[giay]',is_de='$data[is_de]',is_du='$data[is_du]',is_huy='$data[is_huy]',note='$data[note]',made='$made' WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_MON='$monID'";
            mysqli_query($db,$query2);
        } else {
            $query2="INSERT INTO info_buoikt(ID_BUOI,ID_HS,nhap,giay,is_de,is_du,is_huy,note,made,ID_MON)
                                        value('$buoiID','$hsID','1','1','1','1','0','none','$made','$monID')";
            mysqli_query($db,$query2);
        }

    }
	
	function edit_trogiang($oID, $name, $phone, $date_in, $mota, $price, $code) {
	
		global $db;
		
		if($code!="") {
			$query="UPDATE options SET content='$code',note='$name' WHERE ID_O='$oID' AND type='tro-giang-code'";
		} else {
			$query="UPDATE options SET note='$name' WHERE ID_O='$oID' AND type='tro-giang-code'";
		}
		
		mysqli_query($db,$query);
        $query2="UPDATE info_trogiang SET mota='$mota',price='$price',phone='$phone',date_in='$date_in' WHERE ID_O='$oID'";
        mysqli_query($db,$query2);
		
	}
	
	function add_trogiang($name,$code) {
	
		global $db;
		
		$query="INSERT INTO options(content,type,note)
							value('$code','tro-giang-code','$name')";
							
		mysqli_query($db,$query);
		
	}
	
	function check_isset_trogiang($code) {
	
		global $db;
		
		$query="SELECT ID_O FROM options WHERE content='$code' AND type='tro-giang-code'";
		
		$result=mysqli_query($db,$query);
		
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}

	// Đã check
	function get_nghi_dai($hsID,$loai) {
	
		global $db;
		
		$query="SELECT n.*,m.name FROM nghi_temp AS n INNER JOIN lop_mon AS m ON m.ID_LM=n.ID_LM WHERE n.ID_HS='$hsID' AND n.loai='$loai'";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	// Đã check
	function get_nghi_dai_mon($hsID,$lmID) {

        global $db;

        $query="SELECT * FROM nghi_temp WHERE ID_HS='$hsID' AND ID_LM='$lmID' AND loai='0'";

        $result=mysqli_query($db,$query);

        return $result;
    }
	
	function get_trang_thai_nghi($start,$end) {

        if($end!="0000-00-00") {
            $start=date_create($start);
            $end=date_create($end);
            $now=date_create(date("Y-m-d"));
            if ($start <= $now && $now <= $end) {
                return "Đang nghỉ";
            } else if ($now < $start) {
                return "Chuẩn bị";
            } else {
                return "Kết thúc";
            }
        } else {
            return "Đang nghỉ";
        }
	}

	// Đã check
	function diemdanh_nghi_dai($cumID,$now,$lmID,$monID) {
	
		global $db;

        if($lmID!=0) {
            $query = "SELECT ID_HS FROM nghi_temp WHERE start<='$now' AND ((end>='$now' AND loai='0') OR (end='0000-00-00' AND loai='1')) AND ID_LM='$lmID' ORDER BY ID_HS ASC";
        } else {
            $query = "SELECT ID_HS FROM nghi_temp WHERE start<='$now' AND ((end>='$now' AND loai='0') OR (end='0000-00-00' AND loai='1')) AND ID_LM IN (SELECT ID_LM FROM lop_mon WHERE ID_MON='$monID') ORDER BY ID_HS ASC";
        }
		
		$result=mysqli_query($db,$query);
		while($data=mysqli_fetch_assoc($result)) {
            insert_diemdanh_nghi($cumID,$data["ID_HS"],1,$lmID,$monID);
		}
		
	}

	// Đã check
	function check_nghi_dai($now,$hsID,$lmID) {

	    global $db;

        $query="SELECT ID_STT FROM nghi_temp WHERE ID_HS='$hsID' AND start<='$now' AND ((end>='$now' AND loai='0') OR (end='0000-00-00' AND loai='1')) AND ID_LM='$lmID'";

        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)!=0) {
            return true;
        } else {
            return false;
        }
    }



    function get_last_day($now) {

        $temp=explode("-",$now);
        $year=$temp[0];
        $month=$temp[1];

        $days_of_month=array("31","28","31","30","31","30","31","31","30","31","30","31");
        if($year%4==0) {
            $days_of_month[1]=29;
        }

        return $days_of_month[$month-1];
    }

    // Đã check
    function check_nghi_dai_thang($now,$hsID,$lmID) {

        global $db;

        $last=get_last_day($now);

        $query="SELECT ID_STT FROM nghi_temp WHERE ID_HS='$hsID' AND start<='$now-01' AND end>='$now-$last' AND loai='0' AND ID_LM='$lmID'";

        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)!=0) {
            return true;
        } else {
            return false;
        }
    }

    function get_hs_id_rand($monID) {

        global $db;

        $query="SELECT ID_HS FROM hocsinh_mon WHERE ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_MON='$monID') AND ID_MON='$monID' ORDER BY rand() LIMIT 1";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["ID_HS"];
    }
	
	function is_ddos($ip,$now) {
	
		global $db;
		
		$query="SELECT ID_STT FROM ddos WHERE IP='$ip' AND datetime LIKE '$now%'";
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)>=10) {
			return true;
		} else {
			return false;
		}
	}

	// Đã check
	function update_ddos($ip,$note) {
	
		global $db;
		
		$query="INSERT INTO ddos(IP,note,datetime)
						value('$ip','$note',now())";
						
		mysqli_query($db,$query);
	}

	// Đã check
	function clean_ddos($ip,$now) {
	
		global $db;
		
		$query="DELETE FROM ddos WHERE IP='$ip' AND datetime LIKE '$now%'";
		
		mysqli_query($db,$query);
	}

	// Đã check
    function get_cum_date_thu($cumID,$lmID,$monID) {

        global $db;

        $query="SELECT date FROM diemdanh_buoi WHERE ID_CUM='$cumID' AND ID_LM='$lmID' AND ID_MON='$monID' ORDER BY date ASC";

        $string="";
        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            $thu = date("w",strtotime($data["date"])) + 1;
            if(stripos($string,format_date($data["date"]))===false) {
                if($thu==1) {
                    $string.=" và (Chủ nhật) ".format_date($data["date"]);
                } else {
                    $string.=" và (Thứ $thu) ".format_date($data["date"]);
                }
            }
        }

        return substr($string,4);
    }

    // Đã check
    function get_last_cum_date($cumID,$lmID,$monID) {

        global $db;

        $query="SELECT date FROM diemdanh_buoi WHERE ID_CUM='$cumID' AND ID_LM='$lmID' AND ID_MON='$monID' ORDER BY date DESC LIMIT 1";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["date"];
    }

	// Đã check
	function get_cum_date($cumID,$lmID,$monID) {
	
		global $db;
		
		$query="SELECT date FROM diemdanh_buoi WHERE ID_CUM='$cumID' AND ID_LM='$lmID' AND ID_MON='$monID' ORDER BY date ASC";
		
		$string="";
		$result=mysqli_query($db,$query);
		while($data=mysqli_fetch_assoc($result)) {
			if(stripos($string,format_date($data["date"]))===false) {
				$string.=" - ".format_date($data["date"]);
			}
		}
		
		return substr($string,3);
	}

    // Đã check
    function get_cum_date2($cumID,$lmID,$monID) {

        global $db;

        $query="SELECT date FROM diemdanh_buoi WHERE ID_CUM='$cumID' AND ID_LM='$lmID' AND ID_MON='$monID' ORDER BY date ASC LIMIT 1";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["date"];
    }

	// Đã check
	function check_hs_diem($hsID,$buoiID,$lmID) {
	
		global $db;
		
		$query="SELECT ID_DIEM FROM diemkt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_LM='$lmID'";
		
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}

	// Đã check
	function check_exited_thachdau3($hsID,$ngay,$lmID) {

		global $db;

		$query="SELECT ID_STT FROM thachdau WHERE (ID_HS='$hsID' OR ID_HS2='$hsID') AND buoi='$ngay' AND status!='cancle' AND ID_LM='$lmID'";

		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			return true;
		} else {
			return false;
		}
	}

	// Đã check
    function check_exited_thachdau4($hsID,$ngay,$lmID) {

        global $db;

        $query="SELECT ID_STT FROM thachdau WHERE (ID_HS='$hsID' OR ID_HS2='$hsID') AND buoi='$ngay' AND status='accept' AND ID_LM='$lmID'";

        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)!=0) {
            return true;
        } else {
            return false;
        }
    }

    // Đã check
    function khoang_number($number, $khoang, $lo, $hi) {
        for($i = $lo; $i <= $hi; $i += $khoang) {
            if($number == $i) {
                return $i;
            } else if($number - $i <= $khoang) {
                return near_number($i, $i + $khoang, $number);
            }
        }
    }

    // Đã check
	function get_diem_near($du) {
	
		if($du==0 || $du==0.25 || $du==0.5 || $du==0.75 || $du==1) {
			return $du;
		} else {
			if($du<0.25) {
				return near_number(0,0.25,$du);
			} else {
				if($du<0.5) {
					return near_number(0.25,0.5,$du);
				} else {
					if($du<0.75) {
						return near_number(0.5,0.75,$du);
					} else {
						return near_number(0.75,1,$du);
					}
				}
			}	
		}
	}

	// Đã check
    function near_number($a,$b,$du) {

        /*$k=(($b-$a)/2) + $a;
        if($du<=$k) {
            return $a;
        } else {
            return $b;
        }*/

        if($du - $a <= $b - $du) {
            return $a;
        } else {
            return $b;
        }

//        return $a;
    }

	// Đã check
	function add_note($title,$content,$name,$status,$loai,$hsID) {
	
		global $db;

        if($hsID!=0) {
            delete_special_note($hsID,0);
        }
		$query="INSERT INTO note(title,content,name,status,level,ID_HS,datetime)
							VALUES('$title','$content','$name','$status','$loai','$hsID',now())";
		
		mysqli_query($db,$query);
        return mysqli_insert_id($db);
	}

	// Đã check
    function get_hs_special_note($hsID) {

        global $db;

        $query="SELECT content,name,datetime FROM note WHERE status='0' AND ID_HS='$hsID' ORDER BY level DESC,ID_N ASC LIMIT 1";

        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)!=0) {
            $data = mysqli_fetch_assoc($result);

            return $data["content"] . " ($data[name] - " . format_datetime($data["datetime"]) . ")";
        } else {
            return "none";
        }
    }

    // Đã check
    function get_special_note($hsID) {

        global $db;

        $query="SELECT * FROM note WHERE status='0' AND ID_HS='$hsID' ORDER BY level DESC,ID_N ASC LIMIT 1";

        $result=mysqli_query($db,$query);

        return $result;
    }

    function update_note_all($nID,$content) {

        global $db;

        $query="UPDATE note SET content='$content' WHERE ID_N='$nID'";

        mysqli_query($db,$query);
    }

    function delete_special_note($hsID,$status) {

        global $db;

        $query="DELETE FROM note WHERE ID_HS='$hsID' AND status='$status'";

        mysqli_query($db,$query);
    }
	
	function delete_note($nID) {
	
		global $db;
		
		$query="DELETE FROM note WHERE ID_N='$nID'";
		
		mysqli_query($db,$query);
	}
	
	function update_note($nID,$status) {
	
		global $db;
		
		$query="UPDATE note SET status='$status' WHERE ID_N='$nID'";
		
		mysqli_query($db,$query);
	}
	
	function get_note_status($status) {
	
		if($status==1) {
			return "Hoàn thành";
		} else {
			return "Chưa hoàn thành";
		}
	}

	// Đã check
    function get_gim_note($limit) {

        global $db;

        $query="SELECT * FROM note WHERE status='0' AND level='1' ORDER BY datetime DESC LIMIT $limit";

        $result=mysqli_query($db,$query);

        return $result;
    }
	
	function get_note($position,$display) {
	
		global $db;
		
		$query="SELECT * FROM note WHERE status='0' AND ID_HS='0' ORDER BY ID_N DESC LIMIT $position,$display";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function get_all_note() {
	
		global $db;
		
		$query="SELECT * FROM note ORDER BY ID_N DESC";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}
	
	function count_note_undone() {
		
		global $db;
		
		$query="SELECT ID_N FROM note WHERE status='0' AND ID_HS='0'";
		
		$result=mysqli_query($db,$query);
		
		return mysqli_num_rows($result);
	}
	
	function search_truong($search) {
	
		global $db;
		
		$query="SELECT * FROM truong WHERE string LIKE '%$search%' ORDER BY string ASC LIMIT 5";
		
		$result=mysqli_query($db,$query);
		
		return $result;
	}

	function get_who_pay($oID) {

	    global $db;

        $query="SELECT p.ID_P,p.phan,a.ID,a.fullname FROM admin AS a LEFT JOIN pay_trogiang AS p ON p.ID_A=a.ID AND p.ID_O='$oID' WHERE level='2' ORDER BY a.ID ASC";

        $result=mysqli_query($db,$query);

        return $result;
    }

    function get_who_paid($oID) {

        global $db;

        $string="";
        $query="SELECT p.phan,a.fullname FROM admin AS a INNER JOIN pay_trogiang AS p ON p.ID_A=a.ID AND p.ID_O='$oID' WHERE level='2' ORDER BY a.ID ASC";

        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            $string.=", $data[fullname] ($data[phan]%)<br />";
        }
        return substr($string,2);
    }

    function add_pay_trogiang($idA,$is_pay,$phan,$oID) {

        global $db;

        if($is_pay==0) {
            $query="DELETE FROM pay_trogiang WHERE ID_A='$idA' AND ID_O='$oID'";
            mysqli_query($db,$query);
        } else {
            $query="SELECT ID_P FROM pay_trogiang WHERE ID_A='$idA' AND ID_O='$oID'";
            $result=mysqli_query($db,$query);
            if(mysqli_num_rows($result)!=0) {
                $query2="UPDATE pay_trogiang SET phan='$phan' WHERE ID_A='$idA' AND ID_O='$oID'";
            } else {
                $query2="INSERT INTO pay_trogiang(ID_A,ID_O,phan)
                                            value('$idA','$oID','$phan')";
            }
            mysqli_query($db,$query2);
        }

    }

    function check_thanh_toan($date,$monID,$note) {

        global $db;

        if($note=="thanh_toan") {
            $query = "SELECT ID_STT FROM tien_thanh_toan WHERE datetime LIKE '$date-%' AND ID_MON='$monID' AND note='$note'";
        } else {
            $query = "SELECT ID_STT FROM tien_thanh_toan WHERE datetime='$date' AND ID_MON='$monID' AND note='$note'";
        }

        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)!=0) {
            return true;
        } else {
            return false;
        }
    }

    function get_admin_id_mon($monID) {

        global $db;

        $query="SELECT ID FROM admin WHERE level='2' AND note='$monID'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["ID"];
    }

    function get_sdt_hocsinh($hsID) {

        global $db;

        $query="SELECT sdt FROM hocsinh WHERE ID_HS='$hsID'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["sdt"];
    }

    function get_sdt_phuhuynh($hsID) {

        global $db;

        $query="SELECT sdt_bo,sdt_me FROM hocsinh WHERE ID_HS='$hsID'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        if(is_numeric($data["sdt_bo"])) {
            return $data["sdt_bo"];
        } else {
            return $data["sdt_me"];
        }
    }

    // Đã check
    function check_chua_dong_hoc($hsID,$date_in,$lmID,$monID,$now,$old) {

        $date_in2=date_create($date_in);
        $now_in2=date_create($now."-02");
        $mon_name=get_mon_name($monID);

        if(stripos($date_in,$now)===false) {
            $temp=explode("-",$date_in);
            $nam_in=$temp[0];
            $thang_in=$temp[1];
            $temp=explode("-",$now);
            $nam_in2=$temp[0];
            $thang_in2=$temp[1];
            if($nam_in2<$nam_in || ($nam_in2==$nam_in && $thang_in2<$thang_in)) {
                return "Chưa học";
            } else {
                // Học lâu rồi
                if (!check_hs_nghi($hsID, $lmID)) {
                    if (!check_nghi_dai_thang($now, $hsID, $lmID)) {
                        // Ko nghỉ hẳn và ko nghỉ dài
                        return du_kien_tien_hoc($now, date("Y-m-d"), $hsID, $mon_name);
                    } else {
                        // Ko nghỉ hẳn nhưng có nghỉ dài
                        return "Nghỉ dài trong tháng";
                    }
                } else {
                    $date_nghi = get_date_nghi($hsID, $lmID);
                    $date_nghi2 = date_create($date_nghi);
                    if ($date_nghi2 <= $now_in2) {
                        // Nghỉ từ trước
                        return "Nghỉ từ trước";
                    } else if (stripos($date_nghi, $now) === false) {
                        // Nghỉ sau đó
                        return du_kien_tien_hoc($now, date("Y-m-d"), $hsID, $mon_name);
                    } else {
                        // Nghỉ trong tháng đó
                        if ($old) {
                            return "Nghỉ trong tháng";
                        } else {
                            return count_hs_di_hoc($hsID, $date_in, $now, $lmID, $monID, 0) * get_muctien("tien_hoc_buoi");
                        }
                    }
                }
            }
        } else {
            // Mới học trong tháng đó
            if (!check_hs_nghi($hsID, $lmID) && !check_nghi_dai_thang($now, $hsID, $lmID)) {
                // Không nghỉ hẳn và cũng không nghỉ dài
                if(get_day_from_date($date_in)<=7) {
                    $price = get_muctien("dau_thang_sau_".unicode_convert($mon_name));
                } else {
                    $price = du_kien_tien_hoc_buoi2($now, get_day_from_date($date_in), $hsID, $lmID, $monID);
                    $price = $price[0];
                }
                $discount=get_discount_hs($hsID,$monID);
                return $price-($price*$discount/100);
            } else {
                return "Đã nghỉ hẳn hoặc nghỉ dài tháng này";
            }
        }
    }

    // Đã check
    function update_level($hsID,$lmID,$mylvl) {

        $x=count_thachdau_win($hsID,$lmID);
        $delta=1+8*$x;
        $lvl=(-1+sqrt($delta))/2;
        $lvl=floor($lvl);
        if($lvl>$mylvl) {
            tang_level($hsID,$lmID,$lvl);
            thuong_level($hsID,$lmID,$lvl);
        } else if($lvl<$mylvl) {
            $tien=get_muctien("thuong_level");
            $mon_name=get_lop_mon_name($lmID);
            tang_level($hsID,$lmID,$lvl);
            if($mylvl%5==0 && $mylvl!=0) {
                tru_tien_hs($hsID,$lvl*$tien,"Thu lại tiền thưởng tiền lên level $lvl môn $mon_name: -".format_price($lvl*$tien)." do bị hạ level","len_level",$lmID);
            }
            delete_log($hsID,"len-level-big-$lmID");
        } else {

        }
    }

    // Đã check
    function tang_level($hsID,$lmID,$lvl) {

        global $db;

        $query="UPDATE hocsinh_mon SET level='$lvl' WHERE ID_HS='$hsID' AND ID_LM='$lmID'";

        mysqli_query($db,$query);
    }

    // Đã check
    function thuong_level($hsID,$lmID,$lvl) {

        $tien=get_muctien("thuong_level");
        $mon_name=get_lop_mon_name($lmID);
        if($lvl%5==0 && $lvl!=0) {
            cong_tien_hs($hsID,$lvl*$tien,"Thưởng tiền lên level $lvl môn $mon_name: +".format_price($lvl*$tien),"len_level",$lmID);
            add_log($hsID,"Chúc mừng bạn đã đạt level $lvl và được thưởng ".format_price($lvl*$tien)." vào tài khoản!","len-level-big-$lmID");
            add_thong_bao_hs2($hsID,$lvl,"Chúc mừng bạn đã đạt level $lvl và được thưởng ".format_price($lvl*$tien)." vào tài khoản!","len-level",$lmID);
        } else {
            add_log($hsID,"Bạn được tặng 1 thẻ miễn phạt do đạt level $lvl","len-level-$lmID");
            add_thong_bao_hs($hsID,$lvl,"Chúc mừng bạn đã đạt level $lvl và được tặng 1 thẻ miễn phạt","len-level",$lmID);
        }
    }

    // Đã check
    function get_level($hsID,$lmID) {

        global $db;

        $query="SELECT level FROM hocsinh_mon WHERE ID_HS='$hsID' AND ID_LM='$lmID'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["level"];
    }

    // Đã check
    function get_history_nghi($hsID,$date_in,$lmID,$monID) {

        global $db;

        $now=date("Y-m");
        $last=get_last_time(date("Y"),date("m"));
        $string="-";

        $query="SELECT ID_CUM,date FROM diemdanh_buoi WHERE date>='$date_in' AND (date LIKE '$last-%' OR date LIKE '$now-%') AND ID_LM='$lmID' AND ID_MON='$monID' GROUP BY ID_CUM ORDER BY ID_CUM ASC,date ASC";
        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            $result2 = check_di_hoc($hsID, $data["ID_CUM"], $lmID, $monID);
            if ($result2 != false) {
                $string .= "<span>D-</span>";
            } else {
                if (get_lydo_nghi($data["ID_CUM"], $hsID, $lmID, $monID)) {
                    $string.="<span style='color:red;'>P</span>-";
                } else {
                    $string.="<span style='color:red;'>X</span>-";
                }
            }
        }

        $string.=" ||| ";

        $query="SELECT ID_CUM,date FROM diemdanh_buoi WHERE date>='$date_in' AND (date LIKE '$last-%' OR date LIKE '$now-%') AND ID_LM='0' AND ID_MON='$monID' GROUP BY ID_CUM ORDER BY ID_CUM ASC,date ASC";
        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            $result2 = check_di_hoc($hsID, $data["ID_CUM"], 0, $monID);
            if ($result2 != false) {
                $string .= "<span>D-</span>";
            } else {
                if (get_lydo_nghi($data["ID_CUM"], $hsID, 0, $monID)) {
                    $string.="<span style='color:red;'>P</span>-";
                } else {
                    $string.="<span style='color:red;'>X</span>-";
                }
            }
        }

        return $string;
    }

    // Đã check
    function check_done_options($ID,$type,$lmID,$monID) {

        global $db;

        $query="SELECT ID_O FROM options WHERE content='$ID' AND type='$type' AND note='$lmID' AND note2='$monID'";
        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)!=0) {
            return true;
        } else {
            return false;
        }
    }

    // Đã check
    function get_all_hs_level($de,$lmID) {

        global $db;

        if($de=="G" || $de=="B" || $de=="Y") {
            $query = "SELECT h.ID_HS,h.cmt,h.fullname,m.de,m.level,n.ID_N FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.de='$de' AND m.ID_LM='$lmID' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID' ORDER BY m.level DESC,h.cmt ASC";
        } else {
            $query = "SELECT h.ID_HS,h.cmt,h.fullname,m.de,m.level,n.ID_N FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID' ORDER BY m.level DESC,h.cmt ASC";
        }
        $result=mysqli_query($db,$query);

        return $result;
    }

    // Đã check
    function add_options3($content, $type, $note, $note2) {

        global $db;

        $query = "INSERT INTO options(content,type,note,note2)
                                       value('$content','$type','$note','$note2')";
        mysqli_query($db, $query);

    }

    // Đã check
    function update_options($content, $oID) {

        global $db;

        $query="UPDATE options SET content='$content' WHERE ID_O='$oID'";

        mysqli_query($db, $query);
    }

    // Đã check
    function add_options2($content, $type, $note, $note2) {

        global $db;

        $query0="SELECT * FROM options WHERE type='$type' AND note='$note' AND note2='$note2'";
        $result0=mysqli_query($db,$query0);
        if(mysqli_num_rows($result0)!=0) {
            $query = "UPDATE options SET content='$content' WHERE type='$type' AND note='$note' AND note2='$note2'";
        } else {
            $query = "INSERT INTO options(content,type,note,note2)
                                       value('$content','$type','$note','$note2')";
        }
        mysqli_query($db, $query);

    }

    // Đã check
    function get_list_note($position,$display) {

        global $db;

        //$now=date("Y-m-d H:i:s");
        //$days_ago = date('Y-m-d H:i:s', strtotime('-3 days', strtotime($now)));
        $query="SELECT o.content AS datetime,h.ID_HS,h.cmt AS maso,h.fullname,h.facebook,o.note2 AS ID,h.note,h.hot AS hot,o.note AS has FROM hocsinh AS h 
        INNER JOIN options AS o ON o.note2=h.ID_HS AND o.type='cap-nhat-note' AND o.note=''
        WHERE h.note!=''
        UNION ALL 
        SELECT o.content AS datetime,h.ID_HS,h.cmt AS maso,h.fullname,h.facebook,n.ID_STT AS ID,n.note,n.hot AS hot,n.ngay AS has FROM hocsinh AS h
        INNER JOIN hocsinh_note AS n ON n.ID_HS=h.ID_HS AND n.ready='1'
        INNER JOIN options AS o ON o.note2=h.ID_HS AND o.type='cap-nhat-note' AND o.note=n.ID_STT
        WHERE n.note!=''
        ORDER BY datetime DESC,maso ASC LIMIT $position,$display";

        $result=mysqli_query($db, $query);

        return $result;
    }

    // Đã check
    function update_hot_hs($hsID,$hot) {

        global $db;

        $query="UPDATE hocsinh SET hot='$hot' WHERE ID_HS='$hsID'";

        mysqli_query($db, $query);
    }

    // Đã check
    function add_log2($hsID,$content,$type) {

        global $db;

        $query="INSERT INTO log(ID_HS,content,type,datetime) SELECT * FROM (SELECT '$hsID' AS hsID,'$content' AS content,'$type' AS type,now() AS now) AS tmp WHERE NOT EXISTS (SELECT ID_STT FROM log WHERE content='$content' AND type='$type' AND ID_HS='$hsID') LIMIT 1";

        mysqli_query($db,$query);
    }

    // Đã check
    function add_options($content, $type, $note, $note2) {

        global $db;

        $query="INSERT INTO options(content,type,note,note2) SELECT * FROM (SELECT '$content' AS content,'$type' AS type,'$note' AS note,'$note2' AS note2) AS tmp WHERE NOT EXISTS (SELECT ID_O FROM options WHERE content='$content' AND type='$type' AND note='$note' AND note2='$note2') LIMIT 1";

        mysqli_query($db, $query);
    }

    // Đã check
    function add_danhmuc($title, $lmID, $monID) {

        global $db;

        $query="INSERT INTO danhmuc(title,ID_LM,ID_MON)
                            value('$title','$lmID','$monID')";

        mysqli_query($db, $query);

    }

    // Đã check
    function get_danhmuc($lmID, $monID) {

        global $db;

        $query="SELECT * FROM danhmuc WHERE ID_LM='$lmID' AND ID_MON='$monID' ORDER BY ID_DM ASC";

        $result=mysqli_query($db, $query);

        return $result;
    }

    // Đã check
    function get_all_danhmuc($monID) {

        global $db;

        $query="SELECT d.*,l.name FROM danhmuc AS d LEFT JOIN lop_mon AS l ON l.ID_LM=d.ID_LM WHERE d.ID_MON='$monID'";

        $result=mysqli_query($db,$query);

        return $result;
    }

    // Đã check
    function get_one_danhmuc($dmID) {

        global $db;

        $query="SELECT * FROM danhmuc WHERE ID_DM='$dmID'";

        $result=mysqli_query($db,$query);

        return $result;
    }

    function edit_danhmuc($dmID, $title) {

        global $db;

        $query="UPDATE danhmuc SET title='$title' WHERE ID_DM='$dmID'";

        mysqli_query($db,$query);

    }

    function delete_danhmuc($dmID) {

        global $db;

        $query="DELETE FROM danhmuc WHERE ID_DM='$dmID'";

        mysqli_query($db,$query);
    }

    function get_name_danhmuc($dmID) {

        global $db;

        $query="SELECT title FROM danhmuc WHERE ID_DM='$dmID'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["title"];
    }

    // Đã check
    function check_hs_in_codinh_cum($hsID,$cum) {

        global $db;

        $query="SELECT ID_STT FROM ca_codinh WHERE ID_HS='$hsID' AND cum='$cum'";

        $result=mysqli_query($db,$query);

        if(mysqli_num_rows($result)!=0) {
            return true;
        } else {
            return false;
        }
    }

    function get_mon_id($mon_name) {

        global $db;

        $mon_name=unicode_convert($mon_name);
        $query="SELECT ID_MON,name FROM mon";

        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            if(unicode_convert($data["name"])==$mon_name) {
                return $data["ID_MON"];
            }
        }

        return 0;
    }

    // Đã check
    function get_soc($num) {
        $string="";
        for($i=1;$i<=$num;$i++) {
            $string.="|";
        }
        return $string;
    }

    // Đã check
    function get_phan_tram($diemtb) {

        $string="";
        $temp=floor($diemtb/5)*5;
        for($i=0;$i<=$temp;$i+=5) {
            $string.="|";
        }
        return $string;
    }

    // Đã check
    function delete_thongbao($hsID, $object, $danhmuc, $lmID) {

        global $db;

        $query="DELETE FROM thongbao WHERE ID_HS='$hsID' AND object='$object' AND danhmuc='$danhmuc' AND ID_LM='$lmID' ORDER BY ID_TB DESC LIMIT 1";

        mysqli_query($db,$query);

    }

    // Đã check
    function delete_thongbao2($tbID) {

        global $db;

        $query="DELETE FROM thongbao WHERE ID_TB='$tbID'";

        mysqli_query($db,$query);

    }

    function edit_thongbao($tbID,$content) {

        global $db;

        $query="UPDATE thongbao SET content='$content' WHERE ID_TB='$tbID'";

        mysqli_query($db,$query);

    }

    function change_status_thongbao($tbID,$status) {

        global $db;

        $query="UPDATE thongbao SET status='$status' WHERE ID_TB='$tbID'";

        mysqli_query($db,$query);

    }

    function check_tru_tien($hsID,$string,$object) {

        global $db;

        $query="SELECT ID_VAO FROM tien_vao WHERE ID_HS='$hsID' AND string='$string' AND object='$object'";

        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)!=0) {
            return true;
        } else {
            return false;
        }
    }

    // Đã check
    function check_done_diem($buoiID,$lmID) {

        global $db;

        $ngay=get_ngay_buoikt($buoiID);

        $query="SELECT COUNT(h.ID_HS) AS dem FROM hocsinh AS h 
        INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' AND m.date_in<='$ngay' 
        WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID') ORDER BY h.cmt ASC";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        $query1="SELECT COUNT(h.ID_HS) AS dem FROM hocsinh AS h 
        INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' AND m.date_in<='$ngay' 
        INNER JOIN diemkt AS d ON d.ID_BUOI='$buoiID' AND d.ID_LM='$lmID' AND d.ID_HS=h.ID_HS 
        WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID') ORDER BY h.cmt ASC";
        $result1=mysqli_query($db,$query1);
        $data1=mysqli_fetch_assoc($result1);

        if($data["dem"]==$data1["dem"]) {
            return true;
        } else {
            return false;
        }
    }

    // Đã check
    // Thông báo all thì ko cần có chỉ số object = lớp như hồi xưa
    function get_thong_bao_all($lmID) {

        global $db;

        $query="SELECT * FROM thongbao WHERE danhmuc='all' AND ID_LM='$lmID' AND status='new' ORDER BY datetime DESC LIMIT 1";

        $result=mysqli_query($db,$query);

        return $result;

    }

    // Đã check
    function ko_lay_bai($oID,$buoiID,$hsID,$lmID) {

        global $db;

        if($oID==0) {
            $query="INSERT INTO options(content,type,note,note2)
                                    value('$hsID','khong-lay-bai','$buoiID','$lmID')";
            mysqli_query($db,$query);
            return mysqli_insert_id($db);
        } else {
            delete_options($oID);
            return 0;
        }
    }

    // Đã check
    function format_diem($diem) {

        $diem=number_format((float)$diem, 2, '.', '');
        if(substr($diem,4,1)=="0") {
            return substr($diem,0,4);
        }

        return $diem;

    }

    // Đã check
    function format_diem2($diem) {

        if(stripos($diem,"/") === false) {

        } else {
            $temp = explode("/",$diem);
            $diem = $temp[0] / $temp[1];
        }

        $diem=number_format((float) $diem, 1, '.', '');

        return $diem;

    }

    function format_price_sms($price) {

        $price=$price/1000;
        if($price>=1000) {
            $first=floor($price/1000);
            $sen=$price-$first*1000;
            if($sen!=0) {
                return $first . "tr" . $sen . "k";
            } else {
                return $first . "tr";
            }
            /*$price="$price";
            if(substr($price,1)==0) {
                return substr($price, 0, 1) . "tr";
            } else {
                return substr($price, 0, 1) . "tr" . substr($price, 1) . "k";
            }*/
        } else {
            return $price."k";
        }
    }

    function get_tien_hoc_hs($hsID, $monID) {

        global $db;

        $query="SELECT * FROM tien_hoc WHERE ID_HS='$hsID' AND ID_MON='$monID' ORDER BY date_dong ASC";

        $result=mysqli_query($db,$query);

        return $result;
    }

    // Đã check
    function valid_maso($maso) {
        if(strlen($maso)==7) {
            $temp = explode("-", $maso);
            if (count($temp) == 2) {
                if (!is_numeric($temp[0]) || !is_numeric($temp[1])) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function count_cau_hoi_hs_tuan($hsID,$monID,$now) {

        global $db;

        $now=date_create($now);
        $dem=0;
        for($i=1;$i<=6;$i++) {
            date_add($now, date_interval_create_from_date_string("-$i days"));
            $date = date_format($now, "Y-m-d");
            $query="SELECT COUNT(ID_STT) AS dem FROM hoc_sinh_cau WHERE ID_HS='$hsID' AND ID_C IN (SELECT ID_C FROM cau_hoi WHERE ngay LIKE '$date %' AND ID_MON='$monID')";
            $result=mysqli_query($db,$query);
            $data=mysqli_fetch_assoc($result);
            $dem+=$data["dem"];
        }

        return $dem;
    }

    function count_cau_hoi_tuan($lopID,$monID,$now) {

        global $db;

        $now=date_create($now);
        $dem=0;
        for($i=1;$i<=6;$i++) {
            date_add($now, date_interval_create_from_date_string("-$i days"));
            $date = date_format($now, "Y-m-d");
            $query="SELECT COUNT(ID_C) AS dem FROM cau_hoi WHERE ngay LIKE '$date %' AND ID_MON='$monID' AND ID_LOP='$lopID'";
            $result=mysqli_query($db,$query);
            $data=mysqli_fetch_assoc($result);
            $dem+=$data["dem"];
        }

        return $dem;
    }

    function get_is_phat($monID) {

        global $db;

        $query="SELECT is_phat FROM mon WHERE ID_MON='$monID'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        if($data["is_phat"] == 1) {
            return true;
        } else {
            return false;
        }
    }

    function get_is_tinh($monID) {

        global $db;

        $query="SELECT is_tinh FROM mon WHERE ID_MON='$monID'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        if($data["is_tinh"] == 1) {
            return true;
        } else {
            return false;
        }
    }

    function get_auto_diem($monID) {

        global $db;

        $query="SELECT is_auto FROM mon WHERE ID_MON='$monID'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["is_auto"];
    }

    function get_nhay_de($monID) {

        global $db;

        $query="SELECT is_nhayde FROM mon WHERE ID_MON='$monID'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["is_nhayde"];
    }

    function get_hoc_sinh_kem($lmID) {

        global $db;

        $result2 = array();
        $now = date("j");
        $last = get_last_time(date("Y"),date("m"));
        $temp = explode("-",$last);
        $last2 = get_last_time($temp[0],$temp[1]);

        $query="SELECT m.ID_HS,h.cmt,h.fullname,h.facebook,h.sdt_bo,h.sdt_me FROM hocsinh AS h 
        INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.date_in<='$last-15' AND m.ID_LM='$lmID' 
        WHERE m.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID') 
        ORDER BY h.cmt ASC";
        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            $diem1 = tinh_diemtb_month2($data["ID_HS"],$lmID);
            if($now < 16) {
                $diem2 = tinh_diemtb_month3($data["ID_HS"], $last, $lmID);
                $diem3 = tinh_diemtb_month3($data["ID_HS"], $last2, $lmID);
            } else {
                $diem2 = tinh_diemtb_month3($data["ID_HS"], date("Y-m"), $lmID);
                $diem3 = tinh_diemtb_month3($data["ID_HS"], $last, $lmID);
            }
            if($diem1 < 5) {
                $result2[] = array(
                    "ID_HS" => $data["ID_HS"],
                    "cmt" => $data["cmt"],
                    "fullname" => $data["fullname"],
                    "diemtb" => $diem1,
                    "facebook" => $data["facebook"],
                    "sdt" => get_hs_ph_sdt($data["sdt_bo"],$data["sdt_me"]),
                    "content" => "Điểm TB 5 bài gần nhất thấp: $diem1"
                );
            } else if($diem2 <= $diem3 - 1 && $diem2 <= 5 && $diem3 <= 5) {
                $result2[] = array(
                    "ID_HS" => $data["ID_HS"],
                    "cmt" => $data["cmt"],
                    "fullname" => $data["fullname"],
                    "diemtb" => 10,
                    "facebook" => $data["facebook"],
                    "sdt" => get_hs_ph_sdt($data["sdt_bo"],$data["sdt_me"]),
                    "content" => "Điểm TB các tháng liên tiếp xụt giảm: $diem3 -> $diem2"
                );
            } else {

            }
        }
        usort($result2,"diemtb_sort_asc");

        return $result2;
    }

    // Đã check
    function get_hs_ph_sdt($sdt_bo,$sdt_me) {
        if($sdt_me != "" && $sdt_me != "X") {
            return $sdt_me;
        } else {
            if($sdt_bo != "" && $sdt_bo !="X") {
                return $sdt_bo;
            } else {
                return "#";
            }
        }
    }

    // Đã check
    function get_all_hs_nghi_buoi($cumID,$ngay,$loai,$lmID,$lmID2) {

        global $db;

        if ($loai == 3) {
            $query = "SELECT d.ID_STT AS stt,h.ID_HS,h.cmt,h.fullname,h.facebook,h.sdt,h.sdt_bo,h.sdt_me,n.ID_STT,n.note,n.hot,d.nhan,d.confirm,d.is_phep,d.anh,d.sdt AS sdtp FROM diemdanh_nghi AS d INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_LM='$lmID2' LEFT JOIN hocsinh_note AS n ON n.ID_HS=d.ID_HS AND n.ngay='$ngay' WHERE d.ID_CUM='$cumID' AND d.ID_LM='$lmID' ORDER BY d.is_phep ASC,d.nhan DESC,d.confirm DESC,h.cmt ASC";
        } else if($loai == 4) {
            $query = "SELECT d.ID_STT AS stt,h.ID_HS,h.cmt,h.fullname,h.facebook,h.sdt,h.sdt_bo,h.sdt_me,n.ID_STT,n.note,n.hot,d.nhan,d.confirm,d.is_phep,d.anh,d.sdt AS sdtp FROM diemdanh_nghi AS d INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_LM='$lmID2' LEFT JOIN hocsinh_note AS n ON n.ID_HS=d.ID_HS AND n.ngay='$ngay' WHERE d.ID_CUM='$cumID' AND d.ID_LM='$lmID' ORDER BY d.nhan DESC,d.is_phep ASC,d.confirm DESC,h.cmt ASC";
        } else if($loai == 5) {
            $query = "SELECT d.ID_STT AS stt,h.ID_HS,h.cmt,h.fullname,h.facebook,h.sdt,h.sdt_bo,h.sdt_me,n.ID_STT,n.note,n.hot,d.nhan,d.confirm,d.is_phep,d.anh,d.sdt AS sdtp FROM diemdanh_nghi AS d INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_LM='$lmID2' LEFT JOIN hocsinh_note AS n ON n.ID_HS=d.ID_HS AND n.ngay='$ngay' WHERE d.ID_CUM='$cumID' AND d.ID_LM='$lmID' ORDER BY d.confirm DESC,d.is_phep ASC,d.nhan DESC,h.cmt ASC";
        } else {
            $query = "SELECT d.ID_STT AS stt,h.ID_HS,h.cmt,h.fullname,h.facebook,h.sdt,h.sdt_bo,h.sdt_me,n.ID_STT,n.note,n.hot,d.nhan,d.confirm,d.is_phep,d.anh,d.sdt AS sdtp FROM diemdanh_nghi AS d INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_LM='$lmID2' LEFT JOIN hocsinh_note AS n ON n.ID_HS=d.ID_HS AND n.ngay='$ngay' WHERE d.ID_CUM='$cumID' AND d.ID_LM='$lmID' AND d.is_phep='$loai' ORDER BY d.nhan DESC,d.confirm DESC,h.cmt ASC";
        }
        $result=mysqli_query($db,$query);

        return $result;
    }

    // Đã check
    function check_is_hot($hsID) {

        global $db;

        $query="SELECT hot FROM hocsinh WHERE ID_HS='$hsID'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        if($data["hot"]==1) {
            return true;
        } else {
            return false;
        }

    }

    // Đã check
    function get_hs_by_van($vantay,$lmID,$monID) {

        global $db;

        $result_arr=array();
        if($lmID!=0) {
            $query = "SELECT h.ID_HS,h.cmt,h.fullname,n.ID_N FROM hocsinh AS h 
        INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
        LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID'
        WHERE h.vantay='$vantay' ORDER BY h.cmt ASC";
        } else {
            $query = "SELECT h.ID_HS,h.cmt,h.fullname,n.ID_N FROM hocsinh AS h 
        INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS
        LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS
        INNER JOIN lop_mon AS l ON l.ID_LM=m.ID_LM AND l.ID_LM=n.ID_LM AND l.ID_MON='$monID'
        WHERE h.vantay='$vantay' ORDER BY h.cmt ASC";
        }

        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            if($data["ID_N"]) {
                continue;
            }
            $result_arr[] = array(
                "ID_HS" => $data["ID_HS"],
                "cmt" => $data["cmt"],
                "fullname" => $data["fullname"]
            );
        }

        return $result_arr;
    }

    // Đã check
    function get_hs_by_sdt($sdt1, $sdt2,$lmID) {

        global $db;

        if($lmID!=0) {
            $query="SELECT h.ID_HS,h.cmt,h.fullname FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' WHERE h.sdt='$sdt1' OR h.sdt_bo='$sdt1' OR h.sdt_me='$sdt1'
            UNION
            SELECT h.ID_HS,h.cmt,h.fullname FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' WHERE h.sdt='$sdt2' OR h.sdt_bo='$sdt2' OR h.sdt_me='$sdt2'";
        } else {
            $query="SELECT ID_HS,cmt,fullname FROM hocsinh WHERE sdt='$sdt1' OR sdt_bo='$sdt1' OR sdt_me='$sdt1'
            UNION
            SELECT ID_HS,cmt,fullname FROM hocsinh WHERE sdt='$sdt2' OR sdt_bo='$sdt2' OR sdt_me='$sdt2'";
        }

        $result=mysqli_query($db,$query);

        return $result;
    }

    // Đã check
    function get_all_diemdanh($ddID) {

        global $db;

        $query="SELECT d.ID_HS,h.cmt,h.vantay,h.fullname FROM diemdanh AS d INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS WHERE d.ID_DD='$ddID' ORDER BY h.cmt ASC";

        $result=mysqli_query($db,$query);

        return $result;
    }

    // Đã check
    function get_all_diemdanh_cum($cumID,$lmID,$monID) {

        global $db;

        $query="SELECT d.ID_HS,h.cmt,h.vantay,h.fullname FROM diemdanh AS d INNER JOIN diemdanh_buoi AS b ON b.ID_CUM='$cumID' AND b.ID_LM='$lmID' AND b.ID_MON='$monID' AND b.ID_STT=d.ID_DD INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS ORDER BY h.cmt ASC";

        $result=mysqli_query($db,$query);

        return $result;
    }

    // Đã check
    function formatFacebook($facebook) {
        if($facebook=="" || $facebook=="X") {
            return "#";
        } else {
			$facebook=str_replace("m.facebook.com","facebook.com",$facebook);
			$facebook=str_replace("mobile.facebook.com","facebook.com",$facebook);
            return $facebook;
        }
    }

    // Đã check
    function get_hocsinh_note($hsID,$limit) {

        global $db;

        if($limit!=null) {
            $query = "SELECT * FROM hocsinh_note WHERE ID_HS='$hsID' ORDER BY ngay DESC LIMIT $limit";
        } else {
            $query = "SELECT * FROM hocsinh_note WHERE ID_HS='$hsID' ORDER BY ngay DESC";
        }

        $result=mysqli_query($db,$query);

        return $result;
    }

    // Đã check
    function add_hs_note_only($hsID,$ngay,$note,$hot) {

        global $db;

        if($note!="" && $note!=" ") {
            $ready=1;
        } else {
            $ready=0;
        }
        $query="INSERT INTO hocsinh_note(ID_HS,ngay,note,ready,hot) SELECT * FROM (SELECT '$hsID' AS id,'$ngay' AS ngay,'$note' AS note,'$ready' AS ready,'$hot' AS hot) AS tmp WHERE NOT EXISTS (SELECT * FROM hocsinh_note WHERE ID_HS='$hsID' AND ngay='$ngay') LIMIT 1";
        mysqli_query($db,$query);
//        $query="SELECT ID_STT FROM hocsinh_note WHERE ID_HS='$hsID' AND ngay='$ngay'";
//        $result=mysqli_query($db,$query);
//        if(mysqli_num_rows($result)!=0) {
//            $query = "INSERT INTO hocsinh_note(ID_HS,ngay,note,ready,hot)
//                                    value('$hsID','$ngay','$note','$ready','$hot')";
//            mysqli_query($db,$query);
//        }
    }

    // Đã check
    function add_hs_note($hsID,$ngay,$note) {

        global $db;

        if($note!="" && $note!=" ") {
            $ready=1;
        } else {
            $ready=0;
        }
        $pre=0;
        $query="SELECT ID_STT FROM hocsinh_note WHERE ID_HS='$hsID' AND ngay='$ngay'";
        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)!=0) {
            $data=mysqli_fetch_assoc($result);
            $pre=$data["ID_STT"];
            $query2="UPDATE hocsinh_note SET note='$note',ready='$ready',hot='1' WHERE ID_HS='$hsID' AND ngay='$ngay'";
        } else {
            $query2="INSERT INTO hocsinh_note(ID_HS,ngay,note,ready,hot)
                                        value('$hsID','$ngay','$note','$ready','1')";
        }

        mysqli_query($db,$query2);
        if($pre!=0) {
            return $pre;
        } else {
            return mysqli_insert_id($db);
        }
    }

    // Đã check
    function update_hs_note($nID,$hot) {

        global $db;

        $query="UPDATE hocsinh_note SET hot='$hot' WHERE ID_STT='$nID'";

        mysqli_query($db,$query);
    }

    // Đã check
    function format_mobile_click($sdt) {

        if($sdt=="cc") {return "";}
        return "<a href=\"tel:$sdt\" onclick=\"_gaq.push(['_trackEvent', 'Contact', 'Call Now Button', 'Phone']);\"  class=\"callnowbutton\">$sdt</a>";
    }

    // Đã check
    function format_google_phone($sdt) {

        $temp=str_replace("-","",$sdt);
        return str_replace("+84","0",$temp);
    }

    // Đã check
    function add_google_contact_url($cmt,$url) {

        global $db;

        $query="INSERT INTO google_contact(cmt,url)
                                    value('$cmt','$url')";
        mysqli_query($db,$query);

    }

    // Đã check
    function edit_google_contact_url($cmt,$url) {

        global $db;

        $query="UPDATE google_contact SET url='$url' WHERE cmt='$cmt'";

        mysqli_query($db,$query);

    }

    // Đã check
    function get_google_contact_url($cmt) {

        global $db;

        $query="SELECT url FROM google_contact WHERE cmt='$cmt'";

        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result) != 0) {
            $data=mysqli_fetch_assoc($result);
            if($data["url"]!="") {
                return $data["url"];
            } else {
                return "none";
            }
        } else {
            return "none";
        }
    }

    // Đã check
    function update_google_contact($name,$sdt) {

        $url = get_google_contact_url($name);
        if($url!="none") {
            $contact = rapidweb\googlecontacts\factories\ContactFactory::getBySelfURL($url);
            $contact->name = $name;
            $contact->phoneNumber = $sdt;

            $result = rapidweb\googlecontacts\factories\ContactFactory::submitUpdates($contact);
            $array_var = json_encode($result);
            $data = json_decode($array_var, true);
            edit_google_contact_url($name, $data["selfURL"]);
        }
    }

    // Đã check
    function upload_google_contact($name,$sdt) {

        $result=rapidweb\googlecontacts\factories\ContactFactory::create($name, $sdt, "");

        $array_var = json_encode($result);
        $data=json_decode($array_var, true);
        add_google_contact_url($name,$data["selfURL"]);
    }

    // Đã check
    function update_nhan_tin_nghi($sttID,$nhan) {

        global $db;

        $query="UPDATE diemdanh_nghi SET nhan='$nhan' WHERE ID_STT='$sttID'";

        mysqli_query($db,$query);
    }

    // Đã check
    function update_xac_nhan_nghi($sttID,$xacnhan) {

        global $db;

        $query="UPDATE diemdanh_nghi SET confirm='$xacnhan' WHERE ID_STT='$sttID'";

        mysqli_query($db,$query);
    }

    // Đã check
    function get_gio_last($hour,$past) {
        $temp=explode("h",$hour);
        if(is_numeric($temp[1])) {
            if($temp[1]-$past<0) {
                $gio=$temp[0]-1;
                $phut=60+($temp[1]-$past);
            } else {
                $gio=$temp[0];
                $phut=$temp[1]-$past;
            }
        } else {
            $gio=$temp[0]-1;
            $phut=45;
        }
        if($phut==0) {$phut="";}
        return $gio."h".$phut;
    }

    // Đã check
    function get_de_id($maso, $hsID, $buoiID, $lmID) {

        global $db;

        $query="SELECT ID_DE FROM de_thi WHERE maso='$maso' AND ID_LM='$lmID' AND nhom IN (SELECT ID_N FROM nhom_de WHERE object='$buoiID') AND loai IN (SELECT ID_D FROM loai_de WHERE name IN (SELECT de FROM hocsinh_mon WHERE ID_HS='$hsID' AND ID_LM='$lmID'))";

        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)!=0) {
            $data = mysqli_fetch_assoc($result);
            return $data["ID_DE"];
        } else {
            return 0;
        }
    }

    // Đã check
    function count_cau_on_de($deID) {

        global $db;

        $query="SELECT COUNT(ID_C) AS dem FROM de_noi_dung WHERE ID_DE='$deID'";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["dem"];
    }

    // Đã check
    function get_cau_hoi_by_de_main($nhom,$lmID) {

        global $db;

        $query="SELECT ID_DE FROM de_thi WHERE main='1' AND nhom='$nhom'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        $query="SELECT c.ID_C,a.ID_DA,e.ID_CD FROM de_noi_dung AS d 
        INNER JOIN cau_hoi AS c ON c.ID_C=d.ID_C
        INNER JOIN chuyen_de_con AS o ON o.ID_CD=c.ID_CD
        INNER JOIN chuyende AS e ON e.maso=o.maso AND e.ID_LM='$lmID'
        INNER JOIN dap_an_ngan AS a ON a.ID_C=d.ID_C AND a.main='1'
        WHERE d.ID_DE='$data[ID_DE]'";

        $result=mysqli_query($db,$query);

        return $result;
    }

    // Đã check
    function get_id_group_hs($hsID) {

        global $db;

        $query="SELECT level,ID_N FROM list_group WHERE ID_HS='$hsID'";

        $result=mysqli_query($db,$query);

        return $result;
    }

    function check_nhom_du_bi($nID) {

        global $db;

        $query="SELECT ID_N FROM game_group WHERE state='dubi' AND ID_N='$nID'";
        $result=mysqli_query($db,$query);

        if(mysqli_num_rows($result)!=0) {
            return true;
        } else {
            return false;
        }
    }

    function add_list_group($hsID, $level, $nID) {

        global $db;

//        tru_tien_hs($hsID,60000,"Trừ tiền gia nhập nhóm tham gia trò chơi","game",$nID);
        $query = "INSERT INTO list_group(ID_HS,level,ID_N) 
                                    VALUES ('$hsID','$level','$nID')";
        mysqli_query($db, $query);
    }

    function delete_game_group($nID) {

        global $db;

        $query="DELETE FROM game_group WHERE ID_N='$nID'";
        mysqli_query($db, $query);

//        $query="SELECT ID_HS FROM list_group WHERE ID_N='$nID'";
//        $result=mysqli_query($db,$query);
//        while($data=mysqli_fetch_assoc($result)) {
//            cong_tien_hs($data["ID_HS"],60000,"Hoàn tiền rời trò chơi","game",$nID);
//        }

        $query="DELETE FROM list_group WHERE ID_N='$nID'";
        mysqli_query($db, $query);
    }

    function delete_hs_list_group($hsID, $nID) {

        global $db;

//        cong_tien_hs($hsID,60000,"Hoàn tiền bị kick rời trò chơi","game",$nID);

        $query="DELETE FROM list_group WHERE ID_HS='$hsID' AND ID_N='$nID'";
        mysqli_query($db, $query);
    }

    function get_game_group($nID) {

        global $db;

        $query="SELECT * FROM game_group WHERE ID_N='$nID'";

        $result=mysqli_query($db, $query);

        return $result;
    }

    function count_so_ve($lmID) {

        global $db;

        if($lmID != 0) {
            $query = "SELECT COUNT(ID_STT) AS dem FROM hocvien_info WHERE ID_N IN (SELECT ID_N FROM game_group WHERE ID_LM='$lmID')";
        } else {
            $query = "SELECT COUNT(ID_STT) AS dem FROM hocvien_info";
        }

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["dem"];
    }

    function add_game_group($name, $pass, $hsID, $lmID, $state) {
        $maso = rand_maso(4);

        global $db;

        $query="INSERT INTO game_group(maso,name,password,datetime,ID_HS,ID_LM,state) 
                                VALUES ('$maso','$name','$pass',now(),'$hsID','$lmID','$state')";
        mysqli_query($db,$query);
        $id=mysqli_insert_id($db);
        if(is_numeric($id) && $id!=0) {
            add_list_group($hsID, 1, $id);
        }
    }

    // Đã check
    function search_hoc_sinh_limit($search, $limit, $lmID) {

        global $db;

        $query = "SELECT h.ID_HS,h.cmt,h.fullname,h.sdt,h.facebook FROM hocsinh AS h 
            INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
            WHERE h.cmt='$search' OR h.namestring LIKE '%" . unicode_convert($search) . "%' ";

        if($limit) {
            $query.="LIMIT $limit";
        }

        $result=mysqli_query($db,$query);

        return $result;
    }

    // Đã check
    function count_game_group($lmID) {

        global $db;

        if($lmID != 0) {
            $query = "SELECT COUNT(ID_N) AS dem FROM game_group WHERE ID_LM='$lmID'";
        } else {
            $query = "SELECT COUNT(ID_N) AS dem FROM game_group";
        }

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["dem"];
    }

    // Đã check
    function count_hs_in_group($lmID) {

        global $db;

        $query="SELECT COUNT(ID_STT) AS dem FROM list_group WHERE ID_N IN (SELECT ID_N FROM game_group WHERE ID_LM='$lmID')";

        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);

        return $data["dem"];
    }

    function get_all_lop_mon_cu($monID){

        global $db;

        $query="SELECT DISTINCT lop,note FROM tien_hoc_cu WHERE ID_MON='$monID' ORDER BY ID_MON ASC,lop ASC";

        $result=mysqli_query($db,$query);

        return $result;
    }

    function addlichtrogiang($buoi,$thu,$id) {

        global $db;

        $query = "INSERT INTO trogiang_lich(ID,buoi,thu) 
                                VALUES ('$id','$buoi','$thu')";
        mysqli_query($db, $query);
    }

    function addlichtrogiangmulti($content) {

        global $db;

        $query = "INSERT INTO trogiang_lich(ID,buoi,thu) VALUES $content";
        mysqli_query($db, $query);
    }

    function addtrogiangnew($name,$pass,$price,$date_in,$loai) {

        global $db;

        $query="INSERT INTO trogiang_info(name,pass,price,date_in,loai)
                                    VALUES('$name','".md5($pass)."','$price','$date_in','$loai')";
        mysqli_query($db, $query);
    }

    function edittrogiang($name, $id) {

        global $db;

        $query="UPDATE trogiang_info SET name='$name' WHERE ID_TG='$id'";
        mysqli_query($db, $query);
    }

    function deletetrogiang($id) {

        global $db;

        $query="DELETE FROM trogiang_info WHERE ID_TG='$id'";
        mysqli_query($db, $query);
    }

    function get_lich_tro_giang () {

        global $db;

        $query = "SELECT * FROM trogiang_lich";
        $result=mysqli_query($db,$query);

        return $result;
    }

    function check_chon_lich($buoi,$thu,$id) {

        global $db;

        $query = "SELECT COUNT(ID_STT) AS dem FROM trogiang_lich WHERE ID='$id' AND buoi='$buoi' AND thu='$thu'";
        $result = mysqli_query($db, $query);
        $data = mysqli_fetch_array($result);
        if ($data["dem"] == 0) {
            return true;
        } else {
            return false;
        }
    }

    function deletelichtrogiang($buoi,$thu,$id) {

        global $db;

        $query = "DELETE FROM trogiang_lich WHERE ID='$id' AND buoi='$buoi' AND thu='$thu'";
        mysqli_query($db,$query);
    }

    function check_diem_danh($ngay,$id) {

        global $db;

        $query = "SELECT COUNT(ID_STT) AS dem FROM trogiang_diemdanh WHERE ID='$id' AND ngay='$ngay' AND trang_thai='-1'";
        $result = mysqli_query($db, $query);
        $data = mysqli_fetch_assoc($result);
        if ($data["dem"] <3) {
            return $data["dem"];
        } else {
            return -1;
        }
    }

    function get_buoi_diem_danh_trogiang($id,$ngay,$check) {

        global $db;

        $thu=date("w", strtotime($ngay))+1;
        $query="SELECT buoi FROM trogiang_lich WHERE ID='$id' AND thu='$thu' ORDER BY FIELD(buoi,S,C,T)";

        $result=mysqli_query($db,$query);

        $dem=0;$buoi="S";
        while($data=mysqli_fetch_assoc($result)) {
            if($dem==$check) {
                $buoi=$data["buoi"];
                break;
            }
            $dem++;
        }

        return $buoi;
    }

    function diemdanhtrogiangcong($ngay,$buoi,$id) {

        global $db;

        $query="INSERT INTO trogiang_diemdanh(ID,ngay,buoi,trang_thai) 
                                        VALUES ('$id','$ngay','$buoi','-1')";
        mysqli_query($db,$query);
    }

    function diemdanhtrogiangnghi($ngay,$id,$bu,$ngay_bu) {

        global $db;

        if($bu == 1 || $ngay_bu != "") {
            $query = "DELETE FROM trogiang_diemdanh WHERE ID='$id' AND ngay='$ngay' AND trang_thai='-1' limit 1";
            mysqli_query($db,$query);
            if($bu==1) {
                $query="INSERT INTO trogiang_diemdanh(ID,ngay,trang_thai) 
                             VALUES ('$id','$ngay','0')";
                mysqli_query($db,$query);
            } else {
                $query="INSERT INTO lich_bu(ID,ngay) 
                                    VALUES ('$id','$ngay_bu')";
                mysqli_query($db,$query);
                $id1=mysqli_insert_id($db);
                $query1="INSERT INTO trogiang_diemdanh(ID,ngay,trang_thai) 
                             VALUES ('$id','$ngay','$id1')";
                mysqli_query($db,$query1);
            }
        }
    }

    function getlistSanpham($idnh) {
        global $db;
        $query = "SELECT * FROM san_pham WHERE ID_NH='$idnh'";
        $result=mysqli_query($db,$query);
        return $result;
    }

    function deleteSanpham($id,$idnh) {
        global $db;
        $query = "DELETE FROM san_pham WHERE ID_SP='$id' AND ID_NH='$idnh'";
        mysqli_query($db,$query);
    }

    function getlistDonhang($idnh) {
        global $db;
        $query = "SELECT d.ID_STT,d.gia_tien,d.datetime,d.so_luong,s.ten,h.fullname,h.cmt FROM don_hang AS d INNER JOIN san_pham AS s ON s.ID_SP=d.ID_SP
                      INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS
                      WHERE d.status='1' AND d.ID_NH='$idnh'";
        $result=mysqli_query($db,$query);
        return $result;
    }

    function addLichsuSanpham($id,$idnh) {
        global $db;
        $query = "UPDATE don_hang SET status='0' WHERE ID_STT='$id' AND ID_NH='$idnh'";
        mysqli_query($db, $query);
    }

    function getlistLichsu($idnh) {
        global $db;
        $query = "SELECT d.ID_STT,h.ID_HS,d.gia_tien,d.datetime,d.so_luong,s.ten,h.fullname,h.cmt FROM don_hang AS d INNER JOIN san_pham AS s ON s.ID_SP=d.ID_SP
                      INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS
                      WHERE d.status='0' AND d.ID_NH='$idnh'";
        $result=mysqli_query($db,$query);
        return $result;
    }

    function deleteDonhang($id,$idnh) {
        global $db;
        $query = "DELETE FROM don_hang WHERE ID_STT='$id' AND status='1' AND ID_NH='$idnh'";
        mysqli_query($db,$query);
    }

    function deleteLichsu($id,$idnh) {
        global $db;
        $query = "DELETE FROM don_hang WHERE ID_STT='$id' AND status='0' AND ID_NH='$idnh'";
        mysqli_query($db,$query);
    }

    function addnewsanpham($name,$gia,$giam,$idnh) {
        global $db;
        $query="INSERT INTO san_pham(ten,anh,gia_tien,giam_gia,ID_NH) 
                                        VALUES ('$name','','$gia','$giam','$idnh')";
        mysqli_query($db,$query);
    }

    function checksanpham($name,$gia,$giam,$idnh) {
        global $db;
        $query = "SELECT COUNT(ID_SP) AS dem FROM san_pham WHERE ten='$name' AND gia_tien='$gia' AND giam_gia='$giam' AND ID_NH='$idnh'";
        $result = mysqli_query($db, $query);
        $data = mysqli_fetch_array($result);
        if ($data["dem"] == 0) {
            return true;
        } else {
            return false;
        }
    }

    function add_game_unlock($nID, $hsID, $level) {
        global $db;
        $query="INSERT INTO game_unlock(ID_N,ID_HS,level,datetime) SELECT * FROM (SELECT '$nID' AS nhom,'$hsID' AS id,'$level' AS level,now() AS now) AS tmp WHERE NOT EXISTS (SELECT ID_STT FROM game_unlock WHERE ID_N='$nID' AND level='$level') LIMIT 1";
        mysqli_query($db, $query);
    }

    function get_hs_from_fb($id) {
        global $db;
        $query="SELECT ID_HS FROM hocsinh WHERE facebook_id='$id' LIMIT 1";
        $result = mysqli_query($db, $query);
        if(mysqli_num_rows($result) != 0) {
            $data = mysqli_fetch_array($result);
            return $data["ID_HS"];
        } else {
            return 0;
        }
    }

    function get_fb_from_hs($hsID) {
        global $db;
        $query="SELECT facebook_id FROM hocsinh WHERE ID_HS='$hsID' LIMIT 1";
        $result = mysqli_query($db, $query);
        $data = mysqli_fetch_array($result);
        return $data["facebook_id"];
    }

    function update_facebook_id($hsID, $fbid) {
        global $db;
        $pattern="/[^0-9]/";
        $fbid=preg_replace($pattern,"",$fbid);
        $query="UPDATE hocsinh SET facebook='https://www.facebook.com/$fbid',facebook_id='$fbid' WHERE ID_HS='$hsID'";
        mysqli_query($db, $query);
        return mysqli_affected_rows($db);
    }

    function validAccountKit($hsID, $sdt, $code) {
        if($sdt) {

        } else {
            $sdt = get_sdt_hocsinh($hsID);
        }
        $ch = curl_init();
        $fb_app_id = '967757889982912';
        $ak_secret = '843619fcf8650a84c72245c1b7eeb90d';
        $token = 'AA|' . $fb_app_id . '|' . $ak_secret;
        $url = 'https://graph.accountkit.com/v1.0/access_token?grant_type=authorization_code&code=' . $code . '&access_token=' . $token;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        $info = json_decode($result, true);
        if(isset($info["access_token"])) {
            $appsecret_proof = hash_hmac('sha256', $info["access_token"], $ak_secret);
            $url = 'https://graph.accountkit.com/v1.0/me/?access_token=' . $info["access_token"] . '&appsecret_proof=' . $appsecret_proof;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            $result = curl_exec($ch);
            curl_close($ch);
            $final = json_decode($result, true);
            if(stripos($sdt,$final["phone"]["national_number"]) === false) {
                return "no";
            } else {
                return "ok";
            }
        } else {
            return "unknow";
        }
    }

    function count_hs_vao($aa,$b,$lmID) {
        global $db;
        $query = "SELECT COUNT(ID_HS) AS dem FROM hocsinh_mon WHERE date_in LIKE '$b-$aa-%' AND ID_LM='$lmID'";
        $result=mysqli_query($db,$query);
        return $result;
    }

    function count_hs_ra($aa,$b,$lmID) {
        global $db;
        $query = "SELECT COUNT(ID_N) AS dem FROM hocsinh_nghi WHERE date LIKE '$b-$aa-%' AND ID_LM ='$lmID'";
        $result=mysqli_query($db,$query);
        return $result;
    }

    function get_da_chi() {
        global $db;
        $query="SELECT SUM(note) AS money FROM options WHERE type='da-chi'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        return $data["money"];
    }

    function showFolderGDrive($service, $fileId, $lop, &$folder_arr, $level = 1, $parentName = null, $parentFolder = null) {
        global $db;
        if($level == 1) {
            $orderBy = "name desc";
            $q = "'$fileId' in parents and name='$lop'";
        } else if($level == 3) {
            $orderBy = "createdTime desc";
            $q = "'$fileId' in parents";
        } else {
            $orderBy = "name desc";
            $q = "'$fileId' in parents";
        }
        $response_con = $service->files->listFiles(array(
            "q" => $q,
            "spaces" => "drive",
            "orderBy" => $orderBy,
            "fields" => "files(id, name, createdTime, mimeType, viewersCanCopyContent, writersCanShare)",
        ));
        foreach ($response_con->files as $file_con) {
            if(stripos($file_con->mimeType, "folder") != false) {
                if($level == 1 && $file_con->name == $lop) {
                    // Folder 1999
                    showFolderGDrive($service, $file_con->id, $lop, $folder_arr, $level+1);
                    break;
                } else if($level == 2 && stripos($file_con->name, "-") != false && stripos($file_con->name, ",") != false) {
                    // Folder 27/2/2017,28/2/2017-Số phức B1
                    $temp = explode("-", $file_con->name);
                    $folderName = $temp[1];
                    $date = explode(",", $temp[0]);
                    if (count($date) == 2) {
                        $datestr = format_date_o($date[0]) . "," . format_date_o($date[1]);
                        showFolderGDrive($service, $file_con->id, $lop, $folder_arr, $level + 1, $datestr, $folderName);
                    }
                }
//                    showFolderGDrive($service, $file_con->id, $lop, $folder_arr, $level+1, $file_con->name);
//                } else if($level == 3 && stripos($file_con->name, "-") != false) {
//                    // Folder ngày
//                    $temp = explode("/",$parentName);
//                    $temp2 = explode("-",$file_con->name);
//                    if(stripos($temp2[0], ",") === false) {
//                        $datestr=$temp[1]."-".format_month_db($temp[0])."-".format_month_db($temp2[0]);
//                    } else {
//                        $temp3=explode(",",$temp2[0]);
//                        $datestr=$temp[1]."-".format_month_db($temp[0])."-".format_month_db($temp3[0]).",".format_month_db($temp3[1]);
//                    }
//                    showFolderGDrive($service, $file_con->id, $lop, $folder_arr, $level+1, $datestr, $temp2[1]);
//                }
            } else if($level == 3) {
                $folder_arr[] = array(
                    "name" => $file_con->name,
                    "id" => $file_con->id,
                    "createdTime" => $file_con->createdTime,
                    "mimeType" => $file_con->mimeType,
                    "parentId" => $fileId,
                    "parentName" => $parentName,
                    "parentFolder" => $parentFolder,
                    "canView" => $file_con->viewersCanCopyContent,
                    "canShare" => $file_con->writersCanShare,
                    "type" => "file"
                );
            }
        }
    }

    function get_video_share_hs($hsID, $dad, $lmID) {
        global $db;
        if($dad == "X") {
            $query="SELECT * FROM google_drive WHERE ID_HS='$hsID' AND ID_LM='$lmID' ORDER BY date_up DESC";
        } else {
            $query="SELECT * FROM google_drive WHERE ID_HS='$hsID' AND dad='$dad' AND ID_LM='$lmID' ORDER BY date_up DESC,dad ASC";
        }
        $result=mysqli_query($db,$query);
        return $result;
    }

    function get_diem_dh($diem,$de) {
        if($de=="G") {
            return $diem;
        } else if($de=="B") {
            return format_diem((40/50)*$diem);
        } else if($de=="Y") {
            return format_diem((32/50)*$diem);
        } else {
            return "";
        }
    }

    function push_chatfuel($hsID, $content) {
        $fbid=get_fb_from_hs($hsID);
        if(isset($fbid) && $fbid!="") {
            $url = "https://api.chatfuel.com/bots/58c44092e4b09504b4941c38/users/$fbid/send?chatfuel_token=vnbqX6cpvXUXFcOKr5RHJ7psSpHDRzO1hXBY8dkvn50ZkZyWML3YdtoCnKH7FSjC&chatfuel_block_id=58d3ca21e4b0b5d3d3466700&noti_content=$content";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $result = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

//            var_dump($result);
            return $error;
        }
    }
	
	function done_note_trogiang($note,$date,$id) {
        global $db;
        $query="UPDATE trogiang_note SET status='0',donetime='$date' WHERE ID_TG='$id' AND ID_STT='$note'";
        mysqli_query($db,$query);
    }

    function add_note_tro_giang($content,$id) {
        global $db;
        $query="INSERT INTO trogiang_note (ID_TG,content,datetime,donetime,status)
                                        VALUES ('$id','$content',now(),'','1')";
        mysqli_query($db, $query);
    }

    function add_thong_bao_tro_giang($id,$content) {
        global $db;
        $query="INSERT INTO thongbao_trogiang (ID_TG,content,danhmuc,datetime,status)
                                            VALUES ('$id','$content','thong-bao',now(),'new')";
        mysqli_query($db, $query);
    }
    function get_thong_bao_trogiang($id) {
        global $db;
        $query = "SELECT * FROM thongbao_trogiang WHERE ID_TG ='$id' AND status ='new' AND danhmuc='thong-bao' ORDER BY datetime DESC";
        $result=mysqli_query($db,$query);
        return $result;
    }

    function count_thong_bao_tro_giang($id) {
        global $db;
        $query="SELECT COUNT(ID_TB) AS dem FROM thongbao_trogiang WHERE ID_TG='$id' AND status='new'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        return $data["dem"];
    }

    function tat_thong_bao_tro_giang($tbID) {

        global $db;

        $query="UPDATE thongbao_trogiang SET status='old' WHERE ID_TB='$tbID'";

        mysqli_query($db,$query);

    }

     function update_info_tro_giang($sdt, $mail, $id) {
         global $db;

         $query="UPDATE trogiang_info SET email='$mail',sdt='$sdt' WHERE ID_TG='$id'";

         mysqli_query($db,$query);
     }

     function delete_hocvien($id) {
         global $db;
         $query = "DELETE FROM hocvien_info WHERE ID_STT='$id'";
         mysqli_query($db,$query);
     }

     function get_info_hocsinh($hsID) {
         global $db;
         $query = "SELECT * FROM hocsinh WHERE ID_HS='$hsID'";
         $result=mysqli_query($db,$query);
         return $result;
     }

    function get_info_tro_giang($id){

        global $db;

        $query="SELECT * FROM trogiang_info WHERE ID_TG=$id";

        $result=mysqli_query($db,$query);

        return $result;
    }

    function get_tro_giang_lich($id) {
        global $db;
        $query="SELECT thu,buoi FROM trogiang_lich WHERE ID='$id' ORDER BY thu ASC";
        $result=mysqli_query($db,$query);
        $a="";
        $dem=1;
        while($data=mysqli_fetch_assoc($result)) {
            $a.="- Thứ ".$data["thu"]." (".$data["buoi"].") ";
            $dem++;
            if($dem==5) {
                $a.="<br/>";
                $dem=1;
            }
        }


    //        $lich_hoc=implode("-", $a);

        return $a;
    }

    function change_mk_trogiang($id,$mk) {

        global $db;

        $query="UPDATE trogiang_info SET pass='$mk' WHERE ID_TG='$id'";

        mysqli_query($db,$query);
    }

    function get_sach_detail($id) {

        global $db;

        $query="SELECT * FROM sach WHERE ID_S='$id'";

        $result=mysqli_query($db,$query);

        return $result;
    }

    function get_order_detail($sttid) {

        global $db;

        $query="SELECT a.name,a.ID_LM,s.* FROM sach_mua AS s 
        INNER JOIN sach AS a ON a.ID_S=s.ID_S
        WHERE s.ID_STT='$sttid'";

        $result=mysqli_query($db,$query);

        return $result;
    }

    function add_mua_sach($hsID, $id, $gia, $sl, $total) {

        global $db;

        $query="INSERT INTO sach_mua(ID_HS,ID_S,tien,sl,total,datetime,status)
                            VALUES('$hsID','$id','$gia','$sl','$total',now(),'0')";

        mysqli_query($db,$query);

        return mysqli_insert_id($db);
    }

    function get_list_sach($lmID, $limit = 30, $type = "newest") {

        global $db;

        if($type == "newest") {
            $query="SELECT * FROM sach WHERE ID_LM='$lmID' ORDER BY ID_S DESC";
        } else if($type == "hotest") {
            $query="SELECT s.*,COUNT(m.ID_STT) AS dem FROM sach AS s 
            INNER JOIN sach_mua AS m ON m.ID_S=s.ID_S
            WHERE s.ID_LM='$lmID' ORDER BY dem DESC";
        } else {
            $query="SELECT * FROM sach WHERE ID_LM='$lmID' ORDER BY rand()";
        }
        if(is_numeric($limit) && $limit > 0) {
            $query .= " LIMIT $limit";
        }

        $result=mysqli_query($db,$query);

        return $result;
    }

    function check_ca_phat_ve($ca) {
        $pattern = "/[234567]-[48]h-[59]h[0-5][0-9]/";
        $datas = preg_match_all($pattern, $ca, $matches);
        $datas = $matches[0];
        if(isset($datas[0])) {
            return $datas[0];
        } else {
            return "none";
        }
    }

    function check_hs_tuyen_fb($fb) {

        global $db;

        $pattern = "/facebook.com\/profile.php\?id=[0-9]+|fb.com\/profile.php\?id=[0-9]+|facebook.com\/[a-zA-Z0-9_.]+|fb.com\/[a-zA-Z0-9_.]+/";
        $datas = preg_match_all($pattern, $fb, $matches);
        $datas = $matches[0];
        if(isset($datas[0])) {
            $fb = $datas[0];
            if ($fb != "") {
                $query = "SELECT ID_STT FROM hocvien_info WHERE facebook LIKE '%$fb%' LIMIT 1";
                $result = mysqli_query($db, $query);
                if (mysqli_num_rows($result) != 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function check_hs_tuyen_sdt($sdt) {

        global $db;

        if(trim($sdt)!="" && strlen($sdt) > 0) {
            $query = "SELECT ID_STT FROM hocvien_info WHERE sdt LIKE '%$sdt%' LIMIT 1";
            $result = mysqli_query($db, $query);
            if (mysqli_num_rows($result) != 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function check_unlock_ca($ngay) {

        global $db;

        $query="SELECT note FROM options WHERE content='$ngay' AND type='buoi-phat-ve'";
        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)!=0) {
            $data=mysqli_fetch_assoc($result);
            return $data["note"];
        } else {
            return "off";
        }
    }

    function get_sl_ca_phat_ve($ngay) {

        global $db;

        $query="SELECT content FROM options WHERE type='sl-buoi-phat-ve' AND note2='$ngay'";

        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result)!=0) {
            $data = mysqli_fetch_assoc($result);

            return $data["content"];
        } else {
            return 200;
        }
    }

    function fill_zero($data, $len) {

        $n=strlen($data);
        if($n > 0 && $n < $len) {
            for($i = 1; $i <= $len-$n; $i++) {
                $data = "0".$data;
            }
        }

        return $data;
    }