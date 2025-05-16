<?php


namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Voo;
use App\Models\Passageiro;
use App\Models\Cidade;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filtros = array_filter($request->all());

        $query = DB::table('reserva')
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
            );

        if (!empty($filtros['voo'])) {
            $query->where('voo.numero_voo', 'like', '%' . $filtros['voo'] . '%');
        }

        if (!empty($filtros['data'])) {
            $query->whereDate('voo.data', $filtros['data']);
        }

        if (!empty($filtros['origem_id'])) {
            $query->where('voo.origem_id', $filtros['origem_id']);
        }

        if (!empty($filtros['destino_id'])) {
            $query->where('voo.destino_id', $filtros['destino_id']);
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

        $resultados = $query->paginate(3);
        $resultados->appends($filtros);

        // Envie as cidades para o filtro se necessário
        $cidades = Cidade::all();

        return view('index', [
            'resultados' => $resultados,
            'filtros' => $filtros,
            'cidades' => $cidades
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cidades = Cidade::all();
        return view('admin.reserva.create', compact('cidades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'voo' => 'required|string|max:10',
            'data' => 'required|date',
            'origem_id' => 'required|exists:cidades,id',
            'destino_id' => 'required|exists:cidades,id',
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

        // Adiciona prefixo RES ao número da reserva se não existir
        $numeroReserva = $request->reserva;
        if (strpos($numeroReserva, 'RES') !== 0) {
            $numeroReserva = 'RES' . $numeroReserva;
        }

        // Cria ou busca o voo
        $voo = Voo::firstOrCreate(
            ['numero_voo' => $numeroVoo, 'data' => $request->data, 'origem_id' => $request->origem_id, 'destino_id' => $request->destino_id],
            [
                'numero_voo' => $numeroVoo,
                'data' => $request->data,
                'origem_id' => $request->origem_id,
                'destino_id' => $request->destino_id,
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
                'email' => $request->nif . '@exemplo.com',
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
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $reserva = Reserva::findOrFail($id);
        $voo = $reserva->voo;
        $passageiro = $reserva->passageiro;
        $cidades = Cidade::all();
        return view('admin.reserva.edit', compact('reserva', 'voo', 'passageiro', 'cidades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'voo' => 'required|string|max:10',
            'data' => 'required|date',
            'origem_id' => 'required|exists:cidades,id',
            'destino_id' => 'required|exists:cidades,id',
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
            'origem_id' => $request->origem_id,
            'destino_id' => $request->destino_id,
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

    /**
     * Busca avançada (exemplo)
     */
    public function buscar(Request $request)
    {
        $filtros = array_filter($request->all());

        $query = DB::table('reserva')
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
            );

        if (!empty($filtros['voo'])) {
            $query->where('voo.numero_voo', 'like', '%' . $filtros['voo'] . '%');
        }

        if (!empty($filtros['data'])) {
            $query->whereDate('voo.data', $filtros['data']);
        }

        if (!empty($filtros['preco'])) {
            $query->where('reserva.preco', $filtros['preco']);
        }

        if (!empty($filtros['origem_id'])) {
            $query->where('voo.origem_id', $filtros['origem_id']);
        }

        if (!empty($filtros['destino_id'])) {
            $query->where('voo.destino_id', $filtros['destino_id']);
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

        $resultados = $query->get();

        return view('index', compact('resultados', 'filtros'));
    }
}