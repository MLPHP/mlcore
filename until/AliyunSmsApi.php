<?php
/**
 * 阿里云短信服务
 */
namespace until;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use \think\facade\Cache;
use \think\facade\Env;
use \think\facade\Config;

// Download：https://github.com/aliyun/openapi-sdk-php
// Usage：https://github.com/aliyun/openapi-sdk-php/blob/master/README.md

header("Content-type:text/html; charset=UTF-8");

class AliyunSmsApi {
	/**
	 * 发送短信
	 *
	 * @param string $mobile 		手机号码
	 * @param string $msg 			短信内容
	 */
	public function sendSMS($mobile, $msg, $code) {
		
		AlibabaCloud::accessKeyClient(Config::get('ali_oss_accessKey'), Config::get('ali_oss_secretKey'))
                        ->regionId(Config::get('ali_sms_regionId'))
                        ->asDefaultClient();

		try {
			$result = AlibabaCloud::rpc()
				->product('Dysmsapi')
				// ->scheme('https') // https | http
				->version('2017-05-25')
				->action('SendSms')
				->method('POST')
				->host('dysmsapi.aliyuncs.com')
				->options([
						'query' => [
							'RegionId' => Config::get('ali_sms_regionId'),
							'PhoneNumbers' => $mobile,
							'SignName' => Config::get('ali_sms_sign'),
							// 'TemplateCode' => Config::get('ali_sms_templateCode'),
							'TemplateCode' => $code,
							'TemplateParam' => $msg,
						],
					])
				->request();
			print_r($result->toArray());
		} catch (ClientException $e) {
			echo $e->getErrorMessage() . PHP_EOL;
		} catch (ServerException $e) {
			echo $e->getErrorMessage() . PHP_EOL;
		}
		return $result;
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
