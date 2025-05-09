<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <div class="container">

        <form class="formulario" action="/buscar" method="GET">

            <div class="flightdata">

                <div class="campo-form">
                    <label for="Voo">Voo</label>
                    <input type="text" id="Voo" name="voo">
                </div>

                <div class="campo-form">
                    <label for="Data">Data do voo</label>
                    <input type="date" id="Data" name="data">
                </div>

                <div class="campo-form">
                    <label for="Origem">Origem</label>
                    <input type="text" id="Origem" name="origem">
                </div>

                <div class="campo-form">
                    <label for="Destino">Destino</label>
                    <input type="text" id="Destino" name="destino">
                </div>
            </div>

            <div class="passageiros">

                <div class="campo-form">
                    <label for="Reserva">Reserva</label>
                    <input type="text" id="Reserva" name="reserva">
                </div>

                <div class="campo-form">
                    <label for="Name">Nome do passageiro</label>
                    <input type="text" id="Name" name="name">
                </div>

                <div class="campo-form">
                    <label for="NIF">NIF do passageiro</label>
                    <input type="text" id="NIF" name="nif">
                </div>

                <div class="campo-form">
                    <label for="CC">CC/BI</label>
                    <input type="text" id="CC" name="cc">
                </div>


                <div class="botoes">
                    <button type="submit">Pesquisar</button>
                    <button type="reset">Limpar</button>
                </div>
            </div>
        </form>

        <div class="resultado">
            <table class="tabela-resultados">
                <thead>
                    <tr>
                        <th>Passageiro</th>
                        <th>NIF</th>
                        <th>Reserva</th>
                        <th>Voo</th>
                        <th>Data</th>
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
                        <td>{{ $resultado->origem }}</td>
                        <td>{{ $resultado->destino }}</td>
                        <td>{{ number_format($resultado->preco / 100, 2, ',', '.') }}€</td>
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
    </div>

</body>

</html>