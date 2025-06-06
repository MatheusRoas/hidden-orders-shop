@echo off

REM Inicia o serviço Tor e PHP normalmente (ajuste os caminhos se necessário)
REM start /min "Servico Tor" cmd /k "cd /d %TOR_PATH% && tor.exe -f torrc"
REM start /min "Servico PHP" cmd /k "cd /d %PHP_PATH% && php -S 127.0.0.1:%PHP_PORT%"

REM Aguarda alguns segundos para garantir que o servidor subiu
timeout /t 3 >nul

REM Abre o site principal no navegador padrão
start "" http://127.0.0.1:8000/

REM Abre a página de admin no navegador padrão
start "" http://127.0.0.1:8000/admin.php

REM Executa o restante do seu script normalmente
cd /d %PHP_PATH%
php gerar_codigo.php
echo.
pause