<?php
$botToken = "8406108478:AAEaJPfFHN4u83_uX6je2pguLipWnTI-VnI";

$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (isset($update["message"])) {
    $chatId = $update["message"]["chat"]["id"];
    $text = trim($update["message"]["text"]);
    $name = $update["message"]["from"]["first_name"];

    // قائمة الأدمن
    $admins = [7785947020]; 

    if (in_array($chatId, $admins)) {
        if ($text == "/start") {
            $reply = "مرحباً مهندس الصقور الجارحه 🦅\nتم تحديث البروتوكول.. هل تظهر الأزرار الآن؟";
            
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => "📢 نشر رسالة", 'callback_data' => "publish_msg"],
                        ['text' => "📊 عدد المشتركين", 'callback_data' => "user_count"]
                    ]
                ]
            ];

            // إرسال البيانات باستخدام CURL (الطريقة الأضمن للأزرار)
            $postData = [
                'chat_id' => $chatId,
                'text' => $reply,
                'reply_markup' => json_encode($keyboard)
            ];

            $ch = curl_init("https://api.telegram.org/bot$botToken/sendMessage");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_exec($ch);
            curl_close($ch);
            exit;
        } else {
            // رد احتياطي للأدمن
            $msg = "استلمت نصك: " . $text;
            file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=".urlencode($msg));
            exit;
        }
    }
}
?>
