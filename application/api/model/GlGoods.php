<?php
/**
 * Created by PhpStorm.
 * User: administrator_liwy
 * Date: 2019/5/15
 * Time: 18:49
 */

namespace app\api\model;


class GlGoods extends BaseModel
{
    public function getOriginalImgAttr($value, $data)
    {
        return $this->spellOriginalImg($value, $data);
    }

    public function getGoodsImgAttr($value, $data)
    {
        return $this->spellOriginalImg($value, $data);
    }

    public function getGoodsDescAttr($value, $data)
    {
        return $this->imgTagSpellOriginalImg($value, $data);
    }

    public function getAttributeAttr($value, $data){

        return json_decode($value,true);
    }
}