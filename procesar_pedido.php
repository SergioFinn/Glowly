<?php
require_once __DIR__.'/funciones.php'; validar_post(); require __DIR__.'/conexion.php';
try {
    $variante=post_entero('variante_id',1,2147483647); $cantidad=post_entero('cantidad',1,999);
    $pdo->beginTransaction();
    $q=$pdo->prepare('SELECT p.producto,v.detalle,v.precio,v.stock FROM variantes v JOIN productos p ON p.id=v.producto_id WHERE v.id=? FOR UPDATE');
    $q->execute([$variante]);$item=$q->fetch();
    if(!$item || $item['precio']===null || (float)$item['precio']<=0) throw new DomainException('Producto no disponible.');
    if($item['stock']===null || (int)$item['stock']<$cantidad) throw new DomainException('No hay suficientes existencias para este pedido.');
    $idCliente=cliente($pdo);
    $q=$pdo->prepare('INSERT INTO pedidos(cliente_id) VALUES(?)');$q->execute([$idCliente]);$idPedido=$pdo->lastInsertId();
    $q=$pdo->prepare('INSERT INTO detalle_pedido(pedido_id,variante_id,nombre_producto,cantidad,precio_unitario) VALUES(?,?,?,?,?)');
    $q->execute([$idPedido,$variante,$item['producto'].' - '.$item['detalle'],$cantidad,$item['precio']]);
    $q=$pdo->prepare('UPDATE variantes SET stock=stock-? WHERE id=? AND stock>=?');$q->execute([$cantidad,$variante,$cantidad]);
    if($q->rowCount()!==1) throw new DomainException('Cambió la disponibilidad. Intenta nuevamente.');
    $pdo->commit();$_SESSION['csrf_token']=bin2hex(random_bytes(32));
    volver('catalogo.php','Pedido #'.$idPedido.' registrado por $'.number_format($cantidad*(float)$item['precio'],2).' MXN. No se realizó ningún cargo ni envío.');
} catch(DomainException $e) { if($pdo->inTransaction())$pdo->rollBack();volver('catalogo.php',$e->getMessage(),true);
} catch(Throwable $e) { if($pdo->inTransaction())$pdo->rollBack();error_log('Glowly pedido: '.$e->getMessage());volver('catalogo.php','No se pudo guardar el pedido. Verifica la actualización de la base de datos.',true); }
