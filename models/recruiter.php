<?php
require_once "../db/dbWrapper.php";

class Recruiter extends User
{
    public $Id;
    public $UserId;
    private static $db;

    public function __construct()
    {
        $this->Id = 0;
    }

    public static function Create($object)
    {
        // Initializing class properties
        $recruiter = new Recruiter();
        foreach($object as $property => $value) {
            $recruiter->$property = $value;
        }
        return $recruiter;
    }

    public static function Load($user)
    {
        try {
            self::$db = new dbWrapper();

            $sql = "select * from recruiters where userId = ?";
            $result = self::$db::queryOne($sql, [$user->Id]);
            $recruiter = null;
            if ($result != null) {
                $recruiter = new Recruiter();
                $recruiter->Id = $result['Id'];
                $recruiter->UserId = $result['UserId'];
                $recruiter->FirstName = $user->FirstName;
                $recruiter->LastName = $user->LastName;
                $recruiter->Email = $user->Email;
                $recruiter->Username = $user->Username;
                $recruiter->DOB = $user->DOB;
                $recruiter->UserTypeId = $user->UserTypeId;
                $recruiter->CreationDate = $user->CreationDate;
                $recruiter->IsDeleted = $user->IsDeleted;
            }
            return $recruiter;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function LoadByUserId($userId)
    {
        try {
            self::$db = new dbWrapper();

            $sql = "select * from recruiters r inner join users u on r.UserId = u.Id where r.userId = ?";
            $result = self::$db::queryOne($sql, [$userId]);
            $recruiter = null;
            if ($result != null) {
                $recruiter = new Recruiter();
                $recruiter->Id = $result[0]; // recruiter Id
                $recruiter->UserId = $result['UserId'];
                $recruiter->FirstName = $result['FirstName'];
                $recruiter->LastName = $result['LastName'];
                $recruiter->Email = $result['Email'];
                $recruiter->Username = $result['Username'];
                $recruiter->DOB = $result['DOB'];
                $recruiter->UserTypeId = $result['UserTypeId'];
                $recruiter->CreationDate = $result['CreationDate'];
                $recruiter->IsDeleted = $result['IsDeleted'];
            }
            return $recruiter;
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
                $sql = "insert into recruiters(UserId) values(?)";
                $id = self::$db::insert($sql, [$this->UserId]);
                if ($id > 0) {
                    $this->Id =  $id;
                    $result = true;
                }
            }

            return $result;
        } catch (Exception $e) {
            return $result;
        }
    }
}
