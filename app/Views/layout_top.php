<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CRUD de Productos</title>

    <link rel="stylesheet" href="<?= e(url('assets/estilos.css')) ?>">
</head>

<body>
    <header>
        <div class="wrap header">

            <div>
                <strong>CRUD de Productos</strong>

                <small>
                    Desarrollo de Aplicaciones Web II
                </small>
            </div>

            <a href="<?= e(url()) ?>">
                Inicio
            </a>

        </div>
    </header>

    <main class="wrap">

        <?php if (!empty($_SESSION['flash'])): ?>

            <div class="alert ok">
                <?= e($_SESSION['flash']) ?>
            </div>

            <?php unset($_SESSION['flash']); ?>

        <?php endif; ?>
