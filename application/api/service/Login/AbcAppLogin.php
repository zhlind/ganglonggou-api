<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/6/11
 * Time: 12:42
 */

namespace app\api\service\Login;


use app\api\model\GlUser;
use app\lib\exception\CommonException;

class AbcAppLogin extends BaseLogin
{
    private $abcAppId;
    private $id;
    private $intoType;
    private $sonIntoType;
    private $abcAppOpenid;
    private $userInfo;

    public function __construct()
    {
        $this->abcAppId = request()->param('abc_app_appid');
        $this->id = request()->param('id');
        $this->intoType = 'abc';
        $this->sonIntoType = 'abc_app';
    }

    /**
     * @return string
     * @throws CommonException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * 返回token
     */
    public function giveToken()
    {

        $token = $this->getTokenByOpenId();

        return $token;
    }


    /**
     * @throws CommonException
     * 获取openId
     */
    private function getOpenId()
    {
        $lbAppId = config('my_config.lbAppId');
        $bdAppId = config('my_config.bdAppId');
        $lbAppKey = config('my_config.bAppKey');
        $bdAppKey = config('my_config.bdAppKey');

        if ($this->abcAppId === $lbAppId) {
            $key = $lbAppKey;
        } elseif ($this->abcAppId === $bdAppId) {
            $key = $bdAppKey;
        } else {
            throw new CommonException(
                [
                    'msg' => "无有效appid",
                    'errorCode' => "10002"
                ]
            );
        }
        //发送请求获取openid
        $post_result = send_post("http://192.168.0.132:8080/ECBDecodeEasy", ["id" => $this->id, "appkey" => $key]);

        if ($post_result["Msg"] !== "success" || $post_result["Code"] !== "10200") {
            throw new CommonException(
                [
                    'msg' => "获取openid失败",
                    'errorCode' => "10002"
                ]
            );
        }

        $this->abcAppOpenid = $post_result['Data'];

    }

    /**
     * @return string
     * @throws CommonException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * 保存用户信息，返回token
     */
    private function getTokenByOpenId()
    {

        $this->getOpenId();

        $this->userInfo = GlUser::where(['abc_app_openid' => $this->abcAppOpenid])->find();

        if (!$this->userInfo) {
            //表示新用户
            $data = [
                'user_name' => "abc_app" . time(),
                'user_password' => md5("ganglong8888"),
                'login_ip' => request()->ip(),
                'user_img' => "head_portrait.png",
                'add_time' => time(),
                'login_time' => time(),
                'integral' => 0,
                'is_del' => 0,
                'login_count' => 1,
                'abc_app_openid' => $this->abcAppOpenid
            ];
            $user_id = GlUser::create($data)->id;
        } else {
            $user_id = $this->userInfo['user_id'];
            //更新用户登录时间
            $data = [
                'login_ip' => request()->ip(),
                'login_time' => time()
            ];
            GlUser::where(['user_id' => $user_id])
                ->update($data);
            GlUser::where(['user_id' => $user_id])
                ->setInc('login_count');
        }

        $result['user_id'] = $user_id;
        $result['into_type'] = $this->intoType;
        $result['son_into_type'] = $this->sonIntoType;
        $token = self::saveToCache($result);

        return $token;
    }
}