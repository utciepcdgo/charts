<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function _getPacketsReceived()
    {
        $packetsReceived = array("series" =>
            array("received" => 95, "expected" => 100, "progress" => 72)
        );

        return json_encode($packetsReceived);
    }
}
