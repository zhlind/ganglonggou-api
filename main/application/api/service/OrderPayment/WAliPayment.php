<?php
/**
 * Created by PhpStorm.
 * User: zhl
 * Date: 2019-07-23
 * Time: 17:15
 */

namespace app\api\service\OrderPayment;
use think\Exception;
use think\facade\Log;
use think\facade\Request;
use think\response\Json;
class WAliPayment
{
    private $file;
    private $paymentHtmlInfo;//生成的支付html信息
    private $midOrderInfo;
    private $backUrl;
    private $successUrl;
    private $notifyUrl;
    private $orderInfo;
    public function __construct()
    {
        $this->file = dirname(\think\facade\Env::get('root_path')) . '/extend/WAliPay/';
        $this->notifyUrl = config('my_config.api_url') . 'api/v1/notify/ali_pay_notify';
    }
    /**
     * @param $order_info
     * @param $mid_order_info
     * @param $success_url
     * @param $back_url
     * @return \think\response\View
     * @throws \Exception
     * 发起支付
     */
    public function startPayment($order_info, $mid_order_info, $success_url, $back_url)
    {
        $config = [];
        require_once($this->file . 'config.php');
        require_once($this->file . 'wappay/service/AlipayTradeService.php');
        require_once($this->file . 'wappay/buildermodel/AlipayTradeWapPayContentBuilder.php');
        $this->orderInfo = $order_info;
        $this->midOrderInfo = $mid_order_info;
        $this->successUrl = $success_url;
        $this->backUrl = $back_url;
        /*生成订单名称*/
        $goods_name_array = [];
        foreach ($this->midOrderInfo as $k => $v) {
            array_push($goods_name_array, $v['goods_name']);
        }
        $goods_name_str = implode(',', $goods_name_array);
        $goods_name_str = substr($goods_name_str, 0, 250);
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $this->orderInfo['order_sn'];
        //订单名称，必填
        $subject = trim($goods_name_str);
        //付款金额，必填
        $total_amount = $this->orderInfo['order_price'];
        //商品描述，可空
        $body = trim('江苏岗隆数码-商品购买');
        //构造参数AlipayTradeWapPayContentBuilder
        $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $aop = new \AlipayTradeService($config);
        /*生成支付html*/
        $this->paymentHtmlInfo = $aop->wapPay($payRequestBuilder, $this->successUrl, $this->notifyUrl);

        return $this->paymentHtml();
    }
    /**
     * @return \think\response\View
     * 生成支付HTML
     */
    private function paymentHtml()
    {
        return view('/WAliPayment')->assign('html_info', $this->paymentHtmlInfo);

    }


}