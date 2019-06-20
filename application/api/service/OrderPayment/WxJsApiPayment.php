<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/6/20
 * Time: 16:57
 */

namespace app\api\service\OrderPayment;


use app\api\model\GlUser;
use app\lib\exception\CommonException;
use think\facade\Log;

class WxJsApiPayment
{
    private $file;
    private $paymentUrl;//生成的支付html
    private $midOrderInfo;
    private $backUrl;
    private $successUrl;
    private $orderInfo;
    private $userInfo;
    private $unifiedOrder;
    private $config;
    private $input;
    private $refund;
    private $jsApiParameters;

    public function __construct()
    {
        $this->file = dirname(\think\facade\Env::get('root_path')) . '/extend/WxJSAPIPay/';

        require($this->file . 'WxPay.php');
        require($this->file . 'WxPayConfig.php');

        $this->unifiedOrder = new \WxPayUnifiedOrder();
        $this->config = new \WxPayConfig();
        $this->input = new \WxPayOrderQuery();
        $this->refund = new \WxPayRefund();
    }

    public function startPayment($order_info, $mid_order_info, $success_url, $back_url)
    {
        $this->orderInfo = $order_info;
        $this->midOrderInfo = $mid_order_info;
        $this->successUrl = $success_url;
        $this->backUrl = $back_url;

        /*获取用户微信openId*/
        $this->userInfo = GlUser::where([
            ['user_id', '=', $this->orderInfo['user_id']]
        ])
            ->find();

        $this->unifiedOrder->SetBody("江苏岗隆数码-商品购买");
        $this->unifiedOrder->SetAttach("江苏岗隆数码科技有限公司");
        $this->unifiedOrder->SetOut_trade_no($this->orderInfo['order_sn']);
        $this->unifiedOrder->SetTotal_fee($this->orderInfo['oder_price'] * 100);
        $this->unifiedOrder->SetTime_start(date("YmdHis"));
        $this->unifiedOrder->SetTime_expire(date("YmdHis", time() + 600));
        $this->unifiedOrder->SetTrade_type("JSAPI");
        $this->unifiedOrder->SetOpenid($this->userInfo['wx_openid']);

        $data = \WxPayApi::unifiedOrder($this->config, $this->unifiedOrder);

        if ($data['return_code'] != "SUCCESS" || $data['result_code'] != "SUCCESS") {
            Log::write($data, 'error');
            Log::write("获取预支付订单信息失败", 'error');
            throw new CommonException(['msg' => '获取预支付订单信息失败']);
        }

        $this->jsApiParameters = $this->getJsApiParameters($data);
    }

    /**
     * @param $UnifiedOrderResult
     * @return false|string
     * @throws CommonException
     * 获取jsapi支付的参数
     */
    private function getJsApiParameters($UnifiedOrderResult)
    {
        if (!array_key_exists("appid", $UnifiedOrderResult)
            || !array_key_exists("prepay_id", $UnifiedOrderResult)
            || $UnifiedOrderResult['prepay_id'] == "") {
            throw new CommonException(['msg' => '获取jsapi支付的参数失败']);
        }

        $JsApi = new \WxPayJsApiPay();
        $JsApi->SetAppid($UnifiedOrderResult["appid"]);
        $timeStamp = time();
        $JsApi->SetTimeStamp("$timeStamp");
        $JsApi->SetNonceStr(\WxPayApi::getNonceStr());
        $JsApi->SetPackage("prepay_id=" . $UnifiedOrderResult['prepay_id']);

        $config = $this->config;
        $JsApi->SetPaySign($JsApi->MakeSign($config));
        $parameters = json_encode($JsApi->GetValues());

        return $parameters;
    }
    /**
     * @return \think\response\View
     * 生成支付页面
     */
    private function paymentHtml()
    {

        $goods_list = [];

        foreach ($this->midOrderInfo as $k => $v) {

            array_push($goods_list, ['goods_name' => $v['goods_name']]);

        }

        $order_info = [
            'order_price' => strval($this->orderInfo['order_price']),
            'goods_list' => $goods_list,
            'bank_url' => $this->backUrl,
            'success_url' => $this->successUrl,
            'payment_url' => $this->paymentUrl,
            'js_api_parameters' => $this->jsApiParameters,
        ];

        return view('/wxPayment')->assign('order_info', $order_info);

    }
}
