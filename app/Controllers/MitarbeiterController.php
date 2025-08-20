<?php

namespace App\Controllers;

use GeekGroveOfficial\PhpSmartValidator\Validator\Validator;
use App\Actions\Mitarbeiter\GetAll;

use App\Traits\ApiResponse;
use Exception;
use Flight;

class MitarbeiterController
{
    use ApiResponse;

    public function index()
    {
        return $this->success(GetAll::execute(), getMessage("mitarbeiter_route"), 200);
    }


}