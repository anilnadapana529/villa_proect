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
                v.id,
                v.name AS title,
                v.weekday_price AS price,
                v.location,
                (SELECT image FROM villa_images WHERE villa_id = v.id LIMIT 1) AS image
            FROM villas v
            WHERE v.status = 'approved'
            ORDER BY v.id DESC
            LIMIT 10
        ");
    }
}
