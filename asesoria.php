<?php
require_once __DIR__.'/funciones.php';
encabezado('Asesoría de Imagen Personalizada');
?>

<p>Cuéntanos tus preferencias para registrar una solicitud de asesoría. La revisión es manual; no se envían correos ni recomendaciones automáticas.</p>
<?php if(is_file(__DIR__.'/img/mapa_estilos.png')): ?>
<div style="text-align:center"><img src="img/mapa_estilos.png" alt="Paleta de estilos Glowly" style="max-width:100%"></div>
<?php endif ?>
<div class="form-container" style="background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); max-width: 600px; margin: auto;">
    <form action="procesar_contacto.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
        
        <label for="nombre">Nombre completo</label>
        <input type="text" id="nombre" name="nombre" maxlength="120" required style="width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px;">
        
        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo" maxlength="190" required style="width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px;">
        
        <label for="colorimetria">¿Con qué tonos te sientes más cómoda?</label>
        <select id="colorimetria" name="colorimetria" style="width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px;">
            <option value="frios">Tonos Fríos (Azules, Plateados, Violetas)</option>
            <option value="calidos">Tonos Cálidos (Rojos, Naranjas, Dorados)</option>
            <option value="neutros">Tonos Neutros (Beige, Blanco, Negro)</option>
            <option value="no_se">No estoy segura, ¡necesito ayuda!</option>
        </select>

        <label for="evento">¿Para qué ocasión buscas asesoría?</label>
        <select id="evento" name="evento" style="width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px;">
            <option value="casual">Día a día / Casual</option>
            <option value="oficina">Trabajo / Profesional</option>
            <option value="noche">Evento de Noche / Formal</option>
        </select>

        <label for="comentarios">Cuéntanos más sobre tu estilo y necesidades</label>
        <textarea id="comentarios" name="comentarios" rows="4" maxlength="500" style="width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px;"></textarea>

        <label style="display: block; margin-bottom: 20px;">
            <input type="checkbox" name="acepta_politicas" value="1" required> Acepto el aviso de privacidad para el manejo de mis datos de perfil.
        </label>

        <button type="submit" style="background: #d88c9a; color: white; padding: 10px 20px; border: none; border-radius: 20px; cursor: pointer; width: 100%; font-weight: bold;">Solicitar Asesoría de Imagen</button>
    </form>
</div>

<?php pie(); ?>