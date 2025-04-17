<?php
$token = "7597947712:AAFsGlNwO9KDYDTN6PRunNNged7ssXaCwG4";
$api_url = "https://api.telegram.org/bot$token/";

// Получаем данные от Telegram или формы
$input = file_get_contents("php://input");
$update = json_decode($input, true);

// Проверяем, пришли ли данные от формы (через AJAX)
if (isset($update['name']) || isset($_POST['name'])) {
    // Если данные пришли через POST (например, через AJAX)
    $data = $update ?: $_POST;

    $name = $data['name'] ?? 'Не указано';
    $phone = $data['phone'] ?? 'Не указано';
    $email = $data['email'] ?? 'Не указано';
    $message = $data['message'] ?? 'Не указано';
    $source = $data['source'] ?? 'Неизвестный источник'; // Добавляем source

    // Формируем сообщение для Telegram
    $telegram_message = "Новая заявка:\n";
    $telegram_message .= "Источник: $source\n";
    $telegram_message .= "Имя: $name\n";
    $telegram_message .= "Телефон: $phone\n";
    $telegram_message .= "Email: $email\n";
    $telegram_message .= "Сообщение: $message\n";
    $telegram_message .= "Дата: " . date('Y-m-d H:i:s');

    // Отправляем сообщение в Telegram
    $chat_id = "8187920225"; // Ваш Telegram ID
    $data = [
        'chat_id' => $chat_id,
        'text' => $telegram_message
    ];
    file_get_contents($api_url . "sendMessage?" . http_build_query($data));

    // Сохраняем данные в crm_data.json
    $data_file = 'crm_data.json';
    $current_data = file_exists($data_file) ? json_decode(file_get_contents($data_file), true) : [];
    $new_entry = [
        'name' => $name,
        'phone' => $phone,
        'email' => $email,
        'message' => $message,
        'source' => $source, // Добавляем source в JSON
        'timestamp' => date('Y-m-d H:i:s')
    ];
    $current_data[] = $new_entry;
    file_put_contents($data_file, json_encode($current_data, JSON_PRETTY_PRINT));

    // Возвращаем ответ для AJAX
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Форма отправлена']);
    exit();
}

// Проверяем, что это сообщение от Telegram
if (isset($update['message'])) {
    $chat_id = $update['message']['chat']['id'];
    $text = $update['message']['text'];

    // Обработка команды /start
    if ($text === "/start") {
        $reply = "Добро пожаловать! Используйте кнопки ниже для управления CRM.";

        // Reply Keyboard
        $reply_keyboard = [
            'keyboard' => [
                ['Открыть CRM'],
                ['Показать записи'],
                ['Помощь']
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        ];

        // Inline Keyboard для Web App
        $inline_keyboard = [
            'inline_keyboard' => [
                [
                    [
                        'text' => 'Открыть CRM (Web App)',
                        'web_app' => ['url' => 'https://zemlirusi.ru/crm.php?telegram_user_id=' . $chat_id]
                    ]
                ]
            ]
        ];

        $data = [
            'chat_id' => $chat_id,
            'text' => $reply,
            'reply_markup' => json_encode($reply_keyboard)
        ];

        file_get_contents($api_url . "sendMessage?" . http_build_query($data));
    }

    // Обработка кнопки "Открыть CRM"
    if ($text === "Открыть CRM") {
        $reply = "Нажмите на кнопку ниже, чтобы открыть CRM.";
        $inline_keyboard = [
            'inline_keyboard' => [
                [
                    [
                        'text' => 'Открыть CRM (Web App)',
                        'web_app' => ['url' => 'https://zemlirusi.ru/crm.php?telegram_user_id=' . $chat_id]
                    ]
                ]
            ]
        ];

        $data = [
            'chat_id' => $chat_id,
            'text' => $reply,
            'reply_markup' => json_encode($inline_keyboard)
        ];
        file_get_contents($api_url . "sendMessage?" . http_build_query($data));
    }

    // Обработка кнопки "Показать записи"
    if ($text === "Показать записи") {
        $data_file = 'crm_data.json';
        if (file_exists($data_file)) {
            $data = json_decode(file_get_contents($data_file), true);
            if (empty($data)) {
                $reply = "Записей пока нет.";
                $data = [
                    'chat_id' => $chat_id,
                    'text' => $reply
                ];
                file_get_contents($api_url . "sendMessage?" . http_build_query($data));
            } else {
                foreach ($data as $index => $entry) {
                    $reply = "Запись #" . ($index + 1) . ":\n";
                    $reply .= "Источник: " . $entry['source'] . "\n"; // Добавляем source
                    $reply .= "Имя: " . $entry['name'] . "\n";
                    $reply .= "Телефон: " . $entry['phone'] . "\n";
                    $reply .= "Email: " . $entry['email'] . "\n";
                    $reply .= "Сообщение: " . $entry['message'] . "\n";
                    $reply .= "Дата: " . $entry['timestamp'] . "\n";

                    // Inline Keyboard для управления записью
                    $inline_keyboard = [
                        'inline_keyboard' => [
                            [
                                ['text' => 'Удалить', 'callback_data' => 'delete_' . $index]
                            ]
                        ]
                    ];

                    $data = [
                        'chat_id' => $chat_id,
                        'text' => $reply,
                        'reply_markup' => json_encode($inline_keyboard)
                    ];
                    file_get_contents($api_url . "sendMessage?" . http_build_query($data));
                }
            }
        } else {
            $reply = "Файл с данными не найден.";
            $data = [
                'chat_id' => $chat_id,
                'text' => $reply
            ];
            file_get_contents($api_url . "sendMessage?" . http_build_query($data));
        }
    }

    // Обработка кнопки "Помощь"
    if ($text === "Помощь") {
        $reply = "Список команд:\n/start - Начать\nОткрыть CRM - Открыть веб-интерфейс\nПоказать записи - Показать все записи\nПомощь - Показать это сообщение";
        $data = [
            'chat_id' => $chat_id,
            'text' => $reply
        ];
        file_get_contents($api_url . "sendMessage?" . http_build_query($data));
    }
}

// Обработка нажатий на Inline Keyboard
if (isset($update['callback_query'])) {
    $callback_query = $update['callback_query'];
    $chat_id = $callback_query['message']['chat']['id'];
    $callback_data = $callback_query['data'];

    if (strpos($callback_data, 'delete_') === 0) {
        $index = (int)str_replace('delete_', '', $callback_data);
        $data_file = 'crm_data.json';
        if (file_exists($data_file)) {
            $data = json_decode(file_get_contents($data_file), true);
            if (isset($data[$index])) {
                array_splice($data, $index, 1);
                file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));
                $reply = "Запись #" . ($index + 1) . " удалена.";
            } else {
                $reply = "Запись не найдена.";
            }
        } else {
            $reply = "Файл с данными не найден.";
        }

        $data = [
            'chat_id' => $chat_id,
            'text' => $reply
        ];
        file_get_contents($api_url . "sendMessage?" . http_build_query($data));

        // Ответ на callback, чтобы убрать "часики"
        $callback_query_id = $callback_query['id'];
        file_get_contents($api_url . "answerCallbackQuery?callback_query_id=$callback_query_id");
    }
}

// Обработка данных из Web App (если они приходят)
if (isset($update['message']['web_app_data'])) {
    $web_app_data = json_decode($update['message']['web_app_data']['data'], true);
    // Здесь можно обработать данные, если Web App отправляет их обратно в бота
}
?>