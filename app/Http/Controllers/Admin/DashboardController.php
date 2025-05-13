<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{

    public function dashboard(): View
    {
        $resultados = DB::table('reserva')
            ->join('voo', 'reserva.voo_id', '=', 'voo.id')
            ->join('passageiro', 'reserva.passageiro_id', '=', 'passageiro.id')
            ->select(
                'reserva.id',
                'reserva.numero_reserva',
                'reserva.preco',
                'voo.numero_voo',
                'voo.data',
                'voo.origem',
                'voo.destino',
                'passageiro.nome',
                'passageiro.nif'
            )
            ->get();

        return view('admin.dashboard', compact('resultados'));
    }
}
