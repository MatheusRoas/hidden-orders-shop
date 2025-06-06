<?php
// filepath: c:\Users\Roas\Desktop\Loja de Cria\logout.php
session_start();
session_destroy();
header('Location: index.php');
exit;