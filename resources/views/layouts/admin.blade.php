<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Exercicio Pratico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        /* Cabeçalho fixo */
        .navbar {
            z-index: 1000;
        }

        /* Espaçamento dos itens */
        .navbar-nav .nav-item {
            margin-right: 1rem;
        }

        .admin-content {
            padding: 1.5rem;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <!-- Cabeçalho -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reservas.*') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="fas fa-star me-1"></i> Reservas
                        </a>
                    </li>


                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}" target="_blank">
                            <i class="fas fa-external-link-alt me-1"></i> Ver Site
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" onclick="handleLogout(); return false;" class="nav-link text-danger  rounded ms-2">
                            <i class="fas fa-sign-out-alt me-1"></i> Sair
                        </a>
                        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container-fluid">
        <div class="row">
            <main class="col-12 admin-content">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Confirmar Eliminação</h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body" id="deleteConfirmModalBody">
                    Tem certeza que deseja excluir este item?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="deleteConfirmBtn">Sim</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variáveis para o modal e form de exclusão
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            const deleteConfirmBtn = document.getElementById('deleteConfirmBtn');
            const deleteConfirmModalBody = document.getElementById('deleteConfirmModalBody');
            let deleteForm = null;

            // Interceptar cliques em botões de exclusão
            document.addEventListener('click', function(event) {
                // Verificar se o clique foi em um botão dentro de um form de exclusão
                const deleteButton = event.target.closest('button[type="submit"]');
                if (!deleteButton) return;

                const form = deleteButton.closest('form');
                if (!form) return;

                // Verificar se é um formulário de DELETE
                const method = form.querySelector('input[name="_method"][value="DELETE"]');
                if (!method) return;

                // Prevenir envio do formulário
                event.preventDefault();

                // Obter nome do item a partir do atributo data, ou usar texto padrão
                const itemName = form.getAttribute('data-item-name') || 'este item';
                const itemType = form.getAttribute('data-item-type') || 'item';

                // Personalizar mensagem do modal
                deleteConfirmModalBody.innerHTML = `
                <p>Tem certeza que deseja excluir <strong>${itemName}</strong>?</p>
                <p class="text-danger mb-0"><i class="fas fa-exclamation-triangle me-1"></i> Esta ação não pode ser desfeita!</p>
            `;

                // Armazenar referência ao formulário
                deleteForm = form;

                // Mostrar o modal
                deleteModal.show();
            });

            // Ação quando o botão "Sim" é clicado
            deleteConfirmBtn.addEventListener('click', function() {
                if (deleteForm) {
                    // Enviar o formulário
                    deleteForm.submit();
                }

                // Fechar o modal
                deleteModal.hide();
            });
        });

        function handleLogout() {
            const form = document.getElementById('logout-form');

            // Envie o formulário com AJAX para evitar problemas de CSRF
            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                })
                .finally(() => {
                    // Redireciona para a página principal após o logout
                    window.location.href = '/'; // Ou use uma rota nomeada
                });

            return false;
        }

        // Esconde automaticamente o alerta de sucesso após 3 segundos (3000 ms)
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                setTimeout(() => {
                    // Usa o método do Bootstrap para fechar o alerta (se Bootstrap 5+)
                    var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                }, 3000); // 3000 ms = 3 segundos
            }
        });
    </script>
</body>

</html>