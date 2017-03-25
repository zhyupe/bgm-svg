<?php
define('BGM_API', 1);
define('BGM_ROOT', __DIR__);
define('CACHE_TIME', 86400);

define('SVG_H', 140);
define('SVG_W', 400);

require BGM_ROOT . '/vendor/autoload.php';
require BGM_ROOT . '/function.php';
require BGM_ROOT . '/SvgText.php';

// error_reporting(0);
header('Content-Type: image/svg+xml');

if (!isset($_GET['id'])) {
  output_error(400, '没有指定 id 值');
  exit;
}

$id = intval($_GET['id']);

if ($id <= 0) {
  output_error(400, '指定了错误的 id 值');
  exit;
}

if (CACHE_TIME) {
  require BGM_ROOT . '/Cache.php';
  $content = Cache::get($id);
  if ($content !== false) {
    echo $content;
    exit;
  }

  ob_start();
}

$url = 'http://bangumi.tv/subject/' . $id;

$curl = new \Curl\Curl();
$curl->get($url);

if ($curl->error) {
  output_error($curl->errorCode, $curl->errorMessage);
} else {
  try {
    $dom = new \HtmlParser\ParserDom($curl->response);
    $bgm = parse_bgm($url, $dom);
    include BGM_ROOT . '/template/output.php';
  } catch (Exception $e) {
    output_error(500, $e->getMessage());
  }
}

if (CACHE_TIME) {
  $output = ob_get_clean();
  $output = preg_replace("/[\r\n]+\s*/", '',$output);
  Cache::put($id, $output);
  echo $output;
}
