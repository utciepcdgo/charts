<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HigherOrderCollectionProxy;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $municipio;
    protected $expected;

    public function __construct($municipio = 0)
    {
        $this->municipio = DB::Connection('sice_' . (str_pad($municipio, 2, '0', STR_PAD_LEFT)))->table('cat_municipios')->select('id')
            ->where('id', '=', $municipio)->get()->first();

//        $this->expected = config('elections.aec.' . $this->municipio->id, 5);

    }

    public function _isTheWay(): array
    {
        $distritos = DB::Connection('sice_' . str_pad($this->municipio->id, 2, '0', STR_PAD_LEFT))->table('cat_distritos')->select('distrito')
            ->where('cat_municipio_id', '=', $this->municipio->id)->get();

//        dd($this->municipio->id, $distritos->pluck('distrito')->toArray());

        // 1 = Distritos, 2 = Municipio
        if ($distritos->count() >= 1) return [1, $distritos->pluck('distrito')->toArray()];
        if ($distritos->count() == 0) return [2, [$this->municipio]];

        return ["Sin resultados"];
    }

    public function _getPacketsReceived(): JsonResponse
    {
        // TOTAL PAQUETES
        // @param $this->municipio: DURANGO
        $packets_x = DB::Connection('sice_' . str_pad($this->municipio->id, 2, '0', STR_PAD_LEFT))->table('paquetes AS p')
            ->select('p.id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->where('p.eleccion_id', '=', 3)
            ->whereIn(($this->_isTheWay()[0] == 1 ? 'c.cat_distrito_id' : 'c.cat_municipio_id'), $this->_isTheWay()[1])
            ->get()->count();

        // PAQUETES ENTREGADOS
        // @param $this->municipio: DURANGO
        $packets_y = DB::Connection('sice_' . str_pad($this->municipio->id, 2, '0', STR_PAD_LEFT))->table('paquetes AS p')
            ->select('p.id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->where('p.eleccion_id', '=', 3)
            ->where('p.estado', 1)
            ->whereIn(($this->_isTheWay()[0] == 1 ? 'c.cat_distrito_id' : 'c.cat_municipio_id'), $this->_isTheWay()[1])
            ->get()->count();

        // PROGRESS
        $packets_progress = $packets_y > 0 ? (number_format((($packets_y / $packets_x) * 100), 2, '.', ',')) : 0.00;

        return response()->json(array("series" =>
            array("received" => $packets_y, "expected" => $packets_x, "progress" => $packets_progress)
        ));
    }

    public function _getMaterialSupplied(): jsonResponse
    {
        // TOTAL PAQUETES
        $packets_x = DB::Connection('sice_' . str_pad($this->municipio->id, 2, '0', STR_PAD_LEFT))->table('paquetes AS p')
            ->select('p.id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->where('p.eleccion_id', '=', 3)
            ->whereIn(($this->_isTheWay()[0] == 1 ? 'c.cat_distrito_id' : 'c.cat_municipio_id'), $this->_isTheWay()[1])
            ->get()->count();

        // PAQUETES ENTREGADOS A CAES
        $packets_y = DB::Connection('sice_' . str_pad($this->municipio->id, 2, '0', STR_PAD_LEFT))->table('paquetes AS p')
            ->select('c.seccion', 'c.casilla', 'p.eleccion_id', 'ec.*')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->join('entrega_cae AS ec', 'p.id', '=', 'ec.paquete_id')
            ->where('p.eleccion_id', 3)
            ->where('ec.deleted_at', null)
            ->whereIn(($this->_isTheWay()[0] == 1 ? 'c.cat_distrito_id' : 'c.cat_municipio_id'), $this->_isTheWay()[1])
            ->get();

        // PROGRESS
        $packets_progress = $packets_y->count() > 0 ? (number_format((($packets_y->count() / $packets_x) * 100), 2, '.', ',')) : 0.00;

        return response()->json(array("series" =>
            array("received" => $packets_y->count(), "expected" => $packets_x, "progress" => $packets_progress)
        ));
    }

    public function _getAECRegistration(): jsonResponse
    {
        $registrations_x = DB::Connection('sice_' . str_pad($this->municipio->id, 2, '0', STR_PAD_LEFT))->table('recepciones AS r')->select('r.id')
            ->join('paquetes AS p', 'p.id', '=', 'r.paquete_id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->join('actas_registro AS ar', 'r.id', '=', 'ar.recepcion_id')
            ->where('ar.registro', 1)
            ->where('p.eleccion_id', 3)
            ->whereIn(($this->_isTheWay()[0] == 1 ? 'c.cat_distrito_id' : 'c.cat_municipio_id'), $this->_isTheWay()[1])
            ->count();

        // PROGRESS
        $registrations_progress = config('elections.aec.' . $this->municipio->id) > 0 ? (number_format((($registrations_x / (int)(config('elections.aec.' . $this->municipio->id))) * 100), 2, '.', ',')) : 0;

        return response()->json(array("series" =>
            array("received" => $registrations_x, "expected" => config('elections.aec.' . $this->municipio->id), "progress" => $registrations_progress)
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
