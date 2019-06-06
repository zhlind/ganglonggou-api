<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/6/5
 * Time: 15:10
 */

namespace app\api\controller\v1\notify;


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