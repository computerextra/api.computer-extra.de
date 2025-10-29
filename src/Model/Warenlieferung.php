<?php

namespace MyApi\Model;

use MyApi\App;

class Warenlieferung extends BaseModel
{
    protected string $table = 'Warenlieferung';
    protected string $primaryKey = 'id';
    protected bool $autoIncrement = false;
    protected array $fillable = ["id","lieferantId","artikel","menge","datum","status","empfaenger","bemerkung"];

    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
