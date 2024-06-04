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
            [
                'id' => 1,
                'district' => '01',
                'url' => 'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d01-resultados-preliminares_1717458675.xlsxx',
            ],
            [
                'id' => 2,
                'district' => '02',
                'url' => 'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d02-resultados-preliminares_1717458683.xlsx',
            ],
            [
                'id' => 3,
                'district' => '03',
                'url' => 'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d03-resultados-preliminares_1717458743.xlsx',
            ],
            [
                'id' => 4,
                'district' => '04',
                'url' => 'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d04-resultados-preliminares_1717458767.xlsx',
            ],
            [
                'id' => 5,
                'district' => '05',
                'url' => 'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d05-resultados-preliminares_1717458782.xlsx',
            ],
            [
                'id' => 6,
                'district' => '06',
                'url' => 'https://s3.amazonaws.com/static-test.appsiepcdurango.mx/formatos/preliminares/d06-resultados-preliminares_1717458835.xlsx',
            ]
        ];

        return Inertia::render('Stats/Durango', [
            'materialSupplied' => parent::_getMaterialSupplied()->original,
            'packetsReceived' => parent::_getPacketsReceived()->original,
            'aecRegistration' => parent::_getAECRegistration()->original,
            // CÓMPUTOS
            'collatedPackets' => parent::_getCollatedPackets()->original,
            'recountPackets' => parent::_getRecountPackets()->original,
            // DOWNLOADS
            'preliminaryResults' => json_encode($preliminary_results_links, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ]);
    }
}
