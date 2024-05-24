<?php

namespace App\Http\Controllers\Stats\Dip;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DurangoController extends Controller
{
    public function __construct($municipio = 5)
    {
        parent::__construct($municipio);
    }

    public function index()
    {
//        dd(parent::_getPacketsReceived());
        return Inertia::render('Stats/Durango', [
            'materialSupplied'  =>   parent::_getMaterialSupplied() ->original,
            'packetsReceived'   =>   parent::_getPacketsReceived()  ->original,
            'aecRegistration'   =>   parent::_getAECRegistration()  ->original,
            // CÓMPUTOS
            'collatedPackets'   =>   parent::_getCollatedPackets()  ->original,
            'recountPackets'    =>   parent::_getRecountPackets()   ->original,
        ]);
    }
}
