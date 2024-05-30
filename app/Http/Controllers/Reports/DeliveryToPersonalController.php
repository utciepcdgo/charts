<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Reader;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Writer;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DeliveryToPersonalController extends Controller
{
    public function DeliveryLog(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

        //Read file
        $inputFileName = base_path('resources/docs/templates/BitacoraEntregasCAE.xlsx');
        $reader = new Reader();

        $reader->setLoadSheetsOnly(["Hoja1", "Hoja2"]);
        $speadsheet1 = $reader->load($inputFileName);
        $hoja1 = $speadsheet1->getSheetByName("Hoja1");
        $hoja2 = $speadsheet1->getSheetByName("Hoja2");
        $hoja1->setTitle("Entregados");
        $hoja2->setTitle("Faltantes");
        $municipioName = config('elections.cme.' . $this->municipio);

        $municipio_id = $this->municipio;

        $distritos = DB::Connection($this->database)->table('cat_distritos')->select('distrito')->where('cat_municipio_id', $municipio_id)->get();

        //Write file
        $hoja1->setCellValue('D1', "Bitácora de Entrega de Paquete y  Material Electoral al CAE y/o SEL del Consejo Municipal Electoral de {$municipioName}, Durango");

        $entregas = DB::Connection($this->database)->table('entrega_cae AS ec')->select('ec.fecha', 'ca.seccion', 'ca.casilla', 'u.nombre AS entreganombre', 'u.apellido AS entregaapellido', 'u.id as entrega_id', 'p.nombre AS recibenombre', 'p.apellido AS recibeapellido', 'c.cargo')
            ->join('users AS u', 'ec.entrega_id', '=', 'u.id')
            ->join('personal AS p', 'ec.recibe_id', '=', 'p.id')
            ->join('cargo AS c', 'p.cargo_id', '=', 'c.id')
            ->join('paquetes AS pq', 'ec.paquete_id', '=', 'pq.id')
            ->join('casillas AS ca', 'pq.casilla_id', '=', 'ca.id')
            ->orderBy('ca.seccion')
            ->orderByRaw('LENGTH(casilla) ASC')
            ->orderByRaw('ca.casilla')
            ->orderBy('ec.fecha')
            ->get();

        $faltantes = DB::Connection($this->database)->table('casillas AS ca')->select('ca.seccion', 'ca.casilla', 'ca.cat_distrito_id', 'm.municipio')
            ->join('cat_municipios AS m', 'ca.cat_municipio_id', '=', 'm.id')
            ->join('paquetes AS pq', 'pq.casilla_id', '=', 'ca.id')
            ->leftJoin('entrega_cae AS ec', 'ec.paquete_id', '=', 'pq.id')
            ->where(function ($query) use ($distritos, $municipio_id) {
                if (count($distritos) > 0) {
                    foreach ($distritos as $distrito) {
                        $query->orWhere('ca.cat_distrito_id', $distrito->distrito);
                    }
                } elseif (count($distritos) == 0) {
                    $query->where('ca.cat_municipio_id', $municipio_id->id);
                }
            })
            ->whereNull('ec.id')
            ->orderBy('ca.seccion')
            ->orderByRaw('LENGTH(casilla) ASC')
            ->orderBy('ca.casilla')
            ->get();

        // return $faltantes->count();

        foreach ($entregas as $key => $entrega) {
            $pentrega = DB::Connection($this->database)->table('users')->select('roles.name as role')
                ->join('model_has_roles', function ($join) {
                    $join->on('users.id', '=', 'model_has_roles.model_id')
                        ->where('model_has_roles.model_type', User::class);
                })
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('users.id', '=', $entrega->entrega_id)->get()->first();
            $entrega->fecha = Carbon::make($entrega->fecha);
            $hoja1->setCellValue('A5', $entrega->fecha->format('Y-m-d'));
            $hoja1->setCellValue('B5', $entrega->fecha->format('H:i:s'));
            $hoja1->setCellValue('C5', $entrega->seccion);
            $hoja1->setCellValue('D5', $entrega->casilla);
            $hoja1->setCellValue('E5', $entrega->entreganombre . $entrega->entregaapellido);
            $hoja1->setCellValue('F5', $pentrega->role);
            $hoja1->setCellValue('G5', $entrega->recibenombre);
            $hoja1->setCellValue('H5', $entrega->cargo);

            if ($key < $entregas->count() - 1) {
                $hoja1->insertNewRowBefore(5, 1);
            }
        }
        $hoja1->removeRow(4, 1);

        foreach ($faltantes as $key => $casilla) {
            $hoja2->setCellValue('A5', $casilla->seccion);
            $hoja2->setCellValue('B5', $casilla->casilla);
            $hoja2->setCellValue('C5', $casilla->cat_distrito_id);
            $hoja2->setCellValue('D5', $casilla->municipio);

            if ($key < $faltantes->count() - 1) {
                $hoja2->insertNewRowBefore(5, 1);
            }
        }
        $hoja2->removeRow(4, 1);

        $speadsheet1->setActiveSheetIndex(0);

        //Download file
        $writer = new Writer($speadsheet1);

        $writer->save(base_path() . "/resources/docs/templates/doc.xlsx");

        $file = base_path() . "/resources/docs/templates/doc.xlsx";

        $headers = array(
            'Content-Type: application/vnd.ms-excel',
        );


        $filename = str_replace(' ', '_', strtoupper($municipioName)) . '_ENTREGA_CAE_' . time();
//
//
        return response()->download($file, $filename . '.xlsx', $headers);

//        Storage::disk('public')->putFileAS('/BODEGA', $file, $filename . '.xlsx');

//        Download file
//        $writer = new Writer($speadsheet1);
//        $response =  new StreamedResponse(
//            function () use ($writer) {
//                $writer->save('php://output');
//            }
//        );
//        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
//        $response->headers->set('Content-Disposition', 'attachment;filename="'.preg_replace('/[^a-z]/', "", strtolower($municipioName)).'_entregascae_'.time().'.xlsx"');
//        $response->headers->set('Cache-Control', 'max-age=0');
//
//        dd($response);
//
//        return $response;

//        $writer = new Xlsx($speadsheet1);
//        $writer->save('php://output');
//
//
//        $response = new BinaryFileResponse('php://output');
//        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        $response->setContentDisposition('attachment', 'nombre_archivo.xlsx');

// Enviar la respuesta
        return $response;
    }
}
