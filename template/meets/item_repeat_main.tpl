<div class="afishaitem item">
	<table>
		<tr>
			<td>
				[[if item.picture]]<a href="{item.full_url}"><img alt="{item.title}" src="/{meets_obj.fileDirectory}{item.id}/80_80_{item.picture}"></a>[[endif]]
			</td>
			<td>
				<div class="mbg8">
					<div class="name">
						<a href="{meets_obj.getLinkPage(item.parent)}"><span>{item.name_category}</span></a>
					</div>
					<div class="date f12">
						<span>{df('date','d.m.Y H:i',item.date)} [[if item.date_from]]- {df('date','d.m.Y H:i',item.date_from)}[[endif]]</span>
					</div>
				</div>	
				<a href="{item.full_url}">{item.caption}</a>
				<p>
					{item.anons|raw}
				</p>
			</td>
		</tr>
	</table>
	<div style="clear:both;"></div>									
</div>