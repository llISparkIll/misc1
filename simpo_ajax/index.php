<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex">
<title>Interface</title>
<style>
body{background: #333; color: beige;}
p{margin: 0 0 4px;}
#informer{position: fixed; top: 0; right: 0;}
#informer2{position: fixed; top: 15px; right: 0;}
#progress{display: none;}
/*
ToDo List
+AJAX gif-loader in step
+LocalStorage save more
+page selector
+style
*/
</style>
<script src="/bitrix/js/main/jquery/jquery-1.8.3.min.js"></script>
</head>
<body>

<div>
	<h1>Interface</h1>
	<form method="POST" id="form-ajax">
		<p>ИБ</p>
		<input type="text" name="IB" value=""><br>
		<p>Пагинация</p>
		<input type="number" min="1" name="num" value="5"><br>
		<label><input type="checkbox" name="auto" checked><span> Авто</span></label><br><br>
		
		<input type="submit" name="submit" value="submit"> 
		<img id="progress" src="/bitrix/panel/main/images_old/wait.gif" alt="Loading...">
	</form>
	<button onclick="$('#holding').html('');">Clear</button>
	<div id="holding"></div>
	<div id="informer">info</div>
	<div id="informer2"><span>0</span>% <progress max="100" value="0"></div>
</div>

<script>
var dataAjax = {
	totalPages: 0,
	IB: 0,
	pageSize: 0,
	pageNum: 1,
};

function ajaxRequest(){
	$('#progress').show();//
	$.ajax({
		type: 'POST',
		url: '/simpo_ajax/ajax.php',
		dataType: 'json',
		data: dataAjax,
		success: function(data){
//console.log(data);
			dataAjax.pageNum = +data.curPart+1;
			dataAjax.totalPages = data.totalParts;
			$("#holding").append("<br>Партия "+data.curPart+"/"+data.totalParts+"<br>"+data.items+"------------");
			$("#informer").html("Партия "+data.curPart+"/"+data.totalParts);
			var progress = 100* data.curPart/data.totalParts;
			var percent = Math.round(progress).toFixed(0);
			$("#informer2 span").html(percent);
			$("#informer2 progress").attr("value", progress);
			document.title=percent+'% - SA progress';
			if(dataAjax.pageNum <= dataAjax.totalPages){
				if($('#form-ajax input[name="auto"]').is(":checked"))
					ajaxRequest();
			}else{
				$("#holding").append("<br>~~~ Конец ~~~");
			}
			$('#progress').hide();//
		},
		error:  function(xhr, str){
			alert('Возникла ошибка: ' + xhr.responseCode);
			$('#progress').hide();//
		}
	});
}

$('#form-ajax').submit(function(){
	//var res = $(this).serialize();
	dataAjax.IB = $('#form-ajax input[name="IB"]').val();
	dataAjax.pageSize = $('#form-ajax input[name="num"]').val();
	ajaxRequest();	
	event.preventDefault();
});

// Saver
var savedIB = localStorage.getItem('sa_IB');
if(savedIB > 1)
	$('#form-ajax input[name="IB"]').val(savedIB);

$('#form-ajax input[name="IB"]').change(function(){
	var IBnum = Number($(this).val());
	localStorage.setItem('sa_IB', IBnum);
});
</script>
</body>
</html>