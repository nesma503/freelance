<?php
require_once "../db/dbWrapper.php";

class Job
{
  public $Id;
  public $RecruiterId;
  public $Title;
  public $Description;
  public $CategoryId;
  public $Salary;
  public $ImageUrl;
  public $IsDeleted;
  public $CreationDate;
  private static $db;

  public static function GetAll()
  {
    try {
      self::$db = new dbWrapper();

      $sql = "select * from jobs where IsDeleted = 0 order by CreationDate desc";
      $result = self::$db::queryAll($sql);
      $jobs = [];
      if ($result != null) {
        for ($i = 0; $i < count($result); $i++) {
          $job = new Job();
          $job->Id = $result[$i]['Id'];
          $job->RecruiterId = $result[$i]['RecruiterId'];
          $job->Title = $result[$i]['Title'];
          $job->Description = $result[$i]['Description'];
          $job->CategoryId = $result[$i]['CategoryId'];
          $job->Salary = $result[$i]['Salary'];
          $job->ImageUrl = $result[$i]['ImageUrl'];
          $job->CreationDate = $result[$i]['CreationDate'];
          $jobs[$i] = $job;
        }
      }
      return $jobs;
    } catch (Exception $e) {
      return [];
    }
  }

  public function Save()
    {
        try {
            self::$db = new dbWrapper();
            $result = false;

            if ($this->Id == 0) {
                $sql = "insert into jobs(RecruiterId, Title, Description, CategoryId, Salary, ImageUrl ) values(?,?,?,?,?,?)";
                $id = self::$db::insert($sql, [$this->RecruiterId, $this->Title, $this->Description, $this->CategoryId, $this->Salary, $this->ImageUrl]);
                if ($id > 0) {
                    $this->Id =  $id;
                    $result = true;
                }
            }

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
}
