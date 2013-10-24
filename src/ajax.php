<?php
/**
 *	Обработка AJAX запросов DEEX CMF
 *
 *	23.10.2013
 *	@version 1
 *	@author Deemon<a@dee13.ru>
 */

$data=false;
if(empty($_GET['_a'])|| !isset ($_GET['_i'])|| !isset($_POST['_d']))
	elog('Ошибка запроса','','error');
else
{
	$D=$_POST['_d'];
	switch($_GET['_a'])
	{
		case 'start':
			$parser=new Parser($D);
			$catalog_html=$parser->parseUrl();
			if($catalog_html===false)
				break;
			$parser->addPageTasks($catalog_html);
			$parser->addListTasks($catalog_html);
			$data=true;
			break;

		case 'next_task':
			$parser=new Parser($D);
			$data=['finish'=>!$parser->doNextTask(),'success'=>conn::selectOneCell('COUNT(id) FROM `tasks` WHERE type="page" AND status="success"')];

			break;
		case 'update_fields':
			$data=conn::selectAll('* FROM `fields`');
			break;
		case 'clear_base':
			conn::query('TRUNCATE `fields`');
			conn::query('TRUNCATE `tasks`');
			conn::query('DROP TABLE IF EXISTS `datas_t`');
			conn::query('CREATE TABLE `datas_t` (`task_id`  int  UNSIGNED NOT NULL , PRIMARY KEY (`task_id`) ) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci COMMENT="Данные в табличном виде"');
			break;
		case 'get_data':
			if(empty($D['fields']) || !is_array($D['fields']) || empty($D['order_by'])||empty($D['limit']) ||!isset($D['page']))
				break;
			conn::selectLimited('`'.implode('`,`', $D['fields']).'` FROM `datas_t` order by `'.$D['order_by'].'`',$D['limit'],$D['page']);
			$data=['fields'=>conn::fetchAll(0,MYSQL_ASSOC),'total'=>conn::foundRows()];
			break;
		default:
			elog('Пусто','');
	}
}
die(getJSArr(['data'=>$data,'elog'=>elog('elog_return'),'a'=>$_GET['_a'],'i'=>$_GET['_i']]));
