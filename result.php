<?php
session_start();

$sentEmails = $_SESSION['sentEmails'] ?? [];
$failedEmails = $_SESSION['failedEmails'] ?? [];
$sentTelegram = $_SESSION['sentTelegram'] ?? [];
$failedTelegram = $_SESSION['failedTelegram'] ?? [];
$message = $_SESSION['message'] ?? '';

// Чистим сессионные переменные после их использования
unset($_SESSION['sentEmails'], $_SESSION['failedEmails'], $_SESSION['sentTelegram'], $_SESSION['failedTelegram'], $_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результаты отправки</title>
</head>
<body>
<h1>Результаты отправки</h1>

<?php if (!empty($message)): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<h2>Сообщения отправлены:</h2>
<h3>Email:</h3>
<ul>
    <?php foreach ($sentEmails as $email): ?>
        <li><?php echo htmlspecialchars($email); ?></li>
    <?php endforeach; ?>
</ul>

<h3>Telegram:</h3>
<ul>
    <?php foreach ($sentTelegram as $contact): ?>
        <li><?php echo htmlspecialchars($contact); ?></li>
    <?php endforeach; ?>
</ul>

<h2>Не удалось отправить:</h2>
<h3>Email:</h3>
<ul>
    <?php foreach ($failedEmails as $email): ?>
        <li><?php echo htmlspecialchars($email); ?></li>
    <?php endforeach; ?>
</ul>

<h3>Telegram:</h3>
<ul>
    <?php foreach ($failedTelegram as $contact): ?>
        <li><?php echo htmlspecialchars($contact); ?></li>
    <?php endforeach; ?>
</ul>

<form action="index.php" method="post">
    <button type="submit" name="delete">Удалить файл и вернуться на главную</button>
    <button type="button" onclick="location.href='index.php'">Не удалять файл и вернуться на главную</button>
</form>

</body>
</html>
