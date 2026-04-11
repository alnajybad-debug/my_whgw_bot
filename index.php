<?php
// 1. إعداد التوكن
$botToken = "8406108478:AAEaJPfFHN4u83_uX6je2pguLipWnTI-VnI"; 
$website = "https://api.telegram.org/bot".$botToken;

// 2. استقبال البيانات من تلجرام
$content = file_get_contents("php://input");
$update = json_decode($content, true);
$callbackQuery = $update["callback_query"];
if (isset($callbackQuery)) {
    $callbackData = $callbackQuery["data"];
    $callbackChatId = $callbackQuery["message"]["chat"]["id"];

    if ($callbackData == "user_count") {
        $allUsers = file('users.txt', FILE_IGNORE_NEW_LINES);
        $count = count($allUsers);
        file_get_contents($website."/sendMessage?chat_id=".$callbackChatId."&text=".urlencode("عدد المشتركين حالياً هو: $count"));
    }
}

if (isset($update["message"])) {
    $chatId = $update["message"]["chat"]["id"];
    $text = $update["message"]["text"];
    $name = $update["message"]["from"]["first_name"];

    // 3. قائمة المهندسين (ضع أرقام الـ ID الحقيقية هنا)
    // يمكنك إضافة أي عدد من الأرقام داخل هذه المصفوفة
    $admins = ["7785947020"]; 

    // 4. فحص الصلاحيات والرد
    if (in_array($chatId, $admins)) {
        
        if ($text == "/start") {
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => "📢 نشر رسالة", 'callback_data' => "publish_msg"],
                ['text' => "💰 دعم بالنجوم", 'callback_data' => "support_stars"]
            ],
            [
                ['text' => "📊 عدد المشتركين", 'callback_data' => "user_count"]
            ]
        ]
    ];

    $reply = "أهلاً بك يا باشمهندس في لوحة التحكم. اختر ما تريد القيام به:";
    
    // إرسال الرسالة مع الأزرار
    $url = $website . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($reply) . "&reply_markup=" . json_encode($keyboard);
    file_get_contents($url);

   //$reply = "أهلاً بك يا باشمهندس    
 //$name! أنا أعمل الآن من اليمن بكل كفاءة.";

        } elseif ($text == "نشر") {
            $reply = "أهلاً يا بشمهندس، ماذا تريد أن تنشر؟";
        } else {
            $reply = "لقد استلمت رسالتك يا مهندسة: " . $text;
        }

    } else {
        // الرد للأشخاص غير المصرح لهم (المتطفلين)
        $reply = "عذراً، هذا البوت مخصص لفريق المهندسين فقط.";
    }

    // 5. إرسال الرد النهائي (باستخدام urlencode لضمان سلامة اللغة العربية)
    file_get_contents($website."/sendMessage?chat_id=".$chatId."&text=".urlencode($reply));
}
?>
