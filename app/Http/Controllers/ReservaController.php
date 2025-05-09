<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Voo;
use App\Models\Passageiro;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Captura apenas campos com valores (filtros aplicados)
        $filtros = array_filter($request->all());

        // Inicia a consulta com joins
        $query = DB::table('reserva')
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
            );

        // Aplica filtros se fornecidos
        if (!empty($filtros['voo'])) {
            $query->where('voo.numero_voo', 'like', '%' . $filtros['voo'] . '%');
        }

        if (!empty($filtros['data'])) {
            $query->whereDate('voo.data', $filtros['data']);
        }

        if (!empty($filtros['origem'])) {
            $query->where('voo.origem', 'like', '%' . $filtros['origem'] . '%');
        }

        if (!empty($filtros['destino'])) {
            $query->where('voo.destino', 'like', '%' . $filtros['destino'] . '%');
        }

        if (!empty($filtros['reserva'])) {
            $query->where('reserva.numero_reserva', 'like', '%' . $filtros['reserva'] . '%');
        }

        if (!empty($filtros['name'])) {
            $query->where('passageiro.nome', 'like', '%' . $filtros['name'] . '%');
        }

        if (!empty($filtros['nif'])) {
            $query->where('passageiro.nif', $filtros['nif']);
        }

        if (!empty($filtros['cc'])) {
            $query->where('passageiro.identificacao', 'like', '%' . $filtros['cc'] . '%');
        }

        // Executa a busca
        $resultados = $query->paginate(2);

        // Mantém os filtros na paginação
        $resultados->appends($filtros);

        // Retorna a view com os resultados e mantém os filtros para preencher o formulário
        return view('index', [
            'resultados' => $resultados,
            'filtros' => $filtros
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $vooId = $request->input('voo_id');

        $totalReservas = Reserva::where('voo_id', $vooId)->count();

        if ($totalReservas >= 220) {
            return back()->withErrors(['voo_id' => 'Este voo já atingiu o limite de 220 reservas.']);
        }

        Reserva::create($request->all());
        return redirect()->route('reservas.index')->with('success', 'Reserva criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function buscar(Request $request)
    {
        // Captura apenas campos com valores (filtros aplicados)
        $filtros = array_filter($request->all());

        // Inicia a consulta com joins
        $query = DB::table('reserva')
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
            );

        // Aplica filtros dinamicamente
        if (!empty($filtros['voo'])) {
            $query->where('voo.numero_voo', 'like', '%' . $filtros['voo'] . '%');
        }

        if (!empty($filtros['data'])) {
            $query->whereDate('voo.data', $filtros['data']);
        }

        if (!empty($filtros['preco'])) {
            $query->where('reserva.preco', $filtros['preco']);
        }

        if (!empty($filtros['origem'])) {
            $query->where('voo.origem', 'like', '%' . $filtros['origem'] . '%');
        }

        if (!empty($filtros['destino'])) {
            $query->where('voo.destino', 'like', '%' . $filtros['destino'] . '%');
        }

        if (!empty($filtros['reserva'])) {
            $query->where('reserva.numero_reserva', 'like', '%' . $filtros['reserva'] . '%');
        }

        if (!empty($filtros['nome'])) {
            $query->where('passageiro.nome', 'like', '%' . $filtros['nome'] . '%');
        }

        if (!empty($filtros['nif'])) {
            $query->where('passageiro.nif', $filtros['nif']);
        }

        // Executa a busca
        $resultados = $query->get();
        dd($resultados);

        // Retorna para a view com os resultados
        return view('index', compact('resultados'));
    }
}
