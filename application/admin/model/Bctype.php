<?php
namespace app\admin\model;
use think\Model;

class Bctype extends Model{
   
    //添加
    public function getinfo(){
        $file = request()->file('bcimg');
        $data=request()->param();
        $data['time']=date('Y-m-d H:i:s');   
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $data['bcimg']=request()->root(true).'/uploads/'.$info->getSaveName();
                $res=$this->allowField(true)->save($data);
                if ($res){
                    return true;
                }else {
                    return false;
                }
            }else{
                return false;
            }
        }
    }
    //编辑
    public function editinfo(){
        $file = request()->file('bcimg');
        $data=request()->param();
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $data['bcimg']=request()->root(true).'/uploads/'.$info->getSaveName();
            }else{
                return false;
            }
        }
        $res=$this->allowField(true)->save($data,['id'=>$data['id']]);
        if ($res){
            return true;
        }else {
            return false;
        }
    }
    //删除
    public function del($id){
        $res=$this::where('id',$id)->delete();
        if($res){
            return true;
        }else {
            return false;
        }
        
    }
}