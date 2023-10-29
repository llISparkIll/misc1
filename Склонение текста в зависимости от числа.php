<?
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

if(str_contains($count, "11") || str_contains($count, "12") || str_contains($count, "13") || str_contains($count, "14")){
	$count = "Найдено ".$count." объектов";
}elseif(substr($count, -1) == "1"){
	$count = "Найден ".$count." объект";
}elseif(substr($count, -1) == "2" || substr($count, -1) == "3" || substr($count, -1) == "4"){
	$count = "Найдено ".$count." объекта";
}else{
	$count = "Найдено ".$count." объектов";
}