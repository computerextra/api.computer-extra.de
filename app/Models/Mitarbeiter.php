<?php

namespace App\Models;

use App\Database\Database;
use PDO;

class Mitarbeiter
{

    public static function getAll()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT id, name, short, image, sex, focus, mail, abteilungId FROM `Mitarbeiter` WHERE sex IS NOT NULL ANd mail IS NOT NULL AND short IS NOT NULL AND abteilungId IS NOT NULL ORDER BY name ASC;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}