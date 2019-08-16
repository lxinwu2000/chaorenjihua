<?php
namespace app\common\model;

use think\Model;

class Common extends Model
{
    // 获取人的id
    public function getuserid()
    {
        $token = session('token');
        $userinfo = db('user')->where('token', $token)
            ->field('id')
            ->find();
        return $userinfo['id'];
    }
    // 获取彩票名称
    public function bctype($id)
    {
        $res = db('bctype')->where('id', $id)
            ->field('bcname')
            ->find();
        return $res['bcname'];
    }
    // 规则名称
    public function guizename($key)
    {
        $arr = array(
            "1" => 后一,
            "2" => 后二,
            "3" => 后三,
            "4" => 前一,
            "5" => 前二,
            "6" => 前三,
            "7" => 五星,
            "8" => 单式,
            "9" => 复式,
            "10" => 1,
            "11" => 2,
            "12" => 3,
            "13" => 4,
            "14" => 5
        );
        return $arr['' . $key . ''];
    }
    // 判断中和挂
    // 后一单式 $chuhao 随机出号 $opencode $dingwei 定位 $leixing 类型
    // echo substr($str, -1); //截取后面字符的方式,-2,-3
    // echo substr($str, 0,1);//截取前面的字符窜 0,1 0,2
    public function checkrule($chuhao, $opencode, $dingwei, $leixing)
    {
        // 后一 单式
        if ($dingwei == '1' && $leixing == '8') {
            $arr = str_split($chuhao);
            $opencode = substr($opencode, - 1); // 开奖最后1位
            $index = 0;
            for ($i = 0; $i < count($arr); $i ++) {
                if ($arr[$i] == $opencode) {
                    $index += 1;
                }
            }
            if ($index >= 1) {
                return '中';
            } else {
                return '挂';
            }
        }
        // 后二 单式
        if ($dingwei == '2' && $leixing == '8') {
            $arr = str_split($chuhao);
            $opencode = substr($opencode, - 2); // 开奖最后2位
            $openarr = str_split($opencode);
            $index = 0;
            for ($i = 0; $i < count($arr); $i ++) {
                $a1 = $arr[$i];
                for ($j = 0; $j < count($openarr); $j ++) {
                    $a2 = $openarr[$j];
                    if ($a1 == $a2) {
                        $index += 1;
                    }
                }
            }
            if ($index >= 1) {
                return '中';
            } else {
                return '挂';
            }
        }
        // 后三 单式
        if ($dingwei == '3' && $leixing == '8') {
            $arr = str_split($chuhao);
            $opencode = substr($opencode, - 3); // 开奖最后3位
            $openarr = str_split($opencode);
            $index = 0;
            for ($i = 0; $i < count($arr); $i ++) {
                $a1 = $arr[$i];
                for ($j = 0; $j < count($openarr); $j ++) {
                    $a2 = $openarr[$j];
                    if ($a1 == $a2) {
                        $index += 1;
                    }
                }
            }
            if ($index >= 1) {
                return '中';
            } else {
                return '挂';
            }
        }
        // 后一复式
        if ($dingwei == '1' && $leixing == '9') {
            $arr = str_split($chuhao);
            $opencode = substr($opencode, - 1); // 开奖最后1位
            $index = 0;
            for ($i = 0; $i < count($arr); $i ++) {
                if ($arr[$i] == $opencode) {
                    $index += 1;
                }
            }
            if ($index >= 1) {
                return '中';
            } else {
                return '挂';
            }
        }
        
        // 后二复式
        if ($dingwei == '2' && $leixing == '9') {
            $arr = str_split($chuhao);
            $opencode = substr($opencode, - 2); // 开奖最后2位
            $openarr = str_split($opencode);
            $index = 0;
            for ($i = 0; $i < count($arr); $i ++) {
                $a1 = $arr[$i];
                for ($j = 0; $j < count($openarr); $j ++) {
                    $a2 = $openarr[$j];
                    if ($a1 == $a2) {
                        $index += 1;
                    }
                }
            }
            if ($index >= 2) {
                return '中';
            } else {
                return '挂';
            }
        }
        // 后三复式
        if ($dingwei == '3' && $leixing == '9') {
            $arr = str_split($chuhao);
            $opencode = substr($opencode, - 3); // 开奖最后3位
            $openarr = str_split($opencode);
            $index = 0;
            for ($i = 0; $i < count($arr); $i ++) {
                $a1 = $arr[$i];
                for ($j = 0; $j < count($openarr); $j ++) {
                    $a2 = $openarr[$j];
                    if ($a1 == $a2) {
                        $index += 1;
                    }
                }
            }
            if ($index >= 3) {
                return '中';
            } else {
                return '挂';
            }
        }
        // 前一单式
        if ($dingwei == '4' && $leixing == '8') {
            $arr = str_split($chuhao);
            $opencode = substr($opencode, 0, 1); // 开奖前1位
            $index = 0;
            for ($i = 0; $i < count($arr); $i ++) {
                if ($arr[$i] == $opencode) {
                    $index += 1;
                }
            }
            if ($index >= 1) {
                return '中';
            } else {
                return '挂';
            }
        }
        
        // 前2单式
        if ($dingwei == '5' && $leixing == '8') {
            $arr = str_split($chuhao);
            $opencode = substr($opencode, 0, 2); // 开奖前2位
            $openarr = str_split($opencode);
            $index = 0;
            for ($i = 0; $i < count($arr); $i ++) {
                $a1 = $arr[$i];
                for ($j = 0; $j < count($openarr); $j ++) {
                    $a2 = $openarr[$j];
                    if ($a1 == $a2) {
                        $index += 1;
                    }
                }
            }
            if ($index >= 1) {
                return '中';
            } else {
                return '挂';
            }
        }
        // 前3单式
        if ($dingwei == '6' && $leixing == '8') {
            $arr = str_split($chuhao);
            $opencode = substr($opencode, 0, 3); // 开奖前3位
            $openarr = str_split($opencode);
            $index = 0;
            for ($i = 0; $i < count($arr); $i ++) {
                $a1 = $arr[$i];
                for ($j = 0; $j < count($openarr); $j ++) {
                    $a2 = $openarr[$j];
                    if ($a1 == $a2) {
                        $index += 1;
                    }
                }
            }
            if ($index >= 1) {
                return '中';
            } else {
                return '挂';
            }
        }
        // 前一复式
        if ($dingwei == '4' && $leixing == '9') {
            $arr = str_split($chuhao);
            $opencode = substr($opencode, 0, 1); // 开奖前1位
            $index = 0;
            for ($i = 0; $i < count($arr); $i ++) {
                if ($arr[$i] == $opencode) {
                    $index += 1;
                }
            }
            if ($index >= 1) {
                return '中';
            } else {
                return '挂';
            }
        }
        // 前2复式
        if ($dingwei == '5' && $leixing == '9') {
            $arr = str_split($chuhao);
            $opencode = substr($opencode, 0, 2); // 开奖前2位
            $openarr = str_split($opencode);
            $index = 0;
            for ($i = 0; $i < count($arr); $i ++) {
                $a1 = $arr[$i];
                for ($j = 0; $j < count($openarr); $j ++) {
                    $a2 = $openarr[$j];
                    if ($a1 == $a2) {
                        $index += 1;
                    }
                }
            }
            if ($index >= 2) {
                return '中';
            } else {
                return '挂';
            }
        }
        
        // 前3复式
        if ($dingwei == '6' && $leixing == '9') {
            $arr = str_split($chuhao);
            $opencode = substr($opencode, 0, 3); // 开奖前3位
            $openarr = str_split($opencode);
            $index = 0;
            for ($i = 0; $i < count($arr); $i ++) {
                $a1 = $arr[$i];
                for ($j = 0; $j < count($openarr); $j ++) {
                    $a2 = $openarr[$j];
                    if ($a1 == $a2) {
                        $index += 1;
                    }
                }
            }
            if ($index >= 3) {
                return '中';
            } else {
                return '挂';
            }
        }
        // 五星单式
        if ($dingwei == '7' && $leixing == '8') {
            $arr = str_split($chuhao);
            $opencode = substr($opencode, 0, 5); // 开奖前5位
            $openarr = str_split($opencode);
            $index = 0;
            for ($i = 0; $i < count($arr); $i ++) {
                $a1 = $arr[$i];
                for ($j = 0; $j < count($openarr); $j ++) {
                    $a2 = $openarr[$j];
                    if ($a1 == $a2) {
                        $index += 1;
                    }
                }
            }
            if ($index >= 1) {
                return '中';
            } else {
                return '挂';
            }
        }
        // 五星复式
        if ($dingwei == '7' && $leixing == '9') {
            $arr = str_split($chuhao);
            $opencode = substr($opencode, 0, 5); // 开奖前5位
            $openarr = str_split($opencode);
            $index = 0;
            for ($i = 0; $i < count($arr); $i ++) {
                $a1 = $arr[$i];
                for ($j = 0; $j < count($openarr); $j ++) {
                    $a2 = $openarr[$j];
                    if ($a1 == $a2) {
                        $index += 1;
                    }
                }
            }
            if ($index >= 5) {
                return '中';
            } else {
                return '挂';
            }
        }
    }

    /**
     * 大小单双-big small single double
     * 
     * @param
     *            $opt1：bssd-大小单双
     * @param
     *            $opt2：unit-个,ten-十,hundred-百,thousand-千,wan-万,beforetwo-前二,aftertwo-后二,beforethree-前三,middlethree-中三,afterthree-后三,beforefour-前四,afterfour-后四,none-无
     * @param
     *            $opt3：position-定位,bigsmall-大小,singledouble-单双,singlemode-单式,doublemode-复式,danma-胆码,groupthree-组三,groupsix-组六      
     * @param
     *            $openarr：开奖号码,数组
     * @param
     *            $planarr：计划号码,数组
     */
    private function bssd($opt1, $opt2, $opt3, $openarr, $planarr)
    {
        $num = 0;
        if ($opt2 == "wan") {
            for ($i = 0; $i < count($planarr); $i ++) {
                if ($planarr[$i] == $openarr[0]) {
                    $num ++;
                    break;
                }
            }
            return $num >= 1;
        } elseif ($opt2 == "thousand") {
            for ($i = 0; $i < count($planarr); $i ++) {
                if ($planarr[$i] == $openarr[1]) {
                    $num ++;
                    break;
                }
            }
            return $num >= 1;
        } elseif ($opt2 == "hundred") {
            for ($i = 0; $i < count($planarr); $i ++) {
                if ($planarr[$i] == $openarr[2]) {
                    $num ++;
                    break;
                }
            }
            return $num >= 1;
        } elseif ($opt2 == "ten") {
            for ($i = 0; $i < count($planarr); $i ++) {
                if ($planarr[$i] == $openarr[3]) {
                    $num ++;
                    break;
                }
            } 
            return $num >= 1;
        } else {
            for ($i = 0; $i < count($planarr); $i ++) {
                if ($planarr[$i] == $openarr[4]) {
                    $num ++;
                    break;
                }
            }
            return $num >= 1;
        }
        return false;
    }

    /**
     * 二星
     * 
     * @param
     *            $opt1:twostar-二星
     * @param
     *            $opt2:unit-个,ten-十,hundred-百,thousand-千,wan-万,beforetwo-前二,aftertwo-后二,beforethree-前三,middlethree-中三,afterthree-后三,beforefour-前四,afterfour-后四,none-无
     * @param
     *            $opt3:position-定位,bigsmall-大小,singledouble-单双,singlemode-单式,doublemode-复式,danma-胆码,groupthree-组三,groupsix-组六
     * @param
     *            $openarr:开奖号码,数组
     * @param
     *            $planarr:计划号码,数组
     */
    private function twostar($opt1, $opt2, $opt3, $openarr, $planarr)
    {
        if ($opt2 == "beforetwo") {
            // 单式：所选号码与开奖号码的前2位一致，且顺序一致，即为中奖
            if ($opt3 == "singlemode") {
                return $openarr[0] == $planarr[0] && $openarr[1] == $planarr[1];
            }            // 复式：不限顺序，所选号码中有2位与开奖号码的前2位一致，即为中奖
            elseif ($opt3 == "doublemode") {
                $cnt = 0;
                for ($i = 0; $i < 2; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 2;
            }            // 胆码：开奖号前2位号码中任意一个号与所选号相同，即为中奖。
            elseif ($opt3 == "danma") {
                $cnt = 0;
                for ($i = 0; $i < 2; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 1;
            }
        } elseif ($opt2 == "aftertwo") {
            // 单式：所选号码与开奖号码的后2位一致，且顺序一致，即为中奖
            if ($opt3 == "singlemode") {
                return $openarr[count($openarr) - 1] == $planarr[count($planarr) - 1] && $openarr[count($openarr) - 2] == $planarr[count($planarr) - 2];
            }            // 复式：不限顺序，所选号码中有2位与开奖号码的后2位一致，即为中奖
            elseif ($opt3 == "doublemode") {
                $cnt = 0;
                for ($i = count($openarr) - 1; $i >= count($openarr) - 2; $i --) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 2;
            }            // 胆码：开奖号后2位号码中任意一个号与所选号相同，即为中奖。
            elseif ($opt3 == "danma") {
                $cnt = 0;
                for ($i = count($openarr) - 1; $i >= count($openarr) - 2; $i --) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 1;
            }
        }
        return false;
    }

    /**
     *
     * @param
     *            $opt1:threestar-三星
     * @param
     *            $opt2:unit-个,ten-十,hundred-百,thousand-千,wan-万,beforetwo-前二,aftertwo-后二,beforethree-前三,middlethree-中三,afterthree-后三,beforefour-前四,afterfour-后四,none-无
     * @param
     *            $opt3:position-定位,bigsmall-大小,singledouble-单双,singlemode-单式,doublemode-复式,danma-胆码,groupthree-组三,groupsix-组六
     * @param
     *            $openarr:开奖号码,数组
     * @param
     *            $planarr:计划号码,数组
     */
    private function threestar($opt1, $opt2, $opt3, $openarr, $planarr)
    {
        if ($opt2 == "beforethree") {
            // 单式：所选号与开奖号前三位相同，且顺序一致，即为中奖。
            if ($opt3 == "singlemode") {
                return $openarr[0] == $planarr[0] && $openarr[1] == $planarr[1] && $openarr[2] == $planarr[2];
            }            // 复式：不限顺序，所选号码中有3位与开奖号码的前三位一致，即为中奖
            elseif ($opt3 == "doublemode") {
                $cnt = 0;
                for ($i = 0; $i < 3; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 3;
            }            // 胆码：开奖号前3位号码中任意一个号与所选号相同，即为中奖。
            elseif ($opt3 == "danma") {
                $cnt = 0;
                for ($i = 0; $i < 3; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 1;
            }            // 组三：不限顺序，开奖号前三位中包含全部所选号，且其中一个号码出现2次，即为中奖。
            elseif ($opt3 == "groupthree") {
                // 检查开奖号码是否有重复号
                $repeat = $openarr[0] == $openarr[1] || $openarr[0] == $openarr[2] || $openarr[1] == $openarr[2];
                if (! $repeat) {
                    return false;
                }
                $cnt = 0;
                for ($i = 0; $i < 3; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 3;
            }            // 组六：不限顺序，开奖号前三位中包含全部所选号，且开奖号码中无重复号，即为中奖。
            elseif ($opt3 == "groupsix") {
                // 检查开奖号码是否有重复号
                $repeat = $openarr[0] == $openarr[1] || $openarr[0] == $openarr[2] || $openarr[1] == $openarr[2];
                if ($repeat) {
                    return false;
                }
                $cnt = 0;
                for ($i = 0; $i < 3; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 3;
            }
        } elseif ($opt2 == "middlethree") {
            // 单式：所选号与开奖号中间三位相同，且顺序一致，即为中奖。
            if ($opt3 == "singlemode") {
                return $openarr[1] == $planarr[1] && $openarr[2] == $planarr[2] && $openarr[3] == $planarr[3];
            }            // 复式：不限顺序，所选号码中有3位与开奖号码的中间三位一致，即为中奖
            elseif ($opt3 == "doublemode") {
                $cnt = 0;
                for ($i = 1; $i < 4; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 3;
            }            // 胆码：开奖号中间3位号码中任意一个号与所选号相同，即为中奖。
            elseif ($opt3 == "danma") {
                $cnt = 0;
                for ($i = 1; $i < 4; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 1;
            }            // 组三：不限顺序，开奖号中间三位中包含全部所选号，且其中一个号码出现2次，即为中奖。
            elseif ($opt3 == "groupthree") {
                // 检查开奖号码是否有重复号
                $repeat = $openarr[1] == $openarr[2] || $openarr[1] == $openarr[3] || $openarr[2] == $openarr[3];
                if (! $repeat) {
                    return false;
                }
                $cnt = 0;
                for ($i = 1; $i < 4; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 3;
            }            // 组六：不限顺序，开奖号中间三位中包含全部所选号，且开奖号码中无重复号，即为中奖。
            elseif ($opt3 == "groupsix") {
                // 检查开奖号码是否有重复号
                $repeat = $openarr[1] == $openarr[2] || $openarr[1] == $openarr[3] || $openarr[2] == $openarr[3];
                if ($repeat) {
                    return false;
                }
                $cnt = 0;
                for ($i = 1; $i < 4; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 3;
            }
        } elseif ($opt2 == "afterthree") {
            // 单式：所选号与开奖号后三位相同，且顺序一致，即为中奖。
            if ($opt3 == "singlemode") {
                return $openarr[2] == $planarr[2] && $openarr[3] == $planarr[3] && $openarr[4] == $planarr[4];
            }            // 复式：不限顺序，所选号码中有3位与开奖号码的后三位一致，即为中奖
            elseif ($opt3 == "doublemode") {
                $cnt = 0;
                for ($i = 2; $i < 5; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 3;
            }            // 胆码：开奖号后3位号码中任意一个号与所选号相同，即为中奖。
            elseif ($opt3 == "danma") {
                $cnt = 0;
                for ($i = 2; $i < 5; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 1;
            }            // 组三：不限顺序，开奖号后三位中包含全部所选号，且其中一个号码出现2次，即为中奖。
            elseif ($opt3 == "groupthree") {
                // 检查开奖号码是否有重复号
                $repeat = $openarr[2] == $openarr[3] || $openarr[2] == $openarr[4] || $openarr[3] == $openarr[4];
                if (! $repeat) {
                    return false;
                }
                $cnt = 0;
                for ($i = 2; $i < 5; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 3;
            }            // 组六：不限顺序，开奖号后三位中包含全部所选号，且开奖号码中无重复号，即为中奖。
            elseif ($opt3 == "groupsix") {
                // 检查开奖号码是否有重复号
                $repeat = $openarr[2] == $openarr[3] || $openarr[2] == $openarr[4] || $openarr[3] == $openarr[4];
                if ($repeat) {
                    return false;
                }
                $cnt = 0;
                for ($i = 2; $i < 5; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 3;
            }
        }
        return false;
    }

    /**
     * 四星
     * 
     * @param
     *            $opt1：fourstar-四星
     * @param
     *            $opt2：unit-个,ten-十,hundred-百,thousand-千,wan-万,beforetwo-前二,aftertwo-后二,beforethree-前三,middlethree-中三,afterthree-后三,beforefour-前四,afterfour-后四,none-无
     * @param
     *            $opt3:position-定位,bigsmall-大小,singledouble-单双,singlemode-单式,doublemode-复式,danma-胆码,groupthree-组三,groupsix-组六
     * @param
     *            $openarr：开奖号码,数组
     * @param
     *            $planarr：计划号码,数组
     */
    private function fourstar($opt1, $opt2, $opt3, $openarr, $planarr)
    {
        if ($opt2 == "beforefour") {
            // 复式：不限顺序，所选号与开奖号前四位相同，即为中奖。
            if ($opt3 == "doublemode") {
                $cnt = 0;
                for ($i = 0; $i < 4; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 4;
            }            // 胆码：开奖号前4位号码中任意一个号与所选号相同，即为中奖。
            elseif ($opt3 == "danma") {
                $cnt = 0;
                for ($i = 0; $i < 4; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 1;
            }
        } elseif ($opt2 == "afterfour") {
            // 复式：不限顺序，所选号与开奖号后四位相同，即为中奖。
            if ($opt3 == "doublemode") {
                $cnt = 0;
                for ($i = 1; $i < 5; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 4;
            }            // 胆码：开奖号后4位号码中任意一个号与所选号相同，即为中奖。
            elseif ($opt3 == "danma") {
                $cnt = 0;
                for ($i = 1; $i < 5; $i ++) {
                    for ($j = 0; $j < count($planarr); $j ++) {
                        if ($openarr[$i] == $planarr[$j]) {
                            $cnt ++;
                        }
                    }
                }
                return $cnt >= 1;
            }
        }
        return false;
    }

    /**
     * 五星
     * 
     * @param
     *            $opt1:fivestar-五星
     * @param
     *            $opt2:unit-个,ten-十,hundred-百,thousand-千,wan-万,beforetwo-前二,aftertwo-后二,beforethree-前三,middlethree-中三,afterthree-后三,beforefour-前四,afterfour-后四,none-无
     * @param
     *            $opt3:position-定位,bigsmall-大小,singledouble-单双,singlemode-单式,doublemode-复式,danma-胆码,groupthree-组三,groupsix-组六
     * @param
     *            $openarr:开奖号码,数组
     * @param
     *            $planarr:计划号码,数组
     */
    private function fivestar($opt1, $opt2, $opt3, $openarr, $planarr)
    {
        // 复式：不限顺序，完全一致为中奖
        if ($opt3 == "doublemode") {
            $cnt = 0;
            for ($i = 0; $i < count($openarr); $i ++) {
                for ($j = 0; $j < count($planarr); $j ++) {
                    if ($openarr[$i] == $planarr[$j]) {
                        $cnt ++;
                    }
                }
            }
            return $cnt >= 5;
        }        // 定位或胆码：不限位置（个、十、百、千、万），任意位置出现所选号码即为中奖
        elseif ($opt3 == "danma" || $opt3 == "position") {
            $cnt = 0;
            for ($i = 0; $i < count($openarr); $i ++) {
                for ($j = 0; $j < count($planarr); $j ++) {
                    if ($openarr[$i] == $planarr[$j]) {
                        $cnt ++;
                        break;
                    }
                }
            }
            return $cnt >= 1;
        }
        return false;
    }

    /**
     * 获取中奖结果
     * 参数：
     * $opt1:选项1：bssd-大小单双,twostar-二星,threestar-三星,fourstar-四星,fivestar-五星
     * $opt2:选项2：unit-个,ten-十,hundred-百,thousand-千,wan-万,beforetwo-前二,aftertwo-后二,beforethree-前三,middlethree-中三,afterthree-后三,beforefour-前四,afterfour-后四,none-无
     * $opt3:选项3：position-定位,bigsmall-大小,singledouble-单双,double-双,singlemode-单式,doublemode-复式,danma-胆码,groupthree-组三,groupsix-组六
     * $bssd:大小单双：0-大，1-小，2-单，3-双
     * $opencode:开奖号码,逗号分隔的字符串
     * $plancode:计划号码,逗号分隔的字符串
     */
    public function getprizeresult($opt1, $opt2, $opt3, $opencode, $plancode)
    {
        $openarr = explode(",", $opencode);
        $planarr = explode(",", $plancode);
        if ($opt1 == "bssd" && count($openarr) == 5) {
            return $this->bssd($opt1, $opt2, $opt3, $openarr, $planarr);
        } elseif ($opt1 == "twostar") {
            return $this->twostar($opt1, $opt2, $opt3, $openarr, $planarr);
        } elseif ($opt1 == "threestar") {
            return $this->threestar($opt1, $opt2, $opt3, $openarr, $planarr);
        } elseif ($opt1 == "fourstar") {
            return $this->fourstar($opt1, $opt2, $opt3, $openarr, $planarr);
        } elseif ($opt1 == "fivestar") {
            return $this->fivestar($opt1, $opt2, $opt3, $openarr, $planarr);
        }
    }
}