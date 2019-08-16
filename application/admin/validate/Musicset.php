<?php
//验证器Departments
namespace app\common\Validate;
use think\Validate;
class Musicset extends Validate{
    protected $rule = [
        'shutnumber'  =>  'number|unique:number',
    ];
    
    protected $message = [
        'shutnumber.number'  =>  '只能用数字',
        'shutnumber.unique'=>'关数已存在',
    ];
    
   
}