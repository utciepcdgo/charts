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
        Inertia::share('municipio_id', 5);

        $preliminary_results_links = [
            'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d01-resultados-preliminares_1717458675.xlsxx',
            'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d02-resultados-preliminares_1717458683.xlsx',
            'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d03-resultados-preliminares_1717458743.xlsx',
            'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d04-resultados-preliminares_1717458767.xlsx',
            'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d05-resultados-preliminares_1717458782.xlsx',
            'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d06-resultados-preliminares_1717458835.xlsx',
        ];

        return Inertia::render('Stats/Durango', [
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
