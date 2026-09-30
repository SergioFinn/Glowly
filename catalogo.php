<?php
require_once __DIR__.'/funciones.php';
require_once __DIR__.'/conexion.php';

$categorias = $pdo->query('SELECT id, nombre FROM categorias ORDER BY id')->fetchAll();
$id_cat = filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT) ?: 0;

$q = $pdo->prepare('SELECT m.* FROM vista_menu m JOIN productos p ON p.id=m.producto_id WHERE (? = 0 OR p.categoria_id = ?) ORDER BY p.categoria_id, m.producto_id');
$q->execute([$id_cat, $id_cat]);
$catalogo = $q->fetchAll();

encabezado('Catálogo de Moda y Belleza');
?>

<p>Explora nuestras categorías de prendas casuales, formales, maquillaje y accesorios. Precios en MXN. Los pedidos de esta práctica no generan cobros ni envíos.</p>

<form method="get" class="filtro">
    <label for="categoria">Filtrar por categoría:</label>
    <select id="categoria" name="categoria">
        <option value="0">Todo el catálogo</option>
        <?php foreach($categorias as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $id_cat === (int)$c['id'] ? 'selected' : '' ?>><?= e($c['nombre']) ?></option>
        <?php endforeach ?>
    </select>
    <button type="submit">Filtrar</button>
</form>

<div class="scroll">
    <table class="tabla-boutique">
        <thead>
            <tr>
                <th>Artículo</th>
                <th>Descripción</th>
                <th style="text-align: center;">Vista</th>
                <th>Precio</th>
                <th style="width: 240px; text-align: center;">Disponibilidad</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($catalogo as $item): ?>
            <tr>
                <td><strong style="color: #4a4a4a; font-size: 16px;"><?= e($item['producto']) ?></strong><br><small style="color: #999;"><?= e($item['detalle']) ?></small></td>
                <td style="line-height: 1.6; max-width: 250px;"><?= e($item['descripcion']) ?></td>
                <td style="text-align: center;">
                    <?php $ruta=(string)($item['imagen']??''); $rutaLocal=realpath(__DIR__.'/'.$ruta); $raiz=realpath(__DIR__); $foto=$ruta!=='' && $rutaLocal && str_starts_with($rutaLocal,$raiz.DIRECTORY_SEPARATOR) && is_file($rutaLocal); ?>
                    <?php if($foto): ?>
                    <!-- Efecto hover (Cambia a la imagen 'alt' si tienes las fotos secundarias) -->
                    <img src="<?= e($ruta) ?>" width="95" class="img-catalogo" alt="<?= e($item['producto']) ?>">
                    <?php else: ?><span style="color: #d88c9a; font-size: 13px;">Fotografía pendiente</span><?php endif ?>
                </td>
                <td style="font-weight: bold; color: #4a4a4a; font-size: 16px;">$<?= number_format((float)$item['precio'], 2) ?></td>
                <td>
                    <?php if($item['stock'] > 0): ?>
                        <form action="procesar_pedido.php" method="post" class="form-pedido">
                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                            <input type="hidden" name="variante_id" value="<?= (int)$item['variante_id'] ?>">
                            
                            <label>Nombre
                                <input name="nombre" maxlength="120" autocomplete="name" required placeholder="Tu nombre">
                            </label>
                            
                            <label>Correo
                                <input type="email" name="correo" maxlength="190" autocomplete="email" required placeholder="tu@correo.com">
                            </label>
                            
                            <div class="fila-cantidad">
                                <label>Cant.</label>
                                <input type="number" name="cantidad" value="1" min="1" max="<?= (int)$item['stock'] ?>">
                            </div>
                            
                            <button type="submit" class="btn-comprar">Solicitar pedido</button>
                        </form>
                    <?php else: ?>
                        <div style="text-align: center;">
                            <span style="color: #b66d7b; font-weight: bold; background: #f4e8e9; padding: 6px 12px; border-radius: 20px; font-size: 13px;"><?= $item['stock']===null?'Por confirmar':'Agotado' ?></span>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(!$catalogo): ?>
                <tr><td colspan="5" style="text-align: center; padding: 40px; color: #999;">No hay artículos en esta categoría.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>