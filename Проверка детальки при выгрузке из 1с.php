<?
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "DetaiTextCheck"); 

function DetaiTextCheck(&$arFields){
	if (@$_REQUEST['mode']=='import'){
		if(!empty($arFields["DETAIL_TEXT"])){
			$res = CIBlockElement::GetByID($arFields["ID"]);
			if($arElem = $res->GetNext()){
				if(!empty($arElem['DETAIL_TEXT'])){
					unset($arFields['DETAIL_TEXT']);
				}
			}
		}
		
	}
}
?>