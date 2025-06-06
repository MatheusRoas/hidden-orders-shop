
<?php
require 'db.php';

function sendMessage($chat_id, $text, $parse_mode = false) {
    $token = 'SEU_TOKEN_AQUI'; // Coloque seu token real aqui
    $url = "https://api.telegram.org/bot$token/sendMessage";
    $data = [
        'chat_id' => $chat_id,
        'text' => $text,
    ];
    if ($parse_mode) $data['parse_mode'] = 'Markdown';
    file_get_contents($url . '?' . http_build_query($data));
}

$content = file_get_contents("php://input");
$msg = json_decode($content, true)['message'] ?? null;
if (!$msg) exit;

$chat_id = $msg['chat']['id'];
$text = trim($msg['text'] ?? '');

$db = db();
$stmt = $db->prepare("SELECT * FROM users WHERE telegram_id = ?");
$stmt->execute([$chat_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $stmt = $db->prepare("INSERT INTO users (telegram_id, nome) VALUES (?, ?)");
    $stmt->execute([$chat_id, $msg['from']['first_name'] ?? 'Desconocido']);
    sendMessage($chat_id, "Bienvenido a Hidden Orders Shop 🌿\n\nEscribe el código de acceso para continuar.");
    exit;
}

if (!$user['autorizado']) {
    if ($text === '420420') {
        $db->prepare("UPDATE users SET autorizado = 1 WHERE telegram_id = ?")->execute([$chat_id]);
        sendMessage($chat_id, "Código aceptado ✅\nAhora tienes acceso a la tienda.");
    } else {
        sendMessage($chat_id, "Código incorrecto ❌\nInténtalo de nuevo.");
    }
    exit;
}

if ($text === '/start' || strtolower($text) === 'menu') {
    sendMessage($chat_id, "🌿 *¡Bienvenido a Hidden Orders Shop!*\n\nElige una opción:\n- Hacer pedido\n- Ver pedidos", true);
} elseif (strtolower($text) === 'hacer pedido') {
    sendMessage($chat_id, "¿Qué producto deseas?\n🌱 Producto A\n🌱 Producto B\n🌱 Producto C");
} elseif (in_array(strtolower($text), ['producto a', 'producto b', 'producto c'])) {
    $producto = ucfirst(strtolower($text));
    $db->prepare("INSERT INTO pedidos (user_id, producto, cantidad) VALUES (?, ?, 1)")->execute([$user['id'], $producto]);
    sendMessage($chat_id, "Pedido de *$producto* registrado con éxito 🍀", true);
} elseif (strtolower($text) === 'ver pedidos') {
    $stmt = $db->prepare("SELECT * FROM pedidos WHERE user_id = ? ORDER BY creado_em DESC LIMIT 5");
    $stmt->execute([$user['id']]);
    $pedidos = $stmt->fetchAll();
    if (count($pedidos) === 0) {
        sendMessage($chat_id, "Aún no has realizado ningún pedido.");
    } else {
        $mensaje = "📦 *Tus últimos pedidos:*\n";
        foreach ($pedidos as $p) {
            $mensaje .= "- {$p['producto']} ({$p['status']})\n";
        }
        sendMessage($chat_id, $mensaje, true);
    }
} else {
    sendMessage($chat_id, "No entendí... escribe `menu` para volver.", true);
}