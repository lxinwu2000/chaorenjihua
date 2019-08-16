<?php
namespace app\home\controller;
use think\Db;
class GonggaoController extends BaseController{
    public function details(){
        $id=input('get.id');
        $res=Db::name('gonggao')->where('id',$id)->find();
        $res['content']=htmlspecialchars_decode($res['content']);
        $this->assign('details',$res);
        return $this->fetch();
    }
}