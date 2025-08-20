<?php

namespace App\Models;

use App\Database\Database;
use PDO;

class Job
{

    public static function getOnline()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM Jobs WHERE online = 1;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}