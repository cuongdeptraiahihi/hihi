<?php
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    require_once("Base32.php");
    use Base32\Base32;

    class Phan_Loai {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function getPhanLoai() {
            $query = $this->conn->prepare("SELECT ID_PL,name FROM phan_loai ORDER BY ID_PL ASC");
            $query->execute();
            return $query->get_result();
        }

        public function getPhanLoaiWithNum() {
            $query = $this->conn->prepare("SELECT p.ID_PL,p.name,COUNT(c.ID_C) AS num FROM phan_loai AS p
            LEFT JOIN cau_hoi AS c ON c.ID_PL=p.ID_PL
            ORDER BY ID_PL ASC");
            $query->execute();
            return $query->get_result();
        }

        public function addPhanLoai($name, $mota) {
            $query = $this->conn->prepare("INSERT INTO phan_loai(name,mota)
                                                            value('$name','$mota')");
            $query->execute();
        }

        public function editPhanLoai($name, $mota, $id) {
            $query = $this->conn->prepare("UPDATE phan_loai SET name='$name',mota='$mota' WHERE ID_PL=?");
            $query->bind_param("s",$id);
            $query->execute();
        }
    }

    class Do_Kho {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function getDoKho() {
            $query = $this->conn->prepare("SELECT * FROM do_kho ORDER BY muc ASC");
            $query->execute();
            return $query->get_result();
        }
    }

    class Chuyen_De {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function getChuyenDeByMon($monID) {
            $query = $this->conn->prepare("SELECT * FROM chuyen_de_con WHERE ID_DAD IN (SELECT ID_DAD FROM chuyen_de_dad WHERE ID_MON='$monID')");
            $query->execute();
            return $query->get_result();
        }

        public function getChuyenDeUnlockByMon($lmID,$monID) {
            $query = $this->conn->prepare("SELECT * FROM chuyen_de_con WHERE ID_DAD IN (SELECT ID_DAD FROM chuyen_de_dad WHERE ID_MON='$monID') AND ID_CD IN (SELECT ID_CD FROM chuyen_de_unlock WHERE ID_LM='$lmID')");
            $query->execute();
            return $query->get_result();
        }

        public function getChuyenDeUnlockByMonWithNum($lmID,$monID) {
            $query = $this->conn->prepare("SELECT d.*,COUNT(c.ID_C) AS num FROM chuyen_de_con AS d 
            INNER JOIN cau_hoi AS c ON c.ID_CD=d.ID_CD AND c.ID_MON='$monID'
            WHERE d.ID_DAD IN (SELECT ID_DAD FROM chuyen_de_dad WHERE ID_MON='$monID') AND d.ID_CD IN (SELECT ID_CD FROM chuyen_de_unlock WHERE ID_LM='$lmID')
            GROUP BY d.ID_CD
            ORDER BY d.ID_CD ASC");
            $query->execute();
            return $query->get_result();
        }

        public function getChuyenDeDadByMon($monID) {
            $query = $this->conn->prepare("SELECT * FROM chuyen_de_dad WHERE ID_MON='$monID' ORDER BY maso DESC");
            $query->execute();
            return $query->get_result();
        }

        public function getChuyenDeConByDad($dadID) {
            $query = $this->conn->prepare("SELECT * FROM chuyen_de_con WHERE ID_DAD='$dadID' ORDER BY maso DESC");
            $query->execute();
            return $query->get_result();
        }

        public function getChuyenDeConByDad2($cdID) {
            $query = $this->conn->prepare("SELECT * FROM chuyen_de_con WHERE ID_DAD2='$cdID' ORDER BY maso DESC");
            $query->execute();
            return $query->get_result();
        }

        public function getChuyenDeConByDadCheck($dadID,$lmID) {
            $query = $this->conn->prepare("SELECT c.*,u.ID_STT FROM chuyen_de_con AS c LEFT JOIN chuyen_de_unlock AS u ON u.ID_CD=c.ID_CD AND u.ID_LM='$lmID' WHERE c.ID_DAD='$dadID' ORDER BY c.maso DESC");
            $query->execute();
            return $query->get_result();
        }

        public function editChuyenDe($maso, $name, $dadID) {
            $query = $this->conn->prepare("UPDATE chuyen_de_dad SET maso='$maso',name='$name' WHERE ID_DAD='$dadID'");
            $query->execute();
        }

        public function addChuyenDe($maso, $name, $monID) {
            $query = $this->conn->prepare("INSERT INTO chuyen_de_dad(maso,name,ID_MON)
                                                            value('$maso','$name','$monID')");
            $query->execute();
        }

        public function editChuyenDeCon($maso, $name, $cdID) {
            $query = $this->conn->prepare("UPDATE chuyen_de_con SET maso='$maso',name='$name' WHERE ID_CD='$cdID'");
            $query->execute();
        }

        public function addChuyenDeCon($maso, $name, $dadID, $dadID2) {
            $query = $this->conn->prepare("INSERT INTO chuyen_de_con(maso,name,ID_DAD,ID_DAD2)
                                                            value('$maso','$name','$dadID','$dadID2')");
            $query->execute();
        }

        public function checkChuyenDeMaso($maso) {
            $query = $this->conn->prepare("SELECT maso FROM chuyen_de_dad WHERE maso=?");
            $query->bind_param("s",$maso);
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                return true;
            } else {
                return false;
            }
        }

        public function countChuyenDeCon($dadID) {
            $query = $this->conn->prepare("SELECT COUNT(ID_CD) AS dem FROM chuyen_de_con WHERE ID_DAD=?");
            $query->bind_param("s",$dadID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"];
        }

        public function delChuyenDeDad($dadID) {
            $query = $this->conn->prepare("DELETE FROM chuyen_de_dad WHERE ID_DAD='$dadID'");
            $query->execute();
        }

        public function delChuyenDeCon($cdID) {
            $query = $this->conn->prepare("DELETE FROM chuyen_de_con WHERE ID_CD='$cdID'");
            $query->execute();
        }

        public function unlockChuyenDe($cdID,$lmID) {
            $query = $this->conn->prepare("INSERT INTO chuyen_de_unlock(ID_CD,ID_LM)
            SELECT * FROM (SELECT '$cdID' AS cd,'$lmID' AS lm) AS tmp
            WHERE NOT EXISTS (SELECT ID_CD,ID_LM FROM chuyen_de_unlock WHERE ID_CD='$cdID' AND ID_LM='$lmID')
            LIMIT 1");
            $query->execute();
        }

        public function lockChuyenDe($cdID,$lmID) {
            $query = $this->conn->prepare("DELETE FROM chuyen_de_unlock WHERE ID_CD='$cdID' AND ID_LM='$lmID'");
            $query->execute();
        }

        public function getChuyenDeById($cdID) {
            if(stripos($cdID, "-") === false && is_numeric($cdID)) {
                $query = $this->conn->prepare("SELECT * FROM chuyen_de_con WHERE ID_CD='$cdID'");
            } else {
                $maso = $cdID;
                $temp = explode("-",$maso);
                $maso = $temp[0];
                $query = $this->conn->prepare("SELECT * FROM chuyen_de_con WHERE maso='$maso'");
            }
            $query->execute();
            return $query->get_result();
        }
    }

    class Lop {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        function getLopHocSinh($hsID) {
            $query = $this->conn->prepare("SELECT lop FROM hocsinh WHERE ID_HS=?");
            $query->bind_param("s",$hsID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["lop"];
        }

        function getAllLop() {
            $query = $this->conn->prepare("SELECT * FROM lop ORDER BY ID_LOP ASC");
            $query->execute();
            return $query->get_result();
        }
    }

    class Mon_Hoc {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function getAllMon() {
            $query = $this->conn->prepare("SELECT * FROM mon ORDER BY ID_MON ASC");
            $query->execute();
            return $query->get_result();
        }

        public function getAllLopMon() {
            $query = $this->conn->prepare("SELECT * FROM lop_mon ORDER BY ID_LM ASC");
            $query->execute();
            return $query->get_result();
        }

        public function getAllLopMonHs($hsID) {
            $query = $this->conn->prepare("SELECT l.ID_LM,l.name FROM lop_mon AS l 
            INNER JOIN hocsinh_mon AS m ON m.ID_HS='$hsID' AND m.ID_LM=l.ID_LM
            WHERE l.ID_LM NOT IN (SELECT ID_LM FROM hocsinh_nghi WHERE ID_HS='$hsID')
            ORDER BY l.ID_LM ASC");
            $query->execute();
            return $query->get_result();
        }

        public function getNameMonLop($lmID) {
            $query = $this->conn->prepare("SELECT name FROM lop_mon WHERE ID_LM=?");
            $query->bind_param("s",$lmID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["name"];
        }

        public function getMonOfLop($lmID) {
            $query = $this->conn->prepare("SELECT ID_MON FROM lop_mon WHERE ID_LM=?");
            $query->bind_param("s",$lmID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["ID_MON"];
        }

        public function getUseCt($monID) {
            $query = $this->conn->prepare("SELECT ct FROM mon WHERE ID_MON=?");
            $query->bind_param("s",$monID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["ct"];
        }

        public function updateUseCt($monID,$is_use) {
            if($is_use) {
                $query = $this->conn->prepare("UPDATE mon SET ct='1' WHERE ID_MON=?");
            } else {
                $query = $this->conn->prepare("UPDATE mon SET ct='0' WHERE ID_MON=?");
            }
            $query->bind_param("s",$monID);
            $query->execute();
        }

        public function countChuyenDe($monID) {
            $query = $this->conn->prepare("SELECT COUNT(ID_DAD) AS dem FROM chuyen_de_dad WHERE ID_MON=?");
            $query->bind_param("s",$monID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"];
        }

        public function countCauHoi($lmID) {
            $query = $this->conn->prepare("SELECT COUNT(ID_C) AS dem FROM cau_hoi WHERE ID_LM=?");
            $query->bind_param("s",$lmID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"];
        }

        public function getMonFirst($hsID) {
            $query = $this->conn->prepare("SELECT ID_LM FROM hocsinh_mon WHERE ID_HS=? AND ID_LM NOT IN (SELECT ID_LM FROM hocsinh_nghi WHERE ID_HS='$hsID') ORDER BY ID_STT ASC LIMIT 1");
            $query->bind_param("s",$hsID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["ID_LM"];
        }

        public function getMonFirstAdmin() {
            $query = $this->conn->prepare("SELECT ID_MON FROM mon ORDER BY ID_MON ASC LIMIT 1");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["ID_MON"];
        }
    }

    class Nhom_Cau_Hoi {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function getNhomByMon($lmID) {
            $query = $this->conn->prepare("SELECT * FROM nhom WHERE ID_LM = ? ORDER BY ID_N DESC");
            $query->bind_param("s",$lmID);
            $query->execute();
            return $query->get_result();
        }

        public function addNhom($name, $lmID) {
            $query = $this->conn->prepare("INSERT INTO nhom(name,ID_LM)
                                                    value('$name','$lmID')");
            $query->execute();
            return $query->insert_id;
        }

        public function saiNhomDe($nID) {
            $query = $this->conn->prepare("UPDATE nhom_de SET is_sai='1' WHERE ID_N='$nID'");
            $query->execute();
        }

        public function kosaiNhomDe($nID) {
            $query = $this->conn->prepare("UPDATE nhom_de SET is_sai='0' WHERE ID_N='$nID'");
            $query->execute();
        }
    }

    class Cau_Hoi {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function delBinhLuan($cID = NULL, $blID = NULL) {
            if($cID) {
                $query = $this->conn->prepare("DELETE FROM binh_luan WHERE ID_C='$cID'");
                $query->execute();
            } else if($blID) {
                $query = $this->conn->prepare("DELETE FROM binh_luan WHERE ID_STT='$blID'");
                $query->execute();
            }
        }

        public function getDapAnByCauArr($cau_str) {
            if($cau_str == "") {
                $cau_str = "'0'";
            }
            $query = $this->conn->prepare("SELECT ID_DA,ID_C FROM dap_an_ngan WHERE ID_C IN ($cau_str) AND how='1' ORDER BY rand()");
            $query->execute();
            return $query->get_result();
        }

        public function getCauHoiByMain($maso) {
//            $temp = explode("-",$maso);
//            $temp2 = str_split($temp[2]);
//            $me = "";
//            for($i = 0; $i < count($temp2); $i++) {
//                if(is_numeric($temp2[$i]))
//                    $me .= $temp2[$i];
//                else break;
//            }
//            $pre_ma = $temp[0]."-".$temp[1]."-".$me;
            $query = $this->conn->prepare("SELECT c.*,d.content AS da_con,d.anh AS da_anh FROM cau_hoi AS c 
            INNER JOIN dap_an_dai AS d ON d.ID_C=c.ID_C
            WHERE c.maso='$maso'
            ORDER BY c.main DESC,c.ID_C ASC");
            $query->execute();
            return $query->get_result();
        }

        public function getDapAnByMain($maso) {
//            $temp = explode("-",$maso);
//            $temp2 = str_split($temp[2]);
//            $me = "";
//            for($i = 0; $i < count($temp2); $i++) {
//                if(is_numeric($temp2[$i]))
//                    $me .= $temp2[$i];
//                else break;
//            }
//            $pre_ma = $temp[0]."-".$temp[1]."-".$me;
            $query = $this->conn->prepare("SELECT d.* FROM dap_an_ngan AS d 
            INNER JOIN cau_hoi AS c ON c.ID_C=d.ID_C AND c.maso='$maso'
            ORDER BY c.main DESC,c.ID_C ASC");
            $query->execute();
            return $query->get_result();
        }

        public function formatDone($done) {
            if($done == 1) {
                return "Đã check";
            } else {
                return "Chưa check";
            }
        }

        public function getCauHoiByChuyenDe($maso) {
            $query = $this->conn->prepare("SELECT c.*,d.content AS da_con,d.anh AS da_anh FROM cau_hoi AS c
                INNER JOIN dap_an_dai AS d ON d.ID_C=c.ID_C
                WHERE c.maso LIKE '$maso%' AND c.maso LIKE '%-%-%a%'
                ORDER BY c.ID_C ASC");
            $query->execute();
            return $query->get_result();
        }

        public function xoaDapAn($daID){
            $check = false;
            $query = $this->conn->prepare("SELECT COUNT(ID_DA) AS dem FROM de_cau_dap_an WHERE ID_DA=?");
            $query->bind_param("s",$daID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            if($data["dem"] == 0) {
                $query = $this->conn->prepare("SELECT COUNT(ID_DA) AS dem FROM hoc_sinh_cau WHERE ID_DA=?");
                $query->bind_param("s",$daID);
                $query->execute();
                $result = $query->get_result();
                $data = $result->fetch_assoc();
                if($data["dem"] == 0) {
                    $query =  $this->conn->prepare("DELETE FROM dap_an_ngan WHERE ID_DA ='$daID'");
                    $query->execute();
                    $check = true;
                }
            }
            return $check;
        }

        public function xoaCauHoi($cID) {
            $check = false;
            $query = $this->conn->prepare("SELECT COUNT(e.ID_C) AS dem FROM de_noi_dung AS e
            INNER JOIN de_thi AS d ON d.ID_DE=e.ID_DE AND d.nhom!='0'  
            WHERE e.ID_C=?");
            $query->bind_param("s",$cID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            if($data["dem"] == 0) {
                $query = $this->conn->prepare("SELECT COUNT(c.ID_C) AS dem FROM hoc_sinh_cau AS c 
                INNER JOIN de_thi AS d ON d.ID_DE=c.ID_DE AND d.nhom!='0'
                WHERE c.ID_C=?");
                $query->bind_param("s",$cID);
                $query->execute();
                $result = $query->get_result();
                $data = $result->fetch_assoc();
                if($data["dem"] == 0) {
                    $query2 = $this->conn->prepare("DELETE FROM dap_an_ngan WHERE ID_C='$cID'");
                    $query2->execute();
                    $query2 = $this->conn->prepare("DELETE FROM dap_an_dai WHERE ID_C='$cID'");
                    $query2->execute();
                    $query2 = $this->conn->prepare("DELETE FROM cau_hoi WHERE ID_C='$cID'");
                    $query2->execute();
                    $check = true;
                }
            }
            return $check;
        }

        public function xoaCauHoiMulti($maso) {
            $query = $this->conn->prepare("DELETE FROM cau_hoi WHERE maso LIKE '$maso%'");
            $query->execute();
        }

        public function checkIssetCauHoi($maso) {
            $query = $this->conn->prepare("SELECT ID_C FROM cau_hoi WHERE maso=?");
            $query->bind_param("s",$maso);
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                $data = $result->fetch_assoc();
                return $data["ID_C"];
            } else {
                return 0;
            }
        }

        public function getCauHoiByDapAn($daID) {
            $query = $this->conn->prepare("SELECT ID_C FROM dap_an_ngan WHERE ID_DA=?");
            $query->bind_param("s",$daID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["ID_C"];
        }

        public function addCauHoi($maso, $content, $anh, $type_da, $cdID, $kho, $plID, $nhom, $note, $done, $monID, $main) {
            $content = addslashes($this->formatCT($content));
            $query = $this->conn->prepare("INSERT INTO cau_hoi(maso,content,anh,type_da,ID_CD,ID_K,ID_PL,ngay,nhom,note,ready,done,ID_MON,main)
                                                        value('$maso','$content','$anh','$type_da','$cdID','$kho','$plID',now(),'$nhom','$note','1','$done','$monID','$main')");
            $query->execute();
            return array($query->error,$query->insert_id);
        }

        public function addDapAnNgan($content, $type, $main, $cID, $how) {
            $content = addslashes($this->formatCT($content));
            $query = $this->conn->prepare("INSERT INTO dap_an_ngan(content,type,main,ID_C,num,how)
                                                            value('$content','$type','$main','$cID','0','$how')");
            $query->execute();
        }

        public function addDapAnDai($content, $anh, $cID) {
            $content = addslashes($this->formatCT($content));
            $query = $this->conn->prepare("INSERT INTO dap_an_dai(content,anh,ID_C)
                                                            value('$content','$anh','$cID')");
            $query->execute();
        }

        public function getCauHoiByMon($monID) {
            $query = $this->conn->prepare("SELECT c.* FROM cau_hoi AS c WHERE c.ID_MON = ? ORDER BY c.ID_C DESC");
            $query->bind_param("s",$monID);
            $query->execute();
            return $query->get_result();
        }

        public function getCauHoiById($cID) {
            $query = $this->conn->prepare("SELECT * FROM cau_hoi WHERE ID_C = ?");
            $query->bind_param("s",$cID);
            $query->execute();
            return $query->get_result();
        }

        public function getDapAnDai($cID) {
            $query = $this->conn->prepare("SELECT * FROM dap_an_dai WHERE ID_C = ?");
            $query->bind_param("s",$cID);
            $query->execute();
            return $query->get_result();
        }

        public function getDapAnNgan($cID, $is_rand) {
            if($is_rand) {
                $query = $this->conn->prepare("SELECT * FROM dap_an_ngan WHERE ID_C = ? AND how='1' ORDER BY rand()");
            } else {
                $query = $this->conn->prepare("SELECT * FROM dap_an_ngan WHERE ID_C = ? AND how='1' ORDER BY ID_DA ASC");
            }
            $query->bind_param("s",$cID);
            $query->execute();
            return $query->get_result();
        }

        public function getDapAnUnUse($cID) {
            $query = $this->conn->prepare("SELECT * FROM dap_an_ngan WHERE ID_C = ? AND how='0' ORDER BY content ASC");
            $query->bind_param("s",$cID);
            $query->execute();
            return $query->get_result();
        }

        public function getDapAnKoLam($cID) {
            $query = $this->conn->prepare("SELECT * FROM dap_an_ngan WHERE content='Em không làm được' AND ID_C=? AND how='0'");
            $query->bind_param("s",$cID);
            $query->execute();
            return $query->get_result();
        }

        public function getDapAnKhac($cID) {
            $query = $this->conn->prepare("SELECT * FROM dap_an_ngan WHERE content LIKE 'Đáp án khác%' AND ID_C=? AND how='0'");
            $query->bind_param("s",$cID);
            $query->execute();
            return $query->get_result();
        }

        public function getContentCau($cID) {
            $query = $this->conn->prepare("SELECT c.content AS con,a.content AS da_con FROM cau_hoi AS c
            INNER JOIN dap_an_dai AS a ON a.ID_C=c.ID_C
            WHERE c.ID_C='$cID'");
            $query->execute();
            return $query->get_result();
        }

        public function editContentCauHoi($cID, $content) {
            $content = addslashes($this->formatCT($content));
            $query = $this->conn->prepare("UPDATE cau_hoi SET content='$content' WHERE ID_C='$cID'");
            $query->execute();
            $query = $this->conn->prepare("SELECT content FROM cau_hoi WHERE ID_C='$cID'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["content"];
        }

        public function editContentDapAn($cID, $content) {
            $content = addslashes($this->formatCT($content));
            $query = $this->conn->prepare("UPDATE dap_an_dai SET content='$content' WHERE ID_C='$cID'");
            $query->execute();
            $query = $this->conn->prepare("SELECT content FROM dap_an_dai WHERE ID_C='$cID'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["content"];
        }

        public function editCauHoi($cID, $maso, $content, $anh, $type_da, $cdID, $kho, $plID, $nhom, $note, $check_de_anh, $monID, $main) {
            $content = addslashes($this->formatCT($content));
            if($anh != "none" || !$check_de_anh) {
                $query = $this->conn->prepare("UPDATE cau_hoi SET maso='$maso',content='$content',anh='$anh',type_da='$type_da',ID_CD='$cdID',ID_K='$kho',ID_PL='$plID',nhom='$nhom',note='$note',ID_MON='$monID',main='$main' WHERE ID_C=?");
            } else {
                $query = $this->conn->prepare("UPDATE cau_hoi SET maso='$maso',content='$content',type_da='$type_da',ID_CD='$cdID',ID_K='$kho',ID_PL='$plID',nhom='$nhom',note='$note',ID_MON='$monID',main='$main' WHERE ID_C=?");
            }
            $query->bind_param("s",$cID);
            $query->execute();
        }

        public function editDapAnDai($cID,$content,$anh, $check_da_anh) {
            $content = addslashes($this->formatCT($content));
            if($anh != "none" || !$check_da_anh) {
                $query = $this->conn->prepare("UPDATE dap_an_dai SET content='$content',anh='$anh' WHERE ID_C=?");
            } else {
                $query = $this->conn->prepare("UPDATE dap_an_dai SET content='$content' WHERE ID_C=?");
            }
            $query->bind_param("s",$cID);
            $query->execute();
        }

        public function editDapAnNgan($daID,$content,$type,$main) {
            $content = addslashes($this->formatCT($content));
            if($content == "none" && $type=="image") {
                $query = $this->conn->prepare("UPDATE dap_an_ngan SET main='$main' WHERE ID_DA=?");
            } else {
                $query = $this->conn->prepare("UPDATE dap_an_ngan SET content='$content',type='$type',main='$main' WHERE ID_DA=?");
            }
            $query->bind_param("s",$daID);
            $query->execute();
        }

        public function delDapAnNgan($daID) {
            $query = $this->conn->prepare("DELETE FROM dap_an_ngan WHERE ID_DA=?");
            $query->bind_param("s",$daID);
            $query->execute();
        }

        public function formatCT($content) {
            //$content = str_replace("$\\","\\(\\",$content);
            $content = str_replace("$", "\$", $content);
            $temp = explode("\$",$content);
            $pre = "";
            $n = count($temp);
            for($i=0;$i<$n;$i++) {
                if($i % 2 == 0) {
                    $pre .= $temp[$i] . "\\(";
                } else {
                    $pre .= $temp[$i] . "\\)";
                }
            }
            if($n % 2 != 0) {
                $pre = substr($pre,0,strlen($pre)-2);
            }
            $content = $pre;
            $content = str_replace("--","-",$content);
            $content = str_replace("\\["," \\(",$content);
            $content = str_replace("\\]","\\) ",$content);
            $content = str_replace("\\ ","\\",$content);
            $content = str_replace(array("display='block'","mml:","\\(\\)"),"",$content);
//            $content = str_replace("  "," ",$content);
            return $content;
        }
		
		public function formatCTOut($content) {
            $content = str_replace("\\(\\)","",$content);
            $content = str_replace(array("\\(","\\)"),"\$",$content);
            $content = str_replace("$$","",$content);
//            $content = str_replace("  "," ",$content);
            $content = str_replace("<br />","newline",$content);
//            $content = str_replace("<br/>","newline",$content);
//            $content = str_replace("<br>","newline",$content);
			
            return trim($content);
        }

        public function getUrlDe($monID, $content) {
            $img2 = "";
            $file_arr = array("upload/$monID/$content.jpg","upload/$monID/$content.png",
                "../upload/$monID/$content.jpg","../upload/$monID/$content.png");
            $newset = 0;
            foreach($file_arr as $file) {
                if(file_exists($file)) {
                    $timefile = filemtime($file);
                    if($newset < $timefile) {
                        $img2 = str_replace("../","",$file);
                        $newset = $timefile;
                    }
                }
            }
            if($img2 == "") {
                $img2 = "upload/placeholder_mini.jpg";
            }
            return $img2;
        }

        public function getUrlDapAn($monID, $content) {
//            return "upload/$lmID/dapan/$content";
            $img2 = "";
            $file_arr = array("upload/$monID/$content.jpg","upload/$monID/$content.png",
                "../upload/$monID/$content.jpg","../upload/$monID/$content.png");
            $newset = 0;
            foreach($file_arr as $file) {
                if(file_exists($file)) {
                    $timefile = filemtime($file);
                    if($newset < $timefile) {
                        $img2 = str_replace("../","",$file);
                        $newset = $timefile;
                    }
                }
            }
            if($img2 == "") {
                $img2 = "upload/placeholder_mini.jpg";
            }
            return $img2;
        }

        public function configCauHoi($cID,$action) {
            if($action) {
                $query = $this->conn->prepare("UPDATE cau_hoi SET ready='1' WHERE ID_C=?");
            } else {
                $query = $this->conn->prepare("UPDATE cau_hoi SET ready='0' WHERE ID_C=?");
            }
            $query->bind_param("s",$cID);
            $query->execute();
        }

        public function checkCauHoi($cID,$action) {
            if($action) {
                $query = $this->conn->prepare("UPDATE cau_hoi SET done='1' WHERE ID_C=?");
            } else {
                $query = $this->conn->prepare("DELETE FROM hoc_sinh_cau WHERE ID_C=? AND ID_DE='0'");
                $query->bind_param("s",$cID);
                $query->execute();
                $query = $this->conn->prepare("UPDATE cau_hoi SET done='0' WHERE ID_C=?");
            }
            $query->bind_param("s",$cID);
            $query->execute();
        }

        public function delDapAnNganByCau($cID) {
            $query = $this->conn->prepare("DELETE FROM dap_an_ngan WHERE ID_C=?");
            $query->bind_param("s",$cID);
            $query->execute();
        }

        public function getDapAnBySort($position, $cID) {
            $position--;
            $query = $this->conn->prepare("SELECT ID_DA FROM dap_an_ngan WHERE ID_C=? ORDER BY ID_DA ASC LIMIT $position,1");
            $query->bind_param("s",$cID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["ID_DA"];
        }

        public function getRandCauHoi($hsID,$limit,$lmID) {
            $query = $this->conn->prepare("SELECT * FROM cau_hoi WHERE ID_LM=? AND ready='1' ORDER BY rand() LIMIT $limit");
            $query->bind_param("s",$lmID);
            $query->execute();
            return $query->get_result();
        }

        public function getTestCauHoi($hsID,$limit,$lmID) {
            $date=getdate(date("U"));
            $current=$date["wday"]+1;
            if($current != 7) {
                $query = $this->conn->prepare("SELECT * FROM cau_hoi WHERE ID_LM=? AND ready='1' AND done='0' AND ID_C NOT IN (SELECT ID_C FROM hoc_sinh_cau WHERE ID_HS='$hsID' AND ID_DE='0') ORDER BY rand() LIMIT $limit");
            } else {
                $query = $this->conn->prepare("SELECT * FROM cau_hoi WHERE ID_LM=? AND ready='1' AND done='0' AND ID_C NOT IN (SELECT ID_C FROM hoc_sinh_cau WHERE ID_HS='$hsID' AND ID_DE='0') ORDER BY rand() DESC");
            }
            $query->bind_param("s",$lmID);
            $query->execute();
            return $query->get_result();
        }

        public function cauHoiSai($cID) {
            $query = $this->conn->prepare("UPDATE hoc_sinh_cau SET num='0',time='0' WHERE ID_C=?");
            $query->bind_param("s",$cID);
            $query->execute();
        }

        public function searchMaCauHoi($search) {
            $query = $this->conn->prepare("SELECT c.ID_C,e.sort,d.mota,d.ID_DE FROM cau_hoi AS c
                INNER JOIN de_noi_dung AS e ON e.ID_C=c.ID_C
                INNER JOIN de_thi AS d ON d.ID_DE=e.ID_DE AND d.main='1'
                WHERE c.maso LIKE '$search%'");
            $query->execute();
            return $query->get_result();
        }
    }

    class Thong_Ke {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function getCauHoiMostBinhLuan($monID) {
            $query = $this->conn->prepare("SELECT c.ID_C,c.maso,c.ready,c.done,COUNT(b.ID_STT) AS dem,(SELECT datetime FROM binh_luan WHERE ID_C=c.ID_C ORDER BY ID_STT DESC LIMIT 1) AS datetime FROM cau_hoi AS c
            INNER JOIN binh_luan AS b ON b.ID_C=c.ID_C
            WHERE c.ID_MON='$monID'
            GROUP BY c.ID_C
            ORDER BY b.ID_STT DESC LIMIT 10");
            $query->execute();
            return $query->get_result();
        }

        public function getDapAnMain($cID) {
            $query = $this->conn->prepare("SELECT ID_DA FROM dap_an_ngan WHERE ID_C=? AND main='1' LIMIT 1");
            $query->bind_param("s",$cID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["ID_DA"];
        }

        public function getNoteDapAnDe($daID,$deID,$limit) {
            $query = $this->conn->prepare("SELECT h.cmt,h.facebook,c.note FROM hoc_sinh_cau AS c 
            INNER JOIN hocsinh AS h ON h.ID_HS=c.ID_HS 
            WHERE c.ID_DA='$daID' AND c.ID_DE='$deID' AND c.note!='' ORDER BY c.ID_STT ASC LIMIT $limit");
            $query->execute();
            return $query->get_result();
        }

        public function getChonDapAn($daID) {
            $query = $this->conn->prepare("SELECT num FROM dap_an_ngan WHERE ID_DA=?");
            $query->bind_param("s",$daID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["num"];
        }

        public function getListChonDapAn($daID,$nhom) {
            if($nhom != 0) {
                $query = $this->conn->prepare("SELECT h.ID_HS,h.cmt,h.fullname,h.facebook,c.note,c.ID_DE FROM hoc_sinh_cau AS c INNER JOIN hocsinh AS h ON h.ID_HS=c.ID_HS WHERE c.ID_DA='$daID' AND c.ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom='$nhom') ORDER BY c.note ASC,h.cmt ASC");
            } else {
                $query = $this->conn->prepare("SELECT h.ID_HS,h.cmt,h.fullname,h.facebook,c.note,c.ID_DE FROM hoc_sinh_cau AS c INNER JOIN hocsinh AS h ON h.ID_HS=c.ID_HS WHERE c.ID_DA='$daID' ORDER BY c.note ASC,h.cmt ASC");
            }
            $query->execute();
            return $query->get_result();
        }

        public function getTimeTB($cID) {
            $query = $this->conn->prepare("SELECT AVG(time) AS tb FROM hoc_sinh_cau WHERE ID_C=? AND time!='0'");
            $query->bind_param("s",$cID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return formatDiem($data["tb"]/1000);
        }

        public function getTimeTBMax($cID) {
            $query = $this->conn->prepare("SELECT MAX(time) AS tb FROM hoc_sinh_cau WHERE ID_C=? AND time!='0'");
            $query->bind_param("s",$cID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["tb"]/1000;
        }

        public function getTimeTBMin($cID) {
            $query = $this->conn->prepare("SELECT MIN(time) AS tb FROM hoc_sinh_cau WHERE ID_C=? AND time!='0'");
            $query->bind_param("s",$cID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["tb"]/1000;
        }

        public function getHocSinhLamMin($cID) {
            $query = $this->conn->prepare("SELECT h.cmt FROM hoc_sinh_cau AS c INNER JOIN hocsinh AS h ON h.ID_HS=c.ID_HS WHERE c.ID_C=? AND c.time IN (SELECT MIN(time) AS tb FROM hoc_sinh_cau WHERE ID_C='$cID' AND time!='0')");
            $query->bind_param("s",$cID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return "$data[cmt]";
        }

        public function getHocSinhLamMax($cID) {
            $query = $this->conn->prepare("SELECT h.cmt FROM hoc_sinh_cau AS c INNER JOIN hocsinh AS h ON h.ID_HS=c.ID_HS WHERE c.ID_C=? AND c.time IN (SELECT MAX(time) AS tb FROM hoc_sinh_cau WHERE ID_C='$cID' AND time!='0')");
            $query->bind_param("s",$cID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return "$data[cmt]";
        }

        public function countHocSinhDangLam($nhom) {
            $query = $this->conn->prepare("SELECT COUNT(ID_STT) AS dem FROM hoc_sinh_de_in WHERE ID_N=? AND out_time='0000-00-00 00:00:00'");
            $query->bind_param("s",$nhom);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"];
        }

        public function countHocSinhDoneLam($nhom, $lmID) {
            $query = $this->conn->prepare("SELECT COUNT(ID_STT) AS dem FROM hoc_sinh_luyen_de 
            WHERE ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom='$nhom')
            AND ID_HS IN (SELECT ID_HS FROM hocsinh_mon WHERE ID_LM='$lmID')");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"];
        }

        public function countHocSinhChuaLam($nhom, $loai, $lmID) {
            $now = date("Y-m-d");
            $query = $this->conn->prepare("SELECT COUNT(m.ID_HS) AS dem FROM hocsinh_mon AS m
                WHERE m.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID')
                AND m.ID_HS NOT IN (SELECT ID_HS FROM nghi_temp WHERE ((start<='$now' AND end>='$now') OR (start<='$now' AND end='0000-00-00')) AND ID_LM='$lmID')
                AND m.ID_HS NOT IN (SELECT ID_HS FROM hoc_sinh_luyen_de WHERE ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom='$nhom') AND ID_LM='$lmID')
                AND m.de IN (SELECT name FROM loai_de WHERE ID_D='$loai') AND m.ID_LM='$lmID'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"];
        }

        public function countHocSinhAll($lmID) {
            $now = date("Y-m-d");
            $query = $this->conn->prepare("SELECT COUNT(ID_HS) AS dem FROM hocsinh_mon
                WHERE ID_LM='$lmID' AND ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID')
                AND ID_HS NOT IN (SELECT ID_HS FROM nghi_temp WHERE ((start<='$now' AND end>='$now') OR (start<='$now' AND end='0000-00-00')) AND ID_LM='$lmID')");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"];
        }

        public function giaHanLamDe($hsID, $deID, $phut) {
            $query = $this->conn->prepare("SELECT nhom,time FROM de_thi WHERE ID_DE='$deID'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            if($data["time"] >= $phut) {
                $time_pass = $data["time"] - $phut;
                $time_in = date("Y-m-d H:i:s", strtotime("-$time_pass minutes"));
                if ($data["nhom"] != 0) {
                    $query = $this->conn->prepare("DELETE FROM hoc_sinh_luyen_de WHERE ID_HS='$hsID' AND ID_DE='$deID'");
                    $query->execute();
                    $query = $this->conn->prepare("UPDATE hoc_sinh_de_in SET in_time='$time_in',out_time='0000-00-00 00:00:00' WHERE ID_HS='$hsID' AND ID_DE='$deID'");
                    $query->execute();
                    return true;
                } else if ($data["nhom"] == 0) {
                    $query = $this->conn->prepare("UPDATE hoc_sinh_tu_luyen SET diem='',time='',in_time='$time_in',out_time='0000-00-00 00:00:00' WHERE ID_HS='$hsID' AND ID_DE='$deID'");
                    $query->execute();
                    return true;
                }
                return false;
            } else {
                return false;
            }
        }

        public function cleanKetQuaDeThi($hsID,$deID,$is_lai) {
            $query = $this->conn->prepare("INSERT INTO log(ID_HS,content,type,datetime)
                                                            value('$hsID','$deID','lam-lai-de-thi',now())");
            $query->execute();
            $query = $this->conn->prepare("DELETE FROM hoc_sinh_de_in WHERE ID_HS='$hsID' AND ID_DE='$deID'");
            $query->execute();
            if($is_lai) {
                $query = $this->conn->prepare("DELETE FROM hoc_sinh_cau WHERE ID_HS='$hsID' AND ID_DE='$deID'");
                $query->execute();
                $query = $this->conn->prepare("SELECT * FROM hoc_sinh_luyen_de WHERE ID_HS='$hsID' AND ID_DE='$deID'");
                $query->execute();
                $result = $query->get_result();
                $data = $result->fetch_assoc();
                $query = $this->conn->prepare("INSERT INTO hoc_sinh_lam_lai(ID_DE,ID_HS,diem,time,datetime)
                                                                    value('$deID','$hsID','$data[diem]','$data[time]','$data[datetime]')");
                $query->execute();
            }
            $query = $this->conn->prepare("DELETE FROM hoc_sinh_luyen_de WHERE ID_HS='$hsID' AND ID_DE='$deID'");
            $query->execute();
        }

        public function cleanKetQuaNhomDe($hsID,$nID) {
            $query = $this->conn->prepare("SELECT d.ID_DE FROM hoc_sinh_luyen_de AS l
            INNER JOIN de_thi AS d ON d.ID_DE=l.ID_DE AND d.nhom='$nID'
            WHERE l.ID_HS='$hsID'");
            $query->execute();
            $result = $query->get_result();
            while($data = $result->fetch_assoc()) {
                $this->cleanKetQuaDeThi($hsID, $data["ID_DE"], false);
            }
        }
    }

    class Luyen_De {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function getKetQuaLuyenDeByNhomByHow($nhom, $how) {
            if($how == "in") {
                $query = $this->conn->prepare("SELECT l.ID_DE,l.ID_HS FROM hoc_sinh_de_in AS l
                INNER JOIN de_thi AS d ON d.ID_DE=l.ID_DE AND d.nhom='$nhom'
                WHERE l.out_time='0000-00-00 00:00:00'");
            } else if($how == "out") {
                $query = $this->conn->prepare("SELECT l.ID_DE,l.ID_HS FROM hoc_sinh_de_in AS l
                INNER JOIN de_thi AS d ON d.ID_DE=l.ID_DE AND d.nhom='$nhom'
                WHERE l.out_time!='0000-00-00 00:00:00'");
            } else {
                $query = $this->conn->prepare("SELECT l.ID_DE,l.ID_HS FROM hoc_sinh_de_in AS l
                INNER JOIN de_thi AS d ON d.ID_DE=l.ID_DE AND d.nhom='$nhom'");
            }
            $query->execute();
            return $query->get_result();
        }

        public function getKetQuaLuyenDeByNhom($nhom) {
            $query = $this->conn->prepare("SELECT l.ID_DE,l.ID_HS,l.diem,l.time,d.maso FROM hoc_sinh_luyen_de AS l
            INNER JOIN de_thi AS d ON d.ID_DE=l.ID_DE AND d.nhom='$nhom'");
            $query->execute();
            return $query->get_result();
        }

        public function countTotalTimeDung($deID, $hsID) {
            $query = $this->conn->prepare("SELECT SUM(h.time) AS dem FROM de_noi_dung AS d
            INNER JOIN hoc_sinh_cau AS h ON h.ID_HS='$hsID' AND h.ID_DE='$deID' AND h.ID_C=d.ID_C
            WHERE d.ID_DE='$deID'
            GROUP BY h.ID_HS");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"];
        }

        public function countNumCauDung($deID, $hsID) {
            $dem = 0;
            $query = $this->conn->prepare("SELECT COUNT(u.ID_DA) AS dem FROM hoc_sinh_cau AS u
            INNER JOIN cau_hoi AS c ON c.ID_C=u.ID_C AND c.done='1'
            INNER JOIN dap_an_ngan AS a ON a.ID_C=u.ID_C AND a.main='1' AND a.ID_DA=u.ID_DA
            WHERE u.ID_DE='$deID' AND u.ID_HS='$hsID'
            GROUP BY u.ID_HS");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            $dem += $data["dem"];
//            $query = $this->conn->prepare("SELECT COUNT(c.ID_C) AS dem FROM de_noi_dung AS d
//            INNER JOIN cau_hoi AS c ON c.ID_C=d.ID_C AND c.done='0'
//            WHERE d.ID_DE='$deID'");
//            $query->execute();
//            $result = $query->get_result();
//            $data = $result->fetch_assoc();
//            $dem += $data["dem"];
            return $dem;
        }

        public function insertNewLuyenDe($content) {
            $query = $this->conn->prepare("INSERT INTO hoc_sinh_luyen_de(ID_DE,ID_HS,type_de,diem,time,datetime,ID_LM) VALUES $content");
            $query->execute();
        }

        public function newLuyenDe($deID, $hsID, $type_de, $diem, $time, $lmID) {
            $diem = formatDiem($diem);
            $query = $this->conn->prepare("INSERT INTO hoc_sinh_luyen_de(ID_DE,ID_HS,type_de,diem,time,datetime,ID_LM) 
            SELECT * FROM (SELECT '$deID' AS de,'$hsID' AS hs,'$type_de' AS type,'$diem' AS diem,'$time' AS time,now() AS now,'$lmID' AS lm) AS tmp 
            WHERE NOT EXISTS (SELECT ID_DE,ID_HS FROM hoc_sinh_luyen_de WHERE ID_DE='$deID' AND ID_HS='$hsID' AND ID_LM='$lmID') 
            LIMIT 1");
            $query->execute();
        }

        public function getKetQuaLuyenDeByDe($deID, $hsID) {
            $query = $this->conn->prepare("SELECT l.diem,l.time,l.datetime,i.in_time,i.out_time FROM hoc_sinh_luyen_de AS l
            LEFT JOIN hoc_sinh_de_in AS i ON i.ID_HS=l.ID_HS AND i.ID_DE='$deID'
            WHERE l.ID_DE='$deID' AND l.ID_HS='$hsID' ORDER BY l.diem DESC");
            $query->execute();
            return $query->get_result();
        }

        public function updateLuyenDe($deID, $hsID, $diem, $time, $lmID) {
            $diem = formatDiem($diem);
            $query = $this->conn->prepare("UPDATE hoc_sinh_luyen_de SET diem='$diem',time='$time' WHERE ID_DE='$deID' AND ID_HS='$hsID' AND ID_LM='$lmID'");
            $query->execute();
        }

        public function updateLuyenDeBack($buoiID, $hsID, $diem, $made, $lmID) {
            $query = $this->conn->prepare("UPDATE diemkt SET diem='$diem' WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND made='$made' AND ID_LM='$lmID'");
            $query->execute();
        }

        public function getTuLuyenDeHocSinh($hsID, $lmID, $deID = null) {
            if($deID) {
                $query = $this->conn->prepare("SELECT t.* FROM hoc_sinh_tu_luyen AS t
                    WHERE t.ID_HS='$hsID' AND t.ID_DE='$deID'");
            } else {
                $query = $this->conn->prepare("SELECT t.*,d.mota FROM hoc_sinh_tu_luyen AS t
                    INNER JOIN de_thi AS d ON d.ID_DE=t.ID_DE AND d.ID_LM='$lmID'
                    WHERE t.ID_HS='$hsID'
                    ORDER BY t.ID_STT DESC");
            }
            $query->execute();
            return $query->get_result();
        }

        public function getLuyenDeHocSinhByMonType($hsID, $lmID, $type) {
            if($type == "X") {
                $query = $this->conn->prepare("SELECT t.ID_DE,t.maso,t.mota,t.nhom,d.diem,d.time,d.datetime FROM hoc_sinh_luyen_de AS d 
                    INNER JOIN de_thi AS t ON t.ID_DE=d.ID_DE
                    INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_LM='$lmID' WHERE d.ID_HS='$hsID' AND d.ID_LM=? ORDER BY d.datetime DESC");
            } else {
                $query = $this->conn->prepare("SELECT t.ID_DE,t.maso,t.mota,t.nhom,d.diem,d.time,d.datetime,n.object FROM hoc_sinh_luyen_de AS d 
                    INNER JOIN de_thi AS t ON t.ID_DE=d.ID_DE
                    INNER JOIN nhom_de AS n ON n.ID_N=t.nhom AND n.type='$type'
                    INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_LM='$lmID' WHERE d.ID_HS='$hsID' AND d.ID_LM=? ORDER BY d.datetime DESC");
            }
            $query->bind_param("s",$lmID);
            $query->execute();
            return $query->get_result();
        }

        public function getLuyenDeByMonType($lmID, $type) {
            if($type == "X") {
                $query = $this->conn->prepare("SELECT h.cmt,h.fullname,d.* FROM hoc_sinh_luyen_de AS d 
                    INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS 
                    INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_LM='$lmID' WHERE d.ID_LM=? ORDER BY d.datetime DESC");
            } else {
                $query = $this->conn->prepare("SELECT h.cmt,h.fullname,d.* FROM hoc_sinh_luyen_de AS d 
                    INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS 
                    INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_LM='$lmID' WHERE d.type_de='$type' AND d.ID_LM=? ORDER BY d.datetime DESC");
            }
            $query->bind_param("s",$lmID);
            $query->execute();
            return $query->get_result();
        }

        public function getKetQuaLuyenDe($nhom) {
            $query = $this->conn->prepare("SELECT h.ID_HS,h.cmt,h.fullname,h.facebook,d.ID_DE,d.diem,d.time,d.datetime,i.in_time,i.out_time FROM hoc_sinh_luyen_de AS d
                INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS
                LEFT JOIN hoc_sinh_de_in AS i ON i.ID_HS=d.ID_HS AND i.ID_DE=d.ID_DE
                WHERE d.ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom='$nhom')
                ORDER BY d.diem DESC");
            $query->execute();
            return $query->get_result();
        }

        public function getKetQuaLuyenDeKhoangDiem($nhom, $a, $b) {
            $query = $this->conn->prepare("SELECT h.ID_HS,h.cmt,h.fullname,h.facebook,d.ID_DE,d.diem,d.time,d.datetime FROM hoc_sinh_luyen_de AS d
                INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS
                WHERE d.ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom='$nhom') AND d.diem >= '$a' AND d.diem <= '$b'
                ORDER BY d.datetime DESC");
            $query->execute();
            return $query->get_result();
        }

        public function getListHocSinhDangLam($nhom) {
            $query = $this->conn->prepare("SELECT h.ID_HS,h.cmt,h.fullname,h.facebook,d.in_time FROM hocsinh AS h
            INNER JOIN hoc_sinh_de_in AS d ON d.ID_HS=h.ID_HS AND d.ID_N='$nhom' AND out_time='0000-00-00 00:00:00'
            ORDER BY d.in_time DESC");
            $query->execute();
            return $query->get_result();
        }

        public function getListHocSinhChuaLam($nhom, $loai, $lmID) {
            $now = date("Y-m-d");
            $query = $this->conn->prepare("SELECT name FROM loai_de WHERE ID_D='$loai'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            if($data["name"] == "B" || $data["name"] == "G" || $data["name"] == "Y") {
                $query = $this->conn->prepare("SELECT h.ID_HS,h.cmt,h.fullname,h.facebook,s.ID_STT FROM hocsinh AS h
                LEFT JOIN hoc_sinh_special AS s ON s.ID_HS=h.ID_HS AND s.ID_N='$nhom'
                WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID')
                AND h.ID_HS NOT IN (SELECT ID_HS FROM nghi_temp WHERE ((start<='$now' AND end>='$now') OR (start<='$now' AND end='0000-00-00')) AND ID_LM='$lmID')
                AND h.ID_HS NOT IN (SELECT ID_HS FROM hoc_sinh_luyen_de WHERE ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom='$nhom') AND ID_LM='$lmID')
                AND h.ID_HS IN (SELECT ID_HS FROM hocsinh_mon WHERE de='$data[name]' AND ID_LM='$lmID')
                ORDER BY h.cmt ASC");
            } else {
                $query = $this->conn->prepare("SELECT h.ID_HS,h.cmt,h.fullname,h.facebook,s.ID_STT FROM hocsinh AS h
                LEFT JOIN hoc_sinh_special AS s ON s.ID_HS=h.ID_HS AND s.ID_N='$nhom'
                WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID')
                AND h.ID_HS NOT IN (SELECT ID_HS FROM nghi_temp WHERE ((start<='$now' AND end>='$now') OR (start<='$now' AND end='0000-00-00')) AND ID_LM='$lmID')
                AND h.ID_HS NOT IN (SELECT ID_HS FROM hoc_sinh_luyen_de WHERE ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom='$nhom') AND ID_LM='$lmID')
                AND h.ID_HS IN (SELECT ID_HS FROM hocsinh_mon WHERE ID_LM='$lmID')
                ORDER BY h.cmt ASC");
            }
            $query->execute();
            return $query->get_result();
        }

        public function countSoLanLam($hsID, $nhom) {
            $query = $this->conn->prepare("SELECT COUNT(l.ID_STT) AS dem FROM hoc_sinh_lam_lai AS l 
            INNER JOIN de_thi AS d ON d.ID_DE=l.ID_DE AND d.nhom='$nhom'
            WHERE l.ID_HS='$hsID'
            GROUP BY l.ID_HS");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"]+1;
        }

        public function getLamLaiByHocSinh($hsID, $nhom) {
            $query = $this->conn->prepare("SELECT diem,time,datetime FROM hoc_sinh_lam_lai WHERE ID_HS='$hsID' AND ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom='$nhom') ORDER BY ID_STT ASC");
            $query->execute();
            return $query->get_result();
        }
    }

    class Binh_Luan {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function addBinhLuan($cID, $hsID, $content, $type) {
            $query = $this->conn->prepare("INSERT INTO binh_luan(ID_HS,ID_C,content,type,datetime)
                SELECT * FROM (SELECT '$hsID' AS hs,'$cID' AS cID,'$content' AS con,'$type' AS tycontent,now() AS now) AS tmp
                WHERE NOT EXISTS (SELECT ID_STT FROM binh_luan WHERE ID_HS='$hsID' AND ID_C='$cID' AND content='$content' AND type='$type')
                LIMIT 1");
            $query->execute();
        }

        public function getBinhLuanCau($cID,$monID,$limit) {
            $query = $this->conn->prepare("SELECT b.ID_HS,b.content,b.type,b.datetime,h.cmt,h.fullname,h.facebook FROM binh_luan AS b 
                INNER JOIN hocsinh AS h ON h.ID_HS=b.ID_HS 
                INNER JOIN hocsinh_mon AS m ON m.ID_HS=b.ID_HS AND m.ID_LM IN (SELECT ID_LM FROM lop_mon WHERE ID_MON='$monID')
                WHERE b.ID_C=? ORDER BY b.datetime DESC LIMIT $limit");
            $query->bind_param("s",$cID);
            $query->execute();
            return $query->get_result();
        }

        public function getBinhLuanCauMe($hsID,$cID,$lmID) {
            $query = $this->conn->prepare("SELECT b.ID_HS,b.content,b.type,b.datetime,h.cmt,h.fullname FROM binh_luan AS b 
                INNER JOIN hocsinh AS h ON h.ID_HS=b.ID_HS 
                INNER JOIN hocsinh_mon AS m ON m.ID_HS=b.ID_HS AND m.ID_LM='$lmID' 
                WHERE b.ID_HS='$hsID' AND b.ID_C=? ORDER BY b.datetime DESC");
            $query->bind_param("s",$cID);
            $query->execute();
            return $query->get_result();
        }
    }

    class Hoc_Sinh {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function checkHocSinhCode($hsID, $cmt) {
            $query = $this->conn->prepare("SELECT password FROM hocsinh WHERE ID_HS='$hsID' AND cmt='$cmt'");
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                $data = $result->fetch_assoc();
                return $data["password"];
            } else {
                return NULL;
            }
        }

        public function getHocSinhDateIn($hsID, $lmID) {
            $query = $this->conn->prepare("SELECT date_in FROM hocsinh_mon WHERE ID_HS='$hsID' AND ID_LM='$lmID'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["date_in"];
        }

        public function login($data) {
            $temp_code=md5("1241996");
            setcookie("my_monbig","",time() - 86400,"/");
            setcookie("my_mon", "", time() - 86400, "/");
            setcookie("is_ct", "", time() - 86400, "/");
            setcookie("my_id", "", time() - 86400, "/");
            setcookie("my_code", "", time() - 86400, "/");
            setcookie("is_app", "",time() - 86400,"/");
            session_destroy();
            session_start();
            $code = randPass(16);
            $db2 = new Mon_Hoc();
            if(!isset($data["ID_LM"])) {
                $lmID = $db2->getMonFirst($data["ID_HS"]);
            } else {
                $lmID = $data["ID_LM"];
            }
            $monID = $db2->getMonOfLop($lmID);
            $is_ct = $db2->getUseCt($monID);
            $_SESSION["my_monbig"] = $monID;
            $_SESSION["my_mon"] = $lmID;
            $_SESSION["is_ct"] = $is_ct;
            $_SESSION["my_id"] = $data["ID_HS"];
            $_SESSION["my_code"] = $code;
            $_SESSION["is_app"] = false;
            $_SESSION["is_first"] = true;

            if(isset($_COOKIE["cmt"])) {
                if($_COOKIE["cmt"] != encodeData($data["cmt"],$temp_code)) {
                    $cmt = decodeData($_COOKIE["cmt"],$temp_code);
                    $this->addLog($data["ID_HS"], "$data[cmt] - $data[fullname] đã được đăng nhập vào trang Trắc nghiệm từ tài khoản $cmt, IP: ".$_SERVER["REMOTE_ADDR"],"login");
                } else {
                    $this->addLog($data["ID_HS"], "$data[cmt] - $data[fullname] đã đăng nhập vào trang Trắc nghiệm", "login");
                }
            } else {
                $this->addLog($data["ID_HS"], "$data[cmt] - $data[fullname] đã đăng nhập vào trang Trắc nghiệm", "login");
            }
            setcookie("cmt", "", time() - 86400*30, "/");
            setcookie("cmt", encodeData($data["cmt"],$temp_code), time() + 86400*30,"/");

            setcookie("my_monbig",$monID,time() + 86400,"/");
            setcookie("my_mon",$lmID,time() + 86400,"/");
            setcookie("is_ct",$is_ct,time() + 86400,"/");
            setcookie("my_id",$data["ID_HS"],time() + 86400,"/");
            setcookie("my_code",$code,time() + 86400,"/");
            setcookie("is_app",false,time() + 86400,"/");
        }

        public function checkOptions($content, $type, $note, $note2) {
            $query = $this->conn->prepare("SELECT ID_O FROM options WHERE content='$content' AND type='$type' AND note='$note' AND note2='$note2'");
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                return true;
            } else {
                return false;
            }
        }

        public function getAllHocSinh($lmID) {
            $query = $this->conn->prepare("SELECT h.ID_HS FROM hocsinh AS h
            INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID'
            WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID')");
            $query->execute();
            return $query->get_result();
        }

        public function getCheckHocSinh($hsID, $lmID) {
            $query = $this->conn->prepare("SELECT ID_STT FROM hocsinh_mon WHERE ID_HS='$hsID' AND ID_LM='$lmID'");
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                return true;
            } else {
                return false;
            }
        }

        public function addThongBao($content) {
            $query = $this->conn->prepare("INSERT INTO thongbao(ID_HS,object,content,danhmuc,ID_LM,datetime,loai,status) VALUES $content");
            $query->execute();
        }

        public function addLog($hsID, $content, $type) {
            $query = $this->conn->prepare("INSERT INTO log(ID_HS,content,type,datetime)
                                                    value('$hsID','$content','$type',now())");
            $query->execute();
        }

        public function delHocSinhSpecial($sttID) {
            $query = $this->conn->prepare("DELETE FROM hoc_sinh_special WHERE ID_STT='$sttID'");
            $query->execute();
        }

        public function addHocSinhSpecial($hsID, $nhom) {
            $query = $this->conn->prepare("INSERT INTO hoc_sinh_special(ID_HS,ID_N)
                                                                value('$hsID','$nhom')");
            $query->execute();
            return $query->insert_id;
        }

        public function checkTruTien($hsID, $string, $object) {
            $query = $this->conn->prepare("SELECT ID_VAO FROM tien_vao WHERE ID_HS='$hsID' AND string='$string' AND object='$object'");
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                return true;
            } else {
                return false;
            }
        }

        public function checkHocSinhSpecial($hsID, $nID) {
            $query = $this->conn->prepare("SELECT ID_STT FROM hoc_sinh_special WHERE ID_HS='$hsID' AND ID_N='$nID'");
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                return true;
            } else {
                return false;
            }
        }

        public function getHocSinhDetail($hsID) {
            $query = $this->conn->prepare("SELECT cmt,fullname,avata,birth,gender,facebook,truong,taikhoan,lop FROM hocsinh WHERE ID_HS=?");
            $query->bind_param("s",$hsID);
            $query->execute();
            return $query->get_result();
        }

        public function checkHocSinhDetail($cmt,$pass) {
            $query = $this->conn->prepare("SELECT ID_HS,cmt,fullname,avata,birth,gender,truong,taikhoan,lop FROM hocsinh WHERE cmt=? AND password='$pass'");
            $query->bind_param("s",$cmt);
            $query->execute();
            return $query->get_result();
        }

        public function checkAccessMon($hsID, $lmID) {
            $query = $this->conn->prepare("SELECT ID_STT FROM hocsinh_mon WHERE ID_HS='$hsID' AND ID_LM='$lmID'");
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                return true;
            } else {
                return false;
            }
        }

        public function checkNghiMon($hsID, $lmID) {
            $query = $this->conn->prepare("SELECT ID_N FROM hocsinh_nghi WHERE ID_HS='$hsID' AND ID_LM='$lmID'");
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                return true;
            } else {
                return false;
            }
        }

        public function getDeOfHocSinh($hsID, $lmID) {
            $query = $this->conn->prepare("SELECT de FROM hocsinh_mon WHERE ID_HS='$hsID' AND ID_LM='$lmID'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["de"];
        }

        public function insertHocSinhLock($content) {
            $query = $this->conn->prepare("INSERT INTO hoc_sinh_lock(ID_HS,ID_LM,date_lock,mota) VALUES $content");
            $query->execute();
        }

        public function lockHocSinh($hsID) {
            $num = $this->getHocSinhLock($hsID) + 1;
            $query = $this->conn->prepare("UPDATE hocsinh SET lock_times='$num' WHERE ID_HS='$hsID'");
            $query->execute();
        }

        public function unlockHocSinh($hsID,$lmID) {
            $query = $this->conn->prepare("DELETE FROM hoc_sinh_lock WHERE ID_HS='$hsID' AND ID_LM='$lmID'");
            $query->execute();
        }

        public function getHocSinhLock($hsID) {
            $query = $this->conn->prepare("SELECT lock_times FROM hocsinh WHERE ID_HS='$hsID'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["lock_times"];
        }

        public function getListHocSinhKhoa($lmID) {
            $query = $this->conn->prepare("SELECT h.ID_HS,h.cmt,h.fullname,h.facebook,l.mota,l.date_lock FROM hoc_sinh_lock AS l
                INNER JOIN hocsinh AS h ON h.ID_HS=l.ID_HS
                WHERE l.ID_LM='$lmID' ORDER BY h.cmt ASC");
            $query->execute();
            return $query->get_result();
        }

        public function checkHocSinhKhoa($hsID) {
            $query = $this->conn->prepare("SELECT h.*,l.name FROM hoc_sinh_lock AS h 
                INNER JOIN lop_mon AS l ON l.ID_LM=h.ID_LM
                WHERE h.ID_HS='$hsID'");
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                return $result;
            } else {
                return false;
            }
        }

        public function checkSdtPhuHuynh($sdt, $cmt) {
            $query = $this->conn->prepare("SELECT ID_HS FROM hocsinh WHERE cmt='$cmt' AND (sdt_bo='$sdt' OR sdt_me='$sdt')");
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                return true;
            } else {
                return false;
            }
        }

        public function getTienHocSinh($hsID) {
            $query = $this->conn->prepare("SELECT taikhoan FROM hocsinh WHERE ID_HS='$hsID'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["taikhoan"];
        }

        public function updateTienHocSinh($hsID, $tien) {
            $query = $this->conn->prepare("UPDATE hocsinh SET taikhoan='$tien' WHERE ID_HS='$hsID'");
            $query->execute();
        }

        public function truTienHocSinh($hsID, $money, $note, $string, $object) {
            $query = $this->conn->prepare("INSERT INTO tien_vao(ID_HS,price,note,string,object,date,date_dong)
								                        value('$hsID','$money','$note','$string','$object',now(),now())");
            $query->execute();

            $tien = $this->getTienHocSinh($hsID);
            $con = $tien - $money;
            $this->updateTienHocSinh($hsID, $con);
        }
    }

    class Test_De {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function checkTestDe($hsID,$time,$lmID) {
            $query = $this->conn->prepare("SELECT ID_STT FROM test_de WHERE ID_HS='$hsID' AND date='$time' AND ID_LM='$lmID'");
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                return true;
            } else {
                return false;
            }
        }

        public function checkInTestDe($hsID,$time,$lmID) {
            $query = $this->conn->prepare("SELECT ID_STT,in_time FROM test_de WHERE ID_HS='$hsID' AND date='$time' AND ID_LM='$lmID'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            if($data["in_time"] != "0000-00-00 00:00:00") {
                return true;
            } else {
                return false;
            }
        }

        public function addNewTest($hsID,$lmID) {
            $query = $this->conn->prepare("INSERT INTO test_de(ID_HS,date,ID_LM)
                                                        value('$hsID',now(),'$lmID')");
            $query->execute();
        }

        public function updateTestDe($hsID,$lmID,$how,$time) {
            if($how == "in") {
                $query = $this->conn->prepare("UPDATE test_de SET in_time=now() WHERE ID_HS='$hsID' AND date='$time' AND ID_LM='$lmID'");
            } else {
                $query = $this->conn->prepare("UPDATE test_de SET out_time=now() WHERE ID_HS='$hsID' AND date='$time' AND ID_LM='$lmID'");
            }
            $query->execute();
        }
    }

    class Admin {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function getCheckAdminPass($cmt, $password) {
            $query = $this->conn->prepare("SELECT * FROM admin WHERE username=? AND password='$password' AND (level='1' OR level='2')");
            $query->bind_param("s",$cmt);
            $query->execute();
            return $query->get_result();
        }

        public function getCheckAdmin($id,$monID) {
            $query = $this->conn->prepare("SELECT * FROM admin WHERE ID='$id' AND ((level='1' AND note='boss') OR (level='2' AND note='$monID'))");
            $query->execute();
            return $query->get_result();
        }

        public function checkAdmin($id, $username) {
            $query = $this->conn->prepare("SELECT * FROM admin WHERE ID='$id' AND username='$username' AND level='1' AND note='boss'");
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                return true;
            } else {
                return false;
            }
        }

        public function getMonAdmin($id) {
            $query = $this->conn->prepare("SELECT note FROM admin WHERE ID=?");
            $query->bind_param("s",$id);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            if($data["note"] == "boss") {
                $db = new Mon_Hoc();
                return $db->getMonFirstAdmin();
            } else {
                return $data["note"];
            }
        }
    }

    class Log {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function ghiLog($id,$content,$type) {
            $query = $this->conn->prepare("INSERT INTO acc_log(ID_AC,content,type,datetime)
                                                            value('$id','$content','$type',now())");
            $query->execute();
        }

        public function cleanResult($result) {
            $result->free_result();
        }
    }

    class Options {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function getOptions($oID) {
            $query = $this->conn->prepare("SELECT * FROM options WHERE ID_O='$oID'");
            $query->execute();
            return $query->get_result();
        }

        public function addOptions($content, $type, $note, $note2) {
            $query = $this->conn->prepare("INSERT INTO options(content,type,note,note2) 
                SELECT * FROM (SELECT '$content' AS buoi,'$type' AS type,'$note' AS note,'$note2' AS note2) AS tmp 
                WHERE NOT EXISTS (SELECT ID_O FROM options WHERE content='$content' AND type='$type' AND note='$note' AND note2='$note2') LIMIT 1");
            $query->execute();
        }

        public function delOptions($oID) {
            $query = $this->conn->prepare("DELETE FROM options WHERE ID_O='$oID'");
            $query->execute();
        }
    }

    class Loai_De {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function getLoaiDe() {
            $query = $this->conn->prepare("SELECT * FROM loai_de ORDER BY ID_D ASC");
            $query->execute();
            return $query->get_result();
        }

        public function getLoaiDeById($loai) {
            $query = $this->conn->prepare("SELECT * FROM loai_de WHERE ID_D='$loai' OR name='$loai'");
            $query->execute();
            return $query->get_result();
        }

        public function getNameLoaiDeById($loai) {
            $query = $this->conn->prepare("SELECT name FROM loai_de WHERE ID_D=?");
            $query->bind_param("s",$loai);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["name"];
        }

        public function getMotaWithLoaiDe($lmID) {
            $query = $this->conn->prepare("SELECT l.*,m.content FROM loai_de AS l LEFT JOIN mo_ta_de AS m ON m.ID_D=l.ID_D AND m.ID_LM='$lmID' ORDER BY l.ID_D ASC,m.ID_MT ASC");
            $query->execute();
            return $query->get_result();
        }

        public function addMotaDe($content, $dID, $lmID) {
            $query = $this->conn->prepare("SELECT ID_MT FROM mo_ta_de WHERE ID_D='$dID' AND ID_LM='$lmID'");
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                $data = $result->fetch_assoc();
                $query2 = $this->conn->prepare("UPDATE mo_ta_de SET content='$content' WHERE ID_MT='$data[ID_MT]'");
                $query2->execute();
            } else {
                $query2 = $this->conn->prepare("INSERT INTO mo_ta_de(content,ID_D,ID_LM)
                                                                value('$content','$dID','$lmID')");
                $query2->execute();
            }
        }

        public function getMotaDe($dID, $lmID) {
            $query = $this->conn->prepare("SELECT content FROM mo_ta_de WHERE ID_D='$dID' AND ID_LM='$lmID'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["content"];
        }

        public function addLoaiDe($name,$mota) {
            $query = $this->conn->prepare("INSERT INTO loai_de(name,mota)
                                                        value('$name','$mota')");
            $query->execute();
        }

        public function editLoaiDe($name,$mota,$dID) {
            $query = $this->conn->prepare("UPDATE loai_de SET name='$name',mota='$mota' WHERE ID_D='$dID'");
            $query->execute();
        }

        public function delLoaiDe($dID) {
            $query = $this->conn->prepare("DELETE FROM loai_de WHERE ID_D='$dID'");
            $query->execute();
        }
    }

    class De_Thi {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function getYeuCauXemLai($type, $lmID) {
            $query = $this->conn->prepare("SELECT o.ID_O,o.content,o.note,d.mota,d.ID_DE,h.ID_HS,h.cmt,h.facebook FROM options AS o 
            INNER JOIN de_thi AS d ON d.ID_LM='$lmID' AND d.ID_DE=o.note2
            INNER JOIN hocsinh AS h ON h.ID_HS=o.note
            WHERE o.type='$type'
            ORDER BY o.ID_O DESC LIMIT 10");
            $query->execute();
            return $query->get_result();
        }

        public function lamDeShare($name, $diem, $time, $deID) {
            $query = $this->conn->prepare("SELECT * FROM options WHERE type='lam-de-chia-se-$deID' AND content='$name'");
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                $data = $result->fetch_assoc();
                $query2 = $this->conn->prepare("UPDATE options SET note='" . formatDiem($diem) . "',note2='$time' WHERE ID_O='$data[ID_O]'");
                $query2->execute();
                return $data["ID_O"];
            } else {
                $query2 = $this->conn->prepare("INSERT INTO options(content,type,note,note2)
                                                            VALUES('$name','lam-de-chia-se-$deID','0','0')");
                $query2->execute();
                return $query2->insert_id;
            }
        }

        public function getLinkShare($nID) {
            $query = $this->conn->prepare("SELECT * FROM options WHERE type='chia-se-de' AND note='$nID'");
            $query->execute();
            return $query->get_result();
        }

        public function shareDeThi($nID) {
            $date = date("Y-m-d H:i:s");
            $query = $this->conn->prepare("DELETE FROM options WHERE type='chia-se-de' AND note='$nID'");
            $query->execute();
            $deID = $this->getRandDeThiByNhom($nID, NULL);
            $link = "http://localhost/www/TDUONG/luyenthi/xem-truoc-share/".encodeData($deID, md5("1241996"))."/".encodeData($nID, md5("1241996"))."/";
            $query = $this->conn->prepare("INSERT INTO options(content,type,note,note2)
                                                    VALUES('$link','chia-se-de','$nID','$date')");
            $query->execute();
            return $link;
        }

        public function getNhomDeThiAllow($lmID, $type) {
            if($type == "X") {
                $query = $this->conn->prepare("SELECT n.ID_N,d.ID_DE,d.mota,d.ngay FROM nhom_de AS n 
                INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
                WHERE n.allow='1' AND n.public!='0' AND n.ID_LM='$lmID'
                ORDER BY n.type ASC,d.mota ASC");
            } else if(validId($type)) {
                $query = $this->conn->prepare("SELECT n.ID_N,d.ID_DE,d.mota,d.ngay FROM nhom_de AS n 
                INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
                INNER JOIN de_noi_dung AS e ON e.ID_DE=d.ID_DE
                INNER JOIN cau_hoi AS c ON c.ID_CD='$type' AND c.ID_C=e.ID_C
                WHERE n.allow='1' AND n.public!='0' AND n.ID_LM='$lmID' AND n.type='chuyen-de'
                GROUP BY d.ID_DE
                ORDER BY d.mota ASC,n.ID_N DESC");
            } else {
                $query = $this->conn->prepare("SELECT n.ID_N,d.ID_DE,d.mota,d.ngay FROM nhom_de AS n 
                INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
                WHERE n.allow='1' AND n.public!='0' AND n.ID_LM='$lmID' AND n.type='$type'
                ORDER BY d.mota ASC,n.ID_N DESC");
            }
            $query->execute();
            return $query->get_result();
        }

        public function getDapAnNganByDeAllUse($deID, $is_rand) {
            if($is_rand) {
                $query = $this->conn->prepare("SELECT a.ID_C,d.ID_DA FROM de_cau_dap_an AS d
                INNER JOIN dap_an_ngan AS a ON a.ID_DA=d.ID_DA AND a.how='1'
                WHERE d.ID_DE='$deID'
                ORDER BY rand()");
            } else {
                $query = $this->conn->prepare("SELECT a.ID_C,a.content,a.type,d.sort FROM de_cau_dap_an AS d
                INNER JOIN dap_an_ngan AS a ON a.ID_DA=d.ID_DA AND a.how='1'
                WHERE d.ID_DE='$deID' 
                ORDER BY a.ID_C ASC,d.sort ASC");
            }
            $query->execute();
            return $query->get_result();
        }

        public function deleteCauHoiInDe($cID, $deID) {
            $query = $this->conn->prepare("DELETE FROM de_noi_dung WHERE ID_DE='$deID' AND ID_C='$cID'");
            $query->execute();
            $query = $this->conn->prepare("DELETE FROM de_cau_dap_an WHERE ID_DE='$deID' AND ID_DA IN (SELECT ID_DA FROM dap_an_ngan WHERE ID_C='$cID')");
            $query->execute();
            $query = $this->conn->prepare("SELECT ID_ND FROM de_noi_dung WHERE ID_DE='$deID' ORDER BY sort ASC");
            $query->execute();
            $result = $query->get_result();
            $stt = 1;
            while($data = $result->fetch_assoc()) {
                $query2 = $this->conn->prepare("UPDATE de_noi_dung SET sort='$stt' WHERE ID_ND='$data[ID_ND]'");
                $query2->execute();
                $stt++;
            }
        }

        public function deleteCauHoiInDeMulti($content, $deID) {
            $query = $this->conn->prepare("DELETE FROM de_noi_dung WHERE ID_DE='$deID' AND ID_C IN ($content)");
            $query->execute();
            $query = $this->conn->prepare("DELETE FROM de_cau_dap_an WHERE ID_DE='$deID' AND ID_DA IN (SELECT ID_DA FROM dap_an_ngan WHERE ID_C IN ($content))");
            $query->execute();
            $query = $this->conn->prepare("SELECT ID_ND FROM de_noi_dung WHERE ID_DE='$deID' ORDER BY sort ASC");
            $query->execute();
            $result = $query->get_result();
            $stt = 1;
            while($data = $result->fetch_assoc()) {
                $query2 = $this->conn->prepare("UPDATE de_noi_dung SET sort='$stt' WHERE ID_ND='$data[ID_ND]'");
                $query2->execute();
                $stt++;
            }
        }

        public function addHocSinhDapAnMulti($hsID, $deID, $content, $reset = true) {
            if($reset) {
                $query = $this->conn->prepare("DELETE FROM hoc_sinh_cau WHERE ID_HS='$hsID' AND ID_DE='$deID'");
                $query->execute();
            }
            $query = $this->conn->prepare("INSERT INTO hoc_sinh_cau(ID_HS,ID_C,ID_DA,num,time,note,ID_DE) VALUES $content");
            $query->execute();
        }

        public function xoaDeThi($deID) {
            $query = $this->conn->prepare("DELETE FROM de_noi_dung WHERE ID_DE='$deID'");
            $query->execute();
//            $query = $this->conn->prepare("DELETE FROM hoc_sinh_cau WHERE ID_DE='$deID'");
//            $query->execute();
//            $query = $this->conn->prepare("DELETE FROM hoc_sinh_de_in WHERE ID_DE='$deID'");
//            $query->execute();
//            $query = $this->conn->prepare("DELETE FROM hoc_sinh_lam_lai WHERE ID_DE='$deID'");
//            $query->execute();
//            $query = $this->conn->prepare("DELETE FROM hoc_sinh_luyen_de WHERE ID_DE='$deID'");
//            $query->execute();
            $query = $this->conn->prepare("DELETE FROM de_cau_dap_an WHERE ID_DE='$deID'");
            $query->execute();
            $query = $this->conn->prepare("DELETE FROM de_thi WHERE ID_DE='$deID'");
            $query->execute();
        }

        public function xoaNhomDeThi($nID){
            $query = $this->conn->prepare("DELETE FROM nhom_de WHERE ID_N = '$nID'");
            $query->execute();

            $query = $this->conn->prepare("DELETE FROM de_noi_dung WHERE ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom = '$nID')");
            $query->execute();
//            $query = $this->conn->prepare("DELETE FROM hoc_sinh_cau WHERE ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom = '$nID')");
//            $query->execute();
//            $query = $this->conn->prepare("DELETE FROM hoc_sinh_de_in WHERE ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom = '$nID')");
//            $query->execute();
//            $query = $this->conn->prepare("DELETE FROM hoc_sinh_lam_lai WHERE ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom = '$nID')");
//            $query->execute();
//            $query = $this->conn->prepare("DELETE FROM hoc_sinh_luyen_de WHERE ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom = '$nID')");
//            $query->execute();
            $query = $this->conn->prepare("DELETE FROM de_cau_dap_an WHERE ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom = '$nID')");
            $query->execute();

            $query = $this->conn->prepare("DELETE FROM de_thi WHERE nhom = '$nID'");
            $query->execute();
        }

        public function checkHocSinhLamKiemTra($hsID, $buoiID, $lmID) {
            $query = $this->conn->prepare("SELECT loai FROM diemkt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_LM='$lmID'");
            $query->execute();
            $result = $query->get_result();
            if(mysqli_num_rows($result) != 0) {
                $data = $result->fetch_assoc();
                if($data["loai"] == 3) {
                    return "huy";
                } else {
                    return "normal";
                }
            } else {
                return "none";
            }
        }

        public function formatNhomDe($type) {
            switch ($type) {
                case "kiem-tra":
                    return "Kiểm tra";
                case "chuyen-de":
                    return "Chuyên đề";
                case "thi-thu":
                    return "Thi thử";
                default:
                    return "Tự do";
            }
        }

        public function getNgayFromBuoi($buoiID, $monID) {
            $query = $this->conn->prepare("SELECT ngay FROM buoikt WHERE ID_BUOI='$buoiID' AND ID_MON='$monID'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["ngay"];
        }

        public function getBuoiKtFromNhom($nhom) {
            $query = $this->conn->prepare("SELECT object FROM nhom_de WHERE ID_N='$nhom'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["object"];
        }

        public function getBuoiKtFromNgay($ngay, $monID) {
            $query = $this->conn->prepare("SELECT ID_BUOI FROM buoikt WHERE ngay='$ngay' AND ID_MON='$monID'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["ID_BUOI"];
        }

        public function addBuoiKiemTra($next_date, $monID) {
            $query = $this->conn->prepare("INSERT INTO buoikt(ngay,ID_MON) SELECT * FROM (SELECT '$next_date' AS buoi,'$monID' AS mon) AS tmp WHERE NOT EXISTS (SELECT ngay,ID_MON FROM buoikt WHERE ngay='$next_date' AND ID_MON='$monID') LIMIT 1");
            $query->execute();
        }

        public function getHocSinhByDeThi($deID,$how) {
            if($how == "in") {
                $query = $this->conn->prepare("SELECT ID_HS FROM hoc_sinh_de_in WHERE ID_DE=? AND out_time='0000-00-00 00:00:00'");
            } else if($how == "out") {
                $query = $this->conn->prepare("SELECT ID_HS FROM hoc_sinh_de_in WHERE ID_DE=? AND out_time!='0000-00-00 00:00:00'");
            } else {
                $query = $this->conn->prepare("SELECT ID_HS FROM hoc_sinh_de_in WHERE ID_DE=?");
            }
            $query->bind_param("s",$deID);
            $query->execute();
            return $query->get_result();
        }

        public function getChonDapAnByNhom($daID,$nhom) {
            $query = $this->conn->prepare("SELECT COUNT(ID_STT) AS dem FROM hoc_sinh_cau WHERE ID_DA=? AND ID_DE IN (SELECT ID_DE FROM de_thi WHERE nhom='$nhom')");
            $query->bind_param("s",$daID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"];
        }

        public function getNhomDeByCode($maso) {
            $query = $this->conn->prepare("SELECT ID_N FROM nhom_de WHERE code=?");
            $query->bind_param("s",$maso);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["ID_N"];
        }

        public function getNhomDeByDe($deID) {
            $query = $this->conn->prepare("SELECT nhom FROM de_thi WHERE ID_DE=?");
            $query->bind_param("s",$deID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["nhom"];
        }

        public function getNhomDeById($nhom) {
            $query = $this->conn->prepare("SELECT * FROM nhom_de WHERE ID_N='$nhom' OR code='$nhom'");
            $query->execute();
            return $query->get_result();
        }

        public function getDeThiById($deID) {
            $query = $this->conn->prepare("SELECT * FROM de_thi WHERE ID_DE=?");
            $query->bind_param("s",$deID);
            $query->execute();
            return $query->get_result();
        }

        public function getDeThiMainByDe($deID) {
            $query = $this->conn->prepare("SELECT ID_DE FROM de_thi WHERE nhom IN (SELECT nhom FROM de_thi WHERE ID_DE='$deID') AND main='1'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["ID_DE"];
        }

        public function getDeThiMainByNhom($nhom) {
            $query = $this->conn->prepare("SELECT ID_DE FROM de_thi WHERE nhom='$nhom' AND main='1'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["ID_DE"];
        }

        public function suaDe($date_close,$type,$object,$loai,$mota,$time,$nhom) {
            $query = $this->conn->prepare("UPDATE nhom_de SET date_close='$date_close',type='$type',object='$object' WHERE ID_N='$nhom'");
            $query->execute();
            $query = $this->conn->prepare("UPDATE de_thi SET mota='$mota',string='".unicodeConvert($mota)."',time='$time',loai='$loai' WHERE nhom='$nhom'");
            $query->execute();
        }

        public function taoDe($maso,$mota,$lmID,$is_bl,$time,$nhom,$main,$loai,$form) {
            $query = $this->conn->prepare("INSERT INTO de_thi(maso,mota,string,ngay,ID_LM,is_bl,public,time_lam,nhom,time,main,loai,form)
                                                        value('$maso','$mota','".unicodeConvert($mota)."',now(),'$lmID','$is_bl','1','0','$nhom','$time','$main','$loai','$form')");
            $query->execute();
            return $query->insert_id;
        }

        public function countCauOnDe($deID) {
            $query = $this->conn->prepare("SELECT COUNT(ID_C) AS dem FROM de_noi_dung WHERE ID_DE=?");
            $query->bind_param("s",$deID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"];
        }

        public function getDapAnByDe($deID) {
            $query = $this->conn->prepare("SELECT d.ID_DA,d.sort,a.ID_C FROM de_cau_dap_an AS d
            INNER JOIN dap_an_ngan AS a ON a.ID_DA=d.ID_DA
            WHERE ID_DE='$deID' ORDER BY a.ID_C ASC,d.sort ASC");
            $query->execute();
            return $query->get_result();
        }

        public function getDapAnByDeCount($deID,$de_arr) {
            $query = $this->conn->prepare("SELECT a.ID_C,d.ID_DA,a.content,a.type,a.main,COUNT(h.ID_STT) AS dem FROM de_cau_dap_an AS d 
            INNER JOIN dap_an_ngan AS a ON a.ID_DA=d.ID_DA 
            INNER JOIN hoc_sinh_cau AS h ON h.ID_DA=d.ID_DA 
            WHERE d.ID_DE='$deID' AND h.ID_DE IN ($de_arr)
            GROUP BY d.ID_DA
            ORDER BY a.ID_C ASC,d.sort ASC");
            $query->execute();
            return $query->get_result();
        }

        public function getHocSinhDapAnByDe($hsID, $deID) {
            $query = $this->conn->prepare("SELECT ID_DA,note,time,ID_C,datetime FROM hoc_sinh_cau WHERE ID_HS='$hsID' AND ID_DE='$deID'");
            $query->execute();
            return $query->get_result();
        }

        public function getDapAnNganByDeAll($deID) {
            $query = $this->conn->prepare("SELECT a.ID_C,a.ID_DA,a.content,a.type,a.main,a.how,d.sort FROM de_cau_dap_an AS d
            INNER JOIN dap_an_ngan AS a ON a.ID_DA=d.ID_DA
            WHERE d.ID_DE='$deID' 
            ORDER BY a.ID_C ASC,d.sort ASC");
            $query->execute();
            return $query->get_result();
        }

//        public function getDapAnNganByDeAllHs($hsID, $deID) {
//            $query = $this->conn->prepare("SELECT a.ID_C,a.content,a.type,a.main,a.how,d.ID_DA,d.sort,c.ID_STT,c.ID_DA AS da,c.note,c.time FROM de_cau_dap_an AS d
//            INNER JOIN dap_an_ngan AS a ON a.ID_DA=d.ID_DA
//            LEFT JOIN hoc_sinh_cau AS c ON c.ID_HS='$hsID' AND c.ID_DE='$deID' AND c.ID_C=a.ID_C
//            WHERE d.ID_DE='$deID'
//            ORDER BY a.ID_C ASC,d.sort ASC");
//            $query->execute();
//            return $query->get_result();
//        }

        public function getCauHoiByDeDangLam($deID) {
            $query = $this->conn->prepare("SELECT c.ID_C,c.maso,c.content,c.anh,c.type_da,c.ID_MON,d.sort FROM de_noi_dung AS d 
            INNER JOIN cau_hoi AS c ON c.ID_C=d.ID_C AND c.ready='1' 
            WHERE d.ID_DE='$deID' ORDER BY d.sort ASC");
            $query->execute();
            return $query->get_result();
        }

        public function getCauHoiByDe($deID,$is_rand) {
            if($is_rand) {
                $query = $this->conn->prepare("SELECT c.ID_C,c.done FROM de_noi_dung AS d INNER JOIN cau_hoi AS c ON c.ID_C=d.ID_C AND c.ready='1' WHERE d.ID_DE=? ORDER BY rand()");
            } else {
                $query = $this->conn->prepare("SELECT c.*,d.sort FROM de_noi_dung AS d INNER JOIN cau_hoi AS c ON c.ID_C=d.ID_C AND c.ready='1' WHERE d.ID_DE=? ORDER BY d.sort ASC");
            }
            $query->bind_param("s",$deID);
            $query->execute();
            return $query->get_result();
        }

        public function getCauHoiByDeWithDiemKem($deID,$de_arr) {
//            $my_result = array();
            $query = $this->conn->prepare("SELECT c.ID_C,c.maso,c.content,c.anh,c.ID_MON,d.sort,a.content AS da_con,a.anh AS da_anh,k.name,k.muc,COUNT(u.ID_STT) AS num FROM de_noi_dung AS d 
            INNER JOIN cau_hoi AS c ON c.ID_C=d.ID_C AND c.ready='1' 
            INNER JOIN do_kho AS k ON k.ID_K=c.ID_K
            INNER JOIN dap_an_dai AS a ON a.ID_C=d.ID_C
            INNER JOIN hoc_sinh_cau AS u ON u.ID_C=d.ID_C AND u.ID_DE IN ($de_arr)
            INNER JOIN dap_an_ngan AS n ON n.main='1' AND n.ID_C=d.ID_C AND n.ID_DA=u.ID_DA
            WHERE d.ID_DE='$deID'
            GROUP BY c.ID_C
            ORDER BY num ASC");
            $query->execute();
            return $query->get_result();
        }

        public function countCauHoiByDeWithTime($hsID,$deID,$deID_old) {
            $query = $this->conn->prepare("SELECT COUNT(c.ID_C) AS dem FROM de_noi_dung AS d INNER JOIN cau_hoi AS c ON c.ID_C=d.ID_C AND c.ready='1' LEFT JOIN hoc_sinh_cau AS h ON h.ID_HS='$hsID' AND h.ID_C=d.ID_C AND h.ID_DE='$deID_old' WHERE d.ID_DE=? ORDER BY d.sort ASC");
            $query->bind_param("s",$deID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"];
        }

        public function getCauHoiByDeWithTimeShort($hsID,$deID,$deID_old) {
            $query = $this->conn->prepare("SELECT d.ID_C,n.main,h.time FROM de_noi_dung AS d 
            INNER JOIN cau_hoi AS c ON c.ID_C=d.ID_C AND c.ready='1' 
            INNER JOIN hoc_sinh_cau AS h ON h.ID_HS='$hsID' AND h.ID_C=d.ID_C AND h.ID_DE='$deID_old'
            INNER JOIN dap_an_ngan AS n ON n.ID_DA=a.ID_DA AND n.ID_c=d.ID_C
            WHERE d.ID_DE=? ORDER BY d.sort ASC");
            $query->bind_param("s",$deID);
            $query->execute();
            return $query->get_result();
        }

        public function getCauHoiByDeWithTime($hsID,$deID,$deID_old) {
            $query = $this->conn->prepare("SELECT c.*,d.sort,h.time FROM de_noi_dung AS d INNER JOIN cau_hoi AS c ON c.ID_C=d.ID_C AND c.ready='1' LEFT JOIN hoc_sinh_cau AS h ON h.ID_HS='$hsID' AND h.ID_C=d.ID_C AND h.ID_DE='$deID_old' WHERE d.ID_DE=? ORDER BY d.sort ASC");
            $query->bind_param("s",$deID);
            $query->execute();
            return $query->get_result();
        }

        public function getCauHoiByDeWithTimeLimit($deID) {
            $query = $this->conn->prepare("SELECT c.ID_C,c.maso,c.ID_CD,c.content,c.anh,c.ID_MON,c.ID_K,c.done,d.sort,i.content AS da_con,i.anh AS da_anh FROM de_noi_dung AS d 
            INNER JOIN cau_hoi AS c ON c.ID_C=d.ID_C AND c.ready='1'
            INNER JOIN dap_an_dai AS i ON i.ID_C=d.ID_C
            WHERE d.ID_DE=? ORDER BY d.sort ASC");
            $query->bind_param("s",$deID);
            $query->execute();
            return $query->get_result();
        }

        public function getCauHoiByDeWithTimeHs($hsID, $deID) {
            $query = $this->conn->prepare("SELECT c.ID_C,c.maso,c.content,c.anh,c.ID_MON,c.ID_K,c.done,d.sort,i.content AS da_con,i.anh AS da_anh,h.ID_DA,h.note,h.time,h.datetime FROM de_noi_dung AS d 
                INNER JOIN cau_hoi AS c ON c.ID_C=d.ID_C AND c.ready='1'
                INNER JOIN dap_an_dai AS i ON i.ID_C=d.ID_C
                LEFT JOIN hoc_sinh_cau AS h ON h.ID_HS='$hsID' AND h.ID_DE='$deID' AND h.ID_C=d.ID_C
                WHERE d.ID_DE='$deID' ORDER BY d.sort ASC");
            $query->execute();
            return $query->get_result();
        }

        public function getDapAnNganByDe($cID,$deID,$is_rand) {
            if($is_rand) {
                $query = $this->conn->prepare("SELECT a.ID_DA,a.content,a.how FROM de_cau_dap_an AS d INNER JOIN dap_an_ngan AS a ON a.ID_C='$cID' AND a.ID_DA=d.ID_DA WHERE d.ID_DE=? ORDER BY rand()");
            } else {
                $query = $this->conn->prepare("SELECT a.ID_DA,a.content,a.type,a.main,a.how,d.sort FROM de_cau_dap_an AS d INNER JOIN dap_an_ngan AS a ON a.ID_C='$cID' AND a.ID_DA=d.ID_DA WHERE d.ID_DE=? ORDER BY d.sort ASC");
            }
            $query->bind_param("s",$deID);
            $query->execute();
            return $query->get_result();
        }

        public function getDapAnNganByDeUse($cID,$deID) {
            $query = $this->conn->prepare("SELECT a.*,d.sort FROM de_cau_dap_an AS d INNER JOIN dap_an_ngan AS a ON a.ID_C='$cID' AND a.ID_DA=d.ID_DA AND a.how='1' WHERE d.ID_DE=? ORDER BY d.sort ASC");
            $query->bind_param("s",$deID);
            $query->execute();
            return $query->get_result();
        }

        public function getNextNhomDe() {
            $query = $this->conn->prepare("SELECT MAX(nhom) AS dem FROM de_thi");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"];
        }

        public function addCauDeThiMulti($content) {
            $query = $this->conn->prepare("INSERT INTO de_noi_dung(ID_DE,ID_C,sort) VALUES $content");
            $query->execute();
        }

        public function addDapAnDeThiMulti($content_da) {
            $query = $this->conn->prepare("INSERT INTO de_cau_dap_an(ID_DE,ID_DA,sort) VALUES $content_da");
            $query->execute();
        }

        public function taoDeNoiDungHs($type, $chuyende, $sl, $cau_str, $hsID, $monID) {
            if($cau_str == "") {
                $cau_str = "'0'";
            }
            if($type == "sainhieu") {
                $query = $this->conn->prepare("SELECT n.ID_C,COUNT(*) AS dem FROM hoc_sinh_cau AS n 
                INNER JOIN dap_an_ngan AS a ON a.ID_DA=n.ID_DA AND a.ID_C=n.ID_C AND a.how='1' AND a.main='0'
                INNER JOIN cau_hoi AS c ON c.ID_C=n.ID_C AND c.ID_CD IN ($chuyende) AND c.done='1' AND c.ready='1' AND c.ID_MON='$monID'
                WHERE n.ID_HS='$hsID' AND n.ID_C NOT IN ($cau_str)
                GROUP BY n.ID_C
                ORDER BY dem DESC,rand() LIMIT $sl");
                $query->execute();
                return $query->get_result();
            } else if($type == "lopsai") {
                $query = $this->conn->prepare("SELECT n.ID_C,COUNT(*) AS dem FROM hoc_sinh_cau AS n 
                INNER JOIN dap_an_ngan AS a ON a.ID_DA=n.ID_DA AND a.ID_C=n.ID_C AND a.how='1' AND a.main='0'
                INNER JOIN cau_hoi AS c ON c.ID_C=n.ID_C AND c.ID_CD IN ($chuyende) AND c.done='1' AND c.ready='1' AND c.ID_MON='$monID'
                WHERE n.ID_C NOT IN ($cau_str)
                GROUP BY n.ID_C
                ORDER BY dem DESC,rand() LIMIT $sl");
                $query->execute();
                return $query->get_result();
            } else if($type == "caukho") {
                $query = $this->conn->prepare("SELECT c.ID_C,COUNT(*) AS dem FROM cau_hoi AS c 
                INNER JOIN do_kho AS k ON k.ID_K=c.ID_K
                WHERE c.ID_C NOT IN ($cau_str) AND c.ID_CD IN ($chuyende) AND c.done='1' AND c.ready='1' AND c.ID_MON='$monID'
                GROUP BY c.ID_C
                ORDER BY k.muc DESC,rand() LIMIT $sl");
                $query->execute();
                return $query->get_result();
            } else if($type == "caude") {
                $query = $this->conn->prepare("SELECT c.ID_C,COUNT(*) AS dem FROM cau_hoi AS c 
                INNER JOIN do_kho AS k ON k.ID_K=c.ID_K
                WHERE c.ID_C NOT IN ($cau_str) AND c.ID_CD IN ($chuyende) AND c.done='1' AND c.ready='1' AND c.ID_MON='$monID'
                GROUP BY c.ID_C
                ORDER BY k.muc ASC,rand() LIMIT $sl");
                $query->execute();
                return $query->get_result();
            } else {
                $query = $this->conn->prepare("SELECT ID_C FROM cau_hoi WHERE maso NOT LIKE '00-%' AND ID_C NOT IN ($cau_str) AND ID_CD IN ($chuyende) AND done='1' AND ready='1' AND ID_MON='$monID' ORDER BY rand() LIMIT $sl");
                $query->execute();
                return $query->get_result();
            }
        }

        public function taoDeNoiDung($cdID,$sl,$loai,$kho,$option,$option_note,$deID,$dapan_e,$is_bl,$goc,$nhan_ban,$luuygoc,$isUnlock,$isSort,$lmID,$deForm) {
            if($deForm == 1) {
                $sort = $this->countCauOnDe($deID) + 1;
                $string = "SELECT ID_C FROM cau_hoi WHERE ID_C!=0 ";
                if ($goc == 1) {
                    $string .= "AND maso LIKE '%-%-%a%' ";
                    $nhan_ban = $luuygoc = 0;
                }
                if ($nhan_ban == 1) {
                    $string .= "AND maso LIKE '%-%-%b%' ";
                }
                if ($cdID != 0) {
                    $string .= "AND ID_CD='$cdID' ";
                }
                if ($loai != 0) {
                    $string .= "AND ID_PL='$loai' ";
                }
                if ($kho != 0) {
                    $string .= "AND ID_K='$kho' ";
                }
                if ($option != "") {
                    $temp = explode(",", $option);
                    $string .= "AND maso LIKE '".trim($temp[0])."%' ";
                }
                if ($option_note != "") {
                    $temp = explode(",", $option_note);
                    $string .= "AND note LIKE '%".trim($temp[0])."%' ";
                }
                $string .= "AND ID_C NOT IN (SELECT ID_C FROM de_noi_dung WHERE ID_DE='$deID') AND ready='1' ";
                if ($isUnlock != 0) {
                    $string .= " AND ID_CD IN (SELECT chuyen_de_unlock WHERE ID_LM='$lmID') ";
                }
                if ($luuygoc != 0) {
                    $string .= "ORDER BY main DESC ";
                    if ($isSort != 0) {
                        $string .= ",ID_C ASC ";
                    } else {
                        $string .= ",rand() ";
                    }
                } else {
                    if ($isSort != 0) {
                        $string .= "ORDER BY ID_C ASC ";
                    } else {
                        $string .= "ORDER BY rand() ";
                    }
                }
                if ($sl > 0) {
                    $string .= "LIMIT $sl";
                } else {
                    $string .= "LIMIT 50";
                }
                $query = $this->conn->prepare($string);
                $query->execute();
                $result = $query->get_result();
                $db = new Cau_Hoi();
                $content = "";
                $content_da = "";
                while ($data = $result->fetch_assoc()) {
                    $content .= ",('$deID','$data[ID_C]','$sort')";
//                    $this->addCauDeThi($deID, $data["ID_C"], $sort);
                    $dem = 1;
                    $result2 = $db->getDapAnNgan($data["ID_C"], false);
                    while ($data2 = $result2->fetch_assoc()) {
                        $content_da .= ",('$deID','$data2[ID_DA]','$dem')";
//                        $this->addDapAnDeThi($deID, $data2["ID_DA"], $dem);
                        $dem++;
                    }
//                    if ($is_bl == 1) {
//                        $result2 = $db->getDapAnKhac($data["ID_C"]);
//                        $data2 = $result2->fetch_assoc();
//                        $content_da .= ",('$deID','$data2[ID_DA]','$dem')";
////                        $this->addDapAnDeThi($deID, $data2["ID_DA"], $dem);
//                        $dem++;
//                    }
//                    if ($dapan_e == 1) {
//                        $result2 = $db->getDapAnKoLam($data["ID_C"]);
//                        $data2 = $result2->fetch_assoc();
//                        $content_da .= ",('$deID','$data2[ID_DA]','$dem')";
////                        $this->addDapAnDeThi($deID, $data2["ID_DA"], $dem);
//                    }
                    $sort++;
                }
                if($content != "") {
                    $content = substr($content, 1);
                    $query = $this->conn->prepare("INSERT INTO de_noi_dung(ID_DE,ID_C,sort) VALUES $content");
                    $query->execute();
                }
                if($content_da != "") {
                    $content_da = substr($content_da, 1);
                    $query = $this->conn->prepare("INSERT INTO de_cau_dap_an(ID_DE,ID_DA,sort) VALUES $content_da");
                    $query->execute();
                }
            } else if($deForm == 2) {
                $query = $this->conn->prepare("INSERT INTO de_pre_noi_dung(ID_DE,ID_CD,sl,loai,kho,options,options_no,dapan_e,is_bl,goc,nhan_ban,luuygoc,isUnlock,isSort)
                                                                    value('$deID','$cdID','$sl','$loai','$kho','$option','$option_note','$dapan_e','$is_bl','$goc','$nhan_ban','$luuygoc','$isUnlock','$isSort')");
                $query->execute();
            }
        }

        public function getDePreNoiDung($deID) {
            $query = $this->conn->prepare("SELECT * FROM de_pre_noi_dung WHERE ID_DE='$deID' ORDER BY rand()");
            $query->execute();
            $result = $query->get_result();
            return $result;
        }

        public function updateCauSortDeThi($deID, $cID, $sort) {
            $query = $this->conn->prepare("UPDATE de_noi_dung SET sort='$sort' WHERE ID_DE='$deID' AND ID_C='$cID'");
            $query->execute();
        }

        public function addCauDeThi($deID, $cID, $sort) {
            $query = $this->conn->prepare("INSERT INTO de_noi_dung(ID_DE,ID_C,sort)
                                                            value('$deID','$cID','$sort')");
            $query->execute();
        }

        public function addDapAnDeThi($deID,$daID,$dem) {
            $query = $this->conn->prepare("INSERT INTO de_cau_dap_an(ID_DE,ID_DA,sort)
                                                                value('$deID','$daID','$dem')");
            $query->execute();
        }

        public function getNhomDeThiCustom($lmID, $type, $loai, $expect) {
            $query = $this->conn->prepare("SELECT n.*,d.ID_DE,d.mota FROM nhom_de AS n
            INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
            INNER JOIN loai_de AS l ON l.name='$loai' AND l.ID_D=d.loai
            WHERE d.ID_DE NOT IN ($expect) AND n.ID_LM='$lmID' AND n.type='$type'
            ORDER BY n.datetime DESC");
            $query->execute();
            return $query->get_result();
        }

        public function getNhomDeThiByMon($lmID, $type) {
            if($type == "X") {
                $query = $this->conn->prepare("SELECT n.*,d.ID_DE,d.mota,d.loai,COUNT(e.ID_ND) AS cau FROM nhom_de AS n 
                INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
                LEFT JOIN de_noi_dung AS e ON e.ID_DE=d.ID_DE
                WHERE n.ID_LM=? GROUP BY n.ID_N,d.ID_DE ORDER BY n.datetime DESC");
            } else {
                $query = $this->conn->prepare("SELECT n.*,d.ID_DE,d.mota,d.loai,COUNT(e.ID_ND) AS cau FROM nhom_de AS n 
                INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
                LEFT JOIN de_noi_dung AS e ON e.ID_DE=d.ID_DE
                WHERE n.ID_LM=? AND n.type='$type' GROUP BY n.ID_N,d.ID_DE ORDER BY n.datetime DESC");
            }
            $query->bind_param("s",$lmID);
            $query->execute();
            return $query->get_result();
        }

        public function countDeThiByNhom($nhom) {
            $query = $this->conn->prepare("SELECT COUNT(ID_DE) AS dem FROM de_thi WHERE nhom=?");
            $query->bind_param("s",$nhom);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"];
        }

        public function getDeThiByNhom($nhom) {
            $query = $this->conn->prepare("SELECT d.ID_DE FROM de_thi as d 
			WHERE d.nhom=?");
            $query->bind_param("s",$nhom);
            $query->execute();
            return $query->get_result();
        }

        public function getDeThiByNhomDemHocSinh($nhom) {
            $query = $this->conn->prepare("SELECT d.*,COUNT(h.ID_HS) as dem2,l.name FROM de_thi as d 
			LEFT JOIN hoc_sinh_luyen_de AS h ON h.ID_DE = d.ID_DE
			INNER JOIN loai_de AS l ON l.ID_D = d.loai
			WHERE d.nhom=? 
			GROUP BY d.ID_DE 
			ORDER BY d.main DESC, d.ID_DE ASC");
            $query->bind_param("s",$nhom);
            $query->execute();
            return $query->get_result();
        }

        public function getDeThiByNhomDemCau($nhom) {
            $query = $this->conn->prepare("SELECT d.ID_DE,COUNT(n.ID_C) as dem FROM de_thi as d 
			INNER JOIN de_noi_dung AS n ON n.ID_DE = d.ID_DE
			WHERE d.nhom=? 
			GROUP BY d.ID_DE 
			ORDER BY d.main DESC, d.ID_DE ASC");
            $query->bind_param("s",$nhom);
            $query->execute();
            return $query->get_result();
        }

        public function getDeThiByNhomKhoang($nhom, $start, $end) {
            if($start < 0) {$start = 0;}
            if($end < 0) {$end = 0;}
            if($start != 0) {$start--;}
            $display = $end - $start;
            $query = $this->conn->prepare("SELECT * FROM de_thi WHERE nhom=? ORDER BY main DESC,ID_DE ASC LIMIT $start,$display");
            $query->bind_param("s",$nhom);
            $query->execute();
            return $query->get_result();
        }

        public function countDeNhom($nhom) {
            $query = $this->conn->prepare("SELECT COUNT(ID_DE) AS dem FROM de_thi WHERE nhom=?");
            $query->bind_param("s",$nhom);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"];
        }

        public function getAllMasoNhomDe($nhom) {
            $query = $this->conn->prepare("SELECT maso FROM de_thi WHERE nhom='$nhom'");
            $query->execute();
            return $query->get_result();
        }

        public function updateMasoDeThi($deID, $maso) {
            $query = $this->conn->prepare("UPDATE de_thi SET maso='$maso' WHERE ID_DE='$deID'");
            $query->execute();
        }

        public function checkTonTaiMaso($maso, $nhom) {
            $query = $this->conn->prepare("SELECT ID_DE FROM de_thi WHERE maso='$maso' AND nhom='$nhom'");
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                return true;
            } else {
                return false;
            }
        }

        public function checkTonTaiMasoNhom($maso) {
            $query = $this->conn->prepare("SELECT ID_N FROM nhom_de WHERE code=?");
            $query->bind_param("s",$maso);
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                return true;
            } else {
                return false;
            }
        }

        public function getTimeConLai($date_open,$date_close,$public) {
            if($date_open != "0000-00-00 00:00:00") {
                if($date_close != "0000-00-00") {
                    $open = date_create($date_close . " 03:00:00");
                } else {
                    $temp = explode(" ",$date_open);
                    $open = date_create($temp[0] . " 03:00:00");
                    date_add($open, date_interval_create_from_date_string("+6 days"));
                }
                if (date_create(date("Y-m-d H:i:s")) > $open) {
                    if($public == 1) {
                        return "Hết hạn!";
                    } else {
                        return "Đã đóng";
                    }
                } else {
                    $diff = date_diff(date_create(date("Y-m-d H:i:s")), $open);
                    return $diff->format("%ad %hh %im");
                }
            } else {
                return "-";
            }
        }

        public function getCodeLayBai($hsID,$buoiID,$lmID) {
            $query = $this->conn->prepare("SELECT more FROM diemkt WHERE ID_HS='$hsID' AND ID_BUOI='$buoiID' AND ID_LM='$lmID'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["more"];
        }

        public function checkHocSinhLamLai($hsID,$deID) {
            $query = $this->conn->prepare("SELECT diem,datetime FROM hoc_sinh_luyen_de WHERE ID_DE='$deID' AND ID_HS='$hsID'");
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                $data = $result->fetch_assoc();
                if($data["diem"] < 1) {
                    return "diem-0";
                } else {
                    $you_date = strtotime($data["datetime"]);
                    $now = time();
                    $difference = $now - $you_date;
                    $days = floor($difference / (60 * 60 * 24));
                    if ($days >= 1) {
                        return "one-day";
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }
        }

        public function addNhomDe($code,$type,$date_close,$lmID,$object) {
            $query = $this->conn->prepare("INSERT INTO nhom_de(code,datetime,ID_LM,public,date_close,type,object)
                                                        value('$code',now(),'$lmID','2','$date_close','$type','$object')");
            $query->execute();
            return $query->insert_id;
        }

        public function updateNhomDeStatus($nID, $status) {
            if($status == 1) {
                $query = $this->conn->prepare("UPDATE nhom_de SET public='$status',date_open=now() WHERE ID_N='$nID'");
            } else {
                $query = $this->conn->prepare("UPDATE nhom_de SET public='$status' WHERE ID_N='$nID'");
            }
            $query->execute();
        }

        public function updateNhomDeAllow($nID, $allow) {
            $query = $this->conn->prepare("UPDATE nhom_de SET allow='$allow' WHERE ID_N='$nID'");
            $query->execute();
        }

        public function getAnhKiemTraHS($hsID, $buoiID, $lmID) {
            $query = $this->conn->prepare("SELECT anh FROM diemkt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_LM='$lmID'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            if(stripos($data["anh"],".jpg") === false && stripos($data["anh"],".png") === false && stripos($data["anh"],".jpeg") === false) {
                return "none";
            } else {
                return $data["anh"];
            }
        }

        public function getCaKiemTraHS($hsID, $ngay, $monID) {
            $query = $this->conn->prepare("SELECT b.ID_CA FROM diemdanh_buoi AS b 
            INNER JOIN diemdanh AS d ON d.ID_DD=b.ID_STT AND d.ID_HS='$hsID'
            WHERE b.date='$ngay' AND b.ID_LM='0' AND b.ID_MON='$monID' LIMIT 1");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["ID_CA"];
        }

        public function getNhomDeOpenType($hsID,$type,$lmID) {
            if($type == "kiem-tra") {
                $query = $this->conn->prepare("SELECT n.*,d.ID_DE,d.mota,p.ID_STT,l.name FROM nhom_de AS n
                INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
                INNER JOIN loai_de AS l ON l.ID_D=d.loai
                INNER JOIN hocsinh_mon AS m ON m.ID_HS='$hsID' AND m.ID_LM='$lmID' AND m.de=l.name
                LEFT JOIN hoc_sinh_special AS p ON p.ID_HS='$hsID' AND p.ID_N=n.ID_N
                WHERE n.public IN ('1','2') AND n.type='$type' AND n.ID_LM='$lmID' ORDER BY n.datetime DESC LIMIT 2");
//                $query = $this->conn->prepare("SELECT n.*,d.ID_DE,d.mota FROM nhom_de AS n
//                INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
//                INNER JOIN loai_de AS l ON l.ID_D=d.loai AND l.name='$de'
//                WHERE (n.public='1' OR (n.public='2' AND n.ID_N IN (SELECT ID_N FROM hoc_sinh_special WHERE ID_HS='$hsID'))) AND n.type='$type' AND n.ID_LM IN (SELECT ID_LM FROM hocsinh_mon WHERE ID_HS='$hsID') ORDER BY n.datetime DESC");
            } else {
                $query = $this->conn->prepare("SELECT n.*,d.ID_DE,d.mota FROM nhom_de AS n 
                INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
                WHERE n.public='1' AND n.type='$type' AND n.ID_LM='$lmID' ORDER BY n.datetime DESC");
            }
            $query->execute();
            return $query->get_result();
        }

        public function getNhomDeOpen($hsID) {
            if($hsID != 0) {
                $query = $this->conn->prepare("SELECT n.*,d.ID_DE,d.mota FROM nhom_de AS n 
                INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
                WHERE n.public='1' AND n.ID_LM IN (SELECT ID_LM FROM hocsinh_mon WHERE ID_HS='$hsID') AND n.ID_LM NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_HS='$hsID') ORDER BY n.datetime DESC");
            } else {
                $query = $this->conn->prepare("SELECT n.*,d.ID_DE,d.mota FROM nhom_de AS n
                INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
                WHERE n.public='1' OR n.date_close='".date("Y-m-d")."' ORDER BY n.datetime DESC");
            }
            $query->execute();
            return $query->get_result();
        }

        public function getNhomDeNotHideByType($type,$lmID,$limit) {
            if($limit == NULL) {
                $query = $this->conn->prepare("SELECT n.*,d.ID_DE,d.mota,d.loai,COUNT(d.ID_DE) AS dem FROM nhom_de AS n
                INNER JOIN de_thi AS d ON d.nhom=n.ID_N
                WHERE n.ID_LM='$lmID' AND n.public!='0' AND n.type='$type' 
                ORDER BY d.main DESC,n.datetime DESC");
            } else {
                $query = $this->conn->prepare("SELECT n.*,d.ID_DE,d.mota,d.loai,COUNT(d.ID_DE) AS dem FROM nhom_de AS n
                INNER JOIN de_thi AS d ON d.nhom=n.ID_N
                WHERE n.ID_LM='$lmID' AND n.public!='0' AND n.type='$type' 
                GROUP BY n.ID_N
                ORDER BY d.main DESC,n.datetime DESC LIMIT $limit");
            }
            $query->execute();
            return $query->get_result();
        }

        public function getNhomDeNotHide($lmID,$limit) {
            if($limit == NULL) {
                $query = $this->conn->prepare("SELECT n.*,d.ID_DE,d.mota FROM nhom_de AS n
                INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
                WHERE n.ID_LM='$lmID' AND n.public!='0' ORDER BY n.datetime DESC");
            } else {
                $query = $this->conn->prepare("SELECT n.*,d.ID_DE,d.mota FROM nhom_de AS n
                INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
                WHERE n.ID_LM='$lmID' AND n.public!='0' ORDER BY n.datetime DESC LIMIT $limit");
            }
            $query->execute();
            return $query->get_result();
        }

        public function checkDeTuLuyenHs($hsID, $deID) {
            $query = $this->conn->prepare("SELECT ID_STT FROM hoc_sinh_tu_luyen WHERE ID_HS='$hsID' AND ID_DE='$deID' AND out_time='0000-00-00 00:00:00'");
            $query->execute();
            $result = $query->get_result();
            if ($result->num_rows != 0) {
                return true;
            } else {
                return false;
            }
        }

        public function checkDeOpenHocSinh($hsID, $nID, $lmID) {
            $query = $this->conn->prepare("SELECT ID_N FROM nhom_de WHERE ID_N='$nID' AND (public='1' OR (public='2' AND ID_N IN (SELECT ID_N FROM hoc_sinh_special WHERE ID_HS='$hsID'))) AND ID_LM='$lmID' LIMIT 1");
            $query->execute();
            $result = $query->get_result();
            if ($result->num_rows != 0) {
                return true;
            } else {
                return false;
            }
        }

        public function checkHocSinhDoneLam($hsID,$nID) {
            $query = $this->conn->prepare("SELECT h.ID_DE FROM hoc_sinh_luyen_de AS h INNER JOIN de_thi AS d ON d.ID_DE=h.ID_DE AND d.nhom='$nID' WHERE h.ID_HS=?");
            $query->bind_param("s",$hsID);
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                $data = $result->fetch_assoc();
                return $data["ID_DE"];
            } else {
                return 0;
            }
        }

        public function getRandDeThiByNhom($nID, $de) {
            if($de) {
                $query = $this->conn->prepare("SELECT ID_DE FROM de_thi WHERE nhom='$nID' AND public='1' AND loai IN (SELECT ID_D FROM loai_de WHERE name='$de' OR name='Chung') ORDER BY rand() LIMIT 1");
            } else {
                $query = $this->conn->prepare("SELECT ID_DE FROM de_thi WHERE nhom='$nID' AND public='1' ORDER BY rand() LIMIT 1");
            }
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["ID_DE"];
        }

        public function countDoKhoInDe($do_kho,$deID) {
            $query = $this->conn->prepare("SELECT COUNT(d.ID_ND) AS dem FROM de_noi_dung AS d INNER JOIN cau_hoi AS c ON c.ID_C=d.ID_C AND c.ID_K='$do_kho' WHERE d.ID_DE='$deID'");
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"];
        }
    }

    class Vao_Thi {
        private $conn;

        function __construct() {
            require_once 'DB_Connect.php';
            // connecting to database
            $this->conn = DB_Connect::getInstance()->getConnection();
//            $db = new Db_Connect();
//            $this->conn = $db->connect();
        }

        public function updateTimeCauLam($hsID, $cID, $deID, $time) {
            $query = $this->conn->prepare("UPDATE hoc_sinh_cau SET time='$time' WHERE ID_HS=? AND ID_C='$cID' AND ID_DE='$deID'");
            $query->bind_param("s",$hsID);
            $query->execute();
        }

        public function addCauLam($hsID, $cID, $deID, $time, $daID, $note) {
            $query = $this->conn->prepare("INSERT INTO hoc_sinh_cau(ID_HS,ID_C,ID_DA,num,time,note,ID_DE)
            SELECT * FROM (SELECT '$hsID' AS hs,'$cID' AS cID,'$daID' AS daID,'0' AS num,'$time' AS time,'$note' AS note,'$deID' AS de) AS tmp
            WHERE NOT EXISTS (SELECT ID_STT FROM hoc_sinh_cau WHERE ID_HS='$hsID' AND ID_C='$cID' AND ID_DE='$deID')");
            $query->execute();
            return $query->insert_id;
        }

        public function updateCauLam($hsID, $cID, $deID, $time, $daID, $note) {
            $query = $this->conn->prepare("UPDATE hoc_sinh_cau SET ID_DA='$daID',time='$time',note='$note' WHERE ID_HS='$hsID' AND ID_C='$cID' AND ID_DE='$deID'");
            $query->execute();
            return $query->affected_rows;
//            $num = $query->affected_rows;
//            if($num == 0) {
//                $query = $this->conn->prepare("INSERT INTO hoc_sinh_cau(ID_HS,ID_C,ID_DA,num,time,note,ID_DE)
//                                                                VALUES('$hsID','$cID','$daID','0','$time','$note','$deID')");
//                $query->execute();
//                return $query->insert_id;
//            } else {
//                return $num;
//            }
        }

        public function clearNoteDapAnDeHocSinh($hsID,$cID,$deID) {
            $query = $this->conn->prepare("UPDATE hoc_sinh_cau SET note='' WHERE ID_HS='$hsID' AND ID_C='$cID' AND ID_DE='$deID'");
            $query->execute();
        }

        public function addNoteDapAnDeHocSinh($hsID,$daID,$deID,$content) {
            $query = $this->conn->prepare("UPDATE hoc_sinh_cau SET note='$content' WHERE ID_HS='$hsID' AND ID_DA='$daID' AND ID_DE='$deID'");
            $query->execute();
        }

        public function addHocSinhTuLuyen($hsID, $deID, $diem, $time, $type) {
            if($type == "create") {
                $query = $this->conn->prepare("INSERT INTO hoc_sinh_tu_luyen(ID_HS,ID_DE)
                                                                        VALUES('$hsID','$deID')");
                $query->execute();
            } else if($type == "in") {
                $query = $this->conn->prepare("UPDATE hoc_sinh_tu_luyen SET in_time=now() WHERE ID_HS='$hsID' AND ID_DE='$deID' AND in_time='0000-00-00 00:00:00' AND out_time='0000-00-00 00:00:00' ORDER BY ID_STT DESC LIMIT 1");
                $query->execute();
            } else if($type == "out") {
                $query = $this->conn->prepare("UPDATE hoc_sinh_tu_luyen SET diem='$diem',time='$time',out_time=now() WHERE ID_HS='$hsID' AND ID_DE='$deID' AND out_time='0000-00-00 00:00:00' ORDER BY ID_STT DESC LIMIT 1");
                $query->execute();
            } else if($type == "time") {
                $query = $this->conn->prepare("UPDATE hoc_sinh_tu_luyen SET time='$time' WHERE ID_HS='$hsID' AND ID_DE='$deID' ORDER BY ID_STT DESC LIMIT 1");
                $query->execute();
            }
        }

        public function addHocSinhInDe($hsID, $deID, $nhom, $how) {
            $query = $this->conn->prepare("SELECT ID_DE,out_time FROM hoc_sinh_de_in WHERE ID_HS='$hsID' AND ID_N='$nhom'");
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows == 0) {
                if($how == "in") {
                    $query = $this->conn->prepare("INSERT INTO hoc_sinh_de_in(ID_HS,ID_DE,ID_N,in_time)
                                                                        value('$hsID','$deID','$nhom',now())");
                    $query->execute();
                }
                return $deID;
            } else {
                $data = $result->fetch_assoc();
                if($how == "in") {
                    return $data["ID_DE"];
                }
                if($how == "out") {
                    if($data["out_time"] == "0000-00-00 00:00:00") {
                        $query = $this->conn->prepare("UPDATE hoc_sinh_de_in SET out_time=now() WHERE ID_HS='$hsID' AND ID_DE='$deID'");
                        $query->execute();
                    }
                    return 0;
                }
            }
        }

        public function getLishSuLamLai($hsID,$lmID) {
            $query = $this->conn->prepare("SELECT l.diem,l.time,l.datetime,n.code,n.public,n.type,t.mota FROM hoc_sinh_lam_lai AS l
            INNER JOIN de_thi AS t ON t.ID_DE=l.ID_DE
            INNER JOIN nhom_de AS n ON n.ID_N=t.nhom AND n.ID_LM='$lmID'
            WHERE l.ID_HS='$hsID' ORDER BY l.datetime DESC");
            $query->execute();
            return $query->get_result();
        }

        public function getLishSuVaoThi($hsID, $lmID) {
            $query = $this->conn->prepare("SELECT d.in_time,d.out_time,n.code,n.public,n.type,t.mota FROM hoc_sinh_de_in AS d
            INNER JOIN de_thi AS t ON t.ID_DE=d.ID_DE AND t.ID_LM='$lmID'
            INNER JOIN nhom_de AS n ON n.ID_N=t.nhom AND n.ID_LM='$lmID'
            WHERE d.ID_HS='$hsID' ORDER BY d.ID_STT DESC");
            $query->execute();
            return $query->get_result();
        }

        public function getDapAnHocSinhDung($hsID, $cID, $deID) {
            $query = $this->conn->prepare("SELECT COUNT(d.ID_DA) AS dem FROM hoc_sinh_cau AS d INNER JOIN dap_an_ngan AS c ON c.ID_DA=d.ID_DA AND c.ID_C='$cID' AND main='1' WHERE d.ID_C='$cID' AND d.ID_HS=? AND d.ID_DE='$deID'");
            $query->bind_param("s",$hsID);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            return $data["dem"];
        }

        public function getDapAnHocSinh($hsID, $cID, $deID) {
            $query = $this->conn->prepare("SELECT d.ID_DA FROM hoc_sinh_cau AS d INNER JOIN dap_an_ngan AS c ON c.ID_DA=d.ID_DA AND c.ID_C='$cID' WHERE d.ID_C='$cID' AND d.ID_HS=? AND d.ID_DE='$deID'");
            $query->bind_param("s",$hsID);
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                $data = $result->fetch_assoc();
                return $data["ID_DA"];
            } else {
                return 0;
            }
        }

        public function getTimeHocSinhInDe($hsID, $deID, $nhom) {
            if($nhom != 0) {
                $query = $this->conn->prepare("SELECT in_time FROM hoc_sinh_de_in WHERE ID_HS=? AND ID_DE='$deID'");
                $query->bind_param("s", $hsID);
                $query->execute();
                $result = $query->get_result();
                $data = $result->fetch_assoc();
                return $data["in_time"];
            } else {
                $query = $this->conn->prepare("SELECT in_time FROM hoc_sinh_tu_luyen WHERE ID_HS='$hsID' AND ID_DE='$deID' ORDER BY ID_STT DESC LIMIT 1");
                $query->execute();
                $result = $query->get_result();
                $data = $result->fetch_assoc();
                return $data["in_time"];
            }
        }

        public function checkToken($hsID, $token) {
            $query = $this->conn->prepare("SELECT ID FROM token WHERE ID_HS=? AND token='$token'");
            $query->bind_param("s",$hsID);
            $query->execute();
            $result = $query->get_result();
            if($result->num_rows != 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    function formatStatus($status) {
        if($status == 1) {
            return "<span class='label' style='color:#FFF;background-color:green;border-color:green;'>Hoạt động</span>";
        } else if($status == 0) {
            return "<span class='label bg-brown-400'>Ẩn</span>";
        } else if($status == 2){
            return "<span class='label bg-danger-400' style='background-color:red;border-color:red;'>Khóa</span>";
        } else {

        }
    }

    function formatDateTime($datetime) {
        $date=date_create($datetime);

        return date_format($date, 'H:i d/m/Y');
    }

    function formatDateFromTime($datetime) {
        $date=date_create($datetime);

        return date_format($date, 'd/m/Y');
    }

    function formatDateUp($date) {
        $date=date_create($date);

        return date_format($date, 'd/m/Y');
    }

    function validId($id) {
        $id = $id + 1 - 1;
        if(is_numeric($id) && $id != 0) {
            return true;
        } else {
            return false;
        }
    }

    function formatZero($number) {
        $number = $number + 1 - 1;
        if($number < 10) {
            return "0".$number;
        } else {
            return $number;
        }
    }

    function randPass($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function randMaso($length) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function randMasoCode($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function encodeData($data, $code) {
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

    function decodeData($data, $code) {
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

    function isJson($string) {
        json_decode($string);
        if(json_last_error() == JSON_ERROR_NONE) {
            if(substr($string,0,1) == '[' && substr($string,-1) == ']') { return TRUE; }
            else if(substr($string,0,1) == '{' && substr($string,-1) == '}') { return TRUE; }
            else { return FALSE; }
        }
    }

    function formatTime($time) {
        // 00:00:00
        $temp = explode(":",$time);
        $new_time = $temp[0]*3600 + $temp[1]*60 + $temp[2];
        return $new_time;
    }

    function formatTimeBack($time) {
        // giây
        $t = round($time);
        return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
    }

    function formatDiem($diem) {
        $diem=number_format((float)$diem, 2, '.', '');
        return $diem;
    }

    function formatNumber($diem) {
        $diem=number_format((float)$diem, 0, '.', '');
        return $diem;
    }

    function validText($text){
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
        return true;
    }

    function validMaso($maso) {
        $temp=explode("-",$maso);
        if(count($temp)==2) {
            if(!is_numeric($temp[0]) || !is_numeric($temp[1])) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    function formatDapAn($content) {
        if(stripos($content, "\\") === false) {
            $length = strlen($content);
            if ($length <= 20) {
                return 1;
            } else if ($length <= 50) {
                return 2;
            } else {
                return 3;
            }
        } else {
            $arr = array("left","right","arrow","\\begin","\\end","gathered","\\backslash",
                "\\hfill","\\sqrt","\\frac","\\leqslant","\\geqslant","\\Left","\\bot","\\Delta",
                "\\Right","\\approx","\\mathop","\\limits_","\\to","\\int","\\in","\\cup",
                "\\max","\\min","\\mathbb","\\forall","\\notin","\\lim","\\infty","\\over","/","{","}"," ");
            $temp = str_replace($arr, "", $content);
            $length = strlen($temp);
            if ($length <= 15) {
                return 1;
            } else if ($length <= 40) {
                return 2;
            } else {
                return 3;
            }
        }
    }

    function formatPara($content) {
        $content = str_replace("sai","SAI",$content);
        $content = str_replace("đúng","ĐÚNG",$content);
        $content = str_replace("\\ne","tempne",$content);
        $content = str_replace("\\notin","tempnotin",$content);
        $content = str_replace("\\n","\n",str_replace("\\r\\n","\n",$content));
        $content = str_replace("tempne","\\ne",$content);
        $content = str_replace("tempnotin","\\notin",$content);
        return nl2br($content);
    }

    function sortByNumDesc($a, $b) {
        return $a["num"] < $b["num"];
    }

    function imageToImg($monID, $content, $max_height) {

        while(stripos($content, "|<|") != false) {
            $me = stripos($content, "|<|");
            $you = stripos($content, "|>|");
            $img = substr($content,$me,$you-$me+3);
            $img2 = str_replace(array("|<|","|>|","<br />"),"",$img);
            $newset = 0;
            $file_arr = array("upload/$monID/$img2.png","upload/$monID/$img2.jpg","../upload/$monID/$img2.png","../upload/$monID/$img2.jpg");
            foreach ($file_arr as $file) {
                if(file_exists($file)) {
                    $timefile = filemtime($file);
                    if($newset < $timefile) {
                        $temp = explode("/",$file);
                        $img2 = end($temp);
                        $newset = $timefile;
                    }
                }
            }
            if($newset == 0) {
                return "Ảnh bị lỗi: ".$img2;
            }
            $content = str_replace($img, "<span style='text-align: center;display: block;'><img src='http://localhost/www/TDUONG/luyenthi/upload/$monID/"."$img2' style='max-height:".$max_height."px;max-width:70%;' class='img-thumbnail img-responsive' /></span>", $content);
        }

//        $content = str_replace(array("|<|","|>|\n","|>|"),"",$content);
        $content = str_replace("--","-",$content);
        $content = str_replace("' ","'\\ ",$content);
        $content = str_replace(array("\\(","\\)"),"\$\$",$content);
        return formatPara($content);
    }

    function imageToImgDapan($monID, $content, $max_height) {

        while(stripos($content, "|<|") != false) {
            $me = stripos($content, "|<|");
            $you = stripos($content, "|>|");
            $img = substr($content,$me,$you-$me+3);
            $img2 = str_replace(array("|<|","|>|","<br />"),"",$img);
            $newset = 0;
            $file_arr = array("upload/$monID/$img2.png","upload/$monID/$img2.jpg","../upload/$monID/$img2.png","../upload/$monID/$img2.jpg");
            foreach ($file_arr as $file) {
                if(file_exists($file)) {
                    $timefile = filemtime($file);
                    if($newset < $timefile) {
                        $temp = explode("/",$file);
                        $img2 = end($temp);
                        $newset = $timefile;
                    }
                }
            }
            if($newset == 0) {
                return "Ảnh bị lỗi: ".$img2;
            }
            $content = str_replace($img, "<span style='text-align: center;display: block;'><img src='http://localhost/www/TDUONG/luyenthi/upload/$monID/"."$img2' style='max-height:".$max_height."px;max-width:70%;' class='img-thumbnail img-responsive' /></span>", $content);
        }

//        $content = str_replace(array("|<|","|>|\n","|>|","\\n"),"",$content);
	    $content = str_replace("--","-",$content);
        $content = str_replace("' ","'\\ ",$content);
        $content = str_replace(array("\\(","\\)"),"\$\$",$content);
        return $content;
    }

    function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir")
                        rrmdir($dir."/".$object);
                    else unlink   ($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    function getTextFromTag($string, $tag) {
        $content = "/<$tag>(.*?)<\/$tag>/";
        preg_match($content, $string, $text);
        return $text[1];
    }

    function unicodeConvert($str) {

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
            '-'=>array(' ','&quot;','?',':','/'),
            ''=>array('(',')',',','.','!')

        );



        foreach($unicode as $nonUnicode=>$uni){

            foreach($uni as $value)

                $str = str_replace($value,$nonUnicode,$str);

        }

        return $str;

    }

    function formatBack($ct) {
        $ct = str_replace("0o0","\"",$ct);
        $ct = str_replace("1o1","'",$ct);
        $ct = str_replace("2o2","</",$ct);
        $ct = str_replace("3o3","<",$ct);
        $ct = str_replace("4o4",">",$ct);
        $ct = str_replace("5o5","+",$ct);
        $ct = str_replace("6o6","&",$ct);
        $ct = str_replace("7o7","\\",$ct);
        $ct = str_replace("8o8","\n",$ct);
        $ct = trim($ct,"\n");
        return $ct;
    }

    function formatFacebook($facebook) {
        if($facebook=="" || $facebook=="X") {
            return "#";
        } else {
            $facebook=str_replace("m.facebook.com","facebook.com",$facebook);
            $facebook=str_replace("mobile.facebook.com","facebook.com",$facebook);
            return $facebook;
        }
    }

    function khoangNumber($number, $khoang, $lo, $hi) {
        for($i = $lo; $i <= $hi; $i += $khoang) {
            if($number == $i) {
                return $i;
            } else if($number - $i <= $khoang) {
                return nearNumber($i, $i + $khoang, $number);
            }
        }
    }

    function nearNumber($a, $b, $number) {
        if($number - $a <= $b - $number) {
            return $a;
        } else {
            return $b;
        }
    }

    function getNextCN() {
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

    function formatPrice($price) {
        $new_money=number_format($price, 0, ',', '.').'đ';

        return $new_money;
    }

