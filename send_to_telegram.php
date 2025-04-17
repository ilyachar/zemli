<?php
$token = "7597947712:AAFsGlNwO9KDYDTN6PRunNNged7ssXaCwG4";
$api_url = "https://api.telegram.org/bot$token/";

// Получаем данные от формы
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Если данные пришли через POST
$name = $data['name'] ?? 'Не указано';
$phone = $data['phone'] ?? 'Не указано';
$email = $data['email'] ?? 'Не указано';
$source = $data['source'] ?? 'Неизвестный источник';

// Формируем сообщение для Telegram
$telegram_message = "Новая заявка:\n";
$telegram_message .= "Источник: $source\n";
$telegram_message .= "Имя: $name\n";
$telegram_message .= "Телефон: $phone\n";
$telegram_message .= "Email: $email\n";
$telegram_message .= "Дата: " . date('Y-m-d H:i:s');

// Отправляем сообщение в Telegram
$chat_id = "8187920225"; // Ваш Telegram ID
$telegram_data = [
    'chat_id' => $chat_id,
    'text' => $telegram_message
];
file_get_contents($api_url . "sendMessage?" . http_build_query($telegram_data));

// Сохраняем данные в crm_data.json
$data_file = 'crm_data.json';
$current_data = file_exists($data_file) ? json_decode(file_get_contents($data_file), true) : [];
$new_entry = [
    'name' => $name,
    'phone' => $phone,
    'email' => $email,
    'source' => $source,
    'timestamp' => date('Y-m-d H:i:s')
];
$current_data[] = $new_entry;
file_put_contents($data_file, json_encode($current_data, JSON_PRETTY_PRINT));

// Ничего не возвращаем
exit();
?>