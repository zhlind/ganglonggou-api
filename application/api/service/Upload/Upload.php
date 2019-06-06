<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/16
 * Time: 15:00
 */

namespace app\api\service\Upload;


use app\lib\exception\CommonException;
use think\Controller;
use think\Image;

class Upload extends Controller
{
    /**
     * @param int $size
     * @param string $ext
     * @return mixed
     * @throws CommonException
     * 上传图片
     */
    public function ImgUpload($size = 2097152,$ext='jpg,png,gif,jpeg')
    {
        $file = request()->file('portrait_img');
        if ($file) {
            //字节(b)
            $info = $file->validate(['size' => $size, 'ext' => $ext])->move(config('my_config.img_file'));
            if ($info) {
                $file_name = str_replace("\\", "/", $info->getSaveName());
                //$file_name_ =$info->getSaveName();
                //压缩图片
                $image = Image::open($file);
                $h = $image->height();
                $w = $image->width();
                //$image->thumb($w, $h)->save(config('my_config.img_file').'thumb'.$file_name);
                $image->thumb($w + 0, $h + 0)->save(config('my_config.img_file') . $file_name);
                // $result['goods_img'] = config('my_config.img_url').'compress/'.$file_name;
                $result['goods_img'] = config('my_config.img_url') . $file_name;
                $result['name'] = config('my_config.img_url') . $file_name;
                $result['original_img'] = config('my_config.img_url') . $file_name;
            } else {
                throw new CommonException([ 'msg'=>'上传图片失败:' . $file->getError()]);
            }
        } else {
            throw new CommonException(['msg'=>'未获取到有效图片']);
        }
        return $result;
    }
}