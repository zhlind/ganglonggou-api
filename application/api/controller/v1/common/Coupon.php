<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/23
 * Time: 14:23
 */

namespace app\api\controller\v1\common;


use app\api\service\Login\BaseLogin;
use app\api\service\SerCoupon;
use app\api\validate\CurrencyValidate;

class Coupon
{
    /**
     * @return string
     * @throws \app\lib\exception\CommonException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 用户领取优惠券
     */
    public function userGetCoupon(){
        //验证必要
        (new CurrencyValidate())->myGoCheck(['coupon_id', 'user_token'], 'require');
        //验证正整数
        (new CurrencyValidate())->myGoCheck(['coupon_id'], 'positiveInt');

        $coupon_id = request()->param("coupon_id");
        $user_token = request()->param("user_token");
        //获取用户信息
        $user_desc = BaseLogin::getCurrentIdentity(['user_id','into_type','son_into_type'],$user_token);
        $user_id = $user_desc['user_id'];
        $into_type = $user_desc['into_type'];

        return (new SerCoupon())->userGetCouponByUserIdAndCouponIdAndLoginType($coupon_id,$user_id,$into_type);
    }
}