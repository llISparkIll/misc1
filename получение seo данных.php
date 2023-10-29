<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

// AJAX data
// $IB=$_REQUEST["IB"];// 22
// $pageSize=$_REQUEST["pageSize"];// 5
// $pageNum=$_REQUEST["pageNum"];// 1
//$result=$_REQUEST;

CModule::IncludeModule('iblock');
//Elems
$IB = 31;
$arFilter = Array("IBLOCK_ID"=>$IB, "ACTIVE" => 'Y', "SECTION_ID"=>88, "INCLUDE_SUBSECTIONS"=>"Y");
// $arPager = Array("nPageSize"=>$pageSize, "iNumPage"=> $pageNum);
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "CODE");
$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);

$output = '$data = array(';

while($ob = $res->GetNextElement()){
	$arFields = $ob->GetFields();
	$ipropElementValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arFields["IBLOCK_ID"], $arFields["ID"]);
	$arElemValues = $ipropElementValues->getValues();
	$arElemValues1 = array($arElemValues["ELEMENT_META_TITLE"],$arElemValues["ELEMENT_META_KEYWORDS"],$arElemValues["ELEMENT_META_DESCRIPTION"],$arElemValues["ELEMENT_PAGE_TITLE"]);
	// $arElemValues = $ipropElementValues->findTemplates();
$output .= "'".json_encode($arElemValues1, JSON_UNESCAPED_UNICODE)."',<br>";
// echo '<pre>'; print_r($arElemValues1); echo '</pre>';
	
	// $modMark = '';
	// Logic >>>
	// $arCode = array("replace_space" => "-", "replace_other" => "-");
	// $code = Cutil::translit($arFields['NAME'], "ru", $arCode);
	// if($code != $arFields["CODE"]){
		// $el = new CIBlockElement;
		// $arData = Array("CODE" => $code);
		// $el->Update($arFields["ID"], $arData);
		// $modMark = ' Y';
	// }
	
	// $result["items"] .= $arFields["NAME"].'__'.$code.$modMark.'<br>';
	// Logic <<<
}
$output .= ');';
echo $output;
// $result["curPart"] = $res->NavPageNomer;
// $result["totalParts"] = $res->NavPageCount;

// echo json_encode($result);
?>