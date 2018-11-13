<?php
// Config

$config['key'] = '1145141919810';  // 请求鉴定 Key

$config['bot_token'] = '';  // Telegram Bot Token

$config['app_name'] = '';

$config['db_host'] = 'localhost';
$config['db_port'] = '3306';
$config['db_user'] = '';
$config['db_pass'] = '';
$config['db_name'] = $config['db_user'];  // 数据库名
$config['db_char'] = 'utf8';

$config['white_list'] = 'ImYrS23,WooMai';  // 白名单用户

//设置完成后访问以下路径
//http://api.telegram.org/bot$token/setwebhook?url=https://example.com/wenhook.php?Key=$key
//修改$token为bot token，$key为$config['key']

//功能：
//1.查水表：命令：!info
//2.封禁帐号：命令：!sban