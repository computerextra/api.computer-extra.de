<?php

namespace App\Actions\Partner;

use App\Models\Partner;

class GetAll
{
    public static function execute()
    {
        return Partner::getAll();
    }
}