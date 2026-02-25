<?php
require_once '../config.php';
/*
    Puxa o arquivo config.php que você criou. O ../ significa "volta uma pasta" — como o buscar.php está dentro de /api/, precisa voltar pra encontrar o config.php na raiz.

*/

$query = $_GET['query'] ?? '';

/*
$_GET pega o que veio na URL. Quando você acessar buscar.php?query=batman, o $query vai ser "batman". O ?? '' significa "se não vier nada, usa string vazia".
*/

if(empty($query)) {
    echo json_encode(['error' => 'Digite um filme']);
    exit;
}
/*
Se o usuário não digitou nada, devolve um erro e para a execução. O exit é importante — sem ele o código continuaria rodando mesmo com erro.

*/
$url = "https://api.themoviedb.org/3/search/movie?api_key=" . TMDB_KEY . "&query=" . urlencode($query) . "&language=pt-BR";

$response = file_get_contents($url);
$data = json_decode($response, true);
/*
file_get_contents() faz a requisição pra URL e traz o resultado como texto. json_decode() converte esse texto JSON num array PHP que você consegue usar. O true faz ele virar array em vez de objeto.
*/
$filmes = [];

foreach($data['results'] as $filme) {
    $filmes[] = [
        'tmdb_id' => $filme['id'],
        'titulo'  => $filme['title'],
        'poster'  => $filme['poster_path'] ? 'https://image.tmdb.org/t/p/w200' . $filme['poster_path'] : null,
        'ano'     => substr($filme['release_date'], 0, 4)
    ];
}
/*
A API retorna muita informação que você não precisa. Esse foreach percorre cada filme e pega só o essencial. No poster tem um ? que é um ternário — significa "se tiver poster, monta o link completo, senão coloca null". O substr(..., 0, 4) pega só os 4 primeiros caracteres da data, por exemplo "2008-07-18" vira "2008".
*/
header('Content-Type: application/json');
echo json_encode($filmes);
