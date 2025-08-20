<?php

namespace App\Controllers;

use GeekGroveOfficial\PhpSmartValidator\Validator\Validator;
use App\Actions\Partner\GetAll;

use App\Traits\ApiResponse;
use Exception;
use Flight;

class PartnerController
{
    use ApiResponse;

    public function index()
    {
        return $this->success(GetAll::execute(), getMessage("partner_route"), 200);
    }


}