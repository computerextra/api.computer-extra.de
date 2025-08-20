<?php

namespace App\Models;

use App\Database\Database;
use PDO;

class Abteilung
{
    public static function index()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM `Abteilung` ORDER BY `index` ASC;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}