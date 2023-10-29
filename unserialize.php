$array = array();
$i=0;
while($i<991){
$arUnSer = unserialize($array[$i]);
$i++;
echo implode($arUnSer, '|').PHP_EOL;
}