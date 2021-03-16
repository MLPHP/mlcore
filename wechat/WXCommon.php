<?php
namespace wechat;

class WXCommon{
    public function demo(){
        echo 'demo';
    }

    public function httpRequest($url, $data='', $method='GET'){

        $curl = curl_init();  
        curl_setopt($curl, CURLOPT_URL, $url);  
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);  
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);  
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);  
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);  
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);  
        if($method=='POST')
        {
            curl_setopt($curl, CURLOPT_POST, 1); 
            if ($data != '')
            {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  
            }
        }

        curl_setopt($curl, CURLOPT_TIMEOUT, 30);  
        curl_setopt($curl, CURLOPT_HEADER, 0);  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
        $result = curl_exec($curl);  
        curl_close($curl);  
        return $result;
    } 

    /**
     * 方法名称：生成二维码
     * 作者: MartyZane
     */
    public function generalQcrode($serialnumber){
        header('content-type:text/html;charset=utf-8');

        $APPID = "wxec4a7eea6c5260cd"; 
        $APPSECRET =  "f47e234cdfde55997002f04380ddf515"; 
        $access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$APPSECRET";

        if (!session_id()) session_start();
        $_SESSION['access_token'] = "";
        $_SESSION['expires_in'] = 0;

        $ACCESS_TOKEN = "";
        if(!isset($_SESSION['access_token']) || (isset($_SESSION['expires_in']) && time() > $_SESSION['expires_in']))
        {
            
            $json = self::httpRequest( $access_token );
            $json = json_decode($json,true); 
            // var_dump($json);
            $_SESSION['access_token'] = $json['access_token'];
            $_SESSION['expires_in'] = time()+7200;
            $ACCESS_TOKEN = $json["access_token"]; 
        } 
        else{

            $ACCESS_TOKEN =  $_SESSION["access_token"]; 
        }
        $qcode ="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=$ACCESS_TOKEN";

        $param = json_encode(array(
            "scene" => "$serialnumber",
            "page" => 'pages/map/map',
            "width"=> 280));

        $result = self::httpRequest( $qcode, $param,"POST");
        //var_dump($result);
        $base64_image ="data:image/jpeg;base64,".base64_encode( $result );
        return base64_encode( $result );
    }

    public function qrcodeDemo($serialnumber){
        header('content-type:text/html;charset=utf-8');

        $APPID = "wxec4a7eea6c5260cd"; 
        $APPSECRET =  "f47e234cdfde55997002f04380ddf515"; 
        $access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$APPSECRET";

        if (!session_id()) session_start();
        $_SESSION['access_token'] = "";
        $_SESSION['expires_in'] = 0;

        $ACCESS_TOKEN = "";
        if(!isset($_SESSION['access_token']) || (isset($_SESSION['expires_in']) && time() > $_SESSION['expires_in']))
        {
            
            $json = self::httpRequest( $access_token );
            $json = json_decode($json,true); 
            // var_dump($json);
            $_SESSION['access_token'] = $json['access_token'];
            $_SESSION['expires_in'] = time()+7200;
            $ACCESS_TOKEN = $json["access_token"]; 
        } 
        else{

            $ACCESS_TOKEN =  $_SESSION["access_token"]; 
        }


        $qcode ="https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=$ACCESS_TOKEN";

        $param = json_encode(array(
            "scene" => "$serialnumber",
            "page" => 'pages/map/map',
            "width"=> 430));

        $result = self::httpRequest( $qcode, $param,"POST");
        var_dump($result);
        $base64_image ="data:image/jpeg;base64,".base64_encode( $result );
        return base64_encode( $result );
    }

    
}