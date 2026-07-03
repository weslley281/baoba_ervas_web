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
        $password = !empty($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : '';
        $cpf = !empty($post['cpf']) ? encrypt($post['cpf'], ENCRYPTION_KEY) : null;

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
            "birth_date" => htmlspecialchars($post["birth_date"] ?? ''),
            "password" => $password,
            "user_type" => 'client',
            "cpf" => $cpf,
            "gender" => htmlspecialchars($post["gender"] ?? '')
        ];
    }

    switch ($action) {
        case 'create':
            if ($_POST["password"] == $_POST["confirmPassword"]) {

                $data = getUserData($_POST);

                if ($user->create($data)) {
                    header("Location: ../index.php?page=login");
                } else {
                    header("Location: ../index.php?page=login&action=fail");
                }
            } else {
                echo "<center><strong><h1>As duas senhas diferem uma da outra</h1></strong></center>";
                echo "<script>";
                echo "setTimeout(function() { window.location.href = '../index.php?page=login&action=fail'; }, 3000);";
                echo "</script>";
            }
            break;

        case 'update':
            if ($user_id === null) {
                header("Location: ../index.php?page=profile&action=invalid");
                exit;
            }
            $data = getUserData($_POST);
            $existing_user = $user->getById($user_id);
            if ($existing_user) {
                $data['user_type'] = $existing_user['user_type'];
                if (empty($data['password']) && !empty($existing_user['password'])) {
                    $data['password'] = $existing_user['password'];
                }
            }
            
            $redirect_url = "../index.php?page=profile&action=saved";
            $fail_url = "../index.php?page=profile&action=fail";
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin' && $user_id != $_SESSION['user_id']) {
                $redirect_url = "../index.php?page=profile&action=users&action2=saved";
                $fail_url = "../index.php?page=profile&action=users&action2=fail";
            }

            if ($user->update($data, $user_id)) {
                header("Location: " . $redirect_url);
            } else {
                header("Location: " . $fail_url);
            }
            break;

        case 'updatetype':
            if ($user_id === null) {
                header("Location: ../index.php?page=profile&action=users&action2=invalid");
                exit;
            }

            if ($user->updateType($_POST["user_type"], $user_id)) {
                header("Location: ../index.php?page=profile&action=users&action2=saved");
            } else {
                header("Location: ../index.php?page=profile&action=users&action2=fail");
            }
            break;

        case 'delete':
            if ($user_id === null) {
                header("Location: ../index.php?page=profile&action=users&action2=invalid");
                exit;
            }
            if ($user->delete($user_id)) {
                header("Location: ../index.php?page=profile&action=users&action2=deleted");
            } else {
                header("Location: ../index.php?page=profile&action=users&action2=fail");
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
