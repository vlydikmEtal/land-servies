<?php
// send.php — положи на сервер рядом с сайтом

// ==========================================
// 🔧 НАСТРОЙКИ — только на сервере, не в JS
// ==========================================
define('TG_BOT_TOKEN', '8551947767:AAHGTUaqCijckLksNi4QWtGuSOmzSrgXpB8');
define('TG_CHAT_ID',   '630220792');
// ==========================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);

$name     = htmlspecialchars(trim($body['name']    ?? ''));
$phone    = htmlspecialchars(trim($body['phone']   ?? ''));
$address  = htmlspecialchars(trim($body['address'] ?? ''));
$comment  = htmlspecialchars(trim($body['comment'] ?? ''));
$services = $body['services'] ?? [];

if (!$name || !$phone || !$address) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Заполните обязательные поля']);
    exit;
}

// Формируем сообщение
$message = "📩 <b>Новая заявка с сайта</b>\n\n";
$message .= "👤 <b>Имя:</b> {$name}\n";
$message .= "📞 <b>Телефон:</b> {$phone}\n";
$message .= "📍 <b>Адрес:</b> {$address}\n\n";

if (empty($services)) {
    $message .= "❌ Услуги не выбраны\n";
} else {
    $message .= "🛠 <b>Выбранные услуги:</b>\n";
    foreach ($services as $idx => $label) {
        $label    = htmlspecialchars(trim($label));
        $num      = $idx + 1;
        $message .= "  {$num}. {$label}\n";
    }
}

if ($comment) {
    $message .= "\n💬 <b>Комментарий:</b> {$comment}";
}

// Отправка в Telegram
$url  = 'https://api.telegram.org/bot' . TG_BOT_TOKEN . '/sendMessage';
$data = json_encode([
    'chat_id'    => TG_CHAT_ID,
    'text'       => $message,
    'parse_mode' => 'HTML',
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST,           true);
curl_setopt($ch, CURLOPT_POSTFIELDS,     $data);
curl_setopt($ch, CURLOPT_HTTPHEADER,     ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT,        10);

$result   = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$tgResponse = json_decode($result, true);

if ($httpCode === 200 && ($tgResponse['ok'] ?? false)) {
    echo json_encode(['ok' => true]);
} else {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Ошибка отправки в Telegram']);
}