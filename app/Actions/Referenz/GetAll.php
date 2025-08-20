<?php

namespace App\Actions\Referenz;

use App\Models\Referenz;

class GetAll
{
    public static function execute()
    {
        return Referenz::getAll();
    }
}