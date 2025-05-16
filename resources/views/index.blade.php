<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <div class="container">

        <form class="formulario" action="/" method="GET">

            <div class="flightdata">
                <div class="campo-form">
                    <label for="Voo">Voo</label>
                    <input type="text" id="Voo" name="voo" value="{{ $filtros['voo'] ?? '' }}">
                </div>

                <div class="campo-form">
                    <label for="Data">Data do voo</label>
                    <input type="date" id="Data" name="data" value="{{ $filtros['data'] ?? '' }}">
                </div>

                <div class="campo-form">
                    <label for="Origem">Origem</label>
                    <input type="text" id="Origem" name="origem" value="{{ $filtros['origem'] ?? '' }}">
                </div>

                <div class="campo-form">
                    <label for="Destino">Destino</label>
                    <input type="text" id="Destino" name="destino" value="{{ $filtros['destino'] ?? '' }}">
                </div>
            </div>

            <div class="passageiros">
                <div class="campo-form">
                    <label for="Reserva">Reserva</label>
                    <input type="text" id="Reserva" name="reserva" value="{{ $filtros['reserva'] ?? '' }}">
                </div>

                <div class="campo-form">
                    <label for="Name">Nome do passageiro</label>
                    <input type="text" id="Name" name="name" value="{{ $filtros['name'] ?? '' }}">
                </div>

                <div class="campo-form">
                    <label for="NIF">NIF do passageiro</label>
                    <input type="text" id="NIF" name="nif" value="{{ $filtros['nif'] ?? '' }}">
                </div>

                <div class="campo-form">
                    <label for="CC">CC/BI</label>
                    <input type="text" id="CC" name="cc" value="{{ $filtros['cc'] ?? '' }}">
                </div>

                <div class="botoes">
                    <button type="submit">Pesquisar</button>
                    <a href="/" class="btn-limpar">Limpar</a>
                </div>
            </div>
        </form>

        <!-- Tabela de Resultados -->
        <div class="resultado">
            <div class="tabela-responsiva">
                <table class="tabela-resultados">
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
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="8">Nenhum resultado encontrado</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <nav aria-label="...">
                <ul class="pagination">
                    <!-- Link Anterior -->
                    @if ($resultados->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">Previous</span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $resultados->previousPageUrl() }}" rel="prev">Previous</a>
                    </li>
                    @endif

                    <!-- Links de Páginas -->
                    @foreach ($resultados->getUrlRange(1, $resultados->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $resultados->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}" {{ $page == $resultados->currentPage() ? 'aria-current="page"' : '' }}>
                            {{ $page }}
                        </a>
                    </li>
                    @endforeach

                    <!-- Link Próximo -->
                    @if ($resultados->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $resultados->nextPageUrl() }}" rel="next">Next</a>
                    </li>
                    @else
                    <li class="page-item disabled">
                        <span class="page-link">Next</span>
                    </li>
                    @endif
                </ul>
            </nav>

        </div>
    </div>

</body>

</html>