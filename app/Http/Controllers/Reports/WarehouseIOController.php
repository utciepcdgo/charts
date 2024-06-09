<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Csv\CannotInsertRecord;
use League\Csv\CharsetConverter;
use League\Csv\Exception;
use League\Csv\Writer;

class WarehouseIOController extends Controller
{
    /**
     * @throws CannotInsertRecord
     * @throws Exception
     */
    public function WarehouseIO(Request $request)
    {
        // First, get base data
        $municipioName = config('elections.cme.' . $request->municipio_id);
        $municipio_id = $request->municipio_id;
        $this->database = 'sice_' . str_pad($municipio_id, 2, '0', STR_PAD_LEFT);


        $logs = DB::Connection($this->database)->table('bitacora_bodega AS bb')->select('b.paquete_id', 'c.seccion', 'c.casilla', 'bb.entrada_salida', 'bb.fecha', 'bb.motivo')
            ->join('bodega AS b', 'b.id', '=', 'bb.bodega_id')
            ->join('paquetes AS p', 'p.id', '=', 'b.paquete_id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->orderBy('bb.fecha', 'DESC')
            ->get();

        $encoder = (new CharsetConverter())
            ->inputEncoding('utf-8')
            ->outputEncoding('iso-8859-15');

        // Create the CSV file using league/csv package.
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        // Convert the CSV file to UTF-8 encoding.
        $csv->addFormatter($encoder);

        // Insert the headers.
        $csv->insertOne(['SECCION', 'CASILLA', 'ENTRADA_SALIDA', "FECHA", 'MOTIVO']);

        // Insert the data.
        foreach ($logs as $log) {
            $csv->insertOne([$log->seccion, $log->casilla, $log->entrada_salida, $log->fecha, $log->motivo]);
        }

        // Return the CSV file.
        return response()->streamDownload(function () use ($request, $csv, $municipioName) {
            $csv->output(str_replace(' ', '_', strtoupper($municipioName)) . '_BITACORA_BODEGA_' . Carbon::now()->format('d-m-Y_H-s') . '.csv');
        });


        // return $bitacora->count() - 1;
    }
}
