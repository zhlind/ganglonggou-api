<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;


//读取配置文件，定义配置常量
$json_file = dirname(dirname(__DIR__)) . '/config/ganglonggou.json';
$json_str = file_get_contents($json_file);
$json_array = json_decode($json_str, true);
define('_GL_CONFIG_', $json_array);

//尝试绑定域名请求，但是HTTP_ORIGIN是可以伪造的
/*$url = $_SERVER['HTTP_ORIGIN'];
$AccessControlAllowOrigin = _GL_CONFIG_['Access-Control-Allow-Origin'] === '*' ? [] : _GL_CONFIG_['Access-Control-Allow-Origin'];
if ($url === null || count($AccessControlAllowOrigin) === 0 || in_array($url,$AccessControlAllowOrigin)) {
    //允许跨域请求
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Token, X_Requested_With");
    header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
}*/

//允许跨域请求
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Token, X_Requested_With");
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');


// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

require '../vendor/autoload.php';

// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->run()->send();
