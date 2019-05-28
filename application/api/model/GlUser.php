<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/23
 * Time: 10:12
 */

namespace app\api\model;


class GlUser extends BaseModel
{
    public function getUserImgAttr($value, $data)
    {
        return $this->spellOriginalImg($value, $data);
    }
}