<?php
if (!defined('BGM_API')) exit;

class SvgText {
  static $lineArr;
  static $lineCount;
  static $line;
  static $lineSpace;
  static $maxChar;
  static $offset = 0;

  static function charWidth($char) {
    // PHP has bug on U2060(…), so we must do this manually
    $unicode = 0;
    if (isset($char[0])) $unicode = (ord($char[0]) & 0x1F) << 12;
    if (isset($char[1])) $unicode |= (ord($char[1]) & 0x3F) << 6;
    if (isset($char[2])) $unicode |= (ord($char[2]) & 0x3F);

    if ($unicode < 0x0020) {
      return 0;
    } elseif ($unicode < 0x2000) {
      return 1;
    } elseif ($unicode < 0xFF61) {
      return 2;
    } elseif ($unicode < 0xFFA0) {
      return 1;
    } else {
      return 2;
    }
  }

  static function isSpace($char) {
    return preg_match("/\s/u", $char);
  }

  static function isPunctuation($char) {
    return preg_match("/\\\\pP/u", $char);
  }

  static function isPunctuationAtStart($char) {
    $list = array('"', '“', '\'', '<', '《');
    return in_array($char, $list);
  }

  static function appendWord($word, $wordWidth) {
    if ($wordWidth > self::$lineSpace) {
      self::$lineArr[] = self::$line;
      self::$lineCount++;
      self::$line = $word;
      self::$lineSpace = self::$maxChar - $wordWidth;
    } elseif ($wordWidth) {
      self::$line .= $word;
      self::$lineSpace -= $wordWidth;
    }
  }

  static function appendSpace() {
    if (self::$line) {
      self::$line .= ' ';
      self::$lineSpace -= 1;
    }
  }

  static function checkFinalLine() {
    if (self::$line) {
      self::$lineArr[] = self::$line;
      self::$lineCount++;
    }
  }

  static function splitLine($text, $maxChar) {
    self::$lineArr = [];
    self::$lineCount = 0;

    self::$line = '';
    self::$lineSpace = $maxChar;
    self::$maxChar = $maxChar;

    $word = '';
    $wordWidth = 0;
    $wordMode = 0;

    foreach (preg_split('//u', $text) as $i => $char) {
      $charWidth = self::charWidth($char);
      if ($charWidth == 0) continue;

      if (self::isSpace($char)) {
        if ($wordWidth) {
          self::appendWord($word, $wordWidth);
          $wordMode = 2;
          $word = '';
          $wordWidth = 0;
        }
      } elseif (self::isPunctuation($char)) {
        if ($wordWidth) {
          self::appendWord($word, $wordWidth);
        }

        if (self::isPunctuationAtStart($char)) {
          $word = $char;
          $wordWidth = $charWidth;
        } else {
          $word = '';
          $wordWidth = 0;

          self::$line .= $char;
          self::$lineSpace -= $wordWidth;
        }
      } elseif ($charWidth == 1) {
        if ($wordMode && $wordMode != 1) {
          self::appendSpace();
          $wordMode = 1;
        }

        $word .= $char;
        $wordWidth += 1;
      } else {
        if ($wordWidth) {
          self::appendWord($word, $wordWidth);
          self::appendSpace();
          $word = '';
          $wordWidth = 0;
        }
        $wordMode = 2;
        self::appendWord($char, $charWidth); // charWidth should be 2
      }
    }

    if ($wordWidth) {
      self::appendWord($word, $wordWidth);
    }
    self::checkFinalLine();
    return self::$lineArr;
  }

  static function make($text, $x, $size, $width) {
    $dy = $size * 1.5;
    $dp = $size * 2;
    $maxChar = floor($width / $size * 2);
    $arr = explode("\n", $text);
    $return = '';
    self::$offset = 0;

    foreach ($arr as $line) {
      $lineChar = mb_strwidth($line);
      $lineArr = self::splitLine($line, $maxChar);
      $first = true;
      foreach ($lineArr as $subLine) {
        if ($first) {
          $first = false;
          $return .= '<tspan x="' . $x . '" dy="' . $dp . '">' . $subLine . '</tspan>';
          self::$offset += $dp;
        } else {
          $return .= '<tspan x="' . $x . '" dy="' . $dy . '">' . $subLine . '</tspan>';
          self::$offset += $dy;
        }
      }
    }

    return $return;
  }
}
