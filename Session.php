<?php

namespace app\core;

class Session
{
  protected const FLASH_KEY = 'flesh_message';

  public function __construct()
  {
    session_start();
    $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
    foreach($flashMessages as $key => &$flashMessage){
      $flashMessage['remove'] = true;
    }
    $_SESSION[self::FLASH_KEY] = $flashMessages;
    // echo '<pre>';
    // var_dump($_SESSION[self::FLASH_KEY]);
    // echo '</pre>';
  }
  public function setFlash($key, $message){
    $_SESSION[self::FLASH_KEY][$key] = [
      'remove' => false,
      'value' => $message
    ];
  }

  public function getFlash($key){
    return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
  }

  public function __destruct()
  {
    $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
    foreach($flashMessages as $key => &$flashMessage){
      if($flashMessage['remove']){
        unset($flashMessages[$key]);
      }
    }
    $_SESSION[self::FLASH_KEY] = $flashMessages;
  }

  public function set($key, $val){
    $_SESSION[$key] = $val;
  }
  public function get($key){
    return $_SESSION[$key] ?? false;
  }
  public function remove($key)
  {
    unset($_SESSION[$key]);
  }
}