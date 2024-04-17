<?php

namespace App\Charts\Dip\DGO;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class AECRegistrationChart
{
    protected LarapexChart $chart;
    protected int $municipio;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
        $this->municipio = 5;
    }

    public function build(): \Illuminate\Http\JsonResponse
    {
        $recepciones_x = DB::Connection('mysql_dgo')->table('recepciones AS r')->select('r.id')
            ->join('paquetes AS p', 'p.id', '=', 'r.paquete_id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->join('actas_registro AS ar', 'r.id', '=', 'ar.recepcion_id')
            ->where('ar.registro', 1)
            ->where('p.eleccion_id', 3)
            ->where('c.cat_municipio_id', $this->municipio)
            ->count();

        return $this->chart->donutChart()
            ->setTitle('Registro de AEC.')
            ->addData([$recepciones_x, (config('elections.casillas.mr') - $recepciones_x)])
            ->setLabels(['Actas registradas', 'Actas por registrar'])
            ->setColors(['#00B8D4', '#B0BEC5'])
            ->toJson();
    }
}
