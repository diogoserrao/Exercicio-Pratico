@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Editar Reserva</h2>

    {{-- Exibe erros de validação --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $erro)
            <li>{{ $erro }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form class="formulario" action="{{ route('reservas.update', $reserva->id) }}" method="POST">
        @csrf
        @method('PUT')

        <fieldset class="mb-3">
            <legend>Dados do Voo</legend>
            <div class="row">
                <div class="col-md-3 campo-form mb-2">
                    <label for="Voo">Nº do Voo</label>
                    <input type="text" id="Voo" name="voo" class="form-control" value="{{ old('voo', $voo->numero_voo) }}" required>
                </div>
                <div class="col-md-3 campo-form mb-2">
                    <label for="Data">Data do voo</label>
                    <input type="date" id="Data" name="data" class="form-control" value="{{ old('data', $voo->data) }}" required>
                </div>
                <div class="col-md-3 campo-form mb-2">
                    <label for="Origem">Origem</label>
                    <input type="text" id="Origem" name="origem" class="form-control" value="{{ old('origem', $voo->origem) }}" required>
                </div>
                <div class="col-md-3 campo-form mb-2">
                    <label for="Destino">Destino</label>
                    <input type="text" id="Destino" name="destino" class="form-control" value="{{ old('destino', $voo->destino) }}" required>
                </div>
            </div>
        </fieldset>

        <fieldset class="mb-3">
            <legend>Dados do Passageiro</legend>
            <div class="row">
                <div class="col-md-3 campo-form mb-2">
                    <label for="Reserva">Nº da Reserva</label>
                    <input type="text" id="Reserva" name="reserva" class="form-control" value="{{ old('reserva', $reserva->numero_reserva) }}" required>
                </div>
                <div class="col-md-3 campo-form mb-2">
                    <label for="Preco">Preço (€)</label>
                    <input type="number" id="Preco" name="preco" class="form-control" value="{{ old('preco', $reserva->preco ) }}" step="0.01" min="0" max="999999.99" required>
                </div>
                <div class="col-md-3 campo-form mb-2">
                    <label for="Name">Nome do passageiro</label>
                    <input type="text" id="Name" name="name" class="form-control" value="{{ old('name', $passageiro->nome) }}" required>
                </div>
                <div class="col-md-3 campo-form mb-2">
                    <label for="NIF">NIF do passageiro</label>
                    <input type="text" id="NIF" name="nif" class="form-control" value="{{ old('nif', $passageiro->nif) }}" required>
                </div>
                <div class="col-md-3 campo-form mb-2">
                    <label for="CC">CC/BI</label>
                    <input type="text" id="CC" name="cc" class="form-control" value="{{ old('cc', $passageiro->identificacao) }}" required>
                </div>
            </div>
        </fieldset>

        <div class="botoes mt-3">
            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection