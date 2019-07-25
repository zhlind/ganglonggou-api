<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/15
 * Time: 13:12
 */


$json_file = dirname(dirname(__DIR__)) . '/config/ganglonggou.json';
$json_str = file_get_contents($json_file);
$json_array = json_decode($json_str, true);

return [
    //debug
    'debug' => $json_array['debug'],
    //图片存放地址
    'img_file' => dirname(\think\facade\Env::get('root_path')) . '/images/',
    //图片服务器Url
    'img_url' => $json_array['img_url'],
    //apiUrl
    'api_url' => $json_array['api_url'],
    //日志文件
    'log_file' => dirname(\think\facade\Env::get('root_path')) . '/runtime/log/',
    //缓存文件
    'cache_file' => dirname(\think\facade\Env::get('root_path')) . '/runtime/cache/',
    //token盐巴
    'token_salt' => $json_array['token_salt'],
    //Token到期时间s
    'token_expire_in' => 70000,
    //Token到期时间(七天)s
    'token_expire_in_7day' => 604800000,
    //wx各种缓存到期时间s
    'wx_expire_in' => 6000,
    //wxAppId
    'wx_app_id' => $json_array['wx_app_id'],
    //WxSecret
    'wx_secret' => $json_array['wx_secret'],
    //订单支付超时时间s
    'invalid_pay_time' => 43200,
    //签收超时时间s
    'invalid_sign_goods_time' => 604800,
    //子入口对应名称
    'son_into_type_name' => array(
        'abc_wx' => '农行微信端',
        'abc_app' => '农行app端',
        'wx' => 'wx端',
        'pc' => 'pc端',
        'mobile' =>'wap端'
    ),
    //订单状态对应名称0已取消，1未支付，2已支付未发货，3已支付已发货，4已支付已收货，5已评价，6申请售后，7售后失败，8售后成功
    'order_state_name' => array(
        0 => '已取消',
        1 => '未支付',
        2 => '等待商家发货',
        3 => '待签收',
        4 => '交易成功',
        5 => '已评价',
        6 => '申请售后中',
        7 => '售后失败',
        8 => '售后成功',
    ),
    'logistics_code_name' => array(
        'shunfeng' => '顺丰速递',
    ),
    /*sql查询默认缓存时间，24小时s*/
    'sql_sel_cache_time' => 86400,
    /*农行Id*/
    'lbAppId' => $json_array['lbAppId'],
    'bdAppId' => $json_array['bdAppId'],
    'lbAppKey' => $json_array['lbAppKey'],
    'bdAppKey' => $json_array['bdAppKey'],
];