<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/28
 * Time: 9:44
 */

namespace app\api\controller\v1\common;


use app\api\model\GlMidOrder;
use app\api\model\GlOrder;
use app\api\model\GlOrderInvoice;
use app\api\service\Login\BaseLogin;
use app\api\service\Order\SerOrder;
use app\api\validate\CurrencyValidate;
use app\lib\exception\CommonException;

class Order
{
    /**
     * @return mixed
     * @throws \app\lib\exception\CommonException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 提交订单
     */
    public function submitOrder()
    {

        /*检查提交选项*/
        //验证必要
        (new CurrencyValidate())->myGoCheck(['user_token', 'coupon_id', 'goods_list', 'invoice_info', 'pay_id', 'bystages_id', 'use_integral_number'], 'require');
        //验证正整数
        (new CurrencyValidate())->myGoCheck(['pay_id', 'bystages_id'], 'positiveInt');

        return (new SerOrder())->createOrder();

    }

    /**
     * @return array|\PDOStatement|string|\think\Model|null
     * @throws CommonException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * 返回单笔订单
     */
    public function giveOrderInfo()
    {

        //验证必要
        (new CurrencyValidate())->myGoCheck(['user_token', 'order_sn'], 'require');

        //获取用户信息
        $user_token = request()->param("user_token");
        $user_desc = BaseLogin::getCurrentIdentity(['user_id', 'into_type', 'son_into_type'], $user_token);
        $user_id = $user_desc['user_id'];
        $order_sn = request()->param('order_sn');

        /*将支付超时订单改为取消*/
        GlOrder::where([
            ['user_id', '=', $user_id],
            ['is_del', '=', 0],
            ['order_state', '=', 1],
            ['invalid_pay_time', '<=', time()]
        ])
            ->update([
                'upd_time' => time(),
                'order_state' => 0,
                'prev_order_state'=>1,
                'order_visible_note' => '超出支付时间，订单自动取消'
            ]);

        /*获取订单信息*/
        $order_info = GlOrder::getScreenOrderInfoByOrderSnAndUserId($order_sn,$user_id);

        if(!$order_info){
            throw new CommonException(['msg'=>'无效订单']);
        }

        /*订单中间表*/
        $order_info['mid_order'] = GlMidOrder::getScreenMidOrderInfoByOrderSn($order_sn);

        /*发票信息*/
        $order_info['invoice'] = GlOrderInvoice::getScreenInvoiceInfoByOrderSn($order_sn);

        return $order_info;


    }
}