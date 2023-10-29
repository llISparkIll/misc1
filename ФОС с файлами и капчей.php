<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");


include_once($_SERVER["DOCUMENT_ROOT"]."/include/recaptchalib.php");
$status = [];
$input_name = 'file';

// Разрешенные расширения файлов.
$allow = array(
	'jpg', 'jpeg', 'png'
);
 
// Запрещенные расширения файлов.
$deny = array(
	'phtml', 'php', 'php3', 'php4', 'php5', 'php6', 'php7', 'phps', 'cgi', 'pl', 'asp', 
	'aspx', 'shtml', 'shtm', 'htaccess', 'htpasswd', 'ini', 'log', 'sh', 'js', 'html', 
	'htm', 'css', 'sql', 'spl', 'scgi', 'fcgi', 'exe'
);
 
$data = array();
$files = array();
	
$diff = count($_FILES[$input_name]) - count($_FILES[$input_name], COUNT_RECURSIVE);
if ($diff == 0) {
	$files = array($_FILES[$input_name]);
} else {
	foreach($_FILES[$input_name] as $k => $l) {
		foreach($l as $i => $v) {
			$files[$i][$k] = $v;
		}
	}
}
if(isset($_FILES[$input_name])){
	$fileSucces = true;
	if(count($files) <= 3){
		$fileSucces = false;
		foreach ($files as $file) {
		$fileError = '';
		// Проверим на ошибки загрузки.
			if (!empty($file['error']) || empty($file['tmp_name'])) {
				$fileError = 'Не удалось загрузить файл.';
			} elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
				$fileError = 'Не удалось загрузить файл.';
			} else {
				// Оставляем в имени файла только буквы, цифры и некоторые символы.
				$pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
				$name = mb_eregi_replace($pattern, '-', $file['name']);
				$name = mb_ereg_replace('[-]+', '-', $name);
				$parts = pathinfo($name);
				
				if (empty($name) || empty($parts['extension'])) {
					$fileError = 'Недопустимый тип файла';
				} elseif (!empty($allow) && !in_array(strtolower($parts['extension']), $allow)) {
					$fileError = 'Недопустимый тип файла';
				} elseif (!empty($deny) && in_array(strtolower($parts['extension']), $deny)) {
					$fileError = 'Недопустимый тип файла';
				} else {
					if($file["size"] > 10000000){
						$fileError = "Файл не может быть больше 10Мб";
					}else{
						$fileSucces = true;
					}
					
				}
				
			}
		}
	}else{
		$fileError = 'Вы можете выбрать не более 3 файлов';
	}
}


$name = $_POST["name"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$comment = $_POST["comment"];

$nameError = "";
$phoneError = "";
$emailError = "";
$fileError = "";
$grecaptchaError = "";

if(trim($name) == "")
	$nameError = "Укажите ваше имя";
if(trim($phone) == "")
	$phoneError = "Укажите ваш телефон";
if(!check_email($email))
	$emailError = "Укажите ваш email";

$status = [
	"fileError" => $fileError,
	"nameError" => $nameError,
	"phoneError" => $phoneError,
	"emailError" => $emailError,
];

$stat = false;
foreach($status as $err){
	if($err == ""){
		$stat = true;
	}else{
		$stat = false;
		break;
	}
		
}

if (isset($_POST['g-recaptcha-response'])) {
	
	$secret = '6Lc9zvsfAAAAAG6rR9AIiarvcmM-dfKGslXZcoah';
	$recaptcha = new ReCaptcha($secret);
  
	$resp = $recaptcha->verifyResponse($_SERVER["REMOTE_ADDR"], $_POST["g-recaptcha-response"]);
  
	if ($resp != null && $resp->success){
		$stat = true;
	} else {
		$grecaptchaError = "Код капчи не прошёл проверку на сервере";
		$errors = $resp->errorCodes();
		print_r($errors);
		$stat = false;
	}
} else {
	$grecaptchaError = 'Вы не прошли проверку "Я не робот"';
	$stat = false;
}


$status["grecaptchaError"] = $grecaptchaError;

if($stat){
	
	$arPost = array(
		"NAME" => $name,
		"PHONE" => $phone,
		"EMAIL" => $email,
		"COMMENT" => $comment,
	);
	$filesURL = array();
	foreach ($files as $file) {
		$filesURL[] = $file["tmp_name"];
	}
	
	if(CEvent::Send("SERVICE_REQUEST", "s1", $arPost, "Y", 52, $filesURL)){
		$status["SUCCES"] = true;
	}
}
echo json_encode($status);	



