<?php

namespace App\Actions\Abteilung;

use App\Models\Job;

class GetAll
{
    public static function execute()
    {
        return Job::getOnline();
    }
}