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
/*Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');

return [

];*/

use think\facade\Route;

/*测试*/
Route::get('api/:version/test$', 'api/:version.Test/test');

/*百度ueditor*/
Route::get('api/:version/ueditor_file_upload$', 'api/:version.ueditor.Ueditor/ueditorFileUpload');
Route::post('api/:version/ueditor_file_upload$', 'api/:version.ueditor.Ueditor/ueditorFileUpload');
Route::rule('api/:version/ueditor_file_upload$', 'api/:version.Option/returnTrue','OPTIONS');

/*cms*/
//登录
Route::post('api/:version/cms/login$', 'api/:version.cms.CmsAdmin/adminLogin');
//获取商品列表
Route::get('api/:version/cms/cms_get_goods_list$', 'api/:version.cms.CmsGoods/giveGoodsListByPage');
//添加商品
Route::post('api/:version/cms/cms_add_goods', 'api/:version.cms.CmsGoods/addGoods');
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