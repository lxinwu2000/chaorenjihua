<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use app\home\model\Common;
class LoginController extends Controller{
    public function index(){
      return $this->fetch();
    }
    public function checkoutlogin(){
        $data=request()->param();
        $result = $this->validate($data,'Login');
        if (true !== $result){
            return ajaxinfo($result,'0');
        }else{
            $password=md5($data['password']);
            $account=$data['account'];
            $findinfo=Db::name('user')->where('account',$account)->where('password',$password)->find();
            //查看是否禁用
            if ($findinfo['forbiddenstatus']=='1'){
                return ajaxinfo('您的账号已被禁用','0');
            }
                  
            if ($findinfo){
                $map['token']=$this->buildAccessToken();
                //判断有没有充值
                if($findinfo['paystatus']=='0'){
                    if (empty($findinfo['daoqitime'])){
                        $map['daoqitime']=$this->timeout();
                    }
                }                              
                $result=Db::name('user')->where('account',$account)->where('password',$password)->update($map);
                if ($result){
                    session('token',$map['token']);
                    return ajaxinfo('登录成功','1');
                }                
            }else {
                return ajaxinfo('用户名或密码不正确','0');
            }
           
           
        }
    }    
    //生成token
    protected static function buildAccessToken($lenght = 32)
    {
        //生成AccessToken
        $str_pol = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789abcdefghijklmnopqrstuvwxyz";
        return substr(str_shuffle($str_pol), 0, $lenght);
    }
    //新人默认浏览的时间段
    protected static function timeout(){
        $com=new Common();
        $time=$com->looktime();
        $timeout=time()+$time;
        $truetime=date('Y-m-d H:i:s',$timeout);
        return $truetime;
    }
    //退出登录
    public function logout(){
        $com=new \app\common\model\Common();
        $userid=$com->getuserid();
        $res=Db::name('user')->where('id',$userid)->update(['token'=>'']);
        if ($res){
            return ajaxinfo('退出成功','1');
        }else {
            return ajaxinfo('系统繁忙','0');
        }
    }
}