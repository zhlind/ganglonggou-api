<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/21
 * Time: 13:32
 */

namespace app\api\controller\v1\common;


use app\api\model\GlGoods;
use app\api\model\GlGoodsGallery;
use app\api\model\GlGoodsSku;
use app\api\service\SerCoupon;
use app\api\validate\CurrencyValidate;

class Goods
{
    /**
     * @return mixed
     * @throws \app\lib\exception\CommonException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 获取额外商品信息
     */
    public function giveExtraGoodsInfo()
    {

        //验证必要
        (new CurrencyValidate())->myGoCheck(['goods_id','into_type'], 'require');
        //验证正整数
        (new CurrencyValidate())->myGoCheck(['goods_id'], 'positiveInt');

        $data['goods_id'] = request()->param('goods_id');
        $into_type= request()->param('into_type');

        $result['goods_gallery'] = GlGoodsGallery::where($data)
            ->select();

        $result['goods_sku'] = GlGoodsSku::where($data)
            ->select();

        $result['coupon_list'] = (new SerCoupon())->giveUsableCouponByGoodsIdAndIntoType($data['goods_id'],$into_type);

        //增加点击量
        GlGoods::where($data)->setInc('click_count');

        return $result;

    }
}