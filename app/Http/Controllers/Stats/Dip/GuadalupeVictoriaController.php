<?php

namespace App\Http\Controllers\Stats\Dip;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class GuadalupeVictoriaController extends Controller
{
    public function __construct($municipio = 8)
    {
        parent::__construct($municipio);
    }

    public function index()
    {
        Inertia::share('municipio_id', 8);

        return Inertia::render('Stats/GuadalupeVictoria', [
            'materialSupplied' => parent::_getMaterialSupplied()->original,
            'packetsReceived' => parent::_getPacketsReceived()->original,
            'aecRegistration' => parent::_getAECRegistration()->original,
            // CÓMPUTOS
            'collatedPackets' => parent::_getCollatedPackets()->original,
            'recountPackets' => parent::_getRecountPackets()->original,
        ]);
    }
}
