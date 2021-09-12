<?php
require_once "../db/dbWrapper.php";

class Recruiter
{
    public $ID;
    public $UserID;
    private $db;

    public static function GetRecruiter($userId)
    {
        try {
            if (self::$db ==  null)
                self::$db = new dbWrapper();

            $sql = "select * from recruiters where userId = ?";
            $result = self::$db::queryOne($sql, [$userId]);

            if($result != null)
            {
                $recruiter = new Recruiter();
                $recruiter->ID = $result['id'];
                $recruiter->FirtName = $result['firstName'];
            }
            return $recruiter;
        } catch (Exception $e) {
            return null;
        }
    }
}
