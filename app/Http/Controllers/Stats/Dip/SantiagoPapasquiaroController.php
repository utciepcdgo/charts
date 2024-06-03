<?php

namespace App\Http\Controllers\Stats\Dip;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class SantiagoPapasquiaroController extends Controller
{
    public function __construct($municipio = 33)
    {
        parent::__construct($municipio);
    }

    public function index()
    {
        Inertia::share('municipio_id', 33);

        return Inertia::render('Stats/SantiagoPapasquiaro', [
            'materialSupplied'  =>   parent::_getMaterialSupplied() ->original,
            'packetsReceived'   =>   parent::_getPacketsReceived()  ->original,
            'aecRegistration'   =>   parent::_getAECRegistration()  ->original,
            // CÓMPUTOS
            'collatedPackets'   =>   parent::_getCollatedPackets()  ->original,
            'recountPackets'    =>   parent::_getRecountPackets()   ->original,
        ]);
    }
}