<div class="mbh" >
	<a class="big" href="{site_obj.getLinkPage(2)}">Новости</a>
</div>
[[for item in news_right_block]]
	<div class="altnews">
		[[if item.picture]]<a href="{item.full_url}"><img alt="" src="/{site_obj.fileDirectory}{item.id}/100_80_{item.picture}"></a>[[endif]]
		<div class="date">
			<span>{df('date','d.m.Y',item.date)}</span>
		</div>
		<a href="{item.full_url}">{item.caption}</a>
		<div style="clear:both;"></div>
	</div>
[[endfor]]
<div style="clear:both;"></div>