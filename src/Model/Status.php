<?php

namespace MyApi\Model;

use MyApi\App;

class Status extends BaseModel
{
    protected string $table = 'Status';
    protected string $primaryKey = 'id';
    protected bool $autoIncrement = false;
    protected array $fillable = ["id","name","description"];

    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
