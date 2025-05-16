<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{

    public function dashboard()
    {
        $resultados = DB::table('reserva')
            ->join('voo', 'reserva.voo_id', '=', 'voo.id')
            ->join('passageiro', 'reserva.passageiro_id', '=', 'passageiro.id')
            ->join('cidades as origem', 'voo.origem_id', '=', 'origem.id')
            ->join('cidades as destino', 'voo.destino_id', '=', 'destino.id')
            ->select(
                'reserva.id',
                'reserva.numero_reserva',
                'reserva.preco',
                'voo.numero_voo',
                'voo.data',
                'origem.nome as origem_nome',
                'destino.nome as destino_nome',
                'passageiro.nome',
                'passageiro.nif'
            )
            ->get();

        return view('admin.dashboard', compact('resultados'));
    }
    
}
