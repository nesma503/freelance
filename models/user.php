<?php
require_once "../db/dbWrapper.php";

class User
{
    public $ID;
    public $FirtName;
    public $LastName;
    public $Email;
    public $Username;
    public $Password;
    public $DOB;
    public $CreationDate;
    public $IsDeleted;
    private static $db = null;

    public static function GetUser($username, $password)
    {
        try {
            if (self::$db ==  null)
                self::$db = new dbWrapper();

            $sql = "select * from users where username= ? and IsDeleted = 0";
            $result = self::$db::queryOne($sql, [$username]);

            if($result != null)
            {
                $user = new User();
                $user->ID = $result['Id'];
                $user->Username = $username;
                $user->FirtName = $result['FirstName'];
            }
            return $user;
        } catch (Exception $e) {
            return null;
        }
    }


    public static function UpdateUser()
    {
    }
}
