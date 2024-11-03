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
        <h1 class="text-center">Termos e Condições</h1>
        <p class="text-muted text-center">Última atualização: 02 de novembro de 2024</p>

        <section class="mt-4">
            <h2>1. Introdução</h2>
            <p>Bem-vindo à nossa loja de produtos naturais. Estes Termos e Condições regulam o uso deste site e a compra de produtos oferecidos por nós. Ao utilizar este site, você concorda com estes termos. Se não concordar, por favor, não utilize nossos serviços.</p>
        </section>

        <section class="mt-4">
            <h2>2. Política de Privacidade</h2>
            <p>Levamos a sua privacidade a sério. Os dados fornecidos durante a compra e cadastro serão tratados com segurança e usados apenas para processar pedidos e melhorar nossos serviços. Para mais detalhes, consulte nossa <a href="policies.php">Política de Privacidade</a>.</p>
        </section>

        <section class="mt-4">
            <h2>3. Produtos e Serviços</h2>
            <p>Nossa loja oferece produtos naturais de alta qualidade. No entanto, recomendamos consultar um profissional de saúde antes de usar qualquer produto, especialmente em casos de condições médicas ou uso contínuo de medicamentos.</p>
        </section>

        <section class="mt-4">
            <h2>4. Responsabilidades do Usuário</h2>
            <p>O usuário concorda em fornecer informações precisas ao se cadastrar ou realizar uma compra, e a manter a confidencialidade dos dados de acesso. É responsabilidade do usuário informar qualquer uso não autorizado de sua conta.</p>
        </section>

        <section class="mt-4">
            <h2>5. Limitação de Responsabilidade</h2>
            <p>Nós não nos responsabilizamos por eventuais reações adversas aos produtos. Todas as descrições de produtos são fornecidas apenas para fins informativos, e o usuário deve avaliar as informações cuidadosamente antes de consumir.</p>
        </section>

        <section class="mt-4">
            <h2>6. Alterações nos Termos</h2>
            <p>Reservamo-nos o direito de atualizar estes Termos e Condições periodicamente. Recomendamos revisar esta página regularmente para estar ciente de qualquer mudança. O uso contínuo do site implica a aceitação dos novos termos.</p>
        </section>

        <section class="mt-4 mb-5">
            <h2>7. Contato</h2>
            <p>Se tiver dúvidas sobre estes Termos e Condições, entre em contato conosco através do e-mail <a href="mailto:contato@lojaprodutosnaturais.com">contato@lojaprodutosnaturais.com</a> ou pelo telefone (65) 3023-9010.</p>
        </section>
    </div>
    <?php require_once "./footer.php" ?>
</body>

</html>