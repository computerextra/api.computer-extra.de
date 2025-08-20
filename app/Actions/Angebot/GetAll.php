<?php

namespace App\Actions\Angebot;

use App\Models\Angebot;

class GetAll
{
    public static function execute()
    {
        return Angebot::getAll();
    }
}