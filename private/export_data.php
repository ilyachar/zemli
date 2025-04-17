<?php
// export_data.php - поместите в закрытую часть сайта

// Подключение к Cockpit API
$cockpit = new CockpitAPI([
    'api_key' => 'ваш_api_ключ',
    'base_url' => 'https://zemlirusi.ru/admin/api'
]);

// Получение объектов из коллекции
$properties = $cockpit->collection('properties')->find()->toArray();

// Экспорт в JSON
file_put_contents('../genplan/js/data.json', json_encode($properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Данные успешно экспортированы!";

