<?php
require_once "../db/dbWrapper.php";

class Degree
{
  public $Id;
  public $Name;
  private static $db;

  public static function GetAll()
  {
    try {
      self::$db = new dbWrapper();

      $sql = "select * from degrees";
      $result = self::$db::queryAll($sql);
      $degrees = [];
      if ($result != null) {
        $degrees = [];
        for ($i = 0; $i < count($result); $i++) {
          $degree = new Degree();
          $degree->Id = $result[$i]['Id'];
          $degree->Name = $result[$i]['Name'];
          $degrees[$i] = $degree;
        }
      }
      return $degrees;
    } catch (Exception $e) {
      return [];
    }
  }
}
