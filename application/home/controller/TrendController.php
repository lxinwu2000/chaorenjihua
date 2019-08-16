<?php
namespace app\home\controller;
use think\Db;
class TrendController extends BaseController{
    public function index(){
        $list=Db::name('bctype')->where('status',1)->field('id,bcname')->select();
        $this->assign('list',$list);
        $api="http://b.apiplus.net/newly.do?token=tde17f9a0f22c371dk&code=cqssc&rows=20&format=json";
        $arr=explode('&', $api);       
        $file = $this->curl_get($api);
        $arrpoint = json_decode($file, true);
        $data=$arrpoint['data'];     
        $newarr=array();
        for ($i=0;$i<count($data);$i++){
            $opencode=$data[$i]['opencode'];
            $expect=$data[$i]['expect'];
            $qh=substr($expect ,strlen($expect)- 3,3);
            $key=substr($opencode , 0 , 1);
            $newarr[$i]['qh']=$qh;
            $newarr[$i]['key']=$key;            
        }            
        $arr=$this->table($newarr);
        $this->assign('arr',$arr);    
        return $this->fetch();
    }
    //走势图
    public function trend(){
        $type=input('type');
        $id=input('id');
        $bctype=Db::name('bctype')->where('id',$id)->field('api')->find();
        $api=$bctype['api'];
        $arr=explode('&', $api);
        $trueapi=$arr[0].'&'.$arr[1].'&'.'rows=20'.'&'.$arr[3];
        $file = $this->curl_get($trueapi);
        $arrpoint = json_decode($file, true);
       
        if ($id==4){
            $data=$arrpoint['data'];
            $newarr=array();
            for ($i=0;$i<count($data);$i++){
                $opencode=$data[$i]['opencode'];
                $expect=$data[$i]['expect'];
                $qh=substr($expect ,strlen($expect)- 3,3);
                $key=explode(',', $opencode);
                $newarr[$i]['qh']=$qh;
                $newarr[$i]['key']=$key[$type];
            }
            $arr=$this->table($newarr);
        }
        if ($id==9){
            $data=$arrpoint['data'];
            $newarr=array();
            for ($i=0;$i<count($data);$i++){
                $opencode=$data[$i]['opencode'];
                $expect=$data[$i]['expect'];
                $qh=substr($expect ,strlen($expect)- 3,3);
                $key=explode(',', $opencode);
                $newarr[$i]['qh']=$qh;
                $newarr[$i]['key']=$key[$type];
            }
            $arr=$this->table2($newarr);
        }
        if ($id==5){
            $data=$this->txffdata();
            $newarr=array();
            for ($i=0;$i<count($data);$i++){
                $opencode=$data[$i]['opencode'];
                $expect=$data[$i]['expect'];
                $qh=substr($expect ,strlen($expect)- 3,3);
                $key=explode(',', $opencode);
                $newarr[$i]['qh']=$qh;
                $newarr[$i]['key']=$key[$type];
            }
            $arr=$this->table($newarr);
        }
       
        return ajaxinfo('ok','1',$arr);
        
    }
    //北京pk10
    public function bctype(){
        $list=Db::name('bctype')->where('status',1)->field('id,bcname')->select();
        $this->assign('list',$list);
        $id=input('get.id');
        $this->assign('findid',$id);
        $bctype=Db::name('bctype')->where('id',$id)->field('api')->find();     
        $api=$bctype['api'];
        $arr=explode('&', $api);
        $trueapi=$arr[0].'&'.$arr[1].'&'.'rows=20'.'&'.$arr[3];
        $file = $this->curl_get($trueapi);
        $arrpoint = json_decode($file, true);
        $data=$arrpoint['data'];
        $newarr=array();
        for ($i=0;$i<count($data);$i++){
            $opencode=$data[$i]['opencode'];
            $expect=$data[$i]['expect'];
            $qh=substr($expect ,strlen($expect)- 3,3);
            $key=explode(',', $opencode);
            $newarr[$i]['qh']=$qh;
            $newarr[$i]['key']=$key[0];
        }
        $arr=$this->table2($newarr);
        $this->assign('arr',$arr);
        return $this->fetch('index2');
    }
    //腾讯分分彩    
    public function txffctype(){
        $list=Db::name('bctype')->where('status',1)->field('id,bcname')->select();
        $this->assign('list',$list);
        $id=input('get.id');
        $this->assign('findid',$id);      
        $data=$this->txffdata();
        $newarr=array();
        for ($i=0;$i<count($data);$i++){
            $opencode=$data[$i]['opencode'];
            $expect=$data[$i]['expect'];
            $qh=substr($expect ,strlen($expect)- 3,3);
            $key=substr($opencode , 0 , 1);
            $newarr[$i]['qh']=$qh;
            $newarr[$i]['key']=$key;
        }
        $arr=$this->table($newarr);
        $this->assign('arr',$arr);                          
        return $this->fetch('index3');
    }
    //腾讯分分彩重装数据
    protected function txffdata(){
        $txffcapi="http://qq-online.org/get_result_list";
        $txdata=[
            "page_size"=>20,
            "page_no"=>1
        ];
        $txdata=$this->datastr($txdata);
        $txffcdata=$this->request_post($txffcapi,$txdata);
        $txbackdata=json_decode($txffcdata,true);
        $txbackdata=$txbackdata['data'];
        //生成开奖号码
        for ($i=0;$i<count($txbackdata);$i++){
            $txbackdata[$i]['expect']=$txbackdata[$i]['issue'];
            $txbackdata[$i]['opentime']=date('Y-m-d H:i');
            $txbackdata[$i]['opentimestamp']=strtotime(date('Y-m-d H:i'));
            unset($txbackdata[$i]['issue']);
            unset($txbackdata[$i]['time']);
            $wwcode=wsjarr(str_split($txbackdata[$i]['count']));
            $hswcode=substr($txbackdata[$i]['count'], -4);
            $opencode=zddarr(str_split($wwcode.$hswcode));
            $txbackdata[$i]['opencode']=$opencode;
            unset($txbackdata[$i]['count']);
        }
        return $txbackdata;
    }
    protected  function datastr($post_data){
        foreach ($post_data as $k => $v ){
            $o.= "$k=".$v ."&" ;
        }
        $datatrue = substr($o,0,-1);
        return $datatrue;
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
    
    private function table2($arr){
        $html='';
        for ($i=0;$i<count($arr);$i++){
            $tdhtml=$this->td2($arr[$i]['key']);
            $html.='<tr>
                    <td>'.$arr[$i]['qh'].'</td>
                     '.$tdhtml.'
                  </tr>';
        }
        return $html;
    }
    
    private function td2($key){
        $std='';
        for ($j=1;$j<11;$j++){
            if ($j==$key){
                $std.='<td style="color:white;background:red;" class="wsj">'.$key.'</td>';
            }else {
                $std.='<td>'.$j.'</td>';
            }
        }
        return $std;
    }
    
    
    
    private function table($arr){
        $html='';          
        for ($i=0;$i<count($arr);$i++){ 
            $tdhtml=$this->td($arr[$i]['key']);
                $html.='<tr>
                    <td>'.$arr[$i]['qh'].'</td>                                                                                                                                                                                     
                     '.$tdhtml.'
                  </tr>';                                  
        }        
        return $html;      
  } 
  
    private function td($key){
        $std='';     
        for ($j=0;$j<10;$j++){
            if ($j==$key){
                $std.='<td style="color:white;background:red;" class="wsj">'.$key.'</td>';                
            }else {
                $std.='<td>'.$j.'</td>';
            }           
        }
        return $std;
    }
 
    
}