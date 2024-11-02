<?php
session_start();
if (isset($_SESSION["user_id"]) && $_SESSION['user_type'] == "admin") {
    require_once __DIR__ . '/../models/Category.php';
    require_once __DIR__ . '/../config/db.php';
    require_once __DIR__ . '/../utils/generateRandomPassword.php';
    require_once __DIR__ . '/../utils/openssl.php';

    function generateSlogan($string)
    {
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $string = preg_replace('/[^A-Za-z0-9]/', '_', $string);
        return strtolower($string);
    }

    $category = new Category($conn);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        $action = isset($_GET['action']) ? strtolower($_GET['action']) : '';

        function getcategoryData($post, $existingImagePath = null)
        {
            $directoryUpload = "../images/";
            $path = $existingImagePath; // Usa o caminho da imagem existente, se não houver nova imagem

            // Verifica se uma nova imagem foi enviada
            if (!empty($_FILES["image"]["name"])) {
                $imageName = uniqid() . "_" . basename($_FILES["image"]["name"]);
                $path = $directoryUpload . $imageName;
                $extensionImage = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                // Definindo as extensões de imagem permitidas
                $allowedExtensions = ["jpg", "jpeg", "gif", "png", "svg", "webp"];
                if (!in_array($extensionImage, $allowedExtensions)) {
                    echo "<center><strong><h1>Formato de imagem inválido. Use JPG, JPEG, GIF ou PNG.</h1></strong></center>";
                    header("Location: ../index.php?page=profile&action=categories&action2=invalid_format");
                    exit;
                }

                if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
                    echo "<center><strong><h1>Erro no upload da imagem. Código de erro: {$_FILES['image']['error']}</h1></strong></center>";
                    header("Location: ../index.php?page=profile&action=categories&action2=upload_error");
                    exit;
                }

                // Verifica se o tamanho do arquivo não excede o limite (por exemplo, 5MB)
                $maxFileSize = 5 * 1024 * 1024; // 5MB
                if ($_FILES["image"]["size"] > $maxFileSize) {
                    echo "<center><strong><h1>O arquivo é muito grande. O tamanho máximo é de 5MB.</h1></strong></center>";
                    header("Location: ../index.php?page=profile&action=categories&action2=file_too_large");
                    exit;
                }

                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $path)) {
                    echo "<center><strong><h1>Falha ao mover a imagem para o diretório de upload.</h1></strong></center>";
                    header("Location: ../index.php?page=profile&action=categories&action2=move_error");
                    exit;
                }
            }

            var_dump($path);
            if ($path == null || $path == "") {
                $path = $_POST["old_path_image"];
            }

            // Retorna os dados do produto com o caminho da imagem atualizado ou existente
            return [
                "name" => htmlspecialchars($post["name"] ?? ''),
                "slogan" => generateSlogan($post["name"] ?? ''),
                "description" => htmlspecialchars($post["description"] ?? ''),
                "path_image" => $path
            ];
        }


        switch ($action) {
            case 'create':
                $data = getcategoryData($_POST);

                if ($category->create($data)) {
                    header("Location: ../index.php?page=profile&action=categories&action2=success");
                } else {
                    echo $category->create($data);
                    header("Location: ../index.php?page=profile&action=categories&action2=fail");
                }

                break;

            case 'update': // Atualiza um usuário existente
                if ($id === null) {
                    echo "errei no id";
                    //header("Location: ../index.php?page=profile&action=categories&action2=invalid");
                    exit;
                }
                $data = getcategoryData($_POST);
                var_dump($data);
                if ($category->update($data, $id)) {
                    header("Location: ../index.php?page=profile&action=categories&action2=saved");
                } else {
                    //header("Location: ../index.php?page=profile&action=categories&action2=fail");
                }
                break;

            case 'delete': // Deleta um usuário pelo ID
                if ($id === null) {
                    header("Location: ../index.php?page=profile&action=categories&action2=invalid");
                    exit;
                }
                if ($category->delete($id)) {
                    header("Location: ../index.php?page=profile&action=categories&action2=deleted");
                } else {
                    header("Location: ../index.php?page=profile&action=categories&action2=fail");
                }
                break;

            default: // Se nenhuma ação for definida
                echo "<center><strong><h1>Ação incorreta</h1></strong></center>";
                header("Location: ../index.php?page=profile&action=categories&action2=unknown");
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
