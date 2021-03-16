<?php
/**
 * Created by PhpStorm.
 * User: MartyZane
 * Date: 2020-02-13
 */
 
namespace until;
 
 
class BaseHelper
{
  
  public static function BaseResult($status_code,$status_message,$data,$total = 0)
  {
    $res['status_code'] = $status_code;
    $res['status_message'] = $status_message;
    $res['timestamp'] = time();
    $res['data'] = $data;
    if(!empty($total)){
      $res['total'] = $total;
    }
    
    //参考：https://www.cnblogs.com/JeromeZ/p/8274794.html
    //JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES = 320
    return json_encode($res,320);
  } 
}