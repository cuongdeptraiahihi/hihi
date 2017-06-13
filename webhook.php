<?php
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    require_once "wolframalpha/WolframAlphaEngine.php";
    require_once "model/Flick.php";
    require_once "model/Base32.php";
    require_once "mess/DB_Functions.php";

//$mess="00-0372-00-0372";
//$pattern="/\d+-\d+-(.*)/";
//$datas = preg_match_all($pattern,$mess,$matches);
//echo json_encode($matches[0]);

//$db = new DB_Functions();
//$message = "Mình đã đóng tiền học tháng 3 chưa?";
//$handle = handleMessage($message);
//echo json_encode($db->executeMessage("cc",$handle));

    // Kiểm tra mã webhook
    $check_code = false;
    if (isset($_GET['hub_verify_token'])) {
        if ($_GET['hub_verify_token'] === 'toi_la_cho_ca_ca') {
            $check_code = true;
            echo $_GET['hub_challenge'];
            return;
        } else {
            echo 'Invalid Verify Token';
            return;
        }
    }

    // Nếu tồn tại người gửi
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['entry'][0]['messaging'][0]['sender']['id'])) {

        $sender = $input['entry'][0]['messaging'][0]['sender']['id'];   // ID người gửi

        if (isset($input['entry'][0]['messaging'][0]["account_linking"]["status"])) {

//            sendTypingOn($sender);  // Gửi trạng thái đang trả lời``

            $sendJson = array();

            $db = new DB_Functions();

            if ($input['entry'][0]['messaging'][0]["account_linking"]["status"] == "linked") {

                $result = $db->sign($sender, $input['entry'][0]['messaging'][0]["account_linking"]["authorization_code"]);

                $sendJson[] = randMessageByType($sender, "welcome");

            } else if ($input['entry'][0]['messaging'][0]["account_linking"]["status"] == "unlinked") {

                $db->out($sender);

                $sendJson[] = randMessageByType($sender, "dang-xuat");

            } else {

                $sendJson[] = randMessageByType($sender, "error");
            }

            sendViaApiMore($sendJson);

        } else if (isset($input['entry'][0]['messaging'][0]['postback'])) {

//            sendTypingOn($sender);  // Gửi trạng thái đang trả lời

            $db = new DB_Functions();

            $sendJson = $db->handlePostBack($sender, $input['entry'][0]['messaging'][0]['postback']['payload']);

            if (count($sendJson) == 0)
                sendTypingOff($sender);
            else sendViaApiMore($sendJson);

        } else if (isset($input['entry'][0]['messaging'][0]['optin'])) {

            $sendJson = array();

            $db = new DB_Functions();

            if (isset($input['entry'][0]['messaging'][0]['optin']["ref"])) {
                $sendJson = $db->handlePostBack($sender, $input['entry'][0]['messaging'][0]['optin']["ref"]);
            } else {
                $sendJson[] = randMessageByType($sender, "error");
            }

            if (count($sendJson) == 0)
                sendTypingOff($sender);
            else sendViaApiMore($sendJson);

        } else if (isset($input['entry'][0]['messaging'][0]['message'])) {

//            sendTypingOn($sender);  // Gửi trạng thái đang trả lời

            $sendJson = array();

            if (isset($input['entry'][0]['messaging'][0]['message']['text'])) {
                // Nếu tin nhắn dạng text
                $message = formatMessage($input['entry'][0]['messaging'][0]['message']['text']);

                $db = new DB_Functions();

                $special = substr($message, 0, 1);

                if ($special == "#") {
                    $str = substr($message, 1);
                    if($str == "food") {
                        $sendJson = $db->prepareData($sender, array("key_found" => "tra_cuu_thuc_an", "data" => array()), "");
                    } else if($str == "game") {
                        $sendJson = $db->getGameIntro($sender);
                    } else {
                        $sendJson[] = randMessageByType($sender, $str);
                    }
//                    if(substr($message,1,1) == "f") {
//                        $ct = substr($message,2);
////                        $sendJson = $db->getWolframAlpha($sender, $ct);
//                        $list = array();
//                        $list[] = array(
//                            "type" => "web_url",
//                            "url" => "https://bgo.edu.vn/wolframalpha.php?sender=$sender&q=$ct",
//                            "title" => "Xem kết quả"
//                        );
//                        $sendJson[] = getListButtons($sender, "Bạn chọn Xem kết quả để xem nguyên hàm của $ct!", json_encode($list));
//                    }
                } else if (isset($input['entry'][0]['messaging'][0]['message']['quick_reply']) && isset($input['entry'][0]['messaging'][0]['message']['quick_reply']['payload'])) {

                    $payload = $input['entry'][0]['messaging'][0]['message']['quick_reply']['payload'];

                    $sendJson = $db->handlePostBack($sender, $payload);

                } else {

                    $sendJson = $db->prepareData($sender, NULL, $message);

                }
            } else if (isset($input['entry'][0]['messaging'][0]['message']['attachments'][0])) {
                // Nếu tin nhắn dạng image, audio, video
                $sendJson[] = randMessageByType($sender, "dev");
            } else {
                // Nếu là định dạng không được hỗ trợ
            }

            if (count($sendJson) == 0)
                sendTypingOff($sender);
//                sendSeen($sender);
            else sendViaApiMore($sendJson);

        }
    }

?>