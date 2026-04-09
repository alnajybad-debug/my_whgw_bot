<?php
// ضع التوكن الجديد هنا
$botToken = "8406108478:AAEaJPfFHN4u83_uX6je2pguLipWnTI-VnI"; 
$website = "https://api.telegram.org/bot".$botToken;

// استقبال البيانات من تلجرام
$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (isset($update["message"])) {
    $chatId = $update["message"]["chat"]["id"];
    $text = $update["message"]["text"];
    $name = $update["message"]["from"]["first_name"];

    if ($text == "/start") {
        $reply = "أهلاً بك يا $name في نسختي المطورة! أنا أعمل الآن من اليمن بكل كفاءة.";
    } else {
        $reply = "لقد استلمت رسالتك: $text";
    }

    // إرسال الرد
    file_get_contents($website."/sendMessage?chat_id=".$chatId."&text=".urlencode($reply));
}
?>
