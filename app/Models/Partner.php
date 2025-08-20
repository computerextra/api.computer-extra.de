<?php

namespace App\Models;

use App\Database\Database;
use PDO;

class Partner
{

    public static function getAll()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * from Partner ORDER BY name ASC;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}