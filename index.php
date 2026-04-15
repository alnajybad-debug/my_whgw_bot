<?php
$botToken = "8406108478:AAEaJPfFHN4u83_uX6je2pguLipWnTI-VnI"; 
$website = "https://api.telegram.org/bot".$botToken;

$content = file_get_contents("php://input");
$update = json_decode($content, true);

$file = 'users.txt';
if (!file_exists($file)) { file_put_contents($file, ""); }

// معالجة ضغطات الأزرار
if (isset($update["callback_query"])) {
    $callbackData = $update["callback_query"]["data"];
    $callbackChatId = $update["callback_query"]["message"]["chat"]["id"];
    if ($callbackData == "user_count") {
        $allUsers = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $count = count($allUsers);
        file_get_contents($website."/sendMessage?chat_id=".$callbackChatId."&text=".urlencode("📊 عدد المشتركين حالياً هو: $count"));
    }
    exit;
}

if (isset($update["message"])) {
    $chatId = (string)$update["message"]["chat"]["id"]; // تحويل إلى نص لضمان المطابقة
    $text = $update["message"]["text"];
    $name = $update["message"]["from"]["first_name"];

    // حفظ المستخدم
    $current_users = file_get_contents($file);
    if (strpos($current_users, $chatId) === false) {
        file_put_contents($file, $chatId . PHP_EOL, FILE_APPEND);
    }

    // ضع رقم الـ ID الخاص بك هنا بدلاً من الصفر
    $admins = ["7785947020"]; 

            if ($text == "/start") {
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

            $reply = "أهلاً بك يا باشمهندس $name ✅\nتم تفعيل لوحة التحكم الخاصة بك:";
            
            $url = $website . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($reply) . "&reply_markup=" . json_encode($keyboard);
            file_get_contents($url);
            exit; 
        }

        
        // إذا كتب الأدمن أي شيء آخر غير /start
        file_get_contents($website."/sendMessage?chat_id=$chatId&text=".urlencode("مرحباً مهندس $name، لقد استلمت: $text"));
        exit;
    } else {
        // الرد لغير الأدمن
        file_get_contents($website."/sendMessage?chat_id=$chatId&text=".urlencode("عذراً، هذا البوت مخصص لفريق المهندسين فقط."));
        exit;
    }
}
?>
