<?php
class Flick {

    private $key;
    private $secret;
    private $callback;

    const REQUEST_TOKEN_URL = "http://www.flickr.com/services/oauth/request_token";
    const AUTH_URL = "http://www.flickr.com/services/oauth/authorize";
    const ACCESS_URL = "http://www.flickr.com/services/oauth/access_token";
    const API_URL = "https://api.flickr.com/services/rest";
    const UPLOAD_URL = "https://up.flickr.com/services/upload/";

    const VERSION = "1.0";
    const SIG_METHOD = "HMAC-SHA1";
    const OAUTH_TOKEN = "72157677841383191-1a014c067e54fa1a";
    const OAUTH_TOKEN_SECRET = "302d44eb4e885468";
    const USER_ID = "131890603@N08";

    private $mt, $rand, $oauth_nonce, $timestamp;

    public function __construct($key, $secret = NULL, $callback = NULL) {
        if (session_id() == '') {
            session_start();
        }
        $this->key = $key;
        $this->secret = $secret;
        $this->callback = $callback;

        $this->mt = microtime();
        $this->rand = mt_rand();
        $this->oauth_nonce = md5($this->mt . $this->rand);
        $this->timestamp = gmdate('U');
    }

    public function signRequest() {
        $callbackURL = $this->callback;

        $basestring = "oauth_callback=".urlencode($callbackURL)."&oauth_consumer_key=".$this->key."&oauth_nonce=".$this->oauth_nonce."&oauth_signature_method=".self::SIG_METHOD."&oauth_timestamp=".$this->timestamp."&oauth_version=".self::VERSION;
        $baseurl         = "GET&".urlencode(self::REQUEST_TOKEN_URL)."&".urlencode($basestring);
        $hashkey         = $this->secret."&";
        $oauth_signature = base64_encode(hash_hmac('sha1', $baseurl, $hashkey, true));

        $fields = array(
            'oauth_nonce'=>$this->oauth_nonce,
            'oauth_timestamp'=>$this->timestamp,
            'oauth_consumer_key'=>$this->key,
            'oauth_signature_method'=>self::SIG_METHOD,
            'oauth_version'=>self::VERSION,
            'oauth_signature'=>$oauth_signature,
            'oauth_callback'=>$callbackURL
        );
        $fields_string = "";

        foreach($fields as $key=>$value)
            $fields_string .= "$key=".urlencode($value)."&";

        $fields_string = rtrim($fields_string,'&');
        $url = self::REQUEST_TOKEN_URL."?".$fields_string;

        return $this->sendRequest($url);
    }

    public function auth($permission = "read", $oauth_token) {
        $url = self::AUTH_URL."?oauth_token=".$oauth_token."&perms=".$permission;

        header("location:".$url);
        exit();
    }

    public function getAccessToken($oauth_token, $oauth_token_secret, $oauth_verifier) {
        $basestring = "oauth_consumer_key=".$this->key."&oauth_nonce=".$this->oauth_nonce."&oauth_signature_method=".self::SIG_METHOD."&oauth_timestamp=".$this->timestamp."&oauth_token=".$oauth_token."&oauth_verifier=".$oauth_verifier."&oauth_version=".self::VERSION;
        $basestring = "GET&".urlencode(self::ACCESS_URL)."&".urlencode($basestring);
        $hashkey = $this->secret."&".$oauth_token_secret;
        $oauth_signature = base64_encode(hash_hmac('sha1', $basestring, $hashkey, true));

        $fields = array(
            'oauth_nonce'=>$this->oauth_nonce,
            'oauth_timestamp'=>$this->timestamp,
            'oauth_verifier'=>$oauth_verifier,
            'oauth_consumer_key'=>$this->key,
            'oauth_signature_method'=>self::SIG_METHOD,
            'oauth_version'=>self::VERSION,
            'oauth_token' => $oauth_token,
            'oauth_signature'=>$oauth_signature
        );

        $fields_string = "";
        foreach($fields as $key=>$value)
            $fields_string .= "$key=".urlencode($value)."&";
        $fields_string = rtrim($fields_string,'&');

        $url = self::ACCESS_URL."?".$fields_string;

        return $this->sendRequest($url);
    }

    public function upload($photo, $title, $tags) {
        $fields = array(
            'title' => $title,
            'tags' => $tags,
            'is_public' => 0,
            'is_friend' => 0,
            'is_family' => 0,
            'oauth_nonce' => $this->oauth_nonce,
            'oauth_timestamp' => $this->timestamp,
            'oauth_consumer_key' => $this->key,
            'oauth_signature_method' => self::SIG_METHOD,
            'oauth_version' => self::VERSION,
            'oauth_token' => self::OAUTH_TOKEN,
        );

        ksort($fields);
        $basestring = "";
        foreach ($fields as $key => $values) {
            $basestring .= "&" . $key . "=" . $values;
        }
        $basestring = substr($basestring,1);

        $basestring = "POST&".urlencode(self::UPLOAD_URL)."&".urlencode($basestring);
        $hashkey    = $this->secret."&".self::OAUTH_TOKEN_SECRET;
        $oauth_signature = base64_encode(hash_hmac('sha1', $basestring, $hashkey, true));

        $fields["oauth_signature"] = $oauth_signature;

        if(stripos($photo,".png") != false) {
            $cfile = curl_file_create(realpath($photo),"image/png",'photo');
        } else {
            $cfile = curl_file_create(realpath($photo),"image/jpeg",'photo');
        }
        $fields['photo'] = $cfile;

        $curl = curl_init();
        curl_setopt ($curl, CURLOPT_URL, self::UPLOAD_URL);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        if ($response === FALSE) {
            die('Curl failed: ' . curl_error($curl));
        }
        curl_close($curl);
//        var_dump($response);
        return $this->getResponseFromXML($response);
    }

    public function request($method, $args) {
        $fields = array(
            'method' => $method,
            'oauth_nonce' => $this->oauth_nonce,
            'oauth_timestamp' => $this->timestamp,
            'oauth_consumer_key' => $this->key,
            'oauth_signature_method' => self::SIG_METHOD,
            'oauth_version' => self::VERSION,
            'oauth_token' => self::OAUTH_TOKEN,
            'format' => 'json',
            'nojsoncallback' => '1'
        );

        $fields = array_merge($fields, $args);
        ksort($fields);
        if(isset($fields["user_id"])) {
            $fields["user_id"] = self::USER_ID;
        }

        $basestring = "";
        foreach ($fields as $key => $values) {
            $basestring .= "&" . $key . "=" . urlencode($values);
        }
        $basestring = substr($basestring,1);
        $fields_string = $basestring;
//        $basestring = "format=json&method=".$method."&nojsoncallback=1&oauth_consumer_key=".$this->key."&oauth_nonce=".$this->oauth_nonce."&oauth_signature_method=".self::SIG_METHOD."&oauth_timestamp=".$this->timestamp."&oauth_token=".self::OAUTH_TOKEN."&oauth_version=".self::VERSION."&user_id=".self::USER_ID;
        $basestring = "GET&".urlencode(self::API_URL)."&".urlencode($basestring);
        $hashkey    = $this->secret."&".self::OAUTH_TOKEN_SECRET;
        $oauth_signature = base64_encode(hash_hmac('sha1', $basestring, $hashkey, true));

        $fields["oauth_signature"] = $oauth_signature;

        $url = self::API_URL."?".$fields_string;

        return $this->sendRequest($url);
    }

    public function sendRequest($url, $timeout = 10) {
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $result = curl_exec($ch);
        curl_close($ch);
        if($result) {
            $respones = explode('&', $result);

            $n = count($respones);
            if ($n >= 2) {
                $result_arr = array();
                for ($i = 0; $i < $n; $i++) {
                    $temp = explode("=", $respones[$i]);
                    $result_arr[$temp[0]] = $temp[1];
                }
                return $result_arr;
            } else {
                return $result;
            }
        } else {
            $result = file_get_contents($url);
            return $result;
        }
    }

    private function getResponseFromXML($xml)
    {
        $rsp = array();
        $stat = 'fail';
        $matches = array();
        preg_match('/<rsp stat="(ok|fail)">/s', $xml, $matches);
        if (count($matches) > 0)
        {
            $stat = $matches[1];
        }
        if ($stat == 'ok')
        {
            // do this in individual steps in case the order of the attributes ever changes
            $rsp['stat'] = $stat;
            $photoid = array();
            $matches = array();
            preg_match('/<photoid.*>(\d+)<\/photoid>/s', $xml, $matches);
            if (count($matches) > 0)
            {
                $photoid['_content'] = $matches[1];
            }
            $matches = array();
            preg_match('/<photoid.* secret="(\w+)".*>/s', $xml, $matches);
            if (count($matches) > 0)
            {
                $photoid['secret'] = $matches[1];
            }
            $matches = array();
            preg_match('/<photoid.* originalsecret="(\w+)".*>/s', $xml, $matches);
            if (count($matches) > 0)
            {
                $photoid['originalsecret'] = $matches[1];
            }
            $rsp['photoid'] = $photoid;
        }
        else
        {
            $rsp['stat'] = 'fail';
            $err = array();
            $matches = array();
            preg_match('/<err.* code="([^"]*)".*>/s', $xml, $matches);
            if (count($matches) > 0)
            {
                $err['code'] = $matches[1];
            }
            $matches = array();
            preg_match('/<err.* msg="([^"]*)".*>/s', $xml, $matches);
            if (count($matches) > 0)
            {
                $err['msg'] = $matches[1];
            }
            $rsp['err'] = $err;
        }
        return $rsp;
    }
}
?>