<?php
namespace app\home\controller;
use think\Controller;
class CommonController extends Controller{
    //到期后弹框提示
    public function index(){
        return $this->fetch();
    }
    
}