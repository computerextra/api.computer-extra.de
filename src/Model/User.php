<?php

namespace MyApi\Model;

use MyApi\App;

class User extends BaseModel
{
    protected string $table = 'User';
    protected string $primaryKey = 'id';
    protected bool $autoIncrement = false;
    protected array $fillable = ["id","username","password","role"];

    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
