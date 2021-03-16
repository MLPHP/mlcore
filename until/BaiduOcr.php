<?php
/**
 * Created by PhpStorm.
 * User: MartyZane
 * Date: 2020-02-25
 */
 
namespace until;
 
use \think\facade\Env;
use \think\facade\Config;
use \think\facade\Cache;

class BaiduOcr
{

  /**
   * 获取访问Token
   */
  public static function GetAccessToken($apiKey,$secretKey,$redisKey){
    $url = 'https://aip.baidubce.com/oauth/2.0/token';
    $post_data['grant_type']       = 'client_credentials';
    $post_data['client_id']      = $apiKey;
    $post_data['client_secret'] = $secretKey;
    $o = "";
    foreach ( $post_data as $k => $v ) 
    {
    	$o.= "$k=" . urlencode( $v ). "&" ;
    }
    $post_data = substr($o,0,-1);
    
    $res = self::RequestPost($url, $post_data);
    //反编码json
    $data = json_decode($res,false);//第二个参数false则返回object类型，false可以默认不写
    $dataArr = (array)$data;
    // var_dump((array)$data);
    // echo $dataArr['access_token'];
    //设置验证码的过期时间
    Cache::store('redis')->set($redisKey,$dataArr['access_token'],259200);
    return $dataArr['access_token'];
  }

  /**
   * 发起http post请求(REST API), 并获取REST请求的结果
   * @param string $url
   * @param string $param
   * @return - http response body if succeeds, else false.
   */
  public static function RequestPost($url = '', $param = '')
  {
    if (empty($url) || empty($param)) {
      return false;
    }

    $postUrl = $url;
    $curlPost = $param;
    // 初始化curl
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $postUrl);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    // 要求结果为字符串且输出到屏幕上
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    // post提交方式
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    // 运行curl
    $data = curl_exec($curl);
    curl_close($curl);

    return $data;
  }
}