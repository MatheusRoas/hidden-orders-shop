<?php

require 'db.php';
initDatabase();

$db = db();
$db->exec("
CREATE TABLE IF NOT EXISTS admin (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    senha_hash TEXT
);
");

$username = 'admin';
$senha_hash = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';

$stmt = $db->prepare("SELECT COUNT(*) FROM admin WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->fetchColumn() == 0) {
    $db->prepare("INSERT INTO admin (username, senha_hash) VALUES (?, ?)")->execute([$username, $senha_hash]);
    echo "¡Administrador creado con éxito!";
} else {
    echo "El usuario administrador ya existe.";
}