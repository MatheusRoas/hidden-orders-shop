<?php
// filepath: c:\Users\Roas\Desktop\Loja de Cria\admin_logout.php
session_start();
session_destroy();
header('Location: admin_login.php');
exit;