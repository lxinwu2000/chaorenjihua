<?php
//验证器修改密码
namespace app\home\Validate;
use think\Validate;
class Editpaw extends Validate{
    protected $rule = [    
        'password'=>'require|confirm',                   
    ];   
    protected $message = [       
        'password.require'=>'密码不能为空',
        'password.confirm'=>'确认密码和密码不一致',                       
    ];
    
   
}