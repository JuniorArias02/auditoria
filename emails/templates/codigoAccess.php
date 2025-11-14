<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Código de acceso</title>
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
      text-align: center;
    }
    .codigo {
      font-size: 40px;
      font-weight: bold;
      letter-spacing: 8px;
      color: #007BFF;
      margin: 25px 0;
      display: inline-block;
      background: #eef4ff;
      padding: 15px 25px;
      border-radius: 10px;
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
      margin-top: 15px;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="header">
      <h2>Tu código de acceso</h2>
    </div>

    <div class="content">
      <p>Hola <strong><?= htmlspecialchars($nombre) ?></strong>,</p>
      <p>Este es tu código para acceder a <strong><?= htmlspecialchars($app ?? 'nuestra plataforma') ?></strong>:</p>

      <div class="codigo">
        <?= htmlspecialchars($codigo) ?>
      </div>

      <p>Este código es válido por 10 minutos ⏳</p>
      <p>Si no solicitaste este código, puedes ignorar este mensaje.</p>

      <br>
      <a href="<?= htmlspecialchars($url ?? '#') ?>" class="btn">Ir a la plataforma</a>
    </div>

    <div class="footer">
      <p>© <?= date('Y') ?> <?= htmlspecialchars($app ?? 'MiApp') ?> — Todos los derechos reservados.</p>
    </div>
  </div>

</body>
</html>
