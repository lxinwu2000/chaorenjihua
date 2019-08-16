<?php
namespace app\home\model;
use think\Model;

class Common extends Model{
    //新人浏览配置时间单位分钟
    public  function looktime(){
        $looktime=db('config')->where('id',1)->field('looktime')->find();
        $minute=$looktime['looktime'];
        $second=$minute*60;
        return $second;
    }
    
}