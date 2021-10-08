<?php
require_once "../db/dbWrapper.php";

class User
{
    public $Id;
    public $FirstName;
    public $LastName;
    public $Email;
    public $Username;
    public $Password;
    public $DOB;
    public $UserTypeId;
    public $CreationDate;
    public $IsDeleted;
    private static $db = null;

    public function __construct()
    {
        $this->Id = 0;
    }

    public static function LoadyByUsername($username)
    {
        try {
            self::$db = new dbWrapper();

            $sql = "select * from users where username= ? and IsDeleted = 0";
            $result = self::$db::queryOne($sql, [$username]);
            $user = null;
            if ($result != null) {
                $user = new User();
                $user->Id = $result['Id'];
                $user->FirstName = $result['FirstName'];
                $user->LastName = $result['LastName'];
                $user->Email = $result['Email'];
                $user->Username = $result['Username'];
                $user->Password = $result['Password'];
                $user->DOB = $result['DOB'];
                $user->UserTypeId = $result['UserTypeId'];
                $user->CreationDate = $result['CreationDate'];
                $user->IsDeleted = $result['IsDeleted'];
            }
            return $user;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function LoadyByEmail($email)
    {
        try {
            self::$db = new dbWrapper();

            $sql = "select Id,Username from users where email= ? and IsDeleted = 0";
            $result = self::$db::queryOne($sql, [$email]);
            $user = null;
            if ($result != null) {
                $user = new User();
                $user->Id = $result['Id'];
                $user->Username = $result['Username'];
            }
            return $user;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function ValidateUsername($username, $userId = 0)
    {
        try {
            self::$db = new dbWrapper();
            $result = null;

            if ($userId == 0) {
                $sql = "select Username from users where username= ?";
                $result = self::$db::queryOne($sql, [$username]);
            } else if ($userId > 0) {
                $sql = "SELECT * FROM users WHERE username= ? where userId <> ?";
                $result = self::$db::queryOne($sql, [$username, $userId]);
            }
            if ($result == null)
                return true;
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function ValidateEmail($email, $userId = 0)
    {
        try {
            self::$db = new dbWrapper();
            $result = null;

            if ($userId == 0) {
                $sql = "select Email from users where email= ?";
                $result = self::$db::queryOne($sql, [$email]);
            } else if ($userId > 0) {
                $sql = "SELECT * FROM users WHERE email= ? where userId <> ?";
                $result = self::$db::queryOne($sql, [$email, $userId]);
            }
            if ($result == null)
                return true;
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function Save()
    {
        try {
            self::$db = new dbWrapper();
            $result = false;

            if ($this->Id == 0) {
                $sql = "insert into users(FirstName, LastName, Username, Email, Password, DOB, UserTypeId) values(?,?,?,?,?,?,?)";
                $id = self::$db::insert($sql, [$this->FirstName, $this->LastName, $this->Username, $this->Email, $this->Password, $this->DOB, $this->UserTypeId]);
                if ($id > 0) {
                    $this->Id =  $id;
                    $result = true;
                }
            } else if ($this->Id > 0) {
                $sql = "update users set FirstName= ?, LastName = ?, Username = ?, Email = ?, DOB = ? where Id = ?";
                $result = self::$db::query($sql, [$this->FirstName, $this->LastName, $this->Username, $this->Email, $this->DOB, $this->Id]);
            }
            return $result;
        } catch (Exception $e) {
            return $result;
        }
    }

    public function ChangePassword()
    {
        try {
            self::$db = new dbWrapper();

            if ($this->Id > 0) {
                $sql = "update users set Password = ? where Id = ?";
                return self::$db::query($sql, [$this->Password, $this->Id]);
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public static function Delete($userId)
    {
        try {
            self::$db = new dbWrapper();

            if ($userId > 0) {
                $sql = "update users set IsDeleted = 1 where Id = ?";
                return self::$db::query($sql, [$userId]);
            }
        } catch (Exception $e) {
            return false;
        }
    }
}
