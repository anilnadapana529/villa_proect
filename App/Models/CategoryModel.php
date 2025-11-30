<?php
namespace App\Models;

use App\Core\BaseModel;

class CategoryModel extends BaseModel
{
    protected static $table = "categories";

    public static function all()
    {
        return self::query("SELECT * FROM categories ORDER BY id ASC");
    }
}
