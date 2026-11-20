<?php

$editando = isset($producto['id']);

if ($editando) {
    $accion = 'productos/actualizar/' . $producto['id'];
    $titulo = 'Editar producto';
    $boton = 'Guardar cambios';
} else {
    $accion = 'productos/guardar';
    $titulo = 'Registrar producto';
    $boton = 'Registrar producto';
}

?>

<style>
    .formulario-producto {
        max-width: 550px;
        margin: 30px auto;
        padding: 25px;
        border: 1px solid #dddddd;
        border-radius: 8px;
        background-color: white;
        font-family: Arial, sans-serif;
    }

    .formulario-producto h1 {
        margin-top: 0;
        font-size: 26px;
    }

    .formulario-producto .section-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
    }

    .formulario-producto .form-grid {
        display: flex;
        flex-direction: column;
        gap: 18px;
        margin-top: 20px;
    }

    .formulario-producto .form-grid label {
        display: flex;
        flex-direction: column;
        gap: 7px;
        font-size: 14px;
        font-weight: bold;
    }

    .formulario-producto input,
    .formulario-producto select,
    .formulario-producto textarea {
        display: block;
        width: 100%;
        padding: 10px;
        border: 1px solid #bbbbbb;
        border-radius: 5px;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
        font-size: 14px;
        font-weight: normal;
    }

    .formulario-producto .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 15px;
        margin-top: 25px;
    }

    .formulario-producto .form-actions button {
        background-color: #2471a3;
        color: white;
        padding: 11px 18px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .formulario-producto .form-actions a {
        color: #333333;
        text-decoration: none;
    }

    .formulario-producto .alert {
        margin-top: 15px;
    }
</style>

<section class="panel narrow formulario-producto">

    <div class="section-head">
        <div>
            <h1><?= $titulo ?></h1>
            <p>Complete los datos del producto.</p>
        </div>

        <a href="<?= e(url()) ?>" class="button">Volver</a>
    </div>

    <?php if (!empty($errores)): ?>
        <div class="alert error">
            <strong>Corrija los siguientes errores:</strong>

            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= e(url($accion)) ?>">

        <input type="hidden"
               name="csrf"
               value="<?= e($_SESSION['csrf']) ?>">

        <div class="form-grid">

            <label>
                Categoría *
                <select name="id_categoria" required>
                    <option value="">Seleccione una categoría</option>

                    <?php foreach ($categorias as $categoria): ?>
                        <option
                            value="<?= e($categoria['id']) ?>"
                            <?= ($producto['id_categoria'] ?? '') == $categoria['id'] ? 'selected' : '' ?>>
                            <?= e($categoria['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>
                Marca *
                <select name="id_marca" required>
                    <option value="">Seleccione una marca</option>

                    <?php foreach ($marcas as $marca): ?>
                        <option
                            value="<?= e($marca['id']) ?>"
                            <?= ($producto['id_marca'] ?? '') == $marca['id'] ? 'selected' : '' ?>>
                            <?= e($marca['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>
                Código *
                <input type="text"
                       name="codigo"
                       maxlength="30"
                       required
                       value="<?= e($producto['codigo'] ?? '') ?>">
            </label>

            <label>
                Nombre *
                <input type="text"
                       name="nombre"
                       maxlength="120"
                       required
                       value="<?= e($producto['nombre'] ?? '') ?>">
            </label>

            <label class="wide">
                Descripción
                <textarea name="descripcion"
                          maxlength="250"
                          rows="3"><?= e($producto['descripcion'] ?? '') ?></textarea>
            </label>

            <label>
                Precio (S/) *
                <input type="number"
                       name="precio"
                       step="0.01"
                       min="0"
                       max="99999999.99"
                       required
                       value="<?= e($producto['precio'] ?? '') ?>">
            </label>

            <label>
                Stock *
                <input type="number"
                       name="stock"
                       min="0"
                       step="1"
                       required
                       value="<?= e($producto['stock'] ?? 0) ?>">
            </label>

            <label>
                Unidad de medida *
                <input type="text"
                       name="unidad_medida"
                       maxlength="30"
                       required
                       value="<?= e($producto['unidad_medida'] ?? 'UNIDAD') ?>">
            </label>

            <label>
                Fecha de registro *
                <input type="date"
                       name="fecha_registro"
                       required
                       value="<?= e($producto['fecha_registro'] ?? date('Y-m-d')) ?>">
            </label>

        </div>

        <div class="form-actions">

            <a href="<?= e(url()) ?>" class="button">
                Cancelar
            </a>

            <button type="submit" class="button primary">
                <?= $boton ?>
            </button>

        </div>

    </form>

</section>
