<?php
$path = 'https://api.telegram.org/bot' . getenv('TOKEN');

$update = json_decode(file_get_contents('php://input'), TRUE);

$chatId = $update['message']['chat']['id'];
$mensaje = $update['message']['text'];

file_get_contents($path . '/sendmessage?chat_id=' . $chatId . '&text=' . '💨  🍃');
?>
