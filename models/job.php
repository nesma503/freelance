<?php
require_once "../db/dbWrapper.php";
require_once "../models/freelancer.php";
require_once "../models/category.php";
require_once "../models/degree.php";
require_once "../models/skill.php";

class Job
{
  public $Id;
  public $RecruiterId;
  public $Title;
  public $Description;
  public $CategoryId;
  public $Category;
  public $Salary;
  public $ImageUrl;
  public $IsDeleted;
  public $CreationDate;
  public $Skills;
  public $TotalRows;
  public $Applicants;
  private static $db;

  public static function GetAll($recruiterId = 0, $categoryId = 0, $orderBy = "CreationDate", $page = 1)
  {
    try {
      self::$db = new dbWrapper();

      $pageSize = 2;
      $pageLimit =  $condition  = "";
      if ($page > 0)
        $pageLimit = "LIMIT " . ($page - 1) * $pageSize . "," . $pageSize;
      if ($categoryId > 0)
        $condition = "AND CategoryId =" . $categoryId . " ";
      if ($recruiterId > 0)
        $condition .= "AND RecruiterId =" . $recruiterId . " ";
      $sql = "SELECT j.*, s.Id as SkillId, s.Name, c.Name as Category, (select COUNT(*) FROM `jobs` WHERE IsDeleted = 0 " . $condition . ") AS TotalRows
              from (SELECT * FROM  `jobs`
                    WHERE IsDeleted = 0 " . $condition . " 
                    ORDER  BY " . $orderBy . " DESC " . $pageLimit . ") as j
              inner join `categories` as c on j.CategoryId = c.Id
              left join `jobs-skills` as js on j.Id = js.JobId
              left join `skills` as s on js.SkillId = s.Id";

      $result = self::$db::queryAll($sql);
      $jobs = [];
      if ($result != null) {
        for ($i = 0, $j = 0; $i < count($result); $i++, $j++) {
          $job = new Job();
          $job->Id = $result[$i]['Id'];
          $job->RecruiterId = $result[$i]['RecruiterId'];
          $job->Title = $result[$i]['Title'];
          $job->Description = $result[$i]['Description'];
          $job->CategoryId = $result[$i]['CategoryId'];
          $job->Category= new Category();
          $job->Category->Name = $result[$i]['Category'];
          $job->Salary = $result[$i]['Salary'];
          $job->ImageUrl = $result[$i]['ImageUrl'];
          $job->CreationDate = $result[$i]['CreationDate'];
          $job->TotalRows = $result[$i]['TotalRows'];

          // fill skills array and consume result rows
          if ($result[$i]['SkillId'] > 0) {
            for ($k = 0; $i < count($result) && $job->Id == $result[$i]['Id']; $i++, $k++) {
              $skill = new Skill();
              $skill->Id = $result[$i]['SkillId'];
              $skill->Name = $result[$i]['Name'];
              $job->Skills[$k] = $skill;
            }
            $i--;
          }
          $jobs[$j] = $job;
        }
      }
      return $jobs;
    } catch (Exception $e) {
      return [];
    }
  }

  public static function GetApplicants($recruiterId, $categoryId = 0, $orderBy = "CreationDate", $page = 1)
  {
    try {
      self::$db = new dbWrapper();

      $pageSize = 2;
      $pageLimit =  $condition  = "";
      if ($page > 0)
        $pageLimit = "LIMIT " . ($page - 1) * $pageSize . "," . $pageSize;
      if ($categoryId > 0)
        $condition = "AND CategoryId =" . $categoryId . " ";
      $sql = "SELECT j.*, u.*, f.*, j.FreelancerId, d.Name as Degree, c.Name as Category,
             (select COUNT(*) FROM `jobs` as j
             inner join `jobs-freelancers` as jf on j.Id = jf.JobId
             WHERE IsDeleted = 0 AND RecruiterId=" . $recruiterId . " " . $condition . ") AS TotalRows
              from (SELECT j.*, jf.FreelancerId FROM  `jobs` as j
                    inner join `jobs-freelancers` as jf on j.Id = jf.JobId
                    WHERE IsDeleted = 0 AND RecruiterId=" . $recruiterId . " " . $condition . " 
                    ORDER  BY " . $orderBy . " DESC " . $pageLimit . ") as j
              inner join `categories` as c on j.CategoryId = c.Id
              inner join `freelancers` as f on j.FreelancerId = f.Id
              inner join `users` as u on f.UserId = u.Id
              left join `degrees` as d on f.DegreeId = d.Id";

      $result = self::$db::queryAll($sql);
      $jobs = [];
      if ($result != null) {
        for ($i = 0, $j = 0; $i < count($result); $i++, $j++) {
          $job = new Job();
          $job->Id = $result[$i]['JobId'];
          $job->RecruiterId = $result[$i]['RecruiterId'];
          $job->Title = $result[$i]['Title'];
          $job->Description = $result[$i]['Description'];
          $job->CategoryId = $result[$i]['CategoryId'];
          $job->Category= new Category();
          $job->Category->Name = $result[$i]['Category'];
          $job->Salary = $result[$i]['Salary'];
          $job->ImageUrl = $result[$i]['ImageUrl'];
          $job->CreationDate = $result[$i][8];
          $job->TotalRows = $result[$i]['TotalRows'];

          // fill freelancers array and consume result rows
          if ($result[$i]['FreelancerId'] > 0) {
            for ($k = 0; $i < count($result) && $job->Id == $result[$i]['JobId']; $i++, $k++) {
              $freelancer = new Freelancer();
              $freelancer->Id = $result[$i]['FreelancerId'];
              $freelancer->UserId = $result[$i]['UserId'];
              $freelancer->FirstName = $result[$i]['FirstName'];
              $freelancer->LastName = $result[$i]['LastName'];
              $freelancer->Email = $result[$i]['Email'];
              $freelancer->Username = $result[$i]['Username'];
              $freelancer->DOB = $result[$i]['DOB'];
              $freelancer->UserTypeId = $result[$i]['UserTypeId'];
              $freelancer->Degree = new Degree();
              $freelancer->Degree->Name = $result[$i]['Degree'];
              $freelancer->Major = $result[$i]['Major'];
              $freelancer->UniversityName = $result[$i]['UniversityName'];
              $freelancer->LastWork = $result[$i]['LastWork'];
              $freelancer->ExperienceYears = $result[$i]['ExperienceYears'];
              $freelancer->CV = $result[$i]['CV'];
              $freelancer->IdPicture = $result[$i]['IdPicture'];
              $job->Applicants[$k] = $freelancer;
            }
            $i--;
          }
          $jobs[$j] = $job;
        }
      }
      return $jobs;
    } catch (Exception $e) {
      return [];
    }
  }

  public static function GetApplied($freelancerId, $categoryId = 0, $orderBy = "CreationDate", $page = 1)
  {
    try {
      self::$db = new dbWrapper();

      $pageSize = 1;
      $pageLimit =  $condition  = "";
      if ($page > 0)
        $pageLimit = "LIMIT " . ($page - 1) * $pageSize . "," . $pageSize;
      if ($categoryId > 0)
        $condition = "AND CategoryId =" . $categoryId . " ";
      $sql = "SELECT j.*, s.Id as SkillId, s.Name, c.Name as Category,
              (SELECT COUNT(*) FROM `jobs` as j
                INNER JOIN `jobs-freelancers` as jf on j.Id = jf.JobId 
                WHERE FreelancerId = " . $freelancerId . " AND IsDeleted = 0 " . $condition . ") AS TotalRows
              from (SELECT j.* FROM  `jobs` as j
                    INNER JOIN `jobs-freelancers` as jf on j.Id = jf.JobId
                    WHERE FreelancerId = " . $freelancerId . " AND IsDeleted = 0 " . $condition . " 
                    ORDER  BY " . $orderBy . " DESC " . $pageLimit . ") as j
              inner join `categories` as c on j.CategoryId = c.Id
              left join `jobs-skills` as js on j.Id = js.JobId
              left join `skills` as s on js.SkillId = s.Id";

      $result = self::$db::queryAll($sql);
      $jobs = [];
      if ($result != null) {
        for ($i = 0, $j = 0; $i < count($result); $i++, $j++) {
          $job = new Job();
          $job->Id = $result[$i]['Id'];
          $job->RecruiterId = $result[$i]['RecruiterId'];
          $job->Title = $result[$i]['Title'];
          $job->Description = $result[$i]['Description'];
          $job->CategoryId = $result[$i]['CategoryId'];
          $job->Category= new Category();
          $job->Category->Name = $result[$i]['Category'];
          $job->Salary = $result[$i]['Salary'];
          $job->ImageUrl = $result[$i]['ImageUrl'];
          $job->CreationDate = $result[$i]['CreationDate'];
          $job->TotalRows = $result[$i]['TotalRows'];

          // fill skills array and consume result rows
          if ($result[$i]['SkillId'] > 0) {
            for ($k = 0; $i < count($result) && $job->Id == $result[$i]['Id']; $i++, $k++) {
              $skill = new Skill();
              $skill->Id = $result[$i]['SkillId'];
              $skill->Name = $result[$i]['Name'];
              $job->Skills[$k] = $skill;
            }
            $i--;
          }
          $jobs[$j] = $job;
        }
      }
      return $jobs;
    } catch (Exception $e) {
      return [];
    }
  }


  public static function Load($jobId)
  {
    try {
      self::$db = new dbWrapper();

      $sql = "SELECT j.*, s.Id as SkillId, s.Name from `jobs` as j
              left join `jobs-skills` as js on j.Id = js.JobId
              left join `skills` as s on js.SkillId = s.Id
              where j.Id = ? AND IsDeleted = 0";
      $result = self::$db::queryAll($sql, [$jobId]);
      $job = null;
      if ($result != null) {
        $job = new Job();
        $job->Id = $result[0]['Id'];
        $job->RecruiterId = $result[0]['RecruiterId'];
        $job->Title = $result[0]['Title'];
        $job->Description = $result[0]['Description'];
        $job->CategoryId = $result[0]['CategoryId'];
        $job->Salary = $result[0]['Salary'];
        $job->ImageUrl = $result[0]['ImageUrl'];
        $job->CreationDate = $result[0]['CreationDate'];

        // fill skills array 
        if ($result[0]['SkillId'] > 0) {
          for ($i = 0; $i < count($result); $i++) {
            $skill = new Skill();
            $skill->Id = $result[$i]['SkillId'];
            $skill->Name = $result[$i]['Name'];
            $job->Skills[$i] = $skill;
          }
        }
      }
      return $job;
    } catch (Exception $e) {
      return null;
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
          if ($this->Skills != null && count($this->Skills) > 0) {
            // insert skills
            $insert_values = array();
            foreach ($this->Skills as $skillId) {
              $question_marks[] =  '(?, ?)';
              $insert_values = array_merge($insert_values, [$this->Id, $skillId]);
            }
            $sql = "INSERT into `jobs-skills` (JobId, SkillId) values " . implode(',', $question_marks);
            return self::$db::query($sql, $insert_values);
          }
          $result = true;
        }
      }

      return $result;
    } catch (Exception $e) {
      return false;
    }
  }

  public static function Delete($Id)
  {
    try {
      self::$db = new dbWrapper();

      $sql = "UPDATE `jobs` SET IsDeleted = 1 WHERE Id = ? ";
      return self::$db::query($sql, [$Id]);
    } catch (Exception $e) {
      return false;
    }
  }
}
