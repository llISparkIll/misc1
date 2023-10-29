<?
$dir = getcwd().'/';

$cList = scandir($dir);
$allSize = 0;
//print_r($cList);
foreach($cList as $item){
	if(strpos($item, '.') !== false) continue;
	if(strpos($item, 'bitrix') !== false) continue;
	if(strpos($item, 'upload') !== false) continue;
	//	echo $item.PHP_EOL;
	////
$it = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator($dir.$item, FilesystemIterator::SKIP_DOTS)
);

$size = 0;
foreach ($it as $fi){
//print_r($fi);
	$size += $fi->getSize();
}
echo $item.' '.round($size/(1024*1024), 3).' Мб<br>';
$allSize += $size;
}
echo 'allSize: '.round($allSize/(1024*1024), 3).' Мб<br>';
?>
<?// formatted
echo '<table border="1" style="border-spacing: 1;">';
$dir = getcwd().'/';

$cList = scandir($dir);
$allSize = 0;
//print_r($cList);
foreach($cList as $item){
	if(strpos($item, '.') !== false) continue;
	if(strpos($item, 'bitrix') !== false) continue;
	if(strpos($item, 'upload') !== false) continue;
	//	echo $item.PHP_EOL;
	////
$it = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator($dir.$item, FilesystemIterator::SKIP_DOTS)
);

$size = 0;
foreach ($it as $fi){
//print_r($fi);
	$size += $fi->getSize();
}
	echo '<tr><td>'.$item.'</td><td>'.round($size/(1024*1024), 3).' mb</td><tr>';
$allSize += $size;
}
echo '</table><br>allSize: '.round($allSize/(1024*1024), 3).' mb<br>';
?>