<?php
$admin = '1184585040';
$token = '7787351429:AAFhw4wBOrlB_Z4X6emyx-LzvBOGtP9jl2w';

function bot($method,$datas=[])
{
    global $token;
    $url = "https://api.telegram.org/bot" . $token . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    if (curl_error($ch)) {
        var_dump(curl_error($ch));
    } else {
        return json_decode($res);
    }
}


function kurs(){
    $response = "";
    $xml = file_get_contents("http://cbu.uz/uzc/arkhiv-kursov-valyut/xml/");
    $m = new SimpleXMLElement($xml);
    foreach ($m as $val) {
        if($val->Ccy == 'USD'){
            $response .= "1 USD💵 - " . $val->Rate . " so'm\n";
        }
        if($val->Ccy == 'EUR'){
            $response .= "1 EURO💶 - " . $val->Rate . " so'm\n";
        }
        if($val->Ccy == 'JPY'){
            $response .= "1 JPY💴 - " . $val->Rate . " so'm\n";
        }  if($val->Ccy == 'RUB'){
            $response .= "1 RUB💴 - " . $val->Rate . " so'm\n";
        }
    }
    return $response;
}

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$mid = $message->message_id;
$texts = json_decode(file_get_contents('msgs.json'),true);
$data = $update->callback_query->data;
$type = $message->chat->type;
$text = $message->text;
$cid = $message->chat->id;
$uid= $message->from->id;
$gname = $message->chat->title;
$left = $message->left_chat_member;
$new = $message->new_chat_member;
$name = $message->from->first_name;
$repid = $message->reply_to_message->from->id;
$repname = $message->reply_to_message->from->first_name;
$newid = $message->new_chat_member->id;
$leftid = $message->left_chat_member->id;
$newname = $message->new_chat_member->first_name;
$leftname = $message->left_chat_member->first_name;
$username = $message->from->username;
$cmid = $update->callback_query->message->message_id;
$cusername = $message->chat->username;
$repmid = $message->reply_to_message->message_id;
$ccid = $update->callback_query->message->chat->id;
$cuid = $update->callback_query->message->from->id;
$cqid = $update->callback_query->id;
$botim="@NazoratchBot";
$link = $message->chat->invite_link;
$description = $message->chat->description;

$photo = $update->message->photo;
$gif = $update->message->animation;
$video = $update->message->video;
$music = $update->message->audio;
$voice = $update->message->voice;
$sticker = $update->message->sticker;
$document = $update->message->document;
$for = $message->forward_from;
$forc = $message->forward_from_chat;

mkdir("soni");
mkdir("soni/$cid");
$odam = file_get_contents("soni/$cid/$uid.txt");
$reply = $message->reply_to_message->text;

$tek = bot('getChatMember',[
    'chat_id'=>$cid,
    'user_id'=>$uid,
]);
$get = $tek->result->status;

$us = bot('getChatMembersCount',[
    'chat_id'=>$cid,
    'user_id'=>$uid,
]);
$azo = $us->result;

$des = bot('getChat',[
    'chat_id'=>$cid,
    'user_id'=>$uid,
]);
$desc = $des->result->description;

if($text=="/leave" && $uid==$admin){
    bot('sendmessage',[
        'chat_id'=>$cid,
        'text'=>"<b>Ho‘p xo‘jayin, guruhni tark etaman!</b>",
        'parse_mode'=>'html'
    ]);
    bot('leaveChat',[
        'chat_id'=>$cid
    ]);
}
if($type=="private"){
    if(mb_stripos($text,"/start")!== false){
        $soat = date('H:i', strtotime('5 hour'));
        $sana = date('d.m.Y',strtotime('5 hour'));
        $text = "Assalomu aleykum! Bot guruhlarda tartib saqlash uchun yaratildi. Batafsil bilib olish uchun <b>Qo'shimcha buyruqlar</b> tugmasini bosing.

<i>📆 Bugun sana: $sana
⏰ Hozir soat: $soat</i>";
        bot('sendmessage',[
            'reply_to_message_id'=>$mid,
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'text'=>$text,
            'parse_mode'=>'html',
            'disable_web_page_preview'=>true,
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    [['text'=>"Qo'shimcha buyruqlar",'callback_data'=>"help"]],
                    [['text'=>"Foydali buyruqlar",'callback_data'=>"foydali"]],
                ]
            ])
        ]);
    }
}
if($data=="help"){
    $text = "Botni guruhga qo'shing va admin qiling, bot sizga guruhni nazorat qilishingizda yordam beradi! 

<b>GURUH ADMINLARI UCHUN QO'SHIMCHA BUYRUQLAR</b>

<code>!ro</code> - <b>3 soatga read only rejimiga tushirish</b>
<code>!unro</code> - <b>3 soatlik read only rejimidan chiqarish</b>
<code>!kick</code> - <b>guruhdan chiqarib yuborish</b>
<code>!ban</code> - <b>guruhdan chiqarib qaytib kirolmaydigan qilish</b>
<code>!unban</code> - <b>guruhda bandan chiqarish</b>
<code>!pin</code> - <b>xabarni pinga qistirish</b>
<code>!unpin</code> - <b>xabarni pindan olish</b>

<b>BOT GURUHLARDA</b>

Reklamalarni tozalash
Servis xabarlarni (kirgan/chiqqan) tozalash
Stickerlarni o'chirish
Ovozli xabarlarni o'chirish
Guruhda so'kinganlarni 3 soat read only rejimiga tushirishni ham bot o'zi amalga oshiradi 

<b>Bu komandalar faqat superguruhlarda ishlaydi, shuning  uchun avval botni guruhga qo'shish va administratorlik huquqlarini berish lozim.</b>

Bot guruhga qo'shgan har bir odamingizni hisoblaydi, guruhda <code>/soni</code> buyrug'ini berib bugungi kungacha qo'shgan odamlaringiz sonini bilib oling yoki reply qilip biror xabarga <code>/soni</code> buyrug'ini yuborsangiz replydagi xabar egasini bugungi kungacha guruhga qo'shgan odamlari sonini bilib olasiz!";
    bot('editMessageText',[
        'message_id'=>$cmid,
        'chat_id'=>$ccid,
        'user_id'=>$cuid,
        'text'=>$text,
        'parse_mode'=>'html',
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [['text'=>"Orqaga qaytish",'callback_data'=>"start"]],
                [['text'=>"Guruhga qo'shish", 'url'=>"https://t.me/NazoratchBot?startgroup=new"]],
            ]
        ])
    ]);
}
if($data=="foydali"){
    $ftext = "<b>GURUHLARDA IHSLATILISHI MUMKIN BO'LGAN FOYDALI BUYRUQLAR BILAN TANISHING!</b>

<code>/soat</code> - <b>hozirgi soat</b>
<code>/sana</code> - <b>bugungi sana</b>
<code>/soat</code> - <b>aniq vaqt</b>
<code>/id</code> - <b>sizning ID raqamingiz</b>
<code>/gid</code> - <b>guruh ID raqami</b>
<code>/rasm</code> - <b>profilingiz rasmi</b>
<code>/qoida</code> - <b>guruh infosiga yozilgan qoidalar</b>
<code>/ob_havo</code> - <b>bugungi ob-havo ma'lumoti</b>
<code>/kurs</code> - <b>bugungi valyuta kursi ma'lumoti</b>
<code>/yangilik</code> - <b>bugunning eng so'ngi yangiligi</b>
<code>/ism Ismingiz</code> - <b>ismingiz ma'nosini bilib oling</b>
<code>/musiqa Qo'shiq nomi</code> - <b>musiqalarni qidirish va yuklash, bu buyruq faqat bot lichkasidagina ishlaydi</b>
<code>/inline</code> - <b>inline rejimidagi buyruqlar</b>

<b>Bot inline rejimida kanallarda ham ma'lumot beroladi,</b> <code>/inline</code> <b>buyrug'ini bering kerakli menyuni tanlang va kanalingizga yuboring!</b>

/stat - <b>bot foydalanuvchilari soni. Bu buyruq faqat bot lichkasida ishlaydi!</b>";
    bot('editMessageText',[
        'message_id'=>$cmid,
        'chat_id'=>$ccid,
        'user_id'=>$cuid,
        'text'=>$ftext,
        'parse_mode'=>'html',
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [['text'=>"Orqaga qaytish",'callback_data'=>"start"]],
                [['text'=>"Guruhga qo'shish", 'url'=>"https://t.me/NazoratchBot?startgroup=new"]],
            ]
        ])
    ]);
}
if($data=="start"){
    $soat = date('H:i', strtotime('5 hour'));
    $sana = date('d.m.Y',strtotime('5 hour'));
    $stext = "Assalomu aleykum! Bot guruhlarda tartib saqlash uchun yaratildi. Batafsil bilib olish uchun <b>Qo'shimcha buyruqlar</b> tugmasini, guruhlarda foydali ma'lumotlar olish uchun <b>Foydali buyruqlar</b> tugmasini bosing.

<i>📆 Bugun sana: $sana
⏰ Hozir soat: $soat</i>";
    bot('editMessageText',[
        'message_id'=>$cmid,
        'chat_id'=>$ccid,
        'user_id'=>$cuid,
        'text'=>$stext,
        'parse_mode'=>'html',
        'disable_web_page_preview'=>true,
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [['text'=>"Qo'shimcha buyruqlar",'callback_data'=>"help"]],
                [['text'=>"Foydali buyruqlar",'callback_data'=>"foydali"]],
            ]
        ])
    ]);
}
if($type=="supergroup"){
    if(mb_stripos($text,"/start") !== false){
        $soat = date('H:i', strtotime('5 hour'));
        $sana = date('d.m.Y',strtotime('5 hour'));
        $text = "Assalomu aleykum botdan to'liq foydalanish uchun avval guruhda administratorlik huquqlarini bering va pastdagi <b>Bot haqida</b> tugmasini bosib botning guruhda ishlatilishi mumkin bo'lgan buyruqlari bilan tanishib chiqing. 

Agar botda xato va kamchiliklarni ko'rsangiz <a href='tg://user?id=$admin'>Developer</a> ga yuboring!

<i>📆 Bugun sana: $sana
⏰ Hozir soat: $soat</i>";
        bot('sendmessage',[
            'reply_to_message_id'=>$mid,
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'text'=>$text,
            'parse_mode'=>'html',
            'disable_web_page_preview'=>true,
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    [['text'=>"Bot haqida",'url'=>"https://t.me/NazoratchBot"]],
                ]
            ])
        ]);
    }
    if($left){
        bot('deletemessage',[
            'chat_id'=>$cid,
            'message_id'=>$mid
        ]);
        bot('sendmessage',[
            'chat_id'=>$cid,
            'text'=>"Diqqat! <a href='tg://user?id=$leftid'>$leftname</a>, <b>$gname</b> guruhidan chiqib ketdi!",
            'parse_mode'=>'html'
        ]);
        $lodam = file_get_contents("soni/$cid/$leftid.txt");
        unlink("soni/$cid/$leftid.txt");
    }
    if($new){
        bot('deletemessage',[
            'chat_id'=>$cid,
            'message_id'=>$mid
        ]);
        bot('sendmessage',[
            'chat_id'=>$cid,
            'text'=>"Salom! <a href='tg://user?id=$newid'>$newname</a>, <b>$gname</b> guruhiga xush kelibsiz!",
            'parse_mode'=>'html'
        ]);
        $odam = file_get_contents("soni/$cid/$uid.txt");
        $op = $odam + 1;
        file_put_contents("soni/$cid/$uid.txt","$op");
    }
    if($get == "administrator" or $get == "creator" or $get == "member"){
        if($reply == $text and (mb_stripos($text,"/soni")!==false)){
            $rodam = file_get_contents("soni/$cid/$repid.txt");
            bot('sendmessage',[
                'chat_id'=>$cid,
                'user_id'=>$repid,
                'reply_to_message_id'=>$mid,
                'parse_mode'=>'html',
                'text'=>"<b>$repname</b> bugungi kungacha guruhga <b>$rodam</b> ta odam qo'shgan!",
            ]);
        }elseif($text == "/soni" or $text == "/soni$botim"){
            $odam = file_get_contents("soni/$cid/$uid.txt");
            bot('sendmessage',[
                'chat_id'=>$cid,
                'user_id'=>$uid,
                'reply_to_message_id'=>$mid,
                'parse_mode'=>'html',
                'text'=>"<b>$name</b> siz bugungi kungacha guruhga <b>$odam</b> ta odam qo'shdingiz!",
            ]);
        }
    }
    if(mb_stripos($text,"!ro")!==false){
        if($get == "administrator" or $get == "creator" or $uid==$admin){
            bot('restrictChatMember',[
                'chat_id'=>$cid,
                'user_id'=>$repid,
                'until_date'=>strtotime("+ 180 minutes"),
                'can_send_messages'=>false,
                'can_send_media_messages'=>false,
                'can_send_other_messages'=>false,
                'can_add_web_page_previews'=>false
            ]);
            bot('sendmessage',[
                'chat_id'=>$cid,
                'parse_mode'=>'html',
                'text'=>"<a href='tg://user?id=$repid'>$repname</a> 3 soatga <b>READ ONLY</b> rejimiga tushirildi!",
            ]);
        }
    }
    if(mb_stripos($text,"!unro")!==false){
        if($get == "administrator" or $get == "creator" or $uid==$admin){
            bot('restrictChatMember',[
                'chat_id'=>$cid,
                'user_id'=>$repid,
                'can_send_messages'=>true,
                'can_send_media_messages'=>true,
                'can_send_other_messages'=>true,
                'can_add_web_page_previews'=>true
            ]);
            bot('sendmessage',[
                'chat_id'=>$cid,
                'parse_mode'=>'html',
                'text'=>"<a href='tg://user?id=$repid'>$repname</a> 3 soatlik <b>READ ONLY</b> rejimidan ozod qilindi!",
            ]);
        }
    }
    if(mb_stripos($text,"!kick")!==false){
        if($get == "administrator" or $get == "creator" or $uid==$admin){
            bot('kickChatMember',[
                'chat_id'=>$cid,
                'user_id'=>$repid,
                'can_send_messages'=>true,
                'can_send_media_messages'=>true,
                'can_send_other_messages'=>true,
                'can_add_web_page_previews'=>true
            ]);
            bot('unbanChatMember',[
                'chat_id'=>$cid,
                'user_id'=>$repid,
            ]);
            bot('sendmessage',[
                'chat_id'=>$cid,
                'parse_mode'=>'html',
                'text'=>"<a href='tg://user?id=$repid'>$repname</a> guruhdan <b>KICK</b> qilindi!",
            ]);
        }
    }
    if(mb_stripos($text,"!ban")!==false){
        if($get == "administrator" or $get == "creator" or $uid==$admin){
            bot('kickChatMember',[
                'chat_id'=>$cid,
                'user_id'=>$repid,
                'can_send_messages'=>false,
                'can_send_media_messages'=>false,
                'can_send_other_messages'=>false,
                'can_add_web_page_previews'=>false
            ]);
            bot('sendmessage',[
                'chat_id'=>$cid,
                'parse_mode'=>'html',
                'text'=>"<a href='tg://user?id=$repid'>$repname</a> guruhdan <b>KICK</b> va <b>BAN</b> qilindi!",
            ]);
        }
    }
    if(mb_stripos($text,"!unban")!==false){
        if($get == "administrator" or $get == "creator" or $uid==$admin){
            bot('unbanChatMember',[
                'chat_id'=>$cid,
                'user_id'=>$repid,
            ]);
            bot('sendmessage',[
                'chat_id'=>$cid,
                'parse_mode'=>'html',
                'text'=>"<a href='tg://user?id=$repid'>$repname</a> guruhda <b>BAN</b> dan ozod qilindi!",
            ]);
        }
    }
    if(mb_stripos($text,"!pin")!==false){
        if($get == "administrator" or $get == "creator" or $uid==$admin){
            bot('PinchatMessage',[
                'chat_id'=>$cid,
                'message_id'=>$repmid
            ]);
            bot('sendmessage',[
                'chat_id'=>$cid,
                'parse_mode'=>'html',
                'text'=>"<a href='tg://user?id=$repid'>$repname</a> xabaringiz <b>PIN</b> ga qistirildi!",
            ]);
        }
    }
    if(mb_stripos($text,"!unpin")!==false){
        if($get == "administrator" or $get == "creator" or $uid==$admin){
            bot('UnpinchatMessage',[
                'chat_id'=>$cid,
                'message_id'=>$repmid
            ]);
            bot('sendmessage',[
                'chat_id'=>$cid,
                'parse_mode'=>'html',
                'text'=>"<a href='tg://user?id=$repid'>$repname</a> xabaringiz <b>PIN</b> dan olindi!",
            ]);
        }
    }
    if($sticker){
        if($get == "member"){
            bot('deletemessage',[
                'chat_id'=>$cid,
                'message_id'=>$mid
            ]);
            bot('sendmessage',[
                'chat_id'=>$cid,
                'parse_mode'=>'html',
                'text'=>"<a href='tg://user?id=$uid'>$name</a> guruhda <b>STICKER</b> yuborish taqiqlangan!",
            ]);
        }
    }
    if($voice){
        if($get == "member"){
            bot('deletemessage',[
                'chat_id'=>$cid,
                'message_id'=>$mid
            ]);
            bot('sendmessage',[
                'chat_id'=>$cid,
                'parse_mode'=>'html',
                'text'=>"<a href='tg://user?id=$uid'>$name</a> guruhda <b>OVOZLI XABAR</b> yuborish taqiqlangan!",
            ]);
        }
    }
    if((mb_stripos($text,"http")!== false) or (mb_stripos($text,"t.me")!== false) or (mb_stripos($text,"telegram.me")!== false) or (mb_stripos($text,"bot?start")!== false)){
        if($get == "member"){
            bot('deletemessage',[
                'chat_id'=>$cid,
                'message_id'=>$mid
            ]);
            bot('sendmessage',[
                'chat_id'=>$cid,
                'parse_mode'=>'html',
                'text'=>"<a href='tg://user?id=$uid'>$name</a> guruhda <b>REKLAMA</b> yuborish taqiqlangan!",
            ]);
        }
    }
    if($for or $forc){
        if($get == "member"){
            bot('deletemessage',[
                'chat_id'=>$cid,
                'message_id'=>$mid
            ]);
            bot('sendmessage',[
                'chat_id'=>$cid,
                'parse_mode'=>'html',
                'text'=>"<a href='tg://user?id=$uid'>$name</a> guruhda <b>REKLAMA</b> yuborish taqiqlangan!",
            ]);
        }
    }
}
if(mb_stripos($text,"Izzat") !== false){
    bot('sendmessage',[
        'reply_to_message_id'=>$mid,
        'chat_id'=>$cid,
        'text'=>"Xabaringiz <a href='tg://user?id=$admin'>DEVELOPER</a> ga yetkazildi!",
        'parse_mode'=>'html'
    ]);
    bot('sendmessage',[
        'chat_id'=>$admin,
        'text'=>"Sizga yangi xabar bor.

Guruh haqida:
Guruh nomi: $gname
Guruh useri: @$cusername

Xabarchi haqida:
Ism: <a href='tg://user?id=$uid'>$name</a>
Useri: @$username

Xabar matni: 
<b>$text</b>",
        'parse_mode'=>'html'
    ]);
}
if($type=="supergroup"){
    if((mb_stripos($text,"Dnx")!== false)
        or (mb_stripos($text,"Jalap")!== false)
        or (mb_stripos($text,"Pnx")!== false)
        or (mb_stripos($text,"Qoto")!== false)
        or (mb_stripos($text,"Gandon")!== false)
        or (mb_stripos($text,"Suka")!== false)
        or (mb_stripos($text,"Tvar")!== false)
        or (mb_stripos($text,"Xaske")!== false)
        or (mb_stripos($text,"Latta")!== false)
        or (mb_stripos($text,"Oneni")!== false)
        or (mb_stripos($text,"Ayeni")!== false)
        or (mb_stripos($text,"Seks")!== false)
        or (mb_stripos($text,"Sekis")!== false)
        or (mb_stripos($text,"Naxuy")!== false)
        or (mb_stripos($text,"Nahuy")!== false)
        or (mb_stripos($text,"Naxoy")!== false)
        or (mb_stripos($text,"Nahoy")!== false)
        or (mb_stripos($text,"Quto")!== false)
        or (mb_stripos($text,"Ske")!== false)
        or (mb_stripos($text,"Skaman")!== false)
        or (mb_stripos($text,"Sikaman")!== false)
        or (mb_stripos($text,"Avatar")!== false)
        or (mb_stripos($text,"Yiban")!== false)
        or (mb_stripos($text,"Yban")!== false)
        or (mb_stripos($text,"Xarom")!== false)
        or (mb_stripos($text,"Xaromi")!== false)
        or (mb_stripos($text,"Am")!== false)
        or (mb_stripos($text,"xarp")!== false)
        or (mb_stripos($text,"yeban")!== false)
        or (mb_stripos($text,"om")!== false)
        or (mb_stripos($text,"xuy")!== false)
        or (mb_stripos($text,"xuysinim")!== false)
        or (mb_stripos($text,"tvar")!== false)
        or (mb_stripos($text,"haromi")!== false)
        or (mb_stripos($text,"harom")!== false)
        or (mb_stripos($text,"jalap") !== false)
        or (mb_stripos($text,"j4lap") !== false)
        or (mb_stripos($text,"pnx") !== false)
        or (mb_stripos($text,"qoto") !== false)
        or (mb_stripos($text,"q*to") !== false)
        or (mb_stripos($text,"gandon") !== false)
        or (mb_stripos($text,"g@ndon") !== false)
        or (mb_stripos($text,"suka") !== false)
        or (mb_stripos($text,"cyka") !== false)
        or (mb_stripos($text,"tvar") !== false)
        or (mb_stripos($text,"tv@r") !== false)
        or (mb_stripos($text,"xaske") !== false)
        or (mb_stripos($text,"x@ske") !== false)
        or (mb_stripos($text,"latta") !== false)
        or (mb_stripos($text,"l@tta") !== false)
        or (mb_stripos($text,"oneni") !== false)
        or (mb_stripos($text,"on3ni") !== false)
        or (mb_stripos($text,"ayeni") !== false)
        or (mb_stripos($text,"ay3ni") !== false)
        or (mb_stripos($text,"seks") !== false)
        or (mb_stripos($text,"secks") !== false)
        or (mb_stripos($text,"sekis") !== false)
        or (mb_stripos($text,"sekc") !== false)
        or (mb_stripos($text,"naxuy") !== false)
        or (mb_stripos($text,"n4huy") !== false)
        or (mb_stripos($text,"nahuy") !== false)
        or (mb_stripos($text,"n4xuy") !== false)
        or (mb_stripos($text,"naxoy") !== false)
        or (mb_stripos($text,"nahoy") !== false)
        or (mb_stripos($text,"quto") !== false)
        or (mb_stripos($text,"kuto") !== false)
        or (mb_stripos($text,"ske") !== false)
        or (mb_stripos($text,"skaman") !== false)
        or (mb_stripos($text,"sikaman") !== false)
        or (mb_stripos($text,"avatar") !== false) // ba'zida mem sifatida ishlatiladi
        or (mb_stripos($text,"yiban") !== false)
        or (mb_stripos($text,"yban") !== false)
        or (mb_stripos($text,"jala") !== false)
        or (mb_stripos($text,"jeb") !== false)
        or (mb_stripos($text,"jebem") !== false)
        or (mb_stripos($text,"blya") !== false)
        or (mb_stripos($text,"b1ya") !== false)
        or (mb_stripos($text,"blyad") !== false)
        or (mb_stripos($text,"b!yad") !== false)
        or (mb_stripos($text,"pidr") !== false)
        or (mb_stripos($text,"pid@r") !== false)
        or (mb_stripos($text,"pi3d") !== false)
        or (mb_stripos($text,"eblan") !== false)
        or (mb_stripos($text,"e6lan") !== false)
        or (mb_stripos($text,"durak") !== false)
        or (mb_stripos($text,"durok") !== false)
        or (mb_stripos($text,"xuy") !== false)
        or (mb_stripos($text,"xyi") !== false)
        or (mb_stripos($text,"hui") !== false)
        or (mb_stripos($text,"hooy") !== false)
        or (mb_stripos($text,"oxuy") !== false)
        or (mb_stripos($text,"oxoy") !== false)
        or (mb_stripos($text,"loxx") !== false)
        or (mb_stripos($text,"lox") !== false)
        or (mb_stripos($text,"loch") !== false)
        or (mb_stripos($text,"urishman") !== false)
        or (mb_stripos($text,"siktir") !== false)
        or (mb_stripos($text,"sik") !== false)
        or (mb_stripos($text,"s1k") !== false)
        or (mb_stripos($text,"shluha") !== false)
        or (mb_stripos($text,"shluxa") !== false)
        or (mb_stripos($text,"4len") !== false)
        or (mb_stripos($text,"еблан") !== false)
        or (mb_stripos($text,"е6лан") !== false)
        or (mb_stripos($text,"е6л@н") !== false)

        or (mb_stripos($text,"ёб твою мать") !== false)
        or (mb_stripos($text,"ёб т*вою") !== false)
        or (mb_stripos($text,"ёб тваю") !== false)
        or (mb_stripos($text,"yob tvoyu") !== false)

        or (mb_stripos($text,"нахер") !== false)
        or (mb_stripos($text,"нах*р") !== false)
        or (mb_stripos($text,"naxer") !== false)

        or (mb_stripos($text,"выеби") !== false)
        or (mb_stripos($text,"вые6и") !== false)
        or (mb_stripos($text,"vye6i") !== false)

        or (mb_stripos($text,"заебал") !== false)
        or (mb_stripos($text,"зае6ал") !== false)
        or (mb_stripos($text,"zaebal") !== false)

        or (mb_stripos($text,"отсоси") !== false)
        or (mb_stripos($text,"отс0си") !== false)
        or (mb_stripos($text,"otsosi") !== false)

        or (mb_stripos($text,"ебись") !== false)
        or (mb_stripos($text,"e6ись") !== false)

        or (mb_stripos($text,"выебу") !== false)
        or (mb_stripos($text,"вые6у") !== false)

        or (mb_stripos($text,"ебал мать") !== false)
        or (mb_stripos($text,"е6ал мать") !== false)
        or (mb_stripos($text,"ebal mat") !== false)

        or (mb_stripos($text,"ебало") !== false)
        or (mb_stripos($text,"е6ало") !== false)

        or (mb_stripos($text,"жопа") !== false)
        or (mb_stripos($text,"ж0па") !== false)
        or (mb_stripos($text,"zhopa") !== false)

        or (mb_stripos($text,"в жопу") !== false)
        or (mb_stripos($text,"в ж0пу") !== false)
        or (mb_stripos($text,"v zhopy") !== false)

        or (mb_stripos($text,"залупа") !== false)
        or (mb_stripos($text,"з@лупа") !== false)
        or (mb_stripos($text,"zalupa") !== false)

        or (mb_stripos($text,"сперм") !== false)
        or (mb_stripos($text,"сперма") !== false)
        or (mb_stripos($text,"sperma") !== false)

        or (mb_stripos($text,"анус") !== false)
        or (mb_stripos($text,"ан@с") !== false)
        or (mb_stripos($text,"anus") !== false)

        or (mb_stripos($text,"проститут") !== false)
        or (mb_stripos($text,"простит") !== false)
        or (mb_stripos($text,"prostitut") !== false)

        or (mb_stripos($text,"gay") !== false)
        or (mb_stripos($text,"gey") !== false)
        or (mb_stripos($text,"гей") !== false)
        or (mb_stripos($text,"g3y") !== false)
        or (mb_stripos($text,"g@y") !== false)

        or (mb_stripos($text,"lesbi") !== false)
        or (mb_stripos($text,"lesbiyan") !== false)
        or (mb_stripos($text,"лесби") !== false)
        or (mb_stripos($text,"лесбиянка") !== false)
        or (mb_stripos($text,"l3sbi") !== false)
        or (mb_stripos($text,"l3zbiyan") !== false)

        or (mb_stripos($text,"lgbt") !== false)
        or (mb_stripos($text,"лгбт") !== false)
        or (mb_stripos($text,"лгбтк") !== false)
        or (mb_stripos($text,"lgbtq") !== false)

        or (mb_stripos($text,"nonbinary") !== false)
        or (mb_stripos($text,"n0nbinary") !== false)
        or (mb_stripos($text,"n0nbin") !== false)

        or (mb_stripos($text,"trans") !== false)
        or (mb_stripos($text,"транс") !== false)
        or (mb_stripos($text,"tr@ns") !== false)
        or (mb_stripos($text,"transgender") !== false)
        or (mb_stripos($text,"transseksual") !== false)
        or (mb_stripos($text,"транссексуал") !== false)

        or (mb_stripos($text,"biseksual") !== false)
        or (mb_stripos($text,"бисексуал") !== false)

        or (mb_stripos($text,"queer") !== false)
        or (mb_stripos($text,"квир") !== false)

        or (mb_stripos($text,"panseksual") !== false)
        or (mb_stripos($text,"пансексуал") !== false)

        or (mb_stripos($text,"interseks") !== false)
        or (mb_stripos($text,"интерсекс") !== false)


        or (mb_stripos($text,"Vart")!== false))
    {
        if($get == "member"){
            bot('deletemessage',[
                'chat_id'=>$cid,
                'message_id'=>$mid,
            ]);
            bot('restrictChatMember',[
                'chat_id'=>$cid,
                'user_id'=>$uid,
                'until_date'=>strtotime("+ 180 minutes"),
                'can_send_messages'=>false,
                'can_send_media_messages'=>false,
                'can_send_other_messages'=>false,
                'can_add_web_page_previews'=>false
            ]);
            bot('sendmessage',[
                'chat_id'=>$cid,
                'parse_mode'=>'html',
                'text'=>"<a href='tg://user?id=$uid'>$name</a> guruhda <b>SO'KINGANINGIZ UCHUN</b> 3 soat read only rejimiga tushirildingiz!",
            ]);
        }
    }
    if($text=="/qoida" or $text=="/qoida$botim"){
        bot('sendmessage',[
            'chat_id'=>$cid,
            'text'=>"$desc

----- ----- ----- ----- -----
Guruh a'zolari soni: <b>$azo</b> ta",
            'reply_to_message_id'=>$mid,
            'parse_mode'=>'html',
        ]);
    }
}
$gruppa = file_get_contents("gruppa.db");
$lichka = file_get_contents("lichka.db");
$xabar = file_get_contents("xabarlar.txt");
$gxabar = file_get_contents("gxabarlar.txt");
if($type=="supergroup"){
    mkdir("data");
    mkdir("data/$cid");
    if(strpos($gruppa,"$cid") !==false){
    }else{
        file_put_contents("gruppa.db","$gruppa\n$cid");
    }
}
if($type=="private"){
    if(strpos($lichka,"$cid") !==false){
    }else{
        file_put_contents("lichka.db","$lichka\n$cid");
    }
}
$reply = $message->reply_to_message->text;
$rpl = json_encode([
    'resize_keyboard'=>false,
    'force_reply'=>true,
    'selective'=>true
]);
if($text=="/send" and $cid==$admin){
    bot('sendmessage',[
        'chat_id'=>$admin,
        'text'=>"Yuboriladigan xabar matnini kiriting!",'parse_mode'=>"html",'reply_markup'=>$rpl
    ]);
}
if($reply=="Yuboriladigan xabar matnini kiriting!"){
    file_put_contents("xabarlar.txt","$text");
    $xabar = file_get_contents("xabarlar.txt");
    $lich = file_get_contents("lichka.db");
    $lichka = explode("\n",$lich);
    foreach($lichka as $uid){
        bot("sendmessage",[
            'chat_id'=>$uid,
            'text'=>$xabar,
        ]);
        unlink("xabarlar.txt");
    }
}
if($text=="/sendgroup" and $cid==$admin){
    bot('sendmessage',[
        'chat_id'=>$admin,
        'text'=>"Guruhlarga yuboriladigan xabar matnini kiriting!",'parse_mode'=>"html",'reply_markup'=>$rpl
    ]);
}
if($reply=="Guruhlarga yuboriladigan xabar matnini kiriting!"){
    file_put_contents("gxabarlar.txt","$text");
    $gxabar = file_get_contents("gxabarlar.txt");
    $gr = file_get_contents("gruppa.db");
    $grup = explode("\n",$gr);
    foreach($grup as $cid){
        bot("sendmessage",[
            'chat_id'=>$cid,
            'text'=>$gxabar,
        ]);
        unlink("gxabarlar.txt");
    }
}
if($type=="private"){
    if($text=="/stat"){
        $soat = date('H:i', strtotime('5 hour'));
        $sana = date('d.m.Y',strtotime('5 hour'));
        $lich = substr_count($lichka,"\n");
        $gr = substr_count($gruppa,"\n");
        $jami = $lich + $gr;
        bot('sendmessage',[
            'chat_id'=>$cid,
            'reply_to_message_id'=>$mid,
            'text'=>"<b>Bot foydalanuvchilari soni:</b>

A'zolar: <b>$lich</b> ta
Guruhlar: <b>$gr</b> ta
Xammasi bo'lib: <b>$jami</b> ta

<i>📆 Bugun sana: $sana
⏰ Hozir soat: $soat</i>",
            'parse_mode'=>"html"
        ]);
    }
}
if($text=="/soat" or $text=="/soat$botim"){
    $soat = date('H:i:s', strtotime('5 hour'));
    $sana = date('d.m.Y',strtotime('5 hour'));
    $text = "<b>⏰ Hozir soat: $soat</b>";
    bot('sendmessage',[
        'chat_id'=>$cid,
        'user_id'=>$uid,
        'reply_to_message_id'=>$mid,
        'text'=>$text,
        'parse_mode'=>'html',
    ]);
}
if($text=="/sana" or $text=="/sana$botim"){
    $soat = date('H:i:s', strtotime('5 hour'));
    $sana = date('d.m.Y',strtotime('5 hour'));
    $text = "<b>📅 Bugun sana: $sana</b>";
    bot('sendmessage',[
        'chat_id'=>$cid,
        'user_id'=>$uid,
        'reply_to_message_id'=>$mid,
        'text'=>$text,
        'parse_mode'=>'html',
    ]);
}
if($text=="/id" or $text=="/id$botim"){
    $soat = date('H:i:s', strtotime('5 hour'));
    $sana = date('d.m.Y',strtotime('5 hour'));
    $text = "<b>🆔 Sizning ID raqamizgiz:</b> <code>$uid</code>";
    bot('sendmessage',[
        'chat_id'=>$cid,
        'user_id'=>$uid,
        'reply_to_message_id'=>$mid,
        'text'=>$text,
        'parse_mode'=>'html',
    ]);
}
if($text=="/gid" or $text=="/gid$botim"){
    $soat = date('H:i:s', strtotime('5 hour'));
    $sana = date('d.m.Y',strtotime('5 hour'));
    $text = "<b>🆔 Guruh ID raqami:</b> <code>$cid</code>";
    bot('sendmessage',[
        'chat_id'=>$cid,
        'user_id'=>$uid,
        'reply_to_message_id'=>$mid,
        'text'=>$text,
        'parse_mode'=>'html',
    ]);
}
if($text=="/rasm" or $text=="/rasm$botim"){
    $getp = file_get_contents("https://api.telegram.org/bot$token/getUserProfilePhotos?user_id=$uid&limit=1");
    $json = json_decode($getp);
    $photo = $json->result->photos[0][0]->file_id;
    bot('sendPhoto',[
        'chat_id'=>$cid,
        'photo'=>$photo,
        'reply_to_message_id'=>$mid,
        'parse_mode'=>'html',
    ]);
}
if($text=="/yangi_yil" or $text=="/yangi_yil$botim"){
    $kun1 = date('z ',strtotime('5 hour'));
    $a = 365;
    $c2 = $a-$kun1;
    $d = date('L ',strtotime('5 hour'));
    $b = $c2+$d;
    $f = $b+81;
    $e = $b+240;
    $kun2 = date('H ',strtotime('5 hour'));
    $a2 = 23;
    $b2 = $a2-$kun2;
    $kun3 = date('i ',strtotime('5 hour'));
    $a3 = 59;
    $b3 = $a3-$kun3;
    $kun4 = date('s ',strtotime('5 hour'));
    $a4 = 60;
    $b4 = $a4-$kun4;
    $yytxt="🎅 <b>Yangi yilga $b kun, $b2 soat, $b3 minut, $b4 sekund qoldi!</b> ⛄";
    bot('sendmessage',[
        'chat_id'=>$cid,
        'user_id'=>$uid,
        'reply_to_message_id'=>$mid,
        'text'=>$yytxt,
        'parse_mode'=>'html',
    ]);
}
if($text=="/ob_havo" or $text=="/ob_havo$botim"){
    $text = "Bugungi <b>OB - HAVO</b> ma'lumoti bilan tanishish uchun o'zingiz yashab turgan manzilni tanlang!";
    bot('sendmessage',[
        'chat_id'=>$cid,
        'user_id'=>$uid,
        'reply_to_message_id'=>$mid,
        'text'=>$text,
        'parse_mode'=>'html',
        'disable_web_page_preview'=>true,
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [['text'=>"⛅ Farg'ona",'callback_data'=>"far"],
                    ['text'=>"⛅ Xiva",'callback_data'=>"xiv"]],
                [['text'=>"⛅ Andijon",'callback_data'=>"and"],
                    ['text'=>"⛅ Namangan",'callback_data'=>"nam"]],
                [['text'=>"⛅ Buxoro",'callback_data'=>"bux"],
                    ['text'=>"⛅ Guliston",'callback_data'=>"gul"]],
                [['text'=>"⛅ Jizzax",'callback_data'=>"jiz"],
                    ['text'=>"⛅ Zarafshon",'callback_data'=>"zar"]],
                [['text'=>"⛅ Qarshi",'callback_data'=>"qar"],
                    ['text'=>"⛅ Navoiy",'callback_data'=>"nav"]],
                [['text'=>"⛅ Nukus",'callback_data'=>"nuk"],
                    ['text'=>"⛅ Samarqand",'callback_data'=>"sam"]],
                [['text'=>"⛅ Termiz",'callback_data'=>"ter"],
                    ['text'=>"⛅ Urganch",'callback_data'=>"urg"]],
                [['text'=>"⛅ Toshkent",'callback_data'=>"tosh"]],
            ]
        ])
    ]);
}
if($data=="far"){
    $anb8 = file_get_contents('https://obhavo.uz/ferghana'); $ex1=explode("\n",$anb8);
    $gr1=str_replace('<span><strong>',' ',$ex1[73]);
    $gr1=str_replace('</strong></span>',' ',$gr1);
    $gr1= trim($gr1);
    $gr2=str_replace('<span>',' ',$ex1[74]);  $gr2=str_replace('</span>',' ',$gr2);
    $gr2= trim($gr2);
    $havo1=str_replace('<div class="current-forecast-desc">',' ',$ex1[77]);
    $havo1 = str_replace('&#039;','‘',$havo1);
    $havo1=str_replace('</div>',' ',$havo1);
    $havo1 = trim($havo1);
    $tongr=str_replace('<p class="forecast">',' ',$ex1[99]);
    $tongr=str_replace('</p>',' ',$tongr);
    $tongr = trim($tongr);
    $kungr=str_replace('<p class="forecast">',' ',$ex1[104]);
    $kungr=str_replace('</p>',' ',$kungr);
    $kungr = trim($kungr);
    $oqgr=str_replace('<p class="forecast">',' ',$ex1[109]);
    $oqgr=str_replace('</p>',' ',$oqgr);
    $oqgr = trim($oqgr);
    $bugun=str_replace('<div class="current-day">',' ',$ex1[67]);
    $bugun=str_replace('</div>',' ',$bugun);
    $bugun = trim($bugun);
    $qch=str_replace('<p>',' ',$ex1[87]);
    $qch=str_replace('</p>',' ',$qch);
    $qch= trim($qch);
    $qb=str_replace('<p>',' ',$ex1[88]);
    $qb=str_replace('</p>',' ',$qb);
    $qb= trim($qb);
    $sha=str_replace('<p>',' ',$ex1[82]);
    $sha = str_replace('&#039;','‘',$sha);
    $sha=str_replace('</p>',' ',$sha);
    $sha= trim($sha);
    $bosim=str_replace('<p>',' ',$ex1[83]);
    $bosim=str_replace('</p>',' ',$bosim);
    $bosim= trim($bosim);
    $oy=str_replace('<p>',' ',$ex1[86]);
    $oy = str_replace('&#039;','‘',$oy);
    $oy=str_replace('</p>',' ',$oy);
    $oy= trim($oy);
    $nam=str_replace('<p>',' ',$ex1[81]);
    $nam=str_replace('</p>',' ',$nam);
    $nam= trim($nam);
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>"📆 $bugun
🌏 Farg'ona
⛅ Harorat: $gr1 ... $gr2
☁ Ob-havo: $havo1
🌄 Tong: $tongr
🌅 Kun: $kungr
🌃 Oqshom: $oqgr
💧 $nam
🌝 $qch
🌚 $qb",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($data=="tosh"){
    $anb8 = file_get_contents('https://obhavo.uz/tashkent'); $ex1=explode("\n",$anb8);
    $gr1=str_replace('<span><strong>',' ',$ex1[73]);
    $gr1=str_replace('</strong></span>',' ',$gr1);
    $gr1= trim($gr1);
    $gr2=str_replace('<span>',' ',$ex1[74]);  $gr2=str_replace('</span>',' ',$gr2);
    $gr2= trim($gr2);
    $havo1=str_replace('<div class="current-forecast-desc">',' ',$ex1[77]);
    $havo1 = str_replace('&#039;','‘',$havo1);
    $havo1=str_replace('</div>',' ',$havo1);
    $havo1 = trim($havo1);
    $tongr=str_replace('<p class="forecast">',' ',$ex1[99]);
    $tongr=str_replace('</p>',' ',$tongr);
    $tongr = trim($tongr);
    $kungr=str_replace('<p class="forecast">',' ',$ex1[104]);
    $kungr=str_replace('</p>',' ',$kungr);
    $kungr = trim($kungr);
    $oqgr=str_replace('<p class="forecast">',' ',$ex1[109]);
    $oqgr=str_replace('</p>',' ',$oqgr);
    $oqgr = trim($oqgr);
    $bugun=str_replace('<div class="current-day">',' ',$ex1[67]);
    $bugun=str_replace('</div>',' ',$bugun);
    $bugun = trim($bugun);
    $qch=str_replace('<p>',' ',$ex1[87]);
    $qch=str_replace('</p>',' ',$qch);
    $qch= trim($qch);
    $qb=str_replace('<p>',' ',$ex1[88]);
    $qb=str_replace('</p>',' ',$qb);
    $qb= trim($qb);
    $sha=str_replace('<p>',' ',$ex1[82]);
    $sha = str_replace('&#039;','‘',$sha);
    $sha=str_replace('</p>',' ',$sha);
    $sha= trim($sha);
    $bosim=str_replace('<p>',' ',$ex1[83]);
    $bosim=str_replace('</p>',' ',$bosim);
    $bosim= trim($bosim);
    $oy=str_replace('<p>',' ',$ex1[86]);
    $oy = str_replace('&#039;','‘',$oy);
    $oy=str_replace('</p>',' ',$oy);
    $oy= trim($oy);
    $nam=str_replace('<p>',' ',$ex1[81]);
    $nam=str_replace('</p>',' ',$nam);
    $nam= trim($nam);
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>"📆 $bugun
🌏 Toshkent
⛅ Harorat: $gr1 ... $gr2
☁ Ob-havo: $havo1
🌄 Tong: $tongr
🌅 Kun: $kungr
🌃 Oqshom: $oqgr
💧 $nam
🌝 $qch
🌚 $qb",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($data=="and"){
    $anb8 = file_get_contents('https://obhavo.uz/andijan'); $ex1=explode("\n",$anb8);
    $gr1=str_replace('<span><strong>',' ',$ex1[73]);
    $gr1=str_replace('</strong></span>',' ',$gr1);
    $gr1= trim($gr1);
    $gr2=str_replace('<span>',' ',$ex1[74]);  $gr2=str_replace('</span>',' ',$gr2);
    $gr2= trim($gr2);
    $havo1=str_replace('<div class="current-forecast-desc">',' ',$ex1[77]);
    $havo1 = str_replace('&#039;','‘',$havo1);
    $havo1=str_replace('</div>',' ',$havo1);
    $havo1 = trim($havo1);
    $tongr=str_replace('<p class="forecast">',' ',$ex1[99]);
    $tongr=str_replace('</p>',' ',$tongr);
    $tongr = trim($tongr);
    $kungr=str_replace('<p class="forecast">',' ',$ex1[104]);
    $kungr=str_replace('</p>',' ',$kungr);
    $kungr = trim($kungr);
    $oqgr=str_replace('<p class="forecast">',' ',$ex1[109]);
    $oqgr=str_replace('</p>',' ',$oqgr);
    $oqgr = trim($oqgr);
    $bugun=str_replace('<div class="current-day">',' ',$ex1[67]);
    $bugun=str_replace('</div>',' ',$bugun);
    $bugun = trim($bugun);
    $qch=str_replace('<p>',' ',$ex1[87]);
    $qch=str_replace('</p>',' ',$qch);
    $qch= trim($qch);
    $qb=str_replace('<p>',' ',$ex1[88]);
    $qb=str_replace('</p>',' ',$qb);
    $qb= trim($qb);
    $sha=str_replace('<p>',' ',$ex1[82]);
    $sha = str_replace('&#039;','‘',$sha);
    $sha=str_replace('</p>',' ',$sha);
    $sha= trim($sha);
    $bosim=str_replace('<p>',' ',$ex1[83]);
    $bosim=str_replace('</p>',' ',$bosim);
    $bosim= trim($bosim);
    $oy=str_replace('<p>',' ',$ex1[86]);
    $oy = str_replace('&#039;','‘',$oy);
    $oy=str_replace('</p>',' ',$oy);
    $oy= trim($oy);
    $nam=str_replace('<p>',' ',$ex1[81]);
    $nam=str_replace('</p>',' ',$nam);
    $nam= trim($nam);
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>"📆 $bugun
🌏 Andijon
⛅ Harorat: $gr1 ... $gr2
☁ Ob-havo: $havo1
🌄 Tong: $tongr
🌅 Kun: $kungr
🌃 Oqshom: $oqgr
💧 $nam
🌝 $qch
🌚 $qb",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($data=="nam"){
    $anb8 = file_get_contents('https://obhavo.uz/namangan'); $ex1=explode("\n",$anb8);
    $gr1=str_replace('<span><strong>',' ',$ex1[73]);
    $gr1=str_replace('</strong></span>',' ',$gr1);
    $gr1= trim($gr1);
    $gr2=str_replace('<span>',' ',$ex1[74]);  $gr2=str_replace('</span>',' ',$gr2);
    $gr2= trim($gr2);
    $havo1=str_replace('<div class="current-forecast-desc">',' ',$ex1[77]);
    $havo1 = str_replace('&#039;','‘',$havo1);
    $havo1=str_replace('</div>',' ',$havo1);
    $havo1 = trim($havo1);
    $tongr=str_replace('<p class="forecast">',' ',$ex1[99]);
    $tongr=str_replace('</p>',' ',$tongr);
    $tongr = trim($tongr);
    $kungr=str_replace('<p class="forecast">',' ',$ex1[104]);
    $kungr=str_replace('</p>',' ',$kungr);
    $kungr = trim($kungr);
    $oqgr=str_replace('<p class="forecast">',' ',$ex1[109]);
    $oqgr=str_replace('</p>',' ',$oqgr);
    $oqgr = trim($oqgr);
    $bugun=str_replace('<div class="current-day">',' ',$ex1[67]);
    $bugun=str_replace('</div>',' ',$bugun);
    $bugun = trim($bugun);
    $qch=str_replace('<p>',' ',$ex1[87]);
    $qch=str_replace('</p>',' ',$qch);
    $qch= trim($qch);
    $qb=str_replace('<p>',' ',$ex1[88]);
    $qb=str_replace('</p>',' ',$qb);
    $qb= trim($qb);
    $sha=str_replace('<p>',' ',$ex1[82]);
    $sha = str_replace('&#039;','‘',$sha);
    $sha=str_replace('</p>',' ',$sha);
    $sha= trim($sha);
    $bosim=str_replace('<p>',' ',$ex1[83]);
    $bosim=str_replace('</p>',' ',$bosim);
    $bosim= trim($bosim);
    $oy=str_replace('<p>',' ',$ex1[86]);
    $oy = str_replace('&#039;','‘',$oy);
    $oy=str_replace('</p>',' ',$oy);
    $oy= trim($oy);
    $nam=str_replace('<p>',' ',$ex1[81]);
    $nam=str_replace('</p>',' ',$nam);
    $nam= trim($nam);
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>"📆 $bugun
🌏 Namangan
⛅ Harorat: $gr1 ... $gr2
☁ Ob-havo: $havo1
🌄 Tong: $tongr
🌅 Kun: $kungr
🌃 Oqshom: $oqgr
💧 $nam
🌝 $qch
🌚 $qb",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($data=="bux"){
    $anb8 = file_get_contents('https://obhavo.uz/bukhara'); $ex1=explode("\n",$anb8);
    $gr1=str_replace('<span><strong>',' ',$ex1[73]);
    $gr1=str_replace('</strong></span>',' ',$gr1);
    $gr1= trim($gr1);
    $gr2=str_replace('<span>',' ',$ex1[74]);  $gr2=str_replace('</span>',' ',$gr2);
    $gr2= trim($gr2);
    $havo1=str_replace('<div class="current-forecast-desc">',' ',$ex1[77]);
    $havo1 = str_replace('&#039;','‘',$havo1);
    $havo1=str_replace('</div>',' ',$havo1);
    $havo1 = trim($havo1);
    $tongr=str_replace('<p class="forecast">',' ',$ex1[99]);
    $tongr=str_replace('</p>',' ',$tongr);
    $tongr = trim($tongr);
    $kungr=str_replace('<p class="forecast">',' ',$ex1[104]);
    $kungr=str_replace('</p>',' ',$kungr);
    $kungr = trim($kungr);
    $oqgr=str_replace('<p class="forecast">',' ',$ex1[109]);
    $oqgr=str_replace('</p>',' ',$oqgr);
    $oqgr = trim($oqgr);
    $bugun=str_replace('<div class="current-day">',' ',$ex1[67]);
    $bugun=str_replace('</div>',' ',$bugun);
    $bugun = trim($bugun);
    $qch=str_replace('<p>',' ',$ex1[87]);
    $qch=str_replace('</p>',' ',$qch);
    $qch= trim($qch);
    $qb=str_replace('<p>',' ',$ex1[88]);
    $qb=str_replace('</p>',' ',$qb);
    $qb= trim($qb);
    $sha=str_replace('<p>',' ',$ex1[82]);
    $sha = str_replace('&#039;','‘',$sha);
    $sha=str_replace('</p>',' ',$sha);
    $sha= trim($sha);
    $bosim=str_replace('<p>',' ',$ex1[83]);
    $bosim=str_replace('</p>',' ',$bosim);
    $bosim= trim($bosim);
    $oy=str_replace('<p>',' ',$ex1[86]);
    $oy = str_replace('&#039;','‘',$oy);
    $oy=str_replace('</p>',' ',$oy);
    $oy= trim($oy);
    $nam=str_replace('<p>',' ',$ex1[81]);
    $nam=str_replace('</p>',' ',$nam);
    $nam= trim($nam);
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>"📆 $bugun
🌏 Buxoro
⛅ Harorat: $gr1 ... $gr2
☁ Ob-havo: $havo1
🌄 Tong: $tongr
🌅 Kun: $kungr
🌃 Oqshom: $oqgr
💧 $nam
🌝 $qch
🌚 $qb",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($data=="gul"){
    $anb8 = file_get_contents('https://obhavo.uz/gulistan'); $ex1=explode("\n",$anb8);
    $gr1=str_replace('<span><strong>',' ',$ex1[73]);
    $gr1=str_replace('</strong></span>',' ',$gr1);
    $gr1= trim($gr1);
    $gr2=str_replace('<span>',' ',$ex1[74]);  $gr2=str_replace('</span>',' ',$gr2);
    $gr2= trim($gr2);
    $havo1=str_replace('<div class="current-forecast-desc">',' ',$ex1[77]);
    $havo1 = str_replace('&#039;','‘',$havo1);
    $havo1=str_replace('</div>',' ',$havo1);
    $havo1 = trim($havo1);
    $tongr=str_replace('<p class="forecast">',' ',$ex1[99]);
    $tongr=str_replace('</p>',' ',$tongr);
    $tongr = trim($tongr);
    $kungr=str_replace('<p class="forecast">',' ',$ex1[104]);
    $kungr=str_replace('</p>',' ',$kungr);
    $kungr = trim($kungr);
    $oqgr=str_replace('<p class="forecast">',' ',$ex1[109]);
    $oqgr=str_replace('</p>',' ',$oqgr);
    $oqgr = trim($oqgr);
    $bugun=str_replace('<div class="current-day">',' ',$ex1[67]);
    $bugun=str_replace('</div>',' ',$bugun);
    $bugun = trim($bugun);
    $qch=str_replace('<p>',' ',$ex1[87]);
    $qch=str_replace('</p>',' ',$qch);
    $qch= trim($qch);
    $qb=str_replace('<p>',' ',$ex1[88]);
    $qb=str_replace('</p>',' ',$qb);
    $qb= trim($qb);
    $sha=str_replace('<p>',' ',$ex1[82]);
    $sha = str_replace('&#039;','‘',$sha);
    $sha=str_replace('</p>',' ',$sha);
    $sha= trim($sha);
    $bosim=str_replace('<p>',' ',$ex1[83]);
    $bosim=str_replace('</p>',' ',$bosim);
    $bosim= trim($bosim);
    $oy=str_replace('<p>',' ',$ex1[86]);
    $oy = str_replace('&#039;','‘',$oy);
    $oy=str_replace('</p>',' ',$oy);
    $oy= trim($oy);
    $nam=str_replace('<p>',' ',$ex1[81]);
    $nam=str_replace('</p>',' ',$nam);
    $nam= trim($nam);
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>"📆 $bugun
🌏 Guliston
⛅ Harorat: $gr1 ... $gr2
☁ Ob-havo: $havo1
🌄 Tong: $tongr
🌅 Kun: $kungr
🌃 Oqshom: $oqgr
💧 $nam
🌝 $qch
🌚 $qb",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($data=="jiz"){
    $anb8 = file_get_contents('https://obhavo.uz/jizzakh'); $ex1=explode("\n",$anb8);
    $gr1=str_replace('<span><strong>',' ',$ex1[73]);
    $gr1=str_replace('</strong></span>',' ',$gr1);
    $gr1= trim($gr1);
    $gr2=str_replace('<span>',' ',$ex1[74]);  $gr2=str_replace('</span>',' ',$gr2);
    $gr2= trim($gr2);
    $havo1=str_replace('<div class="current-forecast-desc">',' ',$ex1[77]);
    $havo1 = str_replace('&#039;','‘',$havo1);
    $havo1=str_replace('</div>',' ',$havo1);
    $havo1 = trim($havo1);
    $tongr=str_replace('<p class="forecast">',' ',$ex1[99]);
    $tongr=str_replace('</p>',' ',$tongr);
    $tongr = trim($tongr);
    $kungr=str_replace('<p class="forecast">',' ',$ex1[104]);
    $kungr=str_replace('</p>',' ',$kungr);
    $kungr = trim($kungr);
    $oqgr=str_replace('<p class="forecast">',' ',$ex1[109]);
    $oqgr=str_replace('</p>',' ',$oqgr);
    $oqgr = trim($oqgr);
    $bugun=str_replace('<div class="current-day">',' ',$ex1[67]);
    $bugun=str_replace('</div>',' ',$bugun);
    $bugun = trim($bugun);
    $qch=str_replace('<p>',' ',$ex1[87]);
    $qch=str_replace('</p>',' ',$qch);
    $qch= trim($qch);
    $qb=str_replace('<p>',' ',$ex1[88]);
    $qb=str_replace('</p>',' ',$qb);
    $qb= trim($qb);
    $sha=str_replace('<p>',' ',$ex1[82]);
    $sha = str_replace('&#039;','‘',$sha);
    $sha=str_replace('</p>',' ',$sha);
    $sha= trim($sha);
    $bosim=str_replace('<p>',' ',$ex1[83]);
    $bosim=str_replace('</p>',' ',$bosim);
    $bosim= trim($bosim);
    $oy=str_replace('<p>',' ',$ex1[86]);
    $oy = str_replace('&#039;','‘',$oy);
    $oy=str_replace('</p>',' ',$oy);
    $oy= trim($oy);
    $nam=str_replace('<p>',' ',$ex1[81]);
    $nam=str_replace('</p>',' ',$nam);
    $nam= trim($nam);
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>"📆 $bugun
🌏 Jizzah
⛅ Harorat: $gr1 ... $gr2
☁ Ob-havo: $havo1
🌄 Tong: $tongr
🌅 Kun: $kungr
🌃 Oqshom: $oqgr
💧 $nam
🌝 $qch
🌚 $qb",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($data=="zar"){
    $anb8 = file_get_contents('https://obhavo.uz/zarafshan'); $ex1=explode("\n",$anb8);
    $gr1=str_replace('<span><strong>',' ',$ex1[73]);
    $gr1=str_replace('</strong></span>',' ',$gr1);
    $gr1= trim($gr1);
    $gr2=str_replace('<span>',' ',$ex1[74]);  $gr2=str_replace('</span>',' ',$gr2);
    $gr2= trim($gr2);
    $havo1=str_replace('<div class="current-forecast-desc">',' ',$ex1[77]);
    $havo1 = str_replace('&#039;','‘',$havo1);
    $havo1=str_replace('</div>',' ',$havo1);
    $havo1 = trim($havo1);
    $tongr=str_replace('<p class="forecast">',' ',$ex1[99]);
    $tongr=str_replace('</p>',' ',$tongr);
    $tongr = trim($tongr);
    $kungr=str_replace('<p class="forecast">',' ',$ex1[104]);
    $kungr=str_replace('</p>',' ',$kungr);
    $kungr = trim($kungr);
    $oqgr=str_replace('<p class="forecast">',' ',$ex1[109]);
    $oqgr=str_replace('</p>',' ',$oqgr);
    $oqgr = trim($oqgr);
    $bugun=str_replace('<div class="current-day">',' ',$ex1[67]);
    $bugun=str_replace('</div>',' ',$bugun);
    $bugun = trim($bugun);
    $qch=str_replace('<p>',' ',$ex1[87]);
    $qch=str_replace('</p>',' ',$qch);
    $qch= trim($qch);
    $qb=str_replace('<p>',' ',$ex1[88]);
    $qb=str_replace('</p>',' ',$qb);
    $qb= trim($qb);
    $sha=str_replace('<p>',' ',$ex1[82]);
    $sha = str_replace('&#039;','‘',$sha);
    $sha=str_replace('</p>',' ',$sha);
    $sha= trim($sha);
    $bosim=str_replace('<p>',' ',$ex1[83]);
    $bosim=str_replace('</p>',' ',$bosim);
    $bosim= trim($bosim);
    $oy=str_replace('<p>',' ',$ex1[86]);
    $oy = str_replace('&#039;','‘',$oy);
    $oy=str_replace('</p>',' ',$oy);
    $oy= trim($oy);
    $nam=str_replace('<p>',' ',$ex1[81]);
    $nam=str_replace('</p>',' ',$nam);
    $nam= trim($nam);
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>"📆 $bugun
🌏 Zarafshon
⛅ Harorat: $gr1 ... $gr2
☁ Ob-havo: $havo1
🌄 Tong: $tongr
🌅 Kun: $kungr
🌃 Oqshom: $oqgr
💧 $nam
🌝 $qch
🌚 $qb",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($data=="qar"){
    $anb8 = file_get_contents('https://obhavo.uz/karshi'); $ex1=explode("\n",$anb8);
    $gr1=str_replace('<span><strong>',' ',$ex1[73]);
    $gr1=str_replace('</strong></span>',' ',$gr1);
    $gr1= trim($gr1);
    $gr2=str_replace('<span>',' ',$ex1[74]);  $gr2=str_replace('</span>',' ',$gr2);
    $gr2= trim($gr2);
    $havo1=str_replace('<div class="current-forecast-desc">',' ',$ex1[77]);
    $havo1 = str_replace('&#039;','‘',$havo1);
    $havo1=str_replace('</div>',' ',$havo1);
    $havo1 = trim($havo1);
    $tongr=str_replace('<p class="forecast">',' ',$ex1[99]);
    $tongr=str_replace('</p>',' ',$tongr);
    $tongr = trim($tongr);
    $kungr=str_replace('<p class="forecast">',' ',$ex1[104]);
    $kungr=str_replace('</p>',' ',$kungr);
    $kungr = trim($kungr);
    $oqgr=str_replace('<p class="forecast">',' ',$ex1[109]);
    $oqgr=str_replace('</p>',' ',$oqgr);
    $oqgr = trim($oqgr);
    $bugun=str_replace('<div class="current-day">',' ',$ex1[67]);
    $bugun=str_replace('</div>',' ',$bugun);
    $bugun = trim($bugun);
    $qch=str_replace('<p>',' ',$ex1[87]);
    $qch=str_replace('</p>',' ',$qch);
    $qch= trim($qch);
    $qb=str_replace('<p>',' ',$ex1[88]);
    $qb=str_replace('</p>',' ',$qb);
    $qb= trim($qb);
    $sha=str_replace('<p>',' ',$ex1[82]);
    $sha = str_replace('&#039;','‘',$sha);
    $sha=str_replace('</p>',' ',$sha);
    $sha= trim($sha);
    $bosim=str_replace('<p>',' ',$ex1[83]);
    $bosim=str_replace('</p>',' ',$bosim);
    $bosim= trim($bosim);
    $oy=str_replace('<p>',' ',$ex1[86]);
    $oy = str_replace('&#039;','‘',$oy);
    $oy=str_replace('</p>',' ',$oy);
    $oy= trim($oy);
    $nam=str_replace('<p>',' ',$ex1[81]);
    $nam=str_replace('</p>',' ',$nam);
    $nam= trim($nam);
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>"📆 $bugun
🌏 Qarshi
⛅ Harorat: $gr1 ... $gr2
☁ Ob-havo: $havo1
🌄 Tong: $tongr
🌅 Kun: $kungr
🌃 Oqshom: $oqgr
💧 $nam
🌝 $qch
🌚 $qb",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($data=="nav"){
    $anb8 = file_get_contents('https://obhavo.uz/navoi'); $ex1=explode("\n",$anb8);
    $gr1=str_replace('<span><strong>',' ',$ex1[73]);
    $gr1=str_replace('</strong></span>',' ',$gr1);
    $gr1= trim($gr1);
    $gr2=str_replace('<span>',' ',$ex1[74]);  $gr2=str_replace('</span>',' ',$gr2);
    $gr2= trim($gr2);
    $havo1=str_replace('<div class="current-forecast-desc">',' ',$ex1[77]);
    $havo1 = str_replace('&#039;','‘',$havo1);
    $havo1=str_replace('</div>',' ',$havo1);
    $havo1 = trim($havo1);
    $tongr=str_replace('<p class="forecast">',' ',$ex1[99]);
    $tongr=str_replace('</p>',' ',$tongr);
    $tongr = trim($tongr);
    $kungr=str_replace('<p class="forecast">',' ',$ex1[104]);
    $kungr=str_replace('</p>',' ',$kungr);
    $kungr = trim($kungr);
    $oqgr=str_replace('<p class="forecast">',' ',$ex1[109]);
    $oqgr=str_replace('</p>',' ',$oqgr);
    $oqgr = trim($oqgr);
    $bugun=str_replace('<div class="current-day">',' ',$ex1[67]);
    $bugun=str_replace('</div>',' ',$bugun);
    $bugun = trim($bugun);
    $qch=str_replace('<p>',' ',$ex1[87]);
    $qch=str_replace('</p>',' ',$qch);
    $qch= trim($qch);
    $qb=str_replace('<p>',' ',$ex1[88]);
    $qb=str_replace('</p>',' ',$qb);
    $qb= trim($qb);
    $sha=str_replace('<p>',' ',$ex1[82]);
    $sha = str_replace('&#039;','‘',$sha);
    $sha=str_replace('</p>',' ',$sha);
    $sha= trim($sha);
    $bosim=str_replace('<p>',' ',$ex1[83]);
    $bosim=str_replace('</p>',' ',$bosim);
    $bosim= trim($bosim);
    $oy=str_replace('<p>',' ',$ex1[86]);
    $oy = str_replace('&#039;','‘',$oy);
    $oy=str_replace('</p>',' ',$oy);
    $oy= trim($oy);
    $nam=str_replace('<p>',' ',$ex1[81]);
    $nam=str_replace('</p>',' ',$nam);
    $nam= trim($nam);
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>"📆 $bugun
🌏 Navoiy
⛅ Harorat: $gr1 ... $gr2
☁ Ob-havo: $havo1
🌄 Tong: $tongr
🌅 Kun: $kungr
🌃 Oqshom: $oqgr
💧 $nam
🌝 $qch
🌚 $qb",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($data=="nuk"){
    $anb8 = file_get_contents('https://obhavo.uz/nukus'); $ex1=explode("\n",$anb8);
    $gr1=str_replace('<span><strong>',' ',$ex1[73]);
    $gr1=str_replace('</strong></span>',' ',$gr1);
    $gr1= trim($gr1);
    $gr2=str_replace('<span>',' ',$ex1[74]);  $gr2=str_replace('</span>',' ',$gr2);
    $gr2= trim($gr2);
    $havo1=str_replace('<div class="current-forecast-desc">',' ',$ex1[77]);
    $havo1 = str_replace('&#039;','‘',$havo1);
    $havo1=str_replace('</div>',' ',$havo1);
    $havo1 = trim($havo1);
    $tongr=str_replace('<p class="forecast">',' ',$ex1[99]);
    $tongr=str_replace('</p>',' ',$tongr);
    $tongr = trim($tongr);
    $kungr=str_replace('<p class="forecast">',' ',$ex1[104]);
    $kungr=str_replace('</p>',' ',$kungr);
    $kungr = trim($kungr);
    $oqgr=str_replace('<p class="forecast">',' ',$ex1[109]);
    $oqgr=str_replace('</p>',' ',$oqgr);
    $oqgr = trim($oqgr);
    $bugun=str_replace('<div class="current-day">',' ',$ex1[67]);
    $bugun=str_replace('</div>',' ',$bugun);
    $bugun = trim($bugun);
    $qch=str_replace('<p>',' ',$ex1[87]);
    $qch=str_replace('</p>',' ',$qch);
    $qch= trim($qch);
    $qb=str_replace('<p>',' ',$ex1[88]);
    $qb=str_replace('</p>',' ',$qb);
    $qb= trim($qb);
    $sha=str_replace('<p>',' ',$ex1[82]);
    $sha = str_replace('&#039;','‘',$sha);
    $sha=str_replace('</p>',' ',$sha);
    $sha= trim($sha);
    $bosim=str_replace('<p>',' ',$ex1[83]);
    $bosim=str_replace('</p>',' ',$bosim);
    $bosim= trim($bosim);
    $oy=str_replace('<p>',' ',$ex1[86]);
    $oy = str_replace('&#039;','‘',$oy);
    $oy=str_replace('</p>',' ',$oy);
    $oy= trim($oy);
    $nam=str_replace('<p>',' ',$ex1[81]);
    $nam=str_replace('</p>',' ',$nam);
    $nam= trim($nam);
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>"📆 $bugun
🌏 Nukus
⛅ Harorat: $gr1 ... $gr2
☁ Ob-havo: $havo1
🌄 Tong: $tongr
🌅 Kun: $kungr
🌃 Oqshom: $oqgr
💧 $nam
🌝 $qch
🌚 $qb",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($data=="ter"){
    $anb8 = file_get_contents('https://obhavo.uz/termez'); $ex1=explode("\n",$anb8);
    $gr1=str_replace('<span><strong>',' ',$ex1[73]);
    $gr1=str_replace('</strong></span>',' ',$gr1);
    $gr1= trim($gr1);
    $gr2=str_replace('<span>',' ',$ex1[74]);  $gr2=str_replace('</span>',' ',$gr2);
    $gr2= trim($gr2);
    $havo1=str_replace('<div class="current-forecast-desc">',' ',$ex1[77]);
    $havo1 = str_replace('&#039;','‘',$havo1);
    $havo1=str_replace('</div>',' ',$havo1);
    $havo1 = trim($havo1);
    $tongr=str_replace('<p class="forecast">',' ',$ex1[99]);
    $tongr=str_replace('</p>',' ',$tongr);
    $tongr = trim($tongr);
    $kungr=str_replace('<p class="forecast">',' ',$ex1[104]);
    $kungr=str_replace('</p>',' ',$kungr);
    $kungr = trim($kungr);
    $oqgr=str_replace('<p class="forecast">',' ',$ex1[109]);
    $oqgr=str_replace('</p>',' ',$oqgr);
    $oqgr = trim($oqgr);
    $bugun=str_replace('<div class="current-day">',' ',$ex1[67]);
    $bugun=str_replace('</div>',' ',$bugun);
    $bugun = trim($bugun);
    $qch=str_replace('<p>',' ',$ex1[87]);
    $qch=str_replace('</p>',' ',$qch);
    $qch= trim($qch);
    $qb=str_replace('<p>',' ',$ex1[88]);
    $qb=str_replace('</p>',' ',$qb);
    $qb= trim($qb);
    $sha=str_replace('<p>',' ',$ex1[82]);
    $sha = str_replace('&#039;','‘',$sha);
    $sha=str_replace('</p>',' ',$sha);
    $sha= trim($sha);
    $bosim=str_replace('<p>',' ',$ex1[83]);
    $bosim=str_replace('</p>',' ',$bosim);
    $bosim= trim($bosim);
    $oy=str_replace('<p>',' ',$ex1[86]);
    $oy = str_replace('&#039;','‘',$oy);
    $oy=str_replace('</p>',' ',$oy);
    $oy= trim($oy);
    $nam=str_replace('<p>',' ',$ex1[81]);
    $nam=str_replace('</p>',' ',$nam);
    $nam= trim($nam);
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>"📆 $bugun
🌏 Termiz
⛅ Harorat: $gr1 ... $gr2
☁ Ob-havo: $havo1
🌄 Tong: $tongr
🌅 Kun: $kungr
🌃 Oqshom: $oqgr
💧 $nam
🌝 $qch
🌚 $qb",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($data=="urg"){
    $anb8 = file_get_contents('https://obhavo.uz/urgench'); $ex1=explode("\n",$anb8);
    $gr1=str_replace('<span><strong>',' ',$ex1[73]);
    $gr1=str_replace('</strong></span>',' ',$gr1);
    $gr1= trim($gr1);
    $gr2=str_replace('<span>',' ',$ex1[74]);  $gr2=str_replace('</span>',' ',$gr2);
    $gr2= trim($gr2);
    $havo1=str_replace('<div class="current-forecast-desc">',' ',$ex1[77]);
    $havo1 = str_replace('&#039;','‘',$havo1);
    $havo1=str_replace('</div>',' ',$havo1);
    $havo1 = trim($havo1);
    $tongr=str_replace('<p class="forecast">',' ',$ex1[99]);
    $tongr=str_replace('</p>',' ',$tongr);
    $tongr = trim($tongr);
    $kungr=str_replace('<p class="forecast">',' ',$ex1[104]);
    $kungr=str_replace('</p>',' ',$kungr);
    $kungr = trim($kungr);
    $oqgr=str_replace('<p class="forecast">',' ',$ex1[109]);
    $oqgr=str_replace('</p>',' ',$oqgr);
    $oqgr = trim($oqgr);
    $bugun=str_replace('<div class="current-day">',' ',$ex1[67]);
    $bugun=str_replace('</div>',' ',$bugun);
    $bugun = trim($bugun);
    $qch=str_replace('<p>',' ',$ex1[87]);
    $qch=str_replace('</p>',' ',$qch);
    $qch= trim($qch);
    $qb=str_replace('<p>',' ',$ex1[88]);
    $qb=str_replace('</p>',' ',$qb);
    $qb= trim($qb);
    $sha=str_replace('<p>',' ',$ex1[82]);
    $sha = str_replace('&#039;','‘',$sha);
    $sha=str_replace('</p>',' ',$sha);
    $sha= trim($sha);
    $bosim=str_replace('<p>',' ',$ex1[83]);
    $bosim=str_replace('</p>',' ',$bosim);
    $bosim= trim($bosim);
    $oy=str_replace('<p>',' ',$ex1[86]);
    $oy = str_replace('&#039;','‘',$oy);
    $oy=str_replace('</p>',' ',$oy);
    $oy= trim($oy);
    $nam=str_replace('<p>',' ',$ex1[81]);
    $nam=str_replace('</p>',' ',$nam);
    $nam= trim($nam);
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>"📆 $bugun
🌏 Urganch
⛅ Harorat: $gr1 ... $gr2
☁ Ob-havo: $havo1
🌄 Tong: $tongr
🌅 Kun: $kungr
🌃 Oqshom: $oqgr
💧 $nam
🌝 $qch
🌚 $qb",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($data=="xiv"){
    $anb8 = file_get_contents('https://obhavo.uz/khiva'); $ex1=explode("\n",$anb8);
    $gr1=str_replace('<span><strong>',' ',$ex1[73]);
    $gr1=str_replace('</strong></span>',' ',$gr1);
    $gr1= trim($gr1);
    $gr2=str_replace('<span>',' ',$ex1[74]);  $gr2=str_replace('</span>',' ',$gr2);
    $gr2= trim($gr2);
    $havo1=str_replace('<div class="current-forecast-desc">',' ',$ex1[77]);
    $havo1 = str_replace('&#039;','‘',$havo1);
    $havo1=str_replace('</div>',' ',$havo1);
    $havo1 = trim($havo1);
    $tongr=str_replace('<p class="forecast">',' ',$ex1[99]);
    $tongr=str_replace('</p>',' ',$tongr);
    $tongr = trim($tongr);
    $kungr=str_replace('<p class="forecast">',' ',$ex1[104]);
    $kungr=str_replace('</p>',' ',$kungr);
    $kungr = trim($kungr);
    $oqgr=str_replace('<p class="forecast">',' ',$ex1[109]);
    $oqgr=str_replace('</p>',' ',$oqgr);
    $oqgr = trim($oqgr);
    $bugun=str_replace('<div class="current-day">',' ',$ex1[67]);
    $bugun=str_replace('</div>',' ',$bugun);
    $bugun = trim($bugun);
    $qch=str_replace('<p>',' ',$ex1[87]);
    $qch=str_replace('</p>',' ',$qch);
    $qch= trim($qch);
    $qb=str_replace('<p>',' ',$ex1[88]);
    $qb=str_replace('</p>',' ',$qb);
    $qb= trim($qb);
    $sha=str_replace('<p>',' ',$ex1[82]);
    $sha = str_replace('&#039;','‘',$sha);
    $sha=str_replace('</p>',' ',$sha);
    $sha= trim($sha);
    $bosim=str_replace('<p>',' ',$ex1[83]);
    $bosim=str_replace('</p>',' ',$bosim);
    $bosim= trim($bosim);
    $oy=str_replace('<p>',' ',$ex1[86]);
    $oy = str_replace('&#039;','‘',$oy);
    $oy=str_replace('</p>',' ',$oy);
    $oy= trim($oy);
    $nam=str_replace('<p>',' ',$ex1[81]);
    $nam=str_replace('</p>',' ',$nam);
    $nam= trim($nam);
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>"📆 $bugun
🌏 Xiva
⛅ Harorat: $gr1 ... $gr2
☁ Ob-havo: $havo1
🌄 Tong: $tongr
🌅 Kun: $kungr
🌃 Oqshom: $oqgr
💧 $nam
🌝 $qch
🌚 $qb",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($data=="sam"){
    $anb8 = file_get_contents('https://obhavo.uz/samarkand'); $ex1=explode("\n",$anb8);
    $gr1=str_replace('<span><strong>',' ',$ex1[73]);
    $gr1=str_replace('</strong></span>',' ',$gr1);
    $gr1= trim($gr1);
    $gr2=str_replace('<span>',' ',$ex1[74]);  $gr2=str_replace('</span>',' ',$gr2);
    $gr2= trim($gr2);
    $havo1=str_replace('<div class="current-forecast-desc">',' ',$ex1[77]);
    $havo1 = str_replace('&#039;','‘',$havo1);
    $havo1=str_replace('</div>',' ',$havo1);
    $havo1 = trim($havo1);
    $tongr=str_replace('<p class="forecast">',' ',$ex1[99]);
    $tongr=str_replace('</p>',' ',$tongr);
    $tongr = trim($tongr);
    $kungr=str_replace('<p class="forecast">',' ',$ex1[104]);
    $kungr=str_replace('</p>',' ',$kungr);
    $kungr = trim($kungr);
    $oqgr=str_replace('<p class="forecast">',' ',$ex1[109]);
    $oqgr=str_replace('</p>',' ',$oqgr);
    $oqgr = trim($oqgr);
    $bugun=str_replace('<div class="current-day">',' ',$ex1[67]);
    $bugun=str_replace('</div>',' ',$bugun);
    $bugun = trim($bugun);
    $qch=str_replace('<p>',' ',$ex1[87]);
    $qch=str_replace('</p>',' ',$qch);
    $qch= trim($qch);
    $qb=str_replace('<p>',' ',$ex1[88]);
    $qb=str_replace('</p>',' ',$qb);
    $qb= trim($qb);
    $sha=str_replace('<p>',' ',$ex1[82]);
    $sha = str_replace('&#039;','‘',$sha);
    $sha=str_replace('</p>',' ',$sha);
    $sha= trim($sha);
    $bosim=str_replace('<p>',' ',$ex1[83]);
    $bosim=str_replace('</p>',' ',$bosim);
    $bosim= trim($bosim);
    $oy=str_replace('<p>',' ',$ex1[86]);
    $oy = str_replace('&#039;','‘',$oy);
    $oy=str_replace('</p>',' ',$oy);
    $oy= trim($oy);
    $nam=str_replace('<p>',' ',$ex1[81]);
    $nam=str_replace('</p>',' ',$nam);
    $nam= trim($nam);
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>"📆 $bugun
🌏 Samarqand
⛅ Harorat: $gr1 ... $gr2
☁ Ob-havo: $havo1
🌄 Tong: $tongr
🌅 Kun: $kungr
🌃 Oqshom: $oqgr
💧 $nam
🌝 $qch
🌚 $qb",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($text=="/yangilik" or $text=="/yangilik$botim"){
    $text = "Bugungi eng so'ngi yangilik bilan tanishing, <b>🆕 YANGILIKLAR 🆕</b> tugmasini bosing!";
    bot('sendmessage',[
        'chat_id'=>$cid,
        'user_id'=>$uid,
        'reply_to_message_id'=>$mid,
        'text'=>$text,
        'parse_mode'=>'html',
        'disable_web_page_preview'=>true,
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [['text'=>"🆕 YANGILIKLAR 🆕",'callback_data'=>"yangilik"]],
            ]
        ])
    ]);
}
$url = 'https://daryo.uz/feed/';
$rss = simplexml_load_file($url);
foreach ($rss->channel->item as $item){
    $line = $item->title;
    break;
}
if($data=="yangilik"){
    $soat = date('H:i', strtotime('5 hour'));
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>"📰 $line

⏰ Soat: $soat",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($text=="/kurs" or $text=="/kurs$botim"){
    $text = "Bugungi valyuta kursini bilish uchun <b>💱 VALYUTA KURSI 💱</b> tugmasini bosing!";
    bot('sendmessage',[
        'chat_id'=>$cid,
        'user_id'=>$uid,
        'reply_to_message_id'=>$mid,
        'text'=>$text,
        'parse_mode'=>'html',
        'disable_web_page_preview'=>true,
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [['text'=>"💱 VALYUTA KURSI 💱",'callback_data'=>"kurs"]],
            ]
        ])
    ]);
}
if($data == 'kurs'){
    $soat = date('H:i', strtotime('5 hour'));
    bot('answerCallbackQuery',[
        'callback_query_id'=>$cqid,
        'chat_id'=>$ccid,
        'text'=>kurs()."
⏰ Soat: $soat",
        'show_alert'=>true,
        'parse_mode'=>'html',
    ]);
}
if($text=="/inline" or $text=="/inline$botim"){
    $text = "Bot inline rejimda <b>Ob-havo , Valyuta kursi, Eng so'ngi yangilik</b>lar bilan sizni kanal yoki guruhlarda tanishtira oladi! Pastdagi tugmalardan kerakligini tanlang 👇";
    bot('sendmessage',[
        'chat_id'=>$cid,
        'user_id'=>$uid,
        'reply_to_message_id'=>$mid,
        'text'=>$text,
        'parse_mode'=>'html',
        'disable_web_page_preview'=>true,
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [['text'=>"⛅ OB-HAVO ⛅",'switch_inline_query'=>"obhavo"]],
                [['text'=>"💱 VALYUTA KURSI 💱",'switch_inline_query'=>"kurs"]],
                [['text'=>"🆕 YANGILIKLAR 🆕",'switch_inline_query'=>"yangilik"]],
            ]
        ])
    ]);
}
//inline

$iuid = $update->inline_query->from->id;
$iid = $update->inline_query->id;
$icid = $update->inline_query->chat->id;
$imid = $update->inline_query->message->id;
$bot = '@'.bot('getme',['bot'])->result->username;
$query = $update->inline_query->query;

if(mb_stripos($query,"obhavo")!==false){
    bot('answerInlineQuery',[
        'inline_query_id'=>$iid,
        'cache_time'=>1,
        'results'=>json_encode([[
            'type'=>'article',
            'thumb_url'=>"https://izzatbek8252.000webhostapp.com/polo/obhavo.jpg",
            'id'=>base64_encode(1),
            'title'=>"⛅ OB-HAVO ma'lumoti ⛅",
            'input_message_content'=>[
                'disable_web_page_preview'=>true,
                'parse_mode'=>'html',
                'message_text'=>"Bugungi <b>OB - HAVO</b> ma'lumoti bilan tanishish uchun o'zingiz yashab turgan manzilni tanlang!",
            ],
            'reply_markup'=>[
                'inline_keyboard'=>[
                    [['text'=>"⛅ Farg'ona",'callback_data'=>"far"],
                        ['text'=>"⛅ Xiva",'callback_data'=>"xiv"]],
                    [['text'=>"⛅ Andijon",'callback_data'=>"and"],
                        ['text'=>"⛅ Namangan",'callback_data'=>"nam"]],
                    [['text'=>"⛅ Buxoro",'callback_data'=>"bux"],
                        ['text'=>"⛅ Guliston",'callback_data'=>"gul"]],
                    [['text'=>"⛅ Jizzax",'callback_data'=>"jiz"],
                        ['text'=>"⛅ Zarafshon",'callback_data'=>"zar"]],
                    [['text'=>"⛅ Qarshi",'callback_data'=>"qar"],
                        ['text'=>"⛅ Navoiy",'callback_data'=>"nav"]],
                    [['text'=>"⛅ Nukus",'callback_data'=>"nuk"],
                        ['text'=>"⛅ Samarqand",'callback_data'=>"sam"]],
                    [['text'=>"⛅ Termiz",'callback_data'=>"ter"],
                        ['text'=>"⛅ Urganch",'callback_data'=>"urg"]],
                    [['text'=>"⛅ Toshkent",'callback_data'=>"tosh"]],
                ]],
        ]
        ])
    ]);
}
if(mb_stripos($query,"kurs")!==false){
    bot('answerInlineQuery',[
        'inline_query_id'=>$iid,
        'cache_time'=>1,
        'results'=>json_encode([[
            'type'=>'article',
            'thumb_url'=>"https://izzatbek8252.000webhostapp.com/polo/kurs.jpg",
            'id'=>base64_encode(1),
            'title'=>"💱 VALYUTA KURSI 💱",
            'input_message_content'=>[
                'disable_web_page_preview'=>true,
                'parse_mode'=>'html',
                'message_text'=>"Bugungi valyuta kursini bilish uchun <b>💱 VALYUTA KURSI 💱</b> tugmasini bosing!",
            ],
            'reply_markup'=>[
                'inline_keyboard'=>[
                    [['text'=>"💱 VALYUTA KURSI 💱",'callback_data'=>"kurs"]],
                ]],
        ]
        ])
    ]);
}
if(mb_stripos($query,"yangilik")!==false){
    bot('answerInlineQuery',[
        'inline_query_id'=>$iid,
        'cache_time'=>1,
        'results'=>json_encode([[
            'type'=>'article',
            'thumb_url'=>"https://izzatbek8252.000webhostapp.com/polo/yangilik.jpg",
            'id'=>base64_encode(1),
            'title'=>"🆕 YANGILIKLAR 🆕",
            'input_message_content'=>[
                'disable_web_page_preview'=>true,
                'parse_mode'=>'html',
                'message_text'=>"Bugungi eng so'ngi yangilik bilan tanishing, <b>🆕 YANGILIKLAR 🆕</b> tugmasini bosing!",
            ],
            'reply_markup'=>[
                'inline_keyboard'=>[
                    [['text'=>"🆕 YANGILIKLAR 🆕",'callback_data'=>"yangilik"]],
                ]],
        ]
        ])
    ]);
}

//developer
if($text=="!admin"){
    if($uid==$admin){
        bot('sendmessage',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'text'=>"D",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"De",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Dev",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Deve",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Devel",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Develo",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Develop",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Develope",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Developer",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Developer:",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Developer: I",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Developer: Iz",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Developer: Izz",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Developer: Izza",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Developer: Izzat",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Developer: Izzatb",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Developer: Izzatbe",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Developer: Izzatbek",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"Developer: Izzatbek!",
            'parse_mode'=>'html',
        ]);
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"THE END",
            'parse_mode'=>'html',
        ]);
    }else{
        bot('sendmessage',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'reply_to_message_id'=>$mid,
            'text'=>"Siz bu buyruqdan foydalana olmaysiz!",
            'parse_mode'=>'html',
        ]);
    }
}
if(mb_stripos($text,"/ism") !== false){
    $ex=explode(" ",$text);
    $ism = file_get_contents("https://ismlar.com/search/$ex[1]");
    $exp = explode('<p class="text-size-5">',$ism);
    $expl = explode('<div class="col-12 col-md-4 text-md-right">',$exp[1]);
    $im = str_replace($expl[1],' ',$exp[1]);
    $ims = str_replace('</p>',' ',$im);
    $isms = str_replace('</div>',' ',$ims);
    $ismn = str_replace('<div class="col-12 col-md-4 text-md-right">',' ',$isms);
    $ismk = str_replace('&#039;','`',$ismn);
    $ismm = trim("$ismk");
    bot('sendmessage',[
        'chat_id'=>$cid,
        'user_id'=>$uid,
        'reply_to_message_id'=>$mid,
        'text'=>"<b>📚 ISMLAR MA'NOSI 📚

🔍 $ex[1]

📑 Manosi:</b> <i>$ismm</i>!",
        'parse_mode'=>'html',
    ]);
}

if($type=="private"){
    if(mb_stripos($text,"/musiqa") !== false){
        $ex=explode(" ",$text);
        $v = file_get_contents("https://xits.pro/search/$ex[1]");
        $vk = 'Master'.$v.'
<div class="mp3">
                <i class="fa fa-play-circle-o"></i>                <a"/musiqa/6312_Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos.html">Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos</a>             </div>
<div class="mp3">
                <i class="fa fa-play-circle-o"></i>                <a"/musiqa/6312_Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos.html">Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos</a>             </div>
<div class="mp3">
                <i class="fa fa-play-circle-o"></i>                <a"/musiqa/6312_Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos.html">Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos</a>             </div>
<div class="mp3">
                <i class="fa fa-play-circle-o"></i>                <a"/musiqa/6312_Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos.html">Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos</a>             </div>
<div class="mp3">
                <i class="fa fa-play-circle-o"></i>                <a"/musiqa/6312_Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos.html">Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos</a>             </div>
<div class="mp3">
                <i class="fa fa-play-circle-o"></i>                <a"/musiqa/6312_Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos.html">Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos</a>             </div>
<div class="mp3">
                <i class="fa fa-play-circle-o"></i>                <a"/musiqa/6312_Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos.html">Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos</a>             </div>
<div class="mp3"> <i class="fa fa-play-circle-o"></i> <a"/musiqa/6312_Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos.html">Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos</a> </div>
<div class="mp3"> <i class="fa fa-play-circle-o"></i> <a"/musiqa/6312_Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos.html">Ummon guruhi - So&#039;ngi Bor So&#039;ngi Iltimos</a> </div>';
        file_put_contents("$uid.get.txt",$vk);
        $zb = file_get_contents("$uid.get.txt");
        $ex1 = explode("fa fa-play-circle-o",$zb);
        $ex12 = explode("</div>",$ex1[1]);
        $ex22 = explode("</div>",$ex1[2]);
        $ex32 = explode("</div>",$ex1[3]);
        $ex42 = explode("</div>",$ex1[4]);
        $ex52 = explode("</div>",$ex1[5]);
        $ex62 = explode("</div>",$ex1[6]);
        $ex72 = explode("</div>",$ex1[7]);
        $ex82 = explode("</div>",$ex1[8]);
        $ex92 = explode("</div>",$ex1[9]);
        $ex102 = explode("</div>",$ex1[10]);
        if(mb_stripos($ex12[0],"<a href") !== false){
            $t = str_replace('"></i>',' ',$ex12[0]);
            $t = str_replace('&#039;','`',$t);
            $m1 = trim($t);
            $m1 = "
🎶 <b>Qidiruv natijalari orasidan kerakligini tanlang:</b>

/1 . $m1";
        }else{
            $m1 = "Siz qidirayotgan musiqani topib bo'lmadi 😔";
        }
        if(mb_stripos($ex22[0],"<a href") !== false){
            $t = str_replace('"></i>',' ',$ex22[0]);
            $t = str_replace('&#039;','`',$t);
            $m2 = trim($t);
            $m2 = "/2 . $m2";
        }else{
            $m2 = "";
        }
        if(mb_stripos($ex32[0],"<a href") !== false){
            $t = str_replace('"></i>',' ',$ex32[0]);
            $t = str_replace('&#039;','`',$t);
            $m3 = trim($t);
            $m3 = "/3 . $m3";
        }else{
            $m3 = "";
        }
        if(mb_stripos($ex42[0],"<a href") !== false){
            $t = str_replace('"></i>',' ',$ex42[0]);
            $t = str_replace('&#039;','`',$t);
            $m4 = trim($t);
            $m4 = "/4 . $m4";
        }else{
            $m4 = "";
        }
        if(mb_stripos($ex52[0],"<a href") !== false){
            $t = str_replace('"></i>',' ',$ex52[0]);
            $t = str_replace('&#039;','`',$t);
            $m5 = trim($t);
            $m5 = "/5 . $m5";
        }else{
            $m5 = "";
        }
        if(mb_stripos($ex62[0],"<a href") !== false){
            $t = str_replace('"></i>',' ',$ex62[0]);
            $t = str_replace('&#039;','`',$t);
            $m6 = trim($t);
            $m6 = "/6 . $m6";
        }else{
            $m6 = "";
        }
        if(mb_stripos($ex72[0],"<a href") !== false){
            $t = str_replace('"></i>',' ',$ex72[0]);
            $t = str_replace('&#039;','`',$t);
            $m7 = trim($t);
            $m7 = "/7 . $m7";
        }else{
            $m7 = "";
        }
        if(mb_stripos($ex82[0],"<a href") !== false){
            $t = str_replace('"></i>',' ',$ex82[0]);
            $t = str_replace('&#039;','`',$t);
            $m8 = trim($t);
            $m8 = "/8 . $m8";
        }else{
            $m8 = "";
        }
        if(mb_stripos($ex92[0],"<a href") !== false){
            $t = str_replace('"></i>',' ',$ex92[0]);
            $t = str_replace('&#039;','`',$t);
            $m9 = trim($t);
            $m9 = "/9 . $m9";
        }else{
            $m9 = "";
        }
        if(mb_stripos($ex102[0],"<a href") !== false){
            $t = str_replace('"></i>',' ',$ex102[0]);
            $t = str_replace('&#039;','`',$t);
            $m10 = trim($t);
            $m10 = "/10 . $m10";
        }else{
            $m10 = "";
        }
        file_put_contents("$uid.txt","$m1
$m2
$m3
$m4
$m5
$m6
$m7
$m8
$m9
$m10");
        bot('sendmessage',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid,
            'text'=>"$m1
$m2
$m3
$m4
$m5
$m6
$m7
$m8
$m9
$m10",
            'parse_mode'=>'html',
        ]);
        sleep("1");
        unlink("$uid.get.txt");
    }
    if($text == "/1" or $text == "/2" or $text == "/3" or $text == "/4" or $text == "/5" or $text == "/6" or $text == "/7" or $text == "/8" or $text == "/9" or $text == "/10"){
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid - 1,
            'text'=>"<b>Qayta musiqa qidirishingiz uchun: </b> <code>/musiqa qo'shiq nomi</code> <b>ni yuboring!</b>",
            'parse_mode'=>'html',
        ]);
        $get = file_get_contents("$uid.txt");
        if($text == "/1"){
            $ex = explode("\n",$get);
            $a = explode(">",$ex[3]);
            $m = str_replace("</a","",$a[1]);
            $m = str_replace("/1 .","",$m);
            $m = trim($m);
            $b = explode('/musiqa/',$ex[3]);
            $d = explode('_',$b[1]);
            $r = trim($d[0]);
            $url = "https://xits.pro/download/$r";
            file_put_contents("$uid.mp3",file_get_contents($url));
        }
        if($text == "/2"){
            $ex = explode("\n",$get);
            $a = explode(">",$ex[4]);
            $m = str_replace("</a","",$a[1]);
            $m = str_replace("/2 .","",$m);
            $m = trim($m);
            $b = explode('/musiqa/',$ex[4]);
            $d = explode('_',$b[1]);
            $r = trim($d[0]);
            $url = "https://xits.pro/download/$r";
            file_put_contents("$uid.mp3",file_get_contents($url));
        }
        if($text == "/3"){
            $ex = explode("\n",$get);
            $a = explode(">",$ex[5]);
            $m = str_replace("</a","",$a[1]);
            $m = str_replace("/3 .","",$m);
            $m = trim($m);
            $b = explode('/musiqa/',$ex[5]);
            $d = explode('_',$b[1]);
            $r = trim($d[0]);
            $url = "https://xits.pro/download/$r";
            file_put_contents("$uid.mp3",file_get_contents($url));
        }
        if($text == "/4"){
            $ex = explode("\n",$get);
            $a = explode(">",$ex[6]);
            $m = str_replace("</a","",$a[1]);
            $m = str_replace("/4 .","",$m);
            $m = trim($m);
            $b = explode('/musiqa/',$ex[6]);
            $d = explode('_',$b[1]);
            $r = trim($d[0]);
            $url = "https://xits.pro/download/$r";
            file_put_contents("$uid.mp3",file_get_contents($url));
        }
        if($text == "/5"){
            $ex = explode("\n",$get);
            $a = explode(">",$ex[7]);
            $m = str_replace("</a","",$a[1]);
            $m = str_replace("/5 .","",$m);
            $m = trim($m);
            $b = explode('/musiqa/',$ex[7]);
            $d = explode('_',$b[1]);
            $r = trim($d[0]);
            $url = "https://xits.pro/download/$r";
            file_put_contents("$uid.mp3",file_get_contents($url));
        }
        if($text == "/6"){
            $ex = explode("\n",$get);
            $a = explode(">",$ex[8]);
            $m = str_replace("</a","",$a[1]);
            $m = str_replace("/6 .","",$m);
            $m = trim($m);
            $b = explode('/musiqa/',$ex[8]);
            $d = explode('_',$b[1]);
            $r = trim($d[0]);
            $url = "https://xits.pro/download/$r";
            file_put_contents("$uid.mp3",file_get_contents($url));
        }
        if($text == "/7"){
            $ex = explode("\n",$get);
            $a = explode(">",$ex[9]);
            $m = str_replace("</a","",$a[1]);
            $m = str_replace("/7 .","",$m);
            $m = trim($m);
            $b = explode('/musiqa/',$ex[9]);
            $d = explode('_',$b[1]);
            $r = trim($d[0]);
            $url = "https://xits.pro/download/$r";
            file_put_contents("$uid.mp3",file_get_contents($url));
        }
        if($text == "/8"){
            $ex = explode("\n",$get);
            $a = explode(">",$ex[10]);
            $m = str_replace("</a","",$a[1]);
            $m = str_replace("/8 .","",$m);
            $m = trim($m);
            $b = explode('/musiqa/',$ex[10]);
            $d = explode('_',$b[1]);
            $r = trim($d[0]);
            $url = "https://xits.pro/download/$r";
            file_put_contents("$uid.mp3",file_get_contents($url));
        }
        if($text == "/9"){
            $ex = explode("\n",$get);
            $a = explode(">",$ex[11]);
            $m = str_replace("</a","",$a[1]);
            $m = str_replace("/9 .","",$m);
            $m = trim($m);
            $b = explode('/musiqa/',$ex[11]);
            $d = explode('_',$b[1]);
            $r = trim($d[0]);
            $url = "https://xits.pro/download/$r";
            file_put_contents("$uid.mp3",file_get_contents($url));
        }
        if($text == "/10"){
            $ex = explode("\n",$get);
            $a = explode(">",$ex[12]);
            $m = str_replace("</a","",$a[1]);
            $m = str_replace("/10 .","",$m);
            $m = trim($m);
            $b = explode('/musiqa/',$ex[12]);
            $d = explode('_',$b[1]);
            $r = trim($d[0]);
            $url = "https://xits.pro/download/$r";
            file_put_contents("$uid.mp3",file_get_contents($url));
        }
        $url = "https://xits.pro/download/$r";
        bot('sendmessage',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid,
            'text'=>"📥 Yuklanmoqda...

█▒▒▒▒▒▒▒▒▒ 0%",
            'parse_mode'=>'html',
        ]);
        sleep("1");
        $du = explode(':', $url->result->info->duration);
        $duration = ((int)$du[0] * 60) + (int)$du[1];
        bot('editmessagetext',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
            'text'=>"📥 Yuklanmoqda...

██████████ 100%",
            'parse_mode'=>'html',
        ]);
        bot('deletemessage',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'message_id'=>$mid + 1,
        ]);
        bot('sendaudio',[
            'chat_id'=>$cid,
            'audio'=>new CURLFile($uid.'.mp3'),
            'performer'=>"@botuser Yozasz",
            'title'=>$m,
            'duration'=> $duration ,
            'caption'=>"@Bot useri yozas",
        ]);
        sleep("1");
        unlink("$uid.txt");
        unlink("$uid.mp3");
    }
}
if($type=="supergroup"){
    if(mb_stripos($text,"/musiqa") !== false){
        bot('sendmessage',[
            'chat_id'=>$cid,
            'user_id'=>$uid,
            'reply_to_message_id'=>$mid,
            'text'=>"<b>Musiqa qidirish uchun bot lichkasiga</b> <code>/musiqa qo'shiq nomi</code> <b>ni yuboring, 10 ta qo'shiq ichidan o'zingiz uchun kerakligini tanlang!</b>",
            'parse_mode'=>'html',
        ]);
    }
}