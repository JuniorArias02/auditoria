<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

class EmailService
{
    public static function send($to, $subject, $template, $data = [])
    {
        $mail = new PHPMailer(true);

        try {
            // SMTP
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'];
            $mail->Password   = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
            $mail->Port       = $_ENV['MAIL_PORT'];

            // Remitente
            $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_NAME']);
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->CharSet = "UTF-8";
            $mail->Subject = $subject;

            // Plantilla
            $templatePath = __DIR__ . "/../emails/templates/{$template}.php";

            if (!file_exists($templatePath)) {
                throw new \Exception("Plantilla de correo '{$template}' no encontrada.");
            }

            ob_start();
            extract($data);
            include $templatePath;
            $body = ob_get_clean();

            $mail->Body = $body;
            $mail->send();

            return ['success' => true, 'message' => 'Correo enviado correctamente.'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $mail->ErrorInfo];
        }
    }
}
