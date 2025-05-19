<?php
session_start();
if (isset($_SESSION["user_id"]) && $_SESSION['user_type'] == "admin") {
    require_once __DIR__ . '/../models/Product.php';
    require_once __DIR__ . '/../config/db.php';
    require_once __DIR__ . '/../utils/generateRandomPassword.php';
    require_once __DIR__ . '/../utils/openssl.php';

    function generateSlogan($string)
    {
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $string = preg_replace('/[^A-Za-z0-9]/', '_', $string);
        return strtolower($string);
    }

    $product = new product($conn);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        $action = isset($_GET['action']) ? strtolower($_GET['action']) : '';

        function getproductData($post, $existingImagePath = null)
        {
            //var_dump($existingImagePath);
            $directoryUpload = "../images/";
            $path = $existingImagePath;

            // Verifica se uma nova imagem foi enviada
            if (!empty($_FILES["image"]["name"])) {
                // Apaga a imagem existente, se houver
                if ($existingImagePath && file_exists($existingImagePath)) {

                    unlink($existingImagePath);
                }

                $imageName = uniqid() . "_" . basename($_FILES["image"]["name"]);
                $path = $directoryUpload . $imageName;
                $extensionImage = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                // Definindo as extensões de imagem permitidas
                $allowedExtensions = ["jpg", "jpeg", "gif", "png", "webp", "svg"];
                if (!in_array($extensionImage, $allowedExtensions)) {
                    echo "<center><strong><h1>Formato de imagem inválido. Use JPG, JPEG, GIF ou PNG.</h1></strong></center>";

                    echo "<script>";
                    echo "window.location.href = '../index.php?page=profile&action=products&action2=invalid_format';";
                    echo "</script>";
                    //header("Location: ../index.php?page=profile&action=products&action2=invalid_format");
                    exit;
                }

                if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
                    echo "<center><strong><h1>Erro no upload da imagem. Código de erro: {$_FILES['image']['error']}</h1></strong></center>";
                    echo "<script>";
                    echo "window.location.href = '../index.php?page=profile&action=products&action2=upload_error';";
                    echo "</script>";
                    //header("Location: ../index.php?page=profile&action=products&action2=upload_error");
                    exit;
                }

                // Verifica se o tamanho do arquivo não excede o limite (por exemplo, 5MB)
                $maxFileSize = 5 * 1024 * 1024; // 5MB
                if ($_FILES["image"]["size"] > $maxFileSize) {
                    echo "<center><strong><h1>O arquivo é muito grande. O tamanho máximo é de 5MB.</h1></strong></center>";
                    echo "<script>";
                    echo "window.location.href = '../index.php?page=profile&action=products&action2=file_too_large';";
                    echo "</script>";
                    //header("Location: ../index.php?page=profile&action=products&action2=file_too_large");
                    exit;
                }

                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $path)) {
                    echo "<center><strong><h1>Falha ao mover a imagem para o diretório de upload.</h1></strong></center>";
                    echo "<script>";
                    echo "window.location.href = '../index.php?page=profile&action=products&action2=move_error';";
                    echo "</script>";
                    //header("Location: ../index.php?page=profile&action=products&action2=move_error");
                    exit;
                }
            } else if ($path == null || $path == "") {
                $path = $_POST["old_path_image"];
            }

            // Retorna os dados do produto com o caminho da imagem atualizado ou existente
            return [
                "name" => htmlspecialchars($post["name"] ?? ''),
                "slogan" => generateSlogan($post["name"] ?? ''),
                "category_id" => generateSlogan($post["category_id"] ?? 0),
                "description" => htmlspecialchars($post["description"] ?? ''),
                "path_image" => $path,
                "price" => htmlspecialchars($post["price"] ?? 0),
                "discount" => htmlspecialchars($post["discount"] ?? 0),
                "stock_quantity" => htmlspecialchars($post["stock_quantity"] ?? 0),
                "reference" => htmlspecialchars($post["reference"] ?? ''),
                "active" => htmlspecialchars($post["active"] ?? 0)
            ];
        }



        switch ($action) {
            case 'create':
                $data = getproductData($_POST);

                if ($product->create($data)) {
                    echo "<center><strong><h1>Produto cadastrado com sucesso!</h1></strong></center>";
                    echo "<script>";
                    echo "window.location.href = '../index.php?page=profile&action=products&action2=success';";
                    echo "</script>";
                    //header("Location: ../index.php?page=profile&action=products&action2=success");
                } else {
                    echo $product->create($data);
                    echo "<center><strong><h1>Erro ao cadastrar produto - contate o desenvolvedor do software.</h1></strong></center>";
                    echo "<script>";
                    echo "window.location.href = '../index.php?page=profile&action=products&action2=fail';";
                    echo "</script>";
                    //header("Location: ../index.php?page=profile&action=products&action2=fail");
                }

                break;

            case 'update': // Atualiza um usuário existente
                if ($id === null) {
                    //echo "errei no id";
                    echo "<center><strong><h1>Erro ao editar produto - contate o desenvolvedor do software.</h1></strong></center>";
                    echo "<script>";
                    echo "window.location.href = '../index.php?page=profile&action=products&action2=invalid';";
                    echo "</script>";
                    //header("Location: ../index.php?page=profile&action=products&action2=invalid");
                    exit;
                }
                $data = getproductData($_POST, $_POST["old_path_image"]);
                //var_dump($data);
                if ($product->update($data, $id)) {
                    echo "<center><strong><h1>Produto editado com sucesso!</h1></strong></center>";
                    echo "<script>";
                    echo "window.location.href = '../index.php?page=profile&action=products&action2=saved';";
                    echo "</script>";
                    //header("Location: ../index.php?page=profile&action=products&action2=saved");
                } else {
                    echo "<center><strong><h1>Erro ao editar produto - contate o desenvolvedor do software.</h1></strong></center>";
                    echo "<script>";
                    echo "window.location.href = '../index.php?page=profile&action=products&action2=fail';";
                    echo "</script>";
                    //header("Location: ../index.php?page=profile&action=products&action2=fail");
                }
                break;

            case 'delete': // Deleta um usuário pelo ID
                if ($id === null) {
                    echo "<center><strong><h1>Erro ao editar produto - contate o desenvolvedor do software.</h1></strong></center>";
                    echo "<script>";
                    echo "window.location.href = '../index.php?page=profile&action=products&action2=invalid';";
                    echo "</script>";
                    //header("Location: ../index.php?page=profile&action=products&action2=invalid");
                    exit;
                }
                if ($product->delete($id)) {
                    echo "<center><strong><h1>Produto deletado com sucesso!</h1></strong></center>";
                    echo "<script>";
                    echo "window.location.href = '../index.php?page=profile&action=products&action2=deleted';";
                    echo "</script>";
                    //header("Location: ../index.php?page=profile&action=products&action2=deleted");
                } else {
                    echo "<center><strong><h1>Erro ao deletar produto - contate o desenvolvedor do software.</h1></strong></center>";
                    echo "<script>";
                    echo "window.location.href = '../index.php?page=profile&action=products&action2=fail';";
                    echo "</script>";
                    //header("Location: ../index.php?page=profile&action=products&action2=fail");
                }
                break;

            default: // Se nenhuma ação for definida
                echo "<center><strong><h1>Erro ao editar produto - contate o desenvolvedor do software.</h1></strong></center>";
                echo "<script>";
                echo "window.location.href = '../index.php?page=profile&action=products&action2=unknown';";
                echo "</script>";
                //header("Location: ../index.php?page=profile&action=products&action2=unknown");
                echo $_GET['action'];
                break;
        }
    } else {
        echo "<center><strong><h1>Requisição incorreta</h1></strong></center>";
        echo "<script>";
        echo "window.location.href = '../index.php'; }, 3000);";
        echo "</script>";
    }
} else {
    echo "<center><strong><h1>Você não Tem permição para isso</h1></strong></center>";
    echo "<script>";
    echo "window.location.href = '../index.php?page=login'; }, 3000);";
    echo "</script>";
}
