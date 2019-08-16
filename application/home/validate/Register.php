<?php
//验证器注册
namespace app\home\Validate;
use think\Validate;
class Register extends Validate{
    protected $rule = [
        'account'=>'require|unique:user|alphaNum',
        'password'=>'require|confirm',                   
    ];
    
    protected $message = [
        'account.require'=>'账号不能为空',
        'account.unique'=>'账号已经存在',
        'account.alphaNum'=>'账号必须字母加数字',
        'password.require'=>'密码不能为空',
        'password.confirm'=>'确认密码和密码不一致',                       
    ];
    
   
}