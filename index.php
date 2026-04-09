<?php
// 1. إعداد التوكن
$botToken = "8406108478:AAEaJPfFHN4u83_uX6je2pguLipWnTI-VnI"; 
$website = "https://api.telegram.org/bot".$botToken;

// 2. استقبال البيانات من تلجرام
$content = file_get_contents("php://input");
$update = json_decode($content, true);

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
            $reply = "أهلاً بك يا باشمهندس $name! أنا أعمل الآن من اليمن بكل كفاءة.";
        } elseif ($text == "نشر") {
            $reply = "أهلاً يا بشمهندس، ماذا تريد أن تنشر؟";
        } else {
            $reply = "لقد استلمت رسالتك يا هندسة: " . $text;
        }

    } else {
        // الرد للأشخاص غير المصرح لهم (المتطفلين)
        $reply = "عذراً، هذا البوت مخصص لفريق المهندسين فقط.";
    }

    // 5. إرسال الرد النهائي (باستخدام urlencode لضمان سلامة اللغة العربية)
    file_get_contents($website."/sendMessage?chat_id=".$chatId."&text=".urlencode($reply));
}
?>
