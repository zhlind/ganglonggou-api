<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/6/4
 * Time: 15:00
 */

namespace app\api\notify;


use app\api\service\OrderPayment\AbcPayment;

class PayNotify
{
    /**
     * @return \think\response\View
     * 农行支付回调
     */
    public function abcPayNotify(){

        $result = (new AbcPayment()) ->NotifyProcess();

        return $result;
    }
}