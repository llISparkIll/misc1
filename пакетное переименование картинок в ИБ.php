<?
CModule::IncludeModule('iblock');

$arFilter = Array("IBLOCK_ID"=>20, "ACTIVE" => "Y");
$arSelect = Array();
$res = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement()){
	$arFields = $ob->GetFields();
	// print_r($arFields["ID"]);
	// $preview_picture = CFile::GetFileArray($arFields["PREVIEW_PICTURE"]);
	$detail_picture= CFile::GetFileArray($arFields["DETAIL_PICTURE"]);
	// print_r($preview_picture);
	$oldName = explode(".", $detail_picture['FILE_NAME']);
	$ext = $oldName[count($oldName)-1];
	unset($oldName[count($oldName)-1]);
	$oldName = implode(" ", $oldName);
	// print_r($oldName);
	$rename= array("replace_space" => "-", "replace_other" => "-");
	$newName = Cutil::translit($oldName, "ru", $rename);
	if($newName != $oldName){
		// print_r($newName);
		//$oldName = iconv("UTF-8", "Windows-1251", $oldName);
		$dir = explode("/", $detail_picture['SRC']);
		unset($dir[count($dir)-1]);
		$dir = implode("/", $dir);
		$dir .= "/";
		$oldDir = $_SERVER["DOCUMENT_ROOT"] .$dir . $oldName . "." . $ext;
		$oldDir = iconv('WINDOWS-1251', 'UTF-8', $oldDir);
		$newDir = $_SERVER["DOCUMENT_ROOT"] . $dir . $newName . "." . $ext;
		$newDir = iconv('WINDOWS-1251', 'UTF-8', $newDir);
		if(rename($oldDir, $newDir)){
			$connection = Bitrix\Main\Application::getConnection();
			$sqlHelper = $connection->getSqlHelper();
			$sql = "UPDATE b_file SET FILE_NAME = '" . $newName . "." . $ext . "' WHERE ID = '".$arFields["DETAIL_PICTURE"]."'";
			$recordset = $connection->query($sql);
			$new_file = CFile::GetFileArray($arFields["DETAIL_PICTURE"]);
		}
	}
}
?>