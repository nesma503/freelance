<?php
require_once "../db/dbWrapper.php";

class Category
{
  public $Id;
  public $Name;
  private static $db;

  public static function GetAll()
  {
    try {
      if (self::$db ==  null)
        self::$db = new dbWrapper();

      $sql = "select * from categories";
      $result = self::$db::queryAll($sql);
      $categories = [];
      if ($result != null) {
        for ($i = 0; $i < count($result); $i++) {
          $category = new Category();
          $category->Id = $result[$i]['Id'];
          $category->Name = $result[$i]['Name'];
          $categories[$i] = $category;
        }
      }
      return $categories;
    } catch (Exception $e) {
      return [];
    }
  }
}
