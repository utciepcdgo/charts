<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Reader;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Writer;
use Illuminate\Http\Request;
use App\Models\EntregaCAE;
use App\Models\User;
use App\Models\Casilla;
use App\Models\Municipio;
use App\Models\Distrito;
use Carbon\Carbon;

class DeliveryToPersonalController extends Controller
{
    public function bitacora(Request $request)
    {
        //Read file
        $inputFileName = base_path('resources/docs/templates/BitacoraEntregasCAE.xlsx');
        $reader = new Reader();

        $reader->setLoadSheetsOnly(["Hoja1", "Hoja2"]);
        $speadsheet1 = $reader->load($inputFileName);
        $hoja1 = $speadsheet1->getSheetByName("Hoja1");
        $hoja2 = $speadsheet1->getSheetByName("Hoja2");
        $hoja1->setTitle("Entregados");
        $hoja2->setTitle("Faltantes");
        $municipioName = env('MUNICIPIO');

        $municipio_id =  Municipio::select('id')
            ->where('municipio', env('MUNICIPIO'))
            ->first();

        $distritos = Distrito::select('distrito')
            ->where('cat_municipio_id', $municipio_id->id)
            ->get();

        //Write file
        $hoja1->setCellValue('D1', "Bitácora de Entrega de Paquete y  Material Electoral al CAE y/o SEL del Consejo Municipal Electoral de {$municipioName}, Durango");

        $entregas = EntregaCAE::select('fecha', 'seccion', 'casilla', 'users.nombre AS entreganombre', 'users.apellido AS entregaapellido', 'users.id as entrega_id', 'personal.nombre AS recibenombre', 'personal.apellido AS recibeapellido', 'cargo.cargo')
            ->join('users', 'entrega_cae.entrega_id', '=', 'users.id')
            ->join('personal', 'entrega_cae.recibe_id', '=', 'personal.id')
            ->join('cargo', 'personal.cargo_id', '=', 'cargo.id')
            ->join('paquetes', 'entrega_cae.paquete_id', '=', 'paquetes.id')
            ->join('casillas', 'paquetes.casilla_id', '=', 'casillas.id')
            ->orderBy('seccion')
            ->orderByRaw('LENGTH(casilla) ASC')
            ->orderByRaw('casilla')
            ->orderBy('fecha')
            ->get();

        $faltantes = Casilla::select('seccion', 'casilla', 'cat_distrito_id', 'cat_municipios.municipio')
            ->join('cat_municipios', 'casillas.cat_municipio_id', '=', 'cat_municipios.id')
            ->join('paquetes', 'paquetes.casilla_id', '=', 'casillas.id')
            ->leftJoin('entrega_cae', 'entrega_cae.paquete_id', '=', 'paquetes.id')
            ->where(function($query) use ($distritos, $municipio_id){
                if(count($distritos) > 0){
                    foreach($distritos as $distrito){
                        $query->orWhere('casillas.cat_distrito_id', $distrito->distrito);
                    }
                }elseif(count($distritos) == 0){
                    $query->where('casillas.cat_municipio_id', $municipio_id->id);
                }
            })
            ->whereNull('entrega_cae.id')
            ->orderBy('seccion')
            ->orderByRaw('LENGTH(casilla) ASC')
            ->orderBy('casilla')
            ->get();

        // return $faltantes->count();

        foreach ($entregas as $key => $entrega) {
            $pentrega = User::findOrFail($entrega->entrega_id)->getRoleNames();
            $entrega->fecha = Carbon::make($entrega->fecha);
            $hoja1->setCellValue('A5', $entrega->fecha->format('Y-m-d'));
            $hoja1->setCellValue('B5', $entrega->fecha->format('H:i:s'));
            $hoja1->setCellValue('C5', $entrega->seccion);
            $hoja1->setCellValue('D5', $entrega->casilla);
            $hoja1->setCellValue('E5', $entrega->entreganombre.$entrega->entregaapellido);
            $hoja1->setCellValue('F5', $pentrega->implode('\n'));
            $hoja1->setCellValue('G5', $entrega->recibenombre.$entrega->recibeapellido);
            $hoja1->setCellValue('H5', $entrega->cargo);

            if($key < $entregas->count() - 1){
                $hoja1->insertNewRowBefore(5, 1);
            }
        }
        $hoja1->removeRow(4, 1);

        foreach ($faltantes as $key => $casilla) {
            $hoja2->setCellValue('A5', $casilla->seccion);
            $hoja2->setCellValue('B5', $casilla->casilla);
            $hoja2->setCellValue('C5', $casilla->cat_distrito_id);
            $hoja2->setCellValue('D5', $casilla->municipio);

            if($key < $faltantes->count() - 1){
                $hoja2->insertNewRowBefore(5, 1);
            }
        }
        $hoja2->removeRow(4, 1);

        $speadsheet1->setActiveSheetIndex(0);

        //Download file
        $writer = new Writer($speadsheet1);
        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.preg_replace('/[^a-z]/', "", strtolower($municipioName)).'_entregascae_'.time().'.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');
        return $response;
    }
}
