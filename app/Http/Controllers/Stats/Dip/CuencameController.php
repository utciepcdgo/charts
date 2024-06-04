<?php

namespace App\Http\Controllers\Stats\Dip;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CuencameController extends Controller
{
    public function __construct($municipio = 4)
    {
        parent::__construct($municipio);
    }

    public function index()
    {
        Inertia::share('municipio_id', 4);

        $preliminary_results_links = [
            'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d14-resultados-preliminares_1717464263.xlsx',
        ];

        return Inertia::render('Stats/Cuencame', [
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
