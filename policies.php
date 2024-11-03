<?php
if (isset($_GET["slogan"])) {
    $name_product = $product->getNameBySlogan($_GET["slogan"]);
} else {
    $name_product = "";
}

$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? '';

$titles = [
    'home' => 'Pagina inicial',
    'login' => 'Login',
    'users' => 'Usuários',
    'profile' => 'Perfil e Administração',
    'product' => $name_product
];

$page_title = isset($titles[$page]) ? $titles[$page] : 'Página não encontrada';
require_once "./header.php";
?>

<body>
    <!-- Main Content -->
    <div class="container mt-5">
        <h1 class="text-center">Política de Privacidade</h1>
        <p class="text-muted text-center">Última atualização: 02 de novembro de 2024</p>

        <section class="mt-4">
            <h2>1. Introdução</h2>
            <p>Esta Política de Privacidade descreve como a nossa loja de produtos naturais coleta, utiliza e protege as informações pessoais dos usuários. Ao utilizar nosso site, você concorda com as práticas descritas aqui.</p>
        </section>

        <section class="mt-4">
            <h2>2. Coleta de Informações</h2>
            <p>Coletamos informações pessoais, como nome, e-mail, endereço e número de telefone, quando você realiza um cadastro, faz uma compra ou entra em contato conosco. Também coletamos dados de navegação de forma automática, como endereço IP e comportamento no site.</p>
        </section>

        <section class="mt-4">
            <h2>3. Uso das Informações</h2>
            <p>As informações coletadas são utilizadas para processar pedidos, melhorar nossos serviços, personalizar a experiência de compra e comunicar atualizações e ofertas. Não compartilhamos suas informações pessoais com terceiros sem o seu consentimento, exceto quando necessário para cumprir obrigações legais.</p>
        </section>

        <section class="mt-4">
            <h2>4. Proteção de Dados</h2>
            <p>Adotamos medidas de segurança técnicas e administrativas para proteger suas informações contra acesso não autorizado, perda ou uso indevido. No entanto, nenhum sistema é completamente seguro, e não podemos garantir a segurança absoluta dos dados.</p>
        </section>

        <section class="mt-4">
            <h2>5. Cookies</h2>
            <p>Utilizamos cookies para melhorar a experiência de navegação, analisando o comportamento do usuário e personalizando conteúdo. Você pode configurar seu navegador para recusar cookies, mas isso pode afetar o uso de algumas funcionalidades do site.</p>
        </section>

        <section class="mt-4">
            <h2>6. Direitos do Usuário</h2>
            <p>Você tem o direito de acessar, corrigir ou excluir suas informações pessoais a qualquer momento. Para exercer esses direitos, entre em contato conosco através do e-mail <a href="mailto:atentimento@baobaervas.com.br">atentimento@baobaervas.com.br</a>.</p>
        </section>

        <section class="mt-4">
            <h2>7. Alterações na Política de Privacidade</h2>
            <p>Reservamo-nos o direito de atualizar esta Política de Privacidade periodicamente. Recomendamos revisar esta página regularmente para estar ciente de qualquer mudança. O uso contínuo do site implica a aceitação da nova versão da política.</p>
        </section>

        <section class="mt-4 mb-5">
            <h2>8. Contato</h2>
            <p>Se tiver dúvidas ou preocupações sobre esta Política de Privacidade, entre em contato através do e-mail <a href="mailto:contato@atentimento@baobaervas.com.br">atentimento@baobaervas.com.br</a> ou pelo telefone (00) 1234-5678.</p>
        </section>
    </div>
    <?php require_once "./footer.php" ?>
</body>

</html>