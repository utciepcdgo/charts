<?php

namespace App\Charts\Dip\DGO;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class CollatedPacketsChart
{
    protected int $municipio;
    protected LarapexChart $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
        $this->municipio = 5;
    }

    public function build(): array
    {

        $collated_x = DB::Connection('mysql_dgo')->table('rc_actas AS ra')->select('ra.id')
            ->join('recepciones AS r', 'ra.recepcion_id', '=', 'r.id')
            ->join('paquetes AS p', 'r.paquete_id', '=', 'p.id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->where('ra.recuento_cotejo', '=', 'c')
            ->where('p.eleccion_id', 3)
            ->where('ra.status', 1)
            ->where('ra.captura', 1)
            ->count();

        $collated_y = DB::Connection('mysql_dgo')->table('rc_actas AS ra')->select('ra.id')
            ->join('recepciones AS r', 'ra.recepcion_id', '=', 'r.id')
            ->join('paquetes AS p', 'r.paquete_id', '=', 'p.id')
            ->where('ra.recuento_cotejo', '=', 'c')
            ->where('p.eleccion_id', 3)
            ->where('ra.status', 1)
            ->count();

        if ($collated_y == 0 && $collated_x == 0) {
            return $this->chart->donutChart()
                ->addData([])
                ->toVue();
        }

        return $this->chart->donutChart()
            ->addData([$collated_y, $collated_x])
            ->toVue();
    }
}
