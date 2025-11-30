<?php
namespace App\Models;

use App\Core\BaseModel;

class ListingModel extends BaseModel
{
    protected static $table = "villas";

    public static function featured()
    {
        return self::query("
            SELECT 
                id, 
                title,
                price_per_night AS price,
                cover_image AS image,
                location
            FROM villas
            WHERE status = 'approved'
            ORDER BY id DESC
            LIMIT 10
        ");
    }
}
