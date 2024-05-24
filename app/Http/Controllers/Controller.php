<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected int $municipio;

    public function __construct($municipio = 0)
    {
        $this->municipio = $municipio;
    }

    public function _getPacketsReceived(): JsonResponse
    {
        // TOTAL PAQUETES
        // @param $this->municipio: DURANGO
        $packets_x = DB::Connection('mysql_dgo')->table('paquetes AS p')
            ->select('p.id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->where('p.eleccion_id', '=', 3)
            ->where('c.cat_municipio_id', '=', $this->municipio)
            ->get()->count();

        // PAQUETES ENTREGADOS
        // @param $this->municipio: DURANGO
        $packets_y = DB::Connection('mysql_dgo')->table('paquetes AS p')
            ->select('p.id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->where('p.eleccion_id', '=', 3)
            ->where('p.estado', 1)
            ->where('c.cat_municipio_id', '=', $this->municipio)
            ->get()->count();

        // PROGRESS
        $packets_progress = number_format((($packets_y / $packets_x) * 100), 2, '.', ',');

        return response()->json(array("series" =>
            array("received" => $packets_y, "expected" => $packets_x, "progress" => $packets_progress)
        ));
    }

    public function _getMaterialSupplied(): jsonResponse
    {
        // TOTAL PAQUETES
        $packets_x = DB::Connection('mysql_dgo')->table('paquetes AS p')
            ->select('p.id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->where('p.eleccion_id', '=', 3)
            ->where('c.cat_municipio_id', '=', $this->municipio)
            ->get()->count();

        // PAQUETES ENTREGADOS A CAES
        $packets_y = DB::Connection('mysql_dgo')->table('paquetes AS p')
            ->select('c.seccion', 'c.casilla', 'p.eleccion_id', 'ec.*')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->join('entrega_cae AS ec', 'p.id', '=', 'ec.paquete_id')
            ->where('c.cat_municipio_id', $this->municipio)
            ->where('p.eleccion_id', 3)
            ->where('ec.deleted_at', null)
            ->get()->count();

        // PROGRESS
        $packets_progress = number_format((($packets_y / $packets_x) * 100), 2, '.', ',');

        return response()->json(array("series" =>
            array("received" => $packets_y, "expected" => $packets_x, "progress" => $packets_progress)
        ));
    }

    public function _getAECRegistration(): jsonResponse
    {
        $registrations_x = DB::Connection('mysql_dgo')->table('recepciones AS r')->select('r.id')
            ->join('paquetes AS p', 'p.id', '=', 'r.paquete_id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->join('actas_registro AS ar', 'r.id', '=', 'ar.recepcion_id')
            ->where('ar.registro', 1)
            ->where('p.eleccion_id', 3)
            ->where('c.cat_municipio_id', $this->municipio)
            ->count();

        // PROGRESS
        $registrations_progress = number_format((($registrations_x / ((int) config('elections.aec.' . $this->municipio))) * 100), 2, '.', ',');

        return response()->json(array("series" =>
            array("received" => $registrations_x, "expected" => config('elections.aec.' . $this->municipio), "progress" => $registrations_progress)
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
