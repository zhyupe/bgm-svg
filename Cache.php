<?php
if (!defined('BGM_API')) exit;

class Cache {
  static private function path($id) {
    return BGM_ROOT . '/cache/' . $id . '.svg';
  }

  static function get($id) {
    $path = self::path($id);

    if (!file_exists($path))
      return false;

    if (time() > filemtime($path) + CACHE_TIME) {
      return false;
    }

    return file_get_contents($path);
  }

  static function put($id, $content) {
    file_put_contents(self::path($id), $content);
  }
}
