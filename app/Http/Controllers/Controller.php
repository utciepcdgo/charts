<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    protected int $distrito;

    public function __construct($municipio = 0)
    {
        $this->distrito = $municipio;
    }


    public function _getPacketsReceived(): JsonResponse
    {
        // TOTAL PAQUETES
        // @param $this->municipio: DURANGO
        $paquetes_x = DB::Connection('mysql_dgo')->table('paquetes AS p')
            ->select('p.id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->where('p.eleccion_id', '=', 3)
            ->where('c.cat_municipio_id', '=', $this->distrito)
            ->get();

        // PAQUETES ENTREGADOS
        // @param $this->municipio: DURANGO
        $paquetes_y = DB::Connection('mysql_dgo')->table('paquetes AS p')
            ->select('p.id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->where('p.eleccion_id', '=', 3)
            ->where('p.estado', 1)
            ->where('c.cat_municipio_id', '=', $this->distrito)
            ->get();

        return response()->json(array("series" =>
            array("received" => 95, "expected" => 11, "progress" => 72)
        ));
    }

    public function _getMaterialSupplied(): jsonResponse
    {
        return response()->json(array("series" =>
            array("received" => 95, "expected" => 100, "progress" => 72)
        ));
    }

    public function _getAECRegistration(): jsonResponse
    {
        return response()->json(array("series" =>
            array("received" => 95, "expected" => 100, "progress" => 72)
        ));
    }

    public function _getCollatedPackets(): jsonResponse
    {
        return response()->json(array("series" =>
            array("received" => 95, "expected" => 100, "progress" => 72)
        ));
    }

    public function _getRecountPackets(): jsonResponse
    {
        return response()->json(array("series" =>
            array("received" => 95, "expected" => 100, "progress" => 72)
        ));
    }
}
