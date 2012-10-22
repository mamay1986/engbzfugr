[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]

	[[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]

	<div class="names">
		<span>{item.caption}</span>
	</div>
	<div class="story">
		{item.text|raw}
	</div>
	
	[[set tags = item.tags]]
	[[ include TEMPLATE_PATH ~ "blocks/tags.tpl"]]
	
	<div class="imglist fotoimg">
		[[for photo in photos]]
			[[if loop.index0>=gap.to and loop.index0<=gap.from]]
				<a href="/images/galleries/{photo.id_catalog}/1024_{photo.image}" class="show" title="{photo.title}">
	            	<img src="/images/galleries/{photo.id_catalog}/thumbs/{photo.image}" alt="" />
	            </a>
	        [[else]]
	        	<a href="/images/galleries/{photo.id_catalog}/1024_{photo.image}" class="show" title="{photo.title}"></a>
	        [[endif]]
		[[endfor]]
		
		[[ include TEMPLATE_PATH ~ "pager/pager.tpl"]]
		
		<div style="margin-bottom:20px;">
			[[ include TEMPLATE_PATH ~ "blocks/block_social_like.tpl"]]
		</div>
		
		[[ include TEMPLATE_PATH ~ "comments/main.tpl"]]
		<div style="clear:both;"></div>
	</div>
[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]