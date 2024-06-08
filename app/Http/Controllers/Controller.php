<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected int $municipio;
    protected int $casillas;
    protected string $database;
    protected bool $useStaticDataStepOne;

    public function __construct($municipio = 5)
    {
        $this->casillas = config('elections.aec.' . $municipio . '.recepcion');
        $this->municipio = $municipio;
        $this->database = 'sice_' . str_pad($municipio, 2, '0', STR_PAD_LEFT);

        // Override the database connections using static data. For this, we use the config/elections file.
        // It is necessary to have completed all stages of the "JE" at 100%.
        $this->useStaticDataStepOne = true;
    }

    public function _isTheWay(): array
    {
        $distritos = DB::Connection($this->database)->table('cat_distritos')->select('distrito')
            ->where('cat_municipio_id', '=', $this->municipio)->get();

        // 1 = Distritos, 2 = Municipio
        if ($distritos->count() >= 1) return [1, $distritos->pluck('distrito')->toArray()];
        if ($distritos->count() == 0) return [2, [$this->municipio]];

        return ["Sin resultados"];
    }

    public function _getPacketsReceived(): JsonResponse
    {

        // PAQUETES ENTREGADOS
        // @param $this->municipio: DURANGO
        if ($this->useStaticDataStepOne) {
            $packets_x = config('elections.aec.' . $this->municipio . '.recepcion');
        } else {
            $packets_x = DB::Connection($this->database)->table('paquetes AS p')
                ->select('p.id')
                ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
                ->where('p.eleccion_id', '=', 3)
                ->where('p.estado', 1)
                ->whereIn(($this->_isTheWay()[0] == 1 ? 'c.cat_distrito_id' : 'c.cat_municipio_id'), $this->_isTheWay()[1])
                ->get()->count();
        }

        // PROGRESS
        $packets_progress = $packets_x > 0 ? (number_format((($packets_x / $this->casillas) * 100), 2, '.', ',')) : 0.00;

        return response()->json(array("series" =>
            array("received" => $packets_x, "expected" => $this->casillas, "progress" => $packets_progress)
        ));
    }

    public function _getMaterialSupplied(): jsonResponse
    {
        // PAQUETES ENTREGADOS A CAES
        if ($this->useStaticDataStepOne) {
            $packets_x = config('elections.aec.' . $this->municipio . '.recepcion');
        } else {
            $packets_x = DB::Connection($this->database)->table('paquetes AS p')
                ->select('c.seccion', 'c.casilla', 'p.eleccion_id', 'ec.*')
                ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
                ->join('entrega_cae AS ec', 'p.id', '=', 'ec.paquete_id')
                ->where('p.eleccion_id', 3)
                ->where('ec.deleted_at', null)
                ->whereIn(($this->_isTheWay()[0] == 1 ? 'c.cat_distrito_id' : 'c.cat_municipio_id'), $this->_isTheWay()[1])
                ->get();
        }

        // PROGRESS
        $packets_progress = number_format(((($this->useStaticDataStepOne ? $packets_x : $packets_x->count()) / $this->casillas) * 100), 2, '.', ',');

        return response()->json(array("series" =>
            array("received" => ($this->useStaticDataStepOne ? $packets_x : $packets_x->count()), "expected" => $this->casillas, "progress" => $packets_progress)
        ));
    }

    public function _getAECRegistration(): jsonResponse
    {
        if ($this->useStaticDataStepOne) {
            $registrations_x = config('elections.aec.' . $this->municipio . '.registro');
        } else {
            $registrations_x = DB::Connection($this->database)->table('recepciones AS r')->select('r.id')
                ->join('paquetes AS p', 'p.id', '=', 'r.paquete_id')
                ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
                ->join('actas_registro AS ar', 'r.id', '=', 'ar.recepcion_id')
                ->where('ar.registro', 1)
                ->where('p.eleccion_id', 3)
                ->whereIn(($this->_isTheWay()[0] == 1 ? 'c.cat_distrito_id' : 'c.cat_municipio_id'), $this->_isTheWay()[1])
                ->count();
        }

        // PROGRESS
        $registrations_progress = number_format((($registrations_x / config('elections.aec.' . $this->municipio . '.registro')) * 100), 2, '.', ',');

        return response()->json(array("series" =>
            array("received" => $registrations_x, "expected" => config('elections.aec.' . $this->municipio . '.registro'), "progress" => $registrations_progress)
        ));
    }

    public function _getCollatedPackets(): jsonResponse
    {
        $collated_x = DB::Connection($this->database)->table('rc_actas AS ac')->select('ac.id')
            ->join('recepciones AS r', 'ac.recepcion_id', '=', 'r.id')
            ->join('paquetes AS p', 'r.paquete_id', '=', 'p.id')
            ->where('ac.recuento_cotejo', '=', 'c')
            ->where('p.eleccion_id', 3)
            ->where('ac.status', 1)
            ->where('ac.captura', 1)
            ->count();

        $collated_y = DB::Connection($this->database)->table('rc_actas AS ac')->select('ac.id')
            ->join('recepciones AS r', 'ac.recepcion_id', '=', 'r.id')
            ->join('paquetes AS p', 'r.paquete_id', '=', 'p.id')
            ->where('ac.recuento_cotejo', '=', 'c')
            ->where('p.eleccion_id', 3)
            ->where('ac.status', 1)
            ->count();

        return response()->json(array("series" =>
            array("received" => $collated_x, "expected" => $collated_y, "progress" => 0)
        ));
    }

    public function _getRecountPackets(): jsonResponse
    {
        $recount_x = DB::Connection($this->database)->table('rc_actas AS ac')->select('ac.id')
            ->join('recepciones AS r', 'ac.recepcion_id', '=', 'r.id')
            ->join('paquetes AS p', 'r.paquete_id', '=', 'p.id')
            ->where('ac.recuento_cotejo', '=', 'r')
            ->where('p.eleccion_id', 3)
            ->where('ac.status', 1)
            ->where('ac.captura', 1)
            ->count();

        $recount_y = DB::Connection($this->database)->table('rc_actas AS ac')->select('ac.id')
            ->join('recepciones AS r', 'ac.recepcion_id', '=', 'r.id')
            ->join('paquetes AS p', 'r.paquete_id', '=', 'p.id')
            ->where('ac.recuento_cotejo', '=', 'r')
            ->where('p.eleccion_id', 3)
            ->where('ac.status', 1)
            ->count();

        return response()->json(array("series" =>
            array("received" => $recount_x, "expected" => $recount_y, "progress" => 72)
        ));
    }

}
