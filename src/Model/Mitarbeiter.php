<?php

namespace MyApi\Model;

use MyApi\App;

class Mitarbeiter extends BaseModel
{
    protected string $table = 'Mitarbeiter';
    protected string $primaryKey = 'id';
    protected bool $autoIncrement = false;
    protected array $fillable = ["id","name","short","image","sex","focus","mail","abteilungId","einkaufId","Azubi","Geburtstag","Gruppenwahl","HomeOffice","Mobil_Business","Mobil_Privat","Telefon_Business","Telefon_Intern_1","Telefon_Intern_2","Telefon_Privat","Bild"];

    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
