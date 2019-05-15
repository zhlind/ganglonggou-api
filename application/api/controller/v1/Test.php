<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/15
 * Time: 11:18
 */

namespace app\api\controller\v1;

class Test
{
    public function test(){
        /*//$json_file = \think\facade\Env::get('root_path') + '../config/ganglonggou.json';
        $json_file = dirname(\think\facade\Env::get('root_path')) . '/config/ganglonggou.json';
        $json_array = json_decode(file_get_contents($json_file),true);*/
        echo config('my_config.hostname');
    }
}