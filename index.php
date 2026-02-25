<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineRank 🎬</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- CSS customizado -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-film"></i> CINERANK
            </a>
            <!-- Contador do ranking -->
            <span class="text-secondary">
                <span id="contador">0</span>/100 filmes
            </span>
        </div>
    </nav>

    <div class="container my-5">

        <!-- SEÇÃO DE BUSCA -->
        <div class="search-container">
            <h2 class="section-title">
                <i class="bi bi-search"></i> Buscar Filme
            </h2>

            <!-- Campo de busca -->
            <div class="input-group">
                <input 
                    type="text" 
                    id="campoBusca" 
                    class="form-control search-input" 
                    placeholder="Digite o nome do filme..."
                >
                <button class="btn-buscar" onclick="buscarFilmes()">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>

            <!-- Resultados da busca -->
            <div id="resultadosBusca" class="resultados-grid"></div>
        </div>

        <!-- SEÇÃO DO RANKING -->
        <div class="ranking-container">
            <h2 class="section-title">
                <i class="bi bi-trophy"></i> Meu Ranking
            </h2>

            <!-- Lista do ranking -->
            <div id="listaRanking">
                <!-- Filmes carregados pelo JS -->
            </div>
        </div>

    </div>

    <!-- TOAST de notificação -->
    <div class="toast-container">
        <div id="toast" class="toast align-items-center text-white border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastMensagem"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript do projeto -->
    <script src="assets/js/app.js" defer></script>

</body>
</html>