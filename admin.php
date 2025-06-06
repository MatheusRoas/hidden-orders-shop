<?php

session_start();
require 'db.php';
initDatabase();

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header('Location: admin_login.php');
    exit;
}

$db = db();

// Generar código de acceso
$msg = '';
if (isset($_POST['gerar_codigo'])) {
    $codigo = strtoupper(bin2hex(random_bytes(3)));
    $expira = intval($_POST['expira'] ?? 24);
    $db->prepare("INSERT INTO codigos_acesso (codigo, expirado_em) VALUES (?, datetime('now', ?))")
        ->execute([$codigo, "+$expira hours"]);
    $msg = "Código generado: <b>$codigo</b> (válido por $expira horas)";
}

// Actualizar estado del pedido
if (isset($_POST['atualizar_pedido'])) {
    $id = intval($_POST['pedido_id']);
    $nuevo_status = $_POST['novo_status'];
    $db->prepare("UPDATE pedidos SET status = ? WHERE id = ?")->execute([$nuevo_status, $id]);
    $msg = "Estado del pedido #$id actualizado a: <b>$nuevo_status</b>";
}

// Eliminar pedido
if (isset($_POST['eliminar_pedido'])) {
    $id = intval($_POST['pedido_id']);
    $db->prepare("DELETE FROM itens_pedido WHERE pedido_id = ?")->execute([$id]);
    $db->prepare("DELETE FROM pedidos WHERE id = ?")->execute([$id]);
    $msg = "Pedido #$id eliminado correctamente";
}

// Filtros de búsqueda
$filtro_status = $_GET['status'] ?? '';
$filtro_entrega = $_GET['entrega'] ?? '';
$busca_id = $_GET['id'] ?? '';

// Construir consulta con filtros
$where_conditions = [];
$params = [];

if ($filtro_status) {
    $where_conditions[] = "status = ?";
    $params[] = $filtro_status;
}
if ($filtro_entrega) {
    $where_conditions[] = "entrega = ?";
    $params[] = $filtro_entrega;
}
if ($busca_id) {
    $where_conditions[] = "id = ?";
    $params[] = intval($busca_id);
}

$where_clause = empty($where_conditions) ? "" : "WHERE " . implode(" AND ", $where_conditions);
$pedidos = $db->prepare("SELECT * FROM pedidos $where_clause ORDER BY criado_em DESC LIMIT 50");
$pedidos->execute($params);
$pedidos = $pedidos->fetchAll(PDO::FETCH_ASSOC);

// Estadísticas
$stats = [
    'total_pedidos' => $db->query("SELECT COUNT(*) FROM pedidos")->fetchColumn(),
    'pendientes' => $db->query("SELECT COUNT(*) FROM pedidos WHERE status = 'pendiente'")->fetchColumn(),
    'aceptados' => $db->query("SELECT COUNT(*) FROM pedidos WHERE status = 'aceptado'")->fetchColumn(),
    'entregados' => $db->query("SELECT COUNT(*) FROM pedidos WHERE status = 'entregado'")->fetchColumn(),
    'cancelados' => $db->query("SELECT COUNT(*) FROM pedidos WHERE status = 'cancelado'")->fetchColumn(),
];

// Códigos activos
$codigos_activos = $db->query("SELECT * FROM codigos_acesso WHERE usado = 0 AND datetime(expirado_em) > datetime('now') ORDER BY expirado_em ASC")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Hidden Orders Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #388e3c;
            --primary-dark-green: #2e7d32;
            --secondary-dark: #1a231d;
            --text-light: #e0ffe0;
            --text-muted: #a5d6a7;
            --card-bg: #26322b;
            --input-bg: #37473b;
            --error-red: #d32f2f;
            --warning-yellow: #fdd835;
            --border-color: #4caf5055;
            --success-green: #4caf50;
            --info-blue: #2196f3;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--secondary-dark);
            color: var(--text-light);
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            min-height: 100vh;
        }

        .header {
            background: var(--card-bg);
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            border-bottom: 3px solid var(--primary-green);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: var(--primary-green);
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logout-btn {
            background: var(--error-red);
            color: white;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logout-btn:hover {
            background: #b71c1c;
            transform: translateY(-2px);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .stat-card.total { color: var(--info-blue); }
        .stat-card.pendiente { color: var(--warning-yellow); }
        .stat-card.aceptado { color: var(--success-green); }
        .stat-card.entregado { color: var(--primary-green); }
        .stat-card.cancelado { color: var(--error-red); }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .section {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
        }

        .section h2 {
            color: var(--primary-green);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            display: flex;
            gap: 1rem;
            align-items: end;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        input, select, button {
            padding: 0.7rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--input-bg);
            color: var(--text-light);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(56, 142, 60, 0.3);
        }

        .btn {
            cursor: pointer;
            font-weight: 600;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: var(--primary-green);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark-green);
        }

        .btn-warning {
            background: var(--warning-yellow);
            color: var(--secondary-dark);
        }

        .btn-danger {
            background: var(--error-red);
            color: white;
        }

        .btn-small {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }

        .filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            align-items: end;
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
        }

        .alert-success {
            background: rgba(76, 175, 80, 0.2);
            border: 1px solid var(--success-green);
            color: var(--success-green);
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--input-bg);
        }

        th, td {
            padding: 1rem 0.8rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background: var(--primary-green);
            color: white;
            font-weight: 600;
            position: sticky;
            top: 0;
        }

        tr:hover {
            background: rgba(56, 142, 60, 0.1);
        }

        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pendiente {
            background: rgba(253, 216, 53, 0.2);
            color: var(--warning-yellow);
            border: 1px solid var(--warning-yellow);
        }

        .status-aceptado {
            background: rgba(76, 175, 80, 0.2);
            color: var(--success-green);
            border: 1px solid var(--success-green);
        }

        .status-entregado {
            background: rgba(56, 142, 60, 0.2);
            color: var(--primary-green);
            border: 1px solid var(--primary-green);
        }

        .status-cancelado {
            background: rgba(211, 47, 47, 0.2);
            color: var(--error-red);
            border: 1px solid var(--error-red);
        }

        .product-list {
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .product-item {
            margin-bottom: 0.3rem;
        }

        .codigo-item {
            background: var(--input-bg);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .codigo-text {
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-green);
        }

        .codigo-expiry {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .container {
                padding: 1rem;
            }
            
            .dashboard-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
            
            .filters {
                flex-direction: column;
                align-items: stretch;
            }
            
            .form-group {
                flex-direction: column;
            }
            
            th, td {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1><i class="fas fa-leaf"></i> Panel Admin - Hidden Orders Shop</h1>
            <form method="POST" action="admin_logout.php">
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </div>

    <div class="container">
        <?php if ($msg): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <!-- Dashboard Stats -->
        <div class="dashboard-grid">
            <div class="stat-card total">
                <i class="fas fa-shopping-cart"></i>
                <div class="stat-number"><?= $stats['total_pedidos'] ?></div>
                <div>Total Pedidos</div>
            </div>
            <div class="stat-card pendiente">
                <i class="fas fa-clock"></i>
                <div class="stat-number"><?= $stats['pendientes'] ?></div>
                <div>Pendientes</div>
            </div>
            <div class="stat-card aceptado">
                <i class="fas fa-check"></i>
                <div class="stat-number"><?= $stats['aceptados'] ?></div>
                <div>Aceptados</div>
            </div>
            <div class="stat-card entregado">
                <i class="fas fa-truck"></i>
                <div class="stat-number"><?= $stats['entregados'] ?></div>
                <div>Entregados</div>
            </div>
            <div class="stat-card cancelado">
                <i class="fas fa-times"></i>
                <div class="stat-number"><?= $stats['cancelados'] ?></div>
                <div>Cancelados</div>
            </div>
        </div>

        <!-- Generar Códigos -->
        <div class="section">
            <h2><i class="fas fa-key"></i> Generar Código de Acceso</h2>
            <form method="POST">
                <div class="form-group">
                    <div>
                        <label for="expira">Válido por (horas):</label>
                        <input type="number" name="expira" id="expira" value="24" min="1" max="168" style="width: 100px;">
                    </div>
                    <button type="submit" name="gerar_codigo" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Generar Código
                    </button>
                </div>
            </form>
            
            <?php if (!empty($codigos_activos)): ?>
                <h3 style="color: var(--text-muted); margin-top: 2rem; margin-bottom: 1rem;">
                    <i class="fas fa-list"></i> Códigos Activos
                </h3>
                <?php foreach ($codigos_activos as $codigo): ?>
                    <div class="codigo-item">
                        <div>
                            <div class="codigo-text"><?= htmlspecialchars($codigo['codigo']) ?></div>
                            <div class="codigo-expiry">
                                Expira: <?= date('d/m/Y H:i', strtotime($codigo['expirado_em'])) ?>
                            </div>
                        </div>
                        <button onclick="copyToClipboard('<?= htmlspecialchars($codigo['codigo']) ?>')" 
                                class="btn btn-primary btn-small">
                            <i class="fas fa-copy"></i> Copiar
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Filtros de Pedidos -->
        <div class="section">
            <h2><i class="fas fa-filter"></i> Gestión de Pedidos</h2>
            <form method="GET" class="filters">
                <div>
                    <label for="status">Estado:</label>
                    <select name="status" id="status">
                        <option value="">Todos</option>
                        <option value="pendiente" <?= $filtro_status == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="aceptado" <?= $filtro_status == 'aceptado' ? 'selected' : '' ?>>Aceptado</option>
                        <option value="entregado" <?= $filtro_status == 'entregado' ? 'selected' : '' ?>>Entregado</option>
                        <option value="cancelado" <?= $filtro_status == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                </div>
                <div>
                    <label for="entrega">Tipo de Entrega:</label>
                    <select name="entrega" id="entrega">
                        <option value="">Todos</option>
                        <option value="dia" <?= $filtro_entrega == 'dia' ? 'selected' : '' ?>>Estándar</option>
                        <option value="urgente" <?= $filtro_entrega == 'urgente' ? 'selected' : '' ?>>Urgente</option>
                        <option value="madrugada" <?= $filtro_entrega == 'madrugada' ? 'selected' : '' ?>>Madrugada</option>
                    </select>
                </div>
                <div>
                    <label for="id">ID del Pedido:</label>
                    <input type="number" name="id" id="id" value="<?= htmlspecialchars($busca_id) ?>" placeholder="Buscar por ID">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Filtrar
                </button>
                <a href="admin.php" class="btn btn-warning">
                    <i class="fas fa-refresh"></i> Limpiar
                </a>
            </form>

            <!-- Tabla de Pedidos -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Productos</th>
                            <th>Precio</th>
                            <th>Entrega</th>
                            <th>Horario</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $p): ?>
                            <?php
                            // Buscar items del pedido
                            $stmt = $db->prepare("SELECT * FROM itens_pedido WHERE pedido_id = ?");
                            $stmt->execute([$p['id']]);
                            $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            // Calcular precio
                            $precio = 0;
                            $precio_unit = 5;
                            if ($p['entrega'] === 'urgente') $precio_unit = 5.5;
                            if ($p['entrega'] === 'madrugada') $precio_unit = 6;
                            if ($itens) {
                                foreach ($itens as $item) {
                                    $precio += $item['quantidade'] * $precio_unit;
                                }
                            }
                            ?>
                            <tr>
                                <td><strong>#<?= $p['id'] ?></strong></td>
                                <td>
                                    <div class="product-list">
                                        <?php if ($itens): ?>
                                            <?php foreach ($itens as $item): ?>
                                                <div class="product-item">
                                                    <strong><?= htmlspecialchars($item['produto']) ?></strong><br>
                                                    <small><?= htmlspecialchars($item['quantidade']) ?>g</small>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <em>Sin productos</em>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><strong>€<?= number_format($precio, 2, ',', '.') ?></strong></td>
                                <td>
                                    <?php
                                    $entrega_labels = [
                                        'dia' => 'Estándar',
                                        'urgente' => 'Urgente',
                                        'madrugada' => 'Madrugada'
                                    ];
                                    echo $entrega_labels[$p['entrega']] ?? $p['entrega'];
                                    ?>
                                </td>
                                <td><?= htmlspecialchars($p['horario']) ?></td>
                                <td>
                                    <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" 
                                         title="<?= htmlspecialchars($p['localizacao']) ?>">
                                        <?= htmlspecialchars($p['localizacao']) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= $p['status'] ?>">
                                        <?= htmlspecialchars($p['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= date('d/m/Y H:i', strtotime($p['criado_em'])) ?>
                                </td>
                                <td>
                                    <div class="actions">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="pedido_id" value="<?= $p['id'] ?>">
                                            <select name="novo_status" class="btn-small" onchange="this.form.submit()">
                                                <option value="pendiente" <?= $p['status'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                                <option value="aceptado" <?= $p['status'] == 'aceptado' ? 'selected' : '' ?>>Aceptado</option>
                                                <option value="entregado" <?= $p['status'] == 'entregado' ? 'selected' : '' ?>>Entregado</option>
                                                <option value="cancelado" <?= $p['status'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                            </select>
                                            <input type="hidden" name="atualizar_pedido" value="1">
                                        </form>
                                        <form method="POST" style="display: inline;" 
                                              onsubmit="return confirm('¿Estás seguro de eliminar este pedido?')">
                                            <input type="hidden" name="pedido_id" value="<?= $p['id'] ?>">
                                            <button type="submit" name="eliminar_pedido" class="btn btn-danger btn-small">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($pedidos)): ?>
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                    <i class="fas fa-inbox"></i> No se encontraron pedidos
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Mostrar feedback visual
                const btn = event.target.closest('button');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Copiado!';
                btn.style.background = 'var(--success-green)';
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = 'var(--primary-green)';
                }, 2000);
            });
        }

        // Auto-refresh cada 30 segundos si hay pedidos pendientes
        <?php if ($stats['pendientes'] > 0): ?>
        setTimeout(() => {
            location.reload();
        }, 30000);
        <?php endif; ?>
    </script>
</body>
</html>