<?php
require 'db.php';
initDatabase(); // Garante que todas as tabelas existem

$codigo = strtoupper(bin2hex(random_bytes(3))); // Exemplo: 6 caracteres, tipo "A1B2C3"
$expira_em_horas = 24; // Código válido por 24 horas

$db = db();
$db->prepare("INSERT INTO codigos_acesso (codigo, expirado_em) VALUES (?, datetime('now', ?))")
   ->execute([$codigo, "+$expira_em_horas hours"]);

echo "Código gerado: $codigo\nValidade: $expira_em_horas horas\n";