<?php
require 'db.php';
session_start();

if (!isset($_SESSION['autenticado'])) {
    header('Location: index.php');
    exit;
}

// Recebe os dados do formulário
$produtos = $_POST['produtos'] ?? [];
$entrega = $_POST['entrega'] ?? '';
$horario = $_POST['horario_dia'] ?? $_POST['horario_urgente'] ?? $_POST['horario_madrugada'] ?? '';
$localizacao = $_POST['localizacao'] ?? '';
$total = 0;
$itens = [];

// Monta array de itens e soma total
foreach ($produtos as $nome => $dados) {
    $qtd = intval($dados['quantidade'] ?? 0);
    if ($qtd > 0) {
        $itens[] = ['produto' => $nome, 'quantidade' => $qtd];
        $total += $qtd;
    }
}

// Validação do limite
if ($total == 0 || $total > 10) {
    die('Pedido inválido: selecione até 10g no total.');
}

// Salva pedido principal
$db = new PDO('sqlite:loja.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $db->prepare("INSERT INTO pedidos (user_id, entrega, horario, localizacao, total_gramas, status, criado_em) VALUES (?, ?, ?, ?, ?, 'pendente', datetime('now'))");
$stmt->execute([$_SESSION['codigo_id'], $entrega, $horario, $localizacao, $total]);
$id_pedido = $db->lastInsertId();

// Salva cada item do pedido
foreach ($itens as $item) {
    $stmt = $db->prepare("INSERT INTO itens_pedido (pedido_id, produto, quantidade) VALUES (?, ?, ?)");
    $stmt->execute([$id_pedido, $item['produto'], $item['quantidade']]);
}

header('Location: pedido_confirmado.php');
exit;