<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/17
 * Time: 9:04
 */

namespace app\api\controller\v1\cms;


use app\api\model\GlGoods;
use app\api\model\GlIndexAd;
use app\api\service\UserAuthority;
use app\api\validate\CurrencyValidate;
use think\facade\Cache;

class CmsIndexAd
{
    public function giveIndexAdList()
    {

        //验证必要
        (new CurrencyValidate())->myGoCheck(['into_type', 'page', 'limit'], 'require');
        UserAuthority::checkAuthority(8);
        $where['into_type'] = request()->param('into_type');
        $data['page'] = request()->param('page');
        $data['limit'] = request()->param('limit');
        $result['list'] = GlIndexAd::where($where)
            ->page($data['page'], $data['limit'])
            ->order(['position_type', 'sort_order' => 'desc'])
            ->select();
        $result['count'] = GlIndexAd::where($where)->count();

        return $result;

    }

    public function giveAllIndexAdList()
    {

        //验证必要
        (new CurrencyValidate())->myGoCheck(['into_type'], 'require');
        UserAuthority::checkAuthority(8);
        $where['into_type'] = request()->param('into_type');

        $result = GlIndexAd::where($where)->order(['position_type', 'sort_order' => 'desc'])
            ->select();

        return $result;

    }

    /**
     * @return bool
     * @throws \app\lib\exception\CommonException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 添加广告
     */
    public function addIndexAd()
    {
        //验证必要
        (new CurrencyValidate())->myGoCheck(['into_type', 'position_type', 'ad_type', 'sort_order'], 'require');
        (new CurrencyValidate())->myGoCheck(['sort_order'], 'positiveInt');
        $data['into_type'] = request()->param('into_type');
        $data['position_type'] = request()->param('position_type');
        $data['ad_type'] = request()->param('ad_type');
        $data['sort_order'] = request()->param('sort_order');
        $data['ad_img'] = removeImgUrl(request()->param('ad_img'));
        $data['position_type_name'] = request()->param('position_type_name');
        $data['text'] = request()->param('text');
        $data['father_position_name'] = request()->param('father_position_name');
        $data['position_type2'] = request()->param('position_type2');
        $data['goods_name'] = request()->param('goods_name');
        $data['goods_price'] = request()->param('goods_price');
        $data['origin_goods_price'] = request()->param('origin_goods_price');
        $data['url'] = request()->param('url');

        //第二次验证
        if ($data['ad_type'] === '商品ID') {
            (new CurrencyValidate())->myGoCheck(['goods_id'], 'require');
            (new CurrencyValidate())->myGoCheck(['goods_id'], 'positiveInt');
            $data['goods_id'] = request()->param('goods_id');
            /*去查询出商品名称和商品价格*/
            $goods_info = GlGoods::where([
                ['goods_id', '=', $data['goods_id']]
            ])
                ->find();
            $data['goods_name'] = $goods_info['goods_name'];
            $data['goods_price'] = $goods_info['shop_price'];
            $data['origin_goods_price'] = $goods_info['market_price'];
        }
        if ($data['ad_type'] === '分类ID') {
            (new CurrencyValidate())->myGoCheck(['cat_id'], 'require');
            (new CurrencyValidate())->myGoCheck(['cat_id'], 'positiveInt');
            $data['cat_id'] = request()->param('cat_id');
        }

        GlIndexAd::create($data);

        return true;
    }

    /**
     * @return bool
     * @throws \app\lib\exception\CommonException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * 编辑广告
     */
    public function updIndexAd()
    {

        //验证必要
        (new CurrencyValidate())->myGoCheck(['into_type', 'position_type', 'ad_type', 'sort_order', 'id'], 'require');
        (new CurrencyValidate())->myGoCheck(['sort_order', 'id'], 'positiveInt');
        UserAuthority::checkAuthority(8);

        $where['id'] = request()->param('id');
        $data['into_type'] = request()->param('into_type');
        $data['position_type'] = request()->param('position_type');
        $data['ad_type'] = request()->param('ad_type');
        $data['sort_order'] = request()->param('sort_order');
        $data['ad_img'] = removeImgUrl(request()->param('ad_img'));
        $data['position_type_name'] = request()->param('position_type_name');
        $data['father_position_name'] = request()->param('father_position_name');
        $data['text'] = request()->param('text');
        $data['position_type2'] = request()->param('position_type2');
        $data['goods_name'] = request()->param('goods_name');
        $data['goods_price'] = request()->param('goods_price');
        $data['origin_goods_price'] = request()->param('origin_goods_price');
        $data['url'] = request()->param('url');
        //第二次验证
        if ($data['ad_type'] === '商品ID') {
            (new CurrencyValidate())->myGoCheck(['goods_id'], 'require');
            (new CurrencyValidate())->myGoCheck(['goods_id'], 'positiveInt');
            $data['goods_id'] = request()->param('goods_id');
            /*去查询出商品名称和商品价格*/
            $goods_info = GlGoods::where([
                ['goods_id', '=', $data['goods_id']]
            ])
                ->find();
            $data['goods_name'] = $goods_info['goods_name'];
            $data['goods_price'] = $goods_info['shop_price'];
            $data['origin_goods_price'] = $goods_info['market_price'];
        }
        if ($data['ad_type'] === '分类ID') {
            (new CurrencyValidate())->myGoCheck(['cat_id'], 'require');
            (new CurrencyValidate())->myGoCheck(['cat_id'], 'positiveInt');
            $data['cat_id'] = request()->param('cat_id');
        }

        //更新
        GlIndexAd::where($where)->update($data);

        return true;


    }

    /**
     * @return bool
     * @throws \app\lib\exception\CommonException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * 删除广告
     */
    public function delIndexAd()
    {
        //验证必要
        (new CurrencyValidate())->myGoCheck(['id'], 'require');
        (new CurrencyValidate())->myGoCheck(['id'], 'positiveInt');
        UserAuthority::checkAuthority(8);

        $data['id'] = request()->param('id');

        GlIndexAd::where($data)->delete();

        return true;
    }

    /**
     * @param $file
     * @return mixed
     * 去除图片中的url
     */
    private function removeImgUrl($file)
    {

        if (strpos($file, config('my_config.img_url')) >= 0) {

            return str_replace(config('my_config.img_url'), '', $file);

        } else {
            return $file;
        }

    }
}
