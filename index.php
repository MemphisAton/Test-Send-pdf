<?php
session_start();
$path = 'pdfs/';
$fixedFileName = "latest.pdf"; // Фиксированное имя файла
$fileExists = file_exists($path . $fixedFileName);

// Логика загрузки файла
if (isset($_FILES['pdfFile'])) {
    $errors = [];
    $extensions = ['pdf'];
    $file_tmp = $_FILES['pdfFile']['tmp_name'];
    $fileNameParts = explode('.', $_FILES['pdfFile']['name']);
    $file_ext = strtolower(end($fileNameParts));

    if (!in_array($file_ext, $extensions)) {
        $_SESSION['message'] = 'Расширение файла не поддерживается: ' . $_FILES['pdfFile']['name'];
    } else {
        if (move_uploaded_file($file_tmp, $path . $fixedFileName)) {
            $_SESSION['message'] = 'Файл успешно загружен';
            $fileExists = true;
            // Перенаправление после успешной загрузки файла
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['message'] = 'Произошла ошибка при перемещении загруженного файла';
        }
    }

    // Если есть ошибки, перенаправляем и передаем сообщение об ошибке
    if ($errors) {
        $_SESSION['errors'] = $errors;
        header('Location: index.php');
        exit;
    }
}

// Обработка удаления файла
if (isset($_POST['delete'])) {
    if ($fileExists) {
        unlink($path . $fixedFileName);
        $_SESSION['message'] = 'Файл был удален';
        $fileExists = false;
        // Перенаправление после удаления файла
        header('Location: index.php');
        exit;
    }
}

// Выводим сообщение из сессии, если оно есть
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']); // Удаляем сообщение после показа
}

if (isset($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $error) {
        echo "<script>alert('{$error}');</script>";
    }
    unset($_SESSION['errors']); // Удаляем ошибки после показа
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная страница</title>
</head>
<body>
<h1>Управление PDF файлами</h1>

<!-- Форма для загрузки PDF файла -->
<form action="index.php" method="post" enctype="multipart/form-data">
    <input type="file" name="pdfFile" accept=".pdf" required>
    <input type="submit" value="Загрузить" name="submit">
</form>
<h2>Навигация</h2>
<form action="edit.php" method="get">
    <button type="submit">Редактировать базу данных</button><br><br>
</form>

<form action="send.php" method="get">
    <button type="submit">Отправить PDF файл</button><br><br>
</form>

<?php if ($fileExists): ?>
    <!-- Форма для удаления файла -->
    <form method="post">
        <button type="submit" name="delete">Удалить файл</button>
    </form>


    <!-- Блок просмотра файла -->
    <h2>Просмотр файла</h2>
    <iframe src="<?= $path . htmlspecialchars($fixedFileName) ?>" width="100%" height="600px"></iframe>
<?php endif; ?>


</body>
</html>
