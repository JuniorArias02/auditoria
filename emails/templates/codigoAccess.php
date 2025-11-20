<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Código de acceso</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      padding: 20px;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .container {
      max-width: 500px;
      width: 100%;
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .container:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .header {
      background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
      color: #fff;
      text-align: center;
      padding: 30px 20px;
      position: relative;
    }
    
    .header::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 0;
      height: 0;
      border-left: 10px solid transparent;
      border-right: 10px solid transparent;
      border-top: 10px solid #0056b3;
    }
    
    .header h2 {
      font-size: 28px;
      font-weight: 600;
      margin: 0;
    }
    
    .content {
      padding: 40px 30px;
      color: #333;
      text-align: center;
    }
    
    .saludo {
      font-size: 18px;
      margin-bottom: 20px;
      color: #555;
    }
    
    .app-name {
      color: #007BFF;
      font-weight: 600;
    }
    
    .codigo-container {
      margin: 30px 0;
      padding: 20px;
      background: linear-gradient(135deg, #f8f9ff 0%, #e8f0ff 100%);
      border-radius: 15px;
      border: 2px dashed #007BFF;
      position: relative;
    }
    
    .codigo {
      font-size: 42px;
      font-weight: 700;
      letter-spacing: 10px;
      color: #007BFF;
      text-shadow: 0 2px 4px rgba(0,123,255,0.1);
      font-family: 'Courier New', monospace;
    }
    
    .tiempo {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #fff3cd;
      color: #856404;
      padding: 10px 20px;
      border-radius: 25px;
      font-size: 14px;
      font-weight: 600;
      margin: 20px 0;
      border: 1px solid #ffeaa7;
    }
    
    .advertencia {
      color: #666;
      font-size: 14px;
      margin: 15px 0;
      line-height: 1.5;
    }
    
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
      color: #fff;
      padding: 14px 32px;
      border-radius: 50px;
      text-decoration: none;
      font-weight: 600;
      margin-top: 20px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0,123,255,0.3);
    }
    
    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0,123,255,0.4);
    }
    
    .footer {
      text-align: center;
      font-size: 12px;
      color: #888;
      padding: 25px 20px;
      background: #f8f9fa;
      border-top: 1px solid #e9ecef;
    }
    
    .logo {
      font-size: 18px;
      font-weight: 700;
      color: #007BFF;
      margin-bottom: 5px;
    }
    
    @media (max-width: 480px) {
      .container {
        margin: 10px;
        border-radius: 15px;
      }
      
      .content {
        padding: 30px 20px;
      }
      
      .codigo {
        font-size: 32px;
        letter-spacing: 8px;
      }
      
      .header h2 {
        font-size: 24px;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="header">
      <h2>🔐 Tu código de acceso</h2>
    </div>

    <div class="content">
      <p class="saludo">Hola <strong><?= htmlspecialchars($nombre) ?></strong>,</p>
      <p>Este es tu código para acceder a <span class="app-name"><?= htmlspecialchars($app ?? 'nuestra plataforma') ?></span>:</p>

      <div class="codigo-container">
        <div class="codigo">
          <?= htmlspecialchars($codigo) ?>
        </div>
      </div>

      <div class="tiempo">
        ⏳ Válido por 10 minutos
      </div>

      <p class="advertencia">Si no solicitaste este código, puedes ignorar este mensaje de forma segura.</p>

      <a href="<?= htmlspecialchars($url ?? '#') ?>" class="btn">
        🚀 Ir a la plataforma
      </a>
    </div>

    <div class="footer">
      <div class="logo"><?= htmlspecialchars($app ?? 'MiApp') ?></div>
      <p>© <?= date('Y') ?> Todos los derechos reservados.</p>
    </div>
  </div>

</body>
</html>