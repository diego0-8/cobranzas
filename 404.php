<?php
/**
 * Vista de Error 404
 * CRM de Cobranzas - Página no encontrada
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada | <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container {
            text-align: center;
            color: white;
        }
        .error-code {
            font-size: 8rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .error-message {
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-message">Página no encontrada</div>
        <p class="mb-4">La página que buscas no existe o ha sido movida.<br> diego se nos daño la pagina  </p>
        <a href="<?= baseUrl() ?>" class="btn btn-light btn-lg">
            <i class="fas fa-home me-2"></i>Volver al inicio
        </a>
    </div>
</body>
</html>
