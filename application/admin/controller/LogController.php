<?php

/**
 *  
 * @file   LogController.php  
 * @date   2016-10-9 18:23:24 
 * @author Zhenxun Du<5552123@qq.com>  
 * @version    SVN:$Id:$ 
 */

namespace app\admin\controller;
use think\controller;
use think\Db;
class LogController extends CommonController {

    public function index() {
        $lists = db("admin_log")->order('id desc')->limit(30)->select();	
        $this->assign('lists', $lists);
        return $this->fetch();
    }
    public function pldel(){
        $id=input('post.checkedList/a');
        if ($id==null){
            $data['state']=0;
            $data['msg']='你没有选择要删除的id';
            return  $data;
        }
        $where['id']=array('in',$id);
        $res=Db::name('admin_log')->where($where)->delete();
        if ($res){
            $data['state']=1;
            $data['msg']='批量删除成功';
            return  $data;
        }else {
            $data['state']=0;
            $data['msg']='批量删除失败';
            return  $data;
        }
        
    }

}
