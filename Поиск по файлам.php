<?
$dir = "/var/www/u0792533/data/vincci.ru/public/upload/catalog_export_files/";
$name = "0c4fe4f01ad30016925beeeb902582c6.webp";
$rs = find_file($dir, $name);

// Открыть известный каталог и начать считывать его содержимое
function find_file($dir, $name){
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if( $file == '.' || $file == '..' ) continue;
				$sub_dir = $dir . $file;
				if( is_file($sub_dir) ){
					if($file == $name){
						return $file;
					}
				}
				else{
					$sub_dir .= "/";
					if( is_dir($sub_dir) ){
	
						$res = find_file( $sub_dir, $name );
					}
				}
			}
			closedir($dh);
		}
	}
}
?>