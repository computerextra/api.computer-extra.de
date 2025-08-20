<?php

namespace App\Controllers;

use GeekGroveOfficial\PhpSmartValidator\Validator\Validator;
use App\Actions\Angebot\GetAll;
use App\Actions\Angebot\GetOnline;

use App\Traits\ApiResponse;
use Exception;
use Flight;

class AngebotController
{
    use ApiResponse;

    public function index()
    {
        return $this->success(GetAll::execute(), getMessage("angebot_route"), 200);
    }


    public function online()
    {
        return $this->success(GetOnline::execute(), getMessage("angebot_route"), 200);
    }
}