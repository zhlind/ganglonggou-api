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
use think\File;

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
        $user_desc = BaseLogin::getCurrentIdentity(['user_id', 'into_type', 'son_into_type'], $user_token);
        $user_id = $user_desc['user_id'];

        $result = GlUser::where([['user_id', '=', $user_id]
            , ['is_del', '=', 0]])
            ->field('user_id,user_name,user_img,add_time,name,email,phone,integral')
            ->find();

        if (!$result) {
            throw  new CommonException(['msg' => '无效用户']);
        }

        return $result;
    }


    /**
     * @return mixed
     * @throws CommonException
     * 更换头像
     */
    public function userUpdPortrait()
    {
        return (new \app\api\service\Upload\Upload())->ImgUpload(1153434);
    }

    /**
     * @return bool
     * @throws CommonException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * 修改用户信息
     */
    public function updUserInfoByUserId()
    {

        //验证必要
        (new CurrencyValidate())->myGoCheck(['user_token', 'user_img', 'user_name'], 'require');

        //获取用户信息
        $user_token = request()->param("user_token");
        $user_desc = BaseLogin::getCurrentIdentity(['user_id', 'into_type', 'son_into_type'], $user_token);
        $user_id = $user_desc['user_id'];

        $data['user_name'] = request()->param('user_name');
        $data['user_img'] = removeImgUrl(request()->param('user_img'));
        $data['phone'] = request()->param('phone');
        $data['email'] = request()->param('email');

        if ($data['phone'] && GlUser::where([['phone', '=', $data['phone']]])->find()) {
            throw new CommonException(['msg' => '该手机号已被注册']);
        }

        if ($data['email'] && GlUser::where([['email', '=', $data['email']]])->find()) {
            throw new CommonException(['msg' => '该邮箱已被注册']);
        }


        $upd_number = GlUser::where([
            ['user_id', '=', $user_id],
            ['is_del', '=', 0]
        ])
            ->update($data);

        if ($upd_number < 1) {
            throw new CommonException(['msg' => '修改失败']);
        }

        return true;


    }
}