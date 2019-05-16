<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/15
 * Time: 18:51
 */

namespace app\api\controller\v1\cms;


use app\api\model\GlGoods;
use app\api\model\GlGoodsGallery;
use app\api\model\GlGoodsSku;
use app\api\service\UserAuthority;
use app\api\validate\CurrencyValidate;
use app\lib\exception\CommonException;
use think\Db;

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
        $data['page'] = request()->param('page');
        $data['limit'] = request()->param('limit');
        $where['is_del'] = 0;
        $where['goods_name'] = request()->param('goods_name') !== '' ? request()->param('goods_name') : array('exp', Db::raw('is not null'));
        $where['cat_id'] = request()->param('cat_id') !== '' ? request()->param('cat_id') : array('exp', Db::raw('is not null'));

        $result['list'] = GlGoods::where($where)
            ->page($data['page'], $data['limit'])
            ->select()
            ->toArray();

        $result['count'] = GlGoods::where($where)
            ->count();

        return $result;
    }

    /**
     * @return bool
     * @throws CommonException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * 添加商品
     */
    public function addGoods()
    {

        //验证必要
        (new CurrencyValidate())->myGoCheck(['cat_id', 'goods_name', 'promote_number', 'promote_start_date'
            , 'promote_end_date', 'goods_img', 'original_img', 'is_on_sale'
            , 'is_best', 'is_new', 'is_hot', 'is_promote', 'goods_sales_volume', 'evaluate_count', 'attribute', 'goods_gallery', 'goods_sku_array'], 'require');
        //验证正整数
        (new CurrencyValidate())->myGoCheck(['cat_id'], 'positiveInt');

        UserAuthority::checkAuthority(8);

        $data['cat_id'] = request()->param('cat_id');
        $data['goods_name'] = request()->param('goods_name');
        $data['click_count'] = 0;
        $data['promote_number'] = request()->param('promote_number');
        $data['promote_start_date'] = request()->param('promote_start_date');
        $data['promote_end_date'] = request()->param('promote_end_date');
        $data['keywords'] = request()->param('keywords');
        $data['goods_brief'] = request()->param('goods_brief');
        $data['goods_desc'] = $this->removeImgUrl(request()->param('goods_desc'));
        $data['goods_img'] = $this->removeImgUrl(request()->param('goods_img'));
        $data['original_img'] = $this->removeImgUrl(request()->param('original_img'));
        $data['is_on_sale'] = request()->param('is_on_sale');
        $data['add_time'] = time();
        $data['sort_order'] = 99;
        $data['is_del'] = 0;
        $data['is_best'] = request()->param('is_best');
        $data['is_new'] = request()->param('is_new');
        $data['is_hot'] = request()->param('is_hot');
        $data['is_promote'] = request()->param('is_promote');
        $data['upd_time'] = time();
        $data['seller_note'] = '';
        $data['goods_sales_volume'] = request()->param('goods_sales_volume');
        $data['evaluate_count'] = request()->param('evaluate_count');
        $data['attribute'] = json_encode(request()->param('attribute/a'));

        $goods_gallery_array = request()->param('goods_gallery/a');
        $goods_sku_array = request()->param('goods_sku_array/a');

        $data['goods_stock'] = $this->countGoodsStock($goods_sku_array);
        $data['market_price'] = $this->mpMarketPrice($goods_sku_array);
        $data['shop_price'] = $this->mpShopPrice($goods_sku_array);

        //插入商品
        $goods_info = GlGoods::create($data);

        if ($goods_info) {
            //生成商品相册
            foreach ($goods_gallery_array as $k => $v) {
                $data_goods_gallery['goods_id'] = $goods_info->id;
                $data_goods_gallery['img_url'] = $this->removeImgUrl($v['url']);
                $data_goods_gallery['img_original'] = $this->removeImgUrl($v['original_url']);
                GlGoodsGallery::create($data_goods_gallery);
            }
            //生成sku
            foreach ($goods_sku_array as $k => $v) {
                $data_goods_sku['goods_id'] = $goods_info->id;
                $data_goods_sku['sku_desc'] = $v['sku_desc'];
                $data_goods_sku['sku_stock'] = $v['sku_stock'];
                $data_goods_sku['sku_shop_price'] = $v['sku_shop_price'];
                $data_goods_sku['sku_market_price'] = $v['sku_market_price'];
                $data_goods_sku['give_integral'] = $v['give_integral'];
                $data_goods_sku['integral'] = $v['integral'];
                $data_goods_sku['img_url'] = $this->removeImgUrl($v['img_url']);
                $data_goods_sku['original_img_url'] = $this->removeImgUrl($v['original_img_url']);
                GlGoodsSku::create($data_goods_sku);
            }
            //生成货号
            $this->addGoodsSn($goods_info->id);

        } else {
            throw new CommonException(['msg' => '保存失败']);
        }

        return true;

    }

    /**
     * @param $file
     * @return string
     * 去除图片中的url
     */
    private function removeImgUrl($file)
    {

        if (strpos($file, config('my_config.img_url'))) {

            return str_replace(config('my_config.img_url'), '', $file);

        } else {
            return $file;
        }

    }


    /**
     * @param $goods_sku_array
     * @return int
     * 计算商品库存
     */
    private function countGoodsStock($goods_sku_array)
    {
        $goods_stock = 0;
        foreach ($goods_sku_array as $k => $v) {
            $goods_stock += $v['sku_stock'];
        }
        return $goods_stock;
    }

    /**
     * @param $arr
     * @return mixed
     * 冒泡排序market_price
     */
    private function mpMarketPrice($arr)
    {
        for ($i = 0; $i < count($arr); $i++) {
            for ($j = $i; $j < count($arr); $j++) {
                if ($arr[$i]['sku_market_price'] + 0 < $arr[$j]['sku_market_price'] + 0) {
                    $temp = $arr[$i];
                    $arr[$i] = $arr[$j];
                    $arr[$j] = $temp;
                }
            }
        }
        return $arr[0]['sku_market_price'];
    }

    /**
     * @param $arr
     * @return mixed
     * 冒泡排序shop_price
     */
    private function mpShopPrice($arr)
    {
        for ($i = 0; $i < count($arr); $i++) {
            for ($j = $i; $j < count($arr); $j++) {
                if ($arr[$i]['sku_shop_price'] + 0 < $arr[$j]['sku_shop_price'] + 0) {
                    $temp = $arr[$i];
                    $arr[$i] = $arr[$j];
                    $arr[$j] = $temp;
                }
            }
        }
        return $arr[0]['sku_shop_price'];
    }

    /**
     * @param $goods_id
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * 生产货号
     */
    private function addGoodsSn($goods_id)
    {

        GlGoods::where(['goods_id' => $goods_id])
            ->update(['goods_sn' => 'GSN000' . $goods_id]);

    }
}