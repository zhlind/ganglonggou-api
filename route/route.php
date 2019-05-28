<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//use think\facade\Route;
use think\facade\Route;

/*测试*/
Route::get('api/:version/test$', 'api/:version.Test/test');

/*微信分享*/
Route::get('api/:version/goods_make/get_WxJsSdk', 'api/:version.WxShare/giveWxShareInfo');


/*百度ueditor*/
Route::get('api/:version/ueditor_file_upload$', 'api/:version.ueditor.Ueditor/ueditorFileUpload');
Route::post('api/:version/ueditor_file_upload$', 'api/:version.ueditor.Ueditor/ueditorFileUpload');
Route::rule('api/:version/ueditor_file_upload$', 'api/:version.Option/returnTrue','OPTIONS');

/*cms*/
//登录
Route::post('api/:version/cms/login$', 'api/:version.cms.CmsAdmin/adminLogin');
//获取商品列表
Route::get('api/:version/cms/cms_get_goods_list$', 'api/:version.cms.CmsGoods/giveGoodsListByPage');
//获取单个商品信息
Route::get('api/:version/cms/cms_get_goods_info$', 'api/:version.cms.CmsGoods/giveGoodsInfo');
//搜索商品
Route::get('api/:version/cms/cms_search_goods$', 'api/:version.cms.CmsGoods/searchGoods');
//搜索商品
Route::get('api/:version/cms/cms_get_goods_by_goods_id_array', 'api/:version.cms.CmsGoods/giveGoodsByGoodsIdArray');
//添加商品
Route::post('api/:version/cms/cms_add_goods$', 'api/:version.cms.CmsGoods/addGoods');
//更新商品
Route::post('api/:version/cms/cms_upd_goods$', 'api/:version.cms.CmsGoods/updGoods');
//删除商品
Route::post('api/:version/cms/cms_del_goods$', 'api/:version.cms.CmsGoods/delGoods');
//批量修改商品头
Route::post('api/:version/cms/cms_batch_upd_goods_head_name$', 'api/:version.cms.CmsGoods/updGoodsNameHeadName');
//批量修改商品头
Route::post('api/:version/cms/cms_copy_goods_by_parent_id$', 'api/:version.cms.CmsGoods/copyGoodsByParentId');
//获取分类
Route::get('api/:version/cms/cms_get_cat_list$', 'api/:version.cms.CmsCat/giveAllCat');
//获取分类列表
Route::get('api/:version/cms/cms_get_cat_list_by_page$', 'api/:version.cms.CmsCat/giveCatListByPage');
//添加分类
Route::post('api/:version/cms/cms_add_cat$', 'api/:version.cms.CmsCat/addCat');
//编辑分类
Route::post('api/:version/cms/cms_upd_cat$', 'api/:version.cms.CmsCat/updCat');
//删除分类
Route::post('api/:version/cms/cms_del_cat$', 'api/:version.cms.CmsCat/delCat');
//图片上传
Route::post('api/:version/cms/cms_upload_goods_img$', 'api/:version.upload.Upload/ImgUpload');
Route::rule('api/:version/cms/cms_upload_goods_img$', 'api/:version.Option/returnTrue','OPTIONS');
//获取广告列表
Route::get('api/:version/cms/cms_get_index_ad_list$', 'api/:version.cms.CmsIndexAd/giveIndexAdList');
//编辑广告
Route::post('api/:version/cms/cms_add_index_ad$', 'api/:version.cms.CmsIndexAd/addIndexAd');
Route::post('api/:version/cms/cms_upd_index_ad$', 'api/:version.cms.CmsIndexAd/updIndexAd');
Route::post('api/:version/cms/cms_del_index_ad$', 'api/:version.cms.CmsIndexAd/delIndexAd');
//预约订单
Route::get('api/:version/cms/cms_get_make_order_list$', 'api/:version.cms.CmsMakeOrder/giveMakeOderList');
Route::post('api/:version/cms/cms_handle_make_order$', 'api/:version.cms.CmsMakeOrder/handleMakeOrder');
Route::post('api/:version/cms/cms_del_make_order$', 'api/:version.cms.CmsMakeOrder/delMakeOrder');
//优惠券
Route::get('api/:version/cms/cms_get_coupon_list$', 'api/:version.cms.CmsCoupon/giveCouponListByPage');
Route::post('api/:version/cms/cms_add_coupon$', 'api/:version.cms.CmsCoupon/addCoupon');
Route::post('api/:version/cms/cms_upd_coupon$', 'api/:version.cms.CmsCoupon/updCoupon');
Route::post('api/:version/cms/cms_del_coupon$', 'api/:version.cms.CmsCoupon/delCoupon');


/*goods_make*/
//获取首页信息
Route::get('api/:version/goods_make/get_index_info$', 'api/:version.goods_make.GoodsMakeIndex/giveIndexInfo');
//获取商品额外信息
Route::get('api/:version/goods_make/get_extra_goods_info$', 'api/:version.goods_make.GoodsInfo/giveExtraGoodsInfo');
//获取商品信息
Route::get('api/:version/goods_make/get_goods_info$', 'api/:version.goods_make.GoodsInfo/giveGoodsInfoByGoodsId');
//提交预约订单
Route::post('api/:version/goods_make/add_make_order$', 'api/:version.goods_make.MakeOrder/addOrder');


/*普通*/
//获取首页信息
Route::get('api/:version/get_index_info$', 'api/:version.common.Index/giveIndexInfo');
//获取商品信息
Route::get('api/:version/get_goods_info$', 'api/:version.common.Goods/giveGoodsInfoByGoodsId');
//商品额外信息
Route::get('api/:version/get_extra_goods_info$', 'api/:version.common.Goods/giveExtraGoodsInfo');
//登录
Route::post('api/:version/test_login$', 'api/:version.common.Login/testLogin');
//领取优惠券
Route::post('api/:version/user_get_coupon$', 'api/:version.common.Coupon/userGetCoupon');
//领取优惠券
Route::get('api/:version/user_get_coupon_list$', 'api/:version.common.Coupon/giveCouponListByUserId');
//用户获取购物车
Route::post('api/:version/user_get_cart$','api/:version.common.Cart/userGetCart');
//添加地址
Route::post('api/:version/user_add_address$','api/:version.common.Address/addAddress');
//添加地址
Route::post('api/:version/user_upd_address$','api/:version.common.Address/updAddress');
//获取收货地址
Route::get('api/:version/user_get_address$','api/:version.common.Address/giveAddress');
//切换默认收货地址
Route::post('api/:version/user_upd_default_address$','api/:version.common.Address/updDefaultAddress');
//删除收货地址
Route::post('api/:version/user_del_address$','api/:version.common.Address/delAddress');
//获取用户信息
Route::get('api/:version/user_get_user_info$','api/:version.common.User/giveUserInfoByUserToken');
//获取支付信息
Route::get('api/:version/user_get_pay_list$','api/:version.common.Pay/givePayList');