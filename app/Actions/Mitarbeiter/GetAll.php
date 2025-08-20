<?php

namespace App\Actions\Mitarbeiter;

use App\Models\Mitarbeiter;

class GetAll
{
    public static function execute()
    {
        return Mitarbeiter::getAll();
    }
}