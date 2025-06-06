<?php
// filepath: c:\Users\Roas\Desktop\Loja de Cria\processa_login.php
session_start();
require 'db.php';
initDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = trim($_POST['codigo'] ?? '');

    $db = db();
    $stmt = $db->prepare("SELECT * FROM codigos_acesso WHERE codigo = ? AND usado = 0 AND (expirado_em IS NULL OR expirado_em > datetime('now'))");
    $stmt->execute([$codigo]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Marca o código como usado
        $db->prepare("UPDATE codigos_acesso SET usado = 1 WHERE id = ?")->execute([$row['id']]);
        // Cria sessão anônima
        $_SESSION['autenticado'] = true;
        $_SESSION['codigo_id'] = $row['id'];
        // Gere um ID falso para o usuário
        if (!isset($_SESSION['id_falso'])) {
            $_SESSION['id_falso'] = bin2hex(random_bytes(4));
        }
        header('Location: pedido.php');
        exit;
    } else {
        // Código inválido ou expirado
        $_SESSION['erro_login'] = "Código inválido ou expirado.";
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}