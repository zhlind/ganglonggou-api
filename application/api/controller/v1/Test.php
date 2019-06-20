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
use app\api\model\GlIndexAd;
use app\api\model\Test1;
use app\api\model\Test2;
use app\api\service\SerEmail;
use app\lib\exception\CommonException;
use Noodlehaus\Config;
use think\Controller;
use think\Db;
use think\facade\Log;

class Test extends Controller
{
    public function test()
    {

        $data = [
            'user_name' => '11' . time(),
            'user_password' => md5("ganglong8888"),
            'login_ip' => request()->ip(),
            'user_img' => "head_portrait.png",
            'add_time' => time(),
            'login_time' => time(),
            'integral' => 0,
            'is_del' => 0,
            'login_count' => 1,
        ];
        Log::write($data, 'error');
        return true;

    }


    private function sendEmailTest()
    {

        $head = '测试';
        $email_body = '用户支付成功:';
        $v['goods_name'] = 'asdasdsa';
        $v['goods_id'] = 'asdasdsa';
        $v['goods_number'] = 10;
        $v['sku_id'] = 'asdasdsa';
        $v['sku_desc'] = 'asdasdsa';
        $sku_info['sku_stock'] = 10;
        $email_body .= '
            (商品名称:' . $v['goods_name'] .
            ',商品id:' . $v['goods_id'] .
            ',购买数量:' . $v['goods_number'] .
            ',SkuId:' . $v['sku_id'] .
            ',属性详情:' . $v['sku_desc'] .
            ',剩余库存:' . ($sku_info['sku_stock'] - $v['goods_number']) .
            ',库存检测结果:库存充足)';

        $address_array = ['987303897@qq.com', '582870246@qq.com'];

        (new SerEmail())->sendEmail($head, $email_body, $address_array);

        return $email_body;

    }

    protected function iniTest()
    {
        $conf = Config::load('C:\Users\administrator_liwy\Desktop\web\API\ganglonggou-api\main\application\api\controller\v1\test.ini');

        $conf['MerchantKeyStoreType'] = '1';

        return $conf->get('MerchantKeyStoreType');
    }

    private function returnHtmlTest()
    {

        $goods_list['g1'] = GlIndexAd::where([
            'into_type' => '3c618pc',
            'position_type' => '券享最惠，品悦好物-商品',
        ])
            ->order(['position_type', 'sort_order' => 'desc'])
            ->select();
        $goods_list['g2'] = GlIndexAd::where([
            'into_type' => '3c618pc',
            'position_type' => '手机数码专区',
            'position_type2' => '内容',
        ])
            ->order(['position_type', 'sort_order' => 'desc'])
            ->select();
        $goods_list['g3'] = GlIndexAd::where([
            'into_type' => '3c618pc',
            'position_type' => '电脑办公专区',
            'position_type2' => '内容',
        ])
            ->order(['position_type', 'sort_order' => 'desc'])
            ->select();

        $test = view('/3cPc')->assign('goods_list', $goods_list);

        return $test;

    }

    /**
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * 手动删除重复的sku
     */
    private function delSku()
    {
        $goods_array = GlGoods::where(['is_del' => 0])
            ->field('goods_id,market_price,shop_price,attribute')
            ->select()
            ->toArray();
        $del_suk_count = [];//需要删除的sku数组
        foreach ($goods_array as $index => $goods_info) {
            $goods_sku_info_array = GlGoodsSku::where(['goods_id' => $goods_info['goods_id']])->select()->toArray();
            if (count($this->get2DRepeat($goods_sku_info_array, ['sku_desc'])) > 0) {
                array_push($del_suk_count, ['goods_id' => $goods_info['goods_id'], $this->get2DRepeat($goods_sku_info_array, ['sku_desc'])]);
            }
        }

        //开始删除重复的sku
        foreach ($del_suk_count as $k => $v) {
            foreach ($v[0] as $k2 => $v2) {
                GlGoodsSku::where(['goods_id' => $v['goods_id'], 'sku_id' => $v2['sku_id']])
                    ->delete();
            }

        }
        return $del_suk_count;
    }

    /**
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 手动添加sku
     */
    private function addSku()
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
                foreach ($sku_desc_array as $index2 => $value2) {
                    $data['goods_id'] = $goods_info['goods_id'];
                    $data['sku_stock'] = 0;
                    $data['sku_shop_price'] = $goods_info['shop_price'];
                    $data['sku_market_price'] = $goods_info['market_price'];
                    $data['goods_id'] = $goods_info['goods_id'];
                    $data['give_integral'] = 0;
                    $data['integral'] = 0;
                    $data['img_url'] = removeImgUrl($sku_info[0]['img_url']);
                    $data['original_img_url'] = removeImgUrl($sku_info[0]['original_img_url']);
                    $data['sku_desc'] = $value2;
                    GlGoodsSku::create($data);
                    $count++;
                }

            }

        }

        return $count;

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
     * @param $arr
     * @return array
     * 获取数组重复项
     */
    private function getRepeat($arr)
    {
        // 获取去掉重复数据的数组
        $unique_arr = array_unique($arr);
        // 获取重复数据的数组
        $repeat_arr = array_diff_assoc($arr, $unique_arr);

        return $repeat_arr;
    }

    /**
     * @param $arr
     * @param $keys
     * @return array
     * 2维数组重复项
     */
    private function get2DRepeat($arr, $keys)
    {
        $unique_arr = array();
        $repeat_arr = array();
        foreach ($arr as $k => $v) {
            $str = "";
            foreach ($keys as $a => $b) {
                $str .= "{$v[$b]},";
            }
            if (!in_array($str, $unique_arr)) {
                $unique_arr[] = $str;
            } else {
                $repeat_arr[] = $v;
            }
        }
        return $repeat_arr;
    }

    /**
     * @param $arr
     * @param $key
     * @return mixed
     * 根据键删除数组项
     */
    private function byKeyrRemoveArrVal($arr, $key)
    {
        if (!array_key_exists($key, $arr)) {
            return $arr;
        }
        $keys = array_keys($arr);
        $index = array_search($key, $keys);
        if ($index !== FALSE) {
            array_splice($arr, $index, 1);
        }
        return $arr;
    }

    /**
     * @return bool
     * 事务测试
     */
    private function testDb()
    {

        Db::transaction(function () {

            $data1['zd1'] = 1;
            $data1['zd2'] = 1;
            $data1['zd3'] = 1;
            $data1['zd4'] = 1;

            Test1::create($data1);

            $data2['zd1'] = 1;
            $data2['zd2'] = 1;
            $data2['zd3'] = 1;

            Test2::create($data2);

        });
        return true;

    }
}