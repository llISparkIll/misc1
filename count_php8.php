<?
	function count_php8($value){
		if(is_array($value))
			return count($value);
		else
			return 0;
	}
?>