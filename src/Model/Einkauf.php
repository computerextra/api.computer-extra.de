<?php

namespace MyApi\Model;

use MyApi\App;

class Einkauf extends BaseModel
{
    protected string $table = 'Einkauf';
    protected string $primaryKey = 'id';
    protected bool $autoIncrement = false;
    protected array $fillable = ["id","artikel","menge","preis","lieferantId","bestelldatum","lieferdatum","status","besteller","bemerkung"];

    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
