/**
 *	JS часть Тестового задания
 *
 *	23.10.2013
 *	@version 1
 *	@author Deemon<a@dee13.ru>
 */

_.test=
{
	'timer':false,
	'field_list':[],
	'table_o':false,
	'fields_o':false,
	'limit_o':false,
	'pager':false,
	'clear_counter':0,
	'finded_o':false,
	'timeout_o':false,
	'_init':function()
	{
		$('._clear_base').click(function(){
			_.test._ajax(false,{'action':'clear_base','desc':'Идет очистка базы'/*,'sys':false,'params':false*/});
		});
		$('._update_fields').click(function(){
			_.test._ajax(false,{'action':'update_fields','desc':'Обновляется список полей'/*,'sys':false,'params':false*/});
		}).click();
		this.pager=_.pager.get(function(){

			var data=_.pager.getLimit(_.test.pager);
			_.test.getData(data.limit,data.page);
		},{}).appendTo('.table .bottom');
		this.table_o=$('._table');
		var url_o=$(':input[name=url]');
		this.timeout_o=$(':input[name=timeout]');
		this.fields_o=$(':input[name=fields]');
		this.finded_o=$(':input[name=finded]');
		this.limit_o=$(':input[name=limit]');
		$('._start_stop').click(function(){
			var o =$(this);
			if(o.hasClass('green'))
			{
				_.mess.show("Произведен запуск парсера");
				o.removeClass('green').find('button').html('Остановить <i class="awico-stop"></i>');
				_.test._ajax(url_o.val(),{'action':'start','desc':'Идет обработка резюме'/*,'sys':false,'params':false*/});


			}
			else
			{
				_.mess.show("Парсер остановлен");
				o.addClass('green').find('button').html('Запустить <i class="awico-play"></i>');
				clearInterval(_.test.timer);
			}
		});
		$('._show').click(function(){
			_.test.getData(20,0);
		});

	},
	'getData':function(limit,page)
	{

		var data={'fields':this.fields_o.val(),'limit':limit,'page':page};
		if(data.fields==null)
			_.mess.show('Нужно выбрать хотябы одно поле','warning');
		else
			this._ajax(data,{'action':'get_data','desc':'Идет загрузка данных','params':data/*,'sys':false,*/});
	},
	'doTask':function()
	{
		clearInterval(_.test.timer);
		_.test.timer=setInterval(_.test.doTask,30000);
		_.test._ajax(false,{'action':'next_task','desc':'Идет обработка резюме',/*'sys':false,'params':false*/});
		if(_.test.clear_counter>10)
		{
			$('ul._elog').empty();
			_.test.clear_counter=0;
		}
		else
			_.test.clear_counter++;
	},
	'_response':function(data,e)
	{
		if(e.action=='update_fields')
		{
			var options='';
			this.field_list={};
			for(var key in data)
			{
				var val=data[key];
				this.field_list[val.id]=val.name;
				options+='<option value="'+val.id+'">'+val.name+'</option>';
			}
			$('select').html(options);
		}
		else if(e.action=='clear_base')
		{
			_.mess.show('База очищена');
		}
		else if(e.action=='start')
		{
			if(!data)
			{
				clearInterval(this.timer);
				_.mess.show('Запуск не удался','error');
			}
			else
			setTimeout(this.doTask,parseInt(this.timeout_o.val())*1000);
		}
		else if(e.action=='next_task')
		{
			if(data.finish)
			{
				clearInterval(this.timer);
				_.mess.show('Резюме обработаны','success');
				$('._start_stop').addClass('green').find('button').html('Запустить <i class="awico-play"></i>');
			}
			else
				setTimeout(this.doTask,parseInt(this.timeout_o.val())*1000);
			this.finded_o.val(data.success);

		}
		else if(e.action=='get_data')
		{
			elog(e.params.fields,'e.params.fields');
			var table='<table><thead><tr>';
				for(var key in e.params.fields)
				{
					var val=e.params.fields[key];
					table+='<th><span>'+this.field_list[val]+'</span></th>';
				}

			table+='</tr></thead><tbody>';
			for(var key in data.fields)
			{
				var row=data.fields[key];
				table+='<tr>';
				for(var key in e.params.fields)
					table+='<td>'+row[e.params.fields[key]]+'</td>';
				table=='</tr>';
			}
			table+='</tbody></table>';
			this.table_o.html(table);
			_.pager.update(this.pager,data.total);
		}

		elog([data,e],'data');
	}
}