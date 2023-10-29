<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main,  
	Bitrix\Sale,
	Bitrix\Sale\Order,
	Bitrix\Sale\Delivery,
	Bitrix\Sale\PaySystem,
	Bitrix\Main\Mail\Event;

//echo '<pre>'; print_r($_POST); echo '</pre>';

// Create Order
$order = Order::create($siteID, $USER->GetID());
//$order = Sale\Order::load(5);

// Data
# Persone fiz/yur
$persone = 1;
if($personType == 5)
	$persone = 2;
$order->setPersonTypeId($persone);

# Basket items
$basket = Sale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), $siteID)->getOrderableItems();
$order->setBasket($basket);

# Shipment
$delySys = 16;
if($_POST["delivery"] != '')
	$delySys = $_POST["delivery"];
$shipmentCollection = $order->getShipmentCollection();
$shipment = $shipmentCollection->createItem(
	Bitrix\Sale\Delivery\Services\Manager::getObjectById($delySys)
);

$shipmentItemCollection = $shipment->getShipmentItemCollection();
foreach($basket as $basketItem){
	$item = $shipmentItemCollection->createItem($basketItem);
	$item->setQuantity($basketItem->getQuantity());
}

# Payment
$paySys = 1;
if($_POST["payment"] != '')
	$paySys = $_POST["payment"];

$paymentCollection = $order->getPaymentCollection();
$payment = $paymentCollection->createItem(
	Bitrix\Sale\PaySystem\Manager::getObjectById($paySys)
);
$payment->setField("SUM", $order->getPrice());
$payment->setField("CURRENCY", $order->getCurrency());

# Fields
$order->doFinalAction(true);
$propertyCollection = $order->getPropertyCollection();

function getPropertyByCode($propertyCollection, $code)  {
	foreach ($propertyCollection as $property){
		if($property->getField('CODE') == $code)
			return $property;
	}
}

if($personType == 5):
	$innProp= getPropertyByCode($propertyCollection, 'INN');
	$innProp->setValue($_POST["inn"]);
	$nameProp= getPropertyByCode($propertyCollection, 'COMPANY');
	$nameProp->setValue($_POST["name2"]);
else:
	$phoneProp = getPropertyByCode($propertyCollection, 'PHONE');
	$phoneProp->setValue($_POST["phone"]);
	$nameProp= getPropertyByCode($propertyCollection, 'FIO');
	$nameProp->setValue($_POST["name"]);
endif;

$emailProp = getPropertyByCode($propertyCollection, 'EMAIL');
$emailProp->setValue($_POST["email"]);

if($delySys == 16): // If Russia Post
	if($_POST["index"] !='')
		$arAdress[] = $_POST["index"];
	if($_POST["city"] !='')
		$arAdress[] = $_POST["city"];
	if($_POST["street"] !='')
		$arAdress[] = $_POST["street"];
	if($_POST["home"] !='')
		$arAdress[] = $_POST["home"].$_POST["corpus"];
	if($_POST["kvar"] !='')
	$arAdress[] = $_POST["kvar"];

	$strAdress = implode(", ", $arAdress);

	$adressProp = getPropertyByCode($propertyCollection, 'ADDRESS');
	$adressProp->setValue($strAdress);
endif;

/*$orderFields = $order->getAvailableFields();
$orderFields2["PERSONE"] = $order->getPersonTypeId();
$orderFields2["PRICE"] = $order->getPrice();
$orderFields2["DELIVERY"] = $order->getDeliveryPrice();
$orderFields2["VAT_SUM"] = $order->getField('VAT_SUM');
$orderFields2["ACCOUNT_NUMBER"] = $order->getField('ACCOUNT_NUMBER');
//$orderFields2["shipment"] = $shipment;

echo '<pre>';
//print_r($propertyCollection);
print_r($orderFields2);
echo '</pre>';
*/

//$order->setField('USER_DESCRIPTION', 'Комментарий к заказу');

$result = $order->save();
$orderNum = $result->getId();
//echo '<pre>'; print_r($result); echo '</pre>';
if($result->isSuccess()):?>
	<!--p>Ваш заказ №<b>8</b> от 23.05.2017 18:06:45 успешно создан.</p-->
	<p>Заказ #<b><?=$orderNum?> от <?=date('d.m.Y');?></b> успешно оформлен.<br>
	<?switch($paySys):
		case 1://Наличные курьеру ?>
	<p>Для подтверждения заказа с Вами свяжется менеджер.</p>
	<?break;
		case 6:
		//Квитанцией Сбербанка
		break;
		case 9://Картой в магазине (для розничных клиентов)?>
		<p>Для подтверждения заказа с Вами свяжется менеджер.</p>
		<?break;
		case 10://По безналичному расчёту?>
	<p>Для выставления счёта с Вами свяжется менеджер.</p>
	<?endswitch;?>

	<p>Вы можете следить за выполнением своего заказа в <a href="/personal/orders/">Персональном разделе сайта</a>. Обратите внимание, что для входа в этот раздел вам необходимо будет ввести логин и пароль пользователя сайта.</p>
	<br>
	<script>//Clear html-basket
		BX.onCustomEvent('OnBasketChange');
	</script>
	<?if($paySys == 6):?>
	<p>Оплата заказа</p>
	<?/*for($i=0; $i<count($arPayments); $i++):
			if($arPayments[$i]["ID"] == $paySys):?>
		<img src="<?=$arPayments[$i]["LOGOTIP"];?>" style="width:100px" alt="<?=$arPayments[$i]["NAME"]?>" title="<?=$arPayments[$i]["NAME"]?>" />
		<p><?=$arPayments[$i]["NAME"];?></p>
	<?endif;
		endfor;*/
	// PaySystems Action File
		$service = Sale\PaySystem\Manager::getObjectById($paySys);
		$context = \Bitrix\Main\Application::getInstance()->getContext();
		$service->initiatePay($payment, $context->getRequest());
	endif;
	
	$APPLICATION->SetPageProperty("title", "Заказ сформирован");
else:
	$result->getErrors();
endif;

//require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
/* Sources
https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=8409
https://mrcappuccino.ru/blog/post/work-with-order-bitrix-d7
https://dev.1c-bitrix.ru/community/webdev/user/227946/blog/18513/
*/?>