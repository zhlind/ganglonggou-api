<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/27
 * Time: 12:46
 */

namespace app\api\controller\v1\common;


use app\api\model\GlUser;
use app\api\service\Login\BaseLogin;
use app\api\validate\CurrencyValidate;
use app\lib\exception\CommonException;

class User
{
    /**
     * @return array|\PDOStatement|string|\think\Model|null
     * @throws CommonException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 获取用户信息
     */
    public function giveUserInfoByUserToken()
    {

        //验证必要
        (new CurrencyValidate())->myGoCheck(['user_token'], 'require');

        //获取用户信息
        $user_token = request()->param("user_token");
        $user_desc = BaseLogin::getCurrentIdentity(['user_id','into_type','son_into_type'],$user_token);
        $user_id = $user_desc['user_id'];

        $result = GlUser::where([['user_id','=',$user_id]
        ,['is_del','=',0]])
            ->field('user_id,user_name,user_img,add_time,name,email,phone,integral')
            ->find();

        if(!$result){
            throw  new CommonException(['msg' => '无效用户']);
        }

        return $result;
    }
}