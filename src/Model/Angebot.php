<?php

namespace MyApi\Model;

use MyApi\App;

class Angebot extends BaseModel
{
    protected string $table = 'Angebot';
    protected string $primaryKey = 'id';
    protected bool $autoIncrement = false;
    protected array $fillable = ["id","title","preis","beschreibung","active","start_date","end_date","kategorie"];

    public function __construct(App $app)
    {
        parent::__construct($app);
    }
}
