<?php
namespace app\home\controller;
use app\common\model\Common;
use think\Db;
use app\home\model\Proxyorder;

class UserController extends BaseController{
    public function index(){
        $com=new Common();
        $userid=$com->getuserid();
        $userinfo=Db::name('user')->where('id',$userid)->find();
        $this->assign('userinfo',$userinfo);
        return $this->fetch();
    }
    //申请代理
    public function shenqingdaili(){
        $com=new Common();
        $userid=$com->getuserid();
        $userinfo=Db::name('user')->where('id',$userid)->find();
        $this->assign('userinfo',$userinfo);  
        //充值查询
        $bonuslist=Db::name('agent')->select();
        $this->assign('bonuslist',$bonuslist);
        return $this->fetch();
    }
    
    public function userrecharge(){
        $com=new Common();
        $userid=$com->getuserid();
        $userinfo=Db::name('user')->where('id',$userid)->find();
        $this->assign('userinfo',$userinfo);
        //充值查询
        $bonuslist=Db::name('agent')->select();
        $this->assign('bonuslist',$bonuslist);
        return $this->fetch();
    }
    
    //修改密码
    public function editpaw(){
        if (request()->isPost()){
            $com=new Common();
            $userid=$com->getuserid();           
            $data=request()->param();
            $result = $this->validate($data,'editpaw');
            if (true !== $result){
                return ajaxinfo($result,'0');
            }else {
                $res=Db::name('user')->where('id',$userid)->update(['password'=>md5($data['password'])]);
                if (false!==$res||0!==$res){
                    return ajaxinfo('修改成功','1');
                }
            }            
        }else {
            return $this->fetch();
        }
    }
    //系统公告
    public function gonggao(){
        $list=Db::name('gonggao')->where('is_show',1)->select();
        $this->assign('list',$list);
        return $this->fetch();
    }
    //意见反馈
    public function liuyan(){
        if (request()->isPost()){
            $com=new Common();
            $userid=$com->getuserid();
            $data['content']=input('post.content');
            if (empty($data['content'])){
                return ajaxinfo('反馈的内容不能为空',0);
            }
            $data['time']=date('Y-m-d H:i:s');
            $data['userid']=$userid;
            $res=Db::name('liuyan')->insert($data);
            if ($res){
                return ajaxinfo('留言成功',1);
            }
        }else {
            return $this->fetch();
        }
    }
    
    public function sjgs($uid){
        $m=new Proxyorder();
        $list=$m->where("uid",$uid)->select();
        for ($i=0;$i<count($list);$i++){
            if($list[$i]['status']=='1'){
                $list[$i]['status']='已支付';
            }else {
                $list[$i]['status']='未支付';
            }
            $list[$i]['createtime']=date("Y-m-d H:i:s", $list[$i]['createtime']);
            $list[$i]['username']=$m->username($list[$i]['uid']);
//             $list[$i]['agentname']=$m->agentname($list[$i]['agentid'])."/人版";
            $list[$i]['validity']=$m->validity($list[$i]['agentid'])."天";
            $list[$i]['money']=$m->money($list[$i]['agentid']);
        }
        $this->assign('list',$list);
        return $this->fetch("orders");
    }
}