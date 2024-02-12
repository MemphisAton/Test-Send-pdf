<?php
require 'db.php';

$message = '';
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$recipient = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $fullname = $_POST['fullname'];
    $contact = $_POST['contact'];

    if ($id && !empty($fullname) && !empty($contact)) {
        try {
            $stmt = $pdo->prepare("UPDATE recipients SET fullname = ?, contact = ? WHERE id = ?");
            if ($stmt->execute([$fullname, $contact, $id])) {
                $message = "Данные получателя успешно обновлены!";
            } else {
                $message = "Не удалось обновить данные получателя.";
            }
        } catch (PDOException $e) {
            $message = "Ошибка при обновлении данных в базе данных: " . $e->getMessage();
        }
    } else {
        $message = "Пожалуйста, заполните все поля.";
    }
} else if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM recipients WHERE id = ?");
    $stmt->execute([$id]);
    $recipient = $stmt->fetch();
}

if (!$recipient) {
    header('Location: edit.php');
    exit;
}
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
</form>

<h2>Редактировать получателя</h2>

<?php if ($message): ?>
    <p><?= $message ?></p>
<?php endif; ?>

<form action="edit_recipient.php" method="post">
    <input type="hidden" name="id" value="<?= $recipient['id'] ?>">
    <label for="fullname">ФИО:</label>
    <input type="text" name="fullname" id="fullname" value="<?= htmlspecialchars($recipient['fullname']) ?>" required><br>
    <label for="contact">Email/ID Telegram:</label>
    <input type="text" name="contact" id="contact" value="<?= htmlspecialchars($recipient['contact']) ?>" required><br>
    <input type="submit" value="Обновить" name="submit">
</form>


</body>
</html>
