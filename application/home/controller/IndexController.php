<?php
namespace app\home\controller;
use think\Db;
class IndexController  extends BaseController{
    public function index() {
        //轮播渲染
        $sidelist=Db::name('slide')->select();
        $this->assign('sidelist',$sidelist);
        //公告
        $gonggaolist=Db::name('gonggao')->where('is_show',1)->field('id,title')->select();
        $this->assign('gonggaolist',$gonggaolist);
        //重庆时时彩           
       $cplist=Db::name('bctype')->where('api!=1')->select();
       for ($i=0;$i<count($cplist);$i++){
           $id=$cplist[$i]['id'];
           $apiinfo=Db::name('bctype')->where('id',$id)->field('api')->find();
           if (!empty($apiinfo)){                   
               $api=$apiinfo['api'];                 
               $file = $this->curl_get($api);              
               $arrpoint = json_decode($file, true);            
               $arrpoint=$arrpoint['data'][0];
               $arrsz=explode(',', $arrpoint['opencode']);
               $arrpoint['opencode']=$arrsz;
               $opentime=$arrpoint['opentimestamp'];              
               $cplist[$i]['opendjstime']=$opentime;                            
           }               
           $cplist[$i]['arrpoint']=$arrpoint;      
       }      
       unset($cplist[$i]['api']);                         
       $this->assign('cplist',$cplist);
       //腾讯分分彩
       $txffcdata=$this->txffc();
       $this->assign('txffcdata',$txffcdata);
       //幸运飞艇
       $xyftinfo=$this->xyft();
       $this->assign('xyftinfo',$xyftinfo);
     
       return  $this->fetch();
    }
    //幸运飞艇
    public function xyft(){
        $xyftinfo=Db::name('bctype')->where('id',6)->find();
        $api='http://api.duourl.com/t?p=json&t=xyft&limit=1&token=978E6B6507B68CA9';
        $file=$this->curl_get($api);
        $arrpoint = json_decode($file, true);
        $data=$arrpoint['data'][0];       
        $arrsz=explode(',', $data['opencode']);
        $data['opencode']=$arrsz;
        $opentime=strtotime($data['opentime']);
        $xyftinfo['opendjstime']=$opentime;/* $opentime */
        $xyftinfo['arrpoint']=$data;
        return $xyftinfo;
    }
    
    
    //腾讯分分彩
    public function txffc(){     
        $txffcinfo=Db::name('bctype')->where('id',5)->find();
        $txffcapi="http://qq-online.org/get_result_list";
        $txdata=[
            "page_size"=>1,
            "page_no"=>1
        ];
        $txdata=$this->datastr($txdata);
        $txffcdata=$this->request_post($txffcapi,$txdata);
        $txbackdata=json_decode($txffcdata,true);
        $txbackdata=$txbackdata['data'][0];        
        //生成开奖号码
        $kjhm=$txbackdata['count'];
        $newkjhm=str_split($kjhm);
        $wwcode=0;
        for ($i=0;$i<count($newkjhm);$i++){
            $wwcode+=$newkjhm[$i];
        }
        $wwcode=substr($wwcode, -1);
        $hswcode=substr($kjhm, -4);
        //开奖的号码
        $tureopencode=$wwcode.$hswcode;       
        //期号
        $txbackdata['expect']=$txbackdata['issue'];
        $txbackdata['opentime']=date('Y-m-d H:i');        
        $txbackdata['opencode']=str_split($tureopencode);
        unset($txbackdata['issue']);
        unset($txbackdata['time']);
        unset($txbackdata['count']);
        $txffcinfo['arrpoint']=$txbackdata;
        $txffcinfo['opendjstime']=strtotime($txbackdata['opentime']);
        return $txffcinfo;
    }             
    //倒计时请求//腾讯分分彩
    public function findtxffc(){
        $id=input('id');
        $cpinfo=Db::name('bctype')->where('id',$id)->find();
        $bcname=$cpinfo['bcname'];
        $txffcapi="http://qq-online.org/get_result_list";
        $txdata=[
            "page_size"=>1,
            "page_no"=>1
        ];
        $txdata=$this->datastr($txdata);
        $txffcdata=$this->request_post($txffcapi,$txdata);
        $txbackdata=json_decode($txffcdata,true);
        $txbackdata=$txbackdata['data'][0];
        //生成开奖号码
        $kjhm=$txbackdata['count'];
        $newkjhm=str_split($kjhm);
        $wwcode=0;
        for ($i=0;$i<count($newkjhm);$i++){
            $wwcode+=$newkjhm[$i];
        }
        $wwcode=substr($wwcode, -1);
        $hswcode=substr($kjhm, -4);
        //开奖的号码
        $tureopencode=$wwcode.$hswcode;
        //期号
        $txbackdata['expect']=$txbackdata['issue'];
        $txbackdata['opentime']=strtotime(date('Y-m-d H:i'));
        $txbackdata['opencode']=str_split($tureopencode);
        unset($txbackdata['issue']);
        unset($txbackdata['time']);
        unset($txbackdata['count']);
        return ajaxinfo($bcname.'已更新期数','1',$txbackdata);
    }
     //倒计时请求 //幸运飞艇
    public function findxyft(){
        $id=input('id');
        $cpinfo=Db::name('bctype')->where('id',$id)->find();
        $bcname=$cpinfo['bcname'];
        $api='http://api.duourl.com/t?p=json&t=xyft&limit=1&token=978E6B6507B68CA9';
        $file=$this->curl_get($api);
        $arrpoint = json_decode($file, true);
        $data=$arrpoint['data'][0];
        $arrsz=explode(',', $data['opencode']);
        $data['opencode']=$arrsz;
        $opentime=strtotime($data['opentime']);
        $data['opendjstime']=$opentime;/* $opentime */
        return ajaxinfo($bcname.'已更新期数','1',$data);
        
    }
    //倒计时
    public function findbc(){
        $id=input('id');
        $cpinfo=Db::name('bctype')->where('id',$id)->find();
        $bcname=$cpinfo['bcname'];
        $api=$cpinfo['api'];
        $file = $this->curl_get($api);
        $arrpoint = json_decode($file, true);
        $arrpoint=$arrpoint['data'][0];
        $arrsz=explode(',', $arrpoint['opencode']);       
        $arrpoint['opencode']=$arrsz;             
        return ajaxinfo($bcname.'已更新期数','1',$arrpoint);
        
        
    }  
    
    
    
    protected  function datastr($post_data){
        foreach ($post_data as $k => $v ){
            $o.= "$k=".$v ."&" ;
        }
        $datatrue = substr($o,0,-1);
        return $datatrue;
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
    
 private  function request_post($url = '', $param = '') {
        if (empty($url) || empty($param)) {
            return false;
        }
        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        return $data;
    }
    
  
}
