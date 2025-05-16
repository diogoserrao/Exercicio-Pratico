<?php

namespace App\Http\Controllers;

use App\Models\Passageiro;
use Illuminate\Http\Request;

class PassageiroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.passageiro.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100',
            'nif' => 'required|string|max:9|unique:passageiro,nif',
            'identificacao' => 'required|string|max:8',
            'email' => 'nullable|email|max:100',
            'telefone' => 'nullable|string|max:20',
        ]);

        Passageiro::create($request->only(['nome', 'nif', 'identificacao', 'email', 'telefone']));

        return redirect()->route('dashboard')->with('success', 'Passageiro criado com sucesso!');
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
        $passageiro = Passageiro::findOrFail($id);
        return view('admin.passageiro.edit', compact('passageiro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $passageiro = Passageiro::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:100',
            'nif' => 'required|string|max:9|unique:passageiro,nif,' . $passageiro->id,
            'identificacao' => 'required|string|max:8',
            'email' => 'nullable|email|max:100',
            'telefone' => 'nullable|string|max:20',
        ]);

        $passageiro->update($request->only(['nome', 'nif', 'identificacao', 'email', 'telefone']));

        return redirect()->route('dashboard')->with('success', 'Passageiro atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
