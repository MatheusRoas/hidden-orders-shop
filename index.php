
<?php
session_start();
if (isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === true) {
    header('Location: pedido.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hidden Orders Shop - Acceso</title>
    <style>
        body { background: #181f1b; color: #e0ffe0; font-family: Arial, sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; }
        form { background: #232d25; padding: 2em; border-radius: 8px; box-shadow: 0 0 10px #0004; }
        input[type="text"] { padding: 0.5em; border-radius: 4px; border: none; width: 200px; }
        button { padding: 0.5em 1.5em; border: none; border-radius: 4px; background: #3fa34d; color: #fff; font-weight: bold; cursor: pointer; }
        button:hover { background: #2e7d32; }
    </style>
</head>
<body>
    <h2>Hidden Orders Shop 🌿</h2>
    <form method="POST" action="processa_login.php">
        <label for="codigo">Código de acceso:</label><br>
        <input type="text" id="codigo" name="codigo" required autofocus><br><br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>