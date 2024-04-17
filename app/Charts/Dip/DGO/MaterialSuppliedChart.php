<?php

namespace App\Charts\Dip\DGO;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class MaterialSuppliedChart
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
        // PAQUETES ENTREGADOS A CAES
        $material_x = DB::Connection('mysql_dgo')->table('paquetes AS p')
            ->select('c.seccion', 'c.casilla', 'p.eleccion_id', 'ec.*')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->join('entrega_cae AS ec', 'p.id', '=', 'ec.paquete_id')
            ->where('c.cat_municipio_id', $this->municipio)
            ->where('p.eleccion_id', 3)
            ->where('ec.deleted_at', null)
            ->get()->count();

        // TOTAL PAQUETES
        $paquetes_x = DB::Connection('mysql_dgo')->table('paquetes AS p')
            ->select('p.id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->where('p.eleccion_id', '=', 3)
            ->where('c.cat_municipio_id', '=', $this->municipio)
            ->get()->count();

        return $this->chart->donutChart()
            ->addData([$material_x, ($paquetes_x - $material_x)])
            ->toVue();
    }
}
