<?php
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    require_once "mess/chatfuel/vendor/autoload.php";
    require_once "mess/DB_Chatfuel.php";
    require_once("mess/httpclient/HTTP/Request2.php");
    use juno_okyo\Chatfuel;

    $chatfuel = new Chatfuel(TRUE);
    if(isset($_GET["action"]) && isset($_GET["chatfuel_user_id"])) {
        $action = addslashes($_GET["action"]);
        $fbId = addslashes($_GET["chatfuel_user_id"]);
        $db = new DB_Chatfuel();
        $result = $db->getUserInfo($fbId);
        if($result->num_rows == 0 && $action != "sign-ma-so" && $action != "handle-message") {
            $chatfuel->sendTextCard("Bạn chưa kết nối với Bot! Bạn hãy đăng nhập nhé!", array($chatfuel->createButtonToBlock("Đăng nhập", "askMaso")));
        } else {
            $data = $result->fetch_assoc();
            switch ($action) {
                case "sign-ma-so":
                    if(isset($_GET["ma_so"]) && isset($_GET["mat_khau"])) {
                        $maso = addslashes($_GET["ma_so"]);
                        $mk = md5(addslashes($_GET["mat_khau"]));
                        $hsID = $db->login($maso, $mk);
                        if($hsID != 0) {
                            $db->sign($fbId, $hsID);
                            $chatfuel->sendTextCard("Bạn đã kết nối tài khoản thành công!!! :D", array($chatfuel->createButtonToBlock("Bạn có thể làm gì?", "getCanDoList")));
                        } else {
                            $chatfuel->sendTextCard("Tài khoản không tồn tại! :(", array($chatfuel->createButtonToBlock("Đăng nhập", "askMaso")));
                        }
                    } else {
                        getError($_GET);
                    }
                    break;
                case "quit-ma-so":
                    $db->logout($fbId);
                    break;
                case "get-lich-hoc":
                    $db->getLichHocList($data["ID_HS"], $data["ID_LM"], $data["ID_LM"], $data["ID_MON"]);
                    break;
                case "get-lich-thi":
                    $db->getLichHocList($data["ID_HS"], $data["ID_LM"], 0, $data["ID_MON"]);
                    break;
                case "si-so-ca-hoc":
                    if(isset($_GET["caID"])) {
                        $caID = addslashes($_GET["caID"]);
                        $db->getSiso($caID);
                    }
                    break;
                case "doi-ca-hoc":
                    if(isset($_GET["hsID"]) && isset($_GET["type"]) && isset($_GET["caID"]) && isset($_GET["cum"]) && isset($_GET["lmID"])) {
                        $hsID = addslashes($_GET["hsID"]);
                        $type = addslashes($_GET["type"]);
                        $caID = addslashes($_GET["caID"]);
                        $cum = addslashes($_GET["cum"]);
                        $lmID = addslashes($_GET["lmID"]);
                        $db->doiCahoc($hsID, $type, $caID, $cum, $lmID);
                    } else {
                        getError($_GET);
                    }
                    break;
                case "giai-tri":
                    if(isset($_GET["giai_tri"])) {
                        $db->getSpecial(substr($_GET["giai_tri"], 1));
                    } else {
                        getError($_GET);
                    }
                    break;
                case "handle-message":
                    if(isset($_GET["message"])) {
                        $special = substr($_GET["message"], 0, 1);
                        if($special == "#") {
                            $db->getSpecial(substr($_GET["message"], 1));
                        } else {
                            $msg = addslashes($_GET["message"]);
                            $pattern = "/\d+-\d+-(.*)/";
                            $datas = preg_match_all($pattern, $msg, $matches);
                            $datas = $matches[0];
                            if (count($datas) > 0) {
                                $temp = explode("-", addslashes(trim($datas[0])));
                                $maso = $temp[0] . "-" . $temp[1];
                                $mk = "";
                                for ($i = 2; $i < count($temp); $i++) {
                                    $mk .= $temp[$i] . "-";
                                }
                                $mk = md5(rtrim($mk, "-"));
                                $hsID = $db->login($maso, $mk);
                                if ($hsID != 0) {
                                    $db->sign($fbId, $hsID);
                                    $chatfuel->sendTextCard("Bạn đã kết nối tài khoản thành công!!! :D", array($chatfuel->createButtonToBlock("Bạn có thể làm gì?", "getCanDoList")));
                                } else {
                                    $chatfuel->sendTextCard("Tài khoản không tồn tại! :(", array($chatfuel->createButtonToBlock("Đăng nhập", "askMaso")));
                                }
                            } else {
                                getDefault();
                            }
                        }
                    } else {
                        getError($_GET);
                    }
                    break;
                case "computer-vision":
                    if(isset($_GET["cv_anh"])) {
                        $anh = $_GET["cv_anh"];
                        if(validUrl($anh)) {
                            $img = 'hocsinh/api/microsoft/cvanh_' . $db->randCode(10) . '.jpeg';
                            file_put_contents($img, file_get_contents($anh));
                            if (file_exists($img)) {
                                $db->handleCV("https://bgo.edu.vn/" . $img);
                                unlink($img);
                            } else {
                                $chatfuel->sendText("Không thể xử lý ảnh!");
                            }
                        } else {
                            $chatfuel->sendText("Bạn hãy gửi ảnh cho mình nhé!");
                        }
                    } else {
                        getError($_GET);
                    }
                    break;
                case "emotion":
                    if(isset($_GET["e_anh"])) {
                        $anh = $_GET["e_anh"];
                        if(validUrl($anh)) {
                            $img = 'hocsinh/api/microsoft/eanh_' . $db->randCode(10) . '.jpeg';
                            file_put_contents($img, file_get_contents($anh));
                            if (file_exists($img)) {
                                $db->handleEmotion("https://bgo.edu.vn/" . $img);
                                unlink($img);
                            } else {
                                $chatfuel->sendText("Không thể xử lý ảnh!");
                            }
                        } else {
                            $chatfuel->sendText("Bạn hãy gửi ảnh cho mình nhé!");
                        }
                    } else {
                        getError($_GET);
                    }
                    break;
                default:
                    getDev();
                    break;
            }
        }
    } else {
        $chatfuel->sendText("Đã có lỗi xảy ra!");
    }

    function getDev() {
        global $chatfuel;

        $chatfuel->sendText("Mình đang được phát triển! Cảm ơn bạn!");
    }

    function getDefault() {
        global $chatfuel;

        $buttons = array(
            $chatfuel->createQuickReplyButton("Bạn có thể làm gì?", array("getCanDoList")),
            $chatfuel->createQuickReplyButton("Hướng dẫn", array("getHelp")),
            $chatfuel->createQuickReplyButton("Hỗ trợ", array("getSupport"))
        );

        $chatfuel->sendTextQuickReply("Mình không hiểu câu hỏi của bạn! :(", $buttons);
    }

    function validUrl($url) {
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            return false;
        }
        return true;
    }

    function getError($array) {
        global $chatfuel;

        $chatfuel->sendText("Đã có lỗi xảy ra! Bạn hãy chụp màn hình gửi cho Admin nhé! JSON: ".json_encode($array));
    }
?>