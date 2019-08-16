<?php
namespace app\home\controller;

use think\Controller;
use think\Db;

class PayController extends Controller
{   
    //获取用户真实IP
    private function getIp() {
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
    
    //生成订单
    private function makeOrderSn(){
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderSn;
    }
    
    protected function sign($data_arr) {
        return md5(join('',$data_arr));
    }
    
    protected function payconfig($price,$name,$order_id,$notify_url){
        $pay_type = 'jsapi';     # 付款方式
        $secret = '8bf8617b743344eda9116dc5cf1d2657';
        $sign = $this->sign(array($name, $pay_type, $price, $order_id, $notify_url, $secret));
        $newdata=[
            "price" =>$price, # 从 URL 获取充值金额 price
            "name" =>$name,  # 订单商品名称
            "pay_type" =>'jsapi',     # 付款方式
            "order_id" =>$order_id,   # 自己创建的本地订单号
            "notify_url" =>$notify_url,  # 回调通知地址
            "sign"=>$sign
        ];
        return $newdata;
    }
    
    public function pay(){
        $uid=input('uid');
        $aid=input('aid');
        $agentinfo=Db::name('agent')->where('id',$aid)->find();
        if ($agentinfo){
            $data["uid"]=$uid;
            $data["agentid"]=$aid;
            $data["createtime"]=time();
            $data['ordernumber']=$this->makeOrderSn();
            $data["ip"]=$this->getIp();
            $data["status"]=0;
            $data["remark"]="";
            $res=Db::name("proxyorder")->insert($data);
            if ($res){
                $zzid=Db::name('proxyorder')->getLastInsID();
                $price=$agentinfo['bonus'];
                $name=$agentinfo['addrenshu']."人版";
                $order_id=$data['ordernumber'];
                $notify_url='http://cr.shiyaogong.top/home/pay/notify';
                //调起支付
                $this->weixinpay($price, $name, $order_id, $notify_url);
            }
        }
    }
    
    protected function weixinpay($price,$name,$order_id,$notify_url){
        $apiurl="https://xorpay.com/api/cashier/4277";
        $data=$this->payconfig($price,$name,$order_id,$notify_url);
        $html='<html>
            <head>
            <title>正在跳转支付中 .....</title>
             <meta http-equiv="content-type" content="text/html; charset=utf-8">
            </head>
      <body>
          <form id="post_datasss" action="'.$apiurl.'" method="post">
              <input type="hidden" name="name" value="'.$data['name'].'"/>
              <input type="hidden" name="pay_type" value="'.$data['pay_type'].'"/>
              <input type="hidden" name="price" value="'.$data['price'].'"/>
              <input type="hidden" name="order_id" value="'.$data['order_id'].'"/>
              <input type="hidden" name="notify_url" value="'.$data['notify_url'].'"/>
              <input type="hidden" name="sign" value="'.$data['sign'].'"/>
          </form>
          <script>document.getElementById("post_datasss").submit();</script>
      </body>
      </html>';
        echo $html;
    }
    
    
    
    public function notify(){
        $data=request()->param();
        $sign = $this->sign(array($data['aoid'], $data['order_id'], $data['pay_price'], $data['pay_time'], '8bf8617b743344eda9116dc5cf1d2657'));
        if($sign == $data['sign']) {
            # 签名验证成功，更新数据
            $order_id=$data['order_id'];
            $orderinfo=Db::name('proxyorder')->where('ordernumber',$order_id)->find();
            if ($orderinfo['status']=='0'){
                Db::name('proxyorder')->where('ordernumber',$order_id)->update(['status'=>1]);
            }
            //用户id
            $uid=$orderinfo['uid'];
            $userinfo=Db::name('user')->where('id',$uid)->find();
            date_default_timezone_set("Asia/Shanghai");
            $daoqitime=date_create($userinfo["daoqitime"]);
             //代理类型id
            $aid=$orderinfo['agentid'];
            $agentinfo=Db::name('agent')->where('id',$aid)->find();
            date_add($daoqitime,date_interval_create_from_date_string($agentinfo["validity"]." days"));
            //更新到期时间
            Db::name('user')->where('id',$uid)->update(["daoqitime"=>date_format($daoqitime,"Y-m-d H:i:s")]);
            $this->redirect('http://cr.shiyaogong.top');
        } else {
            # 签名验证错误
            header("HTTP/1.0 405 Method Not Allowed");
            exit();
        };
    }
}
