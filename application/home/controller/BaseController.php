<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
class BaseController extends Controller{
//     public  function _initialize(){        
//         $token=session('token');
//         $hasid=Db::name('user')->where('token',$token)->find(); 
//         //到期时间限制
//         date_default_timezone_set('Asia/Shanghai');
//         $time=strtotime($hasid['daoqitime']);
//         $newtime=time();
//         if ($newtime>$time){
//           $this->redirect('/home/Common/index');
//         }
//         if (empty($hasid)){
//             session('userid',null);
//             $this->redirect('/home/Login/index');
//         }
//     }
    public  function _initialize(){
        $token=session('token');
        if ($token==null){
            session('userid',null);
            $this->redirect('/home/Login/index');
        }else {
            $hasid=Db::name('user')->where('token',$token)->find();
            if ($hasid==null){
                session('userid',null);
                $this->redirect('/home/Login/index');
            }
            //到期时间限制
            date_default_timezone_set('Asia/Shanghai');
            $daoqitime=strtotime($hasid['daoqitime']);
            if ($daoqitime<time()){
                $this->redirect('/home/Common/index');
            }
        }
    }
}