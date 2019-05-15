<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/15
 * Time: 18:51
 */

namespace app\api\controller\v1\cms;


use app\api\model\GlGoods;
use app\api\service\UserAuthority;
use app\api\validate\CurrencyValidate;

class CmsGoods
{
    /**
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \app\lib\exception\CommonException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 分页获取商品列表
     */
    public function giveGoodsListByPage()
    {

        //验证必要
        (new CurrencyValidate())->myGoCheck(['page', 'limit'], 'require');
        //验证正整数
        (new CurrencyValidate())->myGoCheck(['page', 'limit'], 'positiveInt');
        UserAuthority::checkAuthority(8);
        $data["page"] = request()->param("page");
        $data["limit"] = request()->param("limit");

        $result = GlGoods::where(['is_del' => 0])
            ->page($data["page"], $data["limit"])
            ->select();

        return $result;

    }
}