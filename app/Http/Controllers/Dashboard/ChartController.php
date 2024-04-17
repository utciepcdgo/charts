<?php

namespace App\Http\Controllers\Dashboard;

use App\Charts\Dip\DGO\AECRegistrationChart;
use App\Charts\Dip\DGO\CollatedPacketsChart;
use App\Charts\Dip\DGO\MaterialSuppliedChart;
use App\Charts\Dip\DGO\PacketsReceivedChart;
use App\Charts\Dip\DGO\RecountPacketsChart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ChartController extends Controller
{

    public function index(MaterialSuppliedChart $chart_packages_dgo,
                          PacketsReceivedChart $chart_packets_received_dgo,
                          AECRegistrationChart $chart_aec_registration_dgo,
                          RecountPacketsChart $chart_recount_packets_dgo,
                          CollatedPacketsChart $chart_collated_packets_dgo)
    {
        return Inertia::render('Dashboard', [
            'x_chart_packages_dgo'         => $chart_packages_dgo->build(),             // Material supplied
            'x_chart_packets_received_dgo' => $chart_packets_received_dgo->build(),     // Packets received
            'x_chart_aec_registration_dgo' => $chart_aec_registration_dgo->build(),     // AEC registration
            'x_chart_collated_packets_dgo' => $chart_collated_packets_dgo->build(),     // Collated packets
            'x_chart_recount_packets_dgo'  => $chart_recount_packets_dgo->build(),      // Recount packets
        ]);
    }
}
