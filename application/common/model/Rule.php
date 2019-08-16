<?php
namespace app\common\model;
use think\Model;

class Rule extends Model{
    public function getOption01Attr($value)
    {
        $status = ["bssd"=>"大小单双","twostar"=>"二星","threestar"=>"三星","fourstar"=>"四星","fivestar"=>"五星"];
        return $status[$value];
    }
    public function getOption02Attr($value)
    {
        $status = ["unit"=>"个","ten"=>"十","hundred"=>"百","thousand"=>"千","wan"=>"万","beforetwo"=>"前二","aftertwo"=>"后二","beforethree"=>"前三","middlethree"=>"中三","afterthree"=>"后三","beforefour"=>"前四","afterfour"=>"后四","none"=>"无"];
        return $status[$value];
    }
    public function getOption03Attr($value)
    {
        $status = ["position"=>"定位","bigsmall"=>"大小","singledouble"=>"单双","singlemode"=>"单式","doublemode"=>"复式","danma"=>"胆码","groupthree"=>"组三","groupsix"=>"组六"];
        return $status[$value];
    }
}