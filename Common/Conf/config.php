<?php
return array(
	'DB_TYPE'      =>  'mysql',     // 数据库类型
    'DB_HOST'      =>  '127.0.0.1',     // 服务器地址
    'DB_NAME'      =>  'think',     // 数据库名
    'DB_USER'      =>  'root',     // 用户名
    'DB_PWD'       =>  'root',     // 密码
    'DB_PORT'      =>  '3306',     // 端口
    // 'DB_PREFIX'    =>  '',     // 数据库表前缀
    // 'DB_DSN'       =>  '',     // 数据库连接DSN 用于PDO方式
    'DB_CHARSET'   =>  'utf8', // 数据库的编码 默认为utf8
    'URL_MODEL' => 2,
    'DB_PREFIX' => 'think_',
    "PWD_SALT" => '@think',

    'TMPL_FILE_DEPR' => '_',
    'LAYOUT_ON'=>true,
    'LAYOUT_NAME'=>'layout',

    'MODULE_ALLOW_LIST'    =>    array('Home','Admin','User'),
    'DEFAULT_MODULE'       =>    'Home',  // 默认模块
    'HTML_CACHE_ON'=>true,
);