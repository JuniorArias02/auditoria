<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cambio de contraseña</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #2068A6;">Cambio de contraseña exitoso</h2>
        <p>Hola <strong><?= htmlspecialchars($nombre) ?></strong>,</p>
        <p>Se ha cambiado la contraseña de tu cuenta el <strong><?= $fecha ?></strong>.</p>
        <?php if(!empty($ip) || !empty($dispositivo)): ?>
        <p>Desde: <?= htmlspecialchars($dispositivo ?? 'desconocido') ?> (IP: <?= htmlspecialchars($ip ?? 'desconocida') ?>)</p>
        <?php endif; ?>
        <p>Si no realizaste este cambio, por favor contacta al soporte inmediatamente.</p>
        <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
        <p>Saludos,<br>El equipo de soporte</p>
    </div>
</body>
</html>
