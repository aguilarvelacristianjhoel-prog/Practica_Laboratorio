
<section class="panel">

    <h1>Lista de productos</h1>

    <p>Productos registrados en el sistema</p>

    <a href="<?= e(url('productos/crear')) ?>">
        + Nuevo producto
    </a>

    <br><br>

    <div style="overflow-x: auto;">

        <table border="1" cellpadding="12" cellspacing="0"
               style="width: 100%; border-collapse: collapse; text-align: left;">

            <thead style="background-color: #243b55; color: white;">
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($productos as $producto): ?>

                    <tr>
                        <td><?= e($producto['codigo']) ?></td>

                        <td>
                            <strong><?= e($producto['nombre']) ?></strong>
                            <br>
                            <small><?= e($producto['descripcion'] ?? '') ?></small>
                        </td>

                        <td><?= e($producto['categoria']) ?></td>

                        <td><?= e($producto['marca']) ?></td>

                        <td>
                            S/ <?= number_format((float)$producto['precio'], 2) ?>
                        </td>

                        <td><?= e($producto['stock']) ?></td>

                        <td>
                            <a href="<?= e(url('productos/editar/' . $producto['id'])) ?>">
                                Editar
                            </a>

                            <form method="post"
                                  action="<?= e(url('productos/eliminar/' . $producto['id'])) ?>"
                                  onsubmit="return confirm('¿Desea eliminar este producto?')">

                                <input type="hidden" name="csrf"
                                       value="<?= e($_SESSION['csrf']) ?>">

                                <button type="submit">
                                    Eliminar
                                </button>

                            </form>
                        </td>
                    </tr>

                <?php endforeach; ?>

                <?php if (empty($productos)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">
                            No hay productos registrados.
                        </td>
                    </tr>
                <?php endif; ?>

            </tbody>

        </table>

    </div>

</section>
