<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/15
 * Time: 11:18
 */

namespace app\api\controller\v1;

use app\api\model\GlGoods;
use app\api\model\GlGoodsSku;
use app\api\service\JsSdk\JsSdk;
use Naixiaoxin\ThinkWechat\Facade;
use think\Controller;
use think\facade\Cache;

class Test extends Controller
{
    /**
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 手动添加sku
     */
    public function test1()
    {
        $goods_array = GlGoods::where(['is_del' => 0])
            ->field('goods_id,market_price,shop_price,attribute')
            ->select()
            ->toArray();
        $count = 0;
        foreach ($goods_array as $index => $goods_info) {
            $attribute = $goods_info['attribute'];
            $attribute_array = [];
            foreach ($attribute as $k => $v) {
                array_push($attribute_array, $v['attribute_value']);
            }

            $sku_array_ = $this->dikaer($attribute_array);

            $sku_info = GlGoodsSku::where(['goods_id' => $goods_info['goods_id']])
                ->order('sku_id')
                ->select()
                ->toArray();

            $sku_array = [];
            foreach ($sku_info as $k => $v) {
                array_push($sku_array, $v['sku_desc']);
            }

            $sku_desc_array = array_merge(array_diff($sku_array_, $sku_array), array_diff($sku_array, $sku_array_));



            if (count($sku_desc_array) > 0) {
                foreach ($sku_desc_array as $index2 => $value2){
                    $data['goods_id'] = $goods_info['goods_id'];
                    $data['sku_stock'] = 0;
                    $data['sku_shop_price'] = $goods_info['shop_price'];
                    $data['sku_market_price'] = $goods_info['market_price'];
                    $data['goods_id'] = $goods_info['goods_id'];
                    $data['give_integral'] = 0;
                    $data['integral'] = 0;
                    $data['img_url'] = $this->removeImgUrl($sku_info[0]['img_url']);
                    $data['original_img_url'] = $this->removeImgUrl($sku_info[0]['original_img_url']);
                    $data['sku_desc'] = $value2;
                    GlGoodsSku::create($data);
                    $count ++;
                }

            }

        }

        return $count;

    }

    public function test(){

    }

    /**
     * @param $arr
     * @return array|mixed
     * 笛卡乘积
     */
    private function dikaer($arr)
    {
        //$arr1 = array();
        $result = array_shift($arr);
        while ($arr2 = array_shift($arr)) {
            $arr1 = $result;
            $result = array();
            foreach ($arr1 as $v) {
                foreach ($arr2 as $v2) {
                    $result[] = $v . ',' . $v2;
                }
            }
        }
        return $result;
    }

    /**
     * @param $file
     * @return string
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