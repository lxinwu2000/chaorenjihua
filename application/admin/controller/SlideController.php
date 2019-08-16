<?php
namespace app\admin\controller;
use think\Request;
use app\admin\model\Slide;
use think\Db;
class SlideController extends CommonController{
    public function index(){
        return $this->fetch();
    }
    public function json(){     
            $limit=input('limit');
            $page=input('page');
            $pages=($page-1)*$limit;
            $data=Db::name('slide')->limit($pages,$limit)->select();
            $res=array();
            for($i=0;$i<count($data);$i++){
                if(empty($data[$i]['imagepath'])){
                    $data[$i]['imagepath']='暂无';
                }else {
                    $data[$i]['imagepath']='<a href="'.$data[$i]['imagepath'].'" target="_blank"><img src="'.$data[$i]['imagepath'].'" width="50" height="50" class="simg"/></a>';
                }       
            }
            $res['data']=$data;
            $res['code']=0;
            $res['count']=Db::name('slide')->count('id');
            return json($res);
        
    }
    
    public function add(){
       $resquest=Request::instance();
       if ($resquest->isPost()){
           $m=new Slide();
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
            $m=new Slide();
            $res=$m->eidtone($id);
            if (false !== $res || 0 !== $res){
                $this->success('修改成功','index');
            }else {
                $this->error('修改失败');
            }
        }else {
            $id=input('get.id');
            $res=Db::name('slide')->where('id',$id)->find();
            $this->assign('editone',$res);
            return $this->fetch('info');
        }
    }
    
    public function delete(){
        $id=input('id');
        $m=new Slide();
        $res=$m->del($id);
     if ($res){
         $data['state']=1;
         $data['msg']='删除成功';
         return json($data);
           
        }else {
         $data['state']=0;
         $data['msg']='删除失败';
         return json($data);
           
        }
    }
  
    
    
}