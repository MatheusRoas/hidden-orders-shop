
<?php
session_start();
require 'db.php';

if (isset($_SESSION['admin_logado']) && $_SESSION['admin_logado'] === true) {
    header('Location: admin.php');
    exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    $db = db();
    $stmt = $db->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$user]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($admin && password_verify($pass, $admin['senha_hash'])) {
        $_SESSION['admin_logado'] = true;
        $_SESSION['admin_user'] = $user;
        header('Location: admin.php');
        exit;
    } else {
        $erro = "Usuario o contraseña inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Hidden Orders Shop</title>
    <style>
        body { background: #181f1b; color: #e0ffe0; font-family: Arial, sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; }
        form { background: #232d25; padding: 2em; border-radius: 8px; box-shadow: 0 0 10px #0004; }
        input { padding: 0.5em; border-radius: 4px; border: none; width: 200px; }
        button { padding: 0.5em 1.5em; border: none; border-radius: 4px; background: #3fa34d; color: #fff; font-weight: bold; cursor: pointer; }
        button:hover { background: #2e7d32; }
        .erro { color: #ff8080; }
    </style>
</head>
<body>
    <h2>Panel de Administración - Hidden Orders Shop</h2>
    <form method="POST">
        <label>Usuario:</label><br>
        <input type="text" name="username" required><br><br>
        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Entrar</button>
        <?php if ($erro): ?><div class="erro"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
    </form>
</body>
</html>