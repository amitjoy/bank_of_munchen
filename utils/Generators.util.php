<?php

require_once "RandomAccNoGenerator.util.php";
require_once "../../classes/DB.class.php";
ini_set('precision', 17);

class Generators {

	/**
	 * Generates Random Password of given length
	 * @param unknown_type $length Password Length
	 */
	public static function randomPasswordGenerate ($length) {
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array();
		$alphaLength = strlen($alphabet) - 1;
		for ($i = 0; $i < $length; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass);
	}
	
	/**
	 * Generates Unique id
	 * @param unknown_type $userId
	 */
	public static function generateuniqueId ($id) {
		return md5(uniqid($id, true));
	}
	
	/**
	 * Generates Unique Random Account No
	 */
	public static function generateUniqueAccountNo () {
		$customAlphabet = '0123456789';
		
		// Set initial alphabet.
		$generator = new RandomStringGenerator($customAlphabet);
		
		// Change alphabet whenever needed.
		$generator->setAlphabet($customAlphabet);
		
		// Set token length.
		$tokenLength = 14;
		
		// Call method to generate random string.
		$token = $generator->generate($tokenLength);
		
		return $token;
	}
	/**
	* Generate Unique TAN No to be stored in the DB
	*/
	private static function generateTAN () {

		$db = DB::getInstance();
		$db->connect();
		$randomNumber = 0;

		while(1) {
		    // generate unique random number
		    $randomNumber = rand(0, 999999999999999);
		    // check if it exists in database
		    $query = "SELECT * FROM TANS WHERE no=$randomNumber";
		    $res = mysql_query($query);
		    $rowCount = mysql_num_rows($res);
		    // if not found in the db (it is unique), break out of the loop
		    if($rowCount < 1) {
		        break;
		    }
		}
		return $randomNumber; 
	}

	public static function generateTANs ($limit) {

		$tanArray = array();

		for ($i = 0; $i < $limit; $i++) { 
			array_push($tanArray, self::generateTAN());
		}

		return $tanArray;

	}

	/**
	* Used to generate transaction ids
	*/
	public static function generateTAN_Old ($limit) {

		$db = DB::getInstance();
		$db->connect();

		$tanArray = array();

		$startTANdefaultNo = 456928631837232;

		$query = "SELECT * FROM TANS ORDER BY no DESC LIMIT 1";
		
		$result = mysql_query($query);

		if (mysql_num_rows($result) == 1) {
			$row = mysql_fetch_object($result);

			$startTAN = $row->no + 1;

			for ($i = $startTAN; $i < $startTAN + $limit; $i++) { 
				array_push($tanArray, $i);
			}

			return $tanArray;
		}

		for ($i = $startTANdefaultNo; $i < $startTANdefaultNo + $limit; $i++) { 
			array_push($tanArray, $i);
		}

		return $tanArray;
	}
	
	function trunc($float, $prec = 2) {
		return substr(round($float, $prec+1), 0, -1);
	}
}
