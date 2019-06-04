<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/6/4
 * Time: 16:33
 */

namespace app\api\controller\v1\cms;


use app\api\model\GlOrder;
use app\api\service\UserAuthority;
use app\api\validate\CurrencyValidate;

class CmsOrder
{
    /**
     * @return mixed
     * @throws \app\lib\exception\CommonException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 分页获取订单
     */
    public function giveOrderListByPage()
    {
        //验证必要
        (new CurrencyValidate())->myGoCheck(['page', 'limit'], 'require');
        //验证正整数
        (new CurrencyValidate())->myGoCheck(['page', 'limit'], 'positiveInt');
        UserAuthority::checkAuthority(8);
        $data['page'] = request()->param('page');
        $data['limit'] = request()->param('limit');
        $where = [['is_del', '=', 0]];
        if (request()->param('order_sn') !== '') {
            array_push($where, ['order_sn', 'like', '%' . request()->param('order_sn') . '%']);
        }
        if (request()->param('logistics_address_name') !== '') {
            array_push($where, ['logistics_name', 'like', '%' . request()->param('logistics_address_name') . '%']);
        }
        if (request()->param('logistics_address_phone') !== '') {
            array_push($where, ['logistics_tel', 'like', '%' . request()->param('logistics_address_phone') . '%']);
        }
        if (request()->param('order_state') !== '') {
            array_push($where, ['order_state', '=',request()->param('order_state')]);
        }
        $result['list'] = GlOrder::where($where)
            ->page($data['page'], $data['limit'])
            ->order('create_time desc')
            ->select();

        $result['count'] = GlOrder::where($where)
            ->count();

        return $result;

    }
}