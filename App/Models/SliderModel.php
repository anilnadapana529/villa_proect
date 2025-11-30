<?php
namespace App\Models;

use App\Core\BaseModel;

class SliderModel extends BaseModel
{
    protected static $table = "sliders";

    public static function all()
    {
        return self::query("SELECT * FROM sliders ORDER BY id DESC");
    }
}
