<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/15
 * Time: 18:49
 */

namespace app\api\model;


use app\lib\exception\CommonException;

class GlGoods extends BaseModel
{

    static private $screenGoodsInfo = 'goods_id,cat_id,goods_sn,goods_name,goods_head_name,
            market_price,shop_price,keywords,goods_brief,goods_desc,
            goods_img,original_img,sort_order,goods_sales_volume,evaluate_count,attribute,is_promote';//对外筛选后的商品信息

    public function getOriginalImgAttr($value, $data)
    {
        return $this->spellOriginalImg($value, $data);
    }

    public function getGoodsImgAttr($value, $data)
    {
        return $this->spellOriginalImg($value, $data);
    }

    public function getGoodsDescAttr($value, $data)
    {
        return $this->imgTagSpellOriginalImg($value, $data);
    }

    public function getAttributeAttr($value, $data)
    {

        return json_decode($value, true);
    }

    /**
     * @param $parent_id
     * @return array
     * @throws CommonException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 通过parent_id返回商品列表
     */
    public static function giveGoodsListByParentId($parent_id)
    {

        $cat_id_array_ = GlCategory::where(['parent_id' => $parent_id])
            ->select()
            ->toArray();

        $cat_id_array = [];
        if (count($cat_id_array_) > 0) {
            foreach ($cat_id_array_ as $k => $v) {
                array_push($cat_id_array, $v['cat_id']);
            }
        } else {
            throw new CommonException(['msg' => '无效的顶级分类']);
        }

        $where = [];
        array_push($where, ['cat_id', 'in', $cat_id_array]);
        array_push($where, ['is_on_sale', '=', 1]);
        array_push($where, ['is_del', '=', 0]);

        $result = self::where($where)
            ->field(self::$screenGoodsInfo)
            ->select()
            ->toArray();

        return $result;
    }

    /**
     * @param $goods_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 返回筛选过后商品信息
     */
    public static function giveGoodsInfo($goods_id){

        $where['goods_id'] = $goods_id;
        $where['is_on_sale'] = 1;
        $where['is_del'] = 0;

        $result = GlGoods::where($where)->field(self::$screenGoodsInfo)
            ->find()
            ->toArray();

        return $result;
    }
}