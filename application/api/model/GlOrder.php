<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/28
 * Time: 15:55
 */

namespace app\api\model;


class GlOrder extends BaseModel
{

    public static function getOrderInfoByOrderSn($order_sn)
    {
        $result = self::where([['order_sn', '=', $order_sn]])
            ->find();

        return $result;
    }
}