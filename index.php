<?php
session_start();

require_once "config/db.php";
require_once "config/CreateTables.php";

require_once "./utils/renderAlert.php";
require_once "./utils/truncate.php";
require_once './utils/openssl.php';

require_once './models/User.php';

$createTable = new CreateTables;
$user = new User($conn);

$createTable->createUsersTable($conn);

$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? '';

$titles = [
    'home' => 'Pagina inicial',
    'login' => 'Login',
    'users' => 'Usuários'
];

$page_title = isset($titles[$page]) ? $titles[$page] : 'Página não encontrada';

if (!$user->getByEmail("baobaervas.com.br")) {
    $password = password_hash("Admin@123", PASSWORD_DEFAULT);

    $data = [
        "name" => 'Kenshydokan',
        "phone" => '65981233996',
        "email" => "instituto@kenshydokan.org.br",
        "address" => "R. 24 de Outubro, 185",
        "complement" => "Edifício",
        "country" => "Brasil",
        "state" => "Mato Grosso",
        "city" => "Várzea Grande",
        "neighborhood" => "Centro Norte",
        "postal_code" => "78110-520",
        "gender" => "masculine",
        "birth_date" => "2000-01-01",
        "password" => $password,
        "type" => 'admin',
        "cpf" => '10.707.722/0001-34',
    ];

    $user->create($data);
}

require_once "./header.php";
?>

<body>
    <?php include_once './views/navbar.php'; ?>

    <div class="container">
        <?php

        // Usando switch para simplificar condicionais
        switch ($page) {
            case 'home':
                include_once "./views/home.php";
                break;
        }
        ?>
    </div>

    <?php require_once "./footer.php" ?>
</body>

</html>