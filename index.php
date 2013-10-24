<?php
/**
 *	Точка входа DEEX
 *
 *	23.10.2013
 *	@version 1
 *	@author Deemon<a@dee13.ru>
 */
/*Разбор URL */
	$URL=strtolower(str_replace(['\'','?'.$_SERVER['QUERY_STRING']],'',$_SERVER['REQUEST_URI']));
	$LINKS=explode('/',substr($URL,1));
function save($data)
{
	file_put_contents('data', $data);
}
require('src/engine.php');
require('config.php');
elog($CFG['watch_actions'],'elog_watch');
/*инициализация MYSQL соединения */
conn::init(
	$CFG['mysql_user'],
	$CFG['mysql_pass'],
	$CFG['mysql_base'],
	array(
		'elog'=>true,
		'log_query'=>true,
		)
);
if(count($LINKS)>0 && $LINKS[0]=='ajax')
{
	include('src/ajax.php');
	die(' ');
}
?><!doctype html>
<html lang="en">
<head>
	<title>Тестовое задание</title>

	<link rel="stylesheet" href="/css/dee_v.css">
	<link rel="stylesheet" href="/css/font.css">
	<script src="/js/jquery.js"></script>
	<script src="/js/engine.js"></script>
	<script src="/js/prot_sys.js"></script>
	<script src="/js/mess.js"></script>
	<script src="/js/page.js"></script>
	<script src="/js/pager.js"></script>
	<script src="/js/test.js"></script>
</head>
<body>
<div class="m-wrap admin">
	<div class="m-wrap-inner">
		<div class="m-wrap-cont _ac _position">
		<fieldset >
		<legend>Запуск парсера</legend>
			<p>На странице <a href="http://torg.uz/ru/catalog/rezyume" target="_blank">http://torg.uz/ru/catalog/rezyume</a> выберите, необходимую категорию вакансий и скопируйте текст адресной строки браузера</p>
			<div class="i text medium">
				<label>
					Текст адресной строки:
					<span>
						<input type="text" name="url" value="http://torg.uz/ru/catalog/programmisty-it-internet-rezyume">
					</span>
				</label>
			</div>

			<div class="i text micro">
				<label>
					Задержка (сек.):
					<span>
						<input type="text" name="timeout" value="1">
					</span>
				</label>
			</div>
			<br>
			<div class="i text micro">
				<label>
					Получено резюме:
					<span>
						<input type="text" name="found" value="0" readonly="readonly">
					</span>
				</label>
			</div>
			<div class="i button green _start_stop">
				<label ><button>Запустить <i class="awico-play"></i></button></label>
			</div>
			<div class="i button red _clear_base">
				<label ><button>Очистить базу <i class="awico-trash"></i></button></label>
			</div>
		</fieldset >
		<fieldset>
			<legend>Настройка вывода</legend>
			<div class="i select">
				<label for="">Выводимые поля:
					<select name="fields" id="" multiple="multiple">
					</select>
				</label>
			</div>
			<div class="i select">
				<label for="">Сортировать по:
					<select name="order_by" >
					</select>
				</label>
			</div>

			<div class="i button  _update_fields">
				<label ><button>Обновить список полей <i class="awico-refresh"></i></button></label>
			</div>
			<div class="i button green _show">
				<label ><button>Вывести <i class="awico-ok"></i></button></label>
			</div>
			<br>
			<br>
			<div class="table">
			<div class="title">
				<h3>Полученные резюме</h3>

				<ul class="buttons">

				</ul>

			</div>

			<div class="_table"></div>
			<div class="bottom"></div>


		</fieldset>
		</div>
	</div>
</div>
<?if($CFG['show_debug']){?>
	<div class="m-wrap elog">
		<div class="m-wrap-inner">
			<div class="m-wrap-cont">
				<h2>Лог</h2>

				<?php elog('elog_echo');?>
			</div>
		</div>
	</div>
<?}?>

<!-- Invisible Part-->
<script>$(function(){
	_.initAll(true);
});</script>
<div class="m-wrap loading _loading">
	<div class="m-wrap-inner">
		<div class="m-wrap-cont">
			<div class="title _text">Идет загрузка контента...</div>
		</div>
	</div>
</div>
</body>
</html>