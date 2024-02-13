<?php
require 'db.php';
session_start();

// Инициализация переменных
$id = $_GET['id'] ?? null; // Получаем ID из URL
$message = ''; // Сообщение для пользователя
$recipient = ['fullname' => '', 'contact' => '']; // Пустой массив для данных получателя

if ($id) {
    // Загрузка данных получателя из базы данных
    $stmt = $pdo->prepare("SELECT * FROM recipients WHERE id = ?");
    $stmt->execute([$id]);
    $recipient = $stmt->fetch();

    if (!$recipient) {
        // Если получатель не найден, устанавливаем сообщение и возвращаемся к списку
        $_SESSION['message'] = "Получатель с указанным ID не найден.";
        header('Location: edit.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение и очистка данных из формы
    $fullname = trim($_POST['fullname']);
    $contact = trim($_POST['contact']);

    if (!empty($fullname) && !empty($contact)) {
        // Валидация поля contact
        if (filter_var($contact, FILTER_VALIDATE_EMAIL) || preg_match("/^\d{9,10}$/", $contact)) {
            // Попытка обновить данные в базе данных
            $stmt = $pdo->prepare("UPDATE recipients SET fullname = ?, contact = ? WHERE id = ?");
            if ($stmt->execute([$fullname, $contact, $id])) {
                // Обновление прошло успешно, устанавливаем сообщение
                $_SESSION['message'] = "Данные получателя успешно обновлены!";
                header('Location: edit.php');
                exit;
            } else {
                // Произошла ошибка при обновлении
                $message = "Не удалось обновить данные получателя.";
            }
        } else {
            // Неверный формат контакта
            $message = "Неверный формат контакта. Укажите действительную почту или Telegram ID.";
        }
    } else {
        // Не все поля формы заполнены
        $message = "Пожалуйста, заполните все поля.";
    }
}

// HTML-форма будет здесь...
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование получателя</title>
</head>
<body>

<form action="edit.php">
    <button type="submit">Вернуться к списку</button>
    <br>
    <br>
</form>

<?php if (isset($_SESSION['message'])): ?>
    <p><?= $_SESSION['message'] ?></p>
    <?php unset($_SESSION['message']); // Очищаем сообщение из сессии после его показа ?>
<?php endif; ?>

<?php if ($message): ?>
    <p><?= $message ?></p>
<?php endif; ?>

<form action="edit_recipient.php?id=<?= htmlspecialchars($id) ?>" method="post">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
    <label for="fullname">ФИО:</label>
    <input type="text" name="fullname" id="fullname" value="<?= htmlspecialchars($recipient['fullname']) ?>" required><br>
    <label for="contact">Email/ID Telegram:</label>
    <input type="text" name="contact" id="contact" value="<?= htmlspecialchars($recipient['contact']) ?>" required><br>
    <input type="submit" value="Обновить">
</form>


</body>
</html>
