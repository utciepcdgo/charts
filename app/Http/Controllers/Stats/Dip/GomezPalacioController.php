<?php

namespace App\Http\Controllers\Stats\Dip;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class GomezPalacioController extends Controller
{
    public function __construct($municipio = 7)
    {
        parent::__construct($municipio);
    }

    public function index()
    {
        Inertia::share('municipio_id', 7);

        return Inertia::render('Stats/GomezPalacio', [
            'materialSupplied' => parent::_getMaterialSupplied()->original,
            'packetsReceived' => parent::_getPacketsReceived()->original,
            'aecRegistration' => parent::_getAECRegistration()->original,
            // CÓMPUTOS
            'collatedPackets' => parent::_getCollatedPackets()->original,
            'recountPackets' => parent::_getRecountPackets()->original,
        ]);
    }
}
