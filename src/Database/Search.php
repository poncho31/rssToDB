<?php 
//SELECT
namespace Poncho\Database;
include('vendor/autoload.php');
use Poncho\HTML\HTML;
use Poncho\Database\Database;
class Search extends Database
{
    
    public function __construct()
    {
        parent::__construct();
    }
    public function searchHTML($action, $inputName, $inputValue, $selectName, $columnName, $submitName){
        return HTML::form($action, 'POST', $inputName, $inputValue, $selectName, $this->db, $columnName, $submitName);
    }
}