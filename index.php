<?php
$botToken = "8406108478:AAEaJPfFHN4u83_uX6je2pguLipWnTI-VnI";
$website = "https://api.telegram.org/bot".$botToken;

$content = file_get_contents("php://input");
$update = json_decode($content, true);

// ملف تخزين المشتركين
$file = 'users.txt';
if (!file_exists($file)) { file_put_contents($file, ""); }

// --- 1. قسم معالجة ضغطات الأزرار (هذا ما كان ينقصك) ---
if (isset($update["callback_query"])) {
    $callbackChatId = $update["callback_query"]["message"]["chat"]["id"];
    $data = $update["callback_query"]["data"];

    if ($data == "user_count") {
        $users = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $count = count($users);
        file_get_contents($website."/sendMessage?chat_id=$callbackChatId&text=".urlencode("📊 عدد المشتركين حالياً: $count"));
    }

    if ($data == "publish_msg") {
        file_get_contents($website."/sendMessage?chat_id=$callbackChatId&text=".urlencode("📢 حسناً يا مهندس، أرسل رسالتك الآن ليتم نشرها للجميع."));
    }
    exit; // ضروري جداً لإنهاء التنفيذ بعد معالجة الزر
}

// --- 2. قسم معالجة الرسائل النصية ---
if (isset($update["message"])) {
    $chatId = (string)$update["message"]["chat"]["id"];
    $text = trim($update["message"]["text"]);
    $name = $update["message"]["from"]["first_name"];

    // حفظ المشترك تلقائياً
    $current = file_get_contents($file);
    if (strpos($current, $chatId) === false) {
        file_put_contents($file, $chatId . PHP_EOL, FILE_APPEND);
    }

    $admins = ["7785947020"]; 

    if (in_array($chatId, $admins)) {
        if ($text == "/start") {
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => "📢 نشر رسالة", 'callback_data' => "publish_msg"],
                        ['text' => "📊 عدد المشتركين", 'callback_data' => "user_count"]
                    ]
                ]
            ];

            $postData = [
                'chat_id' => $chatId,
                'text' => "مرحباً مهندس الصقور الجارحه 🦅\nلوحة التحكم جاهزة للاستخدام:",
                'reply_markup' => json_encode($keyboard)
            ];

            $ch = curl_init("$website/sendMessage");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_exec($ch);
            curl_close($ch);
            exit;
        }
    }
}
?>
