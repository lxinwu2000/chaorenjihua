<?php
namespace app\upload\controller;
use think\Controller;

class UploadController extends Controller{
     public function img($name){
            $file = request()->file($name);
            if ($file){
                $pic =  $this->uploadd($name);
                if($pic['info']== 1){
                    $url=request()->root(true).'/uploads/'.$pic['savename'];                
                        $data[''.$name.'']=$url;
                        $data['msg']="上传成功！";
                        $data['state']=1;
                        return $data;                 
                }else {
                    $data['msg']="上传失败！";
                    $data['state']=0;
                    return $data;
                }
            }
        }
    private  function uploadd($name){
        $file = request()->file($name);
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        $reubfo = array();
        if($info){
            $reubfo['info']= 1;
            $reubfo['savename'] = $info->getSaveName();
        }else{
            $reubfo['info']= 0;
            $reubfo['err'] = $file->getError();;
        }
        return $reubfo;
    }
}
