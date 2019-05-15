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
        return \think\facade\Env::get('runtime_path');
    }
}