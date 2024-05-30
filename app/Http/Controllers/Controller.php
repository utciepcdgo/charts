<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $municipio;
    public int $casillas;

    public function __construct($municipio = 5)
    {
        $this->casillas = config('elections.aec.' . $municipio);
        $this->municipio = $municipio;
    }

    public function _isTheWay(): array
    {
        $distritos = DB::Connection('sice_' . str_pad($this->municipio, 2, '0', STR_PAD_LEFT))->table('cat_distritos')->select('distrito')
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
        $packets_x = DB::Connection('sice_' . str_pad($this->municipio, 2, '0', STR_PAD_LEFT))->table('paquetes AS p')
            ->select('p.id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->where('p.eleccion_id', '=', 3)
            ->where('p.estado', 1)
            ->whereIn(($this->_isTheWay()[0] == 1 ? 'c.cat_distrito_id' : 'c.cat_municipio_id'), $this->_isTheWay()[1])
            ->get()->count();

        // PROGRESS
        $packets_progress = $packets_x > 0 ? (number_format((($packets_x / $this->casillas) * 100), 2, '.', ',')) : 0.00;

        return response()->json(array("series" =>
            array("received" => $packets_x, "expected" => $this->casillas, "progress" => $packets_progress)
        ));
    }

    public function _getMaterialSupplied(): jsonResponse
    {
        // PAQUETES ENTREGADOS A CAES
        $packets_x = DB::Connection('sice_' . str_pad($this->municipio, 2, '0', STR_PAD_LEFT))->table('paquetes AS p')
            ->select('c.seccion', 'c.casilla', 'p.eleccion_id', 'ec.*')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->join('entrega_cae AS ec', 'p.id', '=', 'ec.paquete_id')
            ->where('p.eleccion_id', 3)
            ->where('ec.deleted_at', null)
            ->whereIn(($this->_isTheWay()[0] == 1 ? 'c.cat_distrito_id' : 'c.cat_municipio_id'), $this->_isTheWay()[1])
            ->get();

//        dd($packets_x);

        // PROGRESS
        $packets_progress = number_format((($packets_x->count() / $this->casillas) * 100), 2, '.', ',');

        return response()->json(array("series" =>
            array("received" => $packets_x->count(), "expected" => $this->casillas, "progress" => $packets_progress)
        ));
    }

    public function _getAECRegistration(): jsonResponse
    {
        $registrations_x = DB::Connection('sice_' . str_pad($this->municipio, 2, '0', STR_PAD_LEFT))->table('recepciones AS r')->select('r.id')
            ->join('paquetes AS p', 'p.id', '=', 'r.paquete_id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->join('actas_registro AS ar', 'r.id', '=', 'ar.recepcion_id')
            ->where('ar.registro', 1)
            ->where('p.eleccion_id', 3)
            ->whereIn(($this->_isTheWay()[0] == 1 ? 'c.cat_distrito_id' : 'c.cat_municipio_id'), $this->_isTheWay()[1])
            ->count();

        // PROGRESS
        $registrations_progress = number_format((($registrations_x / $this->casillas) * 100), 2, '.', ',');

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

    public function _getWarehouseLog()
    {
        $bitacora = DB::Connection('sice_' . str_pad($this->municipio, 2, '0', STR_PAD_LEFT))->table('bitacora_bodega')->select('bodega.paquete_id', 'casillas.seccion', 'casillas.casilla', 'bitacora_bodega.*')
            ->join('bodega', 'bodega.id', '=', 'bodega_id')
            ->join('paquetes', 'paquetes.id', '=', 'paquete_id')
            ->join('casillas', 'casillas.id', '=', 'paquetes.casilla_id')
            ->get();

        // Create a new Excel file using template file at resources/docs/templates/BitacoraEntradaSalida.xlsx and PHPSpreadsheet library,
        // then fill it with data from $bitacora and return it as a StreamedResponse.

        $template = base_path('resources/docs/templates/BitacoraEntradaSalida.xlsx');
        $reader = new Xlsx();

        $reader->setLoadSheetsOnly(["Hoja1"]);
        $spreadsheet = $reader->load($template);

        $sheet = $spreadsheet->getActiveSheet();

        // Setting the header
        $sheet->setCellValue('C4', "Entidad Federativa: Durango. Consejo Municipal Electoral de: " . config('elections.cme.' . $this->municipio));

        // Setting the table content
        foreach ($bitacora as $key => $registro) {

            if ($key < $bitacora->count() + 1) {
                $sheet->insertNewRowBefore(8);
                $sheet->mergeCells('G8:L8');
            }

            $fecha = Carbon::make($registro->created_at);

            $sheet->setCellValue('C8', $fecha->format('Y-m-d'));
            $sheet->setCellValue('D8', $fecha->format('H:i:s'));
            $sheet->setCellValue('F8', $registro->seccion . ' ' . $registro->casilla);
            $sheet->setCellValue('G8', $registro->motivo);


            ($registro->entrada_salida == 'Registro') ? $sheet->setCellValue('A8', 'X') : $sheet->setCellValue('B8', 'X');
        }

        $sheet->removeRow(7);

        // Save the file and return it as a StreamedResponse
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save(base_path() . '/public/docs/temp.xlsx');

        $file = base_path() . '/public/docs/temp.xlsx';

        $headers = array(
            'Content-Type: application/vnd.ms-excel',
        );

        $filename = str_replace(' ', '_', strtoupper(config('elections.cme.' . $this->municipio))) . '_BITACORA_BODEGA_' . time();


        return response()->download($file, $filename . '.xlsx', $headers);
    }
}
