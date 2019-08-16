<?php
namespace app\home\Validate;
use think\Validate;

class Login extends Validate{
    protected $rule = [
        'account'=>'require|alphaNum',
        'password'=>'require|length:8,12',
    ];
    
    protected $message = [
        'account.require'=>'账号不能为空',       
        'account.alphaNum'=>'账号必须字母加数字',
        'password.require'=>'密码不能为空',
        'password.length'=>'密码长度必须是8到12位'
    ];
}