<?php

namespace App\Utils;

use App\Models\User;
use App\Services\Config;
use App\Services\Analytics;

class TelegramProcess
{
    private static function needbind_method($bot, $message, $command, $user, $reply_to = null)
    {
        if ($user != null) {
            switch ($command) {
				//签到——————————————————————————————————————————————————————————————————————————
                case 'checkin':
					$Admin = $user->is_admin;
					if ($Admin != 1) {
						if (!$user->isAbleToCheckin()) {
                        $bot->sendMessage($message->getChat()->getId(), "呀呀呀，你今天已经签到了，明天再来找我吧😘", $parseMode = null, $disablePreview = false, $replyToMessageId = $reply_to);
                        break;
						}
					}
                	if ($Admin != 1) {
						$traffic = rand(Config::get('checkinMin'), Config::get('checkinMax'));
						$user->transfer_enable = $user->transfer_enable + Tools::toMB($traffic);
												$user->last_check_in_time = time();
						$user->save();
						$bot->sendMessage($message->getChat()->getId(), "（づ￣3￣）づ╭❤～签到成功！本小可爱决定送你 ".$traffic." MB 流量~", $parseMode = null, $disablePreview = false, $replyToMessageId = $reply_to);
						break;
                    } else {
						$traffic = rand(Config::get('checkinMin'), Config::get('checkinMax'));
						$traffic = $traffic * 66;
						$user->transfer_enable = $user->transfer_enable + Tools::toMB($traffic);
						$user->last_check_in_time = time();
						$user->save();
						$bot->sendMessage($message->getChat()->getId(), "（づ￣3￣）づ╭❤～签到成功！由于您是网站管理员，签到流量*66倍，您获得了 ".$traffic." MB 流量~", $parseMode = null, $disablePreview = false, $replyToMessageId = $reply_to);
						break;
                    }
				//特殊签到——————————————————————————————————————————————————————————————————————————
				case 'specialcheckin':
					if (Config::get('SpecialCheckin') != null) {
						$Admin = $user->is_admin;
						if ($Admin != 1) {
							if (!$user->isAbleToCheckin()) {
							$bot->sendMessage($message->getChat()->getId(), "呀呀呀，你今天已经签到了，明天再来找我吧😘", $parseMode = null, $disablePreview = false, $replyToMessageId = $reply_to);
							break;
							}
						}
						$traffic = rand(Config::get('STrafficMin'), Config::get('STrafficMax'));
						if ($traffic >= 0) {
							$user->transfer_enable = $user->transfer_enable + Tools::toMB($traffic);
							$user->last_check_in_time = time();
							$user->save();
							$bot->sendMessage($message->getChat()->getId(), "恭喜你参加了今天的特殊签到！运气超级好！得到了 ".$traffic." MB 流量，真是太幸运啦！", $parseMode = null, $disablePreview = false, $replyToMessageId = $reply_to);
							break;
						} else {
							$traffic = 0 - $traffic;
							$user->transfer_enable = $user->transfer_enable - Tools::toMB($traffic);
							$user->last_check_in_time = time();
							$user->save();
							$bot->sendMessage($message->getChat()->getId(), "恭喜你参加了今天的特殊签到！被扣除了 ".$traffic." MB 流量，人品太差了吧！", $parseMode = null, $disablePreview = false, $replyToMessageId = $reply_to);
							break;
						}
                    } else {
						$bot->sendMessage($message->getChat()->getId(), "特殊签到暂时没有开启哦", $parseMode = null, $disablePreview = false, $replyToMessageId = $reply_to);
				break;
					}
				//SS/SSR一键切换————————————————————————————————————————————————————————————————————
				case 'switch':
					$text = explode(" ",$message->getText(),2);
					$param = $text[1];
					if(!isset($text[1])) {
						$bot->sendMessage($message->getChat()->getId(), "参数为空！\r\n\r\n切换为SS/SSD请发送 /switch ss\r\n切换为SSR请发送 /switch ssr", $parseMode = null, $disablePreview = false, $replyToMessageId = $reply_to);
					}
					//SS/SSD
					if ($param == "ss" ) {
						$user->method = "aes-256-gcm";
						$user->protocol = "origin";
						$user->protocol_param = "";
						$user->obfs = "plain";
						$user->obfs_param = Config::get('reg_obfs_param');
						$user->save();
						$bot->sendMessage($message->getChat()->getId(), "您的加密协议混淆设置已经设置为SS/SSD配置\r\n请更新订阅后使用。", $parseMode = null, $disablePreview = false, $replyToMessageId = $reply_to);
					}
					//SSR
					if ($param == "ssr" ) {
						$user->method = "aes-256-cfb";
						$user->protocol = "auth_aes128_md5";
						$user->protocol_param = "";
						$user->obfs = "http_simple";
						$user->obfs_param = Config::get('reg_obfs_param');
						$user->save();
						$bot->sendMessage($message->getChat()->getId(), "您的加密协议混淆设置已经设置为SSR配置\r\n请更新订阅后使用。", $parseMode = null, $disablePreview = false, $replyToMessageId = $reply_to);
					}
				break;
				//prpr——————————————————————————————————————————————————————————————————————————
	    		case 'prpr':
					$prpr = array('⁄(⁄ ⁄•⁄ω⁄•⁄ ⁄)⁄', '(≧ ﹏ ≦)', '(*/ω＼*)', 'ヽ(*。>Д<)o゜', '(つ ﹏ ⊂)', '( >  < )');
                    $bot->sendMessage($message->getChat()->getId(), $prpr[mt_rand(0,5)], $parseMode = null, $disablePreview = false, $replyToMessageId = $reply_to);
                break;
				//帐号状态——————————————————————————————————————————————————————————————————————————
				case 'stat':
					$class = $user->class;
					$classExpire = $user->class_expire;
					$name = $user->user_name;
					$bot->sendMessage($message->getChat()->getId(), "halo💗， ".$name." ！
这是你的账号的使用情况~
你的账号等级是VIP ".$class." 呢w
等级有效期到： ".$classExpire."

这是你的流量的使用情况~
今天用了这么多啦： ".$user->TodayusedTraffic()." （".number_format(($user->u+$user->d-$user->last_day_t)/$user->transfer_enable*100, 2)."%）
今天之前用了这些： ".$user->LastusedTraffic()." （".number_format($user->last_day_t/$user->transfer_enable*100, 2)."%）
还剩下这么多没用哟： ".$user->unusedTraffic()." （".number_format(($user->transfer_enable-($user->u+$user->d))/$user->transfer_enable*100, 2)."%）
", $parseMode = null, $disablePreview = false, $replyToMessageId = $reply_to);
				break;
				//账户详情——————————————————————————————————————————————————————————————————————————
				case 'account':
					$id = $user->id;
					$name = $user->user_name;
					$port = $user->port;
					$regDate = $user->reg_date;
					$refBy = $user->ref_by;
					$regIP = $user->reg_ip;
					$inviteNum = $user->invite_num;
					$money = $user->money;
					$telegramID = $user->telegram_id;
					$class = $user->class;
					$classExpire = $user->class_expire;
					$Admin = $user->is_admin;
					if ($Admin != 1) {
						$isAdmin = "用户";
					} else {
						$isAdmin = "管理员"; 
					}
					$bot->sendMessage($message->getChat()->getId(), "账户详情：
用户ID： ".$id."
用户名： ".$name."
端口： ".$port."
等级： VIP ".$class."
等级有效期： ".$classExpire."
剩余流量： ".$user->unusedTraffic()."
账户余额： ".$money." CNY
注册时间： ".$regDate."
注册IP： ".$regIP."
邀请人ID： ".$refBy."
剩余邀请次数： ".$inviteNum." 次
Telegram ID ： ".$telegramID."
网站身份： ".$isAdmin."w"
, $parseMode = null, $disablePreview = false, $replyToMessageId = $reply_to);
				break;

            default:
				$bot->sendMessage($message->getChat()->getId(), "什么？", $parseMode = null, $disablePreview = false, $replyToMessageId = $reply_to);
            }
        } else {
            $bot->sendMessage($message->getChat()->getId(), "咦惹，你还没绑定本站账号呢。快去 资料编辑 这个页面找找 Telegram 绑定指示吧~", $parseMode = null, $disablePreview = false, $replyToMessageId = $reply_to);
        }
    }


    public static function telegram_process($bot, $message, $command)
    {
        $user = User::where('telegram_id', $message->getFrom()->getId())->first();

        if ($message->getChat()->getId() > 0) {
            //个人
            $commands = array("ping", "chat", "checkin", "help", "stat", "account", "specialcheckin");
            if(in_array($command, $commands)){
                $bot->sendChatAction($message->getChat()->getId(), 'typing');
            }
            switch ($command) {
				case 'ping':
                    $bot->sendMessage($message->getChat()->getId(), '查到啦！这个群组的 ID 是 '.$message->getChat()->getId().'!');
                break;
				case 'chat':
                    $bot->sendMessage($message->getChat()->getId(), Tuling::chat($message->getFrom()->getId(), substr($message->getText(), 5)));
                break;
				case 'checkin':
                    TelegramProcess::needbind_method($bot, $message, $command, $user, $message->getMessageId());
                break;
				case 'prpr':
                    TelegramProcess::needbind_method($bot, $message, $command, $user, $message->getMessageId());
                break;
				case 'stat':
                    TelegramProcess::needbind_method($bot, $message, $command, $user, $message->getMessageId());
                break;
				case 'account':
                    TelegramProcess::needbind_method($bot, $message, $command, $user, $message->getMessageId());
                break;
				case 'specialcheckin':
                    TelegramProcess::needbind_method($bot, $message, $command, $user, $message->getMessageId());
                break;
				case 'help':
                    $help_list = "命令列表：
/checkin - 签到（记得日常签到哦）
/specialcheckin - 特殊签到（偶尔开放）
/stat - 查询等级/流量
/switch - 切换SS/SSR配置
/account - 用户详情（包含邮箱、注册ip等内容）
/prpr - 调戏
/ping - 查看群组或用户id
/help - 查看帮助
";
                    $bot->sendMessage($message->getChat()->getId(), $help_list);
                    break;
                default:
                    if ($message->getPhoto() != null) {
                        $bot->sendMessage($message->getChat()->getId(), "小可爱正在很努力很努力解码呢QAQ，稍等下~");
                        $bot->sendChatAction($message->getChat()->getId(), 'typing');

                        $photos = $message->getPhoto();

                        $photo_size_array = array();
                        $photo_id_array = array();
                        $photo_id_list_array = array();


                        foreach ($photos as $photo) {
                            $file = $bot->getFile($photo->getFileId());
                            $real_id = substr($file->getFileId(), 0, 36);
                            if (!isset($photo_size_array[$real_id])) {
                                $photo_size_array[$real_id] = 0;
                            }

                            if ($photo_size_array[$real_id] < $file->getFileSize()) {
                                $photo_size_array[$real_id] = $file->getFileSize();
                                $photo_id_array[$real_id] = $file->getFileId();
                                if (!isset($photo_id_list_array[$real_id])) {
                                    $photo_id_list_array[$real_id] = array();
                                }

                                array_push($photo_id_list_array[$real_id], $file->getFileId());
                            }
                        }

                        foreach ($photo_id_array as $key => $value) {
                            $file = $bot->getFile($value);
                            $qrcode_text = QRcode::decode("https://api.telegram.org/file/bot".Config::get('telegram_token')."/".$file->getFilePath());

                            if ($qrcode_text == null) {
                                foreach ($photo_id_list_array[$key] as $fail_key => $fail_value) {
                                    $fail_file = $bot->getFile($fail_value);
                                    $qrcode_text = QRcode::decode("https://api.telegram.org/file/bot".Config::get('telegram_token')."/".$fail_file->getFilePath());
                                    if ($qrcode_text != null) {
                                        break;
                                    }
                                }
                            }

                            if (substr($qrcode_text, 0, 11) == 'mod://bind/' && strlen($qrcode_text) == 27) {
                                $uid = TelegramSessionManager::verify_bind_session(substr($qrcode_text, 11));
                                if ($uid != 0) {
                                    $user = User::where('id', $uid)->first();
                                    $user->telegram_id = $message->getFrom()->getId();
                                    $user->im_type = 4;
                                    $user->im_value = $message->getFrom()->getUsername();
                                    $user->save();
                                    $bot->sendMessage($message->getChat()->getId(), "绑定成功。邮箱：".$user->email);
                                } else {
                                    $bot->sendMessage($message->getChat()->getId(), "绑定失败，二维码无效。".substr($qrcode_text, 11));
                                }
                            }

                            if (substr($qrcode_text, 0, 12) == 'mod://login/' && strlen($qrcode_text) == 28) {
                                if ($user != null) {
                                    $uid = TelegramSessionManager::verify_login_session(substr($qrcode_text, 12), $user->id);
                                    if ($uid != 0) {
                                        $bot->sendMessage($message->getChat()->getId(), "登录验证成功。邮箱：".$user->email);
                                    } else {
                                        $bot->sendMessage($message->getChat()->getId(), "登录验证失败，二维码无效。".substr($qrcode_text, 12));
                                    }
                                } else {
                                    $bot->sendMessage($message->getChat()->getId(), "登录验证失败呢！因为你还没绑定本站账号呢。快去 资料编辑 这个页面找找 Telegram 绑定指示吧~".substr($qrcode_text, 12));
                                }
                            }

                            break;
                        }
                    } else {
                        if (is_numeric($message->getText()) && strlen($message->getText()) == 6) {
                            if ($user != null) {
                                $uid = TelegramSessionManager::verify_login_number($message->getText(), $user->id);
                                if ($uid != 0) {
                                    $bot->sendMessage($message->getChat()->getId(), "登录验证成功。邮箱：".$user->email);
                                } else {
                                    $bot->sendMessage($message->getChat()->getId(), "登录验证失败，数字无效。");
                                }
                            } else {
                                $bot->sendMessage($message->getChat()->getId(), "登录验证失败呢！因为你还没绑定本站账号呢。快去 资料编辑 这个页面找找 Telegram 绑定指示吧~");
                            }
                            break;
                        }
                        $bot->sendMessage($message->getChat()->getId(), Tuling::chat($message->getFrom()->getId(), $message->getText()));
                    }
            }
        } else {
            //群组
            if (Config::get('telegram_group_quiet') == 'true') {
                return;
            }
            $commands = array("ping", "chat", "checkin", "help", "stat","account", "specialcheckin");
            if(in_array($command, $commands)){
                $bot->sendChatAction($message->getChat()->getId(), 'typing');
            }
            switch ($command) {
                case 'ping':
                    $bot->sendMessage($message->getChat()->getId(), '查到啦！这个群组的 ID 是 '.$message->getChat()->getId().'!', $parseMode = null, $disablePreview = false, $replyToMessageId = $message->getMessageId());
                break;
                case 'chat':
                    if ($message->getChat()->getId() == Config::get('telegram_chatid')) {
                        $bot->sendMessage($message->getChat()->getId(), Tuling::chat($message->getFrom()->getId(), substr($message->getText(), 5)), $parseMode = null, $disablePreview = false, $replyToMessageId = $message->getMessageId());
                    } else {
                        $bot->sendMessage($message->getChat()->getId(), '不约，叔叔我们不约。', $parseMode = null, $disablePreview = false, $replyToMessageId = $message->getMessageId());
                    }
                break;
                case 'checkin':
                    TelegramProcess::needbind_method($bot, $message, $command, $user, $message->getMessageId());
                break;
                case 'stat':
                    TelegramProcess::needbind_method($bot, $message, $command, $user, $message->getMessageId());
                break;
                case 'account':
                    TelegramProcess::needbind_method($bot, $message, $command, $user, $message->getMessageId());
                break;
	    		case 'prpr':
                    TelegramProcess::needbind_method($bot, $message, $command, $user, $message->getMessageId());
                break;
				case 'specialcheckin':
                    TelegramProcess::needbind_method($bot, $message, $command, $user, $message->getMessageId());
                break;
                case 'help':
                    $help_list_group = "用户命令列表：
/checkin - 签到（记得日常签到哦）
/specialcheckin - 特殊签到（偶尔开放）
/stat - 查询等级/流量
/switch - 切换SS/SSR配置
/account - 用户详情（包含邮箱、注册ip等内容）
/prpr - 调戏
/ping - 查看群组或用户id
/help - 查看帮助
";
                    $bot->sendMessage($message->getChat()->getId(), $help_list_group, $parseMode = null, $disablePreview = false, $replyToMessageId = $message->getMessageId());
                break;
                default:
                    if ($message->getText() != null) {
                        if ($message->getChat()->getId() == Config::get('telegram_chatid')) {
                            $bot->sendMessage($message->getChat()->getId(), Tuling::chat($message->getFrom()->getId(), $message->getText()), $parseMode = null, $disablePreview = false, $replyToMessageId = $message->getMessageId());
                        } else {
                            $bot->sendMessage($message->getChat()->getId(), '不约，叔叔我们不约。', $parseMode = null, $disablePreview = false, $replyToMessageId = $message->getMessageId());
                        }
                    }
                    if ($message->getNewChatMember() != null && Config::get('enable_welcome_message') == 'true') {
                        $bot->sendMessage($message->getChat()->getId(), "你好呀 ".$message->getNewChatMember()->getFirstName()."  ".$message->getNewChatMember()->getLastName()."，很高兴认识你哟，先完成进群验证码吧，然后去看看群规~", $parseMode = null, $disablePreview = false);
                    }
            }
        }

        $bot->sendChatAction($message->getChat()->getId(), '');
    }

    public static function process()
    {
        try {
            $bot = new \TelegramBot\Api\Client(Config::get('telegram_token'));
            // or initialize with botan.io tracker api key
            // $bot = new \TelegramBot\Api\Client('YOUR_BOT_API_TOKEN', 'YOUR_BOTAN_TRACKER_API_KEY');

            $command_list = array("ping", "chat" , "help", "prpr", "checkin", "stat", "account", "specialcheckin");
            foreach ($command_list as $command) {
                $bot->command($command, function ($message) use ($bot, $command) {
                    TelegramProcess::telegram_process($bot, $message, $command);
                });
            }

            $bot->on($bot->getEvent(function ($message) use ($bot) {
                TelegramProcess::telegram_process($bot, $message, '');
            }), function () {
                return true;
            });

            $bot->run();
        } catch (\TelegramBot\Api\Exception $e) {
            $e->getMessage();
        }
    }
}
