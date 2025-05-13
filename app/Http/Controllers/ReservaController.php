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
        $resultados = $query->paginate(3);

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
        $voo = Voo::all();
        $passageiro = Passageiro::all();
        return view('admin.reserva.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'voo' => 'required|string|max:10',
            'data' => 'required|date',
            'origem' => 'required|string|max:50',
            'destino' => 'required|string|max:50',
            'reserva' => 'required|string|unique:reserva,numero_reserva|max:20',
            'preco' => 'required|numeric|min:0|max:9999.99', 
            'name' => 'required|string|max:100',
            'nif' => 'required|string|max:9',
            'cc' => 'required|string|max:20',
        ]);

        // Adiciona prefixo TP ao número do voo se não existir
        $numeroVoo = $request->voo;
        if (strpos($numeroVoo, 'TP') !== 0) {
            $numeroVoo = 'TP' . $numeroVoo;
        }

        // Adiciona prefixo R ao número da reserva se não existir
        $numeroReserva = $request->reserva;
        if (strpos($numeroReserva, 'RES') !== 0) {
            $numeroReserva = 'RES' . $numeroReserva;
        }

        // Cria ou busca o voo
        $voo = Voo::firstOrCreate(
            ['numero_voo' => $numeroVoo],
            [
                'data' => $request->data,
                'origem' => $request->origem,
                'destino' => $request->destino,
            ]
        );

        // Cria ou busca o passageiro
        $passageiro = Passageiro::firstOrCreate(
            [
                'nif' => $request->nif,

            ],
            [
                'nome' => $request->name,
                'identificacao' => $request->cc,
                'email' => $request->nif . '@exemplo.com', // valor padrão, ajuste se quiser
                'telefone' => '000000000',
            ]
        );

        // Limite de reservas por voo
        $totalReservas = Reserva::where('voo_id', $voo->id)->count();
        if ($totalReservas >= 220) {
            return back()->withErrors(['voo' => 'Este voo já atingiu o limite de 220 reservas.']);
        }

        // Cria a reserva corretamente
        Reserva::create([
            'numero_reserva' => $numeroReserva,
            'preco' => $request->preco,
            'voo_id' => $voo->id,
            'passageiro_id' => $passageiro->id,
        ]);

        return redirect()->route('dashboard')->with('success', 'Reserva criada com sucesso!');
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
    public function edit($id)
    {
        $reserva = \App\Models\Reserva::findOrFail($id);
        $voo = $reserva->voo;
        $passageiro = $reserva->passageiro;
        return view('admin.reserva.edit', compact('reserva', 'voo', 'passageiro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'voo' => 'required|string|max:10',
            'data' => 'required|date',
            'origem' => 'required|string|max:50',
            'destino' => 'required|string|max:50',
            'reserva' => 'required|string|max:20',
            'preco' => 'required|numeric|min:0|max:9999.99',
            'name' => 'required|string|max:100',
            'nif' => 'required|string|max:9',
            'cc' => 'required|string|max:20',
        ]);

        $reserva = Reserva::findOrFail($id);

        // Atualiza voo
        $voo = $reserva->voo;
        $voo->update([
            'numero_voo' => $request->voo,
            'data' => $request->data,
            'origem' => $request->origem,
            'destino' => $request->destino,
        ]);

        // Atualiza passageiro
        $passageiro = $reserva->passageiro;
        $passageiro->update([
            'nome' => $request->name,
            'nif' => $request->nif,
            'identificacao' => $request->cc,
        ]);

        // Atualiza reserva
        $reserva->update([
            'numero_reserva' => $request->reserva,
            'preco' => $request->preco,
        ]);

        return redirect()->route('dashboard')->with('success', 'Reserva atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->delete();

        return redirect()->route('dashboard')->with('success', 'Reserva excluída com sucesso!');
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
