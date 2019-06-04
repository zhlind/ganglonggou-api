<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/15
 * Time: 13:12
 */

/*//读取配置文件
$json_file = dirname(\think\facade\Env::get('root_path')) . '/config/ganglonggou.json';
$json_array = json_decode(file_get_contents($json_file), true);*/
return [
    //图片存放地址
    'img_file' => dirname(\think\facade\Env::get('root_path')) . '/images/',
    //图片服务器Url
    'img_url' => _GL_CONFIG_['img_url'],
    //apiUrl
    'api_url' => _GL_CONFIG_['api_url'],
    //日志文件
    'log_file' => dirname(\think\facade\Env::get('root_path')) . '/runtime/log/',
    //缓存文件
    'cache_file' => dirname(\think\facade\Env::get('root_path')) . '/runtime/cache/',
    //token盐巴
    'token_salt' => _GL_CONFIG_['token_salt'],
    //Token到期时间
    'token_expire_in' => 70000,
    //Token到期时间(七天)
    'token_expire_in_7day' => 604800000,
    //wx各种缓存到期时间
    'wx_expire_in' => 6000,
    //wxAppId
    'wx_app_id' => _GL_CONFIG_['wx_app_id'],
    //WxSecret
    'wx_secret' => _GL_CONFIG_['wx_secret'],
    //订单支付超时时间
    'invalid_pay_time' => 43200,
    //子入口对应名称
    'son_into_type_name' => array(
        'abc_wx' => '农行微信端',
        'abc_app' => '农行app端'
    ),
    //订单状态对应名称0已取消，1未支付，2已支付未发货，3已支付已发货，4已支付已收货，5已评价，6申请售后，7售后失败，8售后成功
    'order_state_name' => array(
        0 => '已取消',
        1 => '未支付',
        2 => '等待商家发货',
        3 => '待签收',
        4 => '待评价',
        5 => '已评价',
        6 => '申请售后中',
        7 => '售后失败',
        8 => '售后成功',
    ),
];