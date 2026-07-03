<?php
require_once __DIR__ . '/../config/db.php';

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
        explode(" ", "a A e E i I o O u U n N c"),
        $string
    );
}

function buscarRespostaJson($perguntaCliente, $arquivoJson = __DIR__ . '/faq.json')
{
    if (!file_exists($arquivoJson)) {
        return false;
    }
    // Carrega o conteúdo do arquivo JSON
    $conteudoJson = file_get_contents($arquivoJson);
    $faq = json_decode($conteudoJson, true);

    if (empty($faq)) {
        return false;
    }

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

function buscarNoBancoDeDados($pergunta, $conn)
{
    if (!$conn) {
        return false;
    }

    $perguntaLimpa = removerAcentos(strtolower($pergunta));
    $palavras = explode(' ', $perguntaLimpa);
    $termosBusca = [];
    foreach ($palavras as $palavra) {
        $palavraLimpa = trim($palavra);
        if (strlen($palavraLimpa) > 3) {
            $termosBusca[] = "%" . $palavraLimpa . "%";
        }
    }

    if (empty($termosBusca)) {
        return false;
    }

    // 1. Tenta buscar por Categoria primeiro
    $condicoesCat = [];
    $tiposCat = "";
    $parametrosCat = [];
    foreach ($termosBusca as $termo) {
        $condicoesCat[] = "name LIKE ?";
        $tiposCat .= "s";
        $parametrosCat[] = $termo;
    }

    $sqlCat = "SELECT category_id, name, slogan FROM categories WHERE " . implode(" OR ", $condicoesCat) . " LIMIT 1";
    $stmtCat = $conn->prepare($sqlCat);
    if ($stmtCat) {
        $stmtCat->bind_param($tiposCat, ...$parametrosCat);
        $stmtCat->execute();
        $resCat = $stmtCat->get_result();
        if ($resCat && $resCat->num_rows > 0) {
            $catLinha = $resCat->fetch_assoc();
            
            // Busca produtos nessa categoria
            $stmtProd = $conn->prepare("SELECT name, price, slogan, reference FROM products WHERE active = 1 AND category_id = ? LIMIT 5");
            if ($stmtProd) {
                $stmtProd->bind_param("i", $catLinha['category_id']);
                $stmtProd->execute();
                $resProd = $stmtProd->get_result();
                if ($resProd && $resProd->num_rows > 0) {
                    $retorno = "Temos uma categoria completa de **{$catLinha['name']}**! Veja alguns destaques:\n\n";
                    while ($linha = $resProd->fetch_assoc()) {
                        $preco = number_format($linha['price'], 2, ',', '.');
                        $retorno .= "• **{$linha['name']}** - R$ {$preco}\n";
                        $retorno .= "  [Ver Detalhes do Produto](index.php?page=product&slogan={$linha['slogan']})\n\n";
                    }
                    $retorno .= "Quer ver mais? Veja a lista completa [clicando aqui](index.php?category_slogan={$catLinha['slogan']}#products).";
                    return $retorno;
                }
            }
        }
    }

    // 2. Busca por Produto (nome ou descrição)
    $condicoes = [];
    $tipos = "";
    $parametros = [];
    foreach ($termosBusca as $termo) {
        $condicoes[] = "(name LIKE ? OR description LIKE ?)";
        $tipos .= "ss";
        $parametros[] = $termo;
        $parametros[] = $termo;
    }

    $sql = "SELECT name, price, slogan, reference FROM products WHERE active = 1 AND (" . implode(" OR ", $condicoes) . ") LIMIT 5";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param($tipos, ...$parametros);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado && $resultado->num_rows > 0) {
            $retorno = "Encontrei estes produtos em nosso catálogo que podem te interessar:\n\n";
            while ($linha = $resultado->fetch_assoc()) {
                $preco = number_format($linha['price'], 2, ',', '.');
                $retorno .= "• **{$linha['name']}** (Ref: {$linha['reference']}) - R$ {$preco}\n";
                $retorno .= "  [Ver Detalhes do Produto](index.php?page=product&slogan={$linha['slogan']})\n\n";
            }
            return $retorno;
        }
    }

    return false;
}

function responderPergunta(string $pergunta, $conn): string
{
    $resposta = buscarRespostaJson($pergunta);
    if ($resposta === false) {
        $resposta = buscarNoBancoDeDados($pergunta, $conn);
    }

    return $resposta ?: 'Desculpe, não encontrei informações sobre isso em nosso FAQ ou catálogo. Mas você pode falar diretamente com nossos atendentes clicando no ícone do WhatsApp no canto da tela!';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dadosRecebidos = json_decode(file_get_contents('php://input'), true);

    if (isset($dadosRecebidos['pergunta'])) {
        $respostaDoChatbot = responderPergunta($dadosRecebidos['pergunta'], $conn);

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
