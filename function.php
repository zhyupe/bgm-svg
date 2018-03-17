<?php
if (!defined('BGM_API')) exit;

// set_error_handler(function () {
//   ob_end_clean();
//   output_error(500, '处理时发生未知错误');
//   exit;
// });

function base64_file($src, $type) {
  $im = file_get_contents(BGM_ROOT . $src);
  $imdata = base64_encode($im);

  echo 'data:', $type, ';base64,', $imdata;
}

function find_dom($dom, $query, $first = true) {
  $arr = $dom->find($query);
  if ($first) {
    if (!isset($arr[0])) {
      return false;
    }

    return $arr[0];
  } else {
    return $arr;
  }
}

function parse_bgm($url, $dom) {
  $bgm = [];
  $bgm['url'] = $url;

  $titleDom = find_dom($dom, '#headerSubject h1 a');
  if (!$titleDom) {
    throw new Exception('获取信息时失败');
  }

  $bgm['title'] = $titleDom->getPlainText();
  $bgm['subtitle'] = $titleDom->getAttr('title');

  $scoreDom = find_dom($dom, '.global_score .number');
  $bgm['score'] = explode('.', $scoreDom->getPlainText());

  $summary = find_dom($dom, '#subject_summary');
  if ($summary) {
    $bgm['summary'] = SvgText::make(htmlspecialchars($summary->getPlainText()), 10, 14, 380);
    $bgm['offset_tag'] = SvgText::$offset;
  } else {
    $bgm['summary'] = '';
    $bgm['offset_tag'] = 0;
  }

  $tagDom = find_dom($dom, '.subject_tag_section .inner');
  $bgm['tag'] = [];
  if ($tagDom) {
    $tagA = $tagDom->find('span');
    $tagB = $tagDom->find('small');
    $bgm['tagCount'] = min(count($tagA), 8);
    for ($i = 0; $i < $bgm['tagCount']; $i++) {
      $bgm['tag'][$i] = [
        'name' => $tagA[$i]->getPlainText(),
        'count' => $tagB[$i]->getPlainText()
      ];
    }
  } else {
    $bgm['tagCount'] = 0;
  }

  $bgm['tagRow'] = ceil($bgm['tagCount'] / 4);
  $bgm['offset_cast'] = $bgm['offset_tag'] + 24 * $bgm['tagRow'];
  $bgm['offset'] = $bgm['offset_cast'];

  return $bgm;
}

function output_error($errno, $errmsg) {
  include BGM_ROOT . '/template/error.php';
}
