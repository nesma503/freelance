<?php
require_once "../db/dbWrapper.php";
require_once "../models/skill.php";

class Freelancer extends User
{
    public $Id;
    public $UserId;
    public $DegreeId;
    public $Degree;
    public $Major;
    public $UniversityName;
    public $LastWork;
    public $ExperienceYears;
    public $CV;
    public $IdPicture;
    public $Skills;
    private static $db;

    public function __construct()
    {
        $this->Id = 0;
    }

    public static function Create($object)
    {
        // Initializing class properties
        $freelancer = new Freelancer();
        foreach($object as $property => $value) {
            $freelancer->$property = $value;
        }
        return $freelancer;
    }

    public static function Load($user)
    {
        try {
            self::$db = new dbWrapper();

            $sql = "SELECT f.*, s.Id as SkillId, s.Name from `freelancers` f 
            left join `freelancers-skills` fs on f.Id = fs.FreelancerId 
            left join `skills` s on fs.SkillId = s.Id 
            where f.userId = ?";
            $result = self::$db::queryAll($sql, [$user->Id]);
            $freelancer = null;
            if ($result != null && count($result) > 0) {
                $freelancer = new Freelancer();
                $freelancer->FirstName = $user->FirstName;
                $freelancer->LastName = $user->LastName;
                $freelancer->Email = $user->Email;
                $freelancer->Username = $user->Username;
                $freelancer->DOB = $user->DOB;
                $freelancer->UserTypeId = $user->UserTypeId;
                $freelancer->CreationDate = $user->CreationDate;
                $freelancer->IsDeleted = $user->IsDeleted;
                $freelancer->Id = $result[0]['Id'];
                $freelancer->UserId = $result[0]['UserId'];
                $freelancer->DegreeId = $result[0]['DegreeId'];
                $freelancer->Major = $result[0]['Major'];
                $freelancer->UniversityName = $result[0]['UniversityName'];
                $freelancer->LastWork = $result[0]['LastWork'];
                $freelancer->ExperienceYears = $result[0]['ExperienceYears'];
                $freelancer->CV = $result[0]['CV'];
                $freelancer->IdPicture = $result[0]['IdPicture'];
                // fill skills array
                if ($result[0]['SkillId'] > 0) {
                    for ($i = 0; $i < count($result); $i++) {
                        $skill = new Skill();
                        $skill->Id = $result[$i]['SkillId'];
                        $skill->Name = $result[$i]['Name'];
                        $freelancer->Skills[$i] = $skill;
                    }
                }
            }
            return $freelancer;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function LoadByUserId($userId)
    {
        try {
            self::$db = new dbWrapper();

            $sql = "SELECT * from `freelancers` f 
            left join `freelancers-skills` as fs on f.Id = fs.FreelancerId 
            left join `skills` as s on fs.SkillId = s.Id 
            inner join `users` as u on f.UserId = u.Id 
            where f.UserId = ?";

            $result = self::$db::queryAll($sql, [$userId]);
            $freelancer = null;
            if ($result != null && count($result) > 0) {
                $freelancer = new Freelancer();
                $freelancer->Id = $result[0][0]; // freelancer Id
                $freelancer->UserId = $result[0]['UserId'];
                $freelancer->FirstName = $result[0]['FirstName'];
                $freelancer->LastName = $result[0]['LastName'];
                $freelancer->Email = $result[0]['Email'];
                $freelancer->Username = $result[0]['Username'];
                $freelancer->DOB = $result[0]['DOB'];
                $freelancer->UserTypeId = $result[0]['UserTypeId'];
                $freelancer->CreationDate = $result[0]['CreationDate'];
                $freelancer->IsDeleted = $result[0]['IsDeleted'];
                $freelancer->DegreeId = $result[0]['DegreeId'];
                $freelancer->Major = $result[0]['Major'];
                $freelancer->UniversityName = $result[0]['UniversityName'];
                $freelancer->LastWork = $result[0]['LastWork'];
                $freelancer->ExperienceYears = $result[0]['ExperienceYears'];
                $freelancer->CV = $result[0]['CV'];
                $freelancer->IdPicture = $result[0]['IdPicture'];
                if ($result[0]['SkillId'] > 0) {
                    for ($i = 0; $i < count($result); $i++) {
                        $skill = new Skill();
                        $skill->Id = $result[$i]['SkillId'];
                        $skill->Name = $result[$i]['Name'];
                        $freelancer->Skills[$i] = $skill;
                    }
                }
            }
            return $freelancer;
        } catch (Exception $e) {
            return null;
        }
    }

    public function Save($skillIds = [])
    {
        try {
            self::$db = new dbWrapper();
            $result = false;

            if ($this->Id == 0) {
                $sql = "INSERT into `freelancers` (UserId) values(?)";
                $id = self::$db::insert($sql, [$this->UserId]);
                if ($id > 0) {
                    $this->Id =  $id;
                    $result = true;
                }
            } else if ($this->Id > 0) {
                $sql = "UPDATE `freelancers` SET DegreeId=?, Major=?, UniversityName=?, LastWork=?, ExperienceYears=? , CV = ?, IdPicture = ? WHERE Id=?";
                $result = self::$db::query($sql, [$this->DegreeId, $this->Major, $this->UniversityName, $this->LastWork, $this->ExperienceYears, $this->CV, $this->IdPicture, $this->Id]);
                if ($result && count($skillIds) > 0) {
                    $sql = "DELETE from `freelancers-skills` WHERE FreelancerId=?";
                    $result = self::$db::query($sql, [$this->Id]);
                    if ($result) {
                        $insert_values = array();
                        foreach ($skillIds as $skillId) {
                            $question_marks[] =  '(?, ?)';
                            $insert_values = array_merge($insert_values, [$this->Id, $skillId]);
                        }
                        $sql = "INSERT into `freelancers-skills` (FreelancerId, SkillId) values " . implode(',', $question_marks);
                        $result = self::$db::query($sql, $insert_values);
                    }
                }
            }

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function ApplyJob($jobId)
    {
        try {
            self::$db = new dbWrapper();
            $result = false;

            $sql = "INSERT into `jobs-freelancers` (FreelancerId, JobId) values(?, ?)";
            $id = self::$db::insert($sql, [$this->Id, $jobId]);
            if ($id > 0)
                $result = true;

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

}
