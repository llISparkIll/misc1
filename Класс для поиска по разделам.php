<?
AddEventHandler("search", "BeforeIndex", Array("SectionSearch", "BeforeIndexHandler"));

class SectionSearch
{
   // создаем обработчик события "BeforeIndex"
   function BeforeIndexHandler($arFields)
   {
      
      if($arFields["MODULE_ID"] == "iblock" && $arFields["PARAM2"] == 19 && substr($arFields["ITEM_ID"], 0, 1) != "S")
      {
         $arFields["PARAMS"]["iblock_section"] = array();
         //Получаем разделы привязки элемента (их может быть несколько)
         $rsSections = CIBlockElement::GetElementGroups($arFields["ITEM_ID"], true);
         while($arSection = $rsSections->Fetch())
         {
            //Сохраняем в поисковый индекс
            $arFields["PARAMS"]["iblock_section"][] = $arSection["ID"];
         }
      }
      //Всегда возвращаем arFields
      return $arFields;
   }
}
?>