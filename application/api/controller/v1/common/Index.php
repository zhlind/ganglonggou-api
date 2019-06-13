<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/17
 * Time: 12:55
 */

namespace app\api\controller\v1\common;


use app\api\model\GlCategory;
use app\api\model\GlGoods;
use app\api\model\GlIndexAd;
use app\api\validate\CurrencyValidate;
use app\lib\exception\CommonException;

class Index
{
    /**
     * @return mixed
     * @throws CommonException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 获取首页信息
     */
    public function giveIndexInfo()
    {

        //验证必要
        (new CurrencyValidate())->myGoCheck(['into_type'], 'require');
        $into_type = request()->param('into_type');
        switch ($into_type) {
            case 'abc':
                $parent_id = 154;
                break;
            case 'wx':
                $parent_id = 168;
                break;
            case '3c618mobile':
                $parent_id = 0;
                break;
            case '3c618pc':
                $parent_id = 0;
                break;
            default:
                throw new CommonException(['msg' => '无此入口']);
        }

        $result['ad_list'] = GlIndexAd::giveIndexAdListByIntoType($into_type);

        $result['cat_list'] = GlCategory::giveCatListByParentId($parent_id);

        $result['goods_list'] = GlGoods::giveGoodsListByParentId($parent_id);

        return $result;

    }
}