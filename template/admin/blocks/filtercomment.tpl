/*<div class="filters">
	Комментарии
	<ul class="filter-list" >
		<li><a href="/admin/?modul={mod['redir']}&comm_film_id={request.comm_film_id}" [[if not moderation]]class="active"[[endif]]>Все</a></li>
		<li><a href="/admin/?modul={mod['redir']}&active=0&comm_film_id={request.comm_film_id}" [[if moderation]]class="active"[[endif]] >На модерации</a></li>
	</ul>  
</div>
<div class="filters">
	Комментарии
	<form method="post" id="form_filter">
		<ul class="filter-list" >
			<li id="filter_selects">
				<select onChange="xajax_getParamsModul(this.value,1,this.value);" id="filter1" name="" style="width: 185px;">
					<option value="0"></option>
					[[for key,filter in filters]]
						<option value="{key}">{filter.name}</option>
					[[endfor]]
				</select>
				
			</li>
			<li>
				
			</li>
		</ul>  
	</form>
</div>*/