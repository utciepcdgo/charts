<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class PacketsReceivedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, Queueable;

    private int $municipio;

    public function __construct()
    {
        $this->municipio = 5;
    }

    public function broadcastWith(): array
    {

        $paquetes_x = DB::Connection('mysql_dgo')->table('paquetes AS p')
            ->select('p.id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->where('p.eleccion_id', '=', 3)
            ->where('c.cat_municipio_id', '=', $this->municipio)
            ->count();

        // PAQUETES ENTREGADOS
        // @param $this->municipio: DURANGO
        $paquetes_y = DB::Connection('mysql_dgo')->table('paquetes AS p')
            ->select('p.id')
            ->join('casillas AS c', 'c.id', '=', 'p.casilla_id')
            ->where('p.eleccion_id', '=', 3)
            ->where('p.estado', 1)
            ->where('c.cat_municipio_id', '=', $this->municipio)
            ->count();


        return [
            'received' => [$paquetes_x, $paquetes_y],
        ];
    }

    public function broadcastOn(): Channel
    {
        return new Channel('packets-received-dgo');
    }
}
