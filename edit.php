<?php
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $contact = $_POST['contact'];

    if (!empty($fullname) && !empty($contact)) {
        if (filter_var($contact, FILTER_VALIDATE_EMAIL) || preg_match("/^@?[a-zA-Z0-9_]{5,}$/", $contact)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO recipients (fullname, contact) VALUES (?, ?)");
                if ($stmt->execute([$fullname, $contact])) {
                    // Сохраняем сообщение об успехе в сессии
                    $_SESSION['message'] = "Получатель успешно добавлен!";
                    // Перенаправляем на ту же страницу с помощью GET-запроса
                    header('Location: edit.php');
                    exit;
                } else {
                    $message = "Не удалось добавить получателя.";
                }
            } catch (PDOException $e) {
                $message = "Ошибка при добавлении в базу данных: " . $e->getMessage();
            }
        } else {
            $message = "Контакт должен быть действительной почтой или Telegram ID.";
        }
    } else {
        $message = "Пожалуйста, заполните все поля.";
    }
}

// Проверяем, есть ли сообщение в сессии и выводим его
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

$recipients = $pdo->query("SELECT * FROM recipients")->fetchAll();
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование получателей</title>
</head>
<body>

<form action="index.php">
    <button type="submit">Вернуться на главную страницу</button>
</form>

<?php if ($message): ?>
    <p><?= $message ?></p>
<?php endif; ?>

<form action="edit.php" method="post">
    <h2>Добавить получателя</h2>
    <label for="fullname">ФИО:</label>
    <input type="text" name="fullname" id="fullname" required><br>
    <label for="contact">Email/ID Telegram:</label>
    <input type="text" name="contact" id="contact" required><br>
    <input type="submit" value="Добавить" name="submit">
</form>

<h2>Список получателей</h2>
<table>
    <tr>
        <th>ФИО</th>
        <th>Email/ID Telegram</th>
        <th>Действия</th>
    </tr>
    <?php foreach ($recipients as $recipient): ?>
        <tr>
            <td><?= htmlspecialchars($recipient['fullname']) ?></td>
            <td><?= htmlspecialchars($recipient['contact']) ?></td>
            <td>
                <a href="edit_recipient.php?id=<?= $recipient['id'] ?>">Редактировать</a> |
                <a href="delete_recipient.php?id=<?= $recipient['id'] ?>" onclick="return confirm('Вы уверены?');">Удалить</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
