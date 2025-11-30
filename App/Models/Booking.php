<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Booking
{
    public static function checkAvailability($villaId, $start, $end)
    {
        $pdo = Database::instance();

        $stmt = $pdo->prepare("
            SELECT COUNT(*) AS cnt
            FROM bookings
            WHERE villa_id = ?
            AND status = 'confirmed'
            AND (
                (check_in <= ? AND check_out >= ?) OR
                (check_in <= ? AND check_out >= ?)
            )
        ");
        $stmt->execute([$villaId, $start, $start, $end, $end]);

        return $stmt->fetch()['cnt'] == 0;
    }

    public static function create($d)
    {
        $pdo = Database::instance();

        $ownerStmt = $pdo->prepare("SELECT owner_id FROM villas WHERE id = ?");
        $ownerStmt->execute([$d['villa_id']]);
        $ownerId = $ownerStmt->fetchColumn();

        $stmt = $pdo->prepare("
            INSERT INTO bookings (user_id, villa_id, owner_id, check_in, check_out, total_amount, status)
            VALUES (?, ?, ?, ?, ?, ?, 'confirmed')
        ");

        return $stmt->execute([
            $d['user_id'],
            $d['villa_id'],
            $ownerId,
            $d['check_in'],
            $d['check_out'],
            $d['amount']
        ]);
    }

    public static function calendar($villaId)
    {
        $pdo = Database::instance();

        $stmt = $pdo->prepare("
            SELECT check_in, check_out
            FROM bookings
            WHERE villa_id = ?
            AND status = 'confirmed'
        ");
        $stmt->execute([$villaId]);

        $dates = [
            "booked" => [],
            "blocked" => []
        ];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $start = strtotime($row['check_in']);
            $end = strtotime($row['check_out']);

            for ($i = $start; $i <= $end; $i += 86400) {
                $dates["booked"][] = date("Y-m-d", $i);
            }
        }

        return $dates;
    }
}
