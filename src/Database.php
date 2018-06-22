<?php
namespace Poncho;
use \PDO;
/**
 * summary
 */

class Database
{
    /**
     * summary
     */
    private $db;
    public function __construct($dbname = 'rss', $host = 'localhost', $login = 'root', $psw = '')
    {
    	try {
	        $this->db = new PDO("mysql:dbname=$dbname;host=$host;charset=utf8", $login, $psw);
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    		
    	} catch (PDOException $e) {
    		die('<span style="color:white">Erreur :  : ' . $e->getMessage()) . '</span>';
    	}
    	return $this->db;
    }

	public function getDatabase(){
		if (!$this->db) {
			$this->db = new Database();
		}
		return $this->db;
	}
}