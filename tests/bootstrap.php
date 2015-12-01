<?php
// ensure we get report on all possible php errors
error_reporting(-1);
define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_DEBUG', true);
$_SERVER['SCRIPT_NAME'] = '/' . __DIR__;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;
// require composer autoloader if available
$composerAutoload = __DIR__ . '/../../../autoload.php';
require_once($composerAutoload);
require_once(__DIR__ . '/../../../yiisoft/yii2/Yii.php');
require_once(__DIR__.'/../widget/OL.php'); // cannot be autoloaded for some reason??
Yii::setAlias('@yiiunit', __DIR__);
require_once(__DIR__ . '/TestCase.php');