<?php
session_start();

if (isset($_SESSION["user_id"]) && $_SESSION["user_type"] == "admin") {
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    $faqFile = __DIR__ . '/faq.json';

    // Carrega o FAQ
    $faqData = [];
    if (file_exists($faqFile)) {
        $faqData = json_decode(file_get_contents($faqFile), true) ?? [];
    }

    if ($action === 'save') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['keyword'], $_POST['response'])) {
            $keyword = trim(strtolower($_POST['keyword']));
            $response = trim($_POST['response']);
            $oldKeyword = isset($_POST['old_keyword']) ? trim(strtolower($_POST['old_keyword'])) : '';

            // Se for uma edição e a palavra-chave mudou, remove a antiga
            if (!empty($oldKeyword) && $oldKeyword !== $keyword) {
                if (isset($faqData[$oldKeyword])) {
                    unset($faqData[$oldKeyword]);
                }
            }

            $faqData[$keyword] = $response;

            // Salva as alterações de volta no JSON de forma bonita
            if (file_put_contents($faqFile, json_encode($faqData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false) {
                header("Location: ../index.php?page=profile&action=chatbot_training&action2=success");
                exit();
            }
        }
    } elseif ($action === 'delete') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['keyword'])) {
            $keyword = trim(strtolower($_POST['keyword']));

            if (isset($faqData[$keyword])) {
                unset($faqData[$keyword]);
            }

            if (file_put_contents($faqFile, json_encode($faqData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false) {
                header("Location: ../index.php?page=profile&action=chatbot_training&action2=deleted");
                exit();
            }
        }
    }

    header("Location: ../index.php?page=profile&action=chatbot_training&action2=fail");
    exit();
} else {
    echo "<center><strong><h1>Você não tem permissão para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = '../index.php?page=login'; }, 3000);";
    echo "</script>";
}
