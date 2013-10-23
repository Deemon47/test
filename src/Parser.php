<?php
/*Основной класс парсера*/
class Parser
{
	private $category_url,
	$protocol_and_domen,
	$added=[]
	;

	public static $proxy_list=
	[
	],
	$browsers_list=
	[
		'Opera/9.00 (Windows NT 5.1; U; ru)',
		'Opera/9.80 (Windows NT 5.1; U; MRA 5.8 (build 4139); ru) Presto/2.9.168 Version/11.50',
		'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; MRSPUTNIK 2, 4, 0, 270; MRA 5.8 (build 4139))',
		'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729)',
		'Mozilla/5.0 (Windows; U; Windows NT 6.0; ru; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 (.NET CLR 3.5.30729)',
		'Mozilla/5.0 (Windows NT 5.1; rv:5.0) Gecko/20100101 Firefox/5.0',
		'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/4.0.201.1 Safari/532.0',
		'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU) AppleWebKit/525.27.1 (KHTML, like Gecko) Version/3.2.1 Safari/525.27.1'
	];
	function __construct($category_url=false)
	{
		if($category_url==false)
			return;
		$this->protocol_and_domen=preg_replace('#^([^:]+://[^/]+).*$#', '\1', $category_url);
		elog($this->protocol_and_domen,'$this->$protocol_and_domen');
		$this->category_url=rtrim($category_url,'/');
	}
	function addPageTasks($html)
	{
		/*Поиск списка резюме*/
		if(preg_match('#<table class="offers blue offers">.*<tbody>(.+)</tbody>.*</table>#sU', $html,$list))
		{
			/*Поиск ссылок на подробное описание*/
			if(preg_match_all('#<a[^>]+href\s*=\s*[\'"]([^\'"]+)#s', $list[1], $links))
			{
				$q='';
				foreach($this->getAbsolutLinks($links[1]) as $val)
					$q.='("'.addslashes($val).'","page"),';
				conn::query('INSERT INTO `tasks`(url,type)VALUES'.rtrim($q,',').' ON DUPLICATE KEY UPDATE status="to_update"');
				return true;
			}
		}
		elog('Категория не найдена','ошибка','error');
		return false;
	}
	function addListTasks($html)
	{
		/*Поиск количества страниц*/
		if(preg_match('@<div class="pages">.*(\d+)</a><a[^<]+href\s*=\s*[\'"]([^\'"]+)\d+[^<]+paging_next.*</div@sU', $html,$pages))
		{
			$links=[];
			for($i=2;$i<=$pages[1];++$i)
				$links[]=$pages[2].$i;
			$q='';
			foreach($this->getAbsolutLinks($links) as $val)
				$q.='("'.addslashes($val).'","list"),';
			conn::query('INSERT INTO `tasks`(url,type)VALUES'.rtrim($q,',').' ON DUPLICATE KEY UPDATE status="to_update"');
			return true;
		}
		return false;
	}
	function doNextTask()
	{
		$task=conn::selectOneRow('* FROM `tasks` WHERE status IN("to_do","to_update") ORDER BY `status`="to_do",id ');
		if($task!==false)
		{
			$html=$this->parseUrl($task->url);
			/*Получение данных с резюме*/
			if($task->type=='page')
			{
				$data=$this->getResumeData($html);
				$fields=[];
				foreach($data as $field=>$val)
					if(empty($this->added[$field]))
						$fields[$field]=$field;
				/*Добавляю новые поля*/
				$d=conn::selectAll('* FROM `fields` WHERE `name` in ("'.implode('","', $fields).'")');
				if($d)
					foreach($d as $field)
					{
						$this->added[$field->name]=$field->id;
						unset($fields[$field->name]);
					}
				if(count($fields)>0)
				{
					$fields=array_values($fields);
					$q='';
					foreach($fields as $val)
						$q.='("'.$val.'"),';
					$id=conn::query('INSERT INTO `fields`(`name`) VALUES'.rtrim($q,','),true);
					foreach($fields as $ind=>$val)
						$this->added[$val]=$ind+$id;
				}
				$q='';
				/*Добавляю значения*/
				foreach($data as $field=>$val)
					$q.='('.$task->id.','.$this->added[$field].',"'.addslashes($val).'"),';
				conn::query('INSERT INTO `datas`(`url_id`,`field_id`,`value`) VALUES'.rtrim($q,','));
				elog($this->added,'$this->added');

				conn::query('UPDATE `tasks` set status="success" where id='.$task->id);
			}
			else
				$this->addPageTasks($html);


		}
		elog($task,'$task');
	}
	function getResumeData($html)
	{
		$data=[];
		/*Автор*/
		if(preg_match('#<h3>Автор объявления:</h3>.*<tbody>(.+)</tbody#sU', $html,$match))
		{
			if(preg_match('#Имя:.*>([^<>]+)</a>#sU', $match[1],$name))
				$data['Имя']=$name[1];

			$data['Контакты']=preg_replace(['#contact/4.gif[^>]+>([^<>]+)<#','#<(?!img)[^>]+>#','#\s+#'], ['>Skype:\1<',' ',' '], substr($match[1], strpos($match[1], $name[0])+strlen($name[0])));

		}
		/*Описание*/
		if(preg_match('#<h2>(.+)</h2>.*<table class="details">.+<tbody>(.+)</tbody#sU', $html,$match))
		{
			$data['Описание']=$match[1];
			foreach(explode('</tr>', $match[2]) as $val)
			{
				$val=trim(preg_replace('#<[^<>]+>#', '', $val));
				// save($val);
				$val=explode(':', $val);
				if(count($val)==2)
					$data[addslashes($val[0])]=$val[1];
			}
		}
		if(preg_match('#<h3>Дополнительная информация:</h3>(.+)</#sU', $html,$match))
			$data['Дополнительно']=trim(preg_replace('#<[^<>]+>#', '', $match[1]));

		elog($data,'$data');
		return $data;
	}
	function parseUrl($url=false)
	{

		return file_get_contents(!$url?'torg_cat.txt':'vak.txt');
	}
	/**
	 * Получить содержание страницы
	 * @param  string $url
	 * @return string
	 */
	function parseUrl1($url=false)
	{
		if($url==false)
			$url=$category_url;
		$curl = curl_init();//инициализация сессии
		$proxy =false;
		$user_agent=Parser::$browsers_list[array_rand( Parser::$browsers_list ,1 )];
		if(count(Parser::$proxy_list)>0)
			$proxy=Parser::$proxy_list[ array_rand( Parser::$proxy_list ,1 )];
		elog("$proxy :|: $user_agent",'PROXY');

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HEADER, 1);//неполучать хедер
		curl_setopt($curl, CURLOPT_URL, $url );
		curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
		if($proxy)
			curl_setopt($curl, CURLOPT_PROXY,$proxy);
		$res=curl_exec ($curl);
		return (strpos($res, 'HTTP/1.1 200 OK') !==false)?$res:false;
	}
	/**
	 * Замена относительных ссылок на абсолютные
	 * @param  array $links
	 * @return array
	 */
	function getAbsolutLinks($links)
	{
		foreach($links as &$val)
				if($val[0]=='/')
					/*относительно корня сайта*/
					$val=$this->protocol_and_domen.$val;
				elseif(strpos($val, $this->protocol_and_domen)===false)
					/*отностительно текущей страницы*/
					$this->category_url.'/'.$val;
		return $links;
	}

}
