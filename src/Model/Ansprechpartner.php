<?php

namespace MyApi\Model;

use MyApi\App;

class Ansprechpartner extends BaseModel
{
    protected string $table = 'Ansprechpartner';
    protected string $primaryKey = 'id';
    protected bool $autoIncrement = false;
    protected array $fillable = ["id","name","person_role","mail","telefon","firma"];

    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
