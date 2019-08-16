<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
class RegisterController extends Controller{
    public function index(){
        return $this->fetch();
    }
    public function reg(){
        $data=request()->param();
        $data['password']=md5($data['password']);
        $data['password_confirm']=md5($data['password_confirm']);      
        $result = $this->validate($data,'Register');
        if (true !== $result){
            return ajaxinfo($result,'0');
        }else{
            $data['regtime']=date('Y-m-d H:i:s');
            unset($data['password_confirm']);           
            $res=Db::name('user')->insert($data);
            if ($res){
                return ajaxinfo('注册成功','1',$data);
            }
           
        }
    }
    
   
    
}