<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

// AJAX data
$IB=$_REQUEST["IB"];// 22
$pageSize=$_REQUEST["pageSize"];// 5
$pageNum=$_REQUEST["pageNum"];// 1
//$result=$_REQUEST;

CModule::IncludeModule('iblock');
//Elems
$arFilter = Array("IBLOCK_ID"=>$IB, "ACTIVE" => 'Y');
$arPager = Array("nPageSize"=>$pageSize, "iNumPage"=> $pageNum);
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "CODE");
$res = CIBlockElement::GetList(array(), $arFilter, false, $arPager, $arSelect);
while($ob = $res->GetNextElement()){
	$arFields = $ob->GetFields();
	$modMark = '';

	// Logic >>>
	$arCode = array("replace_space" => "-", "replace_other" => "-");
	$code = Cutil::translit($arFields['NAME'], "ru", $arCode);
	
	if($code != $arFields["CODE"]){
		$el = new CIBlockElement;
		$arData = Array("CODE" => $code);
		$el->Update($arFields["ID"], $arData);
		$modMark = ' Y';
	}
	
	$result["items"] .= $arFields["NAME"].'__'.$code.$modMark.'<br>';
	// Logic <<<
}

// Sections
/*
$arFilter = Array("IBLOCK_ID"=> $IB);
$arPager = Array("nPageSize"=>$pageSize, "iNumPage"=> $pageNum);
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "XML_ID", "CODE");
$res = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect, $arPager);
while($arFields = $res->GetNext()){

	// Logic >>>
	$arCode = array("replace_space" => "-", "replace_other" => "-");
	$code = Cutil::translit($arFields['NAME'], "ru", $arCode);
	
	if($code != $arFields["CODE"]){
		$bs = new CIBlockSection;
		$arData = Array("CODE" => $code);
		$bs->Update($arFields["ID"], $arData);
	}
	
	$result["items"] .= $arFields["NAME"].'__'.$code.'<br>';
	// Logic <<<
}
*/

//
$result["curPart"] = $res->NavPageNomer;
$result["totalParts"] = $res->NavPageCount;

echo json_encode($result);
?>