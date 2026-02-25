// ===== VARIÁVEIS GLOBAIS =====

// Guarda os filmes que já estão no ranking para evitar duplicatas
let filmesNoRanking = [];

// ===== INICIALIZAÇÃO =====

// Quando a página carrega, busca o ranking do banco
document.addEventListener('DOMContentLoaded', () => {
    carregarRanking();
});

// Permite buscar filme pressionando Enter no campo de busca
document.getElementById('campoBusca').addEventListener('keypress', (e) => {
    if(e.key === 'Enter') buscarFilmes();
});

// ===== BUSCAR FILMES NA API TMDB =====
async function buscarFilmes() {
    // Pega o valor do campo de busca e remove espaços extras
    const query = document.getElementById('campoBusca').value.trim();

    // Se estiver vazio, não faz nada
    if(!query) return;

    // Mostra loading enquanto busca
    document.getElementById('resultadosBusca').innerHTML = `
        <div class="loading">
            <i class="bi bi-hourglass-split"></i> Buscando filmes...
        </div>
    `;

    try {
        // Chama o buscar.php passando o nome do filme
        const response = await fetch(`api/buscar.php?query=${encodeURIComponent(query)}`);
        const filmes = await response.json();

        // Renderiza os resultados na tela
        renderizarResultados(filmes);

    } catch(erro) {
        document.getElementById('resultadosBusca').innerHTML = `
            <div class="loading">Erro ao buscar filmes. Tente novamente.</div>
        `;
    }
}

// ===== RENDERIZAR RESULTADOS DA BUSCA =====
function renderizarResultados(filmes) {
    const container = document.getElementById('resultadosBusca');

    // Se não encontrou nenhum filme
    if(filmes.length === 0) {
        container.innerHTML = `<div class="loading">Nenhum filme encontrado.</div>`;
        return;
    }

    // Monta o HTML de cada filme encontrado
    container.innerHTML = filmes.map(filme => {
        // Verifica se o filme já está no ranking
        const jaAdicionado = filmesNoRanking.includes(filme.tmdb_id);

        // Monta o poster ou placeholder se não tiver imagem
        const poster = filme.poster 
            ? `<img src="${filme.poster}" alt="${filme.titulo}">`
            : `<div class="filme-card-sem-poster">Sem imagem</div>`;

        return `
            <div class="filme-card">
                ${poster}
                <div class="filme-card-info">
                    <div class="filme-card-titulo" title="${filme.titulo}">${filme.titulo}</div>
                    <div class="filme-card-ano">${filme.ano || 'S/A'}</div>
                    <button 
                        class="btn-adicionar" 
                        onclick="adicionarFilme(${filme.tmdb_id}, '${escapar(filme.titulo)}', '${filme.poster}', '${filme.ano}')"
                        ${jaAdicionado ? 'disabled' : ''}
                    >
                        ${jaAdicionado ? '✓ Adicionado' : '+ Adicionar'}
                    </button>
                </div>
            </div>
        `;
    }).join('');
}

// ===== ADICIONAR FILME AO RANKING =====
async function adicionarFilme(tmdb_id, titulo, poster, ano) {
    try {
        // Envia os dados do filme pro adicionar.php
        const response = await fetch('api/adicionar.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ tmdb_id, titulo, poster, ano })
        });

        const resultado = await response.json();

        if(resultado.sucesso) {
            // Mostra notificação de sucesso
            mostrarToast(`"${titulo}" adicionado na posição ${resultado.posicao}!`, 'success');
            // Recarrega o ranking
            carregarRanking();
            // Atualiza os botões da busca
            buscarFilmes();
        } else {
            mostrarToast(resultado.erro, 'danger');
        }

    } catch(erro) {
        mostrarToast('Erro ao adicionar filme.', 'danger');
    }
}

// ===== CARREGAR RANKING DO BANCO =====
async function carregarRanking() {
    try {
        // Busca todos os filmes do ranking
        const response = await fetch('api/listar.php');
        const filmes = await response.json();

        // Atualiza a lista de filmes no ranking (para controle de duplicatas)
        filmesNoRanking = filmes.map(f => parseInt(f.tmdb_id));

        // Atualiza o contador na navbar
        document.getElementById('contador').textContent = filmes.length;

        // Renderiza o ranking na tela
        renderizarRanking(filmes);

    } catch(erro) {
        console.error('Erro ao carregar ranking:', erro);
    }
}

// ===== RENDERIZAR RANKING =====
function renderizarRanking(filmes) {
    const container = document.getElementById('listaRanking');

    // Se o ranking estiver vazio
    if(filmes.length === 0) {
        container.innerHTML = `
            <div class="ranking-vazio">
                <i class="bi bi-film"></i>
                <p>Seu ranking está vazio.</p>
                <p>Busque um filme acima e adicione!</p>
            </div>
        `;
        return;
    }

    // Monta o HTML de cada filme do ranking
    container.innerHTML = filmes.map(filme => {
        // Monta o poster ou placeholder
        const poster = filme.poster
            ? `<img src="${filme.poster}" alt="${filme.titulo}" class="ranking-poster">`
            : `<div class="ranking-poster-vazio">Sem imagem</div>`;

        return `
            <div class="ranking-item" data-id="${filme.id}">
                <div class="ranking-posicao">#${filme.posicao}</div>
                ${poster}
                <div class="ranking-info">
                    <div class="ranking-titulo">${filme.titulo}</div>
                    <div class="ranking-ano">${filme.ano || 'S/A'}</div>
                </div>
                <button class="btn-remover" onclick="removerFilme(${filme.id}, '${escapar(filme.titulo)}')">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
    }).join('');
}

// ===== REMOVER FILME DO RANKING =====
async function removerFilme(id, titulo) {
    // Confirmação antes de remover
    
    try {
        // Envia o id do filme pro remover.php
        const response = await fetch('api/remover.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });

        const resultado = await response.json();

        if(resultado.sucesso) {
            mostrarToast(`"${titulo}" removido do ranking!`, 'danger');
            // Recarrega o ranking
            carregarRanking();
        }

    } catch(erro) {
        mostrarToast('Erro ao remover filme.', 'danger');
    }
}

// ===== MOSTRAR NOTIFICAÇÃO (TOAST) =====
function mostrarToast(mensagem, tipo) {
    const toast = document.getElementById('toast');
    const toastMensagem = document.getElementById('toastMensagem');

    // Define a cor do toast baseado no tipo
    toast.className = `toast align-items-center text-white border-0 bg-${tipo}`;
    toastMensagem.textContent = mensagem;

    // Mostra o toast por 3 segundos
    const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
    bsToast.show();
}

// ===== FUNÇÃO AUXILIAR =====
// Escapa aspas simples para não quebrar o HTML
function escapar(texto) {
    return texto.replace(/'/g, "\\'");
}