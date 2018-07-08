<?php 
namespace Poncho\HTML;
/**
 * 
 */
class HTML
{
	
	public function __construct()
	{
		# code...
	}
	public static function form($action, $method, $inputName, $inputValue, $selectName, $rows, $columnName, $submitName){
		$form = "<form action='".$action."' method='".$method."'>";
		$form .= "<input type='text' name='".$inputName."' value='".$inputValue."'>";
		$form .= "<select name='".$selectName."'>";
		$form .= "<option value >Toutes les categories</option>";
		foreach ($rows as $row) {
			if (isset($_REQUEST[$columnName]) && $_REQUEST[$columnName] == $row[$columnName]) {
				$form .= '<option value="'.$_REQUEST[$columnName].'" selected >"'.$row[$columnName].'"</option>';
			}
			else{
				$form .= '<option value="'.$row[$columnName].'">'.$row[$columnName].'"</option>';
			}
		}
		$form .= "</select>";
		$form .= "<input type='submit' name='".$submitName."'>";
		$form .= "</form>";
		return $form;
	}
}