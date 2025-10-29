<?php

namespace MyApi\Model;

use MyApi\App;

class Abteilung extends BaseModel
{
    protected string $table = 'Abteilung';
    protected string $primaryKey = 'id';
    protected bool $autoIncrement = false;
    protected array $fillable = ["id","name","short"];

    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
