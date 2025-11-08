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
        $dotenv = Dotenv::createImmutable(dirname(__DIR__)); 

        $dotenv->load();

        $mail = new PHPMailer(true);

        try {
            // Configuración SMTP
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
            $mail->Subject = $subject;

            // Renderizar plantilla
            ob_start();
            extract($data);
            include __DIR__ . "/../emails/templates/{$template}.php";
            $body = ob_get_clean();

            $mail->Body = $body;
            $mail->send();

            return ['success' => true, 'message' => 'Correo enviado correctamente.'];

        } catch (Exception $e) {
            return ['success' => false, 'error' => $mail->ErrorInfo];
        }
    }
}
