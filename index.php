<?php
$botToken = "8406108478:AAEaJPfFHN4u83_uX6je2pguLipWnTI-VnI"; 
$website = "https://api.telegram.org/bot".$botToken;

$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (isset($update["message"])) {
    $chatId = (string)$update["message"]["chat"]["id"];
    $text = trim($update["message"]["text"]); // تنظيف النص من المسافات
    $name = $update["message"]["from"]["first_name"];

    $admins = ["7785947020"]; 

    if (in_array($chatId, $admins)) {
        // فحص إذا كان النص يحتوي على كلمة start
        if (strpos($text, "/start") !== false) {
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => "📢 نشر رسالة", 'callback_data' => "publish_msg"],
                        ['text' => "📊 عدد المشتركين", 'callback_data' => "user_count"]
                    ]
                ]
            ];

            $reply = "مرحباً مهندس الصقور الجارحه 🦅\nتم تفعيل الأزرار بنجاح:";
            
            $url = $website . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($reply) . "&reply_markup=" . json_encode($keyboard);
            file_get_contents($url);
            exit;
        } else {
            // رد احتياطي للأدمن
            file_get_contents($website . "/sendMessage?chat_id=$chatId&text=" . urlencode("مرحباً مهندس، استلمت: $text"));
            exit;
        }
    }
}
?>
