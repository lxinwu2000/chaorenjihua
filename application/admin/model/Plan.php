<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Plan extends Model{
    //最新一期重庆时时彩
    public function zuixincqssc(){
        $bctype=Db::name('bctype')->where('id',4)->field('api')->find();
        $api=$bctype['api'];
        $arr=explode('&', $api);
        $trueapi=$arr[0].'&'.$arr[1].'&'.'rows=1'.'&'.$arr[3];
        $file = $this->curl_get($trueapi);
        $arrpoint = json_decode($file, true);
        $arr=$arrpoint['data'];
        for ($i=0;$i<count($arr);$i++){
            $arr[$i]['jxexpect']=substr($arr[$i]['expect'] ,strlen($arr[$i]['expect'])- 3,3);
            $opencode=$arr[$i]['opencode'];
            $newarr=explode(',', $opencode);
            $newcode=$this->wsjarr($newarr);
            $arr[$i]['opencode']=$newcode;
    
        }
        $arrs=$arr[0];
        return $arrs;
    }
    private function wsjarr($arr){
        $newcode='';
        for ($j=0;$j<count($arr);$j++){
            $newcode.=$arr[$j];
        }
        return $newcode;
    }
    
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
}