<?// V6 generation simcode from h1 (seo)
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("simpoClass", "reTranslateName"));
AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("simpoClass", "reTranslateName"));
AddEventHandler("iblock", "OnAfterIBlockSectionUpdate", Array("simpoClass", "reTranslateNameSect"));
AddEventHandler("iblock", "OnAfterIBlockSectionAdd", Array("simpoClass", "reTranslateNameSect"));
class simpoClass{
public static $arIB = array(6,19,20,23);
public static $param = array("replace_space"=>"-","replace_other"=>"-");

function reTranslateName(&$arFields){
	$tgtIB = in_array($arFields["IBLOCK_ID"], self::$arIB);

	if(!$tgtIB)
	return;

	$ipropElementValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arFields["IBLOCK_ID"], $arFields["ID"]);
	$arElemValues = $ipropElementValues->getValues();
	$elemH1 = htmlspecialchars_decode($arElemValues["ELEMENT_PAGE_TITLE"]);

	if($elemH1 == '')
	return;

	$oldCode = $arFields["CODE"];
	$newCode = Cutil::translit($elemH1,"ru", self::$param);

	if($oldCode == ''){// if not isset in cur save
		$res = CIBlockElement::GetByID($arFields["ID"]);
		if($arElem = $res->GetNext())
		$oldCode = $arElem["CODE"];
	}

	if($newCode != '' && $newCode != $oldCode){
		// check for duplicates
		$arFilterSub = array("IBLOCK_ID"=>$arFields["IBLOCK_ID"], "CODE" => $newCode);
		$resSub = CIBlockElement::GetList(array(), $arFilterSub, false, false, array('ID'));
		if($obSub = $resSub->GetNextElement()){
			$arFieldsSub = $obSub->GetFields();
			$newCode.='-'.$arFields['ID'];
		}

		if($newCode != '' && $newCode != $oldCode){
			$el = new CIBlockElement;
			$arData = Array("CODE" => $newCode);
			$el->Update($arFields["ID"], $arData);
		}
	}
}

function reTranslateNameSect(&$arFields){
	$tgtIB = in_array($arFields["IBLOCK_ID"], self::$arIB);

	if(!$tgtIB)
	return;

	$ipropSectValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arFields["IBLOCK_ID"], $arFields["ID"]);
	$arSectValues = $ipropSectValues->getValues();
	$sectH1 = htmlspecialchars_decode($arSectValues["SECTION_PAGE_TITLE"]);

	if($sectH1 == '')
	return;

	$oldCode = $arFields["CODE"];
	$newCode = Cutil::translit($sectH1,"ru", self::$param);

	if($oldCode == ''){// if not isset in cur save
		$res = CIBlockSection::GetByID($arFields["ID"]);
		if($arSect = $res->GetNext())
		$oldCode = $arSect["CODE"];
	}

	if($newCode != '' && $newCode != $oldCode){
		// check for duplicates
		$arFilterSub = array("IBLOCK_ID"=>$arFields["IBLOCK_ID"], "CODE" => $newCode);
		$resSub = CIBlockSection::GetList(Array(), $arFilterSub, false, array('ID'), false);
		if($obSub = $resSub->GetNextElement()){
			$arFieldsSub = $obSub->GetFields();
			$newCode.='-'.$arFields['ID'];
		}

		if($newCode != '' && $newCode != $oldCode){
			$bs = new CIBlockSection;
			$arData = Array("CODE" => $newCode);
			$bs->Update($arFields["ID"], $arData);
		}
	}
}
}?>