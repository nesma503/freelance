<?php
require_once "../db/dbWrapper.php";

class Job
{
  public $Id;
  public $Name;
  public $Description;
  public $Salary;
  public $isDeleted;
  // public $datePosted;


  private static $db;

  public static function GetAll()
  {
    try {
      if (self::$db ==  null)
        self::$db = new dbWrapper();

      $sql = "select * from jobs";
      $result = self::$db::queryAll($sql);

      if ($result != null) {
        $degrees = [];
        for ($i = 0; $i < count($result); $i++) {
          $job = new Job();
          $job->Id = $result[$i]['Id'];
          $job->Name = $result[$i]['Name'];
          $job->Description = $result[$i]['Description'];
          $job->Salary = $result[$i]['Salary'];
          $job->IsDeleted = $result[$i]['IsDeleted'];
          $job->RecruiterId = $result[$i]['RecruiterId'];
          $jobs[$i] = $job;
          
        }
      }
      return $jobs;
    } catch (Exception $e) {
      return null;
    }
  }
}
