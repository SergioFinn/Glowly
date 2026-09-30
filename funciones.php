<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start(['cookie_httponly'=>true, 'cookie_samesite'=>'Lax']);
}
date_default_timezone_set('America/Mexico_City');
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token']=bin2hex(random_bytes(32));
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function volver(string $pagina, string $mensaje, bool $error=false): void {
    $_SESSION['aviso']=['texto'=>$mensaje,'error'=>$error];
    header('Location: '.$pagina, true, 303); exit;
}
function aviso(): void {
    if (isset($_SESSION['aviso'])) {
        echo '<p class="aviso" role="status">'.e($_SESSION['aviso']['texto']).'</p>';
        unset($_SESSION['aviso']);
    }
}
function post_texto(string $key, int $max, bool $required=true): string {
    $value=$_POST[$key] ?? '';
    if (!is_string($value)) throw new DomainException('Datos inválidos.');
    $value=trim($value);
    if (($required && $value==='') || mb_strlen($value)>$max) throw new DomainException('Revisa el campo '.$key.'.');
    return $value;
}
function post_entero(string $key, int $min, int $max): int {
    $value=$_POST[$key] ?? null;
    if (!is_string($value) || filter_var($value,FILTER_VALIDATE_INT)===false || (int)$value<$min || (int)$value>$max)
        throw new DomainException('Revisa el campo '.$key.'.');
    return (int)$value;
}
function validar_post(): void {
    if ($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405); header('Allow: POST'); exit('Utiliza el formulario.'); }
    $token=$_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'],$token)) {
        http_response_code(403); exit('El formulario expiró o ya fue enviado. Vuelve a abrirlo.');
    }
}
function cliente(PDO $pdo): int {
    $nombre=post_texto('nombre',120); $correo=post_texto('correo',190); $telefono=post_texto('telefono',25,false);
    if (!filter_var($correo,FILTER_VALIDATE_EMAIL)) throw new DomainException('Escribe un correo válido.');
    // Conserva datos anteriores; no sobrescribe un cliente por una solicitud anónima.
    $q=$pdo->prepare('INSERT INTO clientes(nombre,correo,telefono) VALUES(?,?,?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)');
    $q->execute([$nombre,$correo,$telefono ?: null]);
    return (int)$pdo->lastInsertId();
}
function encabezado(string $titulo): void {
    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.e($titulo).' - Glowly</title><link rel="stylesheet" href="estilos.css"></head><body><nav aria-label="Navegación principal"><a href="index.html">Inicio</a><a href="catalogo.php">Catálogo</a><a href="nosotros.html">Sobre Glowly</a><a href="asesoria.php">Asesoría de Imagen</a><a href="seguridad_de_pagos.html">Seguridad y Pagos</a></nav><main class="contenido"><h1>'.e($titulo).'</h1>';
    aviso();
}
function pie(): void { echo '</main><footer>Glowly © 2026 · Proyecto académico · Sin cobros reales</footer></body></html>'; }
