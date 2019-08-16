<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use app\admin\model\Agent;
class AgentController extends CommonController{
    public function index(){
        return $this->fetch();
    }
    public function json(){
        $limit=input('limit');
        $page=input('page');
        $pages=($page-1)*$limit;
        $data=Db::name('agent')->limit($pages,$limit)->select();
        $res=array();       
        $res['data']=$data;
        $res['code']=0;
        $res['count']=Db::name('agent')->count('id');
        return json($res);  
    }
    public function add(){
        $resquest=Request::instance();
        if ($resquest->isPost()){
            $m=new Agent();
            $res=$m->getimginfo();
            if ($res){
                $this->success('添加成功','index');
            }else {
                $this->error('添加失败');
            }
        }else {
            return $this->fetch('info');
        }       
    }
    public function edit(){
        $resquest=Request::instance();
        if ($resquest->isPost()){
            $id=input('post.id');
            $m=new Agent();
            $res=$m->eidtone($id);
            if (false !== $res || 0 !== $res){
                $this->success('修改成功','index');
            }else {
                $this->error('修改失败');
            }
        }else {
            $id=input('get.id');
            $res=Db::name('agent')->where('id',$id)->find();
            $this->assign('editone',$res);
            return $this->fetch('info');
        }
    }
    
    public function delete(){
        $id=input('id');
        $m=new Agent();
        $res=$m->del($id);
        if ($res){
            $this->success('修改成功','index');
             
        }else {
           $this->error('修改失败');          
        }
    }
}
