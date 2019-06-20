<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/23
 * Time: 9:24
 */

namespace app\api\controller\v1\common;


use app\api\model\GlUser;
use app\api\service\Login\AbcAppLogin;
use app\api\service\Login\AbcWxLogin;
use app\api\service\Login\BaseLogin;
use app\api\service\Login\TestLogin;
use app\api\service\Login\WxLogin;
use app\api\validate\CurrencyValidate;

class Login
{


    /**
     * @return bool
     * @throws \app\lib\exception\CommonException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     *登录统计
     */
    public function loginCount()
    {
        //验证必要
        (new CurrencyValidate())->myGoCheck(['user_token'], 'require');

        //获取用户信息
        $user_token = request()->param("user_token");
        $user_desc = BaseLogin::getCurrentIdentity(['user_id', 'into_type', 'son_into_type'], $user_token);
        $user_id = $user_desc['user_id'];

        GlUser::where([
            ['user_id', '=', $user_id]
        ])
            ->update([
                'login_ip' => request()->ip(),
                'login_time' => time()
            ]);

        GlUser::where([
            ['user_id', '=', $user_id]
        ])
            ->setInc('login_count');

        return true;

    }

    /**
     * @return string
     * @throws \app\lib\exception\CommonException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * 测试登录
     */
    public function testLogin()
    {
        //验证必要
        (new CurrencyValidate())->myGoCheck(['test_app_appid', 'id'], 'require');

        return (new TestLogin())->giveToken();
    }

    /**
     * @return string
     * @throws \app\lib\exception\CommonException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * 农行app登录
     */
    public function abcAppLogin()
    {
        //验证必要
        (new CurrencyValidate())->myGoCheck(['abc_app_appid', 'id'], 'require');

        return (new AbcAppLogin())->giveToken();
    }

    /**
     * @return string
     * @throws \app\lib\exception\CommonException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * 农行wx登录
     */
    public function abcWxLogin()
    {

        //验证必要
        (new CurrencyValidate())->myGoCheck(['abc_wx_openid'], 'require');

        return (new AbcWxLogin())->giveToken();

    }

    /**
     * @return string
     * @throws \app\lib\exception\CommonException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * 微信公众号登录
     */
    public function wxLogin()
    {
        //验证必要
        (new CurrencyValidate())->myGoCheck(['code'], 'require');
        $code = request()->param('code');


        return (new WxLogin($code))->giveToken();


    }
}