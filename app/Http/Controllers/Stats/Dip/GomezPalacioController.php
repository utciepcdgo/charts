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

        $preliminary_results_links = [
            'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d10-resultados-preliminares_1717458939.xlsx',
            'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d11-resultados-preliminares_1717458951.xlsx',
            'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d12-resultados-preliminares_1717459017.xlsx'
        ];

        return Inertia::render('Stats/GomezPalacio', [
            'materialSupplied' => parent::_getMaterialSupplied()->original,
            'packetsReceived' => parent::_getPacketsReceived()->original,
            'aecRegistration' => parent::_getAECRegistration()->original,
            // CÓMPUTOS
            'collatedPackets' => parent::_getCollatedPackets()->original,
            'recountPackets' => parent::_getRecountPackets()->original,
             // DOWNLOADS
            'preliminaryResults' => $preliminary_results_links,
        ]);
    }
}
