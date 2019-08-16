<?php
namespace app\admin\controller;
use think\Db;
class GonggaoController extends CommonController{
    public function index(){
        return $this->fetch();
    }
    public function json(){
        $limit=input('limit');
        $page=input('page');
        $pages=($page-1)*$limit;
        $search='%'.input('key').'%';
        $where['title']=array('like',$search);
        $data=Db::name('gonggao')->where($where)->limit($pages,$limit)->order('addtime desc')->select();
        $res=array();
        for($i=0;$i<count($data);$i++){           
            if ($data[$i]['is_show']=='1'){
                $data[$i]['is_show']='显示';
            }else {
                $data[$i]['is_show']='隐藏';
            }
        }
        $res['data']=$data;
        $res['code']=0;
        $res['count']=Db::name('gonggao')->where($where)->count('id');
        return json($res);
    }
    public function add(){
        if(request()->isPost()){
            $data=request()->param();
            $data['addtime']=date('Y-m-d H:i:s');
            $res=Db::name('gonggao')->insert($data);
            if ($res){
                $this->success('添加成功','index');
            }                       
        }else {
            return $this->fetch('info');
        }
    }
    public function edit(){
        if(request()->isPost()){
            $data=request()->param();
            $data['addtime']=date('Y-m-d H:i:s');
            $res=Db::name('gonggao')->where('id',$data['id'])->update($data);
            if (false!==$res||0!==$res){
                $this->success('修改成功','index');
            }            
        }else {
            $editone=Db::name('gonggao')->where('id',input('id'))->find();
            $this->assign('editone',$editone);
            return $this->fetch('info');
        }
    }
    public function delete(){
        $res=Db::name('gonggao')->where('id',input('id'))->delete();
        if ($res){
            $this->success('删除成功');
        }
    }
}