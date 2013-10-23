<?php
/**
 *	Обработка AJAX запросов DEEX CMF
 *
 *	23.10.2013
 *	@version 1
 *	@author Deemon<a@dee13.ru>
 */


if(empty($_GET['act']))
	die('Ошибка запроса');
switch($_GET['act'])
{
	case 'start':
		// if(!empty($_POST['url']))
		// 	return false;
		$parser=new Parser('http://torg.uz/ru/catalog/programmisty-it-internet-rezyume');
		$catalog_html=$parser->parseUrl();
		$parser->addPageTasks($catalog_html);
		$parser->addListTasks($catalog_html);
		break;

	case 'next_task':
		$parser=new Parser();
		$parser->doNextTask();

		break;

	case '':
		break;

	default:
		elog('Пусто','');
}
