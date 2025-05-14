<?php
session_start();

require_once "config/db.php";
require_once "config/CreateTables.php";

require_once "./utils/renderAlert.php";
require_once "./utils/truncate.php";
require_once './utils/openssl.php';
require_once './utils/generateRandomPassword.php';
require_once './utils/cart.php';

require_once './models/User.php';
require_once './models/Product.php';
require_once './models/Category.php';
require_once './models/Sale.php';
require_once './models/SaleItem.php';

$createTable = new CreateTables;
$user = new User($conn);
$product = new Product($conn);
$category = new Category($conn);
$sale = new Sale($conn);
$sale_item = new SaleItem($conn);

$createTable->createUsersTable($conn);
$createTable->createProductsTable($conn);
$createTable->createCategoriesTable($conn);
//$createTable->createSalesTable($conn);
$createTable->createSalesItemTable($conn);

if (!$user->getByEmail("admbaobabrasil@gmail.com")) {
    $password = password_hash("Admin@123", PASSWORD_DEFAULT);
    $cpf = encrypt("21.468.275/0002-05", ENCRYPTION_KEY);

    $data = [
        "name" => 'Adm Baobá',
        "phone" => '65981233996',
        "email" => "admbaobabrasil@gmail.com",
        "address" => "R. Feliciano Galdino, 585",
        "complement" => "Edifício",
        "country" => "Brasil",
        "state" => "Mato Grosso",
        "city" => "Cuiabá",
        "neighborhood" => "Poto",
        "postal_code" => "78110-520",
        "gender" => "masculine",
        "birth_date" => "2000-01-01",
        "password" => $password,
        "user_type" => 'admin',
        "cpf" => $cpf,
    ];

    $user->create($data);
}

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
    'profile' => 'Perfil',
    'product' => $name_product,
    'cart' => 'Carrinho',
    'register' => 'Registrar-se',
    'assessment' => 'Avaliação'
];

$page_title = isset($titles[$page]) ? $titles[$page] : 'Página não encontrada';

// Limita o título a 20 caracteres e adiciona reticências se for maior que 20
$page_title = strlen($page_title) > 20 ? substr($page_title, 0, 20) . '...' : $page_title;

require_once "./header.php";
?>

<body style="font-family: 'Roboto', sans-serif;">
    <?php include_once './views/navbar.php'; ?>

    <div class="container">
        <?php
        switch ($page) {
            case 'home':
                include_once "./views/home.php";
                break;

            case 'profile':
                require_once "./views/profile/home.php";
                break;

            case 'login':

                switch ($action) {
                    case 'fail':
                        echo renderAlert('danger', 'Erro!', 'Usuário ou senha inválido.');
                        break;
                }

                require_once "./views/login.php";
                break;

            case 'product':
                require_once "./views/product.php";
                break;

            case 'cart':
                require_once "./views/profile/cart.php";
                break;

            case 'register':
                require_once "./views/register.php";
                break;

            case 'assessment':
                require_once "./views/assessment.php";
                break;

            default:
                echo "<h1>Página não encontrada</h1>";
        }
        ?>
    </div>

    <?php require_once "./footer.php" ?>

    <!-- Modal -->
    <div class="modal fade" id="ModalWhatsapp" tabindex="-1" role="dialog" aria-labelledby="TituloModalLongoExemplo" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="TituloModalLongoExemplo">Quer Conversar com Qual Loja</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a id="widthOutDecoration" href="https://api.whatsapp.com/send/?phone=556533621007&text=Olá%20gostaria%20de%20tirar%20umas%20dúvidas" target="_blank">
                        <p class="text-success"><strong>Loja do Centro de Várzea Grande</strong></p>
                    </a>
                    <a id="widthOutDecoration" href="https://api.whatsapp.com/send/?phone=556533621008&text=Olá%20gostaria%20de%20tirar%20umas%20dúvidas" target="_blank">
                        <p class="text-success"><strong>Loja do Centro de Cuiabá</strong></p>
                    </a>
                    <a id="widthOutDecoration" href="https://api.whatsapp.com/send/?phone=556530239010&text=Olá%20gostaria%20de%20tirar%20umas%20dúvidas" target="_blank">
                        <p class="text-success"><strong>Loja do Porto - Cuiabá</strong></p>
                    </a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- Ícone flutuante para abrir a modal -->
<div class="chat-icon" data-toggle="modal" data-target="#chatbotModal">
    <i class="fas fa-comments"></i>
</div>

<!-- Modal do chatbot -->
<div class="modal fade" id="chatbotModal" tabindex="-1" role="dialog" aria-labelledby="TituloModalLongoExemplo" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chatbotModalLabel">Chatbot</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <!-- Conteúdo do chat -->

                <div id="chatContainer">
                    <div class="message bot-message" id="chat-messages" class="mb-3"></div>
                    <div id="user-input" class="input-group">
                        <input
                            type="text"
                            id="user-message"
                            class="form-control"
                            placeholder="Digite sua pergunta..." />
                        <button id="send-button" class="btn btn-success">Enviar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </html>