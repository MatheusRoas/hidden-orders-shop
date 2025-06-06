<?php
session_start();

if (!isset($_SESSION['autenticado'])) {
    header('Location: index.php');
    exit;
}

// Produtos completos com mais detalhes para desktop
$produtos = [
    'Pociones Mágicas' => [
        'Poción Superior' => [
            'preco' => 5, 'disponivel' => true, 'stock' => 15,
            'desc' => 'Regeneración completa de vida y resistencia',
            'img' => 'img/pocion_superior.webp',
            'categoria' => 'premium',
            'efeitos' => ['Cura total', 'Boost stamina', 'Resistencia mágica']
        ],
        'Elixir Aguante' => [
            'preco' => 5, 'disponivel' => true, 'stock' => 8,
            'desc' => 'Resistencia infinita por 24 horas',
            'img' => 'img/elixir_aguante.webp',
            'categoria' => 'premium',
            'efeitos' => ['Stamina +∞', 'Anti-fatiga', 'Foco mental']
        ],
        'Poción Invisibilidad' => [
            'preco' => 8, 'disponivel' => false, 'stock' => 0,
            'desc' => 'Invisibilidad total por 2 horas',
            'img' => 'img/pocion_invisibilidad.webp',
            'categoria' => 'legendaria',
            'efeitos' => ['Stealth 100%', 'Sin detección', 'Movimiento silencioso']
        ],
        'Poción Magia+' => [
            'preco' => 5, 'disponivel' => true, 'stock' => 12,
            'desc' => 'Mana ilimitado y conjuros potenciados',
            'img' => 'img/pocion_magia.webp',
            'categoria' => 'premium',
            'efeitos' => ['Mana +200%', 'Conjuros gratis', 'Poder mágico x2']
        ],
    ],
    'Ingredientes Raros' => [
        'Ala Murciélago' => [
            'preco' => 4, 'disponivel' => true, 'stock' => 25,
            'desc' => 'Componente para pociones de vuelo',
            'img' => 'img/ala_murcielago.webp',
            'categoria' => 'común',
            'efeitos' => ['Ingrediente vuelo', 'Levitación', 'Velocidad']
        ],
        'Polvo Vampiro' => [
            'preco' => 6, 'disponivel' => false, 'stock' => 0,
            'desc' => 'Polvo de vampiro ancestral',
            'img' => 'img/polvo_vampiro.webp',
            'categoria' => 'épica',
            'efeitos' => ['Vida eterna', 'Regeneración', 'Poder nocturno']
        ],
        'Escama Dragón' => [
            'preco' => 7, 'disponivel' => true, 'stock' => 6,
            'desc' => 'Escama de dragón de fuego',
            'img' => 'img/escama_dragon.webp',
            'categoria' => 'épica',
            'efeitos' => ['Resistencia fuego', 'Protección mágica', 'Fuerza x3']
        ],
        'Corazón Daedra' => [
            'preco' => 9, 'disponivel' => true, 'stock' => 3,
            'desc' => 'Corazón de demonio supremo',
            'img' => 'img/corazon_daedra.webp',
            'categoria' => 'legendaria',
            'efeitos' => ['Poder supremo', 'Magia oscura', 'Invulnerabilidad']
        ],
    ]
];

$horarios = [
    'dia' => range(12, 23),
    'urgente' => ['agora', '30min', '1h', '2h'],
    'madrugada' => ['00:00', '03:00', '06:00', '09:00']
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌿 Alquimia Premium - Pedidos Master</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Cinzel:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* DESIGN PREMIUM DESKTOP */
        :root {
            --primary: #7c3aed;
            --primary-dark: #5b21b6;
            --secondary: #10b981;
            --accent: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --dark-light: #1e293b;
            --dark-lighter: #334155;
            --text: #f1f5f9;
            --text-muted: #94a3b8;
            --border: #475569;
            --glow: rgba(124, 58, 237, 0.3);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            color: var(--text);
            min-height: 100vh;
            line-height: 1.6;
        }
        
        /* HEADER ÉPICO */
        .header {
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.1) 0%, rgba(16, 185, 129, 0.1) 100%);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            padding: 2rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-family: 'Cinzel', serif;
            font-size: 2.5rem;
            font-weight: 600;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px var(--glow);
        }
        
        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .tor-test-btn {
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .tor-test-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: var(--text);
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        
        /* CONTAINER PRINCIPAL */
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        /* GRID PRINCIPAL */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            align-items: start;
        }
        
        /* SEÇÃO DE PRODUTOS */
        .products-section {
            background: rgba(30, 41, 59, 0.3);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .section-title {
            font-family: 'Cinzel', serif;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: var(--primary);
            text-align: center;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 2px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }
        
        /* CATEGORIAS */
        .categoria {
            margin-bottom: 3rem;
        }
        
        .cat-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 1.5rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .cat-title::before {
            content: '✨';
            font-size: 1.2rem;
        }
        
        /* PRODUTOS GRID */
        .produtos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .produto {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .produto::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .produto:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            border-color: var(--primary);
        }
        
        .produto:hover::before {
            opacity: 1;
        }
        
        .produto.selected {
            border-color: var(--secondary);
            background: rgba(16, 185, 129, 0.1);
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.2);
        }
        
        .produto.selected::before {
            opacity: 1;
            background: var(--secondary);
        }
        
        .produto.unavailable {
            opacity: 0.5;
            filter: grayscale(100%);
            position: relative;
        }
        
        .produto.unavailable::after {
            content: 'AGOTADO';
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--danger);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 1;
        }
        
        .prod-header {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .prod-img {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid var(--border);
            background: var(--dark);
            flex-shrink: 0;
        }
        
        .prod-info {
            flex: 1;
        }
        
        .prod-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.5rem;
        }
        
        .prod-desc {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }
        
        .prod-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .prod-price {
            background: linear-gradient(135deg, var(--accent), #f97316);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .prod-stock {
            color: var(--text-muted);
            font-size: 0.85rem;
        }
        
        .prod-stock.low {
            color: var(--danger);
            font-weight: 600;
        }
        
        .prod-effects {
            margin-bottom: 1rem;
        }
        
        .effects-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .effect {
            background: rgba(124, 58, 237, 0.2);
            color: var(--primary);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid rgba(124, 58, 237, 0.3);
        }
        
        .qty-control {
            display: flex;
            align-items: center;
            gap: 1rem;
            justify-content: space-between;
        }
        
        .qty-input {
            width: 80px;
            height: 40px;
            background: var(--dark);
            border: 1px solid var(--border);
            color: var(--text);
            text-align: center;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
        }
        
        .qty-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }
        
        /* SIDEBAR PEDIDO */
        .order-sidebar {
            position: sticky;
            top: 120px;
            height: fit-content;
        }
        
        .order-card {
            background: rgba(30, 41, 59, 0.3);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .order-title {
            font-family: 'Cinzel', serif;
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .total-display {
            background: linear-gradient(135deg, var(--secondary), #059669);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 2rem;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }
        
        .total-display.error {
            background: linear-gradient(135deg, var(--danger), #dc2626);
        }
        
        .total-amount {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .total-price {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        /* FORMULÁRIO */
        .form-section {
            margin-bottom: 2rem;
        }
        
        .form-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        select, textarea {
            width: 100%;
            padding: 1rem;
            background: var(--dark);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 8px;
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        
        select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }
        
        .time-options {
            display: none;
            background: var(--dark-light);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .time-options.active {
            display: block;
        }
        
        .submit-btn {
            width: 100%;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(124, 58, 237, 0.3);
        }
        
        .submit-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(124, 58, 237, 0.4);
        }
        
        .submit-btn:disabled {
            background: var(--dark-lighter);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        /* AVISOS */
        .warning {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid var(--accent);
            border-radius: 8px;
            padding: 1rem;
            color: var(--accent);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .info {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid #3b82f6;
            border-radius: 8px;
            padding: 1rem;
            color: #3b82f6;
            margin-bottom: 1rem;
        }
        
        /* RESPONSIVE */
        @media (max-width: 1200px) {
            .main-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .order-sidebar {
                position: static;
            }
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            
            .header-content {
                padding: 0 1rem;
                flex-direction: column;
                gap: 1rem;
            }
            
            .logo {
                font-size: 2rem;
            }
            
            .produtos-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* ANIMAÇÕES */
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 30px rgba(124, 58, 237, 0.2); }
            50% { box-shadow: 0 0 50px rgba(124, 58, 237, 0.4); }
        }
        
        .produto.selected {
            animation: glow 2s ease-in-out infinite;
        }
        
        /* SCROLLBAR CUSTOMIZADA */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--dark);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1 class="logo">🌿 Alquimia Premium</h1>
            <div class="header-actions">
                <a href="pedido.php" class="tor-test-btn">
                    📱 ¿Quieres probar la versión para móvil/Tor?
                </a>
                <div style="color: var(--text-muted); font-size: 0.9rem;">
                    Modo Desktop Premium
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="main-grid">
            <!-- PRODUTOS -->
            <div class="products-section">
                <h2 class="section-title">Catálogo Mágico</h2>
                
                <form method="POST" action="processa_pedido.php" onsubmit="return validarPedido()">
                    
                    <?php foreach ($produtos as $categoria => $items): ?>
                        <div class="categoria">
                            <h3 class="cat-title"><?= $categoria ?></h3>
                            
                            <div class="produtos-grid">
                                <?php foreach ($items as $nome => $dados): ?>
                                    <div class="produto <?= !$dados['disponivel'] ? 'unavailable' : '' ?>" 
                                         data-name="<?= htmlspecialchars($nome) ?>" 
                                         data-price="<?= $dados['preco'] ?>">
                                        
                                        <div class="prod-header">
                                            <img src="<?= htmlspecialchars($dados['img']) ?>" 
                                                 alt="<?= htmlspecialchars($nome) ?>" 
                                                 class="prod-img"
                                                 loading="lazy"
                                                 onerror="this.style.display='none'">
                                            
                                            <div class="prod-info">
                                                <h4 class="prod-name"><?= htmlspecialchars($nome) ?></h4>
                                                <p class="prod-desc"><?= htmlspecialchars($dados['desc']) ?></p>
                                                
                                                <?php if ($dados['disponivel']): ?>
                                                    <div class="prod-meta">
                                                        <span class="prod-price"><?= $dados['preco'] ?>€/g</span>
                                                        <span class="prod-stock <?= $dados['stock'] <= 5 ? 'low' : '' ?>">
                                                            Stock: <?= $dados['stock'] ?>g
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <?php if (isset($dados['efeitos'])): ?>
                                            <div class="prod-effects">
                                                <div class="effects-list">
                                                    <?php foreach ($dados['efeitos'] as $efeito): ?>
                                                        <span class="effect"><?= htmlspecialchars($efeito) ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($dados['disponivel']): ?>
                                            <div class="qty-control">
                                                <input type="number" 
                                                       name="produtos[<?= htmlspecialchars($nome) ?>]" 
                                                       class="qty-input" 
                                                       min="0" 
                                                       max="<?= min(10, $dados['stock']) ?>" 
                                                       value="0"
                                                       data-price="<?= $dados['preco'] ?>"
                                                       oninput="atualizarTotal()">
                                                <span>gramas</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
            </div>
            
            <!-- SIDEBAR PEDIDO -->
            <div class="order-sidebar">
                <div class="order-card">
                    <h3 class="order-title">🛍️ Tu Pedido</h3>
                    
                    <div class="total-display" id="totalDisplay">
                        <div class="total-amount">
                            <span id="totalG">0</span>g / 10g
                        </div>
                        <div class="total-price">
                            Total: <span id="totalPrice">0</span>€
                        </div>
                    </div>
                    
                    <div class="warning">
                        ⚠️ Mínimo 1g • Máximo 10g por pedido
                    </div>
                    
                    <div class="form-section">
                        <div class="form-title">🚚 Tipo de Entrega</div>
                        <select name="entrega" id="entrega" onchange="mostrarHorarios()" required>
                            <option value="">Seleccionar modalidad</option>
                            <option value="dia">Estándar - Mismo día (gratis)</option>
                            <option value="urgente">Express - 30min a 2h (+0.5€/g)</option>
                            <option value="madrugada">Nocturno - Horarios fijos (+1€/g)</option>
                        </select>
                        
                        <div id="time-dia" class="time-options">
                            <select name="horario_dia">
                                <option value="">Elegir hora</option>
                                <?php for($h = 12; $h <= 23; $h++): ?>
                                    <option value="<?= sprintf('%02d:00', $h) ?>"><?= sprintf('%02d:00', $h) ?></option>
                                    <option value="<?= sprintf('%02d:30', $h) ?>"><?= sprintf('%02d:30', $h) ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        
                        <div id="time-urgente" class="time-options">
                            <select name="horario_urgente">
                                <option value="agora">Inmediato</option>
                                <option value="30min">En 30 minutos</option>
                                <option value="1h">En 1 hora</option>
                                <option value="2h">En 2 horas</option>
                            </select>
                        </div>
                        
                        <div id="time-madrugada" class="time-options">
                            <select name="horario_madrugada">
                                <?php foreach($horarios['madrugada'] as $h): ?>
                                    <option value="<?= $h ?>"><?= $h ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <div class="form-title">📍 Punto de Encuentro</div>
                        <div class="info">
                            Describe el lugar con precisión: color de puerta, números visibles, referencias cercanas
                        </div>
                        <textarea name="localizacao" rows="4" required 
                                  placeholder="Ej: Portal verde nº 45B, al lado del Mercadona, esquina con Calle de la Rosa. Hay un banco blanco enfrente..."></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn" id="submitBtn" disabled>
                        🔒 Confirmar Pedido Premium
                    </button>
                </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const CONFIG = {
            MIN_G: 1,
            MAX_G: 10,
            DELIVERY_FEES: {
                'dia': 0,
                'urgente': 0.5,
                'madrugada': 1
            }
        };
        
        let currentTotal = 0;
        let currentPrice = 0;
        
        function atualizarTotal() {
            let totalG = 0;
            let totalPrice = 0;
            const deliveryType = document.getElementById('entrega').value;
            const deliveryFee = CONFIG.DELIVERY_FEES[deliveryType] || 0;
            
            document.querySelectorAll('.qty-input').forEach(input => {
                const qty = parseInt(input.value) || 0;
                const price = parseFloat(input.dataset.price) || 0;
                const finalPrice = price + deliveryFee;
                
                totalG += qty;
                totalPrice += qty * finalPrice;
                
                // Visual feedback
                const produto = input.closestp

                function atualizarTotal() {
            let totalG = 0;
            let totalPrice = 0;
            const deliveryType = document.getElementById('entrega').value;
            const deliveryFee = CONFIG.DELIVERY_FEES[deliveryType] || 0;
            
            document.querySelectorAll('.qty-input').forEach(input => {
                const qty = parseInt(input.value) || 0;
                const price = parseFloat(input.dataset.price) || 0;
                const finalPrice = price + deliveryFee;
                
                totalG += qty;
                totalPrice += qty * finalPrice;
                
                // Visual feedback
                const produto = input.closest('.produto');
                if (qty > 0) {
                    produto.classList.add('selected');
                } else {
                    produto.classList.remove('selected');
                }
            });
            
            currentTotal = totalG;
            currentPrice = totalPrice;
            
            // Atualizar display
            document.getElementById('totalG').textContent = totalG;
            document.getElementById('totalPrice').textContent = totalPrice.toFixed(2);
            
            const totalDisplay = document.getElementById('totalDisplay');
            const submitBtn = document.getElementById('submitBtn');
            
            // Validar limites
            if (totalG > CONFIG.MAX_G) {
                totalDisplay.classList.add('error');
                submitBtn.disabled = true;
                submitBtn.textContent = `🚫 Máximo ${CONFIG.MAX_G}g permitido`;
            } else if (totalG < CONFIG.MIN_G && totalG > 0) {
                totalDisplay.classList.add('error');
                submitBtn.disabled = true;
                submitBtn.textContent = `🚫 Mínimo ${CONFIG.MIN_G}g requerido`;
            } else if (totalG >= CONFIG.MIN_G && totalG <= CONFIG.MAX_G) {
                totalDisplay.classList.remove('error');
                submitBtn.disabled = false;
                submitBtn.textContent = '🔒 Confirmar Pedido Premium';
            } else {
                totalDisplay.classList.remove('error');
                submitBtn.disabled = true;
                submitBtn.textContent = '🛍️ Selecciona productos';
            }
        }
        
        function mostrarHorarios() {
            const entrega = document.getElementById('entrega').value;
            
            // Ocultar todos los horarios
            document.querySelectorAll('.time-options').forEach(div => {
                div.classList.remove('active');
                const select = div.querySelector('select');
                if (select) select.removeAttribute('required');
            });
            
            // Mostrar horario correspondiente
            if (entrega) {
                const timeDiv = document.getElementById(`time-${entrega}`);
                if (timeDiv) {
                    timeDiv.classList.add('active');
                    const select = timeDiv.querySelector('select');
                    if (select) select.setAttribute('required', 'required');
                }
            }
            
            // Recalcular total con nueva tarifa de entrega
            atualizarTotal();
        }
        
        function validarPedido() {
            const totalG = currentTotal;
            const entrega = document.getElementById('entrega').value;
            const localizacao = document.querySelector('textarea[name="localizacao"]').value.trim();
            
            // Validar cantidad
            if (totalG < CONFIG.MIN_G || totalG > CONFIG.MAX_G) {
                alert(`❌ Error: Debes pedir entre ${CONFIG.MIN_G}g y ${CONFIG.MAX_G}g`);
                return false;
            }
            
            // Validar entrega
            if (!entrega) {
                alert('❌ Error: Selecciona un tipo de entrega');
                return false;
            }
            
            // Validar horario según tipo de entrega
            const horarioSelect = document.querySelector(`#time-${entrega} select`);
            if (horarioSelect && !horarioSelect.value) {
                alert('❌ Error: Selecciona un horario de entrega');
                return false;
            }
            
            // Validar localización
            if (localizacao.length < 20) {
                alert('❌ Error: Describe el punto de encuentro con más detalle (mínimo 20 caracteres)');
                return false;
            }
            
            // Confirmación final
            const confirmMsg = `
🧙‍♀️ CONFIRMAR PEDIDO PREMIUM
━━━━━━━━━━━━━━━━━━━━━━━━

📦 Cantidad: ${totalG}g
💰 Total: ${currentPrice.toFixed(2)}€
🚚 Entrega: ${getDeliveryText(entrega)}
📍 Punto: ${localizacao.substring(0, 50)}...

¿Confirmar pedido?`;
            
            return confirm(confirmMsg);
        }
        
        function getDeliveryText(tipo) {
            const tipos = {
                'dia': 'Estándar - Mismo día',
                'urgente': 'Express - 30min a 2h',
                'madrugada': 'Nocturno - Horarios fijos'
            };
            return tipos[tipo] || tipo;
        }
        
        // Event listeners para inicialización
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar total
            atualizarTotal();
            
            // Auto-scroll suave al cambiar productos
            document.querySelectorAll('.qty-input').forEach(input => {
                input.addEventListener('change', function() {
                    if (parseInt(this.value) > 0) {
                        this.closest('.produto').scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                });
            });
            
            // Validación en tiempo real de la localización
            const localizacaoInput = document.querySelector('textarea[name="localizacao"]');
            if (localizacaoInput) {
                localizacaoInput.addEventListener('input', function() {
                    const length = this.value.trim().length;
                    if (length < 20) {
                        this.style.borderColor = 'var(--danger)';
                    } else {
                        this.style.borderColor = 'var(--secondary)';
                    }
                });
            }
            
            // Tooltips informativos
            document.querySelectorAll('.effect').forEach(effect => {
                effect.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05)';
                });
                
                effect.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
            
            // Sonido de feedback (opcional)
            const playSound = (type) => {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                const frequency = type === 'success' ? 800 : 400;
                oscillator.frequency.setValueAtTime(frequency, audioContext.currentTime);
                oscillator.type = 'sine';
                
                gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.1);
            };
            
            // Feedback sonoro ao selecionar produtos
            document.querySelectorAll('.qty-input').forEach(input => {
                input.addEventListener('change', function() {
                    if (parseInt(this.value) > 0) {
                        try {
                            playSound('success');
                        } catch (e) {
                            // Ignorar erro de áudio em alguns browsers
                        }
                    }
                });
            });
        });
        
        // Função para animação de loading no submit
        function showLoading() {
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = '🔄 Procesando pedido...';
            
            // Simular processamento
            setTimeout(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }, 2000);
        }
        
        // Adicionar loading ao formulário
        document.querySelector('form').addEventListener('submit', function(e) {
            if (validarPedido()) {
                showLoading();
            }
        });
        
        // Atalhos de teclado úteis
        document.addEventListener('keydown', function(e) {
            // Ctrl + Enter para submeter
            if (e.ctrlKey && e.key === 'Enter') {
                const submitBtn = document.getElementById('submitBtn');
                if (!submitBtn.disabled) {
                    document.querySelector('form').dispatchEvent(new Event('submit'));
                }
            }
            
            // Escape para limpar seleção
            if (e.key === 'Escape') {
                document.querySelectorAll('.qty-input').forEach(input => {
                    input.value = 0;
                });
                atualizarTotal();
            }
        });
        
        // Auto-save do rascunho (usando sessionStorage seria ideal, mas não disponível)
        let draftTimeout;
        function saveDraft() {
            clearTimeout(draftTimeout);
            draftTimeout = setTimeout(() => {
                // Aqui salvaria o rascunho se tivesse localStorage
                console.log('Draft saved (simulado)');
            }, 1000);
        }
        
        // Monitorar mudanças para salvar rascunho
        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.addEventListener('input', saveDraft);
            element.addEventListener('change', saveDraft);
        });
    </script>
</body>
</html>