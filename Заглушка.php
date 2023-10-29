<?
// файл подключается в init.php, нужно открыть сайт в главном модуле
function OpenAccessForGroup()
{
	//объявляется глабальный объект "Пользователь" и "Приложение"
	global $USER, $APPLICATION;

	//смотрим в какой группе находится текущий пользователь (функция возвращает ID группы).
	$mas = $USER->GetUserGroupArray();

	/*проверяем, что пользователь находится хотя бы в одной группе и с  помощью PHP функции in_array проверяем находится ли группа пользователя в  списке запрещённых групп для доступа к публичной части сайта*/
	if (count($mas)>0 && !in_array(6, $mas) && (strpos($APPLICATION->GetCurPage(),'/bitrix/admin/'))===false)
	{
	/*здесь можно вставить произвольное сообщение о запрете доступа (но надо обязательно выполнить функцию die()*/
		include $_SERVER["DOCUMENT_ROOT"].'/bitrix/php_interface/include/site_closed.php';
		
		die();
	}

}

//Здесь мы привязываем обработчик события к нашей функции
AddEventHandler("main", "OnProlog", "OpenAccessForGroup");