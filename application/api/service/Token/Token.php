<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/15
 * Time: 15:10
 */

namespace app\api\service\Token;


use app\lib\exception\CommonException;
use think\Cache;

class Token
{
    /**
     * @return string
     * 生成令牌
     */
    public static function generateToken()
    {
        $randChar = getRandChar(32);
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];//得到请求开始时的时间戳
        $tokenSalt = config('my_config.token_salt');
        return md5($randChar . $timestamp . $tokenSalt);
    }

    /**
     * @param $result
     * @return string
     * @throws CommonException
     * 写入缓存
     */
    public static function saveToCache($result)
    {
        $key = self::generateToken();
        $value = json_encode($result);
        $expire_in = config('my_config.token_expire_in');
        $result = cache($key, $value, $expire_in);
        if (!$result) {
            throw new CommonException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }

    /**
     * @param $result
     * @return string
     * @throws CommonException
     * 永久写入缓存
     */
    public static function saveToCache7Day($result)
    {
        $key = self::generateToken();
        $value = json_encode($result);
        $expire_in = 0;
        $result = cache($key, $value, $expire_in);
        if (!$result) {
            throw new CommonException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }

    /**
     * @param $keys //获取那种信息
     * @param $token
     * @return array
     * @throws CommonException
     * 从缓存中获取当前用户指定身份标识
     */
    public static function getCurrentIdentity($keys, $token)
    {
        /*设置header头有问题，暂时换个方式
        $token = Request::instance()
            ->header('token');
        */
        $identities = \think\facade\Cache::get($token);
        //cache 助手函数有bug
        // $identities = cache($token);

        if (!$identities) {
            throw new CommonException(['msg'=>'获取用户信息失败','code'=>'500']);
        } else {
            if (!is_array($identities)) {
                $identities = json_decode($identities, true);
            }
            $result = [];
            foreach ($keys as $key) {
                if (array_key_exists($key, $identities)) {
                    $result[$key] = $identities[$key];
                }
            }
            return $result;
        }
    }
}