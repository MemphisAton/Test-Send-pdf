<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'db.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$mail = new PHPMailer(true);

try {
    $mail->CharSet = 'UTF-8';
    // Настройки сервера
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USERNAME'];
    $mail->Password = $_ENV['SMTP_PASSWORD'];
    $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];
    $mail->Port = $_ENV['SMTP_PORT'];

    $mail->setFrom($mail->Username, 'Mailer');

    // Получаем список получателей из базы данных
    $stmt = $pdo->query("SELECT fullname, contact FROM recipients");
    while ($row = $stmt->fetch()) {
        if (filter_var($row['contact'], FILTER_VALIDATE_EMAIL)) {
            // Отправка email
            $mail->addAddress($row['contact'], $row['fullname']);
            $mail->isHTML(true);
            $mail->Subject = 'Ваш PDF файл';
            $mail->Body    = 'Здравствуйте! Ваш PDF файл во вложении.';
            $mail->AltBody = 'Это альтернативный текст для не HTML почтовых клиентов';
            $mail->addAttachment('pdfs/latest.pdf'); // Путь к файлу
            $mail->send();
            $mail->clearAddresses();
        } else {
            // Отправка в Telegram
            $telegramApiUrl = "https://api.telegram.org/bot{$_ENV['TELEGRAM_BOT_TOKEN']}/sendDocument";
            $chat_id = $row['contact'];
            $document = curl_file_create('pdfs/latest.pdf');
            $postData = ['chat_id' => $chat_id, 'document' => $document];
            $ch = curl_init($telegramApiUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:multipart/form-data']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            $result = curl_exec($ch);
            if (curl_error($ch)) {
                throw new Exception('Ошибка отправки в Telegram: ' . curl_error($ch));
            }
            curl_close($ch);
        }
    }

    // Удаление файла после отправки
    unlink('pdfs/latest.pdf');
    echo '<a href="index.php"><button type="button">Вернуться на главную страницу</button></a>';
    echo '<br>';
    echo '<br>';
    echo 'Сообщение было отправлено всем получателям.';

} catch (Exception $e) {
    echo '<a href="index.php"><button type="button">Вернуться на главную страницу</button></a>';
    echo '<br>';
    echo '<br>';
    echo "Произошла ошибка: {$e->getMessage()}";
}
