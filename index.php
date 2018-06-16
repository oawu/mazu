<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2013 - 2018, MAZU
 * @license     http://opensource.org/licenses/MIT  MIT License
 * @link        https://www.ioa.tw/
 */

// 定義時區
date_default_timezone_set('Asia/Taipei');

// 定義版號
define('MAZU', '1.0.0');

//取得此專案資料夾之絕對位置
define('PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

// sys 的絕對位置
define('PATH_SYS', PATH . 'sys' . DIRECTORY_SEPARATOR);

// log 的絕對位置
define('PATH_LOG', PATH . 'log' . DIRECTORY_SEPARATOR);

// app 的絕對位置
define('PATH_APP', PATH . 'app' . DIRECTORY_SEPARATOR);

// view 的絕對位置
define('PATH_VIEW', PATH_APP . 'view' . DIRECTORY_SEPARATOR);

if (!@include_once PATH_SYS . 'Load.php')
  exit('初始化失敗，載入 Load.php 失敗！');

if (!@include_once PATH_SYS . 'View.php')
  exit('初始化失敗，載入 View.php 失敗！');

if (!@include_once PATH_SYS . 'Common.php')
  exit('初始化失敗，載入 Common.php 失敗！');

if (!isPhpVersion('5.6'))
  exit('PHP 版本太舊，請大於等於 5.6');

// 載入基準
Load::path(PATH_SYS . 'Benchmark.php');
Benchmark::markStar('整體');

// 載入編碼
Load::path(PATH_SYS . 'Charset.php');
Charset::init();

// 載入 Log
Load::path(PATH_SYS . 'Log.php');

// 載入 Url
Load::path(PATH_SYS . 'Url.php');
Url::init();

// 載入 Model
// Load::path(PATH_SYS . 'Model.php');

// 載入 Router
Load::path(PATH_SYS . 'Router.php');
Router::init();

class Output {
  static function text ($str) {
    echo $str;
  }
  static function json ($json) {
    echo json_encode($json);
  }
  static function router ($router) {
    if (!$router) {
      responseStatusHeader(404);
      return self::text(View::maybe('error/404.php')->get());
    }

    responseStatusHeader($router->getStatus());

    if (($exec = $router->exec()) === null)
      return self::text('');

    if (is_string($exec))
      return self::text($exec);

    if (is_array($exec))
      return self::json($exec);

    if ($exec instanceOf View)
      return self::text($exec->get());
  }
}

$router = Router::getMatchRouter();
Output::router ($router);






Log::closeAll();
Benchmark::markEnd('整體');

echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
var_dump(Benchmark::elapsedTime());
var_dump(Benchmark::elapsedMemory());
exit ();
