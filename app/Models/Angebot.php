<?php

namespace App\Models;

use App\Database\Database;
use PDO;

class Angebot
{
    public static function getAll()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM Angebot;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getOnline()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM Angebot WHERE anzeigen = 1;");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM Angebot WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}