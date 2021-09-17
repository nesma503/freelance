<?php
require_once "../db/dbWrapper.php";

class Freelancer extends User
{
    public $Id;
    public $UserId;
    public $DegreeId;
    public $Major;
    public $UniversityName;
    public $LastWork;
    public $ExperienceYears;
    public $CV;
    public $IdPicture;
    private static $db;

    public function __construct()
    {
        $this->Id = 0;
    }

    public static function Load($user)
    {
        try {
            self::$db = new dbWrapper();

            $sql = "select * from freelancers where userId = ?";
            $result = self::$db::queryOne($sql, [$user->Id]);
            $freelancer = null;
            if ($result != null) {
                $freelancer = new Freelancer();
                $freelancer->Id = $result['Id'];
                $freelancer->UserId = $result['UserId'];
                $freelancer->DegreeId = $result['DegreeId'];
                $freelancer->Major = $result['Major'];
                $freelancer->UniversityName = $result['UniversityName'];
                $freelancer->LastWork = $result['LastWork'];
                $freelancer->ExperienceYears = $result['ExperienceYears'];
                $freelancer->CV = $result['CV'];
                $freelancer->IdPicture = $result['IdPicture'];
                $freelancer->FirstName = $user->FirstName;
                $freelancer->LastName = $user->LastName;
                $freelancer->Email = $user->Email;
                $freelancer->Username = $user->Username;
                $freelancer->DOB = $user->DOB;
                $freelancer->UserTypeId = $user->UserTypeId;
                $freelancer->CreationDate = $user->CreationDate;
                $freelancer->IsDeleted = $user->IsDeleted;
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

            $sql = "SELECT * from freelancers f inner join users u on f.UserId = u.Id where f.UserId = ?";
            $result = self::$db::queryOne($sql, [$userId]);
            $freelancer = null;
            if ($result != null) {
                $freelancer = new Freelancer();
                $freelancer->Id = $result[0]; // freelancer Id
                $freelancer->UserId = $result['UserId'];
                $freelancer->FirstName = $result['FirstName'];
                $freelancer->LastName = $result['LastName'];
                $freelancer->Email = $result['Email'];
                $freelancer->Username = $result['Username'];
                $freelancer->DOB = $result['DOB'];
                $freelancer->UserTypeId = $result['UserTypeId'];
                $freelancer->CreationDate = $result['CreationDate'];
                $freelancer->IsDeleted = $result['IsDeleted'];
                $freelancer->DegreeId = $result['DegreeId'];
                $freelancer->Major = $result['Major'];
                $freelancer->UniversityName = $result['UniversityName'];
                $freelancer->LastWork = $result['LastWork'];
                $freelancer->ExperienceYears = $result['ExperienceYears'];
                $freelancer->CV = $result['CV'];
                $freelancer->IdPicture = $result['IdPicture'];

            }
            return $freelancer;
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
                $sql = "INSERT into freelancers (UserId) values(?)";
                $id = self::$db::insert($sql, [$this->UserId]);
                if ($id > 0) {
                    $this->Id =  $id;
                    $result = true;
                }
            } else if ($this->Id > 0) {
                $sql = "UPDATE freelancers SET DegreeId=?, Major=?, UniversityName=?, LastWork=?, ExperienceYears=? , CV = ?, IdPicture = ? WHERE Id=?";
                $result = self::$db::query($sql, [$this->DegreeId, $this->Major, $this->UniversityName, $this->LastWork, $this->ExperienceYears, $this->CV, $this->IdPicture, $this->Id]);
            }

            return $result;
        } catch (Exception $e) {
            return $result;
        }
    }
}
