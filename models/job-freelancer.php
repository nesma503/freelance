<?php
require_once "../db/dbWrapper.php";

class JobFreelancer
{
    public $Id;
    public $FreelancerId;
    public $JobId;
    private static $db;

    public static function Load($freelancerId, $jobId)
    {
        try {
            self::$db = new dbWrapper();
            $jobFreelancer = null;

            $sql = "SELECT * FROM `jobs-freelancers` WHERE FreelancerId = ? AND JobId = ? ";
            $result = self::$db::queryOne($sql, [$freelancerId, $jobId]);
            if ($result != null) {
                $jobFreelancer = new JobFreelancer();
                $jobFreelancer->Id = $result['Id'];
                $jobFreelancer->JobId = $result['JobId'];
                $jobFreelancer->FreelancerId = $result['FreelancerId'];
            }

            return $jobFreelancer;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function Delete($Id)
    {
        try {
            self::$db = new dbWrapper();

            $sql = "DELETE FROM `jobs-freelancers` WHERE Id = ? ";
            return self::$db::query($sql, [$Id]);
 
        } catch (Exception $e) {
            return false;
        }
    }
}
