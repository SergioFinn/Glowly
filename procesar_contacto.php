<?php
require_once __DIR__.'/funciones.php'; validar_post(); require __DIR__.'/conexion.php';
try {
    $nombre=post_texto('nombre',120); $correo=post_texto('correo',190);
    if(!filter_var($correo,FILTER_VALIDATE_EMAIL)) throw new DomainException('Escribe un correo válido.');
    $color=post_texto('colorimetria',20); $evento=post_texto('evento',20); $comentarios=post_texto('comentarios',500,false);
    if(!in_array($color,['frios','calidos','neutros','no_se'],true) || !in_array($evento,['casual','oficina','noche'],true)) throw new DomainException('Selecciona preferencias válidas.');
    if(($_POST['acepta_politicas']??'')!=='1') throw new DomainException('Acepta el aviso de privacidad antes de continuar.');
    $q=$pdo->prepare('INSERT INTO asesorias(nombre,correo,colorimetria,evento,comentarios,acepta_politicas) VALUES(?,?,?,?,?,1)');
    $q->execute([$nombre,$correo,$color,$evento,$comentarios?:null]); $id=$pdo->lastInsertId();
    $_SESSION['csrf_token']=bin2hex(random_bytes(32));
    volver('asesoria.php','Solicitud de asesoría #'.$id.' guardada y pendiente de revisión. No se ha enviado ningún correo.');
} catch(DomainException $e) { volver('asesoria.php',$e->getMessage(),true);
} catch(Throwable $e) { error_log('Glowly asesoría: '.$e->getMessage()); volver('asesoria.php','No se pudo guardar la asesoría. Verifica la actualización de la base de datos.',true); }
