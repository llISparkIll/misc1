<?
	//актуализация избранного
	function checkCookieFav(){
		//получение даты последнего сброса
		if($resetRaw = \Bitrix\Main\Context::getCurrent()->getRequest()->getCookie('lastReset')){
			$lastReset = json_decode($resetRaw, true);
		}
		else{
			$cookie = new \Bitrix\Main\Web\Cookie("lastReset", json_encode(date("d.m.Y")), time()+86400*365);
			$cookie->setSpread(\Bitrix\Main\Web\Cookie::SPREAD_DOMAIN);
			$cookie->setDomain(SITE_SERVER_NAME);
			$cookie->setPath("/");
			$cookie->setSecure(false);
			$cookie->setHttpOnly(false);
			\Bitrix\Main\Application::getInstance()->getContext()->getResponse()->addCookie($cookie);
			$lastReset = "";
		}
		$now = date("d.m.Y");
		if(strtotime($lastReset . " + 7 days") <= strtotime($now) || $lastReset == ""){
			CModule::IncludeModule('iblock');
			//получение актуальных товаров
			$IB = 2;
			$favRaw = \Bitrix\Main\Context::getCurrent()->getRequest()->getCookie('favList');
			$arFav = json_decode($favRaw, true);
			$newCookie = [];
			
			$arFilter = Array("IBLOCK_ID"=>$IB, "ID" => $arFav, "ACTIVE" => "Y");
			$arSelect = Array("ID");
			$res = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter, false, false, $arSelect);
			while($ob = $res->GetNextElement()){
				$arFields = $ob->GetFields();
				$newCookie[$arFields["ID"]] = $arFields["ID"];
			}
			//установка новой куки
			$newCookie = json_encode($newCookie);
			
			$cookie = new \Bitrix\Main\Web\Cookie("favList", $newCookie, time()+86400*365);
			$cookie->setSpread(\Bitrix\Main\Web\Cookie::SPREAD_DOMAIN);
			$cookie->setDomain(SITE_SERVER_NAME);
			$cookie->setPath("/");
			$cookie->setSecure(false);
			$cookie->setHttpOnly(false);
			\Bitrix\Main\Application::getInstance()->getContext()->getResponse()->addCookie($cookie);
		}
	}
?>