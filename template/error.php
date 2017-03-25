<?php if (!defined('BGM_API')) exit('Access Denied'); ?>
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" height="60px" width="<?=SVG_W?>px" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 <?=SVG_W?> 60">
  <g>
    <rect stroke-linejoin="round" height="60" width="<?=SVG_W?>" stroke="#cdcdcd" y="0" x="0" rx="5" ry="5" stroke-width="1" fill="#eee"/>
    <image width="50" height="50" xlink:href="<?php base64_file('/bgm.png', 'image/png') ?>" y="1" x="1"/>
    <text font-size="18px" y="26" x="60" line-height="150%" fill="#444">获取数据时出错</text>
    <text font-size="14px" y="46" x="60" line-height="150%" fill="#888"><?=$errno?>: <?=$errmsg?></text>
  </g>
</svg>
