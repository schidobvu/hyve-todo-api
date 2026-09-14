<?php

namespace App\Http\Controllers;

use Bluecloud\ResponseBuilder\Traits\BuildResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, BuildResponse;
}
