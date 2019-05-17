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
    'wx_app_id' =>_GL_CONFIG_['wx_app_id'],
    //WxSecret
     'wx_secret' =>_GL_CONFIG_['wx_secret']
];