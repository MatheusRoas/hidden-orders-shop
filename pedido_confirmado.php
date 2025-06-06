<?php

session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido confirmado</title>
    <style>
        body { background:#181f1b; color:#e0ffe0; font-family:sans-serif; text-align:center; padding-top:60px; }
        .msg { background:#232d25; display:inline-block; padding:30px 40px; border-radius:12px; }
        a { color:#ffd600; text-decoration:none; }
    </style>
</head>
<body>
    <div class="msg">
        <h1>✅ ¡Pedido confirmado!</h1>
        <p>Tu pedido ha sido registrado con éxito.<br>
        Espera el contacto para la entrega.</p>
        <a href="index.php">Volver al inicio</a>
    </div>
</body>
</html>