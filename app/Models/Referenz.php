<?php

namespace App\Models;

use App\Database\Database;
use PDO;

class Referenz
{

    public static function getAll()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * from Referenzen WHERE online = 1 ORDER BY Name ASC;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}