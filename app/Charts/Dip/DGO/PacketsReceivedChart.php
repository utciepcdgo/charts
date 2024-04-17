<?php

namespace App\Charts\Dip\DGO;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class PacketsReceivedChart
{
    protected LarapexChart $chart;
    protected int $municipio;


    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
        $this->municipio = 5;
    }

    public function build(): array
    {
        // TOTAL PAQUETES
        // @param $this->municipio: DURANGO
        $paquetes_x = DB::Connection('mysql_dgo')->table('paquetes AS p')
            ->select('p.id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->where('p.eleccion_id', '=', 3)
            ->where('c.cat_municipio_id', '=', $this->municipio)
            ->get();

        // PAQUETES ENTREGADOS
        // @param $this->municipio: DURANGO
        $paquetes_y = DB::Connection('mysql_dgo')->table('paquetes AS p')
            ->select('p.id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->where('p.eleccion_id', '=', 3)
            ->where('p.estado', 1)
            ->where('c.cat_municipio_id', '=', $this->municipio)
            ->get();

        return $this->chart->donutChart()
            ->setTitle('Paquetes recepcionados')
            ->addData([($paquetes_x->count() - $paquetes_y->count()), $paquetes_y->count()])
            ->setLabels(['Paquetes por entregar', 'Paquetes entregados'])
            ->setColors(['#B0BEC5', '#00C853'])
            ->toVue();
    }
}
