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

class AECRecordsController extends Controller
{
    // Create a new method to retrieve CSV data related to the AEC registration.
    /**
     * @throws CannotInsertRecord
     * @throws Exception
     */
    public function _getAECRegistrationCSV(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $parentClass = new Controller($request->municipio_id);

        // First, get base data
        $municipioName = config('elections.cme.' . $request->municipio_id);
        $municipio_id = $request->municipio_id;
        $this->database = 'sice_' . str_pad($municipio_id, 2, '0', STR_PAD_LEFT);

        $registrations_x = DB::Connection($this->database)->table('recepciones AS r')->select('r.id', 'r.paquete_id', 'r.fecha', 'c.seccion', 'c.casilla', 'r.muestra_alteracion', 'r.firmado', 'r.cinta_etiqueta_seguridad', 'r.sobre_prep', 'r.acta_por_fuera', 'p1.nombre AS enombre', 'p2.nombre AS rnombre')
            ->join('personal AS p1', 'r.entrega_id', '=', 'p1.id')
            ->join('personal AS p2', 'r.recibe_id', '=', 'p2.id')
            ->join('paquetes AS p', 'r.paquete_id', '=', 'p.id')
            ->join('casillas AS c', 'p.casilla_id', '=', 'c.id')
            ->join('elecciones AS e', 'p.eleccion_id', '=', 'e.id')
            ->where('r.status', '=', 1)
            ->where('r.deleted_at', '=', null)
            ->where('p.eleccion_id', '=', 3) //variable
            ->whereIn(($parentClass->_isTheWay()[0] == 1 ? 'c.cat_distrito_id' : 'c.cat_municipio_id'), $parentClass->_isTheWay()[1])
            ->get();

        $encoder = (new CharsetConverter())
            ->inputEncoding('utf-8')
            ->outputEncoding('iso-8859-15');

        // Create the CSV file using league/csv package.
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        // Convert the CSV file to UTF-8 encoding.
        $csv->addFormatter($encoder);

        // Insert the headers.
        $csv->insertOne(['FECHA_RECEPCION', 'HORA_RECEPCION', 'SECCION', 'CASILLA', 'CON_MUESTRA_ALTERACION', 'FIRMADO', 'CINTA_ETIQUETA_SEGURIDAD', 'SOBRE_PREP', 'ACTA_POR_FUERA', 'NOMBRE_ENTREGA', 'NOMBRE_RECIBE']);

        // Insert the data.
        foreach ($registrations_x as $registration) {
            $csv->insertOne([Carbon::parse($registration->fecha)->format('d/m/Y'), Carbon::parse($registration->fecha)->format('H:i'), $registration->seccion, $registration->casilla, $registration->muestra_alteracion === 1 ? "SÍ" : "NO", $registration->firmado === 1 ? "SÍ" : "NO", $registration->cinta_etiqueta_seguridad === 1 ? "SÍ" : "NO", $registration->sobre_prep === 1 ? "SÍ" : "NO", $registration->acta_por_fuera === 1 ? "SÍ" : "NO", $registration->enombre, $registration->rnombre]);
        }

        // Return the CSV file.
        return response()->streamDownload(function () use ($csv, $municipioName) {
            $csv->output(str_replace(' ',  '_', strtoupper($municipioName)) . '_BITACORA_RECEPCION_' . Carbon::now()->format('d-m-Y_H-i-s') . '.csv');
        });
    }
}
