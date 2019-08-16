<?php
namespace app\admin\model;
use think\Model;
class Agent extends Model{
    public function getimginfo(){      
              $data=request()->param();
              $data['time']=date('Y-m-d H:i:s');
              $res=$this->allowField(true)->save($data);
              if ($res){
                  return true;
              }else {
                  return false;
              }         
    }
    //编辑
    public function eidtone($id){     
        $data=request()->param();
        $data['time']=date('Y-m-d H:i:s');
        $res=$this->allowField(true)->save($data,['id'=>$id]);
        if ($res){
            return true;
        }else {
            return false;
        }
    }
    //删除
    public function del($id){
        $res=$this::where('id',$id)->delete();   
        if ($res){
            return true;
        }else {
            return false;
        }
    }
}