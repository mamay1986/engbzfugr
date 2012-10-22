[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]

	[[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]

	<div class="names">
		<span>{item.caption}</span>
	</div>
	<div class="story">
		[[if item.picture]]<img align="left" style="float:left;" src="/{interv_obj.fileDirectory}{item.id}/406__{item.picture}">[[endif]]
		<p class="date">{df('date','d.m.Y',item.dop_params.date)}</p>
		{item.text|raw}
		<div class="imglist">
			[[for photo in photos]]
				<a href="/images/galleries/{photo.id_catalog}/1024_{photo.image}" class="show" title="{photo.title}">
	            	<img src="/images/galleries/{photo.id_catalog}/thumbs/{photo.image}" alt="" />
	            </a>
			[[endfor]]
			<div style="clear:both;"></div>
		</div>
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