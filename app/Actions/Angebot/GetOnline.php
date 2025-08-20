<?php

namespace App\Actions\Angebot;

use App\Models\Angebot;

class GetOnline
{
    public static function execute()
    {
        return Angebot::getOnline();
    }
}