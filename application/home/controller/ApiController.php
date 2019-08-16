<?php
namespace app\home\controller;

use think\Controller;
use think\Db;
use app\common\model\Common;
use app\common\model\Rule;

class ApiController extends Controller
{

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
    }
    
    // 计算万位
    private function calcWan($code)
    {
        $sum = 0;
        for ($i = 0; $i < strlen($code); $i ++) {
            $sum += $code[$i];
        }
        return $sum % 10;
    }
    
    // 开奖号码以逗号分隔
    private function splitopencode($code)
    {
        $res = "";
        for ($i = 0; $i < strlen($code); $i ++) {
            if ($i == strlen($code) - 1) {
                $res .= $code[$i];
            } else {
                $res .= $code[$i] . ",";
            }
        }
        return $res;
    }

    public function txffc()
    {
        $url = "http://qq-online.org/get_result_list";
        $jsonres = $this->curl_get($url);
        $data = array();
        $expectlist = array();
        $arr = json_decode($jsonres, true);
        foreach ($arr as $i => $value1) {
            if (is_array($value1)) {
                foreach ($value1 as $j => $value2) {
                    if (is_array($value2)) {
                        foreach ($value2 as $k => $val) {
                            if ($k == "issue") {
                                $expectlist["expect"] = $val;
                                $expectlist["jxexpect"] = substr($val, - 4);
                            }
                            if ($k == "count") {
                                $expectlist["opencode"] = substr($val, - 4);
                                $expectlist["opencode"] = $this->calcWan($expectlist["opencode"]) . $expectlist["opencode"];
                                $expectlist["opencode"] = $this->splitopencode($expectlist["opencode"]);
                            }
                            if ($k == "time") {
                                date_default_timezone_set('Asia/Shanghai');
                                $expectlist["opentime"] = substr($val, 0, 4) . "-" . substr($val, 4, 2) . "-" . substr($val, 6, 2) . " " . substr($val, 8, 2) . ":" . substr($val, 10, 2) . ":00";
                                $expectlist["opentimestamp"] = strtotime($expectlist["opentime"]);
                            }
                        }
                    }
                    $data[] = $expectlist;
                }
            }
        }
        $res = array();
        $res["data"] = $data;
        return json($res);
    }
    
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
    
    private function dsrw($pid, $arr)
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
            $plancode = $details[$i]['plancode'];
            $zgstatus = $com->getprizeresult($opt1, $opt2, $opt3, $arr['opencode'], $plancode);
            $rand=rand(0, 9);
            if ($opt3=="bigsmall"){
                $bssd=$rand%2;
            }elseif ($opt3=="singledouble"){
                $bssd=$rand%2+2;
            }
            $newplancode = GeneratePlanCode($opt1,$codelength, $num,$bssd);
            Db::startTrans();
            try {
                //中奖
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
                    $map['plancode'] = $newplancode;
                    $map['expect'] = $arr['expect'] + 1;
                    $map['zgstatus'] = 0;
                    $map['status'] = 0;
                    $map['bssd'] = $bssd;
                    $map['pid'] = $rinfo['pid'];
                    $map["rid"] = $rid;
                    Db::name('draw')->insert($map);
                } 
                //未中奖
                else {
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
                        $map["bssd"]=$bssd;
                    }else {
                        $map["bssd"]=$details[$i]['bssd'];
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
    
    public function getlatestnumbers(){
        $bctypelist=Db::name('bctype')->select();
        for ($i=0;$i<count($bctypelist);$i++){
            $bid=$bctypelist[$i]["id"];
            if ($bctypelist[$i]["api"]=="1"){
                continue;
            }
            $arr = $this->zuixincqssc($bid);
            $planlist=Db::name('plan')->where("bid",$bid)->select();
            for ($j=0;$j<count($planlist);$j++){
                $planinfo=$planlist[$j];
                $this->dsrw($planinfo['id'], $arr);
            }
        }
        
        //2连挂自动删除
        $rulelist=Db::name('rule')->select();
        $config=Db::name("config")->find();
        for ($i=0;$i<count($rulelist);$i++){
            $rinfo=$rulelist[$i];
            $drawdata=Db::name('draw')->where("rid",$rinfo["id"])->order("expect")->select();
            $num=$rinfo["count"]*$config["continuedfailcount"];
            $failcount=0;
            for ($j=0;$j<count($drawdata);$j++){
                $draw=$drawdata[$j];
                if ($draw["status"]==2){
                    $failcount++;
                }elseif ($draw["status"]==1){
                    $failcount=0;
                }
                if ($failcount>=$num){
                    Db::name('draw')->where("expect","<=",$draw["expect"])
                        ->where("rid",$rinfo["id"])->delete();
                    break;
                }
            }
            
        }
    }
    
    public function clearhisdraw(){
        $configdata=Db::name("config")->find();
        $reservecount=$configdata["reservecount"];
        $drawdata=Db::query("select rid,count(1) cnt from zxcms_draw group by rid");
        for ($i = 0; $i < count($drawdata); $i ++) {
            if ($drawdata[$i]['cnt']>$reservecount){
                $rid=$drawdata[$i]['rid'];
                $deldata=Db::query("select expect from zxcms_draw where rid=? order by expect desc limit ?,1",[$rid,$reservecount]);
                Db::execute("delete from zxcms_draw where rid=? and expect<?",[$rid,$deldata[0]['expect']]);
            }
        }
    }
}
