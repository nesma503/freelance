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
    private $db;

    public static function GetUser($username, $password)
    {
        try {
            if (self::$db ==  null)
                self::$db = new dbWrapper();

            $sql = "select * from users where username= ? and password = ?";
            $result = self::$db::queryOne($sql, [$username, $password]);

            if($result != null)
            {
                $user = new User();
                $user->ID = $result['id'];
                $user->Username = $username;
                $user->FirtName = $result['firstName'];
            }
            return $user;
        } catch (Exception $e) {
            return null;
        }
    }
}
