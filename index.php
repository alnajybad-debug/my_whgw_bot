<?php
// 1. إعدادات التوكن والموقع
$botToken = "8406108478:AAEaJPfFHN4u83_uX6je2pguLipWnTI-VnI"; 
$website = "https://api.telegram.org/bot".$botToken;

// 2. استقبال البيانات من تليجرام
$content = file_get_contents("php://input");
$update = json_decode($content, true);

// 3. معالجة الرسائل النصية
if (isset($update["message"])) {
    $chatId = (string)$update["message"]["chat"]["id"];
    $text = $update["message"]["text"];
    $name = $update["message"]["from"]["first_name"];

    // قائمة الأدمن (تأكد أن رقمك هنا صحيح)
    $admins = ["77649438459"]; 

    if (in_array($chatId, $admins)) {
        if ($text == "/start") {
            // تنسيق الأزرار بدقة
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

            $reply = "أهلاً بك يا باشمهندس " . $name . " ✅\nتم تفعيل لوحة التحكم الخاصة بك:";
            
            // رابط الإرسال
            $sendUrl = $website . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($reply) . "&reply_markup=" . json_encode($keyboard);
            file_get_contents($sendUrl);
            exit;
        } else {
            // رد الأدمن على أي رسالة أخرى
            $reply = "مرحباً مهندس " . $name . "، لقد استلمت رسالتك: " . $text;
            file_get_contents($website . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($reply));
            exit;
        }
    } else {
        // رد المستخدم العادي
        $reply = "عذراً يا " . $name . "، هذا البوت مخصص للمهندسين فقط.";
        file_get_contents($website . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($reply));
        exit;
    }
}

// 4. معالجة ضغطات الأزرار (Callback)
if (isset($update["callback_query"])) {
    $callbackChatId = $update["callback_query"]["message"]["chat"]["id"];
    $data = $update["callback_query"]["data"];

    if ($data == "user_count") {
        file_get_contents($website . "/sendMessage?chat_id=" . $callbackChatId . "&text=" . urlencode("سيتم تفعيل ميزة الإحصائيات قريباً."));
    }
    exit;
}
?>
