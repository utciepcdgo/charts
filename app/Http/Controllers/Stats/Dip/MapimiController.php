<?php

namespace App\Http\Controllers\Stats\Dip;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class MapimiController extends Controller
{
    public function __construct($municipio = 13)
    {
        parent::__construct($municipio);
    }

    public function index()
    {
        Inertia::share('municipio_id', 13);

         $preliminary_results_links = [
            'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d09-resultados-preliminares_1717464608.xlsx'
        ];

        return Inertia::render('Stats/Mapimi', [
            'materialSupplied'  =>   parent::_getMaterialSupplied() ->original,
            'packetsReceived'   =>   parent::_getPacketsReceived()  ->original,
            'aecRegistration'   =>   parent::_getAECRegistration()  ->original,
            // CÓMPUTOS
            'collatedPackets'   =>   parent::_getCollatedPackets()  ->original,
            'recountPackets'    =>   parent::_getRecountPackets()   ->original,
            // DOWNLOADS
            'preliminaryResults' => $preliminary_results_links,
        ]);
    }
}
