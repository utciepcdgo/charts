<?php

namespace App\Http\Controllers\Stats\Dip;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DurangoController extends Controller
{
    public function index()
    {
        return Inertia::render('Stats/Durango', [
            'materialSupplied' => $this->getMaterialSupplied(),
            'packetsReceived' => $this->getPacketsReceived(),
            'aecRegistration' => $this->getAECRegistration(),
            // COMPUTOS
            'collatedPackets' => $this->getCollatedPackets(),
            'recountPackets' => $this->getRecountPackets(),

        ]);

    }

    public function getPacketsReceived()
    {
        $packetsReceived = array("series" =>
            array("received" => 95, "expected" => 100, "progress" => 72)
        );

        return json_encode($packetsReceived);
    }

    public function getMaterialSupplied()
    {
        $materialSuppliedChart = array("series" =>
            array("received" => 95, "expected" => 100, "progress" => 72)
        );

        return json_encode($materialSuppliedChart);
    }

    public function getAECRegistration()
    {
        $aecRegistration = array("series" =>
            array("received" => 95, "expected" => 100, "progress" => 72)
        );

        return json_encode($aecRegistration);
    }

    public function getCollatedPackets()
    {
        $collatedPackets = array("series" =>
            array("received" => 95, "expected" => 100, "progress" => 72)
        );

        return json_encode($collatedPackets);
    }

    public function getRecountPackets()
    {
        $recountPackets = array("series" =>
            array("received" => 95, "expected" => 100, "progress" => 72)
        );

        return json_encode($recountPackets);
    }
}
