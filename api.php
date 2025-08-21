<?php

require_once "./config.php";

class API
{
    public $db;
    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    public function method_not_allowed()
    {
        $resp["status"] = "fail";
        $resp["num_rows"] = 0;
        $resp["data"] = null;
        return $resp;
    }

    public function get_abteilungen()
    {
        $sql = "SELECT * FROM `Abteilung` ORDER BY `index` ASC;";
        $query = $this->db->query($sql);
        $resp["status"] = "success";
        $resp["num_rows"] = $query->num_rows;
        $resp["data"] = $query->fetch_all(MYSQL_ASSOC);
        return $resp;
    }

    public function get_agebote()
    {
        $sql = "SELECT * FROM `Angebot` WHERE anzeigen = 1 ORDER BY date_start ASC;";
        $query = $this->db->query($sql);
        $resp["status"] = "success";
        $resp["num_rows"] = $query->num_rows;
        $resp["data"] = $query->fetch_all(MYSQL_ASSOC);
        return $resp;
    }

    public function get_jobs()
    {
        $sql = "SELECT * FROM `Jobs` WHERE online = 1 ORDER BY name ASC;";
        $query = $this->db->query($sql);
        $resp["status"] = "success";
        $resp["num_rows"] = $query->num_rows;
        $resp["data"] = $query->fetch_all(MYSQL_ASSOC);
        return $resp;
    }

    public function get_mitarbeiter()
    {
        $sql = "SELECT id, name, short, image, sex, focus, abteilungId FROM `Mitarbeiter` WHERE short IS NOT NULL AND sex IS NOT NULL AND mail IS NOT NULL and abteilungId IS NOT NULL ORDER BY name ASC;";
        $query = $this->db->query($sql);
        $resp["status"] = "success";
        $resp["num_rows"] = $query->num_rows;
        $resp["data"] = $query->fetch_all(MYSQL_ASSOC);
        return $resp;
    }

    public function get_partner()
    {
        $sql = "SELECT * FROM `Partner` ORDER BY name ASC;";
        $query = $this->db->query($sql);
        $resp["status"] = "success";
        $resp["num_rows"] = $query->num_rows;
        $resp["data"] = $query->fetch_all(MYSQL_ASSOC);
        return $resp;
    }

    public function get_referenzen()
    {
        $sql = "SELECT * FROM `Referenzen` WHERE Online = 1 ORDER BY Name ASC;";
        $query = $this->db->query($sql);
        $resp["status"] = "success";
        $resp["num_rows"] = $query->num_rows;
        $resp["data"] = $query->fetch_all(MYSQL_ASSOC);
        return $resp;
    }
}