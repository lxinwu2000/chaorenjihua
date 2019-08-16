<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use app\common\model\Common;
use app\common\model\Rule;
use app\admin\model\Proxyorder;

class RecordController extends CommonController{
    public function index(){
        return $this->fetch();
    }
    public function json(){
        $com=new Common();
        $limit=input('limit');
        $page=input('page');
        $pages=($page-1)*$limit;
        $m=new Proxyorder();
        $data=$m->limit($pages,$limit)->select();
        $res=array();
        for($i=0;$i<count($data);$i++){
            if($data[$i]['status']=='1'){
                $data[$i]['status']='已支付';
            }else {
                $data[$i]['status']='未支付';
            }
            $data[$i]['createtime']=date("Y-m-d H:i:s", $data[$i]['createtime']);
            $data[$i]['username']=$m->username($data[$i]['uid']);
            $data[$i]['agentname']=$m->agentname($data[$i]['agentid'])."/人版";
            $data[$i]['validity']=$m->validity($data[$i]['agentid'])."天";
            $data[$i]['money']=$m->money($data[$i]['agentid']);
        }
        $res['data']=$data;
        $res['code']=0;
        $res['count']=Db::name('proxyorder')->count('id');
        return json($res);
    }
    public function add(){
        if (request()->isPost()){
            $data=request()->param();
            $res=Db::name('plan')->insert($data);
            if ($res){
                $this->success('添加成功','index');
            }
            
        }else {
            $list=Db::name('bctype')->field('id,bcname')->select();
            $this->assign('list',$list);
            return $this->fetch('info');
        }
    }
    public function edit(){
        if (request()->isPost()){
            $data=request()->param();
            $res=Db::name('plan')->where('id',$data['id'])->update($data);
            if ($res){
                $this->success('修改成功','index');
            }       
        }else {
            $list=Db::name('bctype')->field('id,bcname')->select();
            $this->assign('list',$list);
            $editone=Db::name('plan')->where('id',input('id'))->find();
            $this->assign('editone',$editone);
            return $this->fetch('info');
        }
    }
    //规则添加
    public function setrule(){
        if (request()->isPost()){
            $data=request()->param();
            $rid= Db::name('rule')->insertGetId($data);
            //写明细表
            //生成计划号
            $bctypedata=Db::query("select * from zxcms_bctype where id=(select bid from zxcms_plan where id=?)",[$data["pid"]]);
            $openlength=$bctypedata[0]["opencodelength"];
            $rand=rand(0, 9);
            if ($data["option03"]=="bigsmall"){
                $bssd=$rand%2;
            }elseif ($data["option03"]=="singledouble"){
                $bssd=$rand%2+2;
            }
            $plancode=GeneratePlanCode($data["option01"],$openlength,$data["codelength"],$bssd);
//             dump($plancode);
//             if ($codelength>=10){
//                 $plancode=GeneratePlanCode($codelength,$data["codelength"]);
//             }else {
//                 $plancode=GeneratePlanCode(9,$data["codelength"]);
//             }
//             $plancode=GeneratePlanCode($data["option01"],
//                 $data["option02"],
//                 $data["option03"],
//                 $data["option04"],
//                 0,
//                 9,
//                 $data["codelength"]);
            $map=array();
            $map['rid']=$rid;
            $map['pid']=$data["pid"];
            $map['status']=0;
            $map['zgstatus']=0;
            $map['plancode']=$plancode;
            $map["bssd"]=$bssd;
//             $rand=rand(0, 9);
//             if ($data["option03"]=="bigsmall"){
//                 $map["bssd"]=$rand%2;
//             }elseif ($data["option03"]=="singledouble"){
//                 $map["bssd"]=$rand%2+2;
//             }
            Db::name('draw')->insert($map);
            $this->success('提交成功');
        }else {
            $com=new Common();
            $id=input('get.id');
            $info=Db::name('plan')->where('id',$id)->find();
            $this->assign('info',$info);         
            $list=Rule::where('pid',$id)->select();
            $this->assign('list',$list);
            return $this->fetch('info2');
        }       
    }
   
    public function delete(){
        $id=input('id');       
        $res=Db::name('proxyorder')->where('id',$id)->delete();
        if ($res){
            $this->success('删除成功','index');
        }else {
            $this->error('删除失败：数据库错误'.$e->getMessage(),'index');
        }
    }
    //删除规则
    public function delrule(){
        $id=input('id');
        //删除明细
        $res=Db::name('draw')->where('rid',$id)->delete();
        $res=Db::name('rule')->where('id',$id)->delete();
        if ($res){
            $this->success('删除成功');
        }
    }
    public function modifyrulename(){
        $id=input('id');
        $rulename=input("rulename");
        $res=Db::name('rule')->where('id',$id)->update(["rulename"=>$rulename]);
        if ($res){
            $this->success('修改成功');
        }
    }
}