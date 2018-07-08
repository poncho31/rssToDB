<?php 
//SELECT
namespace Poncho\Database;
include('vendor/autoload.php');
use Poncho\HTML\HTML;
use Poncho\Database\Database;
use \PDO;
class Search extends Database
{
    
    public function __construct()
    {
        parent::__construct();
    }
    private function returnGetQuery($sql){
    	$stmt = $this->getQuery($sql);
		$stmt->execute();
		return $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHTML_form($action, $inputName, $inputValue, $selectName, $columnName, $submitName, $sql){
    	$row = $this->returnGetQuery($sql);
        return HTML::form($action, 'POST', $inputName, $inputValue, $selectName, $row, $columnName, $submitName);

    }
    public function getHTML_table($sql){
    	$row = $this->returnGetQuery($sql);
    	return HTML::table($row, 'nom', 'categorie', 'date', 'lien', 'titre', 'description');
    }
    //Display 20 item db column with next and previous  
    public function getSELECT($limit, $entry, $category){
    	$limit = isset($_REQUEST[$limit])? $_REQUEST[$limit] : 0;
		$next = $limit + 20;
		$previous = ($limit == 0)? 0: $limit - 20;
		$entry = isset($_REQUEST[$entry]) ? $_REQUEST[$entry] : ''; 
		$category = isset($_REQUEST[$category]) ? $_REQUEST[$category]: '';

    }
}