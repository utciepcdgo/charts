<?php

namespace App\Charts\Dip\DGO;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class RecountPacketsChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): array
    {
        $recuentos_x = DB::Connection('mysql_dgo')->table('rc_actas AS ra')
            ->join('recepciones AS r', 'ra.recepcion_id', '=', 'r.id')
            ->join('paquetes AS p', 'r.paquete_id', '=', 'p.id')
            ->where('ra.recuento_cotejo', '=', 'r')
            ->where('p.eleccion_id', 3)
            ->where('ra.status', 1)
            ->where('ra.captura', 1)
            ->count();

        $recuentos_y = DB::Connection('mysql_dgo')->table('rc_actas AS ra')
            ->join('recepciones AS r', 'ra.recepcion_id', '=', 'r.id')
            ->join('paquetes AS p', 'r.paquete_id', '=', 'p.id')
            ->where('ra.recuento_cotejo', '=', 'r')
            ->where('p.eleccion_id', 3)
            ->where('ra.status', 1)
            ->count();

        if ($recuentos_y == 0 && $recuentos_x == 0) {
            return $this->chart->donutChart()
                ->addData([])
                ->toVue();
        }

        return $this->chart->donutChart()
            ->addData([$recuentos_y, $recuentos_x])
            ->toVue();
    }
}
