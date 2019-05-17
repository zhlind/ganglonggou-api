<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/17
 * Time: 9:09
 */

namespace app\api\model;


class GlIndexAd extends BaseModel
{
    public function getAdImgAttr($value, $data)
    {
        return $this->spellOriginalImg($value, $data);
    }
}