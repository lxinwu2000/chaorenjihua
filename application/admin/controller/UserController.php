<?php
namespace app\admin\controller;
use think\Db;
class UserController extends CommonController{
    public function index(){
        return $this->fetch();
    }
    public function json(){
        $limit=input('limit');
            $page=input('page');
            $pages=($page-1)*$limit;
            $search='%'.input('key').'%';
            $where['account']=array('like',$search);
            $data=Db::name('user')->where($where)->limit($pages,$limit)->select();
            $res=array();
            for($i=0;$i<count($data);$i++){
                if($data[$i]['paystatus']=="0"){
                    $data[$i]['paystatus']='未支付';
                }  
                if($data[$i]['newstatus']=="0"){
                    $data[$i]['newstatus']='普通会员';
                }
                if($data[$i]['newstatus']=="1"){
                    $data[$i]['newstatus']='代理商会员';
                }
                if($data[$i]['daoqitime']==""){
                    $data[$i]['daoqitime']='暂无';
                }
                if($data[$i]['forbiddenstatus']=="0"){
                    $data[$i]['forbiddenstatus']='未禁用';
                }else {
                    $data[$i]['forbiddenstatus']='已禁用';
                }
                
            }
            $res['data']=$data;
            $res['code']=0;
            $res['count']=Db::name('user')->where($where)->count('id');
            return json($res);
        
    }
 //会员禁用
    public function forbidden(){
        $id=input('id');
        $find=Db::name('user')->where('id',$id)->find();
        if ($find['forbiddenstatus']=='0'){
            $res=Db::name('user')->where('id',$id)->update(['forbiddenstatus'=>'1']);
            if (false!==$res||0!==$res){
                return ajaxinfo('禁用成功！','1');
            }
        }else {
            $res=Db::name('user')->where('id',$id)->update(['forbiddenstatus'=>'0']);
            if (false!==$res||0!==$res){
                return ajaxinfo('解除禁用成功！','1');
            }
        }
        
    }
   
}