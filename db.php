<?php

function db() {
    static $db = null;
    if ($db === null) {
        $db = new PDO('sqlite:loja.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $db;
}

function initDatabase() {
    $db = db();

    // Tabela de pedidos (um pedido por cliente, com total e status)
    $db->exec("
        CREATE TABLE IF NOT EXISTS pedidos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id TEXT,
            entrega TEXT,
            horario TEXT,
            localizacao TEXT,
            total_gramas INTEGER,
            status TEXT,
            criado_em TEXT
        );
    ");

    // Tabela de itens do pedido (cada produto do pedido)
    $db->exec("
        CREATE TABLE IF NOT EXISTS itens_pedido (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            pedido_id INTEGER,
            produto TEXT,
            quantidade INTEGER,
            FOREIGN KEY(pedido_id) REFERENCES pedidos(id)
        );
    ");

    // Tabela de códigos de acesso (para login anônimo)
    $db->exec("
        CREATE TABLE IF NOT EXISTS codigos_acesso (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            codigo TEXT UNIQUE,
            usado INTEGER DEFAULT 0,
            expirado_em TEXT
        );
    ");

    // Tabela de admin (login do painel admin)
    $db->exec("
        CREATE TABLE IF NOT EXISTS admin (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE,
            senha_hash TEXT
        );
    ");

    // Tabela de usuários do bot (se usar bot Telegram)
    $db->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            telegram_id TEXT UNIQUE,
            nome TEXT,
            autorizado INTEGER DEFAULT 0
        );
    ");
}