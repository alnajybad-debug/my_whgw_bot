<?php
// 1. إعدادات التوكن
$botToken = "8406108478:AAEaJPfFHN4u83_uX6je2pguLipWnTI-VnI"; 
$website = "https://api.telegram.org/bot".$botToken;

// 2. استقبال البيانات
$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (isset($update["message"])) {
    $chatId = (string)$update["message"]["chat"]["id"];
    $text = $update["message"]["text"];
    $name = $update["message"]["from"]["first_name"];

    // قائمة الأدمن - رقمك الصحيح
    $admins = ["77649438459"]; 

    if (in_array($chatId, $admins)) {
        if ($text == "/start") {
            // مصفوفة الأزرار بتنسيق هندسي دقيق
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => "📢 نشر رسالة", 'callback_data' => "publish_msg"],
                        ['text' => "📊 عدد المشتركين", 'callback_data' => "user_count"]
                    ],
                    [
                        ['text' => "💰 دعم بالنجوم", 'callback_data' => "support_stars"]
                    ]
                ]
            ];

            $reply = "مرحباً مهندس الصقور الجارحه 🦅\nلوحة التحكم جاهزة الآن:";
            
            // إرسال الطلب
            $url = $website . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($reply) . "&reply_markup=" . json_encode($keyboard);
            file_get_contents($url);
            exit;
        } else {
            // رد الأدمن على أي نص آخر
            $msg = "مرحباً مهندس، لقد استلمت نصك: " . $text;
            file_get_contents($website . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($msg));
            exit;
        }
    } else {
        // رد المستخدم العادي
        file_get_contents($website . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode("عذراً، هذا البوت خاص بالفريق فقط."));
        exit;
    }
}

// معالجة الأزرار
if (isset($update["callback_query"])) {
    $callbackChatId = $update["callback_query"]["message"]["chat"]["id"];
    file_get_contents($website . "/sendMessage?chat_id=" . $callbackChatId . "&text=" . urlencode("سيتم تفعيل هذا القسم قريباً."));
    exit;
}
?>
