<?php

namespace MyApi\Model;

use MyApi\App;

class Lieferant extends BaseModel
{
    protected string $table = 'Lieferant';
    protected string $primaryKey = 'id';
    protected bool $autoIncrement = false;
    protected array $fillable = ["id","name","kontakt","adresse"];

    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
