<?php
namespace app\admin\controller;
use app\admin\model\Bctype;
use think\Db;
class BctypeController extends CommonController{
    public function index(){
        return $this->fetch();
    }
    public function json(){
        $m=new Bctype();
        $limit=input('limit');
        $page=input('page');
        $pages=($page-1)*$limit;
        $data=Bctype::limit($pages,$limit)->select();
        $res=array();
        for($i=0;$i<count($data);$i++){
            if(empty($data[$i]['bcimg'])){
                $data[$i]['bcimg']='暂无';
            }else {
                $data[$i]['bcimg']='<a href="'.$data[$i]['bcimg'].'" target="_blank"><img src="'.$data[$i]['bcimg'].'" width="50" height="50" class="simg"/></a>';
            }
            if ($data[$i]['status']=='1'){
                $data[$i]['status']='显示';
            }else {
                $data[$i]['status']='隐藏';
            }            
        }
        $res['data']=$data;
        $res['code']=0;
        $res['count']=Bctype::count('id');
        return json($res);
    }
    public function add(){
        if (request()->isPost()){
            $m=new Bctype();
            $res=$m->getinfo();
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
        if (request()->isPost()){
            $m=new Bctype();
            $res=$m->editinfo();
            if ($res){
                $this->success('修改成功','index');
            }else {
                $this->error('修改失败');
            }
        }else {
            $id=input('get.id');
            $editone=Db::name('bctype')->where('id',$id)->find();
            $this->assign('editone',$editone);           
            return $this->fetch('info');
        }
    }
    public function delete(){
        $id=input('id');
        $m=new Bctype();
        $res=$m->del($id);
        if ($res){
            return ajaxinfo('删除成功',1);
        }
    }
    //设置玩法
    public function setplay(){
        if (request()->isPost()){
           $data=request()->param();
           //判断玩法是否存在
           $hasname=Db::name('play')->where('playname',$data['playname'])->find();
           if ($hasname){
               $this->error('玩法名称已经存在');
               return  false;
           }           
           $res=Db::name('play')->insert($data);          
           if ($res){
               $this->success('添加成功');
           }
        }else {
            $id=input('get.id');
            $editone=Db::name('bctype')->where('id',$id)->find();
            $this->assign('editone',$editone);
            $list=Db::name('play')->where('bid',$id)->select();
            $list = nodeTree($list);
            $this->assign('list',$list);
            return $this->fetch();
        }
        
        
    }
    //删除玩法名称
    public function deletesetplay(){
        $id=input('id');
        $has=Db::name('play')->where('parentid',$id)->find();
        if ($has){
            return ajaxinfo('删除失败下面有子分类',0);
        }
        $res=Db::name('play')->where('id',$id)->delete();
        if ($res){
            return ajaxinfo('删除成功',1);
        }
    }
    
    
    //设置赔率
    public function setodds(){
       $data=request()->param();
       $res=Db::name('play')->where('id',$data['id'])->update($data);
       if ($res){
           return ajaxinfo('赔率设置成功',1);
       }
    }
    //设置描述
    public function setdesc(){
        $data=request()->param();
        $res=Db::name('play')->where('id',$data['id'])->update($data);
        if ($res){
            return ajaxinfo('描述设置成功',1);
        }
    }
}