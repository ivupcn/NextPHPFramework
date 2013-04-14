<?php
/**
 *  index.php NextPHP 入口
 *
 * @copyright			(C) 2005-2010 NextPHP
 * @license			http://ivup.cn/license/
 * @lastmodify			2013-03-01
 */

 //应用入口文件
define('APP_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
require APP_PATH.'framework'.DIRECTORY_SEPARATOR.'Next.php';
Next::runApp(1);
?>