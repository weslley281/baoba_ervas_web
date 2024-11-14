<?php

function removerAcentos($string)
{
    return preg_replace(
        array(
            "/(á|à|ã|â|ä)/",
            "/(Á|À|Ã|Â|Ä)/",
            "/(é|è|ê|ë)/",
            "/(É|È|Ê|Ë)/",
            "/(í|ì|î|ï)/",
            "/(Í|Ì|Î|Ï)/",
            "/(ó|ò|õ|ô|ö)/",
            "/(Ó|Ò|Õ|Ô|Ö)/",
            "/(ú|ù|û|ü)/",
            "/(Ú|Ù|Û|Ü)/",
            "/(ñ)/",
            "/(Ñ)/",
            "/(Ç|ç)/",
        ),
        explode(" ", "a A e E i I o O u U n N"),
        $string
    );
}

function buscarRespostaJson($perguntaCliente, $arquivoJson = 'faq.json')
{
    // Carrega o conteúdo do arquivo JSON
    $conteudoJson = file_get_contents($arquivoJson);
    $faq = json_decode($conteudoJson, true);

    // Normaliza a pergunta do cliente
    $perguntaClienteNormalizada = removerAcentos(strtolower($perguntaCliente));

    // Verifica cada conjunto de palavras-chave no JSON
    foreach ($faq as $palavrasChave => $resposta) {
        // Remove os acentos e converte para minúsculas as palavras-chave
        $palavrasChaveNormalizadas = removerAcentos(strtolower($palavrasChave));

        // Verifica se as palavras-chave estão na pergunta do cliente
        if (stripos($perguntaClienteNormalizada, $palavrasChaveNormalizadas) !== false) {
            return $resposta;
        }
    }

    // Retorna falso se não encontrar uma resposta correspondente
    return false;
}

function responderPergunta(string $pergunta): string
{
    $resposta = buscarRespostaJson($pergunta);

    return $resposta ?: 'Desculpe, não encontrei uma resposta para sua pergunta.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dadosRecebidos = json_decode(file_get_contents('php://input'), true);

    if (isset($dadosRecebidos['pergunta'])) {
        $respostaDoChatbot = responderPergunta($dadosRecebidos['pergunta']);

        header('Content-Type: application/json');
        echo json_encode(['resposta' => $respostaDoChatbot]);
    } else {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['erro' => 'A chave "pergunta" não foi fornecida']);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['erro' => 'Método não permitido. Use o método POST.']);
}
