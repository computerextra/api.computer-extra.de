<?php

namespace App\Controllers;

use GeekGroveOfficial\PhpSmartValidator\Validator\Validator;
use App\Actions\Referenz\GetAll;

use App\Traits\ApiResponse;
use Exception;
use Flight;

class ReferenzController
{
    use ApiResponse;

    public function index()
    {
        return $this->success(GetAll::execute(), getMessage("partner_route"), 200);
    }


}