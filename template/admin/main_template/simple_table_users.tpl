[[ extends  TEMPLATE_PATH ~ "admin/main.tpl" ]]

[[ block left_content ]]
	<div id="left">
		[[ include TEMPLATE_PATH ~ "admin/blocks/leftmenu.tpl" ]]
		
		[[if mod.id==125]]
			[[ include TEMPLATE_PATH ~ "admin/blocks/filter_inteview.tpl" ]]
		[[endif]]
	</div>
[[endblock]]

[[block center]]

<h1>{mod.caption}</h1>
<br /><br />
[[if content]]
		{content}
		<BR><BR>
	[[ endif ]]
	
	[[if pages]]
		<div class="pager" >
		[[for i in 1..pages]]
			<span><a href="/admin/index.php?modul={request.modul}&page={i}" [[if i==page ]]class="active"[[endif]] title="Страница {i}" >{i}</a></span>  
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
				>{item[key]}</td>	
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