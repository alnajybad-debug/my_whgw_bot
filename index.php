<?php
// 1. إعداد التوكن
$botToken = "8406108478:AAEaJPfFHN4u83_uX6je2pguLipWnTI-VnI"; 
$website = "https://api.telegram.org/bot".$botToken;

// 2. استقبال البيانات
$content = file_get_contents("php://input");
$update = json_decode($content, true);

// إنشاء ملف المستخدمين إذا لم يكن موجوداً (لمنع توقف الكود)
$file = 'users.txt';
if (!file_exists($file)) { file_put_contents($file, ""); }

// معالجة ضغطات الأزرار (Callback Query)
if (isset($update["callback_query"])) {
    $callbackData = $update["callback_query"]["data"];
    $callbackChatId = $update["callback_query"]["message"]["chat"]["id"];

    if ($callbackData == "user_count") {
        $allUsers = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $count = count($allUsers);
        file_get_contents($website."/sendMessage?chat_id=".$callbackChatId."&text=".urlencode("📊 عدد المشتركين حالياً هو: $count"));
    }
    exit; // إنهاء التنفيذ هنا عند معالجة الزر
}

// معالجة الرسائل النصية
if (isset($update["message"])) {
    $chatId = $update["message"]["chat"]["id"];
    $text = $update["message"]["text"];
    $name = $update["message"]["from"]["first_name"];

    // حفظ المستخدم الجديد في الملف
    $current_users = file_get_contents($file);
    if (strpos($current_users, (string)$chatId) === false) {
        file_put_contents($file, $chatId . PHP_EOL, FILE_APPEND);
    }

    // قائمة الأدمن (تأكد من وضع الـ ID الصحيح المكون من أرقام فقط بدون +)
    $admins = ["7785947020"]; // راجع هذا الرقم، يجب أن يكون ID وليس رقم هاتف

    if (in_array($chatId, $admins)) {
        if ($text == "/start") {
            $keyboard = [
                'inline_keyboard' => [
                    [['text' => "📢 نشر رسالة", 'callback_data' => "publish_msg"], ['text' => "💰 دعم بالنجوم", 'callback_data' => "support_stars"]],
                    [['text' => "📊 عدد المشتركين", 'callback_data' => "user_count"]]
                ]
            ];
            $msg = "أهلاً بك يا باشمهندس $name في لوحة التحكم:";
            file_get_contents($website . "/sendMessage?chat_id=$chatId&text=".urlencode($msg)."&reply_markup=".json_encode($keyboard));
        } elseif ($text == "نشر") {
            file_get_contents($website."/sendMessage?chat_id=$chatId&text=".urlencode("أهلاً يا بشمهندس، ماذا تريد أن تنشر؟"));
        } else {
            file_get_contents($website."/sendMessage?chat_id=$chatId&text=".urlencode("استلمت رسالتك: $text"));
        } 
    } else {
        file_get_contents($website."/sendMessage?chat_id=$chatId&text=".urlencode("عذراً، هذا البوت مخصص لفريق المهندسين فقط."));
    }
}
?>
