<?php
ob_start();
session_start();
ini_set('max_execution_time', 2000);
require_once("model/open_db.php");
require_once("model/model.php");

/*$query="SELECT ID_T,name FROM truong";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="UPDATE truong SET string='".unicode_convert($data["name"])."' WHERE ID_T='$data[ID_T]'";
    mysqli_query($db,$query2);
}*/

/*$query="SELECT * FROM diemtb_thang WHERE ID_LOP='1' AND ID_MON='1' AND datetime='2016-02' ORDER BY ID_HS ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    echo"$data[ID_HS] - $data[diemtb] - $data[detb]<br />";
    update_de_hs($data["ID_HS"],$data["detb"],1);
    $query2="UPDATE diemkt SET de='$data[detb]' WHERE ID_HS='$data[ID_HS]' AND ID_BUOI IN ('32','33','34','35')";
    mysqli_query($db,$query2);
}

$time="2016-03";
/*$query3="SELECT ID_BUOI,ngay FROM buoikt WHERE ngay LIKE '2016-04-%' ORDER BY ID_BUOI ASC";
$result3=mysqli_query($db,$query3);
$buoi=0;
while($data3=mysqli_fetch_assoc($result3)) {
    $temp=explode("-",$data3["ngay"]);
    $time=$temp[0]."-".$temp[1];
    if($buoi!=$time) {
        $buoi=$time;
    } else {
        break;
    }*/
/*$query="SELECT ID_HS,de,ID_MON FROM hocsinh_mon ORDER BY ID_HS ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $diemtb=tinh_diemtb_month($data["ID_HS"],$time,"diemkt");
    echo"$time - $data[ID_HS] - $diemtb<br />";
    if($diemtb!=NULL) {
        //insert_diemtb_thang($data["ID_HS"], $diemtb, $data["de"], 1, $data["ID_MON"],$time);
        update_diemtb_thang($data["ID_HS"], $diemtb, $data["de"], 1, $data["ID_MON"],$time);
    }
}

/*if($temp[1]>=12) {
    $year=$temp[0]+1;
    $month=1;
} else {
    $year=$temp[0];
    $month=$temp[1]+1;
}

if($month<10) {
    $now="$year-0$month";
} else {
    $now="$year-$month";
}*/

/*clean_new_nhayde();
$query="SELECT * FROM hocsinh_mon ORDER BY ID_MON ASC, ID_HS ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $diemtb=get_diemtb_month($data["ID_HS"], $data["ID_MON"], $time);
    if($diemtb>=8) {
        $new_de="G";
    } else if($diemtb<5){
        $new_de="B";
    } else {
        $new_de=$data["de"];
    }
    if($data["de"]!=$new_de) {
        update_de_hs($data["ID_HS"], $new_de, $data["ID_MON"]);
        //insert_new_nhayde($data["ID_HS"], $new_de, $diemtb, $data["ID_MON"]);
        if($new_de=="G") {
            echo"$time - $data[ID_HS] - $diemtb - $new_de (lên đề)<br />";
            //add_thong_bao_hs2($data["ID_HS"],1,"Chúc mừng bạn đã chuyến sang đề G từ ngày 1/$month","nhay-de",$data["ID_MON"]);
        } else {
            echo"$time - $data[ID_HS] - $diemtb - $new_de (xuống đề)<br />";
            //add_thong_bao_hs2($data["ID_HS"],0,"Rất tiếc bạn phải chuyển xuống làm đề B từ ngày 1/$month","nhay-de",$data["ID_MON"]);
        }
    }
    $query2="UPDATE diemkt SET de='$new_de' WHERE ID_HS='$data[ID_HS]' AND ID_BUOI IN (SELECT ID_BUOI FROM buoikt WHERE ngay LIKE '$time-%')";
    //mysqli_query($db,$query2);
}
//}

/*$query="SELECT ID_HS FROM hocsinh_mon ORDER BY ID_HS ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
//echo $data["ID_HS"]."<br />";
}*/

/*$query="UPDATE hocsinh SET avata='male.jpg' WHERE gender='1'";
mysqli_query($db,$query);*/

/*$muc_tien=array();
$result1=get_all_muctien();
while($data1=mysqli_fetch_assoc($result1)) {
    $muc_tien[$data1["string"]]=$data1["tien"];
}*/

/*$query0="SELECT * FROM buoikt ORDER BY ID_BUOI ASC";
$result0=mysqli_query($db,$query0);
while($data0=mysqli_fetch_assoc($result0)) {
    $buoiID=$data0["ID_BUOI"];
    $today_date=date_create($data0["ngay"]);

    $query="SELECT ID_MON,name,diem,cd_diem FROM mon WHERE ID_MON='1' ORDER BY ID_MON ASC";
    $result=mysqli_query($db,$query);
    while($data=mysqli_fetch_assoc($result)) {
        $query2="SELECT $data[diem].*,hocsinh_mon.date_in FROM $data[diem] INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=$data[diem].ID_HS AND hocsinh_mon.ID_MON='$data[ID_MON]' WHERE $data[diem].ID_BUOI='$buoiID' AND $data[diem].diem!='X' AND $data[diem].loai IN ('0','1','3','5')";
        $result2=mysqli_query($db,$query2);
        while($data2=mysqli_fetch_assoc($result2)) {
            $date=date_create($data2["date_in"]);
            if($today_date<$date) {
                //echo"$data[diem] - $data2[ID_DIEM] - $data2[diem] - $data2[loai].<br />";
                //delete_diem($data2["ID_DIEM"],$buoiID,$data2["ID_HS"],$data["diem"],$data["cd_diem"]);
            } else {
                if(!check_binh_voi($data2["ID_HS"],$buoiID,$data["diem"])) {
                    $temp=get_phat_diemkt($data2["ID_HS"],$data2["diem"],$data2["de"],$data2["loai"],$data2["note"],$data["ID_MON"],$data["name"],$buoiID,$data0["ngay"],true);
                }
            }
        }
    }
}*/

/*$cu = "kiet";
$me = encode_data($cu,$code);
echo $me;
echo"<br />";
echo decode_data($me,$code);*/

//echo $_SERVER['REQUEST_URI'];

/*$query="SELECT ID_BUOI FROM buoikt ORDER BY ID_BUOI ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    insert_diem_hs2(250,$data["ID_BUOI"],"X","B",4,0,"diemkt");
}*/

/*$query="SELECT ID_HS,de FROM hocsinh_mon ORDER BY ID_HS ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="UPDATE diemkt SET de='$data[de]' WHERE ID_HS='$data[ID_HS]' AND ID_BUOI='44'";
    mysqli_query($db,$query2);
}*/

/*$query="SELECT ID_HS,cmt FROM hocsinh ORDER BY ID_HS ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="UPDATE hocsinh SET password='".md5($data["cmt"])."' WHERE ID_HS='$data[ID_HS]'";
    mysqli_query($db,$query2);
}*/

/*$query="SELECT * FROM chuyende_diem ORDER BY ID_STT ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $temp=explode("/",$data["diem"]);
    if($temp[0]>$temp[1]) {
        echo"$data[ID_STT] - $data[ID_BUOI] - $data[ID_CD] - $data[ID_HS] - $data[diem]<br />";
    }
}*/

/*$query="SELECT ID_HS,birth FROM hocsinh ORDER BY ID_HS ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $temp=explode("-",$data["birth"]);
    $pre=substr($temp[0],1,2);
    $cmt=99*10000+$data["ID_HS"];
    $cmt=substr($cmt,0,2)."-".substr($cmt,2,4);
    echo $cmt."<br />";
    $query2="UPDATE hocsinh SET cmt='$cmt',password='".md5($cmt)."' WHERE ID_HS='$data[ID_HS]'";
    mysqli_query($db,$query2);
}*/

//$query="SELECT h.ID_HS,m.de,n.date,s.diemtb FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='1' INNER JOIN (SELECT AVG(d.diem) AS diemtb FROM buoikt AS b INNER JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.diem!='X' WHERE d.ID_HS=h.ID_HS AND b.ngay LIKE '2016-06-%' ORDER BY b.ID_BUOI DESC) s LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_MON='1' WHERE h.lop='1' ORDER BY s.diemtb ASC";
/*$rankB=$rankG=0;$diem_temp=-1;
$query="SELECT h.ID_HS,m.de AS detb,n.date,(SELECT AVG(d.diem) AS diem FROM buoikt AS b INNER JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.diem!='X' WHERE b.ngay LIKE '2016-06-%' AND d.ID_HS=h.ID_HS ORDER BY b.ID_BUOI DESC) AS diemtb FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='1' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_MON='1' WHERE h.lop='1' ORDER BY diemtb DESC,h.cmt ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    if(isset($data["date"])) {
    } else {
        if($data["diemtb"]!=$diem_temp) {
            if($data["detb"]=="B") {
                $rankB++;
            } else {
                $rankG++;
            }
        }
        echo"$rankG - $data[ID_HS] - $data[diemtb] - ".tinh_diemtb_month($data["ID_HS"],"2016-06","diemkt")."<br />";
        $diem_temp=$data["diemtb"];
    }
}*/

/*$now=date_create("2016-06-22");

date_add($now,date_interval_create_from_date_string("-1 days"));
$back_date=date_format($now,"Y-m-d");
echo $back_date;
$cumID=get_cum_buoi(0, $back_date, 1, 1, 2);
echo $cumID."<br />";
if($cumID==0) {
    $cumID=get_new_cum_buoi(1, 1);
}
echo $cumID;*/

//echo get_last_CN();

/*$query="SELECT * FROM diemkt WHERE ID_BUOI='43' ORDER BY ID_DIEM ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="UPDATE diemkt SET de='$data[de]' WHERE ID_BUOI='44' AND ID_HS='$data[ID_HS]'";
    mysqli_query($db,$query2);
}*/

/*$buoiID=47;
$hsID=3;
$diem=0;
$loai=3;
$note=10;
$de="B";
$diem_string="diemkt";
$query="INSERT INTO $diem_string(ID_BUOI,ID_HS,diem,loai,de,note) SELECT * FROM (SELECT '$buoiID' AS buoi,'$hsID' AS id,'$diem' AS diem,'$loai' AS loai,'$de' AS de,'$note' AS note) AS tmp WHERE NOT EXISTS (SELECT ID_BUOI,ID_HS FROM diemkt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID') LIMIT 1";
mysqli_query($db,$query);
echo mysqli_error($db)."\n";
echo mysqli_affected_rows($db);*/

/*$dem=0;
$query="SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE date LIKE '2016-03-%'  AND ID_LOP='1' AND ID_MON='1'";
$result=mysqli_query($db,$query);
echo mysqli_num_rows($result)."<br />";
while($data=mysqli_fetch_assoc($result)) {
    $query2="SELECT d.ID_STT FROM diemdanh_toan AS d INNER JOIN diemdanh_buoi AS b ON b.ID_STT=d.ID_DD AND b.ID_CUM='$data[ID_CUM]' AND b.ID_LOP='1' AND b.ID_MON='1' WHERE d.ID_HS='2'";
    $result2=mysqli_query($db,$query2);
    if(mysqli_num_rows($result2)!=0) {
        $dem++;
    }
}
echo $dem;*/

/*$query="SELECT h.ID_HS,h.cmt FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='1' WHERE h.lop='2' ORDER BY h.cmt ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    echo"$data[ID_HS] - $data[cmt]<br />";
}*/

/*$query0="SELECT ID_BUOI FROM buoikt WHERE ID_BUOI='48' ORDER BY ID_BUOI ASC";
$result0=mysqli_query($db,$query0);
while($data0=mysqli_fetch_assoc($result0)) {
    $query1="SELECT ID_HS FROM hocsinh ORDER BY ID_HS ASC";
    $result1=mysqli_query($db,$query1);
    while($data1=mysqli_fetch_assoc($result1)) {
        $diem=0;
        $query="SELECT ID_STT,diem FROM chuyende_diem WHERE ID_BUOI='$data0[ID_BUOI]' AND ID_HS='$data1[ID_HS]' ORDER BY ID_STT DESC";
        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            $temp=explode("/",$data["diem"]);
            if($temp[0]>$temp[1]) {
                echo $data["ID_STT"]."<br />";
            }
            $diem+=$temp[0];
        }
        $query2="SELECT ID_DIEM,diem FROM diemkt WHERE ID_BUOI='$data0[ID_BUOI]' AND ID_HS='$data1[ID_HS]'";
        $result2=mysqli_query($db,$query2);
        $data2=mysqli_fetch_assoc($result2);
        if($data2["diem"]!=$diem) {
            echo"$data2[ID_DIEM] - $data2[diem] - $diem<br />";
        }
    }
}*/

/*$lopID=1;
$monID=1;
$now="2016-04";
$hsID=200;
$diemdanh_string="diemdanh_toan";
$dem=0;
$query="SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE date LIKE '$now-%'  AND ID_LOP='$lopID' AND ID_MON='$monID'";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="SELECT d.ID_STT,b.ID_STT AS ID_DD FROM $diemdanh_string AS d INNER JOIN diemdanh_buoi AS b ON b.ID_STT=d.ID_DD AND b.ID_CUM='$data[ID_CUM]' AND b.ID_LOP='$lopID' AND b.ID_MON='$monID' WHERE d.ID_HS='$hsID'";
    $result2=mysqli_query($db,$query2);
    if(mysqli_num_rows($result2)!=0) {
        $data2=mysqli_fetch_assoc($result2);
        echo"$data2[ID_STT] - $data2[ID_DD]<br />";
        $dem++;
    }
}*/

/*$buoiID=42;
$de="B";
$query="SELECT ID_HS FROM diemkt WHERE ID_BUOI='$buoiID' AND de='$de'";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="UPDATE chuyende_diem SET ID_CD='' WHERE ID_BUOI='$buoiID' AND ID_CD='' AND ID_HS=$data[ID_HS] AND cau=''";
    mysqli_query($db,$query2);
}*/

/*$query="SELECT t.ID_RA,t.note,b.ngay FROM tien_ra AS t INNER JOIN buoikt AS b ON b.ID_BUOI=t.object WHERE t.string LIKE 'kiemtra_%' ORDER BY t.ID_RA ASC,b.ID_BUOI ASC";
$result=mysqli_query($db,$query);
$dem=0;
while($data=mysqli_fetch_assoc($result)) {
    if(stripos($data["note"],format_dateup($data["ngay"]))===FALSE) {
            //echo"$data[ID_VAO] - $data[note] - ".format_dateup($data["ngay"])."<br />";
    } else {
        echo"$data[ID_RA] - $data[note] - ".format_dateup($data["ngay"])." true<br />";
        $now=date_create($data["ngay"]);
        date_add($now,date_interval_create_from_date_string("+5 days"));
        $date=date_format($now,"Y-m-d");
        $query2="UPDATE tien_ra SET date='$date' WHERE ID_RA='$data[ID_RA]'";
        mysqli_query($db,$query2);
        $dem++;
    }
}
echo $dem;*/

/*$query0="SELECT ID_HS FROM hocsinh_mon WHERE ID_MON='1' ORDER BY ID_HS ASC";
$result0=mysqli_query($db,$query0);
while($data0=mysqli_fetch_assoc($result0)) {
    $price=0;
    $query="SELECT price FROM tien_ra WHERE ID_HS='$data0[ID_HS]' AND string LIKE 'kiemtra_%' AND date<'2016-03-01' ORDER BY ID_RA ASC";
    $result=mysqli_query($db,$query);
    while($data=mysqli_fetch_assoc($result)) {
        $price+=$data["price"];
    }
    if($price>0) {
        echo"$data0[ID_HS] - ".format_price($price)."<br />";
        //tru_tien_hs($data0["ID_HS"],$price,"Thầy Dương đã thanh toán tiền thưởng đến hết tháng 02/2016: ".format_price($price),"rut-tien",0);
    }
}*/

/*$query="SELECT * FROM tien_ra WHERE string='nap-tien' AND object='1'";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="UPDATE tien_ra SET note='Quá khứ bạn đã đóng ".format_price($data["price"])."',object='134',date_dong='$data[date]' WHERE ID_RA='$data[ID_RA]'";
    mysqli_query($db,$query2);
}*/

/*$query="SELECT ID_HS,sdt,sdt_bo,sdt_me FROM hocsinh ORDER BY ID_HS ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="UPDATE hocsinh SET sdt='".format_phone($data["sdt"])."',sdt_bo='".format_phone($data["sdt_bo"])."',sdt_me='".format_phone($data["sdt_me"])."' WHERE ID_HS='$data[ID_HS]'";
    mysqli_query($db,$query2);
}*/

/*$query="SELECT * FROM diemdanh_nghi ORDER BY ID_STT ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query1="SELECT ID_CUM FROM diemdanh_buoi WHERE ID_STT='$data[ID_CUM]'";
    $result1=mysqli_query($db,$query1);
    $data1=mysqli_fetch_assoc($result1);
    $query2="UPDATE diemdanh_nghi SET ID_CUM='$data1[ID_CUM]' WHERE ID_STT='$data[ID_STT]'";
    mysqli_query($db,$query2);
}*/

/*$query="SELECT * FROM tien_vao WHERE date='2016-07-31' OR date='2016-08-01'";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $taikhoan=get_tien_hs($data["ID_HS"]);
    update_tien_hs($data["ID_HS"],$taikhoan+$data["price"]);
    $query1="DELETE FROM tien_vao WHERE ID_VAO='$data[ID_VAO]'";
    mysqli_query($db,$query1);
}*/

/*$query="SELECT ID_STT,ID_CUM FROM diemdanh_buoi WHERE ID_LOP='1' AND ID_MON='1'";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="UPDATE diemdanh_nghi SET ID_CUM='".($data["ID_CUM"]+20)."' WHERE ID_CUM='$data[ID_CUM]'";
    mysqli_query($db,$query2);
    $query2="UPDATE diemdanh_buoi SET ID_CUM='".($data["ID_CUM"]+20)."' WHERE ID_STT='$data[ID_STT]'";
    mysqli_query($db,$query2);
}*/

/*$query="SELECT ID_STT,ID_CUM FROM diemdanh_buoi WHERE ID_CUM>='56' AND ID_CUM<='58' AND ID_LOP='1' AND ID_MON='1'";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="UPDATE diemdanh_nghi SET ID_CUM='".($data["ID_CUM"]-2)."' WHERE ID_CUM='$data[ID_CUM]'";
    mysqli_query($db,$query2);
    $query2="UPDATE diemdanh_buoi SET ID_CUM='".($data["ID_CUM"]-2)."' WHERE ID_STT='$data[ID_STT]'";
    mysqli_query($db,$query2);
}*/

/*$cumID=$dem=0;
$query="SELECT ID_STT,ID_CUM FROM diemdanh_buoi WHERE date LIKE '2016-07-%' AND date>='2016-07-04' AND (ID_LOP='2' OR ID_LOP='3') AND ID_MON='1' ORDER BY ID_LOP ASC,ID_CUM ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    if($data["ID_CUM"]!=$cumID) {
        $dem++;
    }
    $cumID=$data["ID_CUM"];
}
echo $dem;

$query="SELECT d.ID_STT FROM diemdanh_toan AS d INNER JOIN diemdanh_buoi AS b ON b.ID_STT=d.ID_DD AND b.date LIKE '2016-07-%' AND b.date>='2016-07-04' AND (b.ID_LOP='2' OR b.ID_LOP='3') AND b.ID_MON='1' WHERE d.ID_HS='565'";
$result=mysqli_query($db,$query);
echo mysqli_num_rows($result);*/

/*$query="SELECT m.ID_STT,h.ID_HS,h.cmt,m.date_in FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='1' WHERE h.lop='1' ORDER BY h.cmt ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="UPDATE hocsinh_mon SET date_in='2016-06-01' WHERE ID_STT='$data[ID_STT]' AND ID_HS='$data[ID_HS]' AND ID_MON='1' AND date_in='2016-07-04'";
    mysqli_query($db,$query2);
    echo"$data[cmt] - ".format_dateup($data["date_in"])."<br />";
}*/

/*$query="SELECT * FROM hocsinh_temp ORDER BY ID_HS ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="UPDATE hocsinh_mon SET de='$data[de]' WHERE ID_HS='$data[ID_HS]' AND ID_MON='$data[ID_MON]'";
    mysqli_query($db,$query2);
}*/

/*$query="SELECT ID_HS,taikhoan FROM hocsinh WHERE lop='2' ORDER BY ID_HS ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="SELECT price FROM tien_vao WHERE ID_HS='$data[ID_HS]' AND object='50'";
    $result2=mysqli_query($db,$query2);
    if(mysqli_num_rows($result2)==2) {
        $data2=mysqli_fetch_assoc($result2);
        $tien=$data["taikhoan"]+$data2["price"];
        $query3="UPDATE hocsinh SET taikhoan='$tien' WHERE ID_HS='$data[ID_HS]'";
        mysqli_query($db,$query3);
        $query4="DELETE FROM tien_vao WHERE ID_HS='$data[ID_HS]' AND object='50' LIMIT 1";
        mysqli_query($db,$query4);
    }
}*/
/*$hsID=249;
$now="2016-06";
$result=get_hs_short_detail($hsID,1);
$data=mysqli_fetch_assoc($result);
echo check_chua_dong_hoc($hsID,$data["date_in"],1,1,$now,"cahoc","ca_codinh","diemdanh_toan",true);*/

/*$monID=$lopID=1;
$hsID=451;
$diemdanh_string="diemdanh_toan";
$date_in="2016-06-01";
if($date_in=="2016-06-01" && $lopID==1 && $monID==1) {
    $date_in="2016-07-04";
} else {
    $date_in=$data0["date_in"];
}
$newID=get_new_cum_buoi($lopID,$monID)-1;
$query8="SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE ID_CUM!='$newID' AND date>='$date_in' AND ID_LOP='$lopID' AND ID_MON='$monID'";
$result8=mysqli_query($db,$query8);
$tong_hoc=mysqli_num_rows($result8);
$query8="SELECT DISTINCT b.ID_CUM FROM diemdanh_buoi AS b INNER JOIN $diemdanh_string AS dd ON dd.ID_DD=b.ID_STT AND dd.ID_HS='$hsID' WHERE b.ID_CUM!='$newID' AND b.date>='$date_in' AND b.ID_LOP='$lopID' AND b.ID_MON='$monID'";
$result8=mysqli_query($db,$query8);
$di_hoc=mysqli_num_rows($result8);
$query8="SELECT DISTINCT b.ID_CUM FROM diemdanh_buoi AS b INNER JOIN diemdanh_nghi AS d ON d.ID_CUM=b.ID_CUM AND d.ID_HS='$hsID' AND d.is_phep='1' WHERE b.ID_CUM!='$newID'";
$result8=mysqli_query($db,$query8);
$di_hoc+=mysqli_num_rows($result8);
echo"$tong_hoc - $di_hoc";*/

/*$query="SELECT * FROM tien_vao WHERE note='Chuyển cố định sang ca quá tải' OR note='Chuyển tạm sang ca quá tải'";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    echo"$data[ID_HS] - $data[price] - $data[date]<br />";
    $tien=get_tien_hs($data["ID_HS"]);
    $new=$tien+$data["price"];
    $query2="UPDATE hocsinh SET taikhoan='$new' WHERE ID_HS='$data[ID_HS]'";
    mysqli_query($db,$query2);
    $query3="DELETE FROM tien_vao WHERE ID_VAO='$data[ID_VAO]'";
    mysqli_query($db,$query3);
}

$query="SELECT * FROM tien_ra WHERE note='Chuyển cố định sang ca vắng' OR note='Chuyển tạm sang ca vắng'";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    echo"$data[ID_HS] - $data[price] - $data[date]<br />";
    $tien=get_tien_hs($data["ID_HS"]);
    $new=$tien-$data["price"];
    $query2="UPDATE hocsinh SET taikhoan='$new' WHERE ID_HS='$data[ID_HS]'";
    mysqli_query($db,$query2);
    $query3="DELETE FROM tien_ra WHERE ID_RA='$data[ID_RA]'";
    mysqli_query($db,$query3);
}*/

/*$query="SELECT ID_HS,cmt FROM hocsinh WHERE cmt>='99-0358' AND cmt<='99-0368' ORDER BY cmt ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    echo"$data[ID_HS] - $data[cmt]<br />";
    $query2="UPDATE hocsinh_mon SET date_in='2016-07-04' WHERE ID_HS='$data[ID_HS]'";
    mysqli_query($db,$query2);
}*/

/*$query="SELECT * FROM thongbao WHERE content LIKE '%. huy' AND danhmuc='diem-thi'";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $ngay=substr($data["content"],18,10);
    $buoiID=get_id_buoikt(format_date_o($ngay));
    $query0="UPDATE thongbao SET object='$buoiID' WHERE ID_TB='$data[ID_TB]'";
    //mysqli_query($db,$query0);
    $temp=get_diem_hs3($data["ID_HS"],$buoiID,"diemkt");
    if($temp[1]!=3) {
        delete_thongbao2($data["ID_TB"]);
    }
    $result2=get_lido($temp[2]);
    $data2=mysqli_fetch_assoc($result2);
    $string=str_replace("huy",$data2["name"],$data["content"]);
    $query3="UPDATE thongbao SET content='$string' WHERE ID_TB='$data[ID_TB]'";
    //mysqli_query($db,$query3);
    echo"$data[ID_TB] - $data[object] - $data[ID_HS] - $data[content]<br />";
}*/

/*$hs_arr="'99-0414','99-0413','99-0347','99-0104','99-0412','99-0155','99-0422','99-0415','99-0066','99-0142','99-0069'";
$query="SELECT h.ID_HS FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='3' WHERE h.cmt IN ($hs_arr) ORDER BY h.ID_HS ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="UPDATE chuyende_diem_ly SET diem='X/0.3' WHERE ID_BUOI='53' AND ID_HS='$data[ID_HS]' AND cau>='21' AND cau<='30'";
    mysqli_query($db,$query2);
}*/

/*$query="SELECT ID_BUOI,ID_HS,loai,note FROM diemkt WHERE loai IN ('2','3','4','5')";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="SELECT diem FROM chuyende_diem WHERE ID_BUOI='$data[ID_BUOI]' AND ID_HS='$data[ID_HS]'";
    $result2=mysqli_query($db,$query2);
    while($data2=mysqli_fetch_assoc($result2)) {
        echo"$data[ID_BUOI] - $data[ID_HS] - $data2[diem]<br />";
    }
    $query2="DELETE FROM chuyende_diem WHERE ID_BUOI='$data[ID_BUOI]' AND ID_HS='$data[ID_HS]'";
    mysqli_query($db,$query2);
}*/

/*$query="SELECT ID_CUM,ID_LOP,ID_MON FROM diemdanh_buoi ORDER BY ID_MON ASC,ID_LOP ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="SELECT COUNT(ID_STT) AS dem FROM diemdanh_nghi WHERE ID_CUM='$data[ID_CUM]' AND is_phep='0' AND ID_LOP='$data[ID_LOP]' AND ID_MON='$data[ID_MON]'";
    $result2=mysqli_query($db,$query2);
    $data2=mysqli_fetch_assoc($result2);
    echo"$data[ID_CUM] - $data[ID_LOP] - $data[ID_MON] - $data2[dem]<br />";
    if($data2["dem"]!=0) {
        add_options($data["ID_CUM"],"diemdanh-nghi",$data["ID_LOP"],$data["ID_MON"]);
    }
}*/

/*$query="SELECT * FROM diemkt_anh";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $diem=format_diem($data["diem"]);
    $query2="UPDATE diemkt_anh SET diem='$diem' WHERE ID_DIEM='$data[ID_DIEM]'";
    mysqli_query($db,$query2);
}*/

/*$query="SELECT * FROM cagio";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    if($data["buoi"]=="S") {
        $buoi="1";
    } else if($data["buoi"]=="C") {
        $buoi="2";
    } else {
        $buoi="3";
    }
    $buoi=$buoi.$data["buoi"];
    $query2="UPDATE cagio SET buoi='$buoi' WHERE ID_GIO='$data[ID_GIO]'";
    mysqli_query($db,$query2);
}*/

/*$cumID=1;
$query="SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE ID_LOP='1' AND ID_MON='2' ORDER BY date ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="UPDATE diemdanh_buoi SET ID_CUM='$cumID' WHERE ID_CUM='$data[ID_CUM]' AND ID_LOP='1' AND ID_MON='2'";
    mysqli_query($db,$query2);
    $query3="UPDATE diemdanh_nghi SET ID_CUM='$cumID' WHERE ID_CUM='$data[ID_CUM]' AND ID_LOP='1' AND ID_MON='2'";
    mysqli_query($db,$query3);
    $cumID++;
}*/

/*$query="SELECT m.ID_HS,m.level,h.cmt FROM hocsinh_mon AS m INNER JOIN hocsinh AS h ON h.ID_HS=m.ID_HS WHERE m.ID_MON='1' ORDER BY h.cmt ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $x=count_thachdau_win($data["ID_HS"],1);
    $delta=1+8*$x;
    $lvl=(-1+sqrt($delta))/2;
    $lvl=floor($lvl);
    if($lvl!=$data["level"]) {
        echo"$data[cmt] - $data[level] - $lvl<br />";
        //add_log($data["ID_HS"],"Bạn được tặng 1 thẻ miễn phạt do đạt level $lvl","len-level-1");
        //add_thong_bao_hs($data["ID_HS"],$lvl,"Chúc mừng bạn đã đạt level $lvl và được tặng 1 thẻ miễn phạt","len-level",1);
        //$query2="UPDATE hocsinh_mon SET level='$lvl' WHERE ID_HS='$data[ID_HS]' AND ID_MON='1'";
        //mysqli_query($db,$query2);
    }
}*/

/*$query="SELECT ID_HS,cmt,taikhoan FROM hocsinh ORDER BY cmt ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="SELECT SUM(price) AS money FROM tien_vao WHERE ID_HS='$data[ID_HS]'";
    $result2=mysqli_query($db,$query2);
    $data2=mysqli_fetch_assoc($result2);
    $phat=$data2["money"];
    $query2="SELECT SUM(price) AS money FROM tien_ra WHERE ID_HS='$data[ID_HS]'";
    $result2=mysqli_query($db,$query2);
    $data2=mysqli_fetch_assoc($result2);
    $thuong=$data2["money"];
    if(abs($data["taikhoan"]) != abs($thuong-$phat)) {
        echo"$data[ID_HS] - $data[cmt] - $data[taikhoan] - ($thuong - $phat)<br />";
        update_tien_hs($data["ID_HS"],$thuong-$phat);
    }
}*/

/*clean_new_nhayde();

$query="SELECT h.ID_HS,h.cmt,m.de FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='1'";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="SELECT detb,diemtb FROM diemtb_thang WHERE ID_HS='$data[ID_HS]' AND ID_MON='1' AND datetime='2016-08'";
    $result2=mysqli_query($db,$query2);
    $data2=mysqli_fetch_assoc($result2);
    if($data2["detb"] !=  $data["de"] && isset($data2["detb"])) {
        echo"$data[ID_HS] - $data[cmt] - $data[de] - $data2[detb]<br />";
        insert_new_nhayde($data["ID_HS"], $data["de"], $data2["diemtb"], 1);
    }
}*/

/*$query="SELECT DISTINCT facebook FROM hocsinh ORDER BY cmt ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="SELECT ID_HS,cmt,facebook FROM hocsinh WHERE facebook='$data[facebook]' ORDER BY cmt ASC";
    $result2=mysqli_query($db,$query2);
    if(mysqli_num_rows($result2) > 1) {
        while($data2=mysqli_fetch_assoc($result2)) {
            echo "$data2[ID_HS] - $data2[cmt] - $data2[facebook]<br />";
        }
    }
}*/

/*$query="SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE date>='$date_in' AND ID_LOP='$lopID' AND ID_MON='$monID'";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    echo"<br />$data[ID_CUM] - ";
    $query2="SELECT DISTINCT b.ID_CUM FROM diemdanh_buoi AS b INNER JOIN $diemdanh_string AS dd ON dd.ID_DD=b.ID_STT AND dd.ID_HS='$hsID' WHERE b.ID_CUM='$data[ID_CUM]' AND b.date>='$date_in' AND b.ID_LOP='$lopID' AND b.ID_MON='$monID'";
    $result2=mysqli_query($db,$query2);
    while($data2=mysqli_fetch_assoc($result2)) {
        echo"$data2[ID_CUM]";
    }
}

$query="SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE date>='$date_in' AND ID_LOP='1' AND ID_MON='$monID'";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    echo"<br />$data[ID_CUM] - ";
    $query2="SELECT DISTINCT b.ID_CUM FROM diemdanh_buoi AS b INNER JOIN $diemdanh_string AS dd ON dd.ID_DD=b.ID_STT AND dd.ID_HS='$hsID' WHERE b.ID_CUM='$data[ID_CUM]' AND b.date>='$date_in' AND b.ID_LOP='1' AND b.ID_MON='$monID'";
    $result2=mysqli_query($db,$query2);
    while($data2=mysqli_fetch_assoc($result2)) {
        echo"$data2[ID_CUM]";
    }
}

echo"<br />";
$query="SELECT DISTINCT b.ID_CUM FROM diemdanh_buoi AS b INNER JOIN $diemdanh_string AS d ON d.ID_DD=b.ID_STT INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS AND h.lop='$lopID' WHERE b.date>='$date_in' AND b.ID_LOP!='$lopID' AND b.ID_LOP!='3' AND b.ID_MON='$monID'";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    echo"<br />$data[ID_CUM]";
}*/

/*$monID=1;
$lopID=1;
$date_in="2015-07-11";
$diemdanh_string="diemdanh_toan";
$hsID=27;
$now="2016-03";
$newID = 0;

//$di_hoc =  count_hs_di_hoc($hsID,$date_in,$now,$lopID,$monID,$diemdanh_string,$newID);

$di_hoc=0;
$query="SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE ID_CUM!='$newID' AND date>='$date_in' AND date LIKE '$now-%' AND ID_LOP='$lopID' AND ID_MON='$monID' ORDER BY ID_CUM ASC,ID_STT ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="SELECT d.ID_DD FROM diemdanh_buoi AS b INNER JOIN $diemdanh_string AS d ON d.ID_DD=b.ID_STT AND d.ID_HS='$hsID' WHERE b.ID_CUM='$data[ID_CUM]' AND b.ID_LOP='$lopID' AND b.ID_MON='$monID'";
    $result2=mysqli_query($db,$query2);
    $di_hoc+=mysqli_num_rows($result2);
}

$tong_hoc = count_all_di_hoc($hsID,$date_in,$now,$lopID,$monID,$diemdanh_string,$newID);

echo"$tong_hoc - $di_hoc";*/
/*
$caID=30;
$cahoc_string="cahoc";
$ca_codinh_string="ca_codinh";
$monID=1;

$query="SELECT c.siso,c.max,c.cum,g.lop FROM $cahoc_string AS c INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO WHERE c.ID_CA='$caID'";
$result=mysqli_query($db,$query);
$data=mysqli_fetch_assoc($result);

if($data["lop"]==3) {
    $dem = 0;
    $result3 = get_all_lop();
    while ($data3 = mysqli_fetch_assoc($result3)) {
        $dem += count_hs_mon_lop($data3["ID_LOP"], $monID);
    }
} else {
    $dem=count_hs_mon_lop($data["lop"], $monID);
}

$query2="SELECT COUNT(ID_CA) AS dem FROM $cahoc_string WHERE cum='$data[cum]'";
$result2=mysqli_query($db,$query2);
$data2=mysqli_fetch_assoc($result2);

$num=get_num_hs_ca_codinh($caID,$ca_codinh_string);
if ($num<=($dem/$data2["dem"])) {
    echo "vang";
} else if ($num>($dem/$data2["dem"]) && $num<=$data["max"]) {
    echo "quatai";
} else {
    echo "max";
}*/
/*$chuyende_diem="chuyende_diem";
$lopID=1;
$monID=1;
$buoiID=53;

$query2="SELECT DISTINCT c.cau FROM $chuyende_diem AS c INNER JOIN chuyende AS d ON d.ID_CD=c.ID_CD AND d.ID_LOP='$lopID' AND d.ID_MON='$monID' WHERE c.ID_BUOI='$buoiID' AND c.diem LIKE '0/%' ORDER BY c.ID_CD ASC,c.cau ASC,c.y ASC";
$result2=mysqli_query($db,$query2);
while($data2=mysqli_fetch_assoc($result2)) {
    echo"$data2[cau]<br />";
    $query3 = "SELECT c.ID_HS,c.ID_CD FROM $chuyende_diem AS c INNER JOIN chuyende AS d ON d.ID_CD=c.ID_CD AND d.ID_LOP='$lopID' AND d.ID_MON='$monID' WHERE c.ID_BUOI='$buoiID' AND c.diem LIKE '0/%' AND c.cau='$data2[cau]'";
    $result3 = mysqli_query($db, $query3);
    while($data3 = mysqli_fetch_assoc($result3)) {
        echo"$data3[ID_HS] - $data3[ID_CD]<br />";
    }
}

$query="SELECT * FROM diemkt WHERE ID_BUOI>='48' AND loai='3' AND note='1' ORDER BY ID_BUOI DESC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="SELECT * FROM tien_vao WHERE ID_HS='$data[ID_HS]' AND string='kiemtra_1' AND object='$data[ID_BUOI]'";
    $result2=mysqli_query($db,$query2);
    if(mysqli_num_rows($result2) == 0) {
        if (!check_binh_voi($data["ID_HS"], $data["ID_BUOI"], "diemkt")) {
            $buoi=get_ngay_buoikt($data["ID_BUOI"]);
            if ($data["diem"] < 5.25 && check_exited_thachdau4($data["ID_HS"], $buoi, get_lop_hs($data["ID_HS"]), 1)) {

            } else {
                $mon_name = get_mon_name(1);
                $tien = get_phat_diemkt($data["ID_HS"], $data["diem"], $data["de"], $data["loai"], $data["note"], 1, $mon_name, $data["ID_BUOI"], $buoi, true);
                echo "$data[ID_HS] - $data[ID_BUOI] - $data[diem] - $tien<br />";
            }
        }
    }
}

$num = 0;
$query="SELECT * FROM chuyende_diem WHERE (ID_CD='62' OR ID_CD='64') AND ID_BUOI='55' ORDER BY ID_BUOI ASC,ID_HS ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $query2="SELECT de FROM diemkt WHERE ID_BUOI='$data[ID_BUOI]' AND ID_HS='$data[ID_HS]'";
    $result2=mysqli_query($db,$query2);
    $data2=mysqli_fetch_assoc($result2);
    if($data2["de"] == "B") {
        echo "$data[ID_HS] - $data[ID_BUOI] - $data[cau] - $data[ID_CD]<br />";
        $query3="UPDATE chuyende_diem SET ID_CD='54' WHERE ID_STT='$data[ID_STT]'";
        mysqli_query($db,$query3);
    }
}
$query="SELECT * FROM nghi_temp ORDER BY ID_HS ASC";
$result=mysqli_query($db,$query);
while($data=mysqli_fetch_assoc($result)) {
    $lopID=get_lop_hs($data["ID_HS"]);
    $query2="SELECT ID_STT,ID_CUM FROM diemdanh_buoi WHERE date>='$data[start]' AND date<='$data[end]' AND ID_LOP='$lopID' AND ID_MON='$data[ID_MON]'";
    $result2=mysqli_query($db,$query2);
    while($data2=mysqli_fetch_assoc($result2)) {
        $query3="DELETE FROM diemdanh_toan WHERE ID_DD='$data2[ID_STT]' AND ID_HS='$data[ID_HS]'";
        mysqli_query($db,$query3);
        $query3="DELETE FROM diemdanh_nghi WHERE ID_CUM='$data2[ID_CUM]' AND ID_HS='$data[ID_HS]' AND ID_LOP='$lopID' AND ID_MON='$data[ID_MON]'";
        mysqli_query($db,$query3);
    }
}*/
/*$tokens = get_token_hs(156);
$json = "{\"multicast_id\":5657308527723007326,\"success\":2,\"failure\":4,\"canonical_ids\":0,\"results\":[{\"message_id\":\"0:1473680930605812%52c5e570f9fd7ecd\"},{\"message_id\":\"0:1473680930604912%52c5e570f9fd7ecd\"},{\"error\":\"NotRegistered\"},{\"error\":\"NotRegistered\"}]}";
$data=json_decode($json, true);
$result=$data["results"];
$n=count($result);
$string="";
for($i=0;$i<$n;$i++) {
    if(isset($result[$i]["error"])) {
        if($result[$i]["error"] == "NotRegistered") {
            $string .= ",'" . $tokens[$i] . "'";
            echo $tokens[$i]."<br />";
        }
    }
}
if($string != "") {
    $string = substr($string, 1);
    del_token($string);
}*/
//$hsID=694;
//$date_in="2016-09-20";
//$now=null;
//$lopID=1;
//$monID=1;
//$diemdanh_string="diemdanh_toan";
//$temp=count_di_hoc($hsID,$date_in,$now,$lopID,$monID,$diemdanh_string);
//echo $temp[0]. " - " . $temp[1]."<br />";
//$newID=get_new_cum_buoi($lopID,$monID)-1;
//$query="SELECT DISTINCT b.ID_CUM FROM diemdanh_buoi AS b INNER JOIN $diemdanh_string AS dd ON dd.ID_DD=b.ID_STT AND dd.ID_HS='$hsID' WHERE b.ID_CUM!='$newID' AND b.date>='$date_in' $me2 AND b.ID_LOP='$lopID' AND b.ID_MON='$monID'";
//$result=mysqli_query($db,$query);
//$di_hoc=mysqli_num_rows($result);
//echo $di_hoc;

//$query="RENAME TABLE diemdanh TO diemdanh_toan";
//mysqli_query($db,$query);

//$query="SELECT * FROM diemkt WHERE ID_BUOI='82' AND ID_LM='4' ORDER BY ID_HS ASC";
//$result=mysqli_query($db,$query);
//while($data=mysqli_fetch_assoc($result)) {
//    $diem = 0;
//    $query2="SELECT diem FROM chuyende_diem WHERE ID_BUOI='82' AND ID_HS='$data[ID_HS]' AND ID_LM='4' ORDER BY cau ASC,y ASC";
//    $result2=mysqli_query($db,$query2);
//    while($data2=mysqli_fetch_assoc($result2)) {
//        $temp = explode("/",$data2["diem"]);
//        $diem += $temp[0];
//    }
//    $query2="UPDATE diemkt SET diem='$diem' WHERE ID_DIEM='$data[ID_DIEM]'";
//    mysqli_query($db,$query2);
//    $tb=get_cmt_diem_loai2($data["loai"]);
//    add_thong_bao_hs($data["ID_HS"],$data["ID_BUOI"],"Điểm thi ngày 02/10/2016 của bạn được sửa lại là $data[diem] điểm ($data[de]). $tb","diem-thi",$data["ID_LM"]);
//}

//    $query="SELECT ID_HS FROM hocsinh_mon WHERE ID_LM='2'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $query2="UPDATE tien_vao SET string='kiemtra_2' WHERE ID_HS='$data[ID_HS]' AND string='kiemtra_1'";
//        mysqli_query($db,$query2);
//    }

//    $query="SELECT d.ID_HS,d.de,d.ID_LM,b.ID_BUOI,b.ngay,m.date_in FROM diemkt AS d
//    INNER JOIN buoikt AS b ON b.ID_BUOI=d.ID_BUOI
//    INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_LM=d.ID_LM WHERE d.loai=5 AND (d.ID_LM='1' OR d.ID_LM='2')
//    ORDER BY b.ID_BUOI DESC,d.ID_HS ASC";
//    $result=mysqli_query($db,$query);
//    $sum = 0;
//    while($data=mysqli_fetch_assoc($result)) {
//        $query2 = "SELECT ID_VAO,object FROM tien_vao WHERE ID_HS='$data[ID_HS]' AND object='$data[ID_BUOI]' AND note LIKE 'Kh%' AND string='kiemtra_$data[ID_LM]'";
//        $result2=mysqli_query($db,$query2);
//        if(mysqli_num_rows($result2) == 0) {
//            if (!check_binh_voi($data["ID_HS"], $data["ID_BUOI"], $data["ID_LM"])) {
//                if (0 < 5.25 && check_exited_thachdau4($data["ID_HS"], $data["ngay"], $data["ID_LM"])) {
//                    echo"mien<br />";
//                } else {
//                    $price = get_phat_diemkt($data["ID_HS"], 0, $data["de"], 5, 0, $data["ID_LM"], get_lop_mon_name($data["ID_LM"]), $data["ID_BUOI"], $data["ngay"], false);
//                    echo"ERROR: $data[ID_HS] - $data[ID_BUOI] - $data[ngay] - $price<br />";
//                    $sum+=$price;
//                }
//            } else {
//                echo"binh voi<br />";
//            }
//        }
//    }
//    echo format_price($sum);

//    $query="SELECT d.ID_HS,d.de,d.ID_LM FROM diemkt AS d INNER JOIN buoikt AS b ON b.ID_BUOI=d.ID_BUOI AND b.ngay='2016-10-23' AND b.ID_MON='1' WHERE d.ID_LM='1' OR d.ID_LM='2'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $query="UPDATE diemtb_thang SET detb='$data[de]' WHERE ID_HS='$data[ID_HS]' AND ID_LM='$data[ID_LM]' AND datetime='2016-10'";
//
//        mysqli_query($db,$query);
//    }

//    $query="SELECT ID_HS,cmt,note FROM hocsinh WHERE note!='' ORDER BY ID_HS ASC";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
////        echo"$data[cmt] - $data[note]<br /><br />";
//        $temp = explode("-",$data["note"]);
//        $new_note = "";
//        for($i = 0; $i < count($temp); $i++) {
//            if($temp[$i] != "" && $temp[$i] != " " && $temp[$i] != "-" && $temp[$i] != "- ") {
//                if(stripos($temp[$i],"/")===false) {
//                    $new_note .= "<br />" . $temp[$i];
//                } else {
//                    $temp2 = explode("/",$temp[$i]);
//                    if(strlen($temp2[0])==2) {
//                        $ngay = "2016-".format_month_db(substr($temp2[1], 0, 2))."-".format_month_db($temp2[0]);
//                        echo"cc$data[cmt] - $ngay - $temp[$i]<br />";
//                    }
////                    $query2 = "INSERT INTO hocsinh_note(ID_HS,ngay,note,ready,hot)
////                                        value('$data[ID_HS]','$ngay','$temp[$i]','1','0')";
////                    mysqli_query($db, $query2);
//                }
//            }
//        }
////        $new_note = substr($new_note,6);
////        echo"$data[cmt] - cũ: $data[note] - mới: $new_note<br /><br />";
//    }
//    $me = "x^2 + y^2 + \( {z - 3} \)^2 = 3";
//    echo strlen($me);
//    $query = "SELECT ID_DA,sort FROM de_cau_dap_an WHERE ID_DE='46'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $query2="UPDATE de_cau_dap_an SET sort='$data[sort]' WHERE ID_DA='$data[ID_DA]' AND ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom='12')";
//        mysqli_query($db,$query2);
//    }
//    $query="SELECT ID_M,anh FROM dap_an_dai WHERE anh LIKE 'H06-05a-1-image%'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $temp=substr($data["anh"],15);
//        echo"$temp<br />";
//        if($temp > 19) {
//            $query2="UPDATE dap_an_dai SET anh='H06-05a-1-image2' WHERE ID_M='$data[ID_M]'";
//            mysqli_query($db,$query2);
//        }
//    }
//    $query="SELECT maso FROM cau_hoi WHERE maso LIKE '%-%-%a%'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        echo"$data[maso]<br />";
//    }
//    mysqli_query($db,"SET NAMES utf8");
//    $query="SELECT * FROM dictionary ORDER BY idx ASC LIMIT 20";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        echo"$data[idx] - $data[word] - $data[detail]<br />";
////        $query2="UPDATE dictionary SET detail='".addslashes($content)."' WHERE idx='$data[idx]'";
////        mysqli_query($db,$query2);
//    }
//    $query="SELECT ID_C,maso FROM cau_hoi WHERE maso LIKE 'D01-01-%'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $temp=explode("-",$data["maso"]);
//        $maso=$temp[0]."-".$temp[1]."a-".$temp[2];
//        $query2="UPDATE cau_hoi SET maso='$maso' WHERE ID_C='$data[ID_C]'";
//        mysqli_query($db,$query2);
//    }
//    $query="SELECT ID_C,maso FROM cau_hoi";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $maso=$data["maso"];
//        if(stripos($maso, "-") === false) {
//
//        } else {
//            $temp = explode("-",$maso);
//            $maso = $temp[0];
//        }
//        $query2="SELECT ID_CD FROM chuyen_de_con WHERE maso='$maso'";
//        $result2=mysqli_query($db,$query2);
//        if(mysqli_num_rows($result2) != 0) {
//            $data2 = mysqli_fetch_assoc($result2);
//            $query2 = "UPDATE cau_hoi SET ID_CD='$data2[ID_CD]' WHERE ID_C='$data[ID_C]'";
//            mysqli_query($db, $query2);
//        }
//    }

//    $buoi_arr=array("796","792","791","790","789");
//    $query="SELECT ID_HS,ID_LM FROM diemkt WHERE ID_BUOI='101'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $ddID=$buoi_arr[rand(0,4)];
////        insert_diemdanh($ddID, $data["ID_HS"], 0, 0, 0, 1);
//        del_diemdanh_nghi(20, $data["ID_HS"], 0, 1);
////        delete_thongbao($data["ID_HS"], 20, "nghi-hoc", 0);
//    }

//    $hsID=0;
//    $last_de="";
//    $query="SELECT ID_HS,de FROM diemkt WHERE (ID_BUOI='94' OR ID_BUOI='98') AND (ID_LM='2' OR ID_LM='1') ORDER BY ID_LM ASC,ID_HS ASC,ID_BUOI ASC";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        if($hsID != 0 && $hsID != $data["ID_HS"]) {
//            echo "$hsID - $last_de<br />";
//            $last_de="";
//        }
//        $hsID=$data["ID_HS"];
//        $last_de.="$data[de], ";
//    }

//    $query="SELECT ID_HS,COUNT(ID_STT) AS dem,ID_DE FROM hoc_sinh_luyen_de WHERE ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom IN (SELECT ID_N FROM nhom_de WHERE object='101')) GROUP BY ID_HS ORDER BY dem DESC,ID_HS ASC";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        if($data["dem"] > 1) {
//            echo "$data[ID_HS] - $data[dem] - $data[ID_DE]<br />";
//            $query2="DELETE FROM hoc_sinh_luyen_de WHERE ID_HS='$data[ID_HS]' AND type_de='lam-tai-lop' AND time='0' AND ID_DE=''";
////            mysqli_query($db,$query2);
//        }
//    }

//    $query="SELECT ID_DE FROM de_thi WHERE nhom='29' AND main='0' ORDER BY ID_DE DESC LIMIT 10";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $query3="DELETE FROM de_thi WHERE ID_DE='$data[ID_DE]'";
//        mysqli_query($db,$query3);
//        $query3="DELETE FROM de_cau_dap_an WHERE ID_DE='$data[ID_DE]'";
//        mysqli_query($db,$query3);
//    }

//    $query="SELECT ID_HS,ID_DA,COUNT(ID_STT) AS dem,ID_DE FROM hoc_sinh_cau WHERE ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom='31') GROUP BY ID_DA,ID_HS ORDER BY dem DESC,ID_DA ASC";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        if($data["dem"]==2) {
//            $query2="DELETE FROM hoc_sinh_cau WHERE ID_HS='$data[ID_HS]' AND ID_DA='$data[ID_DA]' AND ID_DE='$data[ID_DE]' LIMIT 1";
//            //mysqli_query($db,$query2);
//        }
//        echo"$data[ID_HS] - $data[ID_DA] - $data[ID_DE] - $data[dem]<br />";
//    }

//    $query="SELECT ID_HS,ID_C,COUNT(ID_STT) AS dem,ID_DE FROM hoc_sinh_cau WHERE ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom='31') GROUP BY ID_C,ID_HS ORDER BY dem DESC,ID_C ASC";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        if($data["dem"]==2) {
//            $query2="DELETE FROM hoc_sinh_cau WHERE ID_HS='$data[ID_HS]' AND ID_C='$data[ID_C]' AND ID_DE='$data[ID_DE]' LIMIT 1";
//            //mysqli_query($db,$query2);
//        }
//        echo"$data[ID_HS] - $data[ID_C] - $data[ID_DE] - $data[dem]<br />";
//    }

//    $stt=1;
//    $query="SELECT ID_HS,COUNT(ID_STT) AS dem FROM chuyende_diem WHERE ID_BUOI='103' AND ID_LM='1' GROUP BY ID_HS ORDER BY ID_HS ASC";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        if($data["dem"]>50) {
//            echo "$stt - $data[ID_HS] - $data[dem]<br />";
//            $stt++;
//        }
//    }

//$query="SELECT c.ID_C,u.ID_CD,u.title,n.sort FROM de_noi_dung AS n
//                INNER JOIN cau_hoi AS c ON c.ID_C=n.ID_C
//                INNER JOIN chuyen_de_con AS d ON d.ID_CD=c.ID_CD
//                INNER JOIN chuyende AS u ON u.maso=d.maso AND u.ID_LM='1'
//                WHERE n.ID_DE='".get_de_id("3227",512,103,1)."'
//                ORDER BY n.sort ASC";
//$result = mysqli_query($db, $query);
//while($data = mysqli_fetch_assoc($result)) {
//    echo"$data[ID_C] - $data[sort]<br />";
//}
//$num = mysqli_num_rows($result);
//echo $num;

//$maso = "H03-05a-10b3";
//$temp = explode("-",$maso);
//$temp2 = str_split($temp[2]);
//$me = "";
//for($i = 0; $i < count($temp2); $i++) {
//    if(is_numeric($temp2[$i]))
//        $me .= $temp2[$i];
//    else break;
//}
//$pre_ma = $temp[0]."-".$temp[1]."-".$me;
//echo $pre_ma;

//    $buoiID=101;
//    $lmID=1;
//    $monID=1;
//    $cumID=20;
//    $query="SELECT ID_HS FROM diemkt WHERE ID_BUOI='$buoiID' AND loai='0' AND ID_LM='$lmID' AND ID_HS NOT IN (SELECT ID_HS FROM diemdanh WHERE ID_DD IN ('789','790','791','792','796')) ORDER BY ID_HS ASC";
//    $result = mysqli_query($db, $query);
//    while($data = mysqli_fetch_assoc($result)) {
////        del_diemdanh_nghi($cumID,$data["ID_HS"],$lmID,$monID);
////        insert_diemdanh(789, $data["ID_HS"], 0, 0, 0, 1);
//        echo"$data[ID_HS]<br />";
//    }

//    $query="SELECT ID_HS,string FROM tien_vao WHERE object='98'";
//    $result = mysqli_query($db, $query);
//    while($data = mysqli_fetch_assoc($result)) {
//        delete_phat_thuong($data["ID_HS"],$data["string"],98);
//    }

//    $query="SELECT ID_HS,start,end,ID_LM FROM nghi_temp WHERE loai='0' AND ID_LM!='0'";
//    $result = mysqli_query($db, $query);
//    while($data = mysqli_fetch_assoc($result)) {
//        $query2="SELECT ID_CUM,ID_LM FROM diemdanh_buoi WHERE date>='$data[start]' AND date<='$data[end]' AND (ID_LM='0' OR ID_LM='$data[ID_LM]') AND ID_MON='1' ORDER BY ID_LM DESC,ID_CUM DESC";
//        $result2 = mysqli_query($db, $query2);
//        while($data2 = mysqli_fetch_assoc($result2)) {
//            del_diemdanh_nghi($data2["ID_CUM"],$data["ID_HS"],$data2["ID_LM"],1);
//            insert_diemdanh_nghi($data2["ID_CUM"],$data["ID_HS"],1,$data2["ID_LM"],1);
//        }
//    }

//    $query="SELECT ID_HS,object,string FROM tien_vao WHERE object IN ('879','880','881','882','883','884') AND string='sai-ca-0'";
//    $result = mysqli_query($db, $query);
//    while($data = mysqli_fetch_assoc($result)) {
//        delete_phat_thuong($data["ID_HS"],$data["string"],$data["object"]);
//    }

//    $query="SELECT ID_VAO FROM tien_vao WHERE note='Chuyá»ƒn táº¡m sang ca quÃ¡ táº£i'";
//    $result = mysqli_query($db, $query);
//    while($data = mysqli_fetch_assoc($result)) {
//        delete_phat($data["ID_VAO"]);
//    }

//    $query="SELECT ID_C,maso,ID_CD,note FROM cau_hoi WHERE maso LIKE '00-%'";
//    $result = mysqli_query($db, $query);
//    while($data = mysqli_fetch_assoc($result)) {
//        echo"$data[maso]<br />";
//        $query2="SELECT ID_CD FROM chuyen_de_con WHERE maso='$data[note]'";
//        $result2=mysqli_query($db,$query2);
//        if(mysqli_num_rows($result2)!=0) {
//            $data2 = mysqli_fetch_assoc($result2);
//            $query3="UPDATE cau_hoi SET ID_CD='$data2[ID_CD]' WHERE ID_C='$data[ID_C]'";
//            mysqli_query($db,$query3);
//        }
//    }

//    mysqli_query($db,"SET NAMES 'utf8';");
//    $query="SELECT word,detail FROM tudien ORDER BY ID_W ASC LIMIT 5";
//    $result = mysqli_query($db, $query);
//    while($data = mysqli_fetch_assoc($result)) {
//        echo"$data[detail]<br />";
//    }

//    push_fb_messenger(512, "Test thông báo!");
//    $query = "SELECT FB_ID FROM fb_messenger WHERE ID_HS='156'";
//    $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=EAANwK7FC0cABADXp0qNtZC1t69RgnrNyM1ppQBv3ZB3fYiWwQ7PGWY8q0SICz3Y7oWB8xqEHR2KkZC3u8lGcTaZAZAD2kMH5viDzzG6KXGqOZAXiwUGrhnb97CcdQ8mhnfTen6ZBrr3ZCO4ZAcpmU287bioI3h9ECwvufffZAwZAiKzDQZDZD';
//    $ch = array();
//    $mh = curl_multi_init();
//    $dem = 0;
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        echo $data["FB_ID"] . "<br />";
//        $ch[$dem] = curl_init($url);
//        $jsonData = '{
//            "recipient":{
//                "id":"' . $data["FB_ID"] . '"
//                },
//                "message":{
//                    "text":"Hello"
//                }
//            }';
//        curl_setopt($ch[$dem], CURLOPT_POST, 1);
//        curl_setopt($ch[$dem], CURLOPT_POSTFIELDS, $jsonData);
//        curl_setopt($ch[$dem], CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//
//        curl_multi_add_handle($mh,$ch[$dem]);
//
//        $dem++;
//    }
//
//    $active = null;
//    do {
//        $mrc = curl_multi_exec($mh, $active);
//    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
//
//    while ($active && $mrc == CURLM_OK) {
//        if (curl_multi_select($mh) != -1) {
//            do {
//                $mrc = curl_multi_exec($mh, $active);
//            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
//        }
//    }
//
//    //close the handles
//    for($i = 0; $i < count($ch); $i++) {
//        curl_multi_remove_handle($mh, $ch[$i]);
//    }
//    curl_multi_close($mh);

//    add_thong_bao_hs(156, 0, "Test thông báo fb bot messenger", "nghi-hoc", 1);
//    $content = "";
//    $query="SELECT * FROM diemkt2 WHERE ID_BUOI='98' AND ID_LM='2'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $content .= ",('$data[ID_BUOI]','$data[ID_HS]','$data[diem]','$data[loai]','$data[de]','$data[note]','$data[made]','$data[ID_LM]','$data[more]')";
//    }
//    $content = substr($content,1);
//    $query="INSERT INTO diemkt(ID_BUOI,ID_HS,diem,loai,de,note,made,ID_LM,more) VALUES $content";
//    mysqli_query($db,$query);

//$name=get_lop_mon_name(2);
//    $query="SELECT * FROM diemkt WHERE ID_BUOI='98' AND loai IN ('0','1') AND ID_LM='2'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $diem = $data["diem"];
//        if (!check_binh_voi($data["ID_HS"], $data["ID_BUOI"], $data["ID_LM"])) {
//            if ($diem < 5.25 && check_exited_thachdau4($data["ID_HS"], "2016-11-27", $data["ID_LM"]) && $data["loai"] != 3) {
//
//            } else {
//                get_phat_diemkt($data["ID_HS"], $diem, $data["de"], $data["loai"], $data["note"], $data["ID_LM"], $name, $data["ID_BUOI"], "2016-11-27", true);
//            }
//        }
//    }

//    $query="SELECT ID_HS,ID_LM FROM diemkt WHERE ID_BUOI='106' AND loai='0' AND ID_LM IN ('1','2')";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
////        delete_thongbao($data["ID_HS"],22,"nghi-hoc",$data["ID_LM"]);
////        del_diemdanh_nghi(22,$data["ID_HS"],0,1);
////        insert_diemdanh(855,$data["ID_HS"],0,0,0,1);
//    }

//    $query="SELECT * FROM tien_vao WHERE string='game'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        delete_phat_thuong($data["ID_VAO"],"game",);
//    }

//    $query="SELECT t.ID_HS,h.cmt FROM tien_ra AS t INNER JOIN hocsinh AS h ON h.ID_HS=t.ID_HS WHERE t.string='game'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        echo"$data[cmt]<br />";
//    }

//    $query="SELECT ID_HS,COUNT(ID_STT) AS dem FROM diemdanh WHERE ID_DD='855' GROUP BY ID_HS";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        echo"$data[ID_HS] - $data[dem]<br />";
//        if($data["dem"]>1) {
//            $dem = $data["dem"] - 1;
//            $query2 = "DELETE FROM diemdanh WHERE ID_HS='$data[ID_HS]' AND ID_DD='855' LIMIT $dem";
//            mysqli_query($db, $query2);
//        }
//    }

//    $query="SELECT ID_HS,ID_N FROM list_group";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $query2="SELECT price FROM tien_vao WHERE string='game' AND object='$data[ID_N]' ORDER BY ID_VAO DESC LIMIT 1";
//        $result2=mysqli_query($db,$query2);
//        $data2=mysqli_fetch_assoc($result2);
//        cong_tien_hs($data["ID_HS"],$data2["price"],"Hoàn tiền rời trò chơi (Admin)","game",$data["ID_N"]);
//        echo $data["ID_HS"]."<br />";
//    }

//    $query="SELECT ID_RA FROM tien_ra WHERE note LIKE 'Tiền phát %' AND ID_HS IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='1')";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        delete_thuong($data["ID_RA"]);
//    }

//    echo decode_data("LUTZ45QINTXN5U2I5LKVX2HSS6MQEEPMMTPMH3VAEJU4YLI5O66A====",md5("123456"));

//    $query="SELECT h.ID_HS,l.ID_STT FROM hocsinh AS h LEFT JOIN list_group AS l ON l.ID_HS=h.ID_HS";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        if(!isset($data["ID_STT"]) && !is_numeric($data["ID_STT"])) {
//            $query2 = "SELECT COUNT(ID_VAO) AS dem FROM tien_vao WHERE ID_HS='$data[ID_HS]' AND string='game'";
//            $result2 = mysqli_query($db, $query2);
//            $data2 = mysqli_fetch_assoc($result2);
//            $tien_vao = $data2["dem"];
//            $query2 = "SELECT COUNT(ID_RA) AS dem FROM tien_ra WHERE ID_HS='$data[ID_HS]' AND string='game'";
//            $result2 = mysqli_query($db, $query2);
//            $data2 = mysqli_fetch_assoc($result2);
//            $tien_ra = $data2["dem"];
//            echo "$data[ID_HS] - $tien_vao - $tien_ra<br />";
//        }
//        //if($tien_vao<=$tien_ra) {
//            //echo"$data[ID_HS]<br />";
//        //}
//    }

//    $de_arr=array();
//    $query="SELECT ID_DE,nhom FROM de_thi WHERE main='0' AND nhom IN ('56','58','60','61','62')";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        if(!isset($de_arr[$data["nhom"]])) {
//            $de_arr[$data["nhom"]]="";
//        }
//        $de_arr[$data["nhom"]].=",'$data[ID_DE]'";
//    }
//
//    $query2="SELECT a.ID_DA,a.sort,d.nhom FROM de_cau_dap_an AS a
//    INNER JOIN de_thi AS d ON d.ID_DE=a.ID_DE AND d.main='1' AND d.nhom IN ('56','58','60','61','62')";
//    $result2=mysqli_query($db,$query2);
//    while($data2=mysqli_fetch_assoc($result2)) {
//        $query3 = "UPDATE de_cau_dap_an SET sort='$data2[sort]' WHERE ID_DA='$data2[ID_DA]' AND ID_DE IN (".substr($de_arr[$data2["nhom"]],1).")";
//        mysqli_query($db,$query3);
//    }

//    $query="SELECT ID_HS FROM hoc_sinh_luyen_de WHERE ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom IN (SELECT ID_N FROM nhom_de WHERE object='111')) AND type_de='lam-tai-lop' AND time!='0'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        echo"$data[ID_HS]<br />";
//    }


//        $query2="SELECT l.ID_HS,COUNT(l.ID_STT) AS dem FROM hoc_sinh_luyen_de AS l
//        INNER JOIN de_thi AS d ON d.ID_DE=l.ID_DE
//        INNER JOIN nhom_de AS n ON n.ID_N=d.nhom AND n.object='111'
//        GROUP BY l.ID_HS";
//        $result2=mysqli_query($db,$query2);
//        while($data2=mysqli_fetch_assoc($result2)) {
//            echo "$data2[ID_HS] - $data2[dem]<br />";
//        }

//    $dem_arr = array();
//    $query="SELECT ID_VAO,ID_HS,note FROM tien_vao WHERE string LIKE 'trac_nghiem_unlock_%' ORDER BY ID_HS ASC";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        if(!isset($dem_arr[$data["ID_HS"]])) {
//            $dem_arr[$data["ID_HS"]]=1;
//        } else {
//            $dem_arr[$data["ID_HS"]]++;
//        }
//    }

//    foreach ($dem_arr as $key => $value) {
//        if($value > 1) {
//            echo "$key - $value<br />";
//            $query="UPDATE hoc_sinh_info SET count_lock='$value' WHERE ID_HS='$key'";
//            mysqli_query($db,$query);
//        }
//    }

//    $query="SELECT ID_HS,count_lock FROM hoc_sinh_info";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $query2="UPDATE hocsinh SET lock_times='$data[count_lock]' WHERE ID_HS='$data[ID_HS]'";
//        mysqli_query($db,$query2);
//    }
//function imageToImg($monID, $content, $max_height) {
//
//    while(stripos($content, "|<|") != false) {
//        $me = stripos($content, "|<|");
//        $you = stripos($content, "|>|");
//        $img = substr($content,$me,$you-$me+3);
//        $img2 = str_replace(array("|<|","|>|","<br />"),"",$img);
//        if(file_exists("luyenthi/upload/$monID/$img2.png")) {
//            $img2 = "$img2.png";
//        } else if(file_exists("luyenthi/upload/$monID/$img2.jpg")) {
//            $img2 = "$img2.jpg";
//        } else if(file_exists("luyenthi/upload/$monID/$img2.jpeg")) {
//            $img2 = "$img2.jpeg";
//        } else if(file_exists("../upload/$monID/$img2.png")) {
//            $img2 = "$img2.png";
//        } else if(file_exists("../upload/$monID/$img2.jpg")) {
//            $img2 = "$img2.jpg";
//        } else if(file_exists("../upload/$monID/$img2.jpeg")) {
//            $img2 = "$img2.jpeg";
//        } else {
//            return "Ảnh bị lỗi: ".$img2;
//        }
//        $content = str_replace($img, "<span style='text-align: center;display: block;'><img src='http://localhost/www/TDUONG/luyenthi/upload/$monID/"."$img2' style='max-height:".$max_height."px;max-width:70%;' class='img-thumbnail img-responsive' /></span>", $content);
//    }
//
////    $content = str_replace(array("|<|","|>|\n","|>|"),"",$content);
//    $content = str_replace("--","-",$content);
//    return $content;
//}
//$content="Một hộp không nắp được làm từ một mảnh các tông theo hình mẫu. Hộp có đáy là một hình vuông cạnh x(cm), chiều cao là h(cm) và có thể tích là \(500c{m^3}\)  .Hãy tìm độ dài cạnh của hình vuông sao cho chiếc hộp được làm ra tốn ít nhiên liệu nhất|<|00-06a-5-image1|>|Heloo f a gf jajfgoj gaij ijgf jaddg afd gadfh aa f sdfg |<|00-06a-5-image2|>|Kasdkasn kfakf adfna kdfa dnflna ngae pandf dn";
//echo imageToImg(1,$content,250);

//
//    $query="SELECT * FROM cau_hoi WHERE maso LIKE '00-06a-%'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $content=str_replace(";\n",";",$data["content"]);
//        $content=str_replace(";<br />",";",$content);
//        echo"<b>$data[ID_C]</b> - ".$content."<br /><br />";
//    }

//    $content=str_replace("\n","<br />",$data["content"]);
//    $temp=explode(";",$content);
//    for($i=0;$i<count($temp);$i++) {
//        echo substr($temp[$i],0,4)."<br />";
//        if(substr($temp[$i],0,4) == "<br />") {
//            echo"cc";
//        }
////        echo nl2br(str_replace("\n","",$temp[$i])).";";
//    }

//    $query="SELECT ID_HS,lock_times FROM hocsinh";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        if($data["lock_times"] > 0) {
//            $query2 = "UPDATE hocsinh SET lock_times='" . ($data["lock_times"] - 1) . "' WHERE ID_HS='$data[ID_HS]'";
//            mysqli_query($db, $query2);
//        }
//    }
//$he = "d' asdasd";
//echo str_replace("' ","'\\ ",$he);
//    $query="SELECT ID_STT,ID_DE,ID_HS FROM hoc_sinh_luyen_de WHERE type_de='lam-cuoi-tuan' AND time='0' AND datetime LIKE '2017-01-15 %'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $query2="DELETE FROM hoc_sinh_cau WHERE ID_DE='$data[ID_DE]' AND ID_HS='$data[ID_HS]'";
//        mysqli_query($db,$query2);
//        $query2="DELETE FROM hoc_sinh_luyen_de WHERE ID_STT='$data[ID_STT]'";
//        mysqli_query($db,$query2);
//    }

//    $query="SELECT ID_N,ID_HS FROM list_group ORDER BY ID_N ASC";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $query2="SELECT COUNT(ID_RA) AS dem FROM tien_ra WHERE ID_HS='$data[ID_HS]' AND string='game'";
//        $result2=mysqli_query($db,$query2);
//        $data2=mysqli_fetch_assoc($result2);
//        $query3="SELECT COUNT(ID_VAO) AS dem FROM tien_vao WHERE ID_HS='$data[ID_HS]' AND string='game'";
//        $result3=mysqli_query($db,$query3);
//        $data3=mysqli_fetch_assoc($result3);
//        if($data3["dem"] <= $data2["dem"]) {
//            echo"$data[ID_HS] - $data3[dem] - $data2[dem]<br />";
//            tru_tien_hs($data["ID_HS"],60000,"Trừ tiền gia nhập nhóm tham gia trò chơi","game",$data["ID_N"]);
//        }
//    }

//    $query="SELECT * FROM diemkt WHERE ID_BUOI='115' AND ID_LM='2'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        delete_thongbao($data["ID_HS"], $data["ID_BUOI"], "diem-thi", $data["ID_LM"]);
//        $loai=$data["loai"];
//        if($loai!=3) {
//            $tb=get_cmt_diem_loai2($loai);
//        } else {
//            $result2=get_lido($data["note"]);
//            $data2=mysqli_fetch_assoc($result2);
//            $tb=$data2["name"];
//        }
//        add_thong_bao_hs($data["ID_HS"],$data["ID_BUOI"], "Điểm thi ngày 22/01/2017 của bạn là $data[diem] điểm ($data[de]). $tb", "diem-thi", $data["ID_LM"]);
//    }

//    $query="SELECT ID_HS FROM hocsinh ORDER BY ID_HS ASC LIMIT 10";
//    $result=mysqli_query($db,$query);
//$data=mysqli_fetch_assoc($result);
//$more=$data;
//    echo $more["ID_HS"]."<br />";
//    while($data=mysqli_fetch_assoc($result)) {
//        echo"$data[ID_HS]<br />";
//    }

//    $query="SELECT ID_HS,new_de,ID_LM FROM nhayde";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        if($data["new_de"] == "G") {
//            $de="B";
//        } else if($data["new_de"] == "B") {
//            $de="G";
//        } else if($data["new_de"] == "Y") {
//            $de="B";
//        } else {
//            $de=$data["new_de"];
//            echo"cc";
//        }
//        update_de_hs($data["ID_HS"],$de,$data["ID_LM"]);
//    }
//
//    $str_arr = file("input.txt");
//    foreach ($str_arr as $str) {
//        $cmt=trim($str);
//        $hsID=get_hs_id($cmt);
//        if($hsID != 0) {
//            echo"$cmt - $hsID<br />";
//            cong_tien_hs($hsID, 10000, "Cộng tiền share bài viết!", "thuong-them",0);
//            add_log($hsID, "Thưởng 01 thẻ miễn phạt share bài viết!", "the-mien-phat");
//        }
//    }
//    $query="SELECT ID_VAO,ID_HS,price FROM tien_vao WHERE string='sai-ca-0' AND date='2017-02-19'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $tien=get_tien_hs($data["ID_HS"]);
//        update_tien_hs($data["ID_HS"],$tien+$data["price"]);
//        $query2="DELETE FROM tien_vao WHERE ID_VAO='$data[ID_VAO]'";
//        mysqli_query($db,$query2);
//    }
//    $query="SELECT ID_VAO,ID_HS,price FROM tien_vao WHERE price='0' AND string LIKE 'kiemtra_%' AND object IN ('118','120')";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $query2="UPDATE tien_vao SET price='10000' WHERE ID_VAO='$data[ID_VAO]'";
//        mysqli_query($db,$query2);
//        $tien=get_tien_hs($data["ID_HS"]);
//        update_tien_hs($data["ID_HS"],$tien-10000);
//    }
//    add_options2("ccc","gdrive_url",1,"");
//    $query="SELECT h.cmt,h.fullname,d.diemtb,d.detb FROM hocsinh AS h
//    INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.de='Y' AND (m.ID_LM='1' OR m.ID_LM='2')
//    INNER JOIN diemtb_thang AS d ON d.ID_HS=h.ID_HS AND d.diemtb<'5' AND d.detb='Y' AND d.datetime='2017-02' AND d.ID_LM=m.ID_LM AND d.diemtb!='0'
//    ORDER BY m.ID_LM ASC,d.diemtb DESC";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        echo"$data[cmt]\t$data[diemtb]\t$data[detb]\t$data[fullname]<br />";
//    }

//    $query="SELECT ID_DE,mota FROM de_thi";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $query2="UPDATE de_thi SET string='".unicode_convert($data["mota"])."' WHERE ID_DE='$data[ID_DE]'";
//        mysqli_query($db,$query2);
//    }
//$me="99-0163-Bimat_99";
//$pattern="/\d+-\d+-(.*)/";
//$datas = preg_match_all($pattern, $me, $matches);
//$datas = $matches[0];
//if(count($datas) > 0) {
//    $temp = explode("-", addslashes(trim($datas[0])));
//    $maso = $temp[0] . "-" . $temp[1];
//    $pass = "";
//    for ($i = 2; $i < count($temp); $i++) {
//        $pass .= $temp[$i] . "-";
//    }
//    $pass = rtrim($pass, "-");
//    echo"$maso - ".addslashes(trim($pass));
//}
//    $de_arr=array();
//    $query="SELECT n.ID_N,l.name,n.ID_LM FROM nhom_de AS n
//        INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
//        INNER JOIN loai_de AS l ON l.ID_D=d.loai
//        WHERE n.object='133'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $de_arr[$data["name"]."|".$data["ID_LM"]]=$data["ID_N"];
//    }
//    $query="SELECT d.*,m.de,m.ID_LM AS lm FROM diemdanh_nghi AS d
//    INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND (m.ID_LM='1' OR m.ID_LM='2')
//    WHERE d.ID_CUM='32' AND d.anh!='' AND d.sdt!='' AND d.ID_LM='0' AND d.ID_MON='1'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $hsID=$data["ID_HS"];
//        if(isset($de_arr[$data["de"]."|".$data["lm"]])) {
//            $nID = $de_arr[$data["de"] . "|" . $data["lm"]];
//            $query = "INSERT INTO hoc_sinh_special(ID_HS,ID_N) SELECT * FROM (SELECT '$hsID' AS hsID,'$nID' AS nID) AS tmp WHERE NOT EXISTS (SELECT ID_STT FROM hoc_sinh_special WHERE ID_HS='$hsID' AND ID_N='$nID') LIMIT 1";
//            mysqli_query($db, $query);
//        }
//        $query="UPDATE diemdanh_nghi SET ID_CUM='32',ID_LM='0' WHERE ID_STT='$data[ID_STT]'";
//        mysqli_query($db, $query);
//    }

//    $date="2017-03-18";
//    $query="SELECT ID_STT FROM diemdanh_buoi WHERE ID_CUM='31' AND date='$date' AND ID_LM='0' AND ID_MON='1'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $query2="SELECT ID_HS FROM diemdanh WHERE ID_DD='$data[ID_STT]' AND ca_check='0'";
//        $result2=mysqli_query($db,$query2);
//        while($data2=mysqli_fetch_assoc($result2)) {
//            tru_tien_hs($data2["ID_HS"], 20000, "Trừ 20k tiền đi học sai ca ngày " . format_dateup($date), "sai-ca-0", $data["ID_STT"]);
//        }
//    }

//    $data_arr=array();
//    $start=0;
//
//	$query="SELECT a.ID_DA,a.note,a.ID_HS,a.ID_DE,d.ID_C FROM hoc_sinh_dap_an AS a
//	INNER JOIN dap_an_ngan AS d ON d.ID_DA=a.ID_DA LIMIT $start,100000";
//	$result=mysqli_query($db,$query);
//	echo mysqli_num_rows($result);
//    while($data=mysqli_fetch_assoc($result)) {
//		$data_arr[$data["ID_C"]."|".$data["ID_HS"]."|".$data["ID_DE"]] = array(
//			"ID_HS" => $data["ID_HS"],
//			"ID_DA" => $data["ID_DA"],
//			"note" => $data["note"],
//			"ID_DE" => $data["ID_DE"],
//			"ID_C" => $data["ID_C"]
//		);
//    }
//	echo "\n";
//	mysqli_free_result($result);
//	$dem=0;
//	$content=array();
//	$query="SELECT ID_C,time,ID_HS,ID_DE FROM hoc_sinh_cau";
//	$result=mysqli_query($db,$query);
//	while($data=mysqli_fetch_assoc($result)) {
//		$str = $data["ID_C"]."|".$data["ID_HS"]."|".$data["ID_DE"];
//		if(isset($data_arr[$str])) {
//			$content[] = "('$data[ID_HS]','$data[ID_C]','".$data_arr[$str]["ID_DA"]."','0','$data[time]','".$data_arr[$str]["note"]."','$data[ID_DE]')";
//			$dem++;
//			if($dem==20000) {
//				$content = implode(",",$content);
//				$query2="INSERT INTO hoc_sinh_temp(ID_HS,ID_C,ID_DA,num,time,note,ID_DE) VALUES $content";
//				mysqli_query($db,$query2);
//				$dem = 0;
//				$content = array();
//			}
//		}
//	}
//	if(count($content) > 0) {
//		$content = implode(",",$content);
//		$query2="INSERT INTO hoc_sinh_temp(ID_HS,ID_C,ID_DA,num,time,note,ID_DE) VALUES $content";
//		mysqli_query($db,$query2);
//		$dem = 0;
//		$content = array();
//	}
    //var_dump($data_arr);

//    push_chatfuel("58d13ef4e4b08d6ebcf13cc5", "Ahihi");
//    $hsID=262;
//    $deID=4743;
//    $query="SELECT c.ID_STT,a.ID_C,c.ID_DA FROM hoc_sinh_cau AS c
//    INNER JOIN dap_an_ngan AS a ON a.ID_DA=c.ID_DA
//    WHERE c.ID_HS='$hsID' AND c.ID_C='0' AND c.ID_DE='$deID'";
//    $result=mysqli_query($db,$query);
//    echo mysqli_num_rows($result)."<br />";
//    while($data=mysqli_fetch_assoc($result)) {
//        echo"$data[ID_STT] - $data[ID_C] - $data[ID_DA]<br />";
//    }

//    $query="SELECT ID_HS FROM hocsinh_mon WHERE ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='1') AND ID_LM='1'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        update_de_hs($data["ID_HS"],"G",1);
//        add_thong_bao_hs($data["ID_HS"],1,"Bạn được lên đề G kể từ tháng 04/2017","nhay-de",1);
//    }
//    $query="SELECT ID_C FROM cau_hoi WHERE maso LIKE 'AA-%' AND ID_MON='3'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $query2="DELETE FROM cau_hoi WHERE ID_C='$data[ID_C]'";
//        mysqli_query($db,$query2);
//        $query2="DELETE FROM dap_an_ngan WHERE ID_C='$data[ID_C]'";
//        mysqli_query($db,$query2);
//        $query2="DELETE FROM dap_an_dai WHERE ID_C='$data[ID_C]'";
//        mysqli_query($db,$query2);
//    }
//add_thong_bao_hs(0,1,"Bạn được lên đề G kể từ tháng 04/2017","nhay-de",1);
//$str = "https://www.fb.com/profile.php?id=3424234";
//$pattern = "/facebook.com\/profile.php\?id=[0-9]+|fb.com\/profile.php\?id=[0-9]+|facebook.com\/[a-zA-Z0-9_.]+|fb.com\/[a-zA-Z0-9_.]+/";
//$datas = preg_match_all($pattern, $str, $matches);
//$datas = $matches[0];
//echo $datas[0];

//    $query="SELECT * FROM game_group WHERE ID_LM='1'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $query2="DELETE FROM list_group WHERE ID_N='$data[ID_N]'";
//        mysqli_query($db,$query2);
//        $query2="DELETE FROM game_group WHERE ID_N='$data[ID_N]'";
//        mysqli_query($db,$query2);
//    }
//    $query="SELECT * FROM game_group WHERE ID_N<='179'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $query2="SELECT COUNT(ID_STT) AS dem FROM hocvien_info WHERE ID_N='$data[ID_N]'";
//        $result2=mysqli_query($db,$query2);
//        $data2=mysqli_fetch_assoc($result2);
//        if($data2["dem"]==0) {
//            $query2="DELETE FROM list_group WHERE ID_N='$data[ID_N]'";
//            mysqli_query($db,$query2);
//            $query2="DELETE FROM game_group WHERE ID_N='$data[ID_N]'";
//            mysqli_query($db,$query2);
//        }
//    }
//    $ca="7-8h-5h30";
//    echo check_ca_phat_ve(addslashes($ca));
//    $query="SELECT t.ID_STT,d.mota FROM hoc_sinh_tu_luyen AS t
//    INNER JOIN de_thi AS d ON d.ID_DE=t.ID_DE";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        if(stripos($data["mota"],"Đề kiểm tra") === false) {
//            $query2="UPDATE hoc_sinh_tu_luyen SET type='chuyen-de' WHERE ";
//        }
//    }
//    echo decode_data("G7H3T5A77I37X7W22AB5XGKXM4UPPPQSO5Z4TNXFU6GL5XM7WILQ====", md5("123456"));
//    $lmID = 2;
//    $ddID = 1480;
//    $cumID = 126;
//    $query="SELECT h.ID_HS FROM hocsinh AS h
//    INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' AND m.date_in='2017-05-01'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        insert_diemdanh($ddID, $data["ID_HS"], 0, 0, 0, 1);
//        add_thong_bao_hs($data["ID_HS"], $cumID, "Bạn đã được điểm danh cụm học ngày 1-2/5 ", "nghi-hoc", $lmID);
//    }
    $lmID = 1;
    $ddID = 1675;
    $cumID = 196;
    $monID = 1;
    $query="SELECT h.ID_HS FROM hocsinh AS h
        INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' AND m.date_in='2017-05-29'";
    $result=mysqli_query($db,$query);
    while($data=mysqli_fetch_assoc($result)) {
        if(!check_exited_diemdanh($cumID, $data["ID_HS"], $lmID, $monID)) {
            insert_diemdanh($ddID, $data["ID_HS"], 0, 0, 0, 1);
            add_thong_bao_hs($data["ID_HS"], $cumID, "Bạn đã được điểm danh cụm học ngày 29-30/5 ", "nghi-hoc", $lmID);
        }
    }
//    echo fill_zero("17", 7);
//    $query="SELECT ID_STT,the FROM hocvien_info";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $query2="UPDATE hocvien_info SET the='".fill_zero($data["the"],7)."' WHERE ID_STT='$data[ID_STT]'";
//        mysqli_query($db,$query2);
//    }
//    $query="SELECT h.ID_HS,h.taikhoan,t.ID_RA,t.price FROM hocsinh AS h
//        INNER JOIN tien_ra AS t WHERE t.ID_HS=h.ID_HS AND t.string='ad-nap-tien' AND t.date='2017-05-17'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $tien=$data["taikhoan"]-$data["price"];
//        update_tien_hs($data["ID_HS"], $tien);
//        $query2="DELETE FROM tien_ra WHERE ID_RA='$data[ID_RA]'";
//        mysqli_query($db,$query2);
//    }

//    $query="SELECT h.ID_HS,d.ID_STT FROM hocsinh AS h
//        INNER JOIN diemdanh AS d ON d.ID_HS=h.ID_HS AND d.ID_DD='1573' AND is_hoc='1' AND is_tinh='1' AND is_kt='1'";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        cong_tien_hs($data["ID_HS"], 10000, "Cộng 10k tiền đi học ca vắng ca 16h-17h30 ngày 15/5/2017", "ad-nap-tien", $data["ID_STT"]);
//    }
//

//    $s1 = "kietcvc";
//    $s2 = "iet";
//    echo "$s1<br />";
//    echo "$s2<br />";
//
//    $s1 = "k-i-e-t-c-v-c";
//    $s2 = "i-e-t";
//    $ecx = explode("-",$s1);
//    $eax = explode("-",$s2);
//
//    $ebx = -1;
//    $edx = 0;
//
//    $ecx_i = 0;
//    while(true) {
//        if(!isset($ecx[$ecx_i])) {
//            break;
//        }
//
//        if(!isset($eax[$edx])) {
//            break;
//        }
//
//        $ebx++;
//        $ecx_i++;
//
//        if($ecx[$ecx_i-1] == $eax[$edx]) {
//            $ebx--;
//            $edx++;
//        } else {
//            $edx = 0;
//        }
//    }
//
//    if($edx == 0) {
//        $ebx = -1;
//    } else {
//        $ebx++;
//    }
//
//    echo "Result: ".$ebx."<br />";
//    echo $edx;

    /*$ecx = array(5, 7, 0, 6, 2, 9);

    $it = 0;
    $ecx_i = 0;
    $ebx = 0;
    while(true) {
        if(isset($ecx[$ecx_i+1])) {
            if ($ecx[$ecx_i] <= $ecx[$ecx_i + 1]) {
                $ebx++;
                if ($ebx == 6) {
                    break;
                }
            } else {
                $temp = $ecx[$ecx_i];
                $ecx[$ecx_i] = $ecx[$ecx_i + 1];
                $ecx[$ecx_i + 1] = $temp;
                $ebx = 0;
            }
        }
        $ecx_i++;
        if(!isset($ecx[$ecx_i])) {
            $ecx_i = 0;
        }
        $it++;
        if($it == 7) {
            break;
        }
    }

    foreach ($ecx as $key => $value) {
        echo "$value, ";
    }
    echo"($it)";*/
	
//	$query="SELECT ID_DIEM,ID_HS FROM diemkt WHERE ID_BUOI='160' AND loai='5' AND ID_LM='2'";
//	$result=mysqli_query($db,$query);
//	while($data=mysqli_fetch_assoc($result)) {
//		$query2="DELETE FROM diemkt WHERE ID_DIEM='$data[ID_DIEM]'";
//		mysqli_query($db,$query2);
//		delete_phat_thuong($data["ID_HS"],"kiemtra_2",160);
//	}

//    $query="SELECT ID_BUOI FROM buoikt WHERE ID_MON='1'";
//    $result=mysqli_query($db,$query);
//	while($data=mysqli_fetch_assoc($result)) {
//        $query2="SELECT ID_HS,string,COUNT(ID_RA) AS dem FROM tien_ra WHERE string IN ('kiemtra_1','kiemtra_2') AND object='$data[ID_BUOI]' GROUP BY ID_HS";
//        $result2=mysqli_query($db,$query2);
//        while($data2=mysqli_fetch_assoc($result2)) {
//            if($data2["dem"] != 1) {
//                echo $data2["ID_HS"] . " - " . $data2["dem"] . " - " . $data["ID_BUOI"]."<br />";
//                $query3="DELETE FROM tien_ra WHERE ID_HS='$data2[ID_HS]' AND string='$data2[string]' AND object='$data[ID_BUOI]' LIMIT ".($data2["dem"]-1);
//                mysqli_query($db,$query3);
//            }
//        }
//	}
//
//    $dem=0;
//    $query="SELECT ID_HS,cmt,taikhoan FROM hocsinh WHERE ID_HS IN (SELECT ID_HS FROM hocsinh_mon WHERE ID_LM='1' OR ID_LM='2')";
//    $result=mysqli_query($db,$query);
//    while($data=mysqli_fetch_assoc($result)) {
//        $query2="SELECT SUM(price) AS tien FROM tien_ra WHERE ID_HS='$data[ID_HS]'";
//        $result2=mysqli_query($db,$query2);
//        $data2=mysqli_fetch_assoc($result2);
//        $cong=$data2["tien"];
//
//        $query2="SELECT SUM(price) AS tien FROM tien_vao WHERE ID_HS='$data[ID_HS]'";
//        $result2=mysqli_query($db,$query2);
//        $data2=mysqli_fetch_assoc($result2);
//        $tru=$data2["tien"];
//
//        if($data["taikhoan"] != $cong-$tru) {
//            echo"$dem - $data[ID_HS] - $data[cmt] - $data[taikhoan] - ". ($cong-$tru)."<br />";
//            update_tien_hs($data["ID_HS"], $cong-$tru);
//        }
//        $dem++;
//    }
//    for($i = 155; $i <= 172; $i++) {
//        $maso = "01-0".$i;
//        $query="UPDATE hocsinh SET password='".md5($maso)."' WHERE cmt='$maso'";
//        mysqli_query($db,$query);
//    }
//    $result=get_all_lop_mon();
//    while($data=mysqli_fetch_assoc($result)) {
//        $total = $dem = 0;
//        $query2="SELECT ID_HS FROM hocsinh_mon WHERE ID_LM='$data[ID_LM]' AND ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$data[ID_LM]')";
//        $result2=mysqli_query($db,$query2);
//        while($data2=mysqli_fetch_assoc($result2)) {
//            $query3="SELECT ID_STT FROM hocsinh_mon WHERE ID_HS='$data2[ID_HS]' AND ID_LM IN ('2','14')";
//            $result3=mysqli_query($db,$query3);
//            if(mysqli_num_rows($result3) != 0) {
//                $dem++;
//            }
//            $total++;
//        }
//        echo $data["name"]. ": $dem/$total (".format_phantram($dem*100/$total).")<br />";
//    }
//    $year_in = 2017;
//    $month_in = 6;
//
//    $now = date("Y-m");
//    $temp = explode("-", $now);
//    $year = $temp[0];
//    $month = $temp[1];
//
//    $mon_arr = array();
//    for($i = -2; $i <= 2; $i++) {
//        if($month + $i < 1) {
//            $year_cur = $year - 1;
//            $month_cur = 12 + ($month + $i);
//        } else if($month + $i > 12) {
//            $year_cur = $year + 1;
//            $month_cur = ($month + $i - 12);
//        } else {
//            $year_cur = $year;
//            $month_cur = $month + $i;
//        }
//
//        if($year_cur < $year_in || ($year_cur == $year_in && $month_cur < $month_in)) {
//            continue;
//        }
//
//        $mon_arr[] = "'" . $year_cur . "-" . format_month_db($month_cur) . "'";
//    }
//
//    $mon_str = implode(",", $mon_arr);
//
//    $query2 = "SELECT ID_STT,date_dong,money FROM tien_hoc WHERE ID_HS='$data[ID_HS]' AND ID_LM='$data1[ID_LM]' AND date_dong IN ($month_need) ORDER BY date_dong DESC LIMIT 5";
//    $result2 = mysqli_query($db,$query2);
//
//    echo $mon_str;

    $query = "SELECT ID_HS FROM hocsinh_mon WHERE ID_LM='2'";
    $result = mysqli_query($db,$query);
    while($data = mysqli_fetch_assoc($result)) {
        $query2 = "SELECT de FROM diemkt WHERE ID_HS='$data[ID_HS]' AND ID_BUOI='187'";
        $result2 = mysqli_query($db,$query2);
        if(mysqli_num_rows($result2) == 0) {
            echo $data["ID_HS"] . "<br />";
        } else {
            $data2 = mysqli_fetch_assoc($result2);
            update_de_hs($data["ID_HS"], $data2["de"], 2);
        }
    }

    ob_end_flush();
    require_once("model/close_db.php");
?>