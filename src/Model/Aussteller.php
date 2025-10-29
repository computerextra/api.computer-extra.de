<?php

namespace MyApi\Model;

use MyApi\App;

class Aussteller extends BaseModel
{
    protected string $table = 'Aussteller';
    protected string $primaryKey = 'id';
    protected bool $autoIncrement = false;
    protected array $fillable = ["id","name","beschreibung","kontakt","ort"];

    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
