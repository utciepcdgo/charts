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
use Symfony\Component\HttpFoundation\StreamedResponse;

class CollateRecountPacketsListController extends Controller
{
    //
    /**
     * @throws CannotInsertRecord
     * @throws Exception
     */
    public function _getAECCollateCSV(Request $request): StreamedResponse
    {
        // First, get base data
        $municipioName = config('elections.cme.' . $request->municipio_id);
        $municipio_id = $request->municipio_id;
        $this->database = 'sice_' . str_pad($municipio_id, 2, '0', STR_PAD_LEFT);

        $collates_x = DB::Connection($this->database)->table('rc_actas AS rc')->select('c.seccion', 'c.casilla', 'c.cat_distrito_id', "rc.principio")
            ->join('recepciones AS r', 'rc.recepcion_id', '=', 'r.id')
            ->join('paquetes AS p', 'r.paquete_id', '=', 'p.id')
            ->join('casillas AS c', 'p.casilla_id', '=', 'c.id')
            ->where('recuento_cotejo', '=', $request->type_of_records === "1" ? 'c' : 'r')
            ->where('rc.status', '=', 1)
            ->where('p.eleccion_id', '=', 3)
            ->orderBy('c.seccion')
            ->orderByRaw('LENGTH(c.casilla) ASC')
            ->get();

        $encoder = (new CharsetConverter())
            ->inputEncoding('utf-8')
            ->outputEncoding('iso-8859-15');

        // Create the CSV file using league/csv package.
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        // Convert the CSV file to UTF-8 encoding.
        $csv->addFormatter($encoder);

        // Insert the headers.
        $csv->insertOne(['SECCION', 'CASILLA', 'DISTRITO', 'PRINCIPIO', 'TIPO_ELECCION']);

        // Insert the data.
        foreach ($collates_x as $collate) {
            $csv->insertOne([$collate->seccion, $collate->casilla, $collate->cat_distrito_id, $collate->principio, "DIPUTACIONES"]);
        }

        // Return the CSV file.
        return response()->streamDownload(function () use ($request, $csv, $municipioName) {
            $csv->output(str_replace(' ', '_', strtoupper($municipioName)) . '_LISTADO_'. ($request->type_of_records === "1" ? 'COTEJO' : 'RECUENTO') .'_' . Carbon::now()->format('d-m-Y_H-s') . '.csv');
        });
    }
}
