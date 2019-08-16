<?php
namespace app\api\controller;
use think\Controller;

class ApiController extends Controller{
//     private  function request_post($url = '', $param = '') {
//         if (empty($url) || empty($param)) {
//             return false;
//         }
//         $postUrl = $url;
//         $curlPost = $param;
//         $ch = curl_init();//初始化curl
//         curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
//         curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
//         curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
//         curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
//         $data = curl_exec($ch);//运行curl
//         curl_close($ch);
//         return $data;
//     }
    
    private  function curl_get($url){
        $testurl = $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $testurl);
        //参数为1表示传输数据，为0表示直接输出显示。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //参数为0表示不带头文件，为1表示带头文件
        curl_setopt($ch, CURLOPT_HEADER,0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
        //print_r($output);
    }
     public function txffc(){
         $url="http://qq-online.org/get_result_list";
         $jsonres=$this->curl_get($url);
         $res=array();
         $arr=json_decode($jsonres, true);
         foreach ($arr as $i=>$value1){
             if(is_array($value1)){
                 foreach($value1 as $j=>$value2){
                     if (is_array($value2)){
                         foreach($value2 as $k=>$val){
                             if($k=="issue"){
                                 $res["expect"]=$val;
                                 $res["jxexpect"]=substr($val,-4);
                             }
                             if($k=="count"){
                                 $res["opencode"]=substr($val,-4);
                             }
                             if($k=="time"){
                                 date_default_timezone_set ('Asia/Shanghai');
                                 $res["opentime"]=substr($val,0,4)."-".substr($val,4,2)."-".substr($val,6,2)." ".substr($val,8,2).":".substr($val,10,2).":00";;
                                 $res["opentimestamp"]=strtotime($res["opentime"]);
                             }
                         }
                     }
                     break;
                 }
             }
         }
         return $res;
     }
}
