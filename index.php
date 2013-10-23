<?php
/**
 *	Точка входа DEEX
 *
 *	23.10.2013
 *	@version 1
 *	@author Deemon<a@dee13.ru>
 */
$ELOG=true;
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
	elog('data');
	// die(' ');
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
	<script src="/js/hot_keys.js"></script>
	<script src="/js/m_win.js"></script>
	<script src="/js/mess.js"></script>
	<script src="/js/pager.js"></script>
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
						<input type="text" name="">
					</span>
				</label>
			</div>
			<div class="i button green">
				<label ><button>Запустить <i class="awico-play"></i></button></label>
			</div>
		</fieldset >
		<fieldset>
			<legend>Настройка вывода</legend>
			<div class="i select">
				<label for="">Выводимые поля:
					<select name="" id="" multiple="multiple">
						<option>asjkdlf</option>
						<option>asjkdlf</option>
						<option>asjkdlf</option>
						<option>asjkdlf</option>
						<option>asjkdlf</option>
						<option>asjkdlf</option>
						<option>asjkdlf</option>
						<option>asjkdlf</option>
					</select>
				</label>
			</div>
			<div class="i select">
				<label for="">Сортировать по:
					<select name="" >
						<option>asjkdlf</option>
						<option>asjkdlf</option>
						<option>asjkdlf</option>
						<option>asjkdlf</option>
						<option>asjkdlf</option>
						<option>asjkdlf</option>
						<option>asjkdlf</option>
						<option>asjkdlf</option>
					</select>
				</label>
			</div>

			<div class="i button green">
				<label ><button>Вывести <i class="awico-ok"></i></button></label>
			</div>
			<br>
			<br>
			<div class="table">
			<div class="title">
				<h3>Полученные резюме</h3>

				<ul class="buttons">
					<li title="Добавить">
						<span class="awico-plus-sign"></span>
					</li>
					<li title="Редактировать">
						<span class="awico-pencil"></span>
					</li>
					<li title="Удалить">
						<span class="awico-remove-sign"></span>
					</li>
					<li title="Сортировать">
						<span class="awico-random"></span>
					</li>
					<li title="Фильтр">
						<span class="awico-filter"></span>
					</li>
					<li title="Экспорт">
						<span class="awico-download-alt"></span>
					</li>
				</ul>

			</div>

			<table>
				<thead>
					<tr>
						<th>
							<span>&nbsp;</span>
						</th>
						<th class="awico-sort">
							<span>Дата</span>
						</th>
						<th class="awico-sort-up">
							<span>Дата и время</span>
						</th>
						<th class="awico-sort-down">
							<span>Название проекта</span>
						</th>

					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Мой проект</td>
						<td>Мой большой проект</td>
						<td>Мой большой проект</td>
						<td>Мой большой проект</td>
					</tr>
				</tbody>
			</table>

			<div class="bottom">

				<p class="limit">Показаны 1 до 10, <input type="text" value="223"> записей</p>
				<span class="pager">
					<span class="awico-arrow-left"> Предыдущая</span>
					<span>1</span>
					<input type="text" value="299"/>
					<span>3</span>
					<span >Следущая <i class="awico-arrow-right"></i></span>
				</span>

			</div>

		</div>
		</fieldset>
		</div>
	</div>
</div>
<?if($ELOG){?>
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