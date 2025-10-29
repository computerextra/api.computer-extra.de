<?php

namespace MyApi\Model;

use MyApi\App;

class Pdfs extends BaseModel
{
    protected string $table = 'Pdfs';
    protected string $primaryKey = 'id';
    protected bool $autoIncrement = false;
    protected array $fillable = ["id","title","body"];

    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
