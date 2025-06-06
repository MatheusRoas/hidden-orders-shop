🌿 Hidden Orders Shop - Sistema de Pedidos y Chat vía Bot de Telegram
Este proyecto es un sistema completo para gestionar pedidos y facilitar la comunicación entre el administrador y los clientes, todo orquestado a través de un bot de Telegram. El enfoque principal es la simplicidad, seguridad y anonimato para el usuario final, utilizando códigos de acceso para la entrada a la tienda y el bot como un proxy de comunicación.

✨ Características Principales
Sistema de Acceso por Código: Los clientes requieren un código único y temporal para acceder a la plataforma de pedidos.
Gestión de Pedidos:
Registro de Pedidos: Los clientes pueden registrar pedidos directamente a través del bot de Telegram.
Visualización de Pedidos: Tanto el cliente (vía bot) como el administrador (vía panel) pueden ver el estado y los detalles de los pedidos.
Actualización de Estado: El administrador puede actualizar fácilmente el estado de los pedidos (pendiente, en proceso, completado, cancelado).
Panel Administrativo Completo: Una interfaz web intuitiva para que el administrador gestione:
Generación de códigos de acceso.
Visualización y gestión de todos los pedidos.
Chat Proxy con Clientes: Una funcionalidad central que permite al administrador conversar directamente con los clientes a través del bot de Telegram, funcionando como un intermediario seguro y anónimo.
Integración con Bot de Telegram: El bot sirve como la interfaz principal para que los clientes realicen pedidos e interactúen con el administrador, garantizando una experiencia discreta y basada en chat.
🚀 Cómo Funciona (Visión General)
Acceso: El administrador genera códigos de acceso únicos a través del panel. Estos códigos se distribuyen a los clientes.
Inicio de Sesión del Cliente: El cliente introduce el código de acceso en el bot de Telegram. Si el código es válido, obtiene acceso a las funcionalidades de la tienda.
Realización de Pedido: Una vez autenticado, el cliente puede seleccionar productos y realizar su pedido directamente a través del bot, que registra la información en la base de datos.
Gestión Administrativa: El administrador accede al panel web para ver nuevos pedidos, cambiar su estado y generar nuevos códigos.
Comunicación Bidireccional (Chat Proxy):
Cliente al Administrador: El cliente envía un mensaje al bot de Telegram. El bot reenvía este mensaje al panel del administrador.
Administrador al Cliente: El administrador responde desde el panel. El mensaje se envía de vuelta al cliente a través del bot de Telegram.
Este flujo garantiza que la comunicación siempre esté mediada por el bot, protegiendo la privacidad de ambas partes.
📁 Estructura del Proyecto
El proyecto está construido principalmente con PHP y SQLite para la base de datos, utilizando la API de Telegram para la interacción con el bot.

admin.php: Panel administrativo principal para la gestión de pedidos, códigos y el chat.
bot.php: El corazón de la integración con Telegram, responsable de procesar mensajes, comandos y actuar como proxy de chat.
db.php: Contiene la lógica de conexión con la base de datos SQLite y la creación de las tablas necesarias (pedidos, itens_pedido, codigos_acesso, admin, users, mensajes_chat).
gerar_codigo.php: Script simple para generar códigos de acceso (también integrado en admin.php).
index.php: Página de inicio de sesión inicial para los clientes usando el código de acceso (aunque la interacción principal es vía bot).
pedido.php: Interfaz web para que los clientes realicen pedidos (opcional, ya que el bot también lo hace).
pedidos-master.php: Posible interfaz para clientes con más detalles de productos (no usada directamente en el flujo del bot, pero puede ser una alternativa o complemento).

🛠️ Tecnologías Utilizadas
Backend: PHP
Base de Datos: SQLite
Frontend (Panel Admin): HTML, CSS, JavaScript (AJAX)
Integración: Telegram Bot API
🔒 Seguridad y Anonimato
El sistema ha sido diseñado pensando en la privacidad:

Códigos de Acceso Temporales: Limita el acceso a la tienda a usuarios autorizados por un tiempo limitado.
Bot como Proxy: La comunicación entre administrador y cliente siempre se realiza a través del bot de Telegram, sin exponer números de teléfono o identidades directas.
SQLite: Base de datos ligera e ideal para entornos que requieren una implementación rápida y sencilla.
🌟 Créditos
Este proyecto fue desarrollado por Matehus (MylasYearIlive)


![image](https://github.com/user-attachments/assets/87860395-7bee-4dad-b537-72495948a972) ![image](https://github.com/user-attachments/assets/b322f976-44cc-47fe-b6f1-8e9c39f351bb)

![image](https://github.com/user-attachments/assets/d2bfe374-834f-4561-b45e-89bc177e8324) ![image](https://github.com/user-attachments/assets/dc487d01-5f89-41dc-8472-9bd281d0c508)



