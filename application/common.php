<?php

use phpDocumentor\Reflection\DocBlock\Tags\Generic;

// 应用公共文件
function p($str) {
    echo '<pre>';
    print_r($str);
}
//数组的最大下标
function maxxiabiao($array){
    $hots = $array;
    $key = array_search(max($hots),$hots);
    return $key;
    
}
/*删除指定目录下的文件，及目录文件夹*/
function deldir($dir)
{
    $dh = opendir($dir);
    while ($file = readdir($dh))
    {
        if ($file != "." && $file != "..")
        {
            $fullpath = $dir . "/" . $file;
            if (!is_dir($fullpath))
            {
                unlink($fullpath);
            } else
            {
                deldir($fullpath);
            }
        }
    }
    closedir($dh);
    if (rmdir($dir))
    {
        return true;
    } else
    {
        return false;
    }
}
//数组的直相加取最后一位
function wsjarr($arr){
    $count=0;
    for ($i=0;$i<count($arr);$i++){
        $count+=$arr[$i];
    }
    $wwcode=substr($count, -1);
    return $wwcode;
}
function zddarr($arr){
    for ($i=0;$i<count($arr);$i++){
        $count.=$arr[$i].',';
    }
    $res=substr($count, 0,-1);
    return $res;
}
//ajax返回信息
function ajaxinfo($msg,$state="0",$datas=array()){
    $data = array(
        'state'    =>  $state,
        'msg'     =>  $msg,
        'data'    =>  $datas
    );
    return json($data);
}

//随机生成4位数字
function GetRandStr($length)
{
    return rand(pow(10,($length-1)), pow(10,$length)-1);
}
function GetRandStr2($num){
    $str='';
    $numbers = range (0,9);
    shuffle ($numbers);
    $num=5;
    $result = array_slice($numbers,0,$num);
    for ($i=0;$i<count($result);$i++){
        $str.=$result[$i];
    }
    return $str;
}

/*
 * 生成计划号码
 * 参数：
 * $opt1：bssd-大小单双,twostar-二星,threestar-三星,fourstar-四星,fivestar-五星
 * $opt2：unit-个,ten-十,hundred-百,thousand-千,wan-万,beforetwo-前二,aftertwo-后二,beforethree-前三,middlethree-中三,afterthree-后三,beforefour-前四,afterfour-后四,none-无
 * $opt3：position-定位,bigsmall-大小,singledouble-单双,singlemode-单式,doublemode-复式,danma-胆码,groupthree-组三,groupsix-组六
 * $opt4：定位号码
 * $max：计划号中每一位的最大值，默认为9
 * $codelength:计划号长度
 * 返回值：
 * 逗号分隔的字符串
 
function GeneratePlanCode($opt1,$opt2,$opt3,$opt4,$max=9,$codelength=5){
    $numbers = range (0,$max);
    shuffle ($numbers);
    $res=array();
    
    for ($i=0;$i<$codelength;$i++){
        if ($max>9){
            $res[]=sprintf("%02d",$numbers[$i]);
        }else {
            $res[]=$numbers[$i];
        }
    }
    return implode(",", $res);
}
*/
/**
 * 生成计划号码
 * $opt1：bssd-大小单双,twostar-二星,threestar-三星,fourstar-四星,fivestar-五星
 * @param number $max 计划号中每一位的最大值，默认为9
 * @param number $codelength 计划号位数
 * @param number $bssd 大小单双，0-大，1-小，2-单，3-双
 */
function GeneratePlanCode($opt1,$max=9,$codelength=5,$bssd){
    $numbers = range (0,9);
    shuffle ($numbers);
    $res=array();
    if ($opt1=="bssd"){
        if ($bssd==0){
            array_push ($res,"5,6,7,8,9");
        }elseif ($bssd==1){
            array_push ($res,"0,1,2,3,4");
        }elseif ($bssd==2){
            array_push ($res,"1,3,5,7,9");
        }elseif ($bssd==3){
            array_push ($res,"0,2,4,6,8");
        }
        if ($max>9){
            for ($i=0;$i<$codelength;$i++){
                $res[$i]=sprintf("%02d",$res[$i]);
            }
        }
        return implode(",", $res);
    }
    
    for ($i=0;$i<$codelength;$i++){
        if ($max>9){
            $res[]=sprintf("%02d",$numbers[$i]);
        }else {
            $res[]=$numbers[$i];
        }
    }
    return implode(",", $res);    
}


function zhwzfc($arr){
    $newcode='';
    for ($j=0;$j<count($arr);$j++){
        $newcode.=$arr[$j];
    }
    return $newcode;
}

//判断开奖中 $str1开奖号 $str2随机号
function strcomp($str1,$str2){
    //开奖号
    $newstr1=str_split($str1);
    sort($newstr1);
    $wsjarr1=zhwzfc($newstr1);
    //随机号
    $newstr2=str_split($str2);
    sort($newstr2);
    $wsjarr2=zhwzfc($newstr2);    
    //计算数组的长度
    $count1=count($newstr1);
    $count2=count($newstr2);    
    //以数组短的计算
    if ($count1>$count2){
       for ($i=0;$i<count($newstr2);$i++){
           if(strpos($wsjarr1,$newstr2[$i]) !==false){
               return true;
           }else {
               return false;
           }
       }
        
    }else {
        for ($i=0;$i<count($newstr1);$i++){
            if(strpos($wsjarr2,$newstr1[$i]) !==false){
                return true;
            }else {
                return false;
            }
        }
    }        
}
//统计相同字符的个数

function strcompcount($str1,$str2){   
    //开奖号
    $newstr1=str_split($str1);
    sort($newstr1);
    $wsjarr1=zhwzfc($newstr1);
    //随机号
    $newstr2=str_split($str2);
    sort($newstr2);
    //以开奖组短的计算   
        $count=0;
        for ($i=0;$i<count($newstr1);$i++){
            if(strpos($wsjarr1,$newstr2[$i]) !==false){
                $count+=1;               
            }
        }

    
    return $count;
}


//获取月份天数
function month(){
    $year = date('Y');
    $b= date("m");
    $t ="";
    if ($b == 9 || $b == 4 || $b == 6 || $b == 11){
        $t=30;
    }else if ($b == 1 || $b == 3 || $b == 5 || $b == 7 || $b == 8 || $b == 10 || $b == 12){
        $t=31;
    }else if ($b == 2) {
        if (($year % 4 == 0 && $year % 100 != 0) || ($year % 100 == 0 && $year % 400 == 0)) {
            $t = 29;
        } else {
            $t = 28;
        }
    }
    return $t;
}

// function delimg($url){
//     $url='.'.$url;
//     unlink($url);
// }
function nodeTree($arr, $id = 0, $level = 0) {
    static $array = array();
    foreach ($arr as $v) {
        if ($v['parentid'] == $id) {
            $v['level'] = $level;
            $array[] = $v;
            nodeTree($arr, $v['id'], $level + 1);
        }
    }
    return $array;
}

/**
 * 数组转树
 * @param type $list
 * @param type $root
 * @param type $pk
 * @param type $pid
 * @param type $child
 * @return type
 */
function list_to_tree($list, $root = 0, $pk = 'id', $pid = 'parentid', $child = '_child') {
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = 0;
            if (isset($data[$pid])) {
                $parentId = $data[$pid];
            }
            if ((string) $root == $parentId) {
                $tree[] = &$list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = &$refer[$parentId];
                    $parent[$child][] = &$list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * 下拉选择框
 */
function select($array = array(), $id = 0, $str = '', $default_option = '') {
    $string = '<select ' . $str . '>';
    $default_selected = (empty($id) && $default_option) ? 'selected' : '';
    if ($default_option)
        $string .= "<option value='' $default_selected>$default_option</option>";
    if (!is_array($array) || count($array) == 0)
        return false;
    $ids = array();
    if (isset($id))
        $ids = explode(',', $id);
    foreach ($array as $key => $value) {
        $selected = in_array($key, $ids) ? 'selected' : '';
        $string .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
    }
    $string .= '</select>';
    return $string;
}

/**
 * 复选框
 * 
 * @param $array 选项 二维数组
 * @param $id 默认选中值，多个用 '逗号'分割
 * @param $str 属性
 * @param $defaultvalue 是否增加默认值 默认值为 -99
 * @param $width 宽度
 */
function checkbox($array = array(), $id = '', $str = '', $defaultvalue = '', $width = 0, $field = '') {
    $string = '';
    $id = trim($id);
    if ($id != '')
        $id = strpos($id, ',') ? explode(',', $id) : array($id);
    if ($defaultvalue)
        $string .= '<input type="hidden" ' . $str . ' value="-99">';
    $i = 1;
    foreach ($array as $key => $value) {
        $key = trim($key);
        $checked = ($id && in_array($key, $id)) ? 'checked' : '';
        if ($width)
            $string .= '<label class="ib" style="width:' . $width . 'px">';
        $string .= '<input type="checkbox" ' . $str . ' id="' . $field . '_' . $i . '" ' . $checked . ' value="' . $key . '"> ' . $value;
        if ($width)
            $string .= '</label>';
        $i++;
    }
    return $string;
}

/**
 * 单选框
 * 
 * @param $array 选项 二维数组
 * @param $id 默认选中值
 * @param $str 属性
 */
function radio($array = array(), $id = 0, $str = '', $width = 0, $field = '') {
    $string = '';
    foreach ($array as $key => $value) {
        $checked = trim($id) == trim($key) ? 'checked' : '';
        if ($width)
            $string .= '<label class="ib" style="width:' . $width . 'px">';
        $string .= '<input type="radio" ' . $str . ' id="' . $field . '_' . $key . '" ' . $checked . ' value="' . $key . '"> ' . $value;
        if ($width)
            $string .= '</label>';
    }
    return $string;
}

/**
 * 字符串加密、解密函数
 *
 *
 * @param	string	$txt		字符串
 * @param	string	$operation	ENCODE为加密，DECODE为解密，可选参数，默认为ENCODE，
 * @param	string	$key		密钥：数字、字母、下划线
 * @param	string	$expiry		过期时间
 * @return	string
 */
function encry_code($string, $operation = 'ENCODE', $key = '', $expiry = 0) {
    $ckey_length = 4;
    $key = md5($key != '' ? $key : config('encry_key'));
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(strtr(substr($string, $ckey_length), '-_', '+/')) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . rtrim(strtr(base64_encode($result), '+/', '-_'), '=');
    }
}

/*
 * 支付宝/微信 配置
 */
function pay_config(){
    $config = [
        // 微信支付参数
        'wechat' => [
        'debug'      => false, // 沙箱模式
        'app_id'     => 'wxcf21ed8a6d670ed6', // 应用ID
        'mch_id'     => '1516544591', // 微信支付商户号
        'mch_key'    => 'SHANXIqinlingermei15994193357666', // 微信支付密钥
        'ssl_cer'    => '/www/wwwroot/wsjtest/cert/apiclient_cert.pem', // 微信证书 cert 文件
        'ssl_key'    => '/www/wwwroot/wsjtest/cert/apiclient_key.pem', // 微信证书 key 文件
        'notify_url' => '', // 支付通知URL
        'cache_path' => '',// 缓存目录配置（沙箱模式需要用到）
        ],
        // 支付宝支付参数
        'alipay' => [
            'debug'       => false, // 沙箱模式
            'app_id'      => '2018041202543018', // 应用ID
            'public_key'  =>  'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoYiCrs2DEDBVu9A5lGA0kwzDT4WsmOwNEdA807S6V1hDWcbcwDQ13klvNU2Uli9rNzRTa+MgDJggWZ99Z5NHvRzd6Vv3gG914n/G8Jmm7oLhkyaDVvnlqeQsP1ihPJiE6/RHT2QcEBLMCG7ca4Q4uCIa+Ji0/58BGciVFWl+OJQxttwCmhW1i+vnaJnc7fCvsTdVLcPnejugTmY52Y9SVkXykjZE3uuEdyvq1ZV9pzWm+cmSCf+aavSJWr+OmVrUpzOjeuMrDhILmwjwEqhd0hiQslzAV9naeIxQk+teMIzwB8Gg3LTAG5a1nHgIO8ZHkQ0/rv8q48KbfAqtPBym/QIDAQAB',
            //'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwzVZBtB3MGGZVvFdVgod6Im0RtL89YI/n2L+BBMgJpa6Zrmpakp5Qw85/7s8/lM02o4sET+I1jU0Rt+rNK26FAX3W+wBAZtYlJYKcdILfoZ5U6zNjCUe6syXvMznfbzaD7WkAQEHkjQ7UuIi6/2YJwp1Qm6hqkAa/KH8Q/Q0crBZYOSKWV/I5jfCPwf07ZieLqC4h9zY+Bsk2/GECAROpvI23Z9NzZkE2qyT5Ez3crE+Oj97h5ZKfscFo2l+DwfVgoK12E6xFNKIGHb67RouIv5dF2HTyBxIWuopmav9Scbiv1LzOy2HB1XqGwspixnhAOmfqWCLmbK9vPO9X8XJ0QIDAQAB',//支付宝公钥
            'private_key' => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDDNVkG0HcwYZlW8V1WCh3oibRG0vz1gj+fYv4EEyAmlrpmualqSnlDDzn/uzz+UzTajiwRP4jWNTRG36s0rboUBfdb7AEBm1iUlgpx0gt+hnlTrM2MJR7qzJe8zOd9vNoPtaQBAQeSNDtS4iLr/ZgnCnVCbqGqQBr8ofxD9DRysFlg5IpZX8jmN8I/B/TtmJ4uoLiH3Nj4GyTb8YQIBE6m8jbdn03NmQTarJPkTPdysT46P3uHlkp+xwWjaX4PB9WCgrXYTrEU0ogYdvrtGi4i/l0XYdPIHEha6imZq/1JxuK/UvM7LYcHVeobCymLGeEA6Z+pYIuZsr28871fxcnRAgMBAAECggEAQ7NXyeY4v/3JWX7iGPnvP3uqmzmHY3olqJiDclRTvS5fPUs8t1FW1uwL+GYulCG1Xesa22yGf4v6Mm5WCTILK/CZxjaGbtE6mmWi/7CSLfJjV6LBss4Y9+O26FLEIjaCBhq/411BC/KzdF5bMC9GOpwE6OJHjS0obt+bBtJF8lQcc58iWG3pMCrZGiIRwpL2ii/t+L0VeuJwMTwZhvZTvJPqYEJQ1s+RRhk7HJBK0thks6JXTGQtUTTyQfLhz0LqNS8GqxkdVhHFtv803yd6J3gRpHSyHuRPEn96eh+P+efy1en9jSrcb42YkRGQ+LC3sx7aAknGRsiEg8Uw9QVS8QKBgQD+/4NFyQyKtEqzz4q0bzNpNUBuQAc/mJYIrslfDMrXQTXT6OQ9BpLEkMoQGr8qZrTEfcm+wf3wmIqwKDWSgjxddknTYhXO5EKtFjqykalQuy0PFZPUsmWmMbELm12pTDUe8Ar1+AsYcDDn03b28ftXJcfln9AOWHZIiEFUReiUnQKBgQDD+bI0hMnwMmYNpbVGqJ+9fjVXjWUZsclxVFaHXTztdXDM09tphLDxcGkM+YfFVHwRzLCLA6k0QOxtjUGNHkAJhXqEQWaHNxfk6WDsUm0qxc5XXF4ofibWWGjT/79I+EmvBPxf5E4xm2m2dbvX7UrSH0R+nIdCJpzG4gdcOZERxQKBgQCCtQRb7A9CteGow5RsoqduoT4yhR1yCsu1DarozszWg3WM9s6vPURT/4ejs6IToOu94GBeKF/7SKWmYCX9wEYi/jQfZyKYl0ZaJI/X4nQwjpEtLzlEiGE0TDpdIbljw01jW1Wy+P7u+usGmc1cDXNxG0uYt09VWyDeUwFxqhfqvQKBgFC7bnRAN8hhmrUIjLL9CEFR2ruknf+FxAyRx8uf11ejR8K5i+veI95yhnQ333ylHy9d+WRrL6s9C6jdxiFSUuMZP614G7qcMZW1pp30HtC+CBDpFkjsHyex6A1dj/mJfyFAlo8SSDwbX+MwgR+ku2vwQZE+/mF78p3jw1B0zpRZAoGAehh9Iw9eb1aQj5VMHsa9yFQrLw93Jp/UXJw6EkMyrsa8ghyWo6iT6rkumkoldqUGYkhLkmBQxPJrmJnhMgbGMgE10kR5OsX1645/Fhx5YqYYKdJGfH1uaQIecIMoPmUDrImQxv/rHFvPthPbTW4i1PXN+xlvHITOtmoCof2L6is=',//'MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCoYJ/G2o3KzOAStOb23yX9Iy7RrjJI02WFMgnF3CDkYd1iuBHTNpPDT2HQcxhn01iyrFortMt6FQEoyLSq1rYLkrT+O7W3xhXRqPQfSh8PnLIRkcA8r6ixON9yV4luvP9j22KZMPBOPQ4edzraP0iGgWR2Hw0P6T2PRLiwDHuBb2bOAtC9JgK2qr8zjYI54s5fYg8BzjiKQRRqI+ujfGIp0JQa9/JNygg/6nFkgCW5OmOy8GV/DqB6RcFVAIJTY3V4UDzk1JocfrW/BBXqS9KcHG/pirbXjp7cBOXhFCnNe4hbhtlmsA4pM3u91tkZO7s05QL4nPNhSBQjsZ8hFd2JAgMBAAECggEAbTDb7JTmxtE9rmPqM8ZaWGJshkW1Dk0o1MuyUsGCAxkD/HTp+lK2WD4ubIN3HD2Ok6/5ecZw/eaJLe2aW/+JZs/o18XY/ihTFfe++FuU8Qbo40PlqnUvrF35aaki0tF1vUgUFMwLynEFqvQfElFfsvpOL9vLGLpk1Xf3XRN7NF/7DECprJLbxQVKhk6C/R7rK6nsfHFZ/nnJAagmlwy+TwN3SyyiwA3HGuTOd19cjD+V4stllItpIrQroyZ5dizggM263CSnsAobi5eZQ6oyGAvz1kfrTWWWQ7zlubGQyBHgLQoMufiVQEVcqiSSx/L7N4HKx7LD/szLXd8HaV9BEQKBgQD+9yiODSNUuzU7WbBL7RCgUkkwegO2I75Y6wxutciT5DbocVDg9n+pEITiBZ9GIx/0lORSX2296WDsbL4NnhKP67x/miWtUP29Njs77MNELqUwZevQamwa/5rI8L3VbUD/5a2BnRJNPntzrwcELw9wcGhNp8mIApFQg2gwllZFmwKBgQCpD4YQZUqZNr+PGvv49WuPsGaGERF52RqHSbE0/QGSyPNiFmPJDfHhEo583YGOupS5Em0TSuVBBrr3UVhtEQc5jiE42/Mcwxu0rM945T/zP3tecAJyjL2u6MF4MRUXh0Tqh6pM8fEfuNo3JAgDfEsQtbYcW3dVYv93l9bnnM2NqwKBgEJa0JMnmCpVDmWD0f3wlIMk5ydWmeCtLLy3b2TgqnLS0fdshkAF5vN4+RxZmjoGqipdFY7ahxUFx9O/+TZSDUKnd0c+NtAEZAT6ODBAThFQkm0mGVkEWV4tZ3skLEN/S4tNmvpAhqLTwA07X/gWx03b80lCgZCEGo15pYP/nDRBAoGAYv7cGi22CRGuCjZa5eWQHovE/Sxxd1BR4HaeddDRYwqYug8yT0EDKjCbjzKF8vX+sIDBrlJk/DbctIXFqdgWyvUXLxxct7LoPlwTAU/8qou5ygr84+bWC93vrFnRZ/2ltU/LwVLLRzAPV8qZhrD5o8dD5EcLEczumzEmfmmrVBECgYA7uMk2kcDpwRhqtAgAsR5g7ks8biigKsvh48qbIQeAGE1nRONMO/vHNP9otnjYnK7VQLkoFiByhnSPIO6H9HR32EtO5n0De40WBeeQ5xcQYUYttyLENnjMpezahcvLN+/HvXezodxLnPRbf4aCyrmT4eLuqUhBn6g0++/LbFovoQ==', // 支付宝私钥(1行填写)
            'notify_url'  => 'http://tram.wsstreet.net/api/order/ali_notify', // 支付通知URL
            'return_url'  => 'http://tram.wsstreet.net',
        ]

    ];
    return $config;
}

//获取用户真实IP
function getIp() {
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
        else
            if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
                $ip = getenv("HTTP_X_FORWARDED_FOR");
                else
                    if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
                        $ip = getenv("REMOTE_ADDR");
                        else
                            if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
                                $ip = $_SERVER['REMOTE_ADDR'];
                                else
                                    $ip = "127.0.0.1";
                                    return ($ip);
}
