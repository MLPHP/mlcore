<?php
/**
 * 阿里云短信服务
 */
namespace until;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;

use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;

// Download：https://github.com/aliyun/openapi-sdk-php
// Usage：https://github.com/aliyun/openapi-sdk-php/blob/master/README.md

//header("Content-type:text/html; charset=UTF-8");

class AliyunSmsSdk {

	/**
	 * 使用AK&SK初始化账号Client
	 * @param string $accessKeyId
	 * @param string $accessKeySecret
	 * @return Dysmsapi Client
	 */
	public function createClient($accessKeyId, $accessKeySecret){
		$config = new Config([
			// 您的AccessKey ID
			"accessKeyId" => $accessKeyId,
			// 您的AccessKey Secret
			"accessKeySecret" => $accessKeySecret
		]);
		// 访问的域名
		$config->endpoint = "dysmsapi.aliyuncs.com";
		return new Dysmsapi($config);
	}

	/**
	 * 发送短信
	 *
	 * @param string $mobile 		手机号码
	 * @param string $msg 			短信内容
	 */
	public function sendSMS($mobile, $msg,$template) {

		$client = self::createClient("LTAI5tJbDKJ16AWa6rwJMsDQ", "sCBG1UJ9Tznq29QbD08gVNzDBnzYuA");
		$sendSmsRequest = new SendSmsRequest([
			"phoneNumbers" => $mobile,
			"signName" => "青岛瑞和农牧",
			"templateCode" => $template, //"SMS_215400108",
			"templateParam" => '{"code":"' . $msg . '"}'
		]);
		// 复制代码运行请自行打印 API 的返回值
		return $client->sendSms($sendSmsRequest);
	}

	/**
	 * 通过CURL发送HTTP请求
	 * @param string $url  //请求URL
	 * @param array $postFields //请求参数 
	 * @return mixed
	 *  
	 */
	private function curlPost($url,$postFields){
		$postFields = json_encode($postFields);
		$ch = curl_init ();
		curl_setopt( $ch, CURLOPT_URL, $url ); 
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json; charset=utf-8'   //json版本需要填写  Content-Type: application/json;
			)
		);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); //若果报错 name lookup timed out 报错时添加这一行代码
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
         	curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
       		curl_setopt( $ch, CURLOPT_TIMEOUT,60); 
        	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
        	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
		$ret = curl_exec ( $ch );
        if (false == $ret) {
            $result = curl_error(  $ch);
        } else {
            $rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
            if (200 != $rsp) {
                $result = "请求状态 ". $rsp . " " . curl_error($ch);
            } else {
                $result = $ret;
            }
        }
		curl_close ( $ch );
		return $result;
	}
	
}





?>
