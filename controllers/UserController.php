<?php
session_start();
if (isset($_SESSION["user_id"]) && $_SESSION['type'] == "admin") {
    require_once __DIR__ . '/../models/User.php';
    require_once __DIR__ . '/../config/db.php';
    require_once __DIR__ . '/../utils/generateRandomPassword.php';
    require_once __DIR__ . '/../utils/openssl.php';

    $user = new User($conn);

    // Verifica o método HTTP
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validação e Sanitização dos Dados
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;

        // Verifica a ação a ser executada
        $action = isset($_GET['action']) ? strtolower($_GET['action']) : '';

        define('ENCRYPTION_KEY', 'gotosao');



        // Função para criar o array de dados do usuário, com tipo padrão como 'student'
        function getUserData($post)
        {
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $cpf = isset($post['cpf']) ? encrypt($post['cpf'], ENCRYPTION_KEY) : null;

            // Aplica a criptografia MD5 ao campo CPF
            //$cpf = isset($post['cpf']) ? md5($post['cpf']) : null;

            return [
                "name" => htmlspecialchars($post["name"] ?? ''),
                "phone" => htmlspecialchars($post["phone"] ?? ''),
                "email" => filter_var($post["email"], FILTER_VALIDATE_EMAIL),
                "address" => htmlspecialchars($post["address"] ?? ''),
                "complement" => htmlspecialchars($post["complement"] ?? ''),
                "country" => htmlspecialchars($post["country"] ?? ''),
                "state" => htmlspecialchars($post["state"] ?? ''),
                "city" => htmlspecialchars($post["city"] ?? ''),
                "neighborhood" => htmlspecialchars($post["neighborhood"] ?? ''),
                "postal_code" => htmlspecialchars($post["postal_code"] ?? ''),
                "gender" => htmlspecialchars($post["gender"] ?? ''),
                "birth_date" => htmlspecialchars($post["birth_date"] ?? ''),
                "password" => $password,
                "type" => 'student', // Define o tipo padrão como 'student'
                "cpf" => $cpf
            ];
        }

        // Executa ações conforme o parâmetro 'action'
        switch ($action) {
            case 'create': // Cria um novo usuário

                if ($_POST["password"] == $_POST["password2"]) {

                    $data = getUserData($_POST);

                    if ($user->create($data)) {
                        header("Location: ../index.php?page=users&action=success");
                    } else {
                        echo $user->create($data);
                        header("Location: ../index.php?page=users&action=fail");
                    }
                } else {
                    echo "<center><strong><h1>As duas senhas diferem uma da outra</h1></strong></center>";
                    echo "<script>";
                    echo "setTimeout(function() { window.location.href = '../index.php?page=users&action=fail'; }, 3000);";
                    echo "</script>";
                }
                break;

            case 'update': // Atualiza um usuário existente
                if ($id === null) {
                    header("Location: ../index.php?page=users&action=invalid");
                    exit;
                }
                $data = getUserData($_POST);
                if ($user->update($data, $id)) {
                    header("Location: ../index.php?page=users&action=saved");
                } else {
                    header("Location: ../index.php?page=users&action=fail");
                }
                break;

            case 'updatetype':
                if ($id === null) {
                    header("Location: ../index.php?page=users&action=invalid");
                    exit;
                }

                if ($user->updateType($_POST["type"], $id)) {
                    header("Location: ../index.php?page=users&action=saved");
                } else {
                    header("Location: ../index.php?page=users&action=fail");
                }
                break;

            case 'delete': // Deleta um usuário pelo ID
                if ($id === null) {
                    header("Location: ../index.php?page=users&action=invalid");
                    exit;
                }
                if ($user->delete($id)) {
                    header("Location: ../index.php?page=users&action=deleted");
                } else {
                    header("Location: ../index.php?page=users&action=fail");
                }
                break;

            default: // Se nenhuma ação for definida
                echo "<center><strong><h1>Ação incorreta</h1></strong></center>";
                header("Location: ../index.php?page=users&action=unknown");
                echo $_GET['action'];
                break;
        }
    }
} else {
    echo "<center><strong><h1>Você não Tem permição para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = '../index.php?page=login'; }, 3000);";
    echo "</script>";
}