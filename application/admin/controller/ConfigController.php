<?php

/**
 *  系统设置
 * @file   ConfigController.php  
 * @date   2016-10-10 9:39:19 
 * @author Zhenxun Du<5552123@qq.com>  
 * @version    SVN:$Id:$ 
 */

namespace app\admin\controller;
use think\Db;
use think\Request;
class ConfigController extends CommonController {

    public function index() {
        $editone=Db::name('config')->where('id',1)->find();
        $this->assign('editone',$editone);
        return $this->fetch();
    }
    public function edit(){
        $request=Request::instance();
        $data=json_decode($request->param('data'),true);
        $res=Db::name('config')->where('id',1)->update($data);
        if (false!==$res||0!==$res){
            return ajaxinfo('编辑成功',1);
        }
    }

}
