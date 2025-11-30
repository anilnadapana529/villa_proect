<?php

namespace App\Core;

use App\Core\Database;
use mysqli;

class BaseModel
{
    protected static $table;

    /** Static query helper for child classes */
    protected static function query(string $sql): array
    {
        $db = Database::connect();
        $result = $db->query($sql);

        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
