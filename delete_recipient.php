<?php
require 'db.php';

// Проверка, передан ли ID получателя
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Подготовка SQL запроса для удаления записи
        $stmt = $pdo->prepare("DELETE FROM recipients WHERE id = ?");
        // Выполнение запроса с переданным ID
        $stmt->execute([$id]);

        // Перенаправление обратно на страницу редактирования с сообщением об успехе
        header("Location: edit.php?message=RecipientDeleted");
    } catch (PDOException $e) {
        // Обработка ошибки при выполнении запроса
        die("Не удалось удалить запись: " . $e->getMessage());
    }
} else {
    // Если ID не передан, возвращаем пользователя обратно на страницу редактирования
    header("Location: edit.php?error=NoRecipientId");
}
