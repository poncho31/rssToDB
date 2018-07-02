<?php
namespace Poncho\Database;
use \PDO;
/**
 * summary
 */

class Database
{
    /**
     * summary
     */
    private $config;
    private $db;
    public function __construct($dbname = 'rss', $host = 'localhost', $login = 'root', $psw = '')
    {
        
        $this->getINIConfig();
        if (!$this->db) 
        {
        	try {
    	        $this->db = new PDO("mysql:dbname=".$this->config['dbname'].";host=".$this->config['host'].";charset=utf8", $this->config['login'], $this->config['pswd']);
    			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    			$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        		
        	} catch (PDOException $e) {
                //CLASS ERROR
        		die('<span style="color:white">Erreur :  : ' . $e->getMessage()) . '</span>';
        	}
        }
        $this->db;
    }

	// public function getDatabase(){
	// 	if (!$this->db) {
	// 		$this->db = new Database();
	// 	}
	// 	return $this->db;
	// }

    public function getQuery($sql, $param = false){
        if ($param) {
            $stmt = $this->db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); //ATTR_CURSOR is the default attribute
            $stmt->execute($param);
        }
        else{
            $stmt = $this->db->query($sql);
        }
        return $stmt;
    }

    private function saveDB(){

    }

    private function getINIConfig(){
        $iniArrayDB = parse_ini_file('config/config.ini', true);
        $this->config = $iniArrayDB['database'];
        return $this->config;
    }
}

//SELECT
class Search extends Database
{
    
    function __construct()
    {
        # code...
    }


}

//INSERT
class InsertRSS extends Database
{
    
    function __construct()
    {
        # code...
    }
    
}

//SELECT + INSERT in another Table 
//
class PreparedData extends Database
{
    
    function __construct()
    {
        # code...
    }
    
}

// UPDATE + PATCH 
class Update extends Database
{
    
    function __construct()
    {
        # code...
    }
    
}