[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]

	[[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]

	<div class="names">
		<span>{item.caption}</span>
	</div>
	<div class="story">
		[[if item.picture]]
			<img alt="" src="/{news_obj.fileDirectory}{item.id}/406__{item.picture}" align="left" style="float:left;" />
		[[endif]] 
		[[if item.dop_params.date]]<p class="date">Дата добавления: {df('date','d.m.Y',item.dop_params.date)}</p>[[endif]]
		[[if item.dop_params.autor]]<p class="autor">Автор: {item.dop_params.autor}</p>[[endif]]
		{item.text|raw}
	</div>
	
	[[set tags = item.tags]]
	[[ include TEMPLATE_PATH ~ "blocks/tags.tpl"]]
	
	<div style="margin-bottom:20px;">
		[[ include TEMPLATE_PATH ~ "blocks/block_social_like.tpl"]]
	</div>
	
	[[ include TEMPLATE_PATH ~ "comments/main.tpl"]]
	
[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]