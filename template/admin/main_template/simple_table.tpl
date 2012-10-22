[[ extends  TEMPLATE_PATH ~ "admin/main.tpl" ]]

[[ macro printFilters(filters) ]]
	[[for key,filt in filters]]
		[[if key == 'date']]
			[[if (filt.to or filt.to=='') and (filt.from or filt.from=='')]]
				&filter[{key}][to]={filt.to}&filter[{key}][from]={filt.from}
			[[elseif filt.to or filt.to=='']]
				&filter[{key}][to]={filt.to}
			[[elseif filt.from or filt.from=='']]
				&filter[{key}][from]={filt.from}
			[[else]]
				&filter[{key}]={filt}
			[[endif]]
		[[else]]
			&filter[{key}]={filt}
		[[endif]]
	[[endfor]]
[[endmacro]]

[[ block left_content ]]
	<div id="left">
		[[ include TEMPLATE_PATH ~ "admin/blocks/leftmenu.tpl" ]]
		
		[[if filters_left]]
			[[ include TEMPLATE_PATH ~ filters_left ]]
		[[endif]]
	</div>
[[endblock]]

[[block center]]

<h1>{mod.caption}</h1>
[[if not no_button_add]]<button class="fmk-button-admin" onclick="document.location='/admin/?modul={request.modul}[[if hiden_param_seo]]&dop_polya=hide[[endif]][[if request.id_interview]]&id_interview={request.id_interview}[[endif]]&action=new';return false;"><div><div><div>Добавить</div></div></div></button>[[endif]]
[[if config_modul]]<button class="fmk-button-admin" onclick="document.location='{config_modul}';return false;"><div><div><div>Настройки</div></div></div></button>[[endif]]
<br /><br />
[[if content]]
		{content}
		<BR><BR>
	[[ endif ]]
	
	[[if pages]]
		<div class="pager" >
		[[for i in 1..pages]]
			<span><a href="/admin/index.php?modul={request.modul}&page={i}{_self.printFilters(filters)}" [[if i==page ]]class="active"[[endif]] title="Страница {i}" >{i}</a></span>  
		[[endfor]]
		</div>
	[[endif]]
	
	<table border="0" cellspacing="1" cellpadding="0" class="main-table"  id="main-table">
	<thead>
		<tr class="td-header" >
	[[for fild in filds]]
		<td>{fild}</td>
	[[endfor]]
	
	<td >Управление</td>
	</tr>
	</thead>
	
	<tbody>
		[[for key,item in items]]
			<tr class="td-item">		
				
			[[for key,fild in filds]]
			<td  
				[[if loop.first]]
					style="padding: 0 0 0 {item['level']*20+20}px"
				[[endif]]
				>[[if key=='link']]<a target="_blank" href="{item[key]}">{item[key]}</a>[[else]]{item[key]}[[endif]]</td>	
			[[endfor]]
			<td valign="middle" align="center">
				[[ include TEMPLATE_PATH ~ "admin/blocks/actions.tpl" ]]
			</td>
			</tr>
		[[endfor]]	
	</tbody></table>
	[[if error_vk_popup_url]]
		[[raw]]
			<script>
				popupWin = window.open("http://[[endraw]]{error_vk_popup_url|raw}&popup=1[[raw]]", "contacts", "resizable=1,top=100,left=100");
				popupWin.focus(); // передаём фокус новому окну
			</script>
		[[endraw]]
	[[endif]]
[[endblock]]	