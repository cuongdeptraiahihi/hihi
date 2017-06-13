<?php
    class DB_Functions
    {

        private $conn;

        // constructor
        function __construct()
        {
            require_once 'DB_Connect.php';
            // connecting to database
            $db = new Db_Connect();
            $this->conn = $db->connect();
        }

        // destructor
        function __destruct()
        {

        }

        public function getConfigMon($hsID)
        {

            $stmt = $this->conn->prepare("SELECT m.ID_LM,m.name,h.de,h.level,h.date_in FROM lop_mon AS m 
            INNER JOIN hocsinh_mon AS h ON h.ID_HS = ? AND h.ID_LM=m.ID_LM 
            WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM=m.ID_LM) ORDER BY m.ID_LM ASC");

            $stmt->bind_param("s", $hsID);

            if ($stmt->execute()) {
                return $stmt;
            } else {
                return NULL;
            }
        }

        public function getLopName($lopID)
        {

            $stmt = $this->conn->prepare("SELECT name FROM lop WHERE ID_LOP = ? ");

            $stmt->bind_param("s", $lopID);

            $stmt->execute();

            $data = $stmt->get_result()->fetch_assoc();

            $stmt->close();

            return $data["name"];
        }

        public function getUserByMasoAndPass($maso, $pass)
        {

            $stmt = $this->conn->prepare("SELECT * FROM hocsinh WHERE cmt = ?");

            $stmt->bind_param("s", $maso);

            if ($stmt->execute()) {
                $user = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                // verifying user password
                $encrypted_password = $user['password'];
                $hash = md5($pass);
                // check for password equality
                if ($encrypted_password == $hash) {
                    // user authentication details are correct
                    return $user;
                }
            } else {
                return NULL;
            }
        }

        public function getUserByMasoAndSdt($maso, $pass)
        {

            $stmt = $this->conn->prepare("SELECT * FROM hocsinh WHERE cmt = ?");

            $stmt->bind_param("s", $maso);

            if ($stmt->execute()) {
                $user = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                // check for password equality
                if (($user["sdt_bo"] == $pass) || ($user["sdt_me"] == $pass)) {
                    // user authentication details are correct
                    return $user;
                }
            } else {
                return NULL;
            }
        }

        public function getAllNotification($hsID) {

            $stmt = $this->conn->prepare("SELECT * FROM thongbao WHERE object='0' AND danhmuc='all' AND status='new' AND ID_LM IN (SELECT ID_LM FROM hocsinh_mon WHERE ID_HS='$hsID') ORDER BY datetime DESC LIMIT 1");

            $stmt->execute();

            return $stmt;
        }

        public function getUserNotification($hsID)
        {
            $stmt = $this->conn->prepare("SELECT * FROM thongbao WHERE ID_HS = ? ORDER BY datetime DESC LIMIT 30");

            $stmt->bind_param("s", $hsID);

            $stmt->execute();

            return $stmt;
        }

        public function isUserExisted($maso)
        {
            $stmt = $this->conn->prepare("SELECT cmt FROM hocsinh WHERE cmt = ?");

            $stmt->bind_param("s", $maso);

            $stmt->execute();

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // user existed
                $stmt->close();
                return true;
            } else {
                // user not existed
                $stmt->close();
                return false;
            }
        }

        public function getGender($gender)
        {
            if ($gender == 1) {
                return "Nam";
            } else {
                return "Nữ";
            }
        }

        public function getTruongHs($truong)
        {
            $stmt = $this->conn->prepare("SELECT name FROM truong WHERE ID_T = ?");

            $stmt->bind_param("s", $truong);

            if ($stmt->execute()) {
                $user = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                return $user['name'];
            } else {
                return "Không có";
            }
        }

        public function formatPrice($price)
        {
            $new_money = number_format($price, 0, ',', '.') . 'đ';

            return $new_money;
        }

        public function formatDiem($diem)
        {
            $diem=number_format((float)$diem, 2, '.', '');

            return $diem;
        }

        public function validMaso($maso)
        {
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
        }

        public function getInfoMon($monID)
        {

            $stmt = $this->conn->prepare("SELECT diem,diemtb,ca,ca_codinh,ca_hientai,diemdanh,cd_diem FROM mon WHERE ID_MON = ? ");

            $stmt->bind_param("s", $monID);

            $stmt->execute();

            return $stmt;
        }

        public function getInfoHs($hsID) {

            $stmt = $this->conn->prepare("SELECT cmt,fullname,avata FROM hocsinh WHERE ID_HS = ? ");

            $stmt->bind_param("s", $hsID);

            $stmt->execute();

            return $stmt;
        }

        public function getThachdau($hsID,$tdID) {

            $stmt = $this->conn->prepare("SELECT * FROM thachdau WHERE (ID_HS='$hsID' OR ID_HS2='$hsID') AND ID_STT = ?");

            $stmt->bind_param("s", $tdID);

            $stmt->execute();

            return $stmt;
        }

        public function getDiemString($monID)
        {

            $stmt = $this->conn->prepare("SELECT diem FROM mon WHERE ID_MON = ? ");

            $stmt->bind_param("s", $monID);

            $stmt->execute();

            $result = $stmt->get_result();
            $stmt->close();
            $data = $result->fetch_assoc();

            return $data["diem"];
        }

        public function getMonOfLop($lmID) {

            $stmt = $this->conn->prepare("SELECT ID_MON FROM lop_mon WHERE ID_LM = ? ");

            $stmt->bind_param("s", $lmID);

            $stmt->execute();

            $result = $stmt->get_result();
            $stmt->close();
            $data = $result->fetch_assoc();

            return $data["ID_MON"];
        }

        public function getDiemTBHs($hsID, $lmID)
        {

            $now = date("Y-m");
            $stmt = $this->conn->prepare("SELECT AVG(d.diem) AS diemtb FROM buoikt AS b INNER JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS=? AND d.diem!='X' AND d.loai IN ('0','1','3','5') AND d.ID_LM='$lmID' WHERE b.ngay LIKE '$now-%' AND b.ID_MON='".$this->getMonOfLop($lmID)."' ORDER BY b.ID_BUOI DESC");
            $stmt->bind_param("s", $hsID);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $data = $result->fetch_assoc();

            if ($data["diemtb"]) {
                return $data["diemtb"];
            } else {
                return 0;
            }
        }

        public function getDiemDetail($hsID, $buoiID, $lmID)
        {

            $stmt = $this->conn->prepare("SELECT ID_DIEM,diem,loai,de,note FROM diemkt WHERE ID_BUOI='$buoiID' AND ID_HS = ? AND ID_LM='$lmID'");

            $stmt->bind_param("s", $hsID);

            $stmt->execute();

            return $stmt;
        }

        public function getCmtLoai($loai)
        {
            switch ($loai) {
                case 0:
                    return "Làm bài trên lớp";
                case 1:
                    return "Làm bài ở nhà";
                case 2:
                    return "Nghỉ hẳn";
                case 3:
                    return "Hủy bài";
                case 4:
                    return "Mất bài / Nghỉ có phép";
                case 5:
                    return "Không đi thi";
                default:
                    return "Không xác định";
            }
        }

        public function getNoteMean($note)
        {

            $stmt = $this->conn->prepare("SELECT name FROM lido WHERE ID_LD = ? ");

            $stmt->bind_param("s", $note);

            $stmt->execute();

            $result = $stmt->get_result();
            $stmt->close();
            $data = $result->fetch_assoc();

            return $data["name"];
        }

        public function getNgayKt($buoiID)
        {

            $stmt = $this->conn->prepare("SELECT ngay FROM buoikt WHERE ID_BUOI = ? ");

            $stmt->bind_param("s", $buoiID);

            $stmt->execute();

            $result = $stmt->get_result();
            $stmt->close();
            $data = $result->fetch_assoc();

            return $data["ngay"];
        }

        public function getBuoiID($buoi)
        {

            $stmt = $this->conn->prepare("SELECT ID_BUOI FROM buoikt WHERE ngay = ? ");

            $stmt->bind_param("s", $buoi);

            $stmt->execute();

            $result = $stmt->get_result();
            $stmt->close();
            $data = $result->fetch_assoc();

            return $data["ID_BUOI"];
        }

        public function getLopHs($hsID)
        {

            $stmt = $this->conn->prepare("SELECT lop FROM hocsinh WHERE ID_HS = ? ");

            $stmt->bind_param("s", $hsID);

            $stmt->execute();

            $result = $stmt->get_result();
            $stmt->close();
            $data = $result->fetch_assoc();

            return $data["lop"];
        }

        public function getDiemTbLop($buoiID, $de, $lmID)
        {

            $stmt = $this->conn->prepare("SELECT diemtb FROM diemkt_tb WHERE ID_BUOI='$buoiID' AND detb='$de' AND ID_LM='$lmID'");

            $stmt->execute();

            $result = $stmt->get_result();
            $stmt->close();
            if($result->num_rows != 0) {
                $data = $result->fetch_assoc();

                return $data["diemtb"];
            } else {
                return 0;
            }
        }

        public function getPhatDiemKt($hsID, $buoiID, $lmID)
        {

            if($lmID == 1 || $lmID == 2) {
                $stmt = $this->conn->prepare("SELECT price FROM tien_ra WHERE ID_HS='$hsID' AND string='kiemtra_$lmID' AND object='$buoiID'");

                $stmt->execute();

                $result = $stmt->get_result();
                $stmt->close();
                if ($result->num_rows != 0) {
                    $data = $result->fetch_assoc();
                    return $data["price"];
                } else {
                    $stmt = $this->conn->prepare("SELECT price FROM tien_vao WHERE ID_HS='$hsID' AND string='kiemtra_$lmID' AND object='$buoiID'");

                    $stmt->execute();

                    $result = $stmt->get_result();
                    $stmt->close();
                    if ($result->num_rows != 0) {
                        $data = $result->fetch_assoc();
                        return "-" . $data["price"];
                    } else {
                        return "none";
                    }
                }
            } else {
                return "none";
            }
        }

        public function xemThongBao($hsID, $object, $danhmuc, $monID) {

            $stmt = $this->conn->prepare("UPDATE thongbao SET status='old' WHERE ID_HS='$hsID' AND object='$object' AND danhmuc='$danhmuc' AND ID_MON='$monID'");

            $stmt->execute();
        }

        public function xemThongBaoId($id_arr) {

            $stmt = $this->conn->prepare("UPDATE thongbao SET status='old' WHERE ID_TB IN ($id_arr)");

            $stmt->execute();
        }

        public function getCdDiem($buoiID,$hsID,$lmID) {

            $stmt = $this->conn->prepare("SELECT c.title,d.diem,d.cau,d.y FROM chuyende_diem AS d INNER JOIN chuyende AS c ON c.ID_CD=d.ID_CD WHERE d.ID_BUOI='$buoiID' AND d.ID_HS='$hsID' AND d.ID_LM='$lmID' ORDER BY cau ASC,y ASC");

            $stmt->execute();

            return $stmt;
        }

        public function addFirebaseToken($hsID, $token) {

            $stmt = $this->conn->prepare("SELECT ID,ID_HS FROM token WHERE token='$token'");
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            if($result->num_rows == 0) {
                $stmt = $this->conn->prepare("SELECT ID,time FROM token WHERE ID_HS='$hsID' ORDER BY time DESC");
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                $dem = $result->num_rows;
                if($dem > 5) {
                    $data = $result->fetch_assoc();
                    $time = $data["time"] + 1;
                    $stmt = $this->conn->prepare("DELETE FROM token WHERE ID_HS='$hsID' ORDER BY time ASC LIMIT ".($dem-5));
                    $stmt->execute();
                    $stmt->close();
                } else if($dem > 0) {
                    $data = $result->fetch_assoc();
                    $time = $data["time"] + 1;
                } else {
                    $time = 1;
                }
                $stmt = $this->conn->prepare("INSERT INTO token(ID_HS,token,type,time)
                                                        value('$hsID','$token','android','$time')");
                $stmt->execute();
                $stmt->close();
            } else {
                $data = $result->fetch_assoc();
                if($hsID != $data["ID_HS"]) {
                    $stmt = $this->conn->prepare("UPDATE token SET ID_HS='$hsID' WHERE ID='$data[ID]'");
                    $stmt->execute();
                }
            }
        }

        public function updateToken($hsID,$token) {
            $stmt = $this->conn->prepare("SELECT ID,time FROM token WHERE token='$token'");
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            if($result->num_rows == 0) {
                $stmt = $this->conn->prepare("SELECT MAX(time) AS max FROM token WHERE ID_HS='$hsID'");
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                if($result->num_rows != 0) {
                    $data = $result->fetch_assoc();
                    $time = $data["max"] + 1;
                } else {
                    $time = 1;
                }
                $stmt = $this->conn->prepare("INSERT INTO token(ID_HS,token,type,time)
                                                        value('$hsID','$token','android','$time')");
                $stmt->execute();
                $stmt->close();
            } else {
                $data = $result->fetch_assoc();
                $time = $data["time"] + 1;
                $stmt = $this->conn->prepare("UPDATE token SET ID_HS='$hsID',time='$time' WHERE ID='$data[ID]'");
                $stmt->execute();
            }
        }
    }
?>