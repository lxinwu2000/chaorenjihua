<?php
namespace app\home\model;
use think\Model;
use think\Db;
class Proxyorder extends Model{
    public function agentname($id){
        $info=db('agent')->where('id',$id)->field('addrenshu')->find();
        return $info['addrenshu'];
    }
    
    public function validity($id){
        $info=db('agent')->where('id',$id)->field('validity')->find();
        return $info['validity'];
    }
    
    public function money($id){
        $info=db('agent')->where('id',$id)->field('bonus')->find();
        return $info['bonus'];
    }
    
    public function username($id){
        $info=db('user')->where('id',$id)->field('account')->find();
        return $info['account'];
    }
}