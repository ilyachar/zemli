<?php
// Ограничение доступа: только для вашего Telegram ID
if (!isset($_GET['telegram_user_id']) || $_GET['telegram_user_id'] != '8187920225') {
    die("Доступ запрещён!");
}

// Путь к файлу с данными
$data_file = 'crm_data.json';

// Чтение данных из файла
if (file_exists($data_file)) {
    $data = json_decode(file_get_contents($data_file), true);
} else {
    $data = [];
}

// Обработка действий (добавление, редактирование, удаление)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $new_entry = [
                'name' => $_POST['name'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'message' => $_POST['message'],
                'source' => $_POST['source'] ?? 'Неизвестный источник', // Добавляем source
                'timestamp' => date('Y-m-d H:i:s')
            ];
            $data[] = $new_entry;
        } elseif ($_POST['action'] === 'edit') {
            $index = (int)$_POST['index'];
            $data[$index] = [
                'name' => $_POST['name'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'message' => $_POST['message'],
                'source' => $_POST['source'] ?? $data[$index]['source'], // Сохраняем source
                'timestamp' => $data[$index]['timestamp']
            ];
        } elseif ($_POST['action'] === 'delete') {
            $index = (int)$_POST['index'];
            array_splice($data, $index, 1);
        }
        file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
        header("Location: crm.php?telegram_user_id=" . $_GET['telegram_user_id']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM для Telegram-бота</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .form-container { margin: 20px 0; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0; box-sizing: border-box; }
        button { padding: 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer; width: 100%; }
        button:hover { background-color: #45a049; }
        .delete-btn { background-color: #f44336; }
        .delete-btn:hover { background-color: #da190b; }
    </style>
</head>
<body>
    <h2>CRM для Telegram-бота</h2>

    <!-- Форма добавления -->
    <div class="form-container">
        <h3>Добавить новую запись</h3>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <input type="text" name="name" placeholder="Имя" required>
            <input type="text" name="phone" placeholder="Телефон" required>
            <input type="email" name="email" placeholder="Email" required>
            <textarea name="message" placeholder="Сообщение" required></textarea>
            <input type="text" name="source" placeholder="Источник" value="Неизвестный источник">
            <button type="submit">Добавить</button>
        </form>
    </div>

    <!-- Таблица с данными -->
    <table>
        <thead>
            <tr>
                <th>Источник</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Email</th>
                <th>Сообщение</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $index => $entry): ?>
                <tr>
                    <td><?php echo htmlspecialchars($entry['source']); ?></td>
                    <td><?php echo htmlspecialchars($entry['name']); ?></td>
                    <td><?php echo htmlspecialchars($entry['phone']); ?></td>
                    <td><?php echo htmlspecialchars($entry['email']); ?></td>
                    <td><?php echo htmlspecialchars($entry['message']); ?></td>
                    <td><?php echo htmlspecialchars($entry['timestamp']); ?></td>
                    <td>
                        <!-- Форма редактирования -->
                        <button onclick="document.getElementById('edit-form-<?php echo $index; ?>').style.display='block'">Редактировать</button>
                        <div id="edit-form-<?php echo $index; ?>" style="display:none;">
                            <form method="POST">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="index" value="<?php echo $index; ?>">
                                <input type="text" name="source" value="<?php echo htmlspecialchars($entry['source']); ?>" required>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($entry['name']); ?>" required>
                                <input type="text" name="phone" value="<?php echo htmlspecialchars($entry['phone']); ?>" required>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($entry['email']); ?>" required>
                                <textarea name="message" required><?php echo htmlspecialchars($entry['message']); ?></textarea>
                                <button type="submit">Сохранить</button>
                            </form>
                        </div>
                        <!-- Форма удаления -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <button type="submit" class="delete-btn">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        // Инициализация Telegram Web App
        const tg = window.Telegram.WebApp;
        tg.ready();
        tg.expand(); // Разворачиваем приложение на полный экран
    </script>
</body>
</html>