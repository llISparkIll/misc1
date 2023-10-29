<?
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
if(isset($_POST)):
//echo '<pre>'; print_r($_POST); echo '</pre>';
# 1. Get Data
$service = $_POST["usl"];
$name = trim($_POST["fio"]);
$phone = trim($_POST["phone"]);
$email = trim($_POST["email"]);
$payment = $_POST["usl"];
$company = $_POST["usl"];
$site = $_POST["usl"];
$comm = trim($_POST["comment"]);
$privacy = $_POST["privacy"];

# 2. Check
if($service == '')
	$err[] = 'Не выбрана услуга';
if($name == '')
	$err[] = 'Не заполнено поле \'Имя\'';
if($phone == '')
	$err[] = 'Не заполнено поле \'Телефон\'';
if($email == '')
	$err[] = 'Не заполнено поле \'E-mail\'';
if($payment == '')
	$err[] = 'Не выбран способ оплаты';
if($privacy != 'on')
	$err[] = 'Требуется согласие на обработку персональных данных';

// Google ReCaptcha
include_once($_SERVER["DOCUMENT_ROOT"]."/include/recaptchalib.php");

$secret = "6Lc9yDAUAAAAANRtqeyVhcXbAOFOu3yL7pZmjIeS";
$response = null;
$reCaptcha = new ReCaptcha($secret);

// if submitted check response
if($_POST["g-recaptcha-response"]):
	$response = $reCaptcha->verifyResponse(
		$_SERVER["REMOTE_ADDR"],
		$_POST["g-recaptcha-response"]
	 );
	 
	if($response != null && $response->success)
		$skip = 1;// tmp
	else
		$err[] = "Не пройдена проверка на робота";
else:
	$err[] = "Не пройдена проверка на робота";
endif;

# 3. Print Result
if(is_array($err)):
	foreach($err as $str):
		echo '<p>'.$str.'</p>';
	endforeach;
else:

// 4. Sending email
	$arPost = array(
		"SERVICE" => $service,
		"NAME" => $name,
		"PHONE" => $phone,
		"EMAIL" => $email,
		"PAYMENT" => $payment,
		"COMPANY" => $company,
		"SITE" => $site,
		"COMMENT" => $comment,
	);
	
	if(CEvent::Send("SERVICE_REQUEST", "s1", $arPost, "Y", 170)){?>
		<div class="success">Ваше сообщение успешно отправлено.</div>
<?CEvent::Send("SERVICE_REQUEST", "s1", $arPost, "Y", 169);
	}

	// 5#IB Save
/*	CModule::IncludeModule('iblock');
	$el = new CIBlockElement;

	$PROP = array();
	$PROP[189] = $_POST['fio'];
	$PROP[190] = $_POST['phone'];
	$PROP[191] = $_POST['comment'];

	$arLoadProductArray = Array(
	  "IBLOCK_SECTION_ID" => false,
	  "IBLOCK_ID"      => 14,
	  "PROPERTY_VALUES"=> $PROP,
	  "NAME"           =>$PROP[189],
	  "ACTIVE"         => "Y",
	);

	$el->Add($arLoadProductArray);*/

endif;
endif;
?>