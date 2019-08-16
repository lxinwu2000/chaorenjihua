<?php
namespace app\admin\model;
use think\Model;
class Slide extends Model{
    public function getimginfo(){  
        $file = request()->file('imagepath');       
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'. DS .'slide');
            if($info){               
              $data['imagepath']=request()->root(true).'/uploads/'.'slide/'.$info->getSaveName();
              $data['url']=input('url');
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
    public function eidtone($id){
     $file = request()->file('imagepath');   
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'. DS .'slide');
            if($info){               
              $data['imagepath']=request()->root(true).'/uploads/'.'slide/'.$info->getSaveName();
              $data['url']=input('url');
             return $this->allowField(true)->save($data,['id'=>$id]);            
            }else{
                return false;
            }
        }else {
            $data['url']=input('url');
            return $this->allowField(true)->save($data,['id'=>$id]);
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