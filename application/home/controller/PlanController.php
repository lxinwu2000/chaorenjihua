<?php
namespace app\home\controller;

use think\Db;
use app\common\model\Common;
use app\common\model\Rule;

class PlanController extends BaseController
{
    public function index(){
        $id = input('pid');
        $com = new Common();
        $list = Db::name('bctype')->where([
            'status' => 1
        ])
        ->field('id,bcname')
        ->order('id asc')
        ->select();
        if (input('bid') == null) {
            $list[0]["class"] = "tabItem red";
        } else {
            for ($i = 0; $i < count($list); $i ++) {
                $list[$i]["class"] = "tabItem";
                if ($list[$i]["id"] == input('bid')) {
                    $list[$i]["class"] = "tabItem red";
                }
            }
        }
        $this->assign('list', $list);
        // 计划list
        $typebid = Db::name('bctype')->where('status', 1)
        ->order('id asc')
        ->min('id');
        if (input('bid') != null) {
            $typebid = input('bid');
        }
        $planlist = Db::name('plan')->where('bid', $typebid)
        ->order('id asc')
        ->select();
        $this->assign('planlist', $planlist);
        // 计划下面的玩法
        $planinfo = Db::name('plan')->where('bid', $typebid)
        ->field('id')
        ->order('id asc')
        ->find();
        if ($id != null) {
            $planinfo['id'] = $id;
        }
        $orderlist=array();
        $drawdata=Db::name('draw')->where('pid', $planinfo['id'])->order("rid,expect")->select();
        $groupcount=0;
        $minexpect="";
        $wincount=0;
        for ($i=0;$i<count($drawdata);$i++){
            $groupcount++;
        
            $draw=$drawdata[$i];
            $rid = $draw['rid'];
            $rinfo = Rule::where('id', $rid)->find();
            $rulename = $rinfo['rulename'];
            if ($rinfo['option03']=="大小"||$rinfo['option03']=="单双"){
        	if ($draw["bssd"]==0){
        	    $bssd="大";
        	}elseif ($draw["bssd"]==1){
        	    $bssd="小";
        	}elseif ($draw["bssd"]==2){
        	    $bssd="单";
        	}elseif ($draw["bssd"]==3){
        	    $bssd="双";
        	}
        	$rulename .= str_repeat("&nbsp;", 20)."<font color='red'>" . $bssd."</font>";
            }else {
        	$rulename .= str_repeat("&nbsp;", 20)."<font color='red'>" . $rinfo['option03']."</font>";
            }
            if ($minexpect==""){
        	$minexpect=sprintf("%03d",$i+1);
            }
            
            //计划号排序
            $array=explode (",",  $draw["plancode"]);
            asort ($array);
            $orderplancode=implode(',',$array);
            
            //中奖
            if ($draw["status"]==1){
                $wincount++;
            	$single=array();
            	$single["qishuqj"]=$minexpect."-".sprintf("%03d",$i+1+$rinfo["count"]-$groupcount)."期";
            	$single["status"]=1;
            	$single["opencode"]=$draw["opencode"];
            	$single["rulename"]=$rulename;
            	if ($rinfo['option03']=="大小"||$rinfo['option03']=="单双"){
            	    $single["plancode"]="";
            	}else {
            	    $single["plancode"]=$orderplancode;
            	}
            	$single["winexpect"]=sprintf("%03d",$i+1);
            	$orderlist[]=$single;
            	$groupcount=0;
            	$wincount=0;
            	$minexpect="";
            }
            //未中奖
            elseif ($draw["status"]==2){
        	
            }
            //待开奖
            else {
        	$single=array();
            	$single["qishuqj"]=sprintf("%03d",$i+1)."期";
            	$single["rulename"]=$rulename;
            	$single["status"]=0;
            	if ($rinfo['option03']=="大小"||$rinfo['option03']=="单双"){
            	    $single["plancode"]="";
            	}else {
            	    $single["plancode"]=$orderplancode;
            	}
            	$orderlist[]=$single;
            	$minexpect="";
            	$groupcount=0;
            	$wincount=0;
            }
            
            if ($groupcount>=$rinfo["count"]){
            	$single=array();
            	$single["qishuqj"]=$minexpect."-".sprintf("%03d",$i+1)."期";
            	$single["status"]=2;   
            	$single["opencode"]=$draw["opencode"];
            	$single["rulename"]=$rulename;
            	if ($rinfo['option03']=="大小"||$rinfo['option03']=="单双"){
            	    $single["plancode"]="";
            	}else {
            	    $single["plancode"]=$orderplancode;
            	}
            	$single["winexpect"]=sprintf("%03d",$i+1);
            	$orderlist[]=$single;
            	$groupcount=0;
            	$minexpect="";
            }
        }
        $this->assign('rulelist', $orderlist);
        //最新一期开奖号码
        $sql="select * from zxcms_draw where pid=? and opencode is not null order by expect desc limit 1";
        $data=Db::query($sql,[$planinfo['id']]);
        if (!empty($data)){
            $this->assign('latestnumber', $data);
        }
        return $this->fetch();
    }
    
    // 定时任务
    public function dsrw($pid, $arr)
    {
        $exsits = Db::name('draw')->where([
            'pid' => $pid,
            "expect" => $arr['expect']
        ])->select();
        if (! empty($exsits)) {
            return;
        }
        $com = new Common();
        
        $rulelist = Db::name('rule')->where('pid', $pid)->select();
        $bctypedata = Db::query("select * from zxcms_bctype where id=(select bid from zxcms_plan where id=?)", [
            $pid
        ]);
        $codelength = $bctypedata[0]["opencodelength"];
        for ($i = 0; $i < count($rulelist); $i ++) {
            $rid = $rulelist[$i]["id"];
            $details = Db::name('draw')->where('rid', $rid)
                ->order("id", "desc")
                ->limit(1)
                ->select();
            $rinfo = $rulelist[$i];
            $times = $rinfo['count'];
            $num = $rinfo['codelength'];
            $opt1 = $rinfo['option01'];
            $opt2 = $rinfo['option02'];
            $opt3 = $rinfo['option03'];
            $opt4 = $rinfo['option04'];
            $plancode = $details[0]['plancode'];
            $zgstatus = $com->getprizeresult($opt1, $opt2, $opt3, $arr['opencode'], $plancode);
            if ($codelength >= 10) {
                $newplancode = GeneratePlanCode($opt1, $opt2, $opt3, $opt4, $codelength, $num);
            } else {
                $newplancode = GeneratePlanCode($opt1, $opt2, $opt3, $opt4, 9, $num);
            }
            Db::startTrans();
            try {
                if ($zgstatus) {
                    $data['expect'] = $arr['expect'];
                    $data['opentimestamp'] = $arr['opentimestamp'];
                    $data['opencode'] = $arr['opencode'];
                    $data['status'] = 1;
                    $data['zgstatus'] = 1;
                    Db::name('draw')->where('id', $details[0]['id'])->update($data);
                    // 设置之前的明细记录状态为无效
                    $sql = "update zxcms_draw set zgstatus=1 where pid=? and expect<?";
                    Db::execute($sql, [
                        $rinfo['pid'],
                        $arr['expect']
                    ]);
                    // 生成新一期的计划号
                    // $map['plancode']=GetRandStr2($num);
                    $map['plancode'] = $newplancode;
                    $map['expect'] = $arr['expect'] + 1;
                    $map['zgstatus'] = 0;
                    $map['status'] = 0;
                    $map['pid'] = $rinfo['pid'];
                    $map["rid"] = $rid;
                    Db::name('draw')->insert($map);
                } else {
                    $data['expect'] = $arr['expect'];
                    $data['opentimestamp'] = $arr['opentimestamp'];
                    $data['opencode'] = $arr['opencode'];
                    $data['status'] = 2;
                    $data['zgstatus'] = 0;
                    Db::name('draw')->where('id', $details[0]['id'])->update($data);
                    $cnt = Db::name('draw')->where([
                        'pid' => $pid,
                        "zgstatus" => 0,
                        "status" => 2
                    ])->count();
                    // 挂奖记录数是否大于设定的期数,生成新的计划号
                    if ($cnt >= $times) {
                        $plancode = $newplancode;
                    }
                    $map['plancode'] = $plancode;
                    $map['expect'] = $arr['expect'] + 1;
                    $map['zgstatus'] = 0;
                    $map['status'] = 0;
                    $map['pid'] = $rinfo['pid'];
                    $map["rid"] = $rid;
                    Db::name('draw')->insert($map);
                }
                Db::commit();
            } catch (Exception $e) {
                Db::rollback();
            }
        }
    }
    
    // 彩票类型计划渲染
    public function playdisplay()
    {
        $typebid = input('id');
        $planlist = Db::name('plan')->where('bid', $typebid)
            ->order('id asc')
            ->select();
        $html = '';
        for ($i = 0; $i < count($planlist); $i ++) {
            $html .= '<button type="button" class="am-btn am-btn-primary am-btn-xs" onclick="planrule(' . $planlist[$i]['bid'] . ',' . $planlist[$i]['id'] . ')">' . $planlist[$i]['planname'] . '</button>&nbsp;';
        }
        return ajaxinfo('ok', 1, $html);
    }
    
    // 最新一期重庆时时彩
    private function zuixincqssc($bid)
    {
        $bctype = Db::name('bctype')->where('id', $bid)
            ->field('api')
            ->find();
        $api = $bctype['api'];
        if (strpos($api, "/home/api/txffc")) {
            $trueapi = $api;
        } else {
            $arr = explode('&', $api);
            $trueapi = $arr[0] . '&' . $arr[1] . '&' . 'rows=1' . '&' . $arr[3];
        }
        $file = $this->curl_get($trueapi);
        $arrpoint = json_decode($file, true);
        $arr = $arrpoint['data'];
        for ($i = 0; $i < count($arr); $i ++) {
            $arr[$i]['jxexpect'] = substr($arr[$i]['expect'], strlen($arr[$i]['expect']) - 3, 3);
            $opencode = $arr[$i]['opencode'];
            $arr[$i]['opencode'] = $opencode;
        }
        $arr = $arr[0];
        return $arr;
    }

    public function planrule()
    {
        $pid = input('id');
        $rulelist = Db::name('draw')->where('pid', $pid)
            ->where('status!=2')
            ->order('rid asc')
            ->select();
        for ($i = 0; $i < count($rulelist); $i ++) {
            $rid = $rulelist[$i]['rid'];
            $ruleinfo = Db::name('rule')->where('id', $rid)
                ->field('rulename')
                ->find();
            $rulelist[$i]['rid'] = $ruleinfo['rulename'];
            if ($rulelist[$i]['status'] == '0') {
                $rulelist[$i]['status'] = '等待开奖';
            } else 
                if ($rulelist[$i]['status'] == '1') {
                    $rulelist[$i]['status'] = '中';
                } else {
                    $rulelist[$i]['status'] = '挂';
                }
        }
        $arr = $this->planstr($rulelist);
        
        return ajaxinfo('ok', '1', $arr);
    }

    private function bctypearr($pid)
    {
        $planinfo = Db::name('plan')->where('id', $pid)
            ->field('bid')
            ->find();
        $id = $planinfo['bid'];
        $bctype = Db::name('bctype')->where('id', $id)
            ->field('api')
            ->find();
        $api = $bctype['api'];
        $arr = explode('&', $api);
        $trueapi = $arr[0] . '&' . $arr[1] . '&' . 'rows=5' . '&' . $arr[3];
        $file = $this->curl_get($trueapi);
        $arrpoint = json_decode($file, true);
        $arr = $arrpoint['data'];
        for ($i = 0; $i < count($arr); $i ++) {
            $arr[$i]['expect'] = substr($arr[$i]['expect'], strlen($arr[$i]['expect']) - 3, 3);
            $opencode = $arr[$i]['opencode'];
            $newarr = explode(',', $opencode);
            $newcode = $this->wsjarr($newarr);
            $arr[$i]['opencode'] = $newcode;
            unset($arr[$i]['opentime']);
            unset($arr[$i]['opentimestamp']);
        }
        return $arr;
    }

    private function wsjarr($arr)
    {
        $newcode = '';
        for ($j = 0; $j < count($arr); $j ++) {
            $newcode .= $arr[$j];
        }
        return $newcode;
    }

    private function curl_get($url)
    {
        $testurl = $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $testurl);
        // 参数为1表示传输数据，为0表示直接输出显示。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 参数为0表示不带头文件，为1表示带头文件
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
        // print_r($output);
    }
}