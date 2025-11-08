<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>¡Bienvenido, <?= htmlspecialchars($nombre) ?>!</title>
  <style>
    body {
      background-color: #f4f4f4;
      font-family: 'Segoe UI', Arial, sans-serif;
      padding: 0;
      margin: 0;
    }
    .container {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    .header {
      background: #007BFF;
      color: #fff;
      text-align: center;
      padding: 20px;
    }
    .content {
      padding: 25px;
      color: #333;
    }
    .footer {
      text-align: center;
      font-size: 13px;
      color: #777;
      padding: 10px 0 20px;
    }
    .btn {
      display: inline-block;
      background: #007BFF;
      color: #fff;
      padding: 10px 18px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>¡Bienvenido de nuevo, <?= htmlspecialchars($nombre) ?>!</h2>
    </div>
    <div class="content">
      <p>Nos alegra verte otra vez en <strong><?= htmlspecialchars($app ?? 'nuestra plataforma') ?></strong>.</p>
      <p>Tu inicio de sesión fue exitoso ✅</p>
      <p>Si no fuiste tú, por favor revisa tu cuenta de inmediato.</p>
      <br>
      <a href="<?= htmlspecialchars($url ?? '#') ?>" class="btn">Ir a la plataforma</a>
    </div>
    <div class="footer">
      <p>© <?= date('Y') ?> <?= htmlspecialchars($app ?? 'MiApp') ?> — Todos los derechos reservados.</p>
    </div>
  </div>
</body>
</html>
