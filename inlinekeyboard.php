<?php
# Author : @Mr_lotfim | Mr Mohammad Hossein Lotfi
# Channel Author : @ZarinSource
/*
اوپن شده توسط @MaMaD_NoP در چنل @ZarinSource
*/
/*
راهنمای نصب ربات :

1) در لاین 83 به ترتیب - توکن ، ایدی عددی چنل - را وارد کنید
*/
class ZarinSource
{
    private $Token;
    public function __construct($token,$id_channel,$keyboard)
    {
        $Update = json_decode(file_get_contents('php://input'));
        if (isset($Update)) {
            $telegram_ip_ranges = [['lower' => '149.154.160.0', 'upper' => '149.154.175.255'], ['lower' => '91.108.4.0', 'upper' => '91.108.7.255']];
            $ip_dec = (float) sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
            $ok = false;
            foreach ($telegram_ip_ranges as $telegram_ip_range) if (!$ok) {
                $lower_dec = (float) sprintf('%u', ip2long($telegram_ip_range['lower']));
                $upper_dec = (float) sprintf('%u', ip2long($telegram_ip_range['upper']));
                if ($ip_dec >= $lower_dec && $ip_dec <= $upper_dec) $ok = true;
            }
            if (!$ok) die;
            $this->Token = $token;
            if (isset($Update->message)) {
                $message = $Update->message;
                $text = $message->text;
                $tc = $message->chat->type;
                $chat_id = $message->chat->id;
                $message_id = $message->message_id;
                if ($text == "/start") {
                    $this->sendmessage($chat_id,"این ربات توسط @MaMaD_NoP نوشته شده است.",$message_id,$keyboard);
                }
            } 
            if (isset($Update->channel_post)) {
                $channel_post = $Update->channel_post;
                $channel_post_message_id = $channel_post->message_id;
                $channel_post_chat_id = $channel_post->chat->id;
                if ($channel_post_chat_id == $id_channel) {
                    $this->editmessagereplymarkup($channel_post_chat_id, $channel_post_message_id, $keyboard);
                }
            }
        }
        return true;
    }
    private function request($method, $parameters)
    {
        if (!$parameters) {$parameters = [];}
        $parameters["method"] = $method;
        $handle = curl_init('https://api.telegram.org/bot' . $this->Token . '/');
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 60);
        curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
        curl_setopt($handle, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        $result = curl_exec($handle);
        return $result;
    }
    ##----------[sendmessage]----------##
    public function sendmessage($chat_id, $text, $reply = '', $key = '')
    {
        return $this->request('sendmessage', ['chat_id' => $chat_id, 'text' => $text, 'reply_to_message_id' => $reply, 'reply_markup' => $key, 'parse_mode' => "Html"]);
    }
    ##----------[editmessagereplymarkup]----------##
    public function editmessagereplymarkup($chat_id, $message_id, $key = NULL)
    {
        return $this->request("editmessagereplymarkup", ["chat_id" => $chat_id, "message_id" => $message_id, "reply_markup" => $key]);
    }
}
$keyboard = json_encode([
    'inline_keyboard' => [
        [
            ['text' => '💯 GitHub', 'url' => "https://github.com/MrLotfi696"], ['text' => '📣 Telegram', 'url' => "https://t.me/ZarinSource"]
        ]
    ]
]);
$run = new ZarinSource("TOKEN","ID Channel",$keyboard);