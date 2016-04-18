<?php
class DB {
  private static $instance = NULL;

  private function __construct() { }
  private function __clone() { }

  public static function getInstance() {
    if (!self::$instance) {
      try {
        self::$instance = new PDO('sqlite:sleep.sqlite');
        self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $ex) {
        error_log("Failed to open database file sleep.sqlite - ".$ex->getMessage() );
        echo($ex->getMessage());
      }
    }
    return self::$instance;
  }
}
