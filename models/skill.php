<?php
require_once "../db/dbWrapper.php";

class Skill
{
  public $Id;
  public $Name;
  private static $db;

  public static function GetAll()
  {
    try {
      self::$db = new dbWrapper();

      $sql = "select * from skills";
      $result = self::$db::queryAll($sql);
      $skills = [];
      if ($result != null) {
        $skills = [];
        for ($i = 0; $i < count($result); $i++) {
          $skill = new Skill();
          $skill->Id = $result[$i]['Id'];
          $skill->Name = $result[$i]['Name'];
          $skills[$i] = $skill;
        }
      }
      return $skills;
    } catch (Exception $e) {
      return [];
    }
  }
}
