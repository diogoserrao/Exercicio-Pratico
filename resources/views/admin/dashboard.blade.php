@extends('layouts.admin')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
</head>
@section('content')
<div class="dashboard-container">
    <h1>Dashboard de Reservas</h1>
    <a href="{{ route('reservas.create') }}" class="btn-nova-reserva">Nova Reserva</a>
    <div class="tabela-scroll">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Passageiro</th>
                    <th>NIF</th>
                    <th>Reserva</th>
                    <th>Voo</th>
                    <th>Datado Voo</th>
                    <th>Origem</th>
                    <th>Destino</th>
                    <th>Valor</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($resultados) && count($resultados) > 0)
                @foreach($resultados as $resultado)
                <tr>
                    <td>{{ $resultado->nome }}</td>
                    <td>{{ $resultado->nif }}</td>
                    <td>{{ $resultado->numero_reserva }}</td>
                    <td>{{ $resultado->numero_voo }}</td>
                    <td>{{ date('d/m/Y', strtotime($resultado->data)) }}</td>
                    <td>{{ $resultado->origem_nome }}</td>
                    <td>{{ $resultado->destino_nome }}</td>
                    <td>{{ number_format($resultado->preco, 2, ',', '.') }}€</td>
                    <td>
                        <a href="{{ route('reservas.edit', $resultado->id) }}" class="btn-editar">Editar</a>
                        <form action="{{ route('reservas.destroy', $resultado->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-excluir">Excluir</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="9">Nenhum resultado encontrado</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- ...tabela de reservas... --}}

    <h2 class="mt-5">Passageiros</h2>
    <div class="tabela-scroll">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>NIF</th>
                    <th>CC/BI</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($passageiros as $passageiro)
                <tr>
                    <td>{{ $passageiro->nome }}</td>
                    <td>{{ $passageiro->nif }}</td>
                    <td>{{ $passageiro->identificacao }}</td>
                    <td>{{ $passageiro->email }}</td>
                    <td>{{ $passageiro->telefone }}</td>
                    <td>
                        <a href="{{ route('passageiros.edit', $passageiro->id) }}" class="btn btn-primary btn-sm">Editar</a>
                        <form action="{{ route('passageiros.update', $passageiro->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Tem certeza que deseja excluir este passageiro?')">Excluir</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


</div>
@endsection