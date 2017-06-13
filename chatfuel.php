<?php
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    require_once "mess/chatfuel/vendor/autoload.php";
    require_once "mess/DB_Chatfuel.php";
    use juno_okyo\Chatfuel;

    $chatfuel = new Chatfuel(TRUE);
    if(isset($_GET["action"]) && isset($_GET["fb_id"])) {
        $action = addslashes($_GET["action"]);
        $fbId = addslashes($_GET["fb_id"]);
        $db = new DB_Chatfuel();
        $result = $db->getUserInfo($fbId);
        if($result->num_rows == 0 && $action != "sign-ma-so" && $action != "handle-message") {
            $chatfuel->sendTextCard("Bạn chưa kết nối với Bot! Bạn hãy cung cấp các thông tin để kết nối nhé!", array($chatfuel->createButtonToBlock("Đăng nhập", "askMaso")));
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
                        getError($action);
                    }
                    break;
                case "get-lich-hoc":
                    $db->getLichHocList($data["ID_HS"], $data["ID_LM"], $data["ID_LM"], $data["ID_MON"]);
                    break;
                case "get-lich-thi":
                    $db->getLichHocList($data["ID_HS"], $data["ID_LM"], 0, $data["ID_MON"]);
                    break;
                case "":

                    break;
                case "giai-tri":
                    if(isset($_GET["giai_tri"])) {
                        $db->getSpecial(substr($_GET["giai_tri"], 1));
                    } else {
                        getError($action);
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
                        getError($action);
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

    function getError($action) {
        global $chatfuel;

        $chatfuel->sendText("Đã có lỗi xảy ra! Bạn hãy chụp màn hình gửi cho Admin nhé! ACTION: ".$action);
    }
?>