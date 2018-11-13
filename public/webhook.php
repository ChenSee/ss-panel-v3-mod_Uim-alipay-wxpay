<?php
//  Webhook
error_reporting(4);
require './config.php';

date_default_timezone_set('Asia/Shanghai');

if(!isset($_GET['key']) || $_GET['key'] != $config['key'])
{
    header('HTTP/1.1 403 Forbidden');
    exit('Fuck you.');
}

ob_start();  // 开始记录输出内容 便于调试

$raw = file_get_contents('php://input');
$data = json_decode($raw);

// is it an inline callback query? - 未来预留
if(isset($data->callback_query))
{
    ButtonCallback($data->callback_query);
    quit('inline callback');
}

$message = $data->message;
$from = $message->from;  // id, is_bot, first_name, last_name, username, language_code
$text = $message->text;  // full message text

// check reply
if(isset($message->reply_to_message))
{
    $is_reply = true;
    $reply = $message->reply_to_message;
}
else
{
    $is_reply = false;
    //exit('not reply');  // 仅开放回复查询
}

// is it a cmd ?
if(isset($message->entities) && $message->entities[0]->type == 'bot_command' || substr($text,0,1) == '!' || substr($text,0,1) == '.')  // 可以使用 "/", "!", "." 触发
{
    $is_cmd = true;
    $explode = explode(' ',$text);
    $cmd = explode('@',$explode[0])[0];  // 排除 @
    $cmd = strtolower($cmd);  // 一律转化为小写
    if(isset($explode[1])) $param = $explode[1];  // $param 是参数
}
else
{
    // 非命令
    $is_cmd = false;
    exit();
}

if($is_cmd == true)
{
    switch($cmd)
    {
        //=============== check water meter ===============
        case '/info':
        case '!info':
        case '.info':
        // check admin
        if(CheckAdmin($from->username) == false)
        {
            SendMessage('You are not in whitelist!');
            quit('not admin');
        }

        if($is_reply == true) GetInfoByReply($reply,$param);
        else SendMessage('You need to reply a message!');
        break;
    
        //=============== ban in sspanel ===============
        case '/ban':
        case '!ban':
        case '!sban':
        // check admin
        if(CheckAdmin($from->username) == false)
        {
            SendMessage('You are not in whitelist!');
            quit('not admin');
        }

        if($is_reply == true)
        {
            DisableUser($reply);
            //TelegramBanUser();
        }
        else SendMessage('You need to reply a message!');
        break;
        
        //=============== active in sspanel ===============
        case '/active':
        case '!active':
        case '.active':
        // check admin
        if(CheckAdmin($from->username) == false)
        {
            SendMessage('You are not in whitelist!');
            quit('not admin');
        }

        if($is_reply == true) ActiveUser($reply->from->id,$from->username);
        else SendMessage('You need to reply a message!');
        break;
    }
}
quit();


//==================== functions ====================

function SendMessage($msg,$by_reply = true,$edit = false,$cid = false)
{
    if($cid == false)
    {
        global $message;
        $cid = $message->chat->id;
    }
    $data = array(
        'chat_id' => $cid,
        'text' => $msg,
        'disable_web_page_preview' => true,
        'parse_mode' => 'html'
    );

    if($by_reply === true && $edit === false) $data['reply_to_message_id'] = $message->message_id;

    if($edit !== false)
    {
        $data['message_id'] = $edit;
        $request = TelegramAPI('editMessageText',$data);
    }
    else
    {
        $request = TelegramAPI('sendMessage',$data);
    }
    if(json_decode($request)->ok == true) return true;
    return false;
}

function SendMessageWithButtons($msg,$reply_markup,$by_reply = true)
{
    global $message;
    $data = array(
        'chat_id' => $message->chat->id,
        'text' => $msg,
        'disable_web_page_preview' => true,
        'parse_mode' => 'html',
        'reply_markup' => $reply_markup
    );
    if($by_reply == true) $data['reply_to_message_id'] = $message->message_id;

    $request = TelegramAPI('sendMessage',$data);
    if(json_decode($request)->ok == true) return true;
    return false;
}

function TelegramAPI($method,array $d)
{
    global $config;
    $token = $config['bot_token'];

    $d = json_encode($d);

    $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot$token/$method");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: '.strlen($d)
	));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $d);
	
	$res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

function GetDbConn()
{
    global $config;
    $c = new mysqli($config['db_host'],$config['db_user'],$config['db_pass'],$config['db_name'],$config['db_port']);
    $c->set_charset($config['db_char']);
    return $c;
}

function ByteToGigabyte($byte)
{
    if($byte < 0) $negative = true;
    else $negative = false;

    $byte = abs($byte);  // 绝对值

    // from https://blog.csdn.net/lz610756247/article/details/51536611

    if ($byte >= 1073741824)
    {
        $return = round($byte / 1073741824 * 100) / 100 .' GB';
    }
    elseif ($byte >= 1048576)
    {
        $return = round($byte / 1048576 * 100) / 100 .' MB';
    }
    elseif($byte >= 1024)
    {
        $return = round($byte / 1024 * 100) / 100 . ' KB';
    }
    else
    {
        $return = $byte.' Bytes';
    }

    if($negative == true) return '-'.$return;
    else return $return;
}

function TimestampToTime($sec)
{
    if($sec < 60) return 'Just Now';
    if($sec < (60*60) ) return round( $sec / 60 ) .' minutes ago';  // 一小时内，以分钟为单位
    if($sec < (60*60*24) ) return round( $sec / (60*60) ) .' hours ago';  // 一天内，以小时为单位
    return round( $sec / (60*60*24) ) .' days ago';  // 剩下的以天为单位
}

function CheckAdmin($username)
{
    global $config;
    $admins = explode(',',$config['white_list']);
    if(array_search($username,$admins) === false) return false;
    return true;
}

function GetInfoByReply($reply,$param)
{
    global $config;
    $src_from = $reply->from;
    $src_text = $reply->text;
    $msg = "被回复的用户信息如下：\r\n".
    "ID: <code>$src_from->id</code>\r\n".
    "First Name: $src_from->first_name\r\n";
    if(isset($src_from->last_name)) $msg .= "Last Name: $src_from->last_name\r\n";
    if(isset($src_from->username)) $msg .= "Username: <code>$src_from->username</code>\r\n";
    if(isset($src_from->language_code)) $msg .= "Language: $src_from->language_code\r\n";
    $msg .= "\r\n";

    // 接下来去机场数据库里找
    $c = GetDbConn();
    $rsp = $c->query("SELECT * FROM `user` WHERE `telegram_id` = $src_from->id");
    if($rsp === false)
    {
        $msg .= "Database Query Error:\r\n$c->error";
    }
    elseif($rsp->num_rows === 0)
    {
        $msg .= "该用户未绑定 ".$config['app_name']." 账号";
    }
    else
    {
        $msg .= "对应的 ".$config['app_name']." 账号信息如下：\r\n";
        while($row = $rsp->fetch_assoc())
        {
            // sspanel_id
            $sspanel_id = $row['id'];
            
            // sspanel_username
            $sspanel_username = $row['user_name'];

            // banned
            if($row['enable'] == 0)
            {
                $banned = 'Yes';
            }
            else
            {
                $banned = 'No';
            }

            // email
            $email = $row['email'];

            // email_status
            if($row['is_email_verify'] == 1)
            {
                $email_status = 'Yes';
            }
            else
            {
                $email_status = 'No';
            }

            // traffic_all
            $traffic_all = ByteToGigabyte($row['transfer_enable']);

            // traffic_used
            $traffic_used = ByteToGigabyte($row['u'] + $row['d']);

            // traffic_up
            $traffic_up = ByteToGigabyte($row['u']);

            // traffic_down
            $traffic_down = ByteToGigabyte($row['d']);

            // last_active_time
            $last_active_time = date('Y-m-d H:i:s',$row['t']);

            // last_active
            $during = time() - $row['t'];
            $last_active = TimestampToTime($during);
            unset($during);

            // class
            $class = $row['class'];

            // class_expire
            $class_expire = $row['class_expire'];

            // account_expire
            $account_expire = $row['expire_in'];

            // last_checkin
            $last_checkin = date('Y-m-d H:i:s',$row['last_check_in_time']);

            // reg_date
            $reg_date = $row['reg_date'];

            // invite_num
            $invite_num = $row['invite_num'];

            // balance
            $balance = $row['money'];

            // reg_ip
            $reg_ip = $row['reg_ip'];

            // speedlimit
            $speedlimit = $row['node_speedlimit'];
            if($speedlimit == 0)
            {
                $speedlimit = 'No Limit';
            }
            else
            {
                $speedlimit .= ' Mbps';
            }

            // contact_type && contact_detail
            switch($row['im_type'])
            {
                case 1:
                $contact_type = 'WeChat';
                $contact_detail = '<code>'.$row['im_value'].'</code>';
                break;

                case 2:
                $contact_type = 'QQ';
                $contact_detail = '<code>'.$row['im_value'].'</code>';
                break;

                case 3:
                $contact_type = 'Facebook';
                $contact_detail = '<code>'.$row['im_value'].'</code>';
                break;

                case 4:
                $contact_type = 'Telegram';
                $contact_detail = '@'.$row['im_value'];
                break;
            }

            break;
        }

        if(!isset($param) || $param == 'basic')
        {
            $msg .= "ID: $sspanel_id\r\n".
            "Username: $sspanel_username\r\n".
            "Banned: $banned\r\n".
            "Total Traffic: $traffic_all\r\n".
            "Traffic Used: $traffic_used\r\n".
            "Last Active: $last_active\r\n".
            "Level: $class\r\n".
            "Level Expire: $class_expire\r\n".
            "Register Date: $reg_date\r\n".
            "Balance: $balance\r\n".
            "Speed Limit: $speedlimit\r\n";
        }
        elseif($param == 'all')
        {
            $msg .= "ID: $sspanel_id\r\n".
            "Username: $sspanel_username\r\n".
            "Banned: $banned\r\n".
            "E-Mail: <code>$email</code>\r\n".
            "E-Mail Verified: $email_status\r\n".
            "Total Traffic: $traffic_all\r\n".
            "Traffic Used: $traffic_used\r\n".
            "Upload Traffic: $traffic_up\r\n".
            "Download Traffic: $traffic_down\r\n".
            "Last Active: $last_active_time ($last_active)\r\n".
            "Level: $class\r\n".
            "Level Expire: $class_expire\r\n".
            "Account Expire: $account_expire\r\n".
            "Last Checkin: $last_checkin\r\n".
            "Register Date: $reg_date\r\n".
            "Invite Quota: $invite_num\r\n".
            "Balance: $balance\r\n".
            "Register IP: <code>$reg_ip</code>\r\n".
            "Speed Limit: $speedlimit\r\n".
            "Contact Type: $contact_type\r\n".
            "Contact Detail: $contact_detail\r\n";
        }
    }

    SendMessage($msg);
}

function DisableUser($reply)
{
    // 1. 检测是否 whitelist - ok
    // 2. 检测是否绑定 - ok
    // 3. 检测是否网站 admin
    // 4. 更新数据表
    // 5. 提供一键恢复按钮
    global $config;
    $src_from = $reply->from;
    $src_text = $reply->text;

    // 检查 whitelist
    if(CheckAdmin($src_from->username) === true)
    {
        SendMessage('被回复者在白名单内');
        quit('the user who want to ban is in the whitelist');
    }

    // 检查绑定
    $c = GetDbConn();
    $rs = $c->query("SELECT * FROM `user` WHERE `telegram_id` = $src_from->id");
    if($rs === false)
    {
        SendMessage("啊嘞嘞？\r\n$c->error");
        quit();
    }
    elseif($rs->num_rows === 0)
    {
        SendMessage('该用户未绑定账号');
        quit();
    }

    // 检查网站admin
    if($rs->fetch_assoc()['is_admin'] == 1)
    {
        SendMessage('被回复者是网站管理员');
        quit();
    }

    // 更新数据表
    $sql = "UPDATE `user` SET `enable` = 0 WHERE `telegram_id` = $src_from->id";
    if(!$c->query($sql))
    {
        SendMessage("喵耶。好像出问题了\r\n$c->error");
        quit();
    }

    $t = "$src_from->first_name has been banned at ".$config['app_name'].'!';

    // 按钮
    $buttons[0] = array(
        'text' => '恢复账号',
        'callback_data' => "active:$src_from->id"
    );
    // 可以自行添加按钮 如 $buttons[1]
    $btn = json_encode(array('inline_keyboard'=>array($buttons)));
    SendMessageWithButtons($t,$btn,true);
}

function ActiveUser($tgid,$lanquan,$edit = false,$cid = false)
{
    // 1. 检测是否已被禁用
    // 2. 更新数据表
    global $config;
    $c = GetDbConn();

    // 检测是否绑定
    $rs = $c->query("SELECT `enable` FROM `user` WHERE `telegram_id` = $tgid");
    if($rs === false)
    {
        SendMessage("出问题了 兄dei\r\n$c->error",false);
        quit();
    }
    if($rs->num_rows === 0)
    {
        SendMessage('未找到该用户对应的 '.$config['app_name'].' 账号',true,$edit,$cid);
        quit();
    }

    $enable = $rs->fetch_assoc()['enable'];

    // 检测禁用状态
    if($enable == true)
    {
        SendMessage('该账号未被禁用',true,$edit,$cid);
        quit();
    }

    // 更新数据表
    $rs = $c->query("UPDATE `user` SET `enable` = 1 WHERE `telegram_id` = $tgid");
    if($rs === false)
    {
        SendMessage("启用失败 出问题了兄dei\r\n$c->error",false);
        quit();
    }
    else
    {
        SendMessage("封禁已解除！\r\n滥权: @$lanquan",true,$edit,$cid);
    }
}

// 未来预留
function ButtonCallback($callback_query)
{
    $dt = explode(':',$callback_query->data);
    $method = $dt[0];
    $param = $dt[1];
    $from = $callback_query->from;
    $msg_id = $callback_query->message->message_id;
    $cid = $callback_query->message->chat->id;

    $text = false;  // 点按钮以后显示的信息，不需要就不用理，这里作为默认值，可以在下面的case里设置
    $alert = false;  // 显示一个警告（弹窗），而不是通知

    switch($method)
    {
        case 'active':
        // 根据上面创建按钮时的定义，现在 $param 是 telegram id
        if(CheckAdmin($from->username) === false)
        {
            AnswerCallbackQuery($callback_query->id,'No permission.',$alert);
            quit();
        }
        ActiveUser($param,$from->username,$msg_id,$cid);
        AnswerCallbackQuery($callback_query->id,'Done.',$alert);
        break;

        // 如果加按钮的话这里还能加东西
        //case 'xxxx':
    }

}

function quit($msg = '')
{
    $output = ob_get_clean();  // save output
    file_put_contents("./WatchBot-Recent-Log.txt",$output);
    exit($msg);
}

function AnswerCallbackQuery($cqid,$text,$alert)
{
    $d = array(
        'callback_query_id' => $cqid,
    );
    if($text !== false) $d['text'] = $text;
    if($alert === true) $d['show_alert'] = true;
    TelegramAPI('answerCallbackQuery',$d);
}