<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/7/1
 * Time: 10:56
 */

namespace app\api\controller\v1\common;


use app\api\model\GlUser;
use app\api\service\Login\MobileLogin;
use app\api\validate\CurrencyValidate;
use app\lib\exception\CommonException;

class Register
{
    /**
     * @return bool
     * @throws CommonException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 手机端用户注册验证
     */
    public function checkMobileRegister()
    {
        //验证必要
        (new CurrencyValidate())->myGoCheck(['phone', 'password', 'again_password'], 'require');
        $phone = request()->param('phone');
        $password = request()->param('password');
        $again_password = request()->param('again_password');
        //验证手机号
        if (!preg_match('/^1([0-9]{9})/', $phone)) {
            throw new CommonException(['msg' => '你输入的手机号不正确']);
        };
        if (GlUser::where([['phone', '=', $phone]])->find()) {
            throw new CommonException(['msg' => '该手机号已被注册']);
        };
        if (mb_strlen($password) < 5 || mb_strlen($password) > 15) {
            throw new CommonException(['msg' => '你输入的密码长度不符合规范']);
        }
        if ($password !== $again_password) {
            throw new CommonException(['msg' => '两次密码输入不一致']);
        }

        return true;
    }

    /**
     * @return bool
     * @throws CommonException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 手机端用户注册
     */
    public function mobileRegister()
    {
        //验证必要
        (new CurrencyValidate())->myGoCheck(['phone', 'password', 'again_password'], 'require');
        $phone = request()->param('phone');
        $password = request()->param('password');
        $again_password = request()->param('again_password');
        //验证手机号
        if (!preg_match('/^1([0-9]{9})/', $phone)) {
            throw new CommonException(['msg' => '你输入的手机号不正确']);
        };
        if (GlUser::where([['phone', '=', $phone]])->find()) {
            throw new CommonException(['msg' => '该手机号已被注册']);
        };
        if (mb_strlen($password) < 5 || mb_strlen($password) > 15) {
            throw new CommonException(['msg' => '你输入的密码长度不符合规范']);
        }
        if ($password !== $again_password) {
            throw new CommonException(['msg' => '两次密码输入不一致']);
        }

        return (new MobileLogin())->mobileRegister($phone,$password);
    }
}