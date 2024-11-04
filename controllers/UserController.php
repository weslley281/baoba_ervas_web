<?php
session_start();
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../utils/generateRandomPassword.php';
require_once __DIR__ . '/../utils/openssl.php';

$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
    $action = isset($_GET['action']) ? strtolower($_GET['action']) : '';

    function getUserData($post)
    {
        $password = isset($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : '';
        $cpf = isset($post['cpf']) ? encrypt($post['cpf'], ENCRYPTION_KEY) : null;

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
            "user_type" => 'client',
            "cpf" => $cpf
        ];
    }

    switch ($action) {
        case 'create':
            if ($_POST["password"] == $_POST["password2"]) {

                $data = getUserData($_POST);

                if ($user->create($data)) {
                    header("Location: ../index.php?page=profile&action=success");
                } else {
                    echo $user->create($data);
                    header("Location: ../index.php?page=profile&action=fail");
                }
            } else {
                echo "<center><strong><h1>As duas senhas diferem uma da outra</h1></strong></center>";
                echo "<script>";
                echo "setTimeout(function() { window.location.href = '../index.php?page=profile&action=fail'; }, 3000);";
                echo "</script>";
            }
            break;

        case 'update':
            if ($user_id === null) {
                header("Location: ../index.php?page=profile&action=invalid");
                exit;
            }
            $data = getUserData($_POST);
            if ($user->update($data, $user_id)) {
                header("Location: ../index.php?page=profile&action=saved");
            } else {
                header("Location: ../index.php?page=profile&action=fail");
            }
            break;

        case 'updatetype':
            if ($user_id === null) {
                header("Location: ../index.php?page=profile&action=invalid");
                exit;
            }

            if ($user->updateType($_POST["user_type"], $user_id)) {
                header("Location: ../index.php?page=profile&action=saved");
            } else {
                header("Location: ../index.php?page=profile&action=fail");
            }
            break;

        case 'delete':
            if ($user_id === null) {
                header("Location: ../index.php?page=profile&action=invalid");
                exit;
            }
            if ($user->delete($user_id)) {
                header("Location: ../index.php?page=profile&action=deleted");
            } else {
                header("Location: ../index.php?page=profile&action=fail");
            }
            break;

        default:
            echo "<center><strong><h1>Ação incorreta</h1></strong></center>";
            header("Location: ../index.php?page=profile&action=unknown");
            echo $_GET['action'];
            break;
    }
} else {
    echo "<center><strong><h1>Requisição incorreta</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = '../index.php'; }, 3000);";
    echo "</script>";
}
