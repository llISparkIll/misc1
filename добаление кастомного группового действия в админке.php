<?AddEventHandler("main", "OnAdminListDisplay", "MyOnAdminListDisplay");
function MyOnAdminListDisplay(&$list)
{
	if($_GET["IBLOCK_ID"] == 40):
		CModule::IncludeModule('highloadblock');
		$HB_ID = 1;
		$arHLBlock = Bitrix\Highloadblock\HighloadBlockTable::getById($HB_ID)->fetch();
		$obEntity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arHLBlock);
		$strEntityDataClass = $obEntity->getDataClass();
		$rsData = $strEntityDataClass::getList(array(
			'filter' => array(),
			'select' => array('ID', 'UF_NAME', 'UF_XML_ID'),
			'order' => array('ID' => 'ASC'),
		));
		$arSelectValues = [];
		$counter = 0;
		while($arCard = $rsData->Fetch()){
			$arSelectValues[$counter]["NAME"] = $arCard["UF_NAME"];
			$arSelectValues[$counter]["VALUE"] = $arCard["UF_XML_ID"];
			$counter++;
		}
		$snippet = new \Bitrix\Main\Grid\Panel\Snippet();
		
		$list->arActions['producer'] = array(
			'name' => "Задать производителя",
			'type' => 'multicontrol',
			'value' => 'producer',
			'action' => array(
				array(
					'ACTION' => \Bitrix\Main\Grid\Panel\Actions::CREATE,
					'DATA' => array(
						[
							'TYPE' => \Bitrix\Main\Grid\Panel\Types::DROPDOWN,
							'ID' => 'producer_input',
							'NAME' => 'producer_input',
							"ITEMS" => $arSelectValues 
						],
						$snippet->getApplyButton(array(
							'ONCHANGE' => array(
								array( 
									'ACTION' => \Bitrix\Main\Grid\Panel\Actions::CALLBACK,
									'DATA' => array(
										array(
											'JS' => "Grid.sendSelected()"
										)
									)
								)
							)
						)),
					)
				),
				
			),
		);
	endif;
}
AddEventHandler("main", "OnBeforeProlog", "MyOnBeforeProlog");
function MyOnBeforeProlog()
{
	foreach($_POST["controls"] as $control){
		if($control == "producer"){
			CModule::IncludeModule('iblock');
			
			$section = $_POST["rows"][0];
			$section = str_replace("S", "", $section);
			$arFilter = Array("IBLOCK_ID"=>40, "SECTION_ID" => $section, 'INCLUDE_SUBSECTIONS'=>'Y');
			$arSelect = Array("ID");
			$res = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter, false, false, $arSelect);
			while($ob = $res->GetNextElement()){
				$arFields = $ob->GetFields();
				CIBlockElement::SetPropertyValueCode($arFields["ID"], 383, $_POST["controls"]["producer_input"]);
			}
			break;
		}
	}
}