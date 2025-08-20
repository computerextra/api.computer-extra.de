<?php

namespace App\Controllers;

use GeekGroveOfficial\PhpSmartValidator\Validator\Validator;
use App\Actions\Abteilung\GetAll;

use App\Traits\ApiResponse;
use Exception;
use Flight;

class JobController
{
    use ApiResponse;

    public function index()
    {
        return $this->success(GetAll::execute(), getMessage("job_route"), 200);
    }


}