<?php

namespace App\Http\Controllers\Stats\Dip;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DurangoController extends Controller
{
    public function index()
    {

        $packetsReceived = array("series" =>
            array("received" => 1, "expected" => 100, "progress" => 72)
        );

        return Inertia::render('Stats/Durango', [
            'packetsReceived' => json_encode($packetsReceived)
        ]);

    }
}
