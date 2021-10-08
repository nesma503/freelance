<?php

/*
 *       _____ _____ _____                _       _
 *      |_   _/  __ \_   _|              (_)     | |
 *        | | | /  \/ | |  ___  ___   ___ _  __ _| |
 *        | | ||      | | / __|/ _ \ / __| |/ _` | |
 *       _| |_| \__/\ | |_\__ \ (_) | (__| | (_| | |
 *      |_____\_____/ |_(_)___/\___/ \___|_|\__,_|_|
 *                   ___
 *                  |  _|___ ___ ___
 *                  |  _|  _| -_| -_|  LICENCE
 *                  |_| |_| |___|___|
 *
 * IT NEWS  <>  PROGRAMMING  <>  HW & SW  <>  COMMUNITY
 *
 * This source code is part of online courses at IT social
 * network WWW.ICT.SOCIAL
 *
 * Feel free to use it for whatever you want, modify it and share it but
 * don't forget to keep this link in code.
 *
 * For more information visit http://www.ict.social/licences
 */

/**
 * A simple database wrapper over the PDO class
 * Class dbWrapper
 */

require_once "config.php";

class dbWrapper
{
	/**
	 * @var array The default driver settings
	 */
	private static $settings = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
		PDO::ATTR_EMULATE_PREPARES => false,
	);
	/**
	 * @var PDO A database connection
	 */
	private static $connection;

	/**
	 * Connects to the database 
	 */
	public function __construct()
	{
		if (!isset(self::$connection)) {
			try {
				// Data source name
				$dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME;
				self::$connection = @new PDO(
					$dsn,
					DB_USERNAME,
					DB_PASSWORD,
					self::$settings
				);
			} catch (Exception $e) {
				die("ERROR: Could not connect. " . $e->getMessage());
			}
		}
	}

	/**
	 * Executes a query and returns the first row of the result
	 * @param string $query The query
	 * @param array $params Parameters to be passed into the query
	 * @return mixed An associative array representing the row or false in no data returned
	 */
	public static function queryOne($query, $params = array()) //select single row
	{
		try {
			$result = self::$connection->prepare($query);
			$result->execute($params);
			return $result->fetch();
		} catch (Exception $e) {
			return null;
		}
	}

	/**
	 * Executes a query and returns all resulting rows as an array of associative arrays
	 * @param string $query The query
	 * @param array $params Parameters to be passed into the query
	 * @return mixed An array of associative arrays or false in no data returned
	 */
	public static function queryAll($query, $params = array()) //select all rows
	{
		try {
			$result = self::$connection->prepare($query);
			$result->execute($params);
			return $result->fetchAll();
		} catch (Exception $e) {
			return null;
		}
	}

	/**
	 * Executes a query and returns the number of affected rows
	 * @param string $query The query
	 * @param array $params Parameters to be passed into the query
	 * @return int The number of affected rows
	 */
	public static function query($query, $params = array()) //update and delete
	{
		try {
			$result = self::$connection->prepare($query);
			return $result->execute($params);
		} catch (Exception $e) {
			return false;
		}
	}
	
	/**
	 * Executes a query and returns the inserted ID
	 * @param string $query The query
	 * @param array $params Parameters to be passed into the query
	 * @return int The inserted ID
	 */
	public static function insert($query, $params = array())
	{
		try {
			$result = self::$connection->prepare($query);
			$result->execute($params);
			return self::$connection->lastInsertId();
		} catch (Exception $e) {
			return 0;
		}
	}
}
