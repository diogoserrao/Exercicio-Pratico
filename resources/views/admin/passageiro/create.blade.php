@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Novo Passageiro</h2>

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

    <form action="{{ route('passageiros.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" id="nome" name="nome" class="form-control" value="{{ old('nome') }}" required>
        </div>
        <div class="mb-3">
            <label for="nif" class="form-label">NIF</label>
            <input type="text" id="nif" name="nif" class="form-control" value="{{ old('nif') }}" maxlength="9" required>
        </div>
        <div class="mb-3">
            <label for="identificacao" class="form-label">CC/BI</label>
            <input type="text" id="identificacao" name="identificacao" class="form-control" value="{{ old('identificacao') }}" maxlength="8" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" id="telefone" name="telefone" class="form-control" value="{{ old('telefone') }}" maxlength="9" required> 
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection