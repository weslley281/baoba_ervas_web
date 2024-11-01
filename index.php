<?php
session_start();

require_once "config/db.php";
require_once "config/CreateTables.php";

require_once "./utils/renderAlert.php";
require_once "./utils/truncate.php";
require_once './utils/openssl.php';
require_once './utils/generateRandomPassword.php';

require_once './models/User.php';
require_once './models/Product.php';
require_once './models/Category.php';

$createTable = new CreateTables;
$user = new User($conn);
$product = new Product($conn);
$category = new Category($conn);

$createTable->createUsersTable($conn);
$createTable->createProductsTable($conn);
$createTable->createCategoriesTable($conn);

$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? '';

$titles = [
    'home' => 'Pagina inicial',
    'login' => 'Login',
    'users' => 'Usuários',
    'profile' => 'Perfil e Administração'
];

$page_title = isset($titles[$page]) ? $titles[$page] : 'Página não encontrada';

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

require_once "./header.php";
?>

<body>
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

            default:
                echo "<h1>Página não encontrada</h1>";
        }
        ?>
    </div>

    <?php require_once "./footer.php" ?>
</body>

</html>