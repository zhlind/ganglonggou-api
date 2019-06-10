<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/6/10
 * Time: 14:24
 */

namespace app\api\controller\v1\cms;


use app\api\model\GlGoods;
use app\api\model\GlGoodsEvaluate;
use app\api\service\UserAuthority;
use app\api\validate\CurrencyValidate;
use app\lib\exception\CommonException;

class CmsEvaluate
{
    /**
     * @return mixed
     * @throws \app\lib\exception\CommonException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 分页返回
     */
    public function giveEvaluateList()
    {

        //验证必要
        (new CurrencyValidate())->myGoCheck(['page', 'limit'], 'require');
        //验证正整数
        (new CurrencyValidate())->myGoCheck(['page', 'limit'], 'positiveInt');
        UserAuthority::checkAuthority(8);
        $data['page'] = request()->param('page');
        $data['limit'] = request()->param('limit');
        $where['is_del'] = 0;

        $result['list'] = GlGoodsEvaluate::where($where)
            ->page($data['page'], $data['limit'])
            ->order('create_time desc')
            ->select();

        $result['count'] = GlGoodsEvaluate::where($where)
            ->count();

        return $result;

    }


    /**
     * @return bool
     * @throws CommonException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * 审核评价
     */
    public function allowEvaluate()
    {
        //验证必要
        (new CurrencyValidate())->myGoCheck(['evaluate_id', 'goods_id'], 'require');
        //验证正整数
        (new CurrencyValidate())->myGoCheck(['evaluate_id', 'goods_id'], 'positiveInt');

        $evaluate_id = request()->param('evaluate_id');
        $goods_id = request()->param('goods_id');

        $upd_number = GlGoodsEvaluate::where([
            ['id', '=', $evaluate_id],
            ['goods_id', '=', $goods_id],
            ['is_del', '=', 0],
            ['is_allow', '=', 0]
        ])
            ->update([
                'is_allow' => 1
            ]);

        if ($upd_number < 1) {
            throw new CommonException(['msg' => '审核失败']);
        }

        /*增加评论量*/
        GlGoods::where([
            ['goods_id', '=', $goods_id]
        ])
            ->setInc('evaluate_count');

        return true;
    }

    /**
     * @return bool
     * @throws CommonException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * 删除评价
     */
    public function delEvaluate()
    {

        //验证必要
        (new CurrencyValidate())->myGoCheck(['evaluate_id'], 'require');
        //验证正整数
        (new CurrencyValidate())->myGoCheck(['evaluate_id'], 'positiveInt');

        $evaluate_id = request()->param('evaluate_id');

        $upd_number = GlGoodsEvaluate::where([
            ['id', '=', $evaluate_id],
            ['is_del', '=', 0],
        ])
            ->update([
                'is_del' => 1
            ]);

        if ($upd_number < 1) {
            throw new CommonException(['msg' => '删除失败']);
        }

        return true;

    }
}