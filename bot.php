<?php
$token = "8406108478:AAEnDH4opOUDBk88eGenZG1qoJpA5NdMZng";
$website = "https://api.telegram.org/bot".$token;

// جلب التحديثات التي يرسلها المستخدم للبوت
$content = file_get_contents("php://input");
$update = json_decode($content, true);

$chatId = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];

// الرد على رسالة "مرحبا"
if($message == "/start") {
    $url = $website."/sendMessage?chat_id=".$chatId."&text=".urlencode("أهلاً بك يا زميلي المبرمج! أنا بوتك الجديد.");
    file_get_contents($url);
}
?>
