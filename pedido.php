<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌿 Alquimia - Pedidos Móvil/Tor</title>
    <style>
        :root {
            --primary: #7c3aed;
            --secondary: #10b981;
            --accent: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --dark-light: #1e293b;
            --text: #f1f5f9;
            --text-muted: #94a3b8;
            --border: #475569;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--dark);
            color: var(--text);
            line-height: 1.5;
            padding: 0.5rem;
        }
        
        /* HEADER COMPACTO */
        .header {
            background: var(--dark-light);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        /* BOTÃO PREMIUM */
        .premium-promo {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .premium-promo::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 10px,
                rgba(255,255,255,0.1) 10px,
                rgba(255,255,255,0.1) 20px
            );
            animation: shimmer-bg 3s linear infinite;
        }
        
        @keyframes shimmer-bg {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        .premium-text {
            position: relative;
            z-index: 1;
            color: white;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .premium-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .premium-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        
        /* PRODUTO SIMPLES */
        .produto {
            background: var(--dark-light);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .produto.selected {
            border-color: var(--secondary);
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
        }
        
        .produto.unavailable {
            opacity: 0.5;
            position: relative;
        }
        
        .produto.unavailable::after {
            content: 'AGOTADO';
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: var(--danger);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: bold;
        }
        
        .prod-header {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }
        
        .prod-img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--dark), var(--border));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }
        
        .prod-img::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: shimmer 2s infinite;
        }
        
        .prod-img[data-category="premium"] {
            background: linear-gradient(135deg, var(--primary), #5b21b6);
        }
        .prod-img[data-category="premium"]::after { content: '⚗️'; }
        
        .prod-img[data-category="legendaria"] {
            background: linear-gradient(135deg, var(--accent), #f97316);
        }
        .prod-img[data-category="legendaria"]::after { content: '👑'; }
        
        .prod-img[data-category="épica"] {
            background: linear-gradient(135deg, var(--secondary), #059669);
        }
        .prod-img[data-category="épica"]::after { content: '🔮'; }
        
        .prod-img[data-category="común"] {
            background: linear-gradient(135deg, #475569, var(--border));
        }
        .prod-img[data-category="común"]::after { content: '🧪'; }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .prod-info h4 {
            font-size: 1rem;
            margin-bottom: 0.25rem;
            color: var(--text);
        }
        
        .prod-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }
        
        .prod-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }
        
        .prod-price {
            background: var(--accent);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-weight: bold;
            font-size: 0.9rem;
        }
        
        .prod-stock {
            color: var(--text-muted);
            font-size: 0.8rem;
        }
        
        .prod-stock.low {
            color: var(--danger);
            font-weight: bold;
        }
        
        .effects-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
            margin-bottom: 0.75rem;
        }
        
        .effect {
            background: rgba(124, 58, 237, 0.2);
            color: var(--primary);
            padding: 0.15rem 0.5rem;
            border-radius: 12px;
            font-size: 0.7rem;
            border: 1px solid rgba(124, 58, 237, 0.3);
        }
        
        .qty-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .qty-input {
            width: 60px;
            height: 35px;
            background: var(--dark);
            border: 1px solid var(--border);
            color: var(--text);
            text-align: center;
            border-radius: 6px;
            font-weight: bold;
        }
        
        .qty-input:focus {
            outline: none;
            border-color: var(--primary);
        }
        
        /* TOTAL E FORMULÁRIO */
        .order-summary {
            background: var(--dark-light);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            position: sticky;
            top: 0.5rem;
        }
        
        .total-display {
            background: var(--secondary);
            color: white;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .total-display.error {
            background: var(--danger);
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: var(--text);
        }
        
        select, textarea {
            width: 100%;
            padding: 0.75rem;
            background: var(--dark);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 6px;
        }
        
        select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
        }
        
        .submit-btn {
            width: 100%;
            height: 50px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .submit-btn:hover:not(:disabled) {
            background: #5b21b6;
            transform: translateY(-2px);
        }
        
        .submit-btn:disabled {
            background: #475569;
            cursor: not-allowed;
            transform: none;
        }
        
        .warning {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid var(--accent);
            color: var(--accent);
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        
        .cat-title {
            font-size: 1.1rem;
            color: var(--secondary);
            margin: 1.5rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border);
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🌿 Alquimia</div>
        <div style="font-size: 0.9rem; color: var(--text-muted);">
            Versión Móvil/Tor
        </div>
    </div>
    
    <!-- PROMOCIÓN PREMIUM -->
    <div class="premium-promo">
        <div class="premium-text">
            ✨ ¿Quieres una experiencia PREMIUM?
        </div>
        <div style="font-size: 0.9rem; color: rgba(255,255,255,0.9); margin-bottom: 1rem;">
            • Interfaz Desktop avanzada<br>
            • Efectos visuales mejorados<br>
            • Más detalles de productos<br>
            • Navegación optimizada
        </div>
        <a href="pedidos-master.php" class="premium-btn">
            🚀 PROBAR VERSIÓN PREMIUM
        </a>
    </div>
    
    <form method="POST" action="processa_pedido.php" onsubmit="return validarPedido()">
        
        <!-- POCIONES MÁGICAS -->
        <h3 class="cat-title">✨ Pociones Mágicas</h3>
        
        <div class="produto" data-name="Poción Superior" data-price="5">
            <div class="prod-header">
                <div class="prod-img" data-category="premium"></div>
                <div class="prod-info">
                    <h4>Poción Superior</h4>
                    <p class="prod-desc">Regeneración completa de vida y resistencia</p>
                    <div class="prod-meta">
                        <span class="prod-price">5€/g</span>
                        <span class="prod-stock">Stock: 15g</span>
                    </div>
                </div>
            </div>
            <div class="effects-list">
                <span class="effect">Cura total</span>
                <span class="effect">Boost stamina</span>
                <span class="effect">Resistencia mágica</span>
            </div>
            <div class="qty-control">
                <input type="number" name="produtos[Poción Superior]" class="qty-input" 
                       min="0" max="10" value="0" data-price="5" oninput="atualizarTotal()">
                <span>gramas</span>
            </div>
        </div>
        
        <div class="produto" data-name="Elixir Aguante" data-price="5">
            <div class="prod-header">
                <div class="prod-img" data-category="premium"></div>
                <div class="prod-info">
                    <h4>Elixir Aguante</h4>
                    <p class="prod-desc">Resistencia infinita por 24 horas</p>
                    <div class="prod-meta">
                        <span class="prod-price">5€/g</span>
                        <span class="prod-stock">Stock: 8g</span>
                    </div>
                </div>
            </div>
            <div class="effects-list">
                <span class="effect">Stamina +∞</span>
                <span class="effect">Anti-fatiga</span>
                <span class="effect">Foco mental</span>
            </div>
            <div class="qty-control">
                <input type="number" name="produtos[Elixir Aguante]" class="qty-input" 
                       min="0" max="8" value="0" data-price="5" oninput="atualizarTotal()">
                <span>gramas</span>
            </div>
        </div>
        
        <div class="produto unavailable" data-name="Poción Invisibilidad" data-price="8">
            <div class="prod-header">
                <div class="prod-img" data-category="legendaria"></div>
                <div class="prod-info">
                    <h4>Poción Invisibilidad</h4>
                    <p class="prod-desc">Invisibilidad total por 2 horas</p>
                    <div class="prod-meta">
                        <span class="prod-price">8€/g</span>
                        <span class="prod-stock">Stock: 0g</span>
                    </div>
                </div>
            </div>
            <div class="effects-list">
                <span class="effect">Stealth 100%</span>
                <span class="effect">Sin detección</span>
                <span class="effect">Movimiento silencioso</span>
            </div>
        </div>
        
        <div class="produto" data-name="Poción Magia+" data-price="5">
            <div class="prod-header">
                <div class="prod-img" data-category="premium"></div>
                <div class="prod-info">
                    <h4>Poción Magia+</h4>
                    <p class="prod-desc">Mana ilimitado y conjuros potenciados</p>
                    <div class="prod-meta">
                        <span class="prod-price">5€/g</span>
                        <span class="prod-stock">Stock: 12g</span>
                    </div>
                </div>
            </div>
            <div class="effects-list">
                <span class="effect">Mana +200%</span>
                <span class="effect">Conjuros gratis</span>
                <span class="effect">Poder mágico x2</span>
            </div>
            <div class="qty-control">
                <input type="number" name="produtos[Poción Magia+]" class="qty-input" 
                       min="0" max="10" value="0" data-price="5" oninput="atualizarTotal()">
                <span>gramas</span>
            </div>
        </div>
        
        <!-- INGREDIENTES RAROS -->
        <h3 class="cat-title">🔮 Ingredientes Raros</h3>
        
        <div class="produto" data-name="Ala Murciélago" data-price="4">
            <div class="prod-header">
                <div class="prod-img" data-category="común"></div>
                <div class="prod-info">
                    <h4>Ala Murciélago</h4>
                    <p class="prod-desc">Componente para pociones de vuelo</p>
                    <div class="prod-meta">
                        <span class="prod-price">4€/g</span>
                        <span class="prod-stock">Stock: 25g</span>
                    </div>
                </div>
            </div>
            <div class="effects-list">
                <span class="effect">Ingrediente vuelo</span>
                <span class="effect">Levitación</span>
                <span class="effect">Velocidad</span>
            </div>
            <div class="qty-control">
                <input type="number" name="produtos[Ala Murciélago]" class="qty-input" 
                       min="0" max="10" value="0" data-price="4" oninput="atualizarTotal()">
                <span>gramas</span>
            </div>
        </div>
        
        <div class="produto unavailable" data-name="Polvo Vampiro" data-price="6">
            <div class="prod-header">
                <div class="prod-img" data-category="épica"></div>
                <div class="prod-info">
                    <h4>Polvo Vampiro</h4>
                    <p class="prod-desc">Polvo de vampiro ancestral</p>
                    <div class="prod-meta">
                        <span class="prod-price">6€/g</span>
                        <span class="prod-stock">Stock: 0g</span>
                    </div>
                </div>
            </div>
            <div class="effects-list">
                <span class="effect">Vida eterna</span>
                <span class="effect">Regeneración</span>
                <span class="effect">Poder nocturno</span>
            </div>
        </div>
        
        <div class="produto" data-name="Escama Dragón" data-price="7">
            <div class="prod-header">
                <div class="prod-img" data-category="épica"></div>
                <div class="prod-info">
                    <h4>Escama Dragón</h4>
                    <p class="prod-desc">Escama de dragón de fuego</p>
                    <div class="prod-meta">
                        <span class="prod-price">7€/g</span>
                        <span class="prod-stock low">Stock: 6g</span>
                    </div>
                </div>
            </div>
            <div class="effects-list">
                <span class="effect">Resistencia fuego</span>
                <span class="effect">Protección mágica</span>
                <span class="effect">Fuerza x3</span>
            </div>
            <div class="qty-control">
                <input type="number" name="produtos[Escama Dragón]" class="qty-input" 
                       min="0" max="6" value="0" data-price="7" oninput="atualizarTotal()">
                <span>gramas</span>
            </div>
        </div>
        
        <div class="produto" data-name="Corazón Daedra" data-price="9">
            <div class="prod-header">
                <div class="prod-img" data-category="legendaria"></div>
                <div class="prod-info">
                    <h4>Corazón Daedra</h4>
                    <p class="prod-desc">Corazón de demonio supremo</p>
                    <div class="prod-meta">
                        <span class="prod-price">9€/g</span>
                        <span class="prod-stock low">Stock: 3g</span>
                    </div>
                </div>
            </div>
            <div class="effects-list">
                <span class="effect">Poder supremo</span>
                <span class="effect">Magia oscura</span>
                <span class="effect">Invulnerabilidad</span>
            </div>
            <div class="qty-control">
                <input type="number" name="produtos[Corazón Daedra]" class="qty-input" 
                       min="0" max="3" value="0" data-price="9" oninput="atualizarTotal()">
                <span>gramas</span>
            </div>
        </div>
        
        <!-- RESUMO DO PEDIDO -->
        <div class="order-summary">
            <div class="total-display" id="totalDisplay">
                <div style="font-size: 1.5rem; font-weight: bold;">
                    <span id="totalG">0</span>g / 10g
                </div>
                <div style="font-size: 1.1rem;">
                    Total: <span id="totalPrice">0</span>€
                </div>
            </div>
            
            <div class="warning">
                ⚠️ Mínimo 1g • Máximo 10g por pedido
            </div>
            
            <div class="form-group">
                <label>🚚 Tipo de Entrega</label>
                <select name="entrega" id="entrega" onchange="atualizarTotal()" required>
                    <option value="">Seleccionar modalidad</option>
                    <option value="dia">Estándar - Mismo día (gratis)</option>
                    <option value="urgente">Express - 30min a 2h (+0.5€/g)</option>
                    <option value="madrugada">Nocturno - Horarios fijos (+1€/g)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>📍 Punto de Encuentro</label>
                <textarea name="localizacao" rows="3" required 
                          placeholder="Describe el lugar con precisión: color de puerta, números, referencias..."></textarea>
            </div>
            
            <button type="submit" class="submit-btn" id="submitBtn" disabled>
                🔒 Confirmar Pedido
            </button>
        </div>
    </form>

    <script>
        const CONFIG = {
            MIN_G: 1,
            MAX_G: 10,