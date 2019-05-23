<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/23
 * Time: 9:24
 */

namespace app\api\controller\v1\common;


use app\api\service\Login\TestLogin;
use app\api\validate\CurrencyValidate;

class Login
{
    /**
     * @return string
     * @throws \app\lib\exception\CommonException
     * 测试登录
     */
    public function testLogin(){
        //验证必要
        (new CurrencyValidate())->myGoCheck(['test_app_appid', 'id'], 'require');

        return (new TestLogin())->giveToken();

    }
}