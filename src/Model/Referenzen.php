<?php

namespace MyApi\Model;

use MyApi\App;

class Referenzen extends BaseModel
{
    protected string $table = 'Referenzen';
    protected string $primaryKey = 'id';
    protected bool $autoIncrement = false;
    protected array $fillable = ["id","title","client","year","description"];

    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
