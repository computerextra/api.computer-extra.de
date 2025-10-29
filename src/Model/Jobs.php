<?php

namespace MyApi\Model;

use MyApi\App;

class Jobs extends BaseModel
{
    protected string $table = 'Jobs';
    protected string $primaryKey = 'id';
    protected bool $autoIncrement = false;
    protected array $fillable = ["id","title","description","department","location","salary","published_at"];

    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
