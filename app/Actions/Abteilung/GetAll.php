<?php

namespace App\Actions\Abteilung;

use App\Models\Abteilung;

class GetAll
{
    public static function execute()
    {
        return Abteilung::index();
    }
}