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

        return $this->chart->donutChart()
            ->setTitle('Top 3 scorers of the team.')
            ->setSubtitle('Season 2021.')
            ->addData([20, 24, 30])
            ->setLabels(['Player 7', 'Player 10', 'Player 9'])
            ->toVue();
    }
}
