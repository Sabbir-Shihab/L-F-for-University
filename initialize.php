<?php
// $dev_data = array('id'=>'-1','firstname'=>'Developer','lastname'=>'','username'=>'dev_oretnom','password'=>'5da283a2d990e8d8512cf967df5bc0d0','last_login'=>'','date_updated'=>'','date_added'=>'');
if(!defined('base_url')) define('base_url', isset($_SERVER['HTTP_HOST']) ? ((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/') : 'http://localhost/php-lfis/');
if(!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );
// if(!defined('dev_data')) define('dev_data',$dev_data);
if(!defined('DB_SERVER')) define('DB_SERVER',"127.0.0.1");
// Database user for local development. Create this user or adjust as needed.
if(!defined('DB_USERNAME')) define('DB_USERNAME',"lfis");
if(!defined('DB_PASSWORD')) define('DB_PASSWORD',"lfis_pass");
if(!defined('DB_NAME')) define('DB_NAME',"lfis_db");
// Use TCP connection by default. Remove custom socket to avoid 'No such file or directory' errors.
// if(!defined('DB_SOCKET')) define('DB_SOCKET',"/tmp/lfis-mysql.sock");
?>
