<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/6/13
 * Time: 13:50
 */

namespace app\api\model;


class GlClassifyAd extends BaseModel
{
    public function getImgUrlAttr($value, $data)
    {
        return $this->spellOriginalImg($value, $data);
    }
}