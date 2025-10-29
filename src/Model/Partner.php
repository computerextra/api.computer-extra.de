<?php

namespace MyApi\Model;

use MyApi\App;

class Partner extends BaseModel
{
    protected string $table = 'Partner';
    protected string $primaryKey = 'id';
    protected bool $autoIncrement = false;
    protected array $fillable = ["id","name","kontakt","url"];

    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
