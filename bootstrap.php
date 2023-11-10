<?php
define('_DIR_ROOT', __DIR__);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Authorization, X-Requested-With, Content-Type");
header('Access-Control-Request-Method: application/json');
header('Content-Type: application/x-www-form-urlencoded');

// Xử lý http root
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'):
    $webRoot = 'https://' . $_SERVER['HTTP_HOST'];
else:
    $webRoot = 'http://' . $_SERVER['HTTP_HOST'];
endif;

$dirRoot = str_replace('\\', '/', _DIR_ROOT);

$documentRoot = $_SERVER['DOCUMENT_ROOT'];

$folder = str_replace(strtolower($documentRoot), '', strtolower($dirRoot));

$webRoot = $webRoot . $folder;

define('_WEB_ROOT', $webRoot);


/* 
    Tự động load configs
*/
// scandir: trả về một mảng chứa tên của các tệp và thư mục trong thư mục đã chỉ định.
$configsDir = scandir('configs'); 

if (!empty($configsDir)):
    foreach ($configsDir as $item):
        if ($item != '.' && $item != '..' && file_exists('configs/'.$item)):
            require_once 'configs/'.$item;
        endif;
    endforeach;
endif;

require_once 'phpmailer/PHPMailer.php';
require_once 'phpmailer/SMTP.php';
require_once 'phpmailer/Exception.php';

require_once 'core/Route.php';
require_once 'core/Session.php';
require_once 'app/App.php';

// Kiểm tra config của DB và load vào
if (!empty($config['database'])):
    $dbConfig = $config['database'];
    
    if (!empty($dbConfig)):
        require_once 'core/Connection.php';
        require_once 'core/QueryBuilder.php';
        require_once 'core/Database.php';
        require_once 'core/DB.php';
    endif;

endif;


require_once 'core/Mailer.php';
require_once 'core/Model.php';
require_once 'core/Controller.php';
require_once 'core/Request.php';
require_once 'core/Response.php';
