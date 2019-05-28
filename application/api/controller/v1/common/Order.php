<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/28
 * Time: 9:44
 */

namespace app\api\controller\v1\common;


use app\api\service\Order\SerOrder;
use app\api\validate\CurrencyValidate;

class Order
{
    public function submitOrder(){

        /*检查提交选项*/
        //验证必要
        (new CurrencyValidate())->myGoCheck(['user_token', 'coupon_id','goods_list','invoice_info','pay_id','bystages_id','use_integral_number'], 'require');
        //验证正整数
        (new CurrencyValidate())->myGoCheck(['pay_id', 'bystages_id'], 'positiveInt');

        return (new SerOrder())->createOrder();

    }
}