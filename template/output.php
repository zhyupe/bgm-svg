<?php
if (!defined('BGM_API')) exit('Access Denied');
$h = SVG_H + $bgm['offset'];
?>
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" height="<?=$h?>px" width="<?=SVG_W?>px" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 <?=SVG_W?> <?=$h?>">
  <g>
    <rect stroke-linejoin="round" height="<?=$h?>" width="<?=SVG_W?>" stroke="#cdcdcd" y="0" x="0" rx="5" ry="5" stroke-width="1" fill="#fff"/>
    <rect stroke-linejoin="round" height="60" width="<?=SVG_W?>" stroke="#cdcdcd" y="0" x="0" rx="5" ry="5" stroke-width="1" fill="#eee"/>
    <image width="50" height="50" xlink:href="<?php base64_file('/bgm.png', 'image/png') ?>" y="1" x="1"/>

    <text font-size="18px" y="26" x="60" line-height="150%" fill="#444"><?=$bgm['title']?></text>
    <text font-size="14px" y="46" x="60" line-height="150%" fill="#888"><?=$bgm['subtitle']?></text>
    <text font-size="28px" y="40" x="350" font-family="Arial" line-height="150%" font-style="italic" fill="#F09199"><?=$bgm['score'][0]?>.<tspan font-size="18px"><?=$bgm['score'][1]?></tspan></text>
    <text font-size="14px" y="60" x="10" width="<?=SVG_W-20?>" line-height="150%" fill="#444"><?=$bgm['summary']?></text>
  </g>
  <g>
    <rect height="16px" width="2px" y="<?=75+$bgm['offset_tag']?>" x="10" fill="#42ABB3"/>
    <text font-size="14px" y="<?=87+$bgm['offset_tag']?>" x="17" font-family="Arial" width="<?=SVG_W-20?>" line-height="150%" fill="#42ABB3">Tags</text>

    <text font-size="14px" y="<?=111+$bgm['offset_tag']?>" x="10" width="<?=SVG_W-20?>" line-height="150%" fill="#0084B4">
    <?php
    for ($y = 0; $y < $bgm['tagRow']; $y++) {
      for ($x = 0; $x < 4; $x++) {
        $i = $y * 4 + $x;
        $tag = $bgm['tag'][$i];
        if (!$tag) break;
         ?>
      <tspan<?=$x ? ' dx="5"' : (' x="10" dy="' . $y * 24 . '"')?>><?=htmlspecialchars($tag['name'])?></tspan>
      <tspan font-size="12px" fill="#999"><?=htmlspecialchars($tag['count'])?></tspan>
        <?php
      }
    }
    ?>
    </text>
  </g>
  <g>
    <text font-size="14px" y="<?=120+$bgm['offset_cast']?>" x="10" font-family="Arial" line-height="150%" fill="#F09199"><?=$bgm['url']?></text>
    <text font-size="14px" y="<?=120+$bgm['offset_cast']?>" x="<?=SVG_W-10?>" font-family="Arial" line-height="150%" fill="#999" text-anchor="end">更新: <?=date('m/d H:i')?></text>
  </g>
</svg>
